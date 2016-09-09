<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;

class Payment extends MY_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->library('paypal');
        $this->load->library('dragonpay');
        $this->load->library('paypal');
        $this->load->library('pesopay');
        $this->load->library('xmlmap'); 
        $this->load->model('cart_model');
        $this->load->model('user_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('messages_model');
        $this->load->model('memberpage_model');
        session_start();

    }

    public $PayMentPayPal = 1;
    public $PayMentDragonPay = 2;
    public $PayMentCashOnDelivery = 3;
    public $PayMentPesoPayCC = 4;
    public $PayMentDirectBankDeposit = 5;

    function cart_items()
    {
        $res = true;
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };
        $unchecked = $this->input->post('itm');
        $carts = $this->cart->contents();
        for($x=0;$x < sizeof($unchecked);$x++):
            unset($carts[$unchecked[$x]]);
        endfor;
        //$this->session->unset_userdata('cart_contents');
        $cart_contentss=array('choosen_items'=>$carts);
        $this->session->set_userdata($cart_contentss);
        //save cart to es_member -> userdata
        $cart_items = serialize($this->session->userdata('cart_contents'));
        $id = $this->session->userdata('member_id');
        $this->cart_model->save_cartitems($cart_items,$id);

        $promo_allow = $this->cart_model->isCartCheckoutPromoAllow($cart_contentss['choosen_items']);
        if(!$promo_allow){
            $res = "Some items in your cart can only be purchased individually.";
        }
        echo json_encode($res);
        exit();
    }

    /**
     * Get items in cart depending on the promo type
     * and push data to choosen_items
     *
     * @param $promoType
     */
    public function setPromoItemsToPayment($promoType)
    {
        $cartContent = $this->cart->contents();
        $item = array();
        foreach ($cartContent as $key => $value) {
            if($value['promo_type'] == $promoType){
                $item[$key] = $cartContent[$key];
            }
        }
        $cart_contentss=array('choosen_items'=> $item);
        $this->session->set_userdata($cart_contentss);
    }

    /**
     * Review cart data from mobile
     * @param  mixed $itemArray
     * @param  integer $memberId
     * @return mixed
     */
    public function mobileReviewBridge($itemArray,$memberId)
    {
        // set session
        $this->session->set_userdata('member_id', $memberId); 
        $this->session->set_userdata('choosen_items', $itemArray);

        // update cart data details
        $qtySuccess = $this->resetPriceAndQty(); 

        // check the availability of the product
        $productAvailability = $this->checkProductAvailability($itemArray,$memberId);
        $itemArray = $productAvailability['item_array'];
        $successCount = $productAvailability['success_count'];
        $codCount = $productAvailability['cod_count']; 

        // check the purchase limit and payment type available
        $purchaseLimitPaymentType = $this->checkPurchaseLimitAndPaymentType($itemArray,$memberId);
        $paymentType = $purchaseLimitPaymentType['payment_type'];
        unset($paymentType['cdb']);
        $purchaseLimit = $purchaseLimitPaymentType['purchase_limit'];
        $soloRestriction = $purchaseLimitPaymentType['solo_restriction']; 

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

        if($codCount != count($itemArray)){
            // $canContinue = false;
            unset($paymentType['cod']);
            array_push($errorMessage, 'One of your items is unavailable for cash on delivery.'); 
        }

        if(!$purchaseLimit){
            $canContinue = false;
            array_push($errorMessage, 'You have exceeded your purchase limit for a promo of an item in your cart.');
        }

        if(!$soloRestriction){
            $canContinue = false;
            array_push($errorMessage, 'One of your items can only be purchased individually.');
        }

        return array(
            'cartData' => $itemArray,
            'errMsg' => $errorMessage,
            'canContinue' => $canContinue,
            'paymentType' => $paymentType,
        );
    }

    /**
     * request Token for transaction
     * @param  mixed $itemArray   [description]
     * @param  integer $memberId    [description]
     * @param  integer $paymentType [description]
     * @return JSON
     */
    public function mobilePayBridge($itemArray,$memberId,$paymentType)
    {
        // set session
        $this->session->set_userdata('member_id', $memberId); 
        $this->session->set_userdata('choosen_items', $itemArray);

        // update cart data details
        $qtySuccess = $this->resetPriceAndQty();

        if(intval($paymentType) === EsPaymentMethod::PAYMENT_PAYPAL){

            $remove = $this->payment_model->releaseAllLock($memberId);

            if($qtySuccess != count($itemArray)){
                return array(
                    'e' => '0',
                    'd' => 'One of the items in your cart is unavailable.'
                );
            } 

            $paypalReturnURL    = base_url().'mobile/mobilepayment/paypalReturn'; 
            $paypalCancelURL    = base_url().'mobile/mobilepayment/paypalCancel'; 
            $requestData = $this->createPaypalToken($itemArray,$memberId,$paypalReturnURL,$paypalCancelURL);

            $urlArray = array('returnUrl' => $paypalReturnURL,'cancelUrl' => $paypalCancelURL);
            $mergeArray = array_merge($requestData,$urlArray);

            return $mergeArray;
        }
        else if(intval($paymentType) === EsPaymentMethod::PAYMENT_DRAGONPAY){
            if($qtySuccess != count($itemArray)){
                return array(
                    'e' => '0',
                    'm' => 'One of the items in your cart is unavailable.'
                );
            } 

            return json_decode($this->createDragonPayToken($itemArray,$memberId),TRUE);
        }
    }

    /**
     * Persist payment request from mobile
     * @param  mixed $itemArray   [description]
     * @param  integer $memberId    [description]
     * @param  integer $paymentType [description]
     * @param  string $txnid       [description]
     * @param  string $payerId     [description]
     * @return mixed
     */
    public function mobilePayPersist($itemArray,$memberId,$paymentType,$txnid,$payerId)
    {
        // set session
        $this->session->set_userdata('member_id', $memberId); 
        $this->session->set_userdata('choosen_items', $itemArray);

        if(intval($paymentType) === EsPaymentMethod::PAYMENT_PAYPAL){
            $persistData = $this->persistPaypal($memberId,$itemArray,$payerId,$txnid);
            return $persistData;
        }
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

                if(count($details) >= 1){
                    $successCount++;
                    $availability = "Available";
                    $itemArray[$value['rowid']]['shipping_fee'] = $details[0]['price'];
                } 

                if(count($details) > 0){

                    $paymentService = $this->serviceContainer['payment_service'];
                    $paymentMethod = $paymentService->getUserPaymentMethod($value['member_id']);

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
    private function checkPurchaseLimitAndPaymentType($itemArray,$memberId)
    {
        $configPromo = $this->config->item('Promo');
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
        );
    }
    
    /**
     * Render payment review
     */
    public function review()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }

        $member_id =  $this->session->userdata('member_id');
        $remove = $this->payment_model->releaseAllLock($member_id);
        $qtySuccess = $this->resetPriceAndQty(); 
        $itemArray = $this->session->userdata('choosen_items'); 
        $itemCount = count($itemArray); 
        $address = $this->memberpage_model->get_member_by_id($member_id);

        // check the availability of the product
        $productAvailability = $this->checkProductAvailability($itemArray,$member_id);
        $itemArray = $productAvailability['item_array'];
        $successCount = $productAvailability['success_count'];
        $codCount = $productAvailability['cod_count'];
        $paypalCount = $productAvailability['paypal_count'];
        $dragonpayCount = $productAvailability['dragonpay_count'];
        $pesoPayCount = $productAvailability['pesopay_count'];
        $directBankCount = $productAvailability['directbank_count'];
        $data['shippingDetails'] = $productAvailability['shipping_details'];

        // check the purchase limit and payment type available
        $purchaseLimitPaymentType = $this->checkPurchaseLimitAndPaymentType($itemArray,$member_id);
        $paymentType = $purchaseLimitPaymentType['payment_type'];
        $promoteSuccess['purchase_limit'] = $purchaseLimitPaymentType['purchase_limit'];
        $promoteSuccess['solo_restriction'] = $purchaseLimitPaymentType['solo_restriction']; 

        $data['paymentType'] = $paymentType;
        $data['promoteSuccess'] = $promoteSuccess;

        if(!count($itemArray) <= 0){
            $data['cat_item'] = $itemArray;
            $data['qtysuccess'] = ($qtySuccess == $itemCount ? true : false);
            $data['success'] = ($successCount == $itemCount ? true : false);
            $data['codsuccess'] = ($codCount == $itemCount ? true : false);
            $data['paypalsuccess'] = ($paypalCount == $itemCount ? true : false);
            $data['dragonpaysuccess'] = ($dragonpayCount == $itemCount ? true : false);
            $data['pesopaysucess'] = ($pesoPayCount == $itemCount ? true : false);
            $data['directbanksuccess'] = ($directBankCount == $itemCount ? true : false);
 
            $header['title'] = 'Payment Review | Easyshop.ph';
            $header = array_merge($header,$this->fill_header()); 
            $data = array_merge($data, $this->memberpage_model->getLocationLookup());
            $data = array_merge($data,$address);

            // Get all available points
            $data['maxPoint'] = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsPoint')
                            ->getMaxPoint(intval($member_id));
            
            // Load view
            $this->load->view('templates/header', $header); 
            $this->load->view('pages/payment/payment_review_responsive' ,$data);  
            $this->load->view('templates/footer');  
        }
        else{
           redirect('/cart/', 'refresh'); 
       }
    }

    #START OF PAYPAL PAYMENT
    #SET UP PAYPAL FOR PARAMETERS
    #SEE REFERENCE SITE FOR THE PARAMETERS
    # https://developer.paypal.com/webapps/developer/docs/classic/express-checkout/integration-guide/ECCustomizing/
    function paypal_setexpresscheckout() 
    {
        header('Content-type: application/json');
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };

        $paypalReturnURL    = base_url().'pay/paypal'; 
        $paypalCancelURL    = base_url().'payment/review'; 

        $member_id =  $this->session->userdata('member_id');
        $remove = $this->payment_model->releaseAllLock($member_id);
        $qtysuccess = $this->resetPriceAndQty(); 
        $itemList = $this->session->userdata('choosen_items'); 
        $productCount = count($itemList);  

        $cnt = 0; 
        $paypalType = $this->input->post('paypal'); 
        $dataitem = '';  
        $paymentType = $this->PayMentPayPal; #paypal 

        if($productCount <= 0){
            echo  '{"e":"0","d":"There are no items in your cart."}';
            exit();
        } 

        if($qtysuccess != $productCount){
            echo  '{"e":"0","d":"One of the items in your cart is unavailable."}';
            exit();
        } 

        $paypalProcess = $this->createPaypalToken($itemList,$member_id,$paypalReturnURL,$paypalCancelURL,$paypalType);

        echo json_encode($paypalProcess);
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
    function ipn2()
    {
        // CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        // Set this to 0 once you go live or don't require logging.
        $PayPalMode = $this->paypal->getMode(); 
        $paypalmode = ($PayPalMode == '.sandbox' ? '.sandbox' : '');
        define("DEBUG", 1);
        define("LOG_FILE", "./ipn.log");

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
                $orderId = $this->payment_model->cancelTransaction($parent_txn_id,true);
                $orderHistory = array(
                    'order_id' => $orderId,
                    'order_status' => 2,
                    'comment' => 'Paypal transaction ' . $payment_status
                    );
                $this->payment_model->addOrderHistory($orderHistory);
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

    #PROCESS PAYPAL
    function paypal()
    { 
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }
 
        $status = 'f'; 
        $member_id =  $this->session->userdata('member_id'); 
        $itemList =  $this->session->userdata('choosen_items');    
        $productCount = count($itemList);   

        if($this->input->get("token") && $this->input->get("PayerID")){
            $payerid = $this->input->get("PayerID");
            $token = $this->input->get("token");
            $persistData = $this->persistPaypal($member_id,$itemList,$payerid,$token);
            $message = $persistData['message'];
            $status = $persistData['status'];
            $txnid = $persistData['txnid'];
        }
        else{
            $message = 'Some parameters are missing.';
        }

        $this->generateFlash($txnid,$message,$status);
        redirect(base_url().'payment/success/paypal?txnid='.$txnid.'&msg='.$message.'&status='.$status, 'refresh'); 
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
        $status = 'f';
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
                            $status = 's';         
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
    public function cashOnDeliveryProcessing($memberId,$txnid,$itemList,$paymentType)
    {
        $address = $this->memberpage_model->get_member_by_id($memberId); 
        $prepareData = $this->processData($itemList,$address);
        $grandTotal = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $return = $this->payment_model->payment($paymentType,$grandTotal,$memberId,$productstring,count($itemList),json_encode($itemList),$txnid);

        if($return['o_success'] <= 0){
            $message = $return['o_message'];
            $status = 'f';
        }
        else{
            $v_order_id = $return['v_order_id'];
            $invoice = $return['invoice_no'];
            $status = 's';
            $message = ($paymentType == $this->PayMentDirectBankDeposit) 
                            ? 'Your payment has been completed through Direct Bank Deposit.' 
                            : 'Your payment has been completed through Cash on Delivery.';

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

    public function payCashOnDelivery()
    {
        if($this->input->post('promo_type') !== FALSE ){
            $this->setPromoItemsToPayment($this->input->post('promo_type'));
        }

        if(!$this->session->userdata('member_id') 
            || !$this->input->post('paymentToken') 
            || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }

        $lastDigit = substr($this->input->post('paymentToken'), -1);
        $qtysuccess = $this->resetPriceAndQty();
        $status = 'f';
        if($lastDigit == 2) {
            $paymentType = EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT;
            $textType = 'directbankdeposit';
        }
        else{
            $paymentType = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
            $textType = 'cashondelivery';
        }

        $member_id =  $this->session->userdata('member_id');
        $itemList =  $this->session->userdata('choosen_items');
        $productCount = count($itemList);
        $txnid = $this->generateReferenceNumber($paymentType,$member_id);

        if($qtysuccess == $productCount){
            $codData = $this->cashOnDeliveryProcessing($member_id,$txnid,$itemList,$paymentType);
            $status = $codData['status'];
            $message = $codData['message'];
        }
        else{
            $message = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.'; 
        }

        $this->generateFlash($txnid,$message,$status);
        redirect(base_url().'payment/success/'.$textType.'?txnid='.$txnid.'&msg='.$message.'&status='.$status, 'refresh');
    }

    #START OF DRAGONPAY PAYMENT
    function payDragonPay()
    {
        header('Content-type: application/json');

        $paymentType = $this->PayMentDragonPay; 

        $member_id =  $this->session->userdata('member_id'); 
        $remove = $this->payment_model->releaseAllLock($member_id);
        $qtysuccess = $this->resetPriceAndQty(TRUE);
        $itemList =  $this->session->userdata('choosen_items');
        $productCount = count($itemList);

        if($qtysuccess != $productCount){ 
            die('{"e":"0","m":"Item quantity not available."}');
        } 

        $requestToken = $this->createDragonPayToken($itemList,$member_id);

        echo $requestToken;
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

    function dragonPayPostBack()
    {
        header("Content-Type:text/plain");

        $paymentType = $this->PayMentDragonPay; 

        $txnId = $this->input->post('txnid');
        $refNo = $this->input->post('refno');
        $status =  $this->input->post('status');
        $message = $this->input->post('message');
        $digest = $this->input->post('digest');

        $payDetails = $this->payment_model->selectFromEsOrder($txnId,$paymentType);
        $invoice = $payDetails['invoice_no'];
        $orderId = $payDetails['id_order'];
        $member_id = $payDetails['buyer_id'];
        $itemList = json_decode($payDetails['data_response'],true); 
        $postBackCount = $payDetails['postbackcount']; 

        $address = $this->memberpage_model->get_member_by_id($member_id);  

        $prepareData = $this->processData($itemList,$address);  
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];

        if(strtolower($status) == "p" || strtolower($status) == "s"){

            if($postBackCount == "0"){

                foreach ($itemList as $key => $value) {               
                    $itemComplete = $this->payment_model->deductQuantity($value['id'],$value['product_itemID'],$value['qty']);  
                    $this->product_model->update_soldout_status($value['id']);            
                }

                $locked = $this->lockItem($toBeLocked,$orderId,'delete'); 
            }

            $orderStatus = (strtolower($status) == "s" ? 0 : 99); 
            $complete = $this->payment_model->updatePaymentIfComplete($orderId,json_encode($itemList),$txnId,$paymentType,$orderStatus,0);

            if($postBackCount == "0"){
                // send email to buyer
                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice),TRUE,FALSE);  
            }

            if(strtolower($status) == "s"){ 
                // send email to seller
                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice),FALSE,TRUE);  
            }

        }elseif(strtolower($status) == "f" ){

            $locked = $this->lockItem($toBeLocked,$orderId,'delete');
            $orderId = $this->payment_model->cancelTransaction($txnId,true);
            $orderHistory = array(
                'order_id' => $orderId,
                'order_status' => 2,
                'comment' => 'Dragonpay transaction failed: ' . $message
                );
            $this->payment_model->addOrderHistory($orderHistory);
        }

        echo 'result=OK'; 

    }


    function dragonPayReturn()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }

        $paymentType = $this->PayMentDragonPay;   

        $txnId = $this->input->get('txnid');
        $refNo = $this->input->get('refno');
        $status =  $this->input->get('status');
        $message = $this->input->get('message');
        $digest = $this->input->get('digest');

        if(strtolower($status) == "p" || strtolower($status) == "s"){

            $return = $this->payment_model->selectFromEsOrder($txnId,$paymentType); 
            $orderId = $return['id_order'];
            $status = 's';
            $message = 'Your payment has been completed through Dragon Pay. '.urldecode($message);  
            $this->removeItemFromCart(); 

        }else{
            $status = 'f';
            $message = 'Transaction Not Completed. '.urldecode($message);
        }
        
        $this->generateFlash($txnId,$message,$status);
        redirect(base_url().'payment/success/dragonpay?txnid='.$txnId.'&msg='.$message.'&status='.$status, 'refresh');
    }

    #START OF PESOPAY PAYMENT
    function payPesoPay()
    {
        header('Content-type: application/json');

        $paymentType = $this->PayMentPesoPayCC; 

        $member_id =  $this->session->userdata('member_id'); 
        $remove = $this->payment_model->releaseAllLock($member_id);
        $qtysuccess = $this->resetPriceAndQty(TRUE); 
        $itemList =  $this->session->userdata('choosen_items');
        $productCount = count($itemList); 

        if($qtysuccess != $productCount){
           die('{"e":"0","m":"Item quantity not available."}');
        } 

        $address = $this->memberpage_model->get_member_by_id($member_id);  

        $prepareData = $this->processData($itemList,$address);
        $grandTotal = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];  
        $transactionID = $this->generateReferenceNumber($paymentType,$member_id);

        $return = $this->payment_model->payment($paymentType,$grandTotal,$member_id,$productstring,$productCount,json_encode($itemList),$transactionID);
        
        if($return['o_success'] <= 0){
            die('{"e":"0","m":"'.$return['o_message'].'"}');  
        }else{ 
            $mode =  $this->pesopay->getMode();
            $data = array(
                'orderRef' => $transactionID,
                'amount' => $grandTotal,
                'url' => $mode['url'],
                'merchantId' =>  $mode['merchantId']
                );
            $orderId = $return['v_order_id'];
            $locked = $this->lockItem($toBeLocked,$orderId,'insert');   
            die('{"e":"1","d":'.json_encode($this->load->view('pages/payment/pesopayform',$data,TRUE)).'}');
        }
    }


    function pesoPayReturnUrl()
    { 
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }
 
        $status =  $this->input->get('status');
        $txnId = $this->input->get('Ref');
        $paymentType = $this->PayMentPesoPayCC;     
 
        if(strtolower($status) == "s"){

            $return = $this->payment_model->selectFromEsOrder($txnId,$paymentType); 
            $orderId = $return['id_order'];
            $message = 'Your payment has been completed through Credit/Debit Card.';
            $this->removeItemFromCart();  
   
        }else{
            $txnId = $this->input->get('Fref');
            $message = 'Transaction Not Completed.';
            $status = 'f';
        }

        $this->generateFlash($txnId,$message,$status);
        redirect(base_url().'payment/success/debitcreditcard?txnid='.$txnId.'&msg='.$message.'&status='.$status, 'refresh');
    }

    function pesoPayDataFeed()
    {

        header("Content-Type:text/plain");

        $ref = $this->input->post('Ref'); 
        $successCode = $this->input->post('successcode'); 

        echo 'OK'; # acknowledgemenet
        
        $paymentType = $this->PayMentPesoPayCC; 

        $txnId = $ref;
        $payDetails = $this->payment_model->selectFromEsOrder($txnId,$paymentType);
        $invoice = $payDetails['invoice_no'];
        $orderId = $payDetails['id_order'];
        $member_id = $payDetails['buyer_id'];
        $itemList = json_decode($payDetails['data_response'],true);  

        $address = $this->memberpage_model->get_member_by_id($member_id); 

        $prepareData = $this->processData($itemList,$address);  
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $locked = $this->lockItem($toBeLocked,$orderId,'delete');
        
        if($successCode == "0"){

            foreach ($itemList as $key => $value) {               
                $itemComplete = $this->payment_model->deductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                $this->product_model->update_soldout_status($value['id']);
            }

            $complete = $this->payment_model->updatePaymentIfComplete($orderId,json_encode($itemList),$txnId,$paymentType,0,0);
            $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));  

        }else{

            $orderId = $this->payment_model->cancelTransaction($txnId,true);
            $orderHistory = array(
                'order_id' => $orderId,
                'order_status' => 2,
                'comment' => 'Dragonpay transaction failed: ' . $message
                );
            $this->payment_model->addOrderHistory($orderHistory);
        }
    }


    /** 
     * Generates the payment succes or error view
     * 
     * @param string $mode
     */
    public function paymentSuccess($mode = "easyshop")
    {   
        if(strtolower($mode) == 'cashondelivery'){
            $paymentType = $this->PayMentCashOnDelivery;
        }
        elseif (strtolower($mode) == 'debitcreditcard') {
            $paymentType = $this->PayMentPesoPayCC;
        }
        elseif (strtolower($mode) == 'dragonpay') {
            $paymentType = $this->PayMentDragonPay;
        }
        elseif (strtolower($mode) == 'PayMentDirectBankDeposit') {
            $xmlResourceService = $this->serviceContainer['xml_resource'];
            $xmlfile =  $xmlResourceService->getContentXMLfile();
            $esAccountNumber = $this->xmlmap->getFilenameID($xmlfile,'bank-account-number');
//             $esBank = $this->xmlmap->getFilenameID($xmlfile,'bank-name');
            $paymentType = $this->PayMentPayPal;
        }
        elseif (strtolower($mode) == 'paypal') {
            $paymentType = $this->PayMentPayPal;
        }
        else{
            $paymentType = $this->PayMentCashOnDelivery;
        }

        // $txnId = ($this->session->flashdata('txnid') ? $this->session->flashdata('txnid') : urldecode($this->input->get('txnid')));
        // $response['message'] = ($this->session->flashdata('msg') ? $this->session->flashdata('msg') : urldecode($this->input->get('msg')));
        // $status = ($this->session->flashdata('status') ? $this->session->flashdata('status') : urldecode($this->input->get('status'))); 

        $txnId =  $response['txnid'] = $this->session->flashdata('txnid'); 
        $response['message'] = $this->session->flashdata('msg');
        $status = $this->session->flashdata('status');  

        $response['completepayment'] = ($status == 's' ? true : false);
        $payDetails = $this->payment_model->selectFromEsOrder($txnId,$paymentType);
        $response['itemList'] = json_decode($payDetails['data_response'],true);
        if($paymentType == 3 && $status == 'f'){
            $member_id =  $this->session->userdata('member_id'); 
            $address = $this->memberpage_model->get_member_by_id($member_id); 
            $prepareData = $this->processData($this->session->userdata('choosen_items'),$address);
            $itemList = $prepareData['newItemList']; 
            $response['itemList'] = $itemList;
        }

        $response['available'] = true;
        if($txnId == ''){
            $response['available'] = false;
            $response['message'] = 'This section is not available.';
            $analytics = array();
        }
        else{
            #google analytics data
            $analytics = $this->ganalytics($response['itemList'],$payDetails['id_order']);
            #end of google analytics data
        }


        $response['analytics'] =  $analytics;
        $response['invoice_no'] = $payDetails['invoice_no'];
        $response['total'] = $payDetails['total'];
        $response['dateadded'] = $payDetails['dateadded'];
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());

        $this->load->view('templates/header', $data);
        // $this->load->view('pages/payment/payment_response' ,$response);  
        $this->load->view('pages/payment/payment_response_responsive' ,$response);  
        $this->load->view('templates/footer_full'); 
 
   }


    /**
     * Remove the chosen items for checkout from the cart
     *
     */
    public function removeItemFromCart()
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
    function sendNotification($data, $buyerFlag = TRUE, $sellerFlag = TRUE) 
    {
        $xmlResourceService = $this->serviceContainer['xml_resource'];
        $xmlfile =  $xmlResourceService->getContentXMLfile();

        $emailService = $this->serviceContainer['email_notification'];
        $smsService = $this->serviceContainer['mobile_notification'];

        $imageArray = array(
            "/assets/images/landingpage/templates/header-img.png"
            , "/assets/images/appbar.home.png"
            , "/assets/images/appbar.message.png"
            , "/assets/images/landingpage/templates/facebook.png"
            , "/assets/images/landingpage/templates/twitter.png"
        );

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
            //case 4:
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

        // Send email to buyer
        if($buyerFlag){
            $buyerEmail = $transactionData['buyer_email'];
            $buyerData = $transactionData;
            unset($buyerData['seller']);
            unset($buyerData['buyer_email']);

            # Additional buyerData for email template
            $buyerData['store_link'] = base_url() ; #user appended on template
            $buyerData['msg_link'] = base_url() . "messages/#"; #user appended on template
            $buyerMsg = $this->parser->parse('emails/email_purchase_notification_buyer',$buyerData,true);
            $buyerSubject = $this->lang->line('notification_subject_buyer');
            $buyerSmsMsg = $buyerData['buyer_name'] . $this->lang->line('notification_txtmsg_buyer');

            $emailService->setRecipient($buyerEmail)
                         ->setSubject($buyerSubject)
                         ->setMessage($buyerMsg, $imageArray)
                         ->sendMail();

            $smsService->setMobile($buyerData['buyer_contactno'])
                       ->setMessage($buyerSmsMsg)
                       ->sendSms();
            
            #Send message via easyshop_messaging to buyer
            if($this->user_model->getUserById($sender)){
                $this->messages_model->send_message($sender,$data['member_id'],$this->lang->line('message_to_buyer'));
            }
        }

        // Send email to seller of each product - once per seller
        if($sellerFlag){
            $sellerData = array(
                'id_order' => $transactionData['id_order'],
                'dateadded' => $transactionData['dateadded'],
                'buyer_name' => $transactionData['buyer_name'],
                'invoice_no' => $transactionData['invoice_no'],
                'payment_msg_seller' => $transactionData['payment_msg_seller'],
                'payment_method_name' => $transactionData['payment_method_name']
            );

            foreach($transactionData['seller'] as $seller_id => $seller){
                $sellerEmail = $seller['email'];
                $sellerData = array_merge( $sellerData, array_slice($seller,1,9) );
                $sellerData['totalprice'] = number_format($seller['totalprice'], 2, '.' , ',');
                $sellerData['buyer_slug'] = $transactionData['buyer_slug'];

                # Additional sellerData for email template
                $sellerSubject = $this->lang->line('notification_subject_seller');
                $sellerData['store_link'] = base_url() . $sellerData['buyer_slug'];
                $sellerData['msg_link'] = base_url() . "messages/#" . $sellerData['buyer_name'];
                $sellerMsg = $this->parser->parse('emails/email_purchase_notification_seller',$sellerData,true);
                $sellerSmsMsg = $seller['seller_name'] . $this->lang->line('notification_txtmsg_seller');

                $emailService->setRecipient($sellerEmail)
                             ->setSubject($sellerSubject)
                             ->setMessage($sellerMsg, $imageArray)
                             ->sendMail();
                
                $smsService->setMobile($seller['seller_contactno'])
                           ->setMessage($sellerSmsMsg)
                           ->sendSms();

                #Send message via easyshop_messaging to seller
                if($this->user_model->getUserById($sender)){
                    $this->messages_model->send_message($sender,$seller_id,$this->lang->line('message_to_seller'));
                }

            }//close foreach seller loop
        }
    }

    /*
     *  Function to generate google analytics data
     */
    function ganalytics($itemList,$v_order_id)
    {
        $analytics = array(); 
        foreach ($itemList as $key => $value) {

            $product = $this->product_model->getProductById($value['id'], true);

            $tempAnalytics = array(
                'id' => $v_order_id,
                'affiliation' => $value['seller_username'],
                'revenue' => $value['subtotal'],
                'shipping'=> $value['otherFee'],
                'tax'=> '0.00',
                'currency' => 'PHP', 
                'data' => array(
                    'id' => $v_order_id,
                    'name' => $value['name'],
                    'sku' => $value['id'],
                    'category' => $product['category'],
                    'price' => $value['price'],
                    'quantity' => $value['qty']
                    )
                );  
            array_push($analytics, $tempAnalytics); 
        }

        return $analytics;
    }

    function processData($itemList,$address)
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
            $tax_amt = 0;
            $isPromote = ($value['is_promote'] == 1) ? $isPromote += 1 : $isPromote += 0;
            $productItem =  $value['product_itemID'];
            $details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
            $shipping_amt = (isset($details[0]['price'])) ? $details[0]['price'] : 0 ;
            $otherFee = ($tax_amt + $shipping_amt) * $orderQuantity;
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

    function changeAddress()
    {
        header('Content-type: application/json');
        $uid = $this->session->userdata('member_id');
        $postdata = array(
            'consignee' => $this->input->post('consignee'),
            'mobile' => $this->input->post('c_mobile'),
            'telephone' => $this->input->post('c_telephone'),
            'stateregion' => $this->input->post('c_stateregion'),
            'city' => $this->input->post('c_city'),
            'address' => $this->input->post('c_address'),
            'country' => $this->input->post('c_country'),
            'lat' => $this->input->post('temp_lat'),
            'lng' => $this->input->post('temp_lng'),
            'addresstype' => 1,
            );
        if(trim($this->input->post('consignee')) == "" || $this->input->post('c_city') == 0 || $this->input->post('c_stateregion') == 0 || trim($this->input->post('c_address')) == "" || trim($this->input->post('c_mobile')) == "")
        {
            echo json_encode("Fill the required fields!");
            exit();
        }else if(!is_numeric($this->input->post('c_mobile')) || strlen($this->input->post('c_mobile')) != 11 || (!preg_match("/^(09|08)[0-9]{9}$/", $this->input->post('c_mobile')))){
            echo json_encode("<b>MOBILE NUMBER</b> should be 11 digits long and starts with 09. eg: 09051235678");
            exit();
        }else if(trim($this->input->post('c_telephone')) != "" && (preg_match("/^([0-9]{4}-){3}[0-9]{4}$/", $this->input->post('c_telephone')) || !is_numeric(str_replace('-', '', $this->input->post('c_telephone'))))){
            echo json_encode("<b>TELEPHONE NUMBER</b> can only be numbers and hyphen. eg: 354-5973");
        }else{
            $postdata['default_add'] = "off";
            $postdata['mobile'] = substr($postdata['mobile'], 1);            
            $addressId = $this->memberpage_model->getAddress($uid,'1')['id_address'];
            $data = $this->memberpage_model->editAddress($uid, $postdata,$addressId);
            $this->output->set_output(json_encode($data));
            echo json_encode("success");
            exit(); 
        }  
    }

    function getLocation()
    {
        $id = $this->input->post('sid');
        $itemId = $this->input->post('iid');
        $itemName = $this->input->post('name');
        $data = array( 
            'shipment_information' => $this->product_model->getShipmentInformation($id),
            'shiploc' => $this->product_model->getLocation(),
            'item_id' => $itemId,
            'item_name' => $itemName
            );

        $data = json_encode($this->load->view('pages/payment/payment_review_popout',$data,TRUE));  
        echo $data;
    }

    function lockItem($ids = array(),$orderId,$action = 'insert')
    {
        foreach ($ids as $key => $value) {
            $lock = $this->payment_model->lockItem($key,$value,$orderId,$action);
        }
    }

    function resetPriceAndQty($condition = FALSE)
    {   
        $carts = $this->session->all_userdata(); 
        $itemArray = $carts['choosen_items'];
        $qtysuccess = 0;

        foreach ($itemArray as $key => $value) {

            $productId = $value['id']; 
            $itemId = $value['product_itemID']; 
            
            $product_array =  $this->product_model->getProductById($productId);
  
            /** NEW QUANTITY **/
            $newQty = $this->product_model->getProductQuantity($productId, FALSE, $condition, $product_array['start_promo']);
            $maxqty = $newQty[$itemId]['quantity'];
            $qty = $value['qty']; 
            $itemArray[$value['rowid']]['maxqty'] = $maxqty;
            $qtysuccess = ($maxqty >= $qty ? $qtysuccess + 1: $qtysuccess + 0);

            /** NEW PRICE **/
            $promoPrice = $product_array['price']; 
            $additionalPrice = $value['additional_fee'];
            $finalPromoPrice = $promoPrice + $additionalPrice;
            $itemArray[$value['rowid']]['price'] = $finalPromoPrice;
            $subtotal = $finalPromoPrice * $qty;
            $itemArray[$value['rowid']]['subtotal'] = $subtotal;
        }

        $this->session->set_userdata('choosen_items', $itemArray);

        return $qtysuccess;
    }

    function generateFlash($txnId,$message,$status)
    {
        $this->session->set_flashdata('txnid',$txnId);
        $this->session->set_flashdata('msg',$message);
        $this->session->set_flashdata('status',$status);
    }

    function generateReferenceNumber($paymentType,$member_id){
    
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
    function pay()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }

        /* JSON Decode*/
        $paymentMethods = json_decode($this->input->post('paymentMethods'),true);

        $pointsAllocated = array_key_exists("PointGateway", $paymentMethods) ? $paymentMethods["PointGateway"]["amount"] : "0.00";

        if(reset($paymentMethods)['method'] === 'CashOnDelivery'){
            if($this->input->post('promo_type') !== FALSE ){
                $this->setPromoItemsToPayment($this->input->post('promo_type'));
            }
        }

        $carts = $this->session->all_userdata();

        $paymentService = $this->serviceContainer['payment_service'];

        // Validate Cart Data and subtract points
        $validatedCart = $paymentService->validateCartData($carts, reset($paymentMethods)['method'], $pointsAllocated);
        $this->session->set_userdata('choosen_items', $validatedCart['itemArray']); 
        $response = $paymentService->pay($paymentMethods, $validatedCart, $this->session->userdata('member_id'));

        extract($response);
        $this->removeItemFromCart();  
        $this->sendNotification(array('member_id'=>$this->session->userdata('member_id'), 'order_id'=>$orderId, 'invoice_no'=>$invoice));
        $this->generateFlash($txnid,$message,$status);
        echo base_url().'payment/success/'.$textType.'?txnid='.$txnid.'&msg='.$message.'&status='.$status, 'refresh';
    }

    /**
     * Postback function for PayPal
     * 
     */
    public function postBackPayPal()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }

        $carts = $this->session->all_userdata();
        $paymentService = $this->serviceContainer['payment_service'];

        // get credit points if there are any
        $points = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsPoint')
                        ->findOneBy(["member" => intval($this->session->userdata('member_id'))]);
        
        // Create a fake decoded JSON for payment service
        if($points && intval($points->getCreditPoint()) > 0 ){
            $paymentMethods = [
                "PaypalGateway" => 
                    ["method" => "PayPal", "getArray" => $this->input->get()],
                "PointGateway" =>
                    ["method" => "Point", "amount" => $points->getCreditPoint(), "pointtype" => "purchase"]
            ];
            $points->setCreditPoint(0);
            $this->serviceContainer['entity_manager']->flush();
        }
        else{
            $paymentMethods = ["PaypalGateway" => ["method" => "PayPal", "getArray" => $this->input->get()]];
        }
        
        $pointsAllocated = array_key_exists("PointGateway", $paymentMethods) ? $paymentMethods["PointGateway"]["amount"] : "0.00";

        // Validate Cart Data
        $validatedCart = $paymentService->validateCartData($carts, reset($paymentMethods)['method'], $pointsAllocated);
        $this->session->set_userdata('choosen_items', $validatedCart['itemArray']); 

        $response = $paymentService->postBack($paymentMethods, $validatedCart, $this->session->userdata('member_id'), null, null);
    
        extract($response);
        $this->removeItemFromCart();
        $this->sendNotification(array('member_id'=>$this->session->userdata('member_id'), 'order_id'=>$orderId, 'invoice_no'=>$invoice));
        $this->generateFlash($txnid,$message,$status);
        redirect(base_url().'payment/success/paypal?txnid='.$txnid.'&msg='.$message.'&status='.$status, 'refresh'); 
    }

    public function returnDragonPay()
    {
        if(!$this->session->userdata('member_id') || !$this->session->userdata('choosen_items')){
            redirect(base_url().'home', 'refresh');
        }
        $paymentService = $this->serviceContainer['payment_service'];
        $paymentMethods = ["DragonPayGateway" => ["method" => "DragonPay"]];

        $params['txnId'] = $this->input->get('txnid');
        $params['refNo'] = $this->input->get('refno');
        $params['status'] =  $this->input->get('status');
        $params['message'] = $this->input->get('message');
        $params['digest'] = $this->input->get('digest');

        $response = $paymentService->returnMethod($paymentMethods, $params);
        
        extract($response);

        if($status === "s" || $status === "p"){
            $this->removeItemFromCart();
        }
        $this->generateFlash($txnId,$message,$status);
        redirect(base_url().'payment/success/dragonpay?txnid='.$txnId.'&msg='.$message.'&status='.$status, 'refresh');
    }

    public function postBackDragonPay()
    {
        header("Content-Type:text/plain");
        $paymentService = $this->serviceContainer['payment_service'];
        $paymentMethods = ["DragonPayGateway" => ["method" => "DragonPay"]];

        $params['txnId'] = $this->input->post('txnid');
        $params['refNo'] = $this->input->post('refno');
        $params['status'] =  $this->input->post('status');
        $params['message'] = $this->input->post('message');
        $params['digest'] = $this->input->post('digest');

        $response = $paymentService->postBack($paymentMethods, null, null, $this, $params);
        echo 'result=OK'; 
    }

}


/* End of file payment.php */
/* Location: ./application/controllers/payment.php */

