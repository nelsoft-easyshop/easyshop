<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;

class PayPalGateway extends AbstractGateway
{

    private $PayPalMode; 
    private $PayPalApiUsername; 
    private $PayPalApiPassword; 
    private $PayPalApiSignature;

    public function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        parent::__construct($em, $request, $pointTracker, $paymentService, $params);
        
        if(strtolower(ENVIRONMENT) === 'production'){
            // LIVE
            $this->PayPalMode             = ''; 
            $this->PayPalApiUsername      = 'admin_api1.easyshop.ph'; 
            $this->PayPalApiPassword      = 'GDWFS6D9ACFG45E7'; 
            $this->PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Adro7yAfl2NInYAAVfFFipJ-QQhT'; 
        }
        else{
            // SANDBOX
            $this->PayPalMode             = 'sandbox'; 
            $this->PayPalApiUsername      = 'easyseller_api1.yahoo.com'; 
            $this->PayPalApiPassword      = '1396000698'; 
            $this->PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Au1bGvwwVcv0garAliLq12YWfivG';
        }
    }

    public function getMode()
    {
        $API_Mode = urlencode($this->PayPalMode);
        
        if($API_Mode === 'sandbox'){
            $paypalMode = '.sandbox';
        }
        else{
            $paypalMode = '';
        }

        return $paypalMode;
    }

    private function PPHttpPost($methodName, $nvpStr)
    {
        // Set up API credentials
        $API_UserName = urlencode($this->PayPalApiUsername);
        $API_Password = urlencode($this->PayPalApiPassword);
        $API_Signature = urlencode($this->PayPalApiSignature);
        
        $API_Endpoint = "https://api-3t".$this->getMode().".paypal.com/nvp";
        $version = urlencode('98.0');

        // set curl parameters
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // turn off server and peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server
        $httpResponse = curl_exec($ch);

        if(!$httpResponse) {
            exit("$methodName failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }

        // Extract the response detail
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if((0 === sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        return $httpParsedResponseAr;
    }

    // setExpressCheckout
    public function pay($validatedCart, $memberId, $paymentService)
    {
        header('Content-type: application/json');
        if(!$memberId){
            redirect(base_url().'home', 'refresh');
        }

        // Point Gateway
        $pointGateway = $paymentService->getPointGateway();

        $PayPalMode = $this->getMode(); 
        // $paypalReturnURL = base_url().'pay/paypal'; 
        $paypalReturnURL = base_url().'pay/postBackPayPal'; 
        $paypalCancelURL = base_url().'payment/review'; 
        // RELEASE ALL LOCK
        // $remove = $this->payment_model->releaseAllLock($member_id);

        $productCount = count($validatedCart['itemArray']);
        $cnt = 0;
        $paypalType = $this->getParameter('type');
        $dataitem = '';
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;

        // paymentType
        $this->setParameter('paymentType', $paymentType);

        if($productCount <= 0){
            echo '{"e":"0","d":"There are no items in your cart."}';
            exit();
        }

        if($validatedCart['itemCount'] !== $productCount){
            echo '{"e":"0","d":"One of the items in your cart is unavailable."}';
            exit();
        } 

        $shippingAddress = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                ->findOneBy(['idMember'=>$memberId, 'type'=>'1']);

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->find(intval($memberId));

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                    ->getShippingAddress(intval($memberId));

        $name = $shippingAddress->getConsignee();
        $street = $shippingAddress->getAddress();
        $cityDescription = $shippingAddress->getCity()->getLocation();
        $email = $member->getEmail();
        $telephone = $shippingAddress->getTelephone();
        $regionDesc = $shippingAddress->getStateregion()->getLocation();

        // Compute shipping fee
        $pointSpent = $pointGateway ? $pointGateway->getParameter('amount') : "0";
        $prepareData = $paymentService->computeFeeAndParseData($validatedCart['itemArray'], intval($address));
        
        // Persist point credit for postback method
        $userPoints = $this->em->getRepository('EasyShop\Entities\EsPoint')
                            ->findOneBy(["member" => intval($memberId)]);

        $userPoints->setCreditPoint(intval($pointSpent));
        $this->em->flush();

        $shipping_amt = round(floatval($prepareData['othersumfee']),2);
        $itemTotalPrice = round(floatval($prepareData['totalPrice']),2) - $shipping_amt;
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $grandTotal= $itemTotalPrice+$shipping_amt; 
        $thereIsPromote = $prepareData['thereIsPromote'];
        $this->setParameter('amount', $grandTotal);

        if($thereIsPromote <= 0 && $grandTotal < '50'){
            die('{"e":"0","d":"We only accept payments of at least PHP 50.00 in total value."}');
        }

        foreach ($itemList as $key => $value) {
            $value['price'] = round(floatval($value['price']),2);
            $dataitem .= '&L_PAYMENTREQUEST_0_QTY'.$cnt.'='. urlencode($value['qty']).
            '&L_PAYMENTREQUEST_0_AMT'.$cnt.'='.urlencode($value['price']).
            '&L_PAYMENTREQUEST_0_NAME'.$cnt.'='.urlencode($value['name']).
            '&L_PAYMENTREQUEST_0_NUMBER'.$cnt.'='.urlencode($value['id']).
            '&L_PAYMENTREQUEST_0_DESC'.$cnt.'=' .urlencode($value['brief']);
            $cnt++;
        }

        $padata =   
        '&RETURNURL='.urlencode($paypalReturnURL).
        '&CANCELURL='.urlencode($paypalCancelURL).
        '&PAYMENTACTION=Sale'. 
        '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode('PHP').
        '&CURRENCYCODE='.urlencode('PHP').
        $dataitem. 
        '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($itemTotalPrice).   
        '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_amt).
        '&PAYMENTREQUEST_0_AMT='.urlencode($grandTotal).
        '&SOLUTIONTYPE='.urlencode('Sole').
        '&ALLOWNOTE=0'.
        '&NOSHIPPING=1'.
        '&PAYMENTREQUEST_0_SHIPTONAME='.urlencode($name).
        '&PAYMENTREQUEST_0_SHIPTOSTREET='.urlencode($street).
        '&PAYMENTREQUEST_0_SHIPTOCITY='.urlencode($cityDescription).
        '&PAYMENTREQUEST_0_SHIPTOSTATE='.urlencode($regionDesc).
        '&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=PH'.
        '&PAYMENTREQUEST_0_SHIPTOZIP='.urlencode('').
        '&PAYMENTREQUEST_0_EMAIL='.urlencode($email).
        '&EMAIL='.urlencode($email).
        '&PAYMENTREQUEST_0_SHIPTOPHONENUM='.urlencode($telephone);

        $padata .= ($paypalType == 2 ? '&LANDINGPAGE='.urlencode('Billing') : '&LANDINGPAGE='.urlencode('Login'));

        // var_dump($padata);
        //die();

        $httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata);
        if("SUCCESS" === strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" === strtoupper($httpParsedResponseAr["ACK"]))
        {   
            $transactionID = urldecode($httpParsedResponseAr["TOKEN"]);
            $return = $paymentService->persistPayment(
                $grandTotal, 
                $memberId, 
                $productstring, 
                $productCount, 
                json_encode($itemList),
                $transactionID,
                $this
                );

            if($return['o_success'] > 0){
                $orderId = $return['v_order_id'];
                //$locked = $this->lockItem($toBeLocked,$orderId,'insert');
                $paypalurl ='https://www'.$PayPalMode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$transactionID.'';

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

                if($pointGateway !== NULL){
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


                die('{"e":"1","d":"'.$paypalurl.'"}');
            }
            else{
                die('{"e":"0","d":"'.$return['o_message'].'"}');
            }        
        }
        else{
            die('{"e":"0","d":"'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'"}');
        }
    }

    public function postBackMethod($validatedCart, $memberId, $paymentService)
    {
        // Point Gateway
        $pointGateway = $paymentService->getPointGateway();

        $getItems = $this->getParameter("getArray");

        $response['status'] = 'f';
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
        $apiResponse = $productstring = ''; 

        $productCount = count($validatedCart['itemArray']);

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                    ->getShippingAddress(intval($memberId));

        if(array_key_exists('token',$getItems) && array_key_exists('PayerID',$getItems)){
            $payerid = $getItems['PayerID'];
            $token = $getItems['token'];
            //$return = $this->payment_model->selectFromEsOrder($token,$paymentType);
            $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                ->findOneBy(['transactionId' => $token, 'paymentMethod' => $paymentType]);

            $invoice = $return->getInvoiceNo();
            $orderId = $return->getIdOrder();

            // Compute shipping fee
            $prepareData = $paymentService->computeFeeAndParseData($validatedCart['itemArray'], intval($address));

            $itemList = $prepareData['newItemList'];
            $grandTotal = $prepareData['totalPrice'];
            $productstring = $prepareData['productstring']; 
            $toBeLocked = $prepareData['toBeLocked'];
            // $lockCountExist = $this->payment_model->lockcount($orderId);
            $lockCountExist = 10;

            if($lockCountExist >= 1){
                // $locked = $this->lockItem($toBeLocked,$orderId,'delete');
                if($validatedCart['itemCount'] === $productCount){

                    $padata = '&TOKEN='.urlencode($token).
                    '&PAYERID='.urlencode($payerid).
                    '&PAYMENTACTION='.urlencode("SALE").
                    '&AMT='.urlencode($grandTotal).
                    '&CURRENCYCODE='.urlencode('PHP');

                    $httpParsedResponseArGECD = $this->PPHttpPost('GetExpressCheckoutDetails', $padata); 
                    $httpParsedResponseArDECP = $this->PPHttpPost('DoExpressCheckoutPayment', $padata); 

                    if(("SUCCESS" == strtoupper($httpParsedResponseArDECP["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseArDECP["ACK"])) && ("SUCCESS" == strtoupper($httpParsedResponseArGECD["ACK"]))){
                        $response['txnid'] = $txnid = urldecode($httpParsedResponseArDECP["TRANSACTIONID"]);
                        $nvpStr = "&TRANSACTIONID=".$txnid;

                        $httpParsedResponseAr = $this->PPHttpPost('GetTransactionDetails', $nvpStr);

                        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){

                            # START SAVING TO DATABASE HERE 
                            foreach ($itemList as $key => $value) {     
                                $itemComplete = $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                                $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                            }

                            $flag = ($httpParsedResponseAr['PAYMENTSTATUS'] == 'Pending' ? 1 : 0);
                            //$complete = $this->payment_model->updatePaymentIfComplete($orderId,json_encode($itemList),$txnid,$paymentType,0,$flag);
                            $complete = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                                ->updatePaymentIfComplete($orderId,json_encode($itemList),$txnid,$paymentType,0,$flag);

                            if($complete){
                                $orderHistory = array(
                                    'order_id' => $orderId,
                                    'order_status' => 0,
                                    'comment' => 'Paypal transaction confirmed'
                                    );
                                // $this->payment_model->addOrderHistory($orderHistory); 
                                $this->em->getRepository('EasyShop\Entities\EsOrderHistory')
                                                ->addOrderHistory($orderHistory);
                                $response['message'] = 'Your payment has been completed through Paypal';
                                $response['status'] = 's';
                                // $this->removeItemFromCart(); 
                                // $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));
                            }
                            else{
                                $response['message'] = 'Someting went wrong. Please contact us immediately. Your EASYSHOP INVOICE NUMBER: '.$invoice.'</div>'; 
                            }
                        }
                        else{
                            $response['message'] = urldecode($httpParsedResponseArDECP["L_LONGMESSAGE0"]);
                        }
                    }
                    else{
                        $response['message'] = urldecode($httpParsedResponseArDECP["L_LONGMESSAGE0"]);
                    }
                }
                else{
                    $response['message'] = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
                }
            }
            else{
                $response['message'] = 'Your session is already expired for this payment.';
            }
        }
        else{
            $response['message'] = 'Some parameters are missing.';
        }

        // echo "SUCCESSSSSSSSSSSSSSS";
        return $response;
        // $this->generateFlash($txnid,$message,$status);
        // redirect(base_url().'payment/success/paypal?txnid='.$txnid.'&msg='.$message.'&status='.$status, 'refresh'); 
    }

    public function getExternalCharge()
    {
        return ($this->getParameter('amount') * 0.044) + 15; 
    }

    public function generateReferenceNumber($memberId){}

    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_DRAFT;
    }

    public function getOrderProductStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }
}

/*
    Params needed
        method:"PayPal", 
        type:$(this).data('type')
*/