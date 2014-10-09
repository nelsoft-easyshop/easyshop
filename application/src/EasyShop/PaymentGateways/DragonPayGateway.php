<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;
use EasyShop\Entities\EsAddress as EsAddress;
use application\libraries\NuSOAP\lib\nusoap as nusoap;

class DragonPayGateway extends AbstractGateway
{

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

        $this->client = get_instance()->kernel->serviceContainer['nusoap_client'];
    }

    public function getProcessors()
    { 
        $result = $this->client->call('GetProcessors');
        return $result['GetProcessorsResult']['ProcessorInfo'];
    }

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

    public function pay($validatedCart, $memberId, $paymentService)
    {
        header('Content-type: application/json');

        // Point Gateway
        $pointGateway = $paymentService->getPointGateway();

        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);
        // $remove = $this->payment_model->releaseAllLock($member_id);

        $productCount = count($validatedCart['itemArray']);
        $name = "";

        if($validatedCart['itemCount'] !== $productCount){
            die('{"e":"0","m":"Item quantity not available."}');
        }

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->find(intval($memberId));

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
            //$locked = $this->lockItem($toBeLocked,$orderId,'insert');  
            exit($dpReturn);
        }
    }

    //$txnId, $refNo, $status, $message, $digest, $paymentService
    public function postBackMethod($paymentService, $params)
    {
        extract($params);
        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);

        $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                ->findOneBy(['transactionId' => $token, 'paymentMethod' => $paymentType]);

        $invoice = $return->getInvoiceNo();
        $orderId = $return->getIdOrder();
        $memberId = $return->getBuyer();
        $itemList = json_decode($return->getDataResponse(), true);
        $postBackCount = $return->getPostbackcount();

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                    ->getShippingAddress(intval($memberId));

        // Compute shipping fee
        $prepareData = $paymentService->computeFeeAndParseData($itemList, intval($address));
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];

        if(strtolower($status) == "p" || strtolower($status) == "s"){
            if(!$postBackCount){
                foreach ($itemList as $key => $value) {     
                    $itemComplete = $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                }
                //$locked = $this->lockItem($toBeLocked,$orderId,'delete'); 
            }
            $orderStatus = (strtolower($status) == "s" ? 0 : 99); 
            $complete = $this->em->getRepository('EasyShop\Entities\EsOrder')
                            ->updatePaymentIfComplete($orderId,json_encode($itemList),$txnId,$paymentType,$orderStatus,0);

            if(!$postBackCount){
                //$remove_to_cart = $this->payment_model->removeToCart($member_id,$itemList);
                //$this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));  
            }

        }
        elseif(strtolower($status) == "f"){
            //$locked = $this->lockItem($toBeLocked,$orderId,'delete');
            //$orderId = $this->payment_model->cancelTransaction($txnId,true);
            $orderHistory = array(
                'order_id' => $orderId,
                'order_status' => 2,
                'comment' => 'Dragonpay transaction failed: ' . $message
                );
            $this->em->getRepository('EasyShop\Entities\EsOrderHistory')
                    ->addOrderHistory($orderHistory);
        }

    }

    public function returnMethod($params)
    {
        // paymentType
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $this->setParameter('paymentType', $paymentType);

        extract($params);

        $response['txnId'] = $txnId;

        if(strtolower($status) == "p" || strtolower($status) == "s"){
            $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                ->findOneBy(['transactionId' => $txnId, 'paymentMethod' => $paymentType]);
            $orderId = $return->getIdOrder();
            $response['status'] = 's';
            $response['message'] = 'Your payment has been completed through Dragon Pay. '.urldecode($message);  
            //$this->removeItemFromCart(); 
        }
        else{
            $response['status'] = 'f';
            $response['message'] = 'Transaction Not Completed. '.urldecode($message);
        }
        
        return $response;
    }

    public function getExternalCharge()
    {
        return 20.00;
    }

    public function generateReferenceNumber($memberId)
    {
        return 'DPY-'.date('ymdhs').'-'.$memberId;
    }

    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_DRAFT;
    }

    public function getOrderProductStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }   
}