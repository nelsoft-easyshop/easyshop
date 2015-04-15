<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsOrderProductStatus as EsOrderProductStatus;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;
use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\PaymentService\PaymentService as PaymentService;


/**
 * Pesopay Gateway Class
 *
 * Params needed:
 *      method:"PesoPayCreditCard"
 */
class PesoPayGateWay extends AbstractGateway
{
    private $merchantId;
    private $redirectUrl;
    private $secureHash;
    private $payType;
    private $currencyCode;

    const SUCCESS_CODE = 0;

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
        $config = $configLoad['payment_type']['pesopay']['Easyshop'];
        $this->merchantId = $config['merchant_id'];
        $this->redirectUrl = $config['redirect_url'];
        $this->secureHash = $config['secure_hash'];
        $this->payType = $config['payment_type'];
        $this->currencyCode = $config['currency_code'];
    }

    /**
     * Pay method for Pesopay Gateway Class
     * 
     * @param mixed $validatedCart
     * @param mixed $memberId Cart
     */
    public function pay($validatedCart, $memberId)
    {
        // Point Gateway
        $pointGateway = $this->paymentService->getPointGateway();

        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_PESOPAYCC;
        $this->setParameter('paymentType', $paymentType);
        $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                 ->releaseAllLock($memberId);
        $productCount = count($validatedCart['itemArray']); 

        if($validatedCart['itemCount'] !== $productCount){
            return [
                'error' => true,
                'message' => "Item quantity not available.",
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

        $txnid = $this->generateReferenceNumber($memberId);  
        $this->setParameter('amount', $grandTotal);

        if($grandTotal < 50.00){
            return [
                'error' => true,
                'message' => 'We only accept payments of at least PHP 50.00 in total value.'
            ];
        }

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
                'error' => true,
                'message' => $return['o_message'],
            ];
        }
        else{
            $orderId = $return['v_order_id'];
            $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                     ->insertLockItem($orderId, $toBeLocked); 

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

            $secureHash = $this->generateSecureHash($txnid, $grandTotal);

            return [
                'error' => false,
                'message' => '',
                'merchantId' => $this->merchantId,
                'amount' => $grandTotal,
                'orderRef' => $txnid,
                'redirectUrl' => $this->redirectUrl,
                'secureHash' => $secureHash,
                'currCode' => $this->currencyCode,
                'payType' => $this->payType,
            ];
        }
    }

    /**
     * Return callback method
     *
     * Pesopay's Return URL is routed to here
     * 
     * @param mixed $params
     */
    public function returnMethod($params)
    {
        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_PESOPAYCC;

        $txnId = $response['txnId'] = $params['ref'];
        $status = $params['status'];
        $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                          ->findOneBy([
                               'transactionId' => $txnId,
                               'paymentMethod' => $paymentType
                           ]); 
        if($order 
            && strtolower($status) === PaymentService::STATUS_SUCCESS){
            $response['status'] = PaymentService::STATUS_SUCCESS;
            $response['message'] = 'Your payment has been processed through Pesopay Credit/Debit Card. Once validated you will receive an email regarding the status of your purchase.';
        }
        else{
            $response['status'] = PaymentService::STATUS_FAIL;
            $response['message'] = 'Transaction Not Completed.';
        }

        return $response;
    } 

    /**
     * Postback callback method
     *
     * Pesopay's PostBack URL is routed to here
     * 
     * @param mixed $params
     */
    public function postBackMethod($params)
    {
        $txnId = $params['txnId'];
        $successCode = $params['successcode'];
        $paymentType = EsPaymentMethod::PAYMENT_PESOPAYCC;
        $this->setParameter('paymentType', $paymentType);

        $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                          ->findOneBy([
                               'transactionId' => $txnId,
                               'paymentMethod' => $paymentType
                           ]);

        if($this->validateSecureHash($params)){
            if($order){
                $orderId = $order->getIdOrder();
                $memberId = $order->getBuyer()->getIdMember();
                $itemList = json_decode($order->getDataResponse(), true);
                $postBackCount = $order->getPostbackcount();

                // get address Id
                $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                    ->getShippingAddress((int)$memberId);

                // Compute shipping fee
                $prepareData = $this->paymentService
                                    ->computeFeeAndParseData(
                                        $itemList,
                                        (int)$address
                                    );
                $itemList = $prepareData['newItemList'];
                $toBeLocked = $prepareData['toBeLocked'];
                $successCode = (int)trim($successCode);
                if($successCode === self::SUCCESS_CODE){ 
                    foreach ($itemList as $item){
                        $itemComplete = $this->paymentService
                                             ->productManager
                                             ->deductProductQuantity(
                                                 $item['id'],
                                                 $item['product_itemID'],
                                                 $item['qty']
                                             );
                        $this->paymentService->productManager
                                             ->updateSoldoutStatus($item['id']);
                    }
                    $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                             ->deleteLockItem($orderId, $toBeLocked); 
                    $complete = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                         ->updatePaymentIfComplete(
                                                $orderId,
                                                json_encode($itemList),
                                                $txnId,
                                                $paymentType,
                                                EsOrderStatus::STATUS_PAID
                                            );
                    $this->paymentService->sendPaymentNotification($orderId);
                    $orderHistory = [
                        'order_id' => $orderId,
                        'order_status' => EsOrderStatus::STATUS_PAID,
                        'comment' => 'Pesopay Payment confirmed'
                    ];
                    $this->em->getRepository('EasyShop\Entities\EsOrderHistory')
                             ->addOrderHistory($orderHistory);
                }
                else{
                    $order->setPostbackcount(bcadd($order->getPostbackcount(), 1));
                    $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                             ->deleteLockItem($orderId, $toBeLocked); 
                    $this->em->flush();
                }
            }
        }
    }

    /**
     * Validate secure hash passed by pesopay
     * @param  mixed $parameters
     * @return boolean
     */
    private function validateSecureHash($parameters)
    {
        $dataCollection = [
            $parameters['src'],
            $parameters['prc'],
            $parameters['successcode'],
            $parameters['Ref'],
            $parameters['PayRef'],
            $parameters['Cur'],
            $parameters['Amt'],
            $parameters['payerAuth'],
            $this->secureHash
        ];

        $generatedHash = (string) sha1(implode('|', $dataCollection));

        return (string) $parameters['secureHash'] === $generatedHash;
    }

    /**
     * generate secure hash
     * @param  string  $referenceNumber 
     * @param  integer $amount 
     * @return string
     */
    private function generateSecureHash($referenceNumber, $amount)
    {
        $dataCollection = [
            $this->merchantId,
            $referenceNumber,
            $this->currencyCode,
            $amount,
            $this->payType,
            $this->secureHash,
        ];

        return sha1(implode('|', $dataCollection));
    }

    /**
     * External Charge for Pesopay
     * @return string
     */
    public function getExternalCharge()
    {
        $transactionFee = bcadd("6.00", bcmul("0.0375", $this->getParameter('amount'), 4), 4);
        $vat = bcmul($transactionFee, "0.12", 4);

        return bcadd($transactionFee, $vat, 4);
    }

    /**
     * Generate Reference Number for Pesopay
     *
     * 
     * @return string
     */
    public function generateReferenceNumber($memberId)
    {
        return 'PPY-'.date('ymdhs').'-'.$memberId;
    }

    /**
     * Returns Order Status for Pesopay
     *
     * 
     * @return int
     */
    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_DRAFT;
    }

    /**
     * Returns Order Product Status for Pesopay
     *
     * 
     * @return int
     */
    public function getOrderProductStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }
}

