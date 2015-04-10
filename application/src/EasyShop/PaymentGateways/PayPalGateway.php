<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;
use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\PaymentService\PaymentService as PaymentService;
use EasyShop\Entities\EsOrderProductStatus as EsOrderProductStatus;


/**
 * Paypal Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 *
 * Params needed:
 *      method:"PayPal"
 *      type:$(this).data('type')
 */
class PayPalGateway extends AbstractGateway
{

    private $type_creditcard = 2;
    private $PayPalMode; 
    private $PayPalApiUsername; 
    private $PayPalApiPassword; 
    private $PayPalApiSignature;
    private $returnUrl;
    private $cancelUrl;
    private $lowestAmount;

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
        $config = $configLoad['payment_type']['paypal']['Easyshop'];

        $this->PayPalMode             = $config['api_mode']; 
        $this->PayPalApiUsername      = $config['api_username']; 
        $this->PayPalApiPassword      = $config['api_password']; 
        $this->PayPalApiSignature     = $config['api_signature'];
        $this->lowestAmount = $config['lowest_amount'];
        $this->returnUrl = isset($params['returnUrl']) ? $params['returnUrl'] : base_url().'pay/postBackPayPal';
        $this->cancelUrl = isset($params['cancelUrl']) ? $params['cancelUrl'] : base_url().'payment/review';
    }

    /**
     * Determines whether sandbox mode or not
     * 
     */
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

    /**
     * Creates the CURL request to Paypal
     * 
     */
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

    /**
     * Pay method for Cash On Delivery Gateway Class
     * 
     */
    public function pay($validatedCart, $memberId)
    {
        if(!$memberId){
            redirect('/', 'refresh');
        }

        // Point Gateway
        $pointGateway = $this->paymentService->getPointGateway();

        $PayPalMode = $this->getMode(); 
        $paypalReturnURL = $this->returnUrl; 
        $paypalCancelURL = $this->cancelUrl; 
        $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->releaseAllLock($memberId);

        $productCount = count($validatedCart['itemArray']);
        $cnt = 0;
        $paypalType = $this->getParameter('type');
        $dataitem = '';
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;

        // paymentType
        $this->setParameter('paymentType', $paymentType);

        if($productCount <= 0){
            return [
                'error' => true,
                'message' => 'There are not items in your cart.'
            ];
        }

        if($validatedCart['itemCount'] !== $productCount){
            return [
                'error' => true,
                'message' => 'Item quantity not available.'
            ];
        }

        if($this->paymentService->checkOutService->checkoutCanContinue($validatedCart['itemArray'], $paymentType) === false){
            return [
                'error' => true,
                'message' => "Payment is not available using Paypal.",
            ];
        }

        $shippingAddress = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                ->findOneBy([
                                    'idMember'=>$memberId, 
                                    'type'=>EsAddress::TYPE_DELIVERY
                                ]);

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find(intval($memberId));

        // get address Id
        $stateRegionId = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getAddressStateRegionId((int)$memberId);

        $name = $shippingAddress->getConsignee();
        $street = $shippingAddress->getAddress();
        $cityDescription = $shippingAddress->getCity()->getLocation();
        $email = $member->getEmail();
        $telephone = $shippingAddress->getTelephone();
        $regionDesc = $shippingAddress->getStateregion()->getLocation();

        // Compute shipping fee
        $prepareData = $this->paymentService->computeFeeAndParseData($validatedCart['itemArray'], (int)$stateRegionId);

        $shipping_amt = round((float)$prepareData['othersumfee'], 2);
        $itemTotalPrice = bcsub(round((float)$prepareData['totalPrice'], 2), $shipping_amt, 2);
        $itemOriginalPrice = $itemTotalPrice;
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $grandTotal = $paypalGrandTotal = bcadd($itemTotalPrice, $shipping_amt, 2); 
        $thereIsPromote = $prepareData['thereIsPromote'];
        $this->setParameter('amount', $grandTotal);

        if($pointGateway){
            $checkPointValid = $pointGateway->isPointValid($memberId, $grandTotal);
            if(!$checkPointValid['valid']){ 
                return [
                    'error' => true,
                    'message' => $checkPointValid['message']
                ];
            } 
            $paypalGrandTotal = bcsub($grandTotal, $pointGateway->getParameter('amount'), 2);
            $itemTotalPrice -= $pointGateway->getParameter('amount');
        }

        if($thereIsPromote <= 0 && $paypalGrandTotal < $this->lowestAmount){
            return [
                'error' => true,
                'message' => 'We only accept payments of at least PHP '.$this->lowestAmount.' in total value.'
            ];
        }
        foreach ($itemList as $value) {
            $value['price'] = round($value['price'], 2);
            $deductPrice = $pointGateway 
                           ? $pointGateway->getProductDeductPoint($value['price'], $itemOriginalPrice)
                           : 0;
            $dataitem .= '&L_PAYMENTREQUEST_0_QTY'.$cnt.'='. urlencode($value['qty']).
            '&L_PAYMENTREQUEST_0_AMT'.$cnt.'='.urlencode(bcsub($value['price'], $deductPrice, 2)).
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
        '&PAYMENTREQUEST_0_AMT='.urlencode($paypalGrandTotal).
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

        $padata .= ($paypalType === $this->type_creditcard ? '&LANDINGPAGE='.urlencode('Billing') : '&LANDINGPAGE='.urlencode('Login'));

        $httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata);
        if("SUCCESS" === strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" === strtoupper($httpParsedResponseAr["ACK"])){   
            $transactionID = urldecode($httpParsedResponseAr["TOKEN"]);
            $return = $this->persistPayment(
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
                $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->insertLockItem($orderId, $toBeLocked); 
                $paypalurl ='https://www'.$PayPalMode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$transactionID.'';

                $order = $this->em->getRepository('EasyShop\Entities\EsOrder')->find($orderId);
                $deductAmount = "0.00";
                if($pointGateway){
                    $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                              ->find($pointGateway->getParameter('paymentType'));

                    $deductAmount = $pointGateway->getParameter('amount');
                    $paymentRecord = new EsPaymentGateway();
                    $paymentRecord->setAmount($deductAmount);
                    $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                    $paymentRecord->setOrder($order);
                    $paymentRecord->setPaymentMethod($paymentMethod);
                    $this->em->persist($paymentRecord);
                }

                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                          ->find($this->getParameter('paymentType'));

                $paymentRecord = new EsPaymentGateway();
                $paymentRecord->setAmount(bcsub($this->getParameter('amount'), $deductAmount));
                $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $paymentRecord->setOrder($order);
                $paymentRecord->setPaymentMethod($paymentMethod);
                $this->em->persist($paymentRecord);
                $this->em->flush();
                return [
                    'error' => false,
                    'message' => "",
                    'url' => $paypalurl,
                ]; 
            }
            else{ 
                return [
                    'error' => true,
                    'message' => $return['o_message']
                ]; 
            } 
        }
        else{
            return [
                'error' => true,
                'message' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])
            ];
        }
    }

    /**
     * Postback function for paypal
     * 
     */
    public function postBackMethod($validatedCart, $memberId, $params=[])
    {
        // Point Gateway
        $pointGateway = $this->paymentService->getPointGateway();

        $getItems = $this->getParameter("getArray");

        $response['status'] = PaymentService::STATUS_FAIL;
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
        $apiResponse = $productstring = ''; 

        $productCount = count($validatedCart['itemArray']);

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getAddressStateRegionId((int)$memberId);

        if(array_key_exists('token',$getItems) && array_key_exists('PayerID',$getItems)){
            $payerid = $getItems['PayerID'];
            $token = $response['txnid'] = $getItems['token']; ;
            $return = $this->em->getRepository('EasyShop\Entities\EsOrder')
                               ->findOneBy([
                                    'transactionId' => $token,
                                    'paymentMethod' => $paymentType
                                ]);

            $response['invoice'] = $invoice = $return->getInvoiceNo();
            $response['orderId'] = $orderId = $return->getIdOrder();

            // Compute shipping fee
            $prepareData = $this->paymentService->computeFeeAndParseData($validatedCart['itemArray'], intval($address));

            $shippingAmt = round((float)$prepareData['othersumfee'], 2);
            $itemList = $prepareData['newItemList'];
            $grandTotal = $prepareData['totalPrice'];
            $productstring = $prepareData['productstring']; 
            $toBeLocked = $prepareData['toBeLocked'];
            $lockCountExist = $this->em->getRepository('EasyShop\Entities\EsProductItemLock')->getLockCount($orderId);

            if($pointGateway){
                $grandTotal = bcsub($grandTotal, $pointGateway->getParameter('amount'), 2);
            }

            if($lockCountExist >= 1){
                $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                         ->deleteLockItem($orderId, $toBeLocked); 
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

                            $pointArray = [];
                            foreach ($itemList as $key => $value) {
                                $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                                $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                                if($pointGateway){
                                    $esOrderProduct = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                                               ->findOneBy(['productItemId' => $value['product_itemID']]);
                                    $data["order_product_id"] = $esOrderProduct->getIdOrderProduct();
                                    $data["point"] = $pointGateway->getProductDeductPoint(
                                                        round((float)$value['price'], 2),
                                                        bcsub($grandTotal, $shippingAmt, 2)
                                                    );
                                    $pointArray[] = $data;
                                }
                            }

                            $flag = (string) $httpParsedResponseAr['PAYMENTSTATUS'] === 'Pending';
                            $complete = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                                 ->updatePaymentIfComplete($orderId,json_encode($itemList),$txnid,$paymentType,EsOrderStatus::STATUS_PAID,$flag);

                            if($complete){
                                $orderHistory = [
                                    'order_id' => $orderId,
                                    'order_status' => EsOrderStatus::STATUS_PAID,
                                    'comment' => 'Paypal transaction confirmed'
                                ];
                                $this->em->getRepository('EasyShop\Entities\EsOrderHistory')
                                         ->addOrderHistory($orderHistory);
                                $response['message'] = 'Your payment has been completed through Paypal';
                                $response['status'] = PaymentService::STATUS_SUCCESS;

                                // get points per item product in order.
                                if($pointGateway){ 
                                    $pointGateway->setParameter('memberId', $memberId);
                                    $pointGateway->setParameter('itemArray', $pointArray);
                                    $pointGateway->usePoints();
                                }
                                $this->paymentService->sendPaymentNotification($orderId);
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

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalCharge()
    {
        return ($this->getParameter('amount') * 0.044) + 15; 
    }

    /**
     * {@inheritdoc}
     */
    public function generateReferenceNumber($memberId){}

    /**
     * {@inheritdoc}
     */
    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_DRAFT;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderProductStatus()
    {
        return EsOrderProductStatus::ON_GOING;
    }
}

