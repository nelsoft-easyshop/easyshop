<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use \Curl\Curl as Curl;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\Entities\EsLocationLookup as EsLocationLookup;
use EasyShop\PaymentService\PaymentService as PaymentService;
use EasyShop\PaymentGateways\PointGateway as PointGateway;


class Payment extends MY_Controller
{

    private $paymentConfig;

    private $em;

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->library('paypal');
        $this->load->library('dragonpay'); 
        $this->load->library('xmlmap'); 
        $this->load->model('cart_model');
        $this->load->model('user_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('messages_model');
        $this->load->model('memberpage_model'); 


        $this->config->load('payment', true);
        $this->paymentConfig = strtolower(ENVIRONMENT) === 'production'
                               ? $this->config->item('production', 'payment')
                               : $this->config->item('testing', 'payment');

        $this->em = $this->serviceContainer['entity_manager'];
    }

    /**
     * Check a Product Availability in location and if available in COD process
     * @param  mixed $itemArray
     * @param  integer $city
     * @param  integer $region
     * @param  integer $majorIsland
     * @return mixed
     */
    private function checkProductAvailability($itemArray,$memberId)
    {
        $successCount = 0;
        $codCount = 0;
        $paypalCount = 0;
        $dragonpayCount = 0;
        $pesopayCreditCardCount = 0;
        $directBankCount = 0;
        $shippingDetails = false;

        $address = $this->memberpage_model->get_member_by_id($memberId);

        $city = ($address['c_stateregionID'] > 0) ? $address['c_stateregionID'] : 0;
        $region = 0;
        $majorIsland = 0;
        if($city > 0){  
            $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
            $region = $cityDetails['parent_id'];
            $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
            $majorIsland = $cityDetails['parent_id'];
        }

        foreach ($itemArray as $key => $value) {
            $productId = $value['id']; 
            $itemId = $value['product_itemID']; 
            $availability = "Not Available";

            if($city > 0){  
                $details = $this->payment_model->getShippingDetails($productId,$itemId,$city,$region,$majorIsland);
                $shippingFee = $this->serviceContainer['product_shipping_location_manager']
                                    ->getProductItemShippingFee($itemId, $city, $region, $majorIsland);
                if($shippingFee !== null){
                    $successCount++;
                    $availability = "Available";
                    $itemArray[$value['rowid']]['shipping_fee'] = $shippingFee;
                } 

                if(count($details) > 0){

                    $checkoutService = $this->serviceContainer['checkout_service'];
                    $paymentMethod = $checkoutService->getUserPaymentMethod($value['member_id']);

                    $itemArray[$value['rowid']]['dragonpay'] = FALSE;
                    $itemArray[$value['rowid']]['paypal'] = FALSE; 
                    $itemArray[$value['rowid']]['cash_delivery'] = FALSE;
                    $itemArray[$value['rowid']]['pesopaycdb'] = FALSE;
                    $itemArray[$value['rowid']]['directbank'] = FALSE;

                    if($paymentMethod['all']){
                        $itemArray[$value['rowid']]['dragonpay'] = TRUE;
                        $itemArray[$value['rowid']]['paypal'] = TRUE; 
                        $itemArray[$value['rowid']]['cash_delivery'] = TRUE;
                        $itemArray[$value['rowid']]['pesopaycdb'] = TRUE;
                        $itemArray[$value['rowid']]['directbank'] = TRUE;

                        $paypalCount++;
                        $dragonpayCount++;
                        $pesopayCreditCardCount++;
                        $directBankCount++;
                    }
                    else{
                        foreach ($paymentMethod['payment_method'] as $payKey => $payValue) {
                            if($payValue === EsPaymentMethod::PAYMENT_PAYPAL){
                                $itemArray[$value['rowid']]['paypal'] = TRUE;
                                $paypalCount++;
                            }
                            elseif ($payValue === EsPaymentMethod::PAYMENT_DRAGONPAY){
                                $itemArray[$value['rowid']]['dragonpay'] = TRUE;
                                $dragonpayCount++;
                            }
                            elseif ($payValue === EsPaymentMethod::PAYMENT_PESOPAYCC){
                                $itemArray[$value['rowid']]['pesopaycdb'] = TRUE;
                                $pesopayCreditCardCount++;
                            }
                            elseif ($payValue === EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT){
                                $itemArray[$value['rowid']]['directbank'] = TRUE;
                                $directBankCount++;
                            }
                            else{
                                $itemArray[$value['rowid']]['cash_delivery'] = TRUE;
                            }
                        }
                    }

                    $codCount = ($details[0]['is_cod'] >= 1 
                                && $itemArray[$value['rowid']]['cash_delivery']) 
                                        ? $codCount + 1 
                                        : $codCount + 0;
                    if($itemArray[$value['rowid']]['cash_delivery'] && $details[0]['is_cod']){
                        $itemArray[$value['rowid']]['cash_delivery'] = TRUE;
                    }
                    else{
                        $itemArray[$value['rowid']]['cash_delivery'] = FALSE;
                    }
                    
                }

                $shippingDetails = true; 
            }

            $seller = $value['member_id'];
            $sellerDetails = $this->memberpage_model->get_member_by_id($seller);
            $itemArray[$value['rowid']]['availability'] = ($availability == "Available" ? true : false);
            $itemArray[$value['rowid']]['isAvailable'] = ($availability == "Available") ? "true" : "false";
            $itemArray[$value['rowid']]['seller_username'] = $sellerDetails['username'];
        }

        return $returnData = array(
            'item_array' => $itemArray,
            'success_count' => $successCount,
            'cod_count' => $codCount,
            'paypal_count' => $paypalCount,
            'dragonpay_count' => $dragonpayCount,
            'pesopay_count' => $pesopayCreditCardCount,
            'directbank_count' => $directBankCount,
            'shipping_details' => $shippingDetails,
        );
    }

    /**
     * Check purchase limit and payment type available for the product in cart
     * @param  mixed $itemArray
     * @param  integer $memberId
     * @return mixed
     */
    private function checkPurchaseLimitAndPaymentType($itemArray, $memberId)
    {   
        $configPromo = $this->serviceContainer['config_loader']->getItem('promo','Promo');
        $purchaseLimit = true;
        $soloRestriction = true;
        $paymentType = $configPromo[0]['payment_method'];
        /*  
         *   Changed code to be able to adopt for any promo type
         */
        if($this->cart_model->isCartCheckoutPromoAllow($itemArray)){
            foreach ($itemArray as $key => $value) {
                $qty = $value['qty'];
                $paymentType = array_intersect ( $paymentType , $configPromo[$value['promo_type']]['payment_method']);
                $purchase_limit = $configPromo[$value['promo_type']]['purchase_limit'];
                $can_purchase = $this->product_model->is_purchase_allowed($memberId ,$value['promo_type'], intval($value['start_promo']) === 1);
                if($purchase_limit < $qty || (!$can_purchase) ){
                    $itemArray[$key]['isAvailable'] = "false";
                    $purchaseLimit = false;
                    break;
                }
            }
        }
        else{
            $soloRestriction = false;
        }

        return $returnData = array(
            'payment_type' => $paymentType,
            'purchase_limit' => $purchaseLimit,
            'solo_restriction' => $soloRestriction,
            'itemArray' => $itemArray,
        );
    }

