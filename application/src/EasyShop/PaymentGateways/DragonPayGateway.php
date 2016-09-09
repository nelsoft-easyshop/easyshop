<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;
use EasyShop\Entities\EsAddress as EsAddress;

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

    /**
     * @var char
     */
    const STATUS_SUCCESS = "s";

    /**
     * @var char
     */
    const STATUS_PENDING = "p";

    /**
     * @var char
     */
    const STATUS_FAILURE = "f";


    private $merchantId  = 'EASYSHOP'; 
    private $merchantPwd = 'UT78W5VQ';  
    private $url;
    private $ps;
    private $client;

    private $errorCodes = array(
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
        );

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        parent::__construct($em, $request, $pointTracker, $paymentService, $params);
        if(!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production'){
        // LIVE
            $this->ps = "https://gw.dragonpay.ph/Pay.aspx";
        }
        else{
        // SANDBOX
            $this->ps = "http://test.dragonpay.ph/Pay.aspx"; 
        } 

        $this->client = get_instance()->kernel->serviceContainer['dragonpay_client'];
    }

    /**
     * Retrive Processors
     * 
     */
    public function getProcessors()
    { 
        $result = $this->client->call('GetProcessors');
        return $result['GetProcessorsResult']['ProcessorInfo'];
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
        $param = array(
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $txnId,
            'amount' => $amount,
            'ccy' => $ccy,
            'description' => $description,
            'email' => $email,
            'mode'=>'1'
            );
        
        $result = $this->client->call('GetTxnToken',$param);
        $token = $result['GetTxnTokenResult'];

        if(strlen($token) <= 3){
            return '{"e":"0","m":"'.$errorCodes[$token].'","c":"'.$token.'"}';
        }else{
            return '{"e":"1","m":"SUCCESS","c":"'.$token.'","tid":"'.$txnId.'","u":"'.$this->ps.'?tokenid='.$token.'&mode=7"}';
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
        $param = array(
                'merchantId' => $this->merchantId,
                'password' => $this->merchantPwd,
                'txnId' => $txnId
            );
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
        $param = array(
                'merchantId' => $this->merchantId,
                'password' => $this->merchantPwd,
                'merchantTxnId' => $txnId
            );
        $result = $this->client->call('CancelTransaction',$param);
        return $result['CancelTransactionResult'];
    }

    /**
     * Pay method for Dragon Pay Gateway Class
     * 
     * @param mixed $validatedCart
     * @param mixed $memberId Cart
     * @param mixed $paymentService
     */
    public function pay($validatedCart, $memberId, $paymentService)
    {
        header('Content-type: application/json');

        // Point Gateway
        $pointGateway = $paymentService->getPointGateway();

        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);
        $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->releaseAllLock($memberId);
        $productCount = count($validatedCart['itemArray']);

        if($validatedCart['itemCount'] !== $productCount){
            die('{"e":"0","m":"Item quantity not available."}');
        }

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')->find(intval($memberId));

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getShippingAddress(intval($memberId));

        // Compute shipping fee
        $pointSpent = $pointGateway ? $pointGateway->getParameter('amount') : "0";
        $prepareData = $paymentService->computeFeeAndParseData($validatedCart['itemArray'], intval($address));
        $grandTotal = $prepareData['totalPrice'];
        $productString = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $name = $prepareData['productName'];

        $this->setParameter('amount', $grandTotal);

        $txnid = $this->generateReferenceNumber($memberId);
        $dpReturn = $this->getTxnToken($grandTotal, $name, $member->getEmail(), $txnid);
        $dpReturnArray = json_decode($dpReturn);

        $return = $paymentService->persistPayment(
                $grandTotal, 
                $memberId, 
                $productString, 
                $productCount, 
                json_encode($itemList),
                $txnid,
                $this
                );

        if($return['o_success'] <= 0){
           die('{"e":"0","m":"'.$return['o_message'].'"}');  
        }
        else{ 
            $orderId = $return['v_order_id'];
            $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->insertLockItem($orderId, $toBeLocked); 
            $order = $this->em->getRepository('EasyShop\Entities\EsOrder')->find($orderId);
            $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                        ->find($this->getParameter('paymentType'));

            $paymentRecord = new EsPaymentGateway();
            $paymentRecord->setAmount($this->getParameter('amount'));
            $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
            $paymentRecord->setOrder($order);
            $paymentRecord->setPaymentMethod($paymentMethod);
            $this->em->persist($paymentRecord);

            if($pointGateway){
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
            $this->em->flush(); 
            exit($dpReturn);
        }
    }

    /**
     * Postback callback method
     *
     * Dragonpay's PostBack URL is routed to here
     * 
     * @param mixed $paymentService
     * @param mixed $params
     */
    public function postBackMethod($paymentService, $paymentController, $params)
    {
        extract($params);
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
                            ->getShippingAddress(intval($memberId));

        // Compute shipping fee
        $prepareData = $paymentService->computeFeeAndParseData($itemList, intval($address));
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];

        if(strtolower($status) === self::STATUS_PENDING || strtolower($status) == self::STATUS_SUCCESS){
            if(!$postBackCount){
                foreach ($itemList as $key => $value) {     
                    $itemComplete = $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                }
                $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->deleteLockItem($orderId, $toBeLocked); 
            }
            $orderStatus = (strtolower($status) === self::STATUS_SUCCESS ? EsOrderStatus::STATUS_PAID : EsOrderStatus::STATUS_DRAFT); 
            $complete = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                    ->updatePaymentIfComplete($orderId,json_encode($itemList),$txnId,$paymentType,$orderStatus,0);

            if(!$postBackCount){
                // send email to buyer
                $paymentController->sendNotification(array('member_id'=>$memberId, 'order_id'=>$orderId, 'invoice_no'=>$invoice),TRUE,FALSE);
            }

            if(strtolower($status) === self::STATUS_SUCCESS){
                // send email to sender
                $paymentController->sendNotification(array('member_id'=>$memberId, 'order_id'=>$orderId, 'invoice_no'=>$invoice),FALSE,TRUE);
            }

        }
        elseif(strtolower($status) === self::STATUS_FAILURE){
            $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->deleteLockItem($orderId, $toBeLocked);
            $orderId = get_instance()->kernel->serviceContainer['transaction_service']->cancelTransaction($txnId, true);
            $orderHistory = array(
                'order_id' => $orderId,
                'order_status' => 2,
                'comment' => 'Dragonpay transaction failed: ' . $message
                );
            $this->em->getRepository('EasyShop\Entities\EsOrderHistory')->addOrderHistory($orderHistory);
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

        extract($params);

        $response['txnId'] = $txnId;

        if(strtolower($status) == self::STATUS_PENDING || strtolower($status) == self::STATUS_SUCCESS){
            $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                ->findOneBy(['transactionId' => $txnId, 'paymentMethod' => $paymentType]);
            $orderId = $return->getIdOrder();
            $response['status'] = self::STATUS_SUCCESS;
            $response['message'] = 'Your payment has been completed through Dragon Pay. '.urldecode($message);  
        }
        else{
            $response['status'] = self::STATUS_FAILURE;
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

