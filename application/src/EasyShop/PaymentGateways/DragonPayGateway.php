<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;
use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\PaymentService\PaymentService as PaymentService;

/**
 * Dragon Pay Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 *
 *
 * Params needed
 *      method:"DragonPay"
 */
class DragonPayGateway extends AbstractGateway
{

    private $merchantId; 
    private $merchantPwd;
    private $redirectUrl;
    private $client;

    private $errorCodes = [
        '000' => 'SUCCESS',
        '101' => 'Ivalid payment gateway id',
        '102' => 'Incorrect secret key',
        '103' => 'Invalid reference number',
        '104' => 'Unauthorized access',
        '105' => 'Invalid token',
        '106' => 'Currency not supported',
        '107' => 'Transaction cancelled',
        '108' => 'Insufficient funds',
        '109' => 'Transaction limit exceeded',
        '110' => 'Error in operation',
        '111' => 'Invalid parameters',
        '201' => 'Invalid Merchant Id',
        '202' => 'Invalid Merchant Password'
    ];

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        parent::__construct($em, $request, $pointTracker, $paymentService, $params);

        if(!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production'){ 
            $configLoad = $this->paymentService->configLoader->getItem('payment','production'); 
        }
        else{ 
            $configLoad = $this->paymentService->configLoader->getItem('payment','testing'); 
        }
        $config = $configLoad['payment_type']['dragonpay']['Easyshop'];