    /**
     * Get available shipping location on individual product item
     * @return json
     */
    public function getProductLocation()
    {
        $itemId = (int) $this->input->post('itemId');
        $markup = "";
        $errorMessage = "";
        $isSuccess = false;
        $bodyData['locationAvailable'] = [];
        $productItem = $this->em->getRepository('EasyShop\Entities\EsProductItem')
                                ->find($itemId);
        if($productItem){
            $bodyData['selectLocation'] = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                                   ->getLocation();
            $itemLocations = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                      ->findBy(['productItem' => $productItem]);
            foreach ($itemLocations as $location) {
                $bodyData['locationAvailable'][] = $location->getShipping()->getLocation()->getIdLocation();
            }

            if (count($bodyData['locationAvailable']) === 1 
                && reset($bodyData['locationAvailable']) === EsLocationLookup::PHILIPPINES_LOCATION_ID) {
                $bodyData['locationAvailable'] = $bodyData['selectLocation']['islandkey'];
            }

            $markup = $this->load->view('partials/payment-item-location', $bodyData, true);
            $isSuccess = true;
        }
        else{
            $errorMessage = "Product not exist!";
        }

        $responseArray = [
            'view' => $markup,
            'isSuccessful' => $isSuccess,
            'errorMessage' => $errorMessage,
        ];

        echo json_encode($responseArray);
    }

    /**
     * Render review page view
     * @return view
     */
    public function review()
    {
        if($memberId = $this->session->userdata('member_id')){
            $paymentService = $this->serviceContainer['payment_service'];
            $checkoutService = $this->serviceContainer['checkout_service'];
            $cartManager = $this->serviceContainer['cart_manager'];
            $pointTracker = $this->serviceContainer['point_tracker'];
            $cartImplementation = $cartManager->getCartObject();

            $esMemberRepository = $this->em->getRepository("EasyShop\Entities\EsMember");
            $esAddressRepository = $this->em->getRepository("EasyShop\Entities\EsAddress");
            $member = $esMemberRepository->find($memberId);
            if($member){
                if($cartImplementation->getSize() > 0){
                    $cart['choosen_items'] = $checkoutService->includeCartItemValidation($member);
                    $postPoints = $this->input->post('used_points') ? (float) $this->input->post('used_points') : 0;
                    $userMaxPoints = $pointTracker->getUserPoint($memberId);
                    $headerData = [
                        "memberId" => $memberId,
                        'title' => 'Payment Review | Easyshop.ph',
                    ];
                    $bodyData = $esAddressRepository->getConsigneeAddress($memberId, EsAddress::TYPE_DELIVERY, true);
                    if($bodyData){
                        $bodyData['shippingFee'] = $cartManager->getCartShippingFee($bodyData['stateRegion'], $memberId);
                    }
                    else{
                        $bodyData = [
                            'address' => [],
                            'stateRegion' => 0,
                            'city' => 0,
                            'shippingFee' => 0,
                        ];
                    }
                    $bodyData['locations'] = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                                      ->getLocationLookup();

                    $bodyData['cartAmount'] = str_replace( ',', '', $cartImplementation->getTotalPrice());
                    $bodyData['cartData'] = $paymentService->validateCartData($cart)['itemArray'];
                    $bodyData['paymentType'] = $checkoutService->getPaymentTypeAvailable($bodyData['cartData']);
                    $bodyData['canCheckout'] = $checkoutService->checkoutCanContinue($bodyData['cartData'], false, false);
                    $bodyData['checkoutError'] = $checkoutService->getCheckoutError($bodyData['cartData']);
                    $totalAmount = bcadd($bodyData['cartAmount'], $bodyData['shippingFee'], 4);
                    $postPoints = $totalAmount >= PointGateway::MIN_AMOUNT_ALLOWED
                                  ? $postPoints
                                  : 0;
                    if ($postPoints > $totalAmount) {
                        $postPoints = $totalAmount;
                    }
                    $bodyData['usedPoints'] = $postPoints > $userMaxPoints ? $userMaxPoints : $postPoints;
                    $bodyData['grandTotal'] = bcsub($totalAmount, $bodyData['usedPoints'], 4);
                    $bodyData['payAllViaPoints'] = bccomp($totalAmount, $bodyData['usedPoints'], 4) === 0;
                    $this->session->set_userdata('choosen_items', $bodyData['cartData']); 
                    $this->load->spark('decorator');
                    $this->load->view('templates/header_alt2', $this->decorator->decorate('header', 'view', $headerData));
                    $this->load->view('pages/payment/payment-review', $bodyData);
                    $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
                }
                else{
                    redirect('/cart', 'refresh');
                }
            }
            else{
                redirect('/', 'refresh');
            }
        }
        else{
            redirect('/', 'refresh');
        }
    }

    /**
     * requesting for paypal token
     * @param  mixed    $itemList        [description]
     * @param  integer  $memberId        [description]
     * @param  string   $paypalReturnURL [description]
     * @param  string   $paypalCancelURL [description]
     * @param  integer  $paypalType      [description]
     * @return mixed
     */
    private function createPaypalToken($itemList,$memberId,$paypalReturnURL,$paypalCancelURL,$paypalType = 1)
    {
        $paypalMode = $this->paypal->getMode(); 
        $productCount = count($itemList);

        $cnt = 0; 
        $dataitem = '';  
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;

        $address = $this->memberpage_model->get_member_by_id($memberId);
        $name = $address['consignee'];
        $street = $address['c_address']; 
        $cityDescription = $address['c_city'];
        $email = $address['email']; 
        $telephone = $address['c_telephone']; 
        $regionDesc = $address['c_stateregion'];  

        $prepareData = $this->processData($itemList,$address);
        $shipping_amt = round(floatval($prepareData['othersumfee']),2);
        $itemTotalPrice = round(floatval($prepareData['totalPrice']),2) - $shipping_amt;
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $grandTotal= $itemTotalPrice+$shipping_amt; 
        $thereIsPromote = $prepareData['thereIsPromote'];

        if($thereIsPromote <= 0 && $grandTotal < '50'){
            return array(
                'e' => '0',
                'd' => 'We only accept payments of at least PHP 50.00 in total value.'
            );
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

        $httpParsedResponseAr = $this->paypal->PPHttpPost('SetExpressCheckout', $padata); 
        
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) 
            || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){   
            $transactionID = urldecode($httpParsedResponseAr["TOKEN"]);
            $return = $this->payment_model->payment($paymentType,$grandTotal,$memberId,$productstring,$productCount,json_encode($itemList),$transactionID);
            
            if($return['o_success'] > 0){
                $orderId = $return['v_order_id'];
                $locked = $this->lockItem($toBeLocked,$orderId,'insert');
                $paypalurl ='https://www'.$paypalMode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$transactionID.'';
                return array(
                    'e' => '1',
                    'd' => $paypalurl
                );
            }
            else{
                return array(
                    'e' => '0',
                    'd' => $return['o_message']
                );
            }        
        }
        else{
            return array(
                    'e' => '0',
                    'd' => urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])
                );
        }
    }

    #PAYPAL IPN (Instant payment Notification)
    public function ipn2()
    {
        // CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        // Set this to 0 once you go live or don't require logging.
        $PayPalMode = $this->paypal->getMode(); 
        $paypalmode = ($PayPalMode == '.sandbox' ? '.sandbox' : '');
        define("DEBUG", 0);
        define("LOG_FILE", "../application/logs/ipn-log-".date('Y-m-d-H-i-s').".log");

        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // Post IPN data back to PayPal to validate the IPN data is genuine
        // Without this step anyone can fake IPN data

        $paypal_url = "https://www".$paypalmode.".paypal.com/cgi-bin/webscr";

        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if(DEBUG == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }

        // CONFIG: Optional proxy configuration
        //curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

        // Set TCP timeout to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
        // of the certificate as shown below. Ensure the file is readable by the webserver.
        // This is mandatory for some environments.

        //$cert = __DIR__ . "./cacert.pem";
        //curl_setopt($ch, CURLOPT_CAINFO, $cert);

        $res = curl_exec($ch);
        if (curl_errno($ch) != 0){  // cURL error
            if(DEBUG == true){ 
                error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
            exit;
        }else{
                // Log the entire HTTP response if debug is switched on.
            if(DEBUG == true) {
                error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
                error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

                    // Split response headers and payload
                list($headers, $res) = explode("\r\n\r\n", $res, 2);
            }
            curl_close($ch);
        }

        // Inspect IPN validation result and act accordingly

        if (strcmp ($res, "VERIFIED") == 0) {
            // check whether the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment and mark item as paid.

            // assign posted variables to local variables
            $payment_status = (isset($_POST['payment_status']) ? $_POST['payment_status'] : '');
            $pending_reason = (isset($_POST['pending_reason']) ? $_POST['pending_reason'] : '');
            $reason_code = (isset($_POST['reason_code']) ? $_POST['reason_code'] : '');
            $txn_id =  (isset($_POST['txn_id']) ? $_POST['txn_id'] : '');
            $parent_txn_id = (isset($_POST['parent_txn_id']) ? $_POST['parent_txn_id'] : ''); 

            if($payment_status == 'Completed')
            {
                error_log(date('[Y-m-d H:i e] '). "STATUS IPN: $payment_status ". PHP_EOL, 3, LOG_FILE);
                $this->payment_model->updateFlag($txn_id);
            }
            else if($payment_status == 'Denied' || $payment_status == 'Failed' || $payment_status == 'Voided')
            {
                error_log(date('[Y-m-d H:i e] '). "STATUS IPN: $payment_status ". PHP_EOL, 3, LOG_FILE);
                $orderId = $this->payment_model->cancelTransaction($txn_id,true);
                $orderHistory = array(
                    'order_id' => $orderId,
                    'order_status' => 2,
                    'comment' => 'Paypal transaction ' . $payment_status
                    );
                $this->payment_model->addOrderHistory($orderHistory);


            }else if ($payment_status == 'Refunded' || $payment_status == 'Reversed') {

                error_log(date('[Y-m-d H:i e] '). "STATUS IPN: $payment_status ". PHP_EOL, 3, LOG_FILE);
            }
            else if($payment_status == 'Pending' || $payment_status == 'Processed')
            {
                error_log(date('[Y-m-d H:i e] '). "STATUS IPN: $payment_status ". PHP_EOL, 3, LOG_FILE);    
            }
            else if($payment_status == 'Canceled_Reversal')
            {
                error_log(date('[Y-m-d H:i e] '). "STATUS IPN: $payment_status ". PHP_EOL, 3, LOG_FILE);
                $this->payment_model->updateFlag($parent_txn_id);
            }

            if(DEBUG == true) {
                error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
            }
        } else if (strcmp ($res, "INVALID") == 0) {
            if(DEBUG == true) {
                error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
            }
        }
    }

    /**
     * Persist pay in paypal do express checkout
     * @param  integer $member_id [description]
     * @param  mixed   $itemList  [description]
     * @param  string  $payerid   [description]
     * @param  string  $token     [description]
     * @return mixed
     */
    private function persistPaypal($member_id,$itemList,$payerid,$token)
    {
        $productCount = count($itemList);  
        $address = $this->memberpage_model->get_member_by_id($member_id);
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
        $status = PaymentService::STATUS_FAIL;
        $message = "";
        $txnid = $token;
        $return = $this->payment_model->selectFromEsOrder($token,$paymentType);
        $invoice = $return['invoice_no'];
        $orderId = $return['id_order']; 
        $prepareData = $this->processData($itemList,$address);
        $itemList = $prepareData['newItemList'];
        $grandTotal = $prepareData['totalPrice']; 
        $toBeLocked = $prepareData['toBeLocked'];
        $lockCountExist = $this->payment_model->lockcount($orderId);
        
        if($lockCountExist >= 1){
            $locked = $this->lockItem($toBeLocked,$orderId,'delete');
            $qtysuccess = $this->resetPriceAndQty();

            if($qtysuccess == $productCount){

                $padata = '&TOKEN='.urlencode($token).
                            '&PAYERID='.urlencode($payerid).
                            '&PAYMENTACTION='.urlencode("SALE").
                            '&AMT='.urlencode($grandTotal).
                            '&CURRENCYCODE='.urlencode('PHP');

                $httpParsedResponseArGECD = $this->paypal->PPHttpPost('GetExpressCheckoutDetails', $padata); 
                $httpParsedResponseArDECP = $this->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata); 
        
                if(("SUCCESS" == strtoupper($httpParsedResponseArDECP["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseArDECP["ACK"])) && ("SUCCESS" == strtoupper($httpParsedResponseArGECD["ACK"]))){

                    $txnid = urldecode($httpParsedResponseArDECP["TRANSACTIONID"]);  
                    $nvpStr = "&TRANSACTIONID=".$txnid;
                    $httpParsedResponseAr = $this->paypal->PPHttpPost('GetTransactionDetails', $nvpStr); 

                    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) 
                        || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){   
                        
                        // START SAVING TO DATABASE HERE 
                        foreach ($itemList as $key => $value) {     
                            $itemComplete = $this->payment_model->deductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                            $this->product_model->update_soldout_status($value['id']);
                        }

                        $flag = ($httpParsedResponseAr['PAYMENTSTATUS'] == 'Pending' ? 1 : 0);
                        $complete = $this->payment_model->updatePaymentIfComplete($orderId,json_encode($itemList),$txnid,$paymentType,0,$flag);

                        if($complete > 0){
                            $orderHistory = array(
                                'order_id' => $orderId,
                                'order_status' => 0,
                                'comment' => 'Paypal transaction confirmed'
                                );
                            $this->payment_model->addOrderHistory($orderHistory); 
                            $message = 'Your payment has been completed through Paypal';   
                            $status = PaymentService::STATUS_SUCCESS;
                            $this->removeItemFromCart(); 
                            $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));
                        }
                        else{
                            $message = 'Someting went wrong. Please contact us immediately. Your EASYSHOP INVOICE NUMBER: '.$invoice.'</div>'; 
                        } 
                    }
                    else{
                        $message = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                    }                       
                }
                else{
                    $message = urldecode($httpParsedResponseArDECP["L_LONGMESSAGE0"]);
                }
            }
            else{ 
                $message = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
            }
        }
        else{
            $message = 'Your session is already expired for this payment.';
        }

        return array(
                'status' => $status,
                'message' => $message,
                'txnid' => $txnid,
            );
    }

    #START OF CASH ON DELIVERY, DIRECT BANK DEPOSIT PAYMENT

    /**
     * Process data for the cash on delivery mode of payment
     * @param  integer $memberId
     * @param  string  $txnid
     * @param  mixed   $itemList
     * @param  integer $paymentType
     * @return mixed
     */
    private function cashOnDeliveryProcessing($memberId,$txnid,$itemList,$paymentType)
    {
        $address = $this->memberpage_model->get_member_by_id($memberId); 
        $prepareData = $this->processData($itemList,$address);
        $grandTotal = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $return = $this->payment_model->payment($paymentType,$grandTotal,$memberId,$productstring,count($itemList),json_encode($itemList),$txnid);

        if($return['o_success'] <= 0){
            $message = $return['o_message'];
            $status = PaymentService::STATUS_FAIL;
        }
        else{
            $v_order_id = $return['v_order_id'];
            $invoice = $return['invoice_no'];
            $status = PaymentService::STATUS_SUCCESS;
            $message = 'Your payment has been completed through Cash on Delivery.';
            foreach ($itemList as $key => $value) {
                $itemComplete = $this->payment_model->deductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                $this->product_model->update_soldout_status($value['id']);
            }
            $this->removeItemFromCart();  
            $this->sendNotification(array('member_id'=>$memberId, 'order_id'=>$v_order_id, 'invoice_no'=>$invoice));
        }

        return array(
                'status' => $status,
                'message' => $message,
            );
    }

    /**
     * Request dragonpay token for able proceed in payment
     * @param  mixed $itemList [description]
     * @param  integer $memberId [description]
     * @return JSON
     */
    private function createDragonPayToken($itemList,$memberId)
    {
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        $productCount = count($itemList); 
        $address = $this->memberpage_model->get_member_by_id($memberId);  

        $prepareData = $this->processData($itemList,$address);
        $grandTotal = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $name = $prepareData['productName'];

        $txnid = $this->generateReferenceNumber($paymentType,$memberId);
        $dpReturn = $this->dragonpay->getTxnToken($grandTotal,$name,$address['email'],$txnid);

        $return = $this->payment_model->payment($paymentType,$grandTotal,$memberId,$productstring,$productCount,json_encode($itemList),$txnid);
        
        if($return['o_success'] <= 0){
            return '{"e":"0","m":"'.$return['o_message'].'"}';
        }
        else{ 
            $orderId = $return['v_order_id'];
            $locked = $this->lockItem($toBeLocked,$orderId,'insert');  
            return $dpReturn;
        }
    }

    /** 
     * Generates the payment succes or error view
     * 
     * @param string $mode
     */
    public function paymentSuccess($mode = "easyshop")
    {
        $entityManager = $this->serviceContainer['entity_manager'];
        $paymentService = $this->serviceContainer['payment_service'];
        $transactionManager = $this->serviceContainer['transaction_manager'];

        $itemGoogleAnalytics = [];

        if($this->session->userdata('payment_txnid')
            && $this->session->userdata('member_id')){
            $txnId = (string)$this->session->userdata('payment_txnid'); 
            $message = (string)$this->session->userdata('payment_msg');
            $status = (string)$this->session->userdata('payment_status');
            $memberId = (int)$this->session->userdata('member_id');
            $isPaymentSuccess = strtolower($status) === PaymentService::STATUS_SUCCESS; 
            $order = $entityManager->getRepository('EasyShop\Entities\EsOrder')
                                   ->findOneBy(['transactionId' => $txnId]);

            if($order){
                if($isPaymentSuccess){
                    $order->setDateadded(date_create(date("Y-m-d H:i:s")));
                    $entityManager->flush();
                    $itemGoogleAnalytics = $this->createGoogleAnalyticsData($order);
                }

                $shippingAddress = $entityManager->getRepository('EasyShop\Entities\EsOrderShippingAddress')
                                                 ->find($order->getShippingAddressId());
                $orderProducts = $transactionManager->getTransactionItems($order);
                $transactionShippingFee = $transactionManager->getTransactionShippingFee($order);

                $bodyData = [
                    'order' => $order,
                    'responseMessage' => $message,
                    'isPaymentSuccess' => $isPaymentSuccess, 
                    'itemGoogleAnalytics' => $itemGoogleAnalytics,
                    'shippingAddress' => $shippingAddress, 
                    'orderProducts' => $orderProducts,
                    'transactionPoints' => $paymentService->getTransactionPoints($order),
                    'transactionShippingFee' => $transactionShippingFee,
                ];

                $headerData = [
                    "memberId" => $memberId,
                    'title' => 'Payment Complete | Easyshop.ph',
                ];

                $this->load->spark('decorator');
                $this->load->view('templates/header_alt2', $this->decorator->decorate('header', 'view', $headerData));
                $this->load->view('pages/payment/payment-response', $bodyData); 
                $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
            }
            else{
                show_404();
            }
        }
        else{
            show_404();
        }
   }

   /**
    * Generate receipt for transaction
    * @return view
    */
   public function generateReceipt()
   {
        $entityManager = $this->serviceContainer['entity_manager'];
        $paymentService = $this->serviceContainer['payment_service'];
        $transactionManager = $this->serviceContainer['transaction_manager'];

        $transactionId = $this->input->get('txnId');
        $order = $entityManager->getRepository('EasyShop\Entities\EsOrder')
                               ->findOneBy(['transactionId' => $transactionId]);

        if($order){
            $shippingAddress = $entityManager->getRepository('EasyShop\Entities\EsOrderShippingAddress')
                                             ->find($order->getShippingAddressId());
            $orderProducts = $transactionManager->getTransactionItems($order);
            $transactionShippingFee = $transactionManager->getTransactionShippingFee($order);

            $orderData = [
                'order' => $order,  
                'shippingAddress' => $shippingAddress, 
                'orderProducts' => $orderProducts,
                'transactionPoints' => $paymentService->getTransactionPoints($order),
                'transactionShippingFee' => $transactionShippingFee,
            ];

            $this->load->view("pages/payment/payment-receipt", $orderData);
        }
        else{
            show_404();
        }
   }


    /**
     * Remove the chosen items for checkout from the cart
     *
     */
    private function removeItemFromCart()
    {
        $cartManager = $this->serviceContainer['cart_manager'];
        $cartCheckout = $this->session->userdata('choosen_items');
        $memberId = $this->session->userdata('member_id');
        if($cartCheckout){
            foreach($cartCheckout as $rowId => $cartItem){
                $cartManager->removeItem($memberId, $rowId);
            }
        }
        $this->session->unset_userdata('choosen_items');
    }

    /**
     *   Function called upon purchasing an item. Sends notification to both buyer and seller
     *
     *   @param $data = array(
     *   'member_id' => Member ID who made the purchase (buyerID)
     *   'order_id' => Transaction Number
     *   'invoice_no' => Invoice number)
     */
    private function sendNotification($data, $buyerFlag = true, $sellerFlag = true) 
    {
        $xmlResourceService = $this->serviceContainer['xml_resource'];
        $xmlfile =  $xmlResourceService->getContentXMLfile();

        $emailService = $this->serviceContainer['email_notification'];
        $smsService = $this->serviceContainer['mobile_notification'];
        $em = $this->serviceContainer['entity_manager'];

        $this->config->load('email', true);
        $imageArray = $this->config->config['images'];
        
        $sender = intval($this->xmlmap->getFilenameID($xmlfile,'message-sender-id'));
        $transactionData = $this->payment_model->getPurchaseTransactionDetails($data);

        #get payment method instructions
        switch($transactionData['payment_method']){
            case 1:
                $transactionData['payment_msg_buyer'] = $this->lang->line('payment_paypal_buyer');
                $transactionData['payment_msg_seller'] = $this->lang->line('payment_ppdp_seller');
                $transactionData['payment_method_name'] = "PayPal";
                break;
            case 2:
                $transactionData['payment_msg_buyer'] = $this->lang->line('payment_dp_buyer');
                $transactionData['payment_msg_seller'] = $this->lang->line('payment_ppdp_seller');
                $transactionData['payment_method_name'] = "DragonPay";
                break;
            case 3:
                $transactionData['payment_msg_buyer'] = $this->lang->line('payment_cod_buyer');
                $transactionData['payment_msg_seller'] = $this->lang->line('payment_cod_seller');
                $transactionData['payment_method_name'] = "Cash on Delivery";
                break;
            case 5:
                $this->load->library('parser');
                $paymentMsg = $this->lang->line('payment_bd_buyer');
                $bankparse['bank_name'] = $this->xmlmap->getFilenameID($xmlfile,'bank-name');
                $bankparse['bank_accname'] = $this->xmlmap->getFilenameID($xmlfile,'bank-account-name');
                $bankparse['bank_accnum'] = $this->xmlmap->getFilenameID($xmlfile,'bank-account-number');
                $transactionData['payment_msg_buyer'] = $this->parser->parse_string($paymentMsg, $bankparse, true);
                $transactionData['payment_msg_seller'] = '';
                $transactionData['payment_method_name'] = "Bank Deposit";
                break;
        }

        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        
        // Send email to buyer
        if($buyerFlag){
            $buyerEmail = $transactionData['buyer_email'];
            $buyerData = $transactionData;
            $buyerData['facebook'] = $socialMediaLinks["facebook"];
            $buyerData['twitter'] = $socialMediaLinks["twitter"];            
            unset($buyerData['seller']);
            unset($buyerData['buyer_email']);

            foreach($buyerData['products'] as $key => $productData){
                $primaryImage = $em->getRepository('EasyShop\Entities\EsProductImage')
                                   ->getDefaultImage($productData['productId']);
                $imagePath = $primaryImage->getDirectory().'categoryview/'.$primaryImage->getFilename();
                $imagePath = ltrim($imagePath, '.');
                if(strtolower(ENVIRONMENT) === 'development'){
                    $imageArray[] = $imagePath;
                    $parsedImage = $primaryImage->getFilename();
                }
                else{
                    $parsedImage = getAssetsDomain().ltrim($imagePath, '/');
                }
                $buyerData['products'][$key]['primaryImage'] = $parsedImage;
            }

            $buyerData['baseUrl'] = base_url();
            $buyerMsg = $this->parser->parse('emails/email_purchase_notification_buyer',$buyerData,true);
            $buyerSubject = $this->lang->line('notification_subject_buyer');
            $buyerSmsMsg = $buyerData['buyer_store'] . $this->lang->line('notification_txtmsg_buyer');

            $emailService->setRecipient($buyerEmail)
                         ->setSubject($buyerSubject)
                         ->setMessage($buyerMsg, $imageArray)
                         ->queueMail();
            $smsService->setMobile($buyerData['buyer_contactno'])
                       ->setMessage($buyerSmsMsg)
                       ->queueSMS();
            
            #Send message via easyshop_messaging to buyer
            if($this->user_model->getUserById($sender)){
                $this->messages_model->send_message($sender,$data['member_id'],$this->lang->line('message_to_buyer'));
            }
        }

        // Send email to seller of each product - once per seller
        if($sellerFlag){
            $sellerData = [
                'id_order' => $transactionData['id_order'],
                'dateadded' => $transactionData['dateadded'],
                'buyer_name' => $transactionData['buyer_name'],
                'buyer_store' => $transactionData['buyer_store'],
                'invoice_no' => $transactionData['invoice_no'],
                'payment_msg_seller' => $transactionData['payment_msg_seller'],
                'payment_method_name' => $transactionData['payment_method_name'],
                'facebook' => $socialMediaLinks["facebook"],
                'twitter' => $socialMediaLinks["twitter"],
                'baseUrl' => base_url(),
            ];

            foreach($transactionData['seller'] as $seller_id => $seller){
                $sellerEmail = $seller['email'];
                $sellerData = array_merge( $sellerData, $seller );
                $sellerData['totalprice'] = number_format($seller['totalprice'], 2, '.' , ',');
                $sellerData['buyer_slug'] = $transactionData['buyer_slug'];

                # Additional sellerData for email template
                $sellerSubject = $this->lang->line('notification_subject_seller');
                foreach($sellerData['products'] as $key => $productData){
                    $primaryImage = $em->getRepository('EasyShop\Entities\EsProductImage')
                                    ->getDefaultImage($productData['productId']);
                    $imagePath = $primaryImage->getDirectory().'categoryview/'.$primaryImage->getFilename();
                    $imagePath = ltrim($imagePath, '.');
                    if(strtolower(ENVIRONMENT) === 'development'){
                        $imageArray[] = $imagePath;
                        $parsedImage = $primaryImage->getFilename();
                    }
                    else{
                        $parsedImage = getAssetsDomain().ltrim($imagePath, '/');
                    }
                    $sellerData['products'][$key]['primaryImage'] = $parsedImage;
                }

                $sellerMsg = $this->parser->parse('emails/email_purchase_notification_seller',$sellerData,true);
                $sellerSmsMsg = $seller['seller_store'] . $this->lang->line('notification_txtmsg_seller');
                $emailService->setRecipient($sellerEmail)
                             ->setSubject($sellerSubject)
                             ->setMessage($sellerMsg, $imageArray)
                             ->queueMail();
                $smsService->setMobile($seller['seller_contactno'])
                           ->setMessage($sellerSmsMsg)
                           ->queueSMS();

                #Send message via easyshop_messaging to seller
                if($this->user_model->getUserById($sender)){
                    $this->messages_model->send_message($sender,$seller_id,$this->lang->line('message_to_seller'));
                }

            }
        }
    }

    /*
     *  Function to generate google analytics data
     */
    private function createGoogleAnalyticsData($order)
    {   
        $productManager = $this->serviceContainer['product_manager'];
        $cartData = json_decode($order->getDataResponse(), true);
        $analytics = []; 
        foreach ($cartData as $item) {
            $product = $productManager->getProductDetails($item['id']);
            $tempAnalytics = [
                'id' => $order->getIdOrder(),
                'affiliation' => $product->getMember()->getStoreName(),
                'revenue' => $item['subtotal'],
                'currency' => 'PHP', 
                'data' => [
                    'id' => $order->getIdOrder(),
                    'name' => $item['name'],
                    'sku' => $item['id'],
                    'category' => $product->getCat()->getName(),
                    'price' => $item['price'],
                    'quantity' => $item['qty']
                ]
            ];
            $analytics[] = $tempAnalytics;
        }

        return $analytics;
    }

    private function processData($itemList,$address)
    {
        $city = ($address['c_stateregionID']) > 0 ? $address['c_stateregionID'] :  27;
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city); 
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id'];

        $grandTotal = 0;
        $productstring = "";
        $name = "";
        $othersumfee = 0;
        $toBeLocked = array();
        $isPromote = 0;

        foreach ($itemList as $key => $value) {
            $sellerId = $value['member_id'];
            $productId = $value['id'];
            $orderQuantity = $value['qty'];
            $price = $value['price']; 
            $isPromote = ($value['is_promote'] == 1) ? $isPromote += 1 : $isPromote += 0;
            $productItem =  $value['product_itemID']; 
            $shippingFee = $this->serviceContainer['product_shipping_location_manager']
                                ->getProductItemShippingFee($productItem, $city, $region, $majorIsland);
            $shippingFee = $shippingFee !== null ? $shippingFee : 0;
            $otherFee = $shippingFee * $orderQuantity;
            $othersumfee += $otherFee;
            $total =  $value['subtotal'] + $otherFee;
            $optionCount = count($value['options']);
            $optionString = '';
            foreach ($value['options'] as $keyopt => $valopt) {
                $optValueandPrice = explode('~', $valopt);
                $optionString .= '(-)'.$keyopt.'[]'.$optValueandPrice[0].'[]'.$optValueandPrice[1];
            } 

            $optionString = ($optionCount <= 0) ? '0[]0[]0' : substr($optionString,3); 
            $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$otherFee."{+}".$total."{+}".$productItem."{+}".$optionCount."{+}".$optionString;
            $itemList[$key ]['otherFee'] = $otherFee;
            $sellerDetails = $this->memberpage_model->get_member_by_id($sellerId); 
            $itemList[$key]['seller_username'] = $sellerDetails['username'];
            $grandTotal += $total;  
            $name .= " ".$value['name'];
            $toBeLocked[$productItem] = $orderQuantity;
        }
        
        
        $productstring = substr($productstring,4);
        return array(
            'totalPrice' => round(floatval($grandTotal),2), 
            'newItemList' => $itemList,
            'productstring' => $productstring,
            'productName' => $name,
            'toBeLocked' => $toBeLocked,
            'othersumfee' => round(floatval($othersumfee),2), 
            'thereIsPromote' => $isPromote
            );
    }

    private function lockItem($ids = [], $orderId, $action = 'insert')
    {
        foreach ($ids as $key => $value) {
            $lock = $this->payment_model->lockItem($key,$value,$orderId,$action);
        }
    }

    /**
     * reset price and quantity data on session 
     * @return integer
     */
    private function resetPriceAndQty()
    {
        $productManager = $this->serviceContainer['product_manager'];
        $carts = $this->session->all_userdata(); 
        $memberId = $this->session->userdata('member_id');
        $itemArray = $carts['choosen_items'];
        $qtySuccess = 0;

        foreach ($itemArray as $key => $value) {
            $productId = $value['id']; 
            $itemId = $value['product_itemID']; 
            $product = $productManager->getProductDetails($productId);
            $productInventory = $productManager->getProductInventory($product);

            if(isset($productInventory[$itemId])){
                $maxqty = $productInventory[$itemId]['quantity'];
                $qty = $value['qty'];
                $finalPromoPrice = $product->getFinalPrice() + $value['additional_fee'];
                $subtotal = $finalPromoPrice * $qty;

                $itemArray[$value['rowid']]['maxqty'] = $maxqty;
                $itemArray[$value['rowid']]['price'] = $finalPromoPrice;
                $itemArray[$value['rowid']]['subtotal'] = $subtotal;

                if($maxqty >= $qty){
                    $qtySuccess++;
                }
            }
            else{
                unset($itemArray[$key]);
            }
        }
        $this->session->set_userdata('choosen_items', $itemArray);

        return $qtySuccess;
    }

    /**
     * Set Flash data into variable
     * @param string $txnId
     * @param string $message
     * @param string $status
     */
    private function __generateFlash($txnId, $message, $status) 
    {
        $this->session->set_userdata('payment_txnid',$txnId);
        $this->session->set_userdata('payment_msg',$message);
        $this->session->set_userdata('payment_status',$status);
    }

    /**
     * Generate reference number by payment type
     * @param  integer $paymentType 
     * @param  integer $member_id 
     * @return string
     */
    private function generateReferenceNumber($paymentType,$member_id){
    
        switch($paymentType)
        {
            case 1:
                $paycode = 'PPL';
            break;

            case 2:
                $paycode = 'DPY';
            break;

            case 3:
                $paycode = 'COD';
            break;

            case 4:
                $paycode = 'PPY';
            break;

            case 5:
                $paycode = 'DBP';
            break;

            default:
                $paycode = 'COD';
            break;
       }

       return $paycode.'-'.date('ymdhs').'-'.$member_id;

    }

    /**
     *  Universal Pay Method
     *
     *  This function should only serve as a bridge that calls PaymentService
     *  and passes all the necessary variables (payment methods + cost of each,
     *  session related data etc.)
     * 
     *  $paymentMethods should be constructed as follows:
     *  
     *  {
     *      "CODGateway" : {
     *              "method" : "CashOnDelivery", 
     *              "amount" : 999, 
     *              "isLock" : false
     *      },
     *      "PointGateway" : {
     *              "method" : "Point", 
     *              "amount" : 999, 
     *              "isLock" : false
     *      }
     *  }
     *
     *
     *  The redirect part on this function will only execute iff the payment gateway
     *  has no postback requirements
     *   
     */
    public function pay()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect('/', 'refresh');
        }

        $paymentService = $this->serviceContainer['payment_service'];
        $carts = $this->session->all_userdata();
        $memberId = $this->session->userdata('member_id');
        $paymentMethods = json_decode($this->input->post('paymentMethods'),true);
        $paymentMethodString = (string)reset($paymentMethods)['method'];

        $isPaymentAcceptPoints = $paymentService->isPaymentMethodAcceptPoints($paymentMethodString);
        if(!$isPaymentAcceptPoints && array_key_exists("PointGateway", $paymentMethods)){
            unset($paymentMethods["PointGateway"]);
        }

        $validatedCart = $paymentService->validateCartData($carts);
        $this->session->set_userdata('choosen_items', $validatedCart['itemArray']); 

        $response = $paymentService->pay($paymentMethods, $validatedCart, $this->session->userdata('member_id'));

        if($paymentMethodString === "PayPal"
           || $paymentMethodString === "DragonPay"){
            echo json_encode($response);
        }
        elseif($paymentMethodString === "PesoPay"){
            $responseArray = [
                'error' => $response['error'],
                'message' => $response['message'],
                'form' => $this->load->view('pages/payment/pesopayform', $response, true)
            ];
            echo json_encode($responseArray);
        }
        else{
            $responseArray = [
                'error' => $response['error'],
                'message' => $response['message'],
                'url' => ''
            ];
            if($response['error'] === false){
                $this->__generateFlash($response['txnid'], $response['message'], $response['status']);
                $this->removeItemFromCart();
                $responseArray['url'] = '/payment/success/'.$response['textType'].
                                        '?txnid='.$response['txnid'].
                                        '&msg='.$response['message'].
                                        '&status='.$response['status'];
            }
            echo json_encode($responseArray);
        }
    }

    /**
     * Postback function for PayPal
     */
    public function postBackPayPal()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect('/', 'refresh');
        }

        $carts = $this->session->all_userdata();
        $memberId = (int) $this->session->userdata('member_id');
        $paypalToken = (string) trim($this->input->get('token'));
        $paymentService = $this->serviceContainer['payment_service'];

        $order = $this->serviceContainer['entity_manager']
                      ->getRepository('EasyShop\Entities\EsOrder')
                      ->findOneBy(["transactionId" => $paypalToken]);
        
        if($order){
            $transactionPoints = $paymentService->getTransactionPoints($order);
            if((int) $transactionPoints > 0 ){
                $paymentMethods = [
                    "PaypalGateway" => [
                        "method" => "PayPal",
                        "getArray" => $this->input->get()
                    ],
                    "PointGateway" => [
                        "method" => "Point", 
                        "amount" => $transactionPoints, 
                        "pointtype" => "purchase"
                    ]
                ];
            }
            else{
                $paymentMethods = [
                    "PaypalGateway" => [
                        "method" => "PayPal", 
                        "getArray" => $this->input->get()
                    ]
                ];
            }

            // Validate Cart Data
            $userCart = json_decode($order->getDataResponse(), true);
            $validatedCart = $paymentService->validateCartData(['choosen_items' => $userCart]);
            $this->session->set_userdata('choosen_items', $validatedCart['itemArray']);
            $response = $paymentService->postBack($paymentMethods, $validatedCart, $memberId, null);
            $message = $response['message'];
            $status = $response['status'];
            $txnid = $response['txnid'];
            if($status === PaymentService::STATUS_SUCCESS){
                $this->removeItemFromCart();
            }
            $this->__generateFlash($txnid, $message, $status);
            redirect('/payment/success/paypal?txnid='.$txnid.'&msg='.$message.'&status='.$status, 'refresh'); 
        }
        else{
            show_404();
        }

    }

    /**
     * Return url for dragonpay
     * @return view
     */
    public function dragonPayReturn()
    {
        $params['txnId'] = trim($this->input->get('txnid'));
        $params['refNo'] = trim($this->input->get('refno'));
        $params['status'] =  trim($this->input->get('status'));
        $params['message'] = trim($this->input->get('message'));
        $params['digest'] = trim($this->input->get('digest'));
        $params['client'] = trim($this->input->get('param1'));
        if($params['client'] === "Easyshop"){
            if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
                redirect('/', 'refresh');
            }
            $paymentService = $this->serviceContainer['payment_service'];
            $paymentMethods = ["DragonPayGateway" => ["method" => "DragonPay"]];

            $response = $paymentService->returnMethod($paymentMethods, $params);
            $message = $response['message'];
            $txnId = $response['txnId'];
            $status = $response['status'];
            if($status === PaymentService::STATUS_SUCCESS){
                $this->removeItemFromCart();
            }
            $this->__generateFlash($txnId, $message, $status);
            redirect('/payment/success/dragonpay?txnid='.$txnId.'&msg='.$message.'&status='.$status, 'refresh');
        }
        elseif($params['client'] === "Easydeal"){
            $redirectUrl = $this->paymentConfig['payment_type']['dragonpay']['Easydeal']['return_url'];
            $redirectUrl .= "?txnid=".$params['txnId']."&refno=".$params['refNo']."&status=".$params['status']."&message=".urlencode($params['message'])."&digest=".$params['digest'];
            redirect($redirectUrl, 'refresh');
        }
        else{
            show_404();
        }
    }

    /**
     * Post back url for dragonpay 
     */
    public function dragonPayPostBack()
    { 
        $paymentConfig = $this->paymentConfig; 
        $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY; 
        $paymentService = $this->serviceContainer['payment_service'];
        $ipAddress = $this->serviceContainer['http_request']->getClientIp();
        $isValidIp = $paymentService->checkIpIsValidForPostback($ipAddress, $paymentType);
        $client = trim($this->input->post('param1'));

        if($isValidIp){
            if($client === "Easyshop"){
                $paymentMethods = ["DragonPayGateway" => ["method" => "DragonPay"]];
                $params['txnId'] = $this->input->post('txnid');
                $params['refNo'] = $this->input->post('refno');
                $params['status'] =  $this->input->post('status');
                $params['message'] = $this->input->post('message');
                $params['digest'] = $this->input->post('digest');
                $response = $paymentService->postBack($paymentMethods, null, null, $params);
                if($response === false){
                    show_404();
                }
                else{
                    header("Content-Type:text/plain");
                    echo 'result=OK'; // acknowledgement
                }
            }
            elseif($client === "Easydeal"){
                $curlUrl = $paymentConfig['payment_type']['dragonpay']['Easydeal']['postback_url'];
                $curl = new Curl();
                $curl->setOpt(CURLOPT_SSL_VERIFYPEER, strtolower(ENVIRONMENT) === 'production');
                $curl->post($curlUrl, $this->input->post());
                header("Content-Type:text/plain");
                echo 'result=OK'; // acknowledgement
            }
            else{
                show_404();
            }
        }
        else{
            show_404();
        }
    }

    /**
     * Return url for pesopay 
     * @return view
     */
    public function pesoPayReturnUrl()
    { 
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect('/', 'refresh');
        }

        $paymentService = $this->serviceContainer['payment_service'];
        $paymentMethods = ["PesoPayGateway" => ["method" => "PesoPay"]];

        $params['ref'] = $this->input->get('Ref');
        $params['status'] =  $this->input->get('status'); 
        $response = $paymentService->returnMethod($paymentMethods, $params);
        $txnId = $response['txnId'];
        $message = $response['message'];
        $status = $response['status'];
        if($status === PaymentService::STATUS_SUCCESS){
            $this->removeItemFromCart();
        }
        $this->__generateFlash($txnId, $message, $status);
        redirect('/payment/success/debitcreditcard'
                    .'?txnid='.$txnId
                    .'&msg='.$message
                    .'&status='.$status, 'refresh');
    }

    /**
     * Post Back url for pesopay
     * @return [type] [description]
     */
    public function pesoPayDataFeed()
    {
        $paymentService = $this->serviceContainer['payment_service'];
        $ipAddress = $this->serviceContainer['http_request']->getClientIp(); 
        $paymentConfig = $this->paymentConfig; 
        $isValidIp = $paymentService->checkIpIsValidForPostback($ipAddress, EsPaymentMethod::PAYMENT_PESOPAYCC);
        $params = $this->input->post();
        if($isValidIp){
            log_message('error', 'DATA FEED --> '. json_encode($this->input->post()));
            header("Content-Type:text/plain");
            echo 'OK'; // acknowledgemenet
            if(strtolower($this->input->post('remark')) === "easydeal"){
                $curlUrl = $paymentConfig['payment_type']['pesopay']['Easydeal']['postback_url'];
                $curl = new Curl();
                $curl->setOpt(CURLOPT_SSL_VERIFYPEER, strtolower(ENVIRONMENT) === 'production');
                $curl->post($curlUrl, $params);
            }
            else{
                $paymentService = $this->serviceContainer['payment_service'];
                $paymentMethods = ["PesoPayGateway" => ["method" => "PesoPay"]]; 
                $params['txnId'] = $this->input->post('Ref'); 
                $paymentService->postBack($paymentMethods, null, null, $params);
            }
        }
        else{
            log_message('error', '404 Page Not Found --> PESOPAY DATAFEED');
            show_404();
        }
    }

    /**
     * NOTE STARTING HERE IS THE OLD API!
     */
    
        /**
     * Review cart data from mobile 
     * @return mixed
     */
    public function mobileReviewBridge()
    {
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager']; 

        $cartManager = $this->serviceContainer['cart_manager'];
        $paymentService = $this->serviceContainer['payment_service'];

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            header('Content-type: text/html'); 
            show_404(); 
            die;
        }

        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
        $memberId = $this->member->getIdMember();
        $itemArray = $cartManager->getValidatedCartContents($memberId); 
 
        $validated = $paymentService->validateCartData(['choosen_items'=>$itemArray]);
        $itemArray = $validated['itemArray'];
        $qtySuccess = $validated['itemCount'];

        // check the availability of the product
        $productAvailability = $this->checkProductAvailability($itemArray,$memberId);
        $itemArray = $productAvailability['item_array'];
        $successCount = $productAvailability['success_count']; 
        // check the purchase limit and payment type available
        $purchaseLimitPaymentType = $this->checkPurchaseLimitAndPaymentType($itemArray,$memberId);
        $itemArray = $purchaseLimitPaymentType['itemArray'];
        $paymentType = $purchaseLimitPaymentType['payment_type'];
        unset($paymentType['cdb']);
        $purchaseLimit = $purchaseLimitPaymentType['purchase_limit'];
        $soloRestriction = $purchaseLimitPaymentType['solo_restriction'];  

        foreach ($itemArray as $key => $value) {
            $productId = $value['id']; 
            $itemId = $value['product_itemID']; 
            $product_array =  $this->product_model->getProductById($productId);
            $newQty = $this->product_model->getProductQuantity($productId, FALSE, FALSE, $product_array['start_promo']);
            $maxqty = $newQty[$itemId]['quantity'];
            $itemArray[$key]['isAvailable'] = ($maxqty <= 0 || strtolower($value['isAvailable']) == "false") ? "false" : "true";
        }

        // get all possible error message
        $errorMessage = [];
        $canContinue = true;
        if($successCount != count($itemArray)){
            $canContinue = false;
            array_push($errorMessage, 'One or more of your item(s) is unavailable in your location.');
        }

        if($qtySuccess != count($itemArray)){

            $canContinue = false;
            array_push($errorMessage, 'The availability of one of your items is less than your desired quantity. 
                                    Someone may have purchased the item before you can complete your payment.
                                    Check the availability of your item and try again.');
        }

        if(!$purchaseLimit){
            $canContinue = false;
            array_push($errorMessage, 'You have exceeded your purchase limit for a promo of an item in your cart.');
        }

        if(!$soloRestriction){
            $canContinue = false;
            foreach ($itemArray as $key => $value) {
                $itemArray[$key]['isAvailable'] = "false";
            }
            array_push($errorMessage, 'One of your items can only be purchased individually.');
        }

        return [
            'cartData' => $itemArray,
            'errMsg' => $errorMessage,
            'canContinue' => $canContinue,
            'paymentType' => $paymentType,
        ];
    }

    /**
     * bridge to persist cod payment
     * @return mixed
     */
    public function mobilePersistCod()
    {
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager']; 
        $cartManager = $this->serviceContainer['cart_manager'];   

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            header('Content-type: text/html'); 
            show_404(); 
            die;
        } 

        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
        $memberId = $this->member->getIdMember();
        $itemArray = $cartManager->getValidatedCartContents($memberId); 
        $paymentType = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
        $txnid = $this->generateReferenceNumber($paymentType, $memberId); 
        $dataProcess = $this->cashOnDeliveryProcessing($memberId, $txnid, $itemArray, $paymentType);
        $dataProcess['txnid'] = $txnid;

        return $dataProcess;
    }

    /**
     * request Token for transaction 
     * @param  integer $paymentType 
     * @return JSON
     */
    public function mobilePayBridge($paymentType = "")
    {
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager']; 
        $cartManager = $this->serviceContainer['cart_manager'];
        $paymentService = $this->serviceContainer['payment_service'];

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            header('Content-type: text/html'); 
            show_404(); 
            die;
        } 

        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
        $memberId = $this->member->getIdMember();
        $itemArray = $cartManager->getValidatedCartContents($memberId); 

        $validated = $paymentService->validateCartData(['choosen_items'=>$itemArray]);
        $itemArray = $validated['itemArray'];
        $qtySuccess = $validated['itemCount'];

        if(intval($paymentType) === EsPaymentMethod::PAYMENT_PAYPAL){

            $remove = $this->payment_model->releaseAllLock($memberId);

            if($qtySuccess != count($itemArray)){
                return [
                    'e' => '0',
                    'd' => 'The availability of one of your items is less than your desired quantity. 
                            Someone may have purchased the item before you can complete your payment.'
                ];
            } 

            $paypalReturnURL    = base_url().'mobile/mobilepayment/paypalReturn'; 
            $paypalCancelURL    = base_url().'mobile/mobilepayment/paypalCancel'; 
            $requestData = $this->createPaypalToken($itemArray,$memberId,$paypalReturnURL,$paypalCancelURL);

            $urlArray = ['returnUrl' => $paypalReturnURL,'cancelUrl' => $paypalCancelURL];
            $mergeArray = array_merge($requestData,$urlArray);

            return $mergeArray;
        }
        else if(intval($paymentType) === EsPaymentMethod::PAYMENT_DRAGONPAY){
            if($qtySuccess != count($itemArray)){
                return [
                    'e' => '0',
                    'm' => 'The availability of one of your items is less than your desired quantity. 
                            Someone may have purchased the item before you can complete your payment.'
                ];
            } 

            return json_decode($this->createDragonPayToken($itemArray,$memberId),TRUE);
        }
    }
    
    /**
     * Persist payment request from mobile 
     * @param  integer $paymentType 
     * @param  string $txnid 
     * @param  string $payerId 
     * @return mixed
     */
    public function mobilePayPersist($paymentType = "", $txnid = "", $payerId = "")
    {
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager']; 

        $cartManager = $this->serviceContainer['cart_manager'];  
        $productManager = $this->serviceContainer['product_manager'];  

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            header('Content-type: text/html'); 
            show_404(); 
            die;
        }

        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
        $memberId = $this->member->getIdMember();
        $itemArray = $cartManager->getValidatedCartContents($memberId); 

        $this->session->set_userdata('member_id', $memberId);
        $this->session->set_userdata('choosen_items', $itemArray);

        if(intval($paymentType) === EsPaymentMethod::PAYMENT_PAYPAL){
            $persistData = $this->persistPaypal($memberId,$itemArray,$payerId,$txnid);
            return $persistData;
        }
    }


    /**
     * Unflag an order
     *
     * @return JSON
     */
    public function unFlagOrder()
    {
        $isAuthenticated = $this->serviceContainer['webservice_manager']
                                ->authenticate(
                                      $this->input->get(), 
                                      $this->input->get('hash'),
                                      true
                                );
        $response = [
            'isSuccessful' => false,
            'message' => 'You are not allowed to perform this action',
        ];

        if($isAuthenticated){
            $orderId = $this->input->post('orderId');
            $response = $this->serviceContainer['payment_service']
                             ->unFlagOrder($orderId);
        }
        
        echo json_encode($response);
    }


}


/* End of file payment.php */
/* Location: ./application/controllers/payment.php */