        $this->redirectUrl = $config['redirect_url'];
        $this->merchantId = $config['merchant_id'];
        $this->merchantPwd = $config['merchant_password']; 
        $this->client = $this->paymentService->dragonPaySoapClient;
    }

    /**
     * Retrieve Transaction Token
     * 
     * @param float $amount
     * @param string $description
     * @param string $email
     * @param string $txnId
     * @return mixed
     */
    public function getTxnToken($amount,$description,$email,$txnId)
    {
        $errorCodes = $this->errorCodes;
        $ccy = 'PHP';
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $txnId,
            'amount' => $amount,
            'ccy' => $ccy,
            'description' => $description,
            'email' => $email,
            'param1' => 'Easyshop',
        ];
        
        $result = $this->client->call('GetTxnToken',$param);
        $token = $result['GetTxnTokenResult'];

        if(strlen($token) <= 3){
            return [
                'e' => false,
                'm' => $errorCodes[$token],
                'c' => $token,
            ];
        }
        else{
            return [
                'e' => true,
                'm' => "SUCCESS",
                'c' => $token,
                'tid' => $txnId,
                'u' => $this->redirectUrl.'?tokenid='.$token.'&mode=7',
            ];
        }
    }

    /**
     * Retrieve Status
     * 
     * @param mixed $txnId
     * @return mixed
     */
    public function getStatus($txnId)
    { 
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'txnId' => $txnId
        ];
        $result = $this->client->call('GetTxnStatus',$param);
        
        return $result['GetTxnStatusResult'];
    }

    /**
     * Voids Transaction
     * 
     * @param mixed $txnId
     * @return mixed
     */
    public function voidTransaction($txnId)    
    {
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $txnId
        ];
        $result = $this->client->call('CancelTransaction',$param);

        return $result['CancelTransactionResult'];
    }

    /**
     * Pay method for Dragon Pay Gateway Class
     * 
     * @param mixed $validatedCart
     * @param mixed $memberId Cart
     */
    public function pay($validatedCart, $memberId)
    {
        // Point Gateway
        $pointGateway = $this->paymentService->getPointGateway();

        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);

        $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->releaseAllLock($memberId);

        $productCount = count($validatedCart['itemArray']); 

        if($validatedCart['itemCount'] !== $productCount){
            return [
                'e' => false,
                'm' => "Item quantity not available.",
            ];
        }

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find((int)$memberId);

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getShippingAddress((int)$memberId);

        // Compute shipping fee
        $pointSpent = $pointGateway ? $pointGateway->getParameter('amount') : "0";
        $prepareData = $this->paymentService->computeFeeAndParseData($validatedCart['itemArray'], (int)$address);
        $grandTotal = $prepareData['totalPrice'];
        $productString = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $name = $prepareData['productName'];

        $txnid = $this->generateReferenceNumber($memberId);
        $dpReturn = $this->getTxnToken($grandTotal, $name, $member->getEmail(), $txnid);
        $this->setParameter('amount', $grandTotal);

        $return = $this->persistPayment(
            $grandTotal, 
            $memberId, 
            $productString, 
            $productCount, 
            json_encode($itemList),
            $txnid,
            $this
        );

        if($return['o_success'] <= 0){ 
            return [
                'e' => false,
                'm' => $return['o_message'],
            ];
        }
        else{ 
            $orderId = $return['v_order_id'];
            $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->insertLockItem($orderId, $toBeLocked); 

            $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                              ->find($orderId);

            $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                      ->find($this->getParameter('paymentType'));

            $paymentRecord = new EsPaymentGateway();
            $paymentRecord->setAmount($this->getParameter('amount'));
            $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
            $paymentRecord->setOrder($order);
            $paymentRecord->setPaymentMethod($paymentMethod);

            $this->em->persist($paymentRecord);
            $this->em->flush(); 

            if($pointGateway !== null){
                $pointGateway->setParameter('memberId', $memberId);
                $pointGateway->setParameter('itemArray', $return['item_array']);

                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                          ->find($pointGateway->getParameter('paymentType'));

                $trueAmount = $pointGateway->pay();

                $paymentRecord = new EsPaymentGateway();
                $paymentRecord->setAmount($trueAmount);
                $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $paymentRecord->setOrder($order);
                $paymentRecord->setPaymentMethod($paymentMethod);

                $this->em->persist($paymentRecord);
                $this->em->flush();
            }

            return $dpReturn;
        }
    }

    /**
     * Postback callback method
     *
     * Dragonpay's PostBack URL is routed to here
     * 
     * @param mixed $params
     */
    public function postBackMethod($params)
    {
        $txnId = $params['txnId'];
        $status = $params['status'];
        $message = $params['message']; 

        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);

        $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                           ->findOneBy(['transactionId' => $txnId, 'paymentMethod' => $paymentType]);

        $invoice = $return->getInvoiceNo();
        $orderId = $return->getIdOrder();
        $memberId = $return->getBuyer()->getIdMember();
        $itemList = json_decode($return->getDataResponse(), true);
        $postBackCount = $return->getPostbackcount();

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getShippingAddress((int)$memberId);

        // Compute shipping fee
        $prepareData = $this->paymentService->computeFeeAndParseData($itemList, (int)$address);
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];

        if(strtolower($status) === PaymentService::STATUS_PENDING || strtolower($status) === PaymentService::STATUS_SUCCESS){
            if(!$postBackCount){
                foreach ($itemList as $key => $value) {     
                    $itemComplete = $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                }
                $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                         ->deleteLockItem($orderId, $toBeLocked); 
            }
            $orderStatus = strtolower($status) === PaymentService::STATUS_SUCCESS ? EsOrderStatus::STATUS_PAID : EsOrderStatus::STATUS_DRAFT; 
            $complete = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                 ->updatePaymentIfComplete($orderId,json_encode($itemList),$txnId,$paymentType,$orderStatus);
        }
        elseif(strtolower($status) === PaymentService::STATUS_FAIL){
            $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->deleteLockItem($orderId, $toBeLocked);
            $orderHistory = [
                'order_id' => $orderId,
                'order_status' => EsOrderStatus::STATUS_VOID,
                'comment' => 'Dragonpay transaction failed: ' . $message
            ];
            $this->em->getRepository('EasyShop\Entities\EsOrderHistory')
                     ->addOrderHistory($orderHistory);
        }
    }

    /**
     * Return callback method
     *
     * Dragonpay's Return URL is routed to here
     * 
     * @param mixed $params
     */
    public function returnMethod($params)
    {
        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);

        $txnId = $response['txnId'] = $params['txnId'];
        $status = $params['status'];
        $message = $params['message'];

        if(strtolower($status) === PaymentService::STATUS_PENDING || strtolower($status) === PaymentService::STATUS_SUCCESS){
            $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                               ->findOneBy(['transactionId' => $txnId, 'paymentMethod' => $paymentType]);
            $orderId = $return->getIdOrder();
            $response['status'] = PaymentService::STATUS_SUCCESS;
            $response['message'] = 'Your payment has been completed through Dragon Pay. '.urldecode($message);
        }
        else{
            $response['status'] = PaymentService::STATUS_FAIL;
            $response['message'] = 'Transaction Not Completed. '.urldecode($message);
        }
      
        return $response;
    }


    /**
     * External Charge for Dragonpay
     *
     * 
     * @return int
     */
    public function getExternalCharge()
    {
        return 20.00;
    }

    /**
     * Generate Reference Number for Dragonpay
     *
     * 
     * @return string
     */
    public function generateReferenceNumber($memberId)
    {
        return 'DPY-'.date('ymdhs').'-'.$memberId;
    }

    /**
     * Returns Order Status for Dragonpay
     *
     * 
     * @return int
     */
    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_DRAFT;
    }

    /**
     * Returns Order Product Status for Dragonpay
     *
     * 
     * @return int
     */
    public function getOrderProductStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }   
}

