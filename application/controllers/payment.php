<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends MY_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->library('paypal');
        $this->load->library('dragonpay');
        $this->load->library('paypal');
        $this->load->library('xmlmap');
        $this->load->model('user_model');
        $this->load->model('cart_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('memberpage_model'); 
        $this->load->model('search_model'); 
        session_start();
    }

    public $PayMentPayPal = 1;
    public $PayMentDragonPay = 2;
    public $PayMentCashOnDelivery = 3;
    public $PayMentDragonPayOnlineBanking = 4;
    public $PayMentDirectBankDeposit = 5;

    // SANDBOX
    public $PayPalMode             = 'sandbox'; 
    public $PayPalApiUsername      = 'easyseller_api1.yahoo.com'; 
    public $PayPalApiPassword      = '1396000698'; 
    public $PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Au1bGvwwVcv0garAliLq12YWfivG';  


    // LIVE
   // public $PayPalMode             = ''; 
   // public $PayPalApiUsername      = 'admin_api1.easyshop.ph'; 
   // public $PayPalApiPassword      = 'GDWFS6D9ACFG45E7'; 
   // public $PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Adro7yAfl2NInYAAVfFFipJ-QQhT'; 

    
    public $PayPalCurrencyCode     = 'PHP';

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
    }

    function review()
    {
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        }

        $qtySuccess = $this->resetPriceAndQty();
        $configPromo = $this->config->item('Promo');
        $carts = $this->session->all_userdata();
        $itemArray = $carts['choosen_items'];
        $member_id =  $this->session->userdata('member_id');

        $address = $this->memberpage_model->get_member_by_id($member_id);

        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        if($city > 0){  
            $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
            $region = $cityDetails['parent_id'];
            $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
            $majorIsland = $cityDetails['parent_id'];
        }
        $itemCount = count($itemArray);
        $successCount = 0;
        $codCount = 0; 
        $data['shippingDetails'] = false; 
        
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
                $codCount = ($details[0]['is_cod'] >= 1 ? $codCount + 1: $codCount + 0);
                $itemArray[$value['rowid']]['cash_delivery'] = $details[0]['is_cod'];
                }

                $data['shippingDetails'] = true; 
            }

            $seller = $value['member_id'];
            $sellerDetails = $this->memberpage_model->get_member_by_id($seller);
            $itemArray[$value['rowid']]['availability'] = ($availability == "Available" ? true : false);
            $itemArray[$value['rowid']]['seller_username'] = $sellerDetails['username'];
        }
        
        $paymentType = $configPromo[0]['payment_method'];        
        $promoteSuccess['purchase_limit'] = true;
        $promoteSuccess['solo_restriction'] = true;
        
        /*  
         *   Changed code to be able to adopt for any promo type
         */
        if($this->cart_model->isCartCheckoutPromoAllow($itemArray)){    
            foreach ($itemArray as $key => $value) {
                $qty = $value['qty'];
                $paymentType = array_intersect ( $paymentType , $configPromo[$value['promo_type']]['payment_method']);
                $purchase_limit = $configPromo[$value['promo_type']]['purchase_limit'];
                $can_purchase = $this->product_model->is_purchase_allowed($member_id ,$value['promo_type']);
                if($purchase_limit < $qty || (!$can_purchase) ){
                    $promoteSuccess['purchase_limit'] = false;
                    break;
                }
            }
        }else{
            $promoteSuccess['solo_restriction'] = false;
        }

        $data['paymentType'] = $paymentType;        
        $data['promoteSuccess'] = $promoteSuccess;

        if(!count($itemArray) <= 0){ 
            
            $data['cat_item'] = $itemArray;
            $data['title'] = 'Payment | Easyshop.ph';
            $data['qtysuccess'] = ($qtySuccess == $itemCount ? true : false);
            $data['success'] = ($successCount == $itemCount ? true : false);
            $data['codsuccess'] = ($codCount == $itemCount ? true : false);

            $data = array_merge($data,$this->fill_header()); 
            $data = array_merge($data, $this->memberpage_model->getLocationLookup());
            $data = array_merge($data,$address);

            $this->load->view('templates/header', $data);
            $this->load->view('pages/payment/payment_review' ,$data);  
            $this->load->view('templates/footer');  
        }else{
           redirect('/cart/', 'refresh'); 
       }
    }

	#SET UP PAYPAL FOR PARAMETERS
    #SEE REFERENCE SITE FOR THE PARAMETERS
    # https://developer.paypal.com/webapps/developer/docs/classic/express-checkout/integration-guide/ECCustomizing/
    function paypal_setexpresscheckout() 
    {      

        header('Content-type: application/json');
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };
        
        $PayPalMode         = $this->paypal->getMode(); 
        $PayPalReturnURL    = base_url().'pay/paypal'; 
        $PayPalCancelURL    = base_url().'payment/review'; 

        $member_id =  $this->session->userdata('member_id');
        $remove = $this->payment_model->releaseAllLock($member_id);
        $qtysuccess = $this->resetPriceAndQty();
        $carts = $this->session->all_userdata();
        $itemList = $carts['choosen_items'];
        $productCount = count($itemList);  

        if(count($itemList) <= 0){
            echo  '{"e":"0","d":"There are no items in your cart."}';
            exit();
        } 

        if($qtysuccess != $productCount){
            echo  '{"e":"0","d":"One of the items in your cart is unavailable."}';
            exit();
        } 

        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $name = $address['consignee'];
        $street = $address['c_address']; 
        $cityDescription = $address['c_city'];
        $email = $address['email']; 
        $telephone = $address['c_telephone'];
        $zipCode = "";

        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $regionDesc = $bigThree['regionDesc'];  
        $majorIsland = $bigThree['majorIsland']; 
        
        $ItemTotalPrice = 0;
        $cnt = 0;
        $solutionType = "Sole"; 
        $landingPage = "Billing";
        $paypalType = $this->input->post('paypal'); 
        $dataitem = ""; 
        $toBeLocked = array(); 
 
        $paymentType = $this->PayMentPayPal; #paypal
        $invoice_no = date('Ymhs'); 
        $ip = $this->user_model->getRealIpAddr();   
        $transactionID = "";

        $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
        $shipping_amt = $prepareData['othersumfee'];
        $ItemTotalPrice = $prepareData['totalPrice'] - $shipping_amt;
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $grandTotal= $ItemTotalPrice+$shipping_amt; 
        $thereIsPromote = $prepareData['thereIsPromote'];
      
        if($thereIsPromote <= 0){
            if($grandTotal < '50'){
                echo '{"e":"0","d":"We only accept payments of at least PHP 50.00 in total value."}';
                exit();
            }
        }
        
        foreach ($itemList as $key => $value) {
            $dataitem .= '&L_PAYMENTREQUEST_0_QTY'.$cnt.'='. urlencode($value['qty']).
            '&L_PAYMENTREQUEST_0_AMT'.$cnt.'='.urlencode($value['price']).
            '&L_PAYMENTREQUEST_0_NAME'.$cnt.'='.urlencode($value['name']).
            '&L_PAYMENTREQUEST_0_NUMBER'.$cnt.'='.urlencode($value['id']).
            '&L_PAYMENTREQUEST_0_DESC'.$cnt.'=' .urlencode($value['brief']);
            $cnt++;
        } 

        $padata =   
                '&RETURNURL='.urlencode($PayPalReturnURL).
                '&CANCELURL='.urlencode($PayPalCancelURL).
                '&PAYMENTACTION=Sale'. 
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode('PHP').
                '&CURRENCYCODE='.urlencode('PHP').
        $dataitem. 
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).   
                '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_amt).
                '&PAYMENTREQUEST_0_AMT='.urlencode($grandTotal).
                '&SOLUTIONTYPE='.urlencode($solutionType).
                '&ALLOWNOTE=0'.
                '&NOSHIPPING=1'.
                '&PAYMENTREQUEST_0_SHIPTONAME='.urlencode($name).
                '&PAYMENTREQUEST_0_SHIPTOSTREET='.urlencode($street).
                '&PAYMENTREQUEST_0_SHIPTOCITY='.urlencode($cityDescription).
                '&PAYMENTREQUEST_0_SHIPTOSTATE='.urlencode($regionDesc).
                '&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=PH'.
                '&PAYMENTREQUEST_0_SHIPTOZIP='.urlencode($zipCode).
                '&PAYMENTREQUEST_0_EMAIL='.urlencode($email).
                '&EMAIL='.urlencode($email).
                '&PAYMENTREQUEST_0_SHIPTOPHONENUM='.urlencode($telephone);

        if($paypalType == 2){
           $padata .= '&LANDINGPAGE='.urlencode($landingPage);
        }else{
           $padata .= '&LANDINGPAGE='.urlencode('Login'); 
        }
        $httpParsedResponseAr = $this->paypal->PPHttpPost('SetExpressCheckout', $padata); 
        
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {   
            $transactionID = urldecode($httpParsedResponseAr["TOKEN"]);
            $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,"",$transactionID);
            
            if($return['o_success'] <= 0){
                echo '{"e":"0","d":"'.$return['o_message'].'"}'; 
                exit();
            }else{
                $orderId = $return['v_order_id'];
                $locked = $this->lockItem($toBeLocked,$orderId,'insert');
                $paypalmode = ($PayPalMode == '.sandbox' ? '.sandbox' : '');
                $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$transactionID.'';
                echo '{"e":"1","d":"'.$paypalurl.'"}';  
                exit();
            }        
        }else{
            echo '{"e":"0","d":"'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'"}';
            exit();
        }
    }

    function paypaltest(){
       $this->load->view('pages/payment/ipn');
    }


    function ipn2(){
        // CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        // Set this to 0 once you go live or don't require logging.
        define("DEBUG", 1);

        // Set to 0 once you're ready to go live
        define("USE_SANDBOX", 1);


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

        if(USE_SANDBOX == true) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

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
            $payment_status = $_POST['payment_status'];
            $pending_reason = (isset($_POST['pending_reason']) ? $_POST['pending_reason'] : '');
            $reason_code = (isset($_POST['reason_code']) ? $_POST['reason_code'] : '');
            $txn_id = $_POST['txn_id'];
            $parent_txn_id = (isset($_POST['parent_txn_id']) ? $_POST['parent_txn_id'] : '');
            //$item_name = $_POST['item_name'];
            //$item_number = $_POST['item_number'];
            //$payment_currency = $_POST['mc_currency'];
            // $payment_amount = $_POST['mc_gross'];
            //$receiver_email = $_POST['receiver_email'];
            //$payer_email = $_POST['payer_email'];

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
    function paypal(){

        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };

        $carts = $this->session->all_userdata();
        if(!isset($carts['choosen_items'])){
            redirect(base_url().'home', 'refresh');
        }

        $this->session->set_userdata('paymentticket', true); 

        $response['message_status'] = "";
        $response['message'] = "";

        $paymentType = $this->PayMentPayPal; #paypal
        $apiResponseArray = array(); 
        $analytics = array(); 
        $apiResponse  = "";      
        $ItemTotalPrice = 0;
        
        $member_id =  $this->session->userdata('member_id'); 
        $itemList =  $carts['choosen_items'];     
        $productCount = count($itemList); 
        $productstring = "";  
        $address = $this->memberpage_model->get_member_by_id($member_id); 

        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland'];   

        $transactionID = "";

        if(isset($_GET["token"]) && isset($_GET["PayerID"]))
        {

            $payerid = $_GET["PayerID"];
            $token= $_GET["token"];
            $return = $this->payment_model->selectFromEsOrder($token,$paymentType);
            $invoice = $return['invoice_no'];
            $orderId = $return['id_order'];
            $response['dateadded'] = $return['dateadded'];
            $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
            $itemList = $prepareData['newItemList'];
            $ItemTotalPrice = $prepareData['totalPrice'];
            $productstring = $prepareData['productstring']; 
            $toBeLocked = $prepareData['toBeLocked'];
            $lockCountExist = $this->payment_model->lockcount($orderId);
            
            if($lockCountExist >= 1){
                $locked = $this->lockItem($toBeLocked,$orderId,'delete');
                $qtysuccess = $this->resetPriceAndQty();

                if($qtysuccess != $productCount){
                    $response['message'] = '<div style="color:red"><b>Error 1011:The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.</b></div>';
                }else{ 

                    $grandTotal= $ItemTotalPrice;
                    $response['total'] = $grandTotal;
                    $padata =   '&TOKEN='.urlencode($token).
                    '&PAYERID='.urlencode($payerid).
                    '&PAYMENTACTION='.urlencode("SALE").
                    '&AMT='.urlencode($grandTotal).
                    '&CURRENCYCODE='.urlencode('PHP');

                    $httpParsedResponseArGECD = $this->paypal->PPHttpPost('GetExpressCheckoutDetails', $padata);
                    $apiResponseArray['GetExpressCheckoutDetails '] =  $httpParsedResponseArGECD;

                    $httpParsedResponseAr = $this->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata);
                    $apiResponseArray['DoExpressCheckoutPayment'] = $httpParsedResponseAr;

                    if(("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) && ("SUCCESS" == strtoupper($httpParsedResponseArGECD["ACK"])))
                    {
                        $transactionID = urldecode($httpParsedResponseAr["TRANSACTIONID"]);
                        $response['message_status'] = 'Your PayPal Transaction ID : '.$transactionID; 
                        $transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
                        $nvpStr = "&TRANSACTIONID=".$transactionID;
                        $httpParsedResponseAr =  $this->paypal->PPHttpPost('GetTransactionDetails', $nvpStr);
                        $apiResponseArray['GetTransactionDetails'] =  $httpParsedResponseAr;

                        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
                        {   
 
                            # START SAVING TO DATABASE HERE
                            $apiResponseArray['ProductData'] =   $itemList;
                            $apiResponse = json_encode($apiResponseArray);
                            foreach ($itemList as $key => $value) {               
                                $productId = $value['id'];
                                $productItem =  $value['product_itemID'];
                                $orderQuantity = $value['qty'];
                                $itemComplete = $this->payment_model->deductQuantity($productId,$productItem,$orderQuantity);
                            }

                            $flag = 0;

                            if($httpParsedResponseAr['PAYMENTSTATUS'] == 'Pending'){
                                $flag = 1;
                            }else{
                                $flag = 0;
                            } 

                            $orderStatus = 0;
                            $complete = $this->payment_model->updatePaymentIfComplete($orderId,$apiResponse,$token . '-' .$transactionID,$paymentType,$orderStatus,$flag);

                            if($complete <= 0){
                                $response['message'] = '
                                <div style="color:red"><b>Error 4: </b>Someting went wrong. Please contact us immediately. Your INVOICE NUMBER: '.$invoice.'</div>
                                '; 
                            }else{
                                $orderHistory = array(
                                    'order_id' => $orderId,
                                    'order_status' => 0,
                                    'comment' => 'Paypal transaction confirmed'
                                    );
                                $this->payment_model->addOrderHistory($orderHistory);
                                $response['completepayment'] = true;
                                $response['message'] = '<div style="color:green">Your payment has been completed through Paypal</div>';            
                                $response = array_merge($response,$return);
                                $this->removeItemFromCart();
                                $this->session->unset_userdata('choosen_items');
                                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));

                                #google analytics data
                                $analytics = $this->ganalytics($itemList,$orderId);
                                #end of google analytics data
                            } 
                        }else{
                            $response['message'] = '<div style="color:red"><b>Error 3: (GetTransactionDetails failed):</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                        }                       
                    }else{
                        $response['message'] = '<div style="color:red"><b>Error 2: </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                    }
                }
            }else{
                $response['message'] = '<div style="color:red"><b>Error 2: </b>Your session is already expired for this payment.</div>';

            }
        }

        $response['itemList'] = $itemList;
        $response['analytics'] = $analytics;
        $member_id =  $this->session->userdata('member_id'); 
        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());

        $this->session->set_userdata('paymentticket', true);
        $this->session->set_userdata('headerData', $data);
        $this->session->set_userdata('bodyData', $response);

        redirect(base_url().'payment/success/paypal', 'refresh'); 
    }


    function payCashOnDelivery(){
   
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };

        if(!$this->input->post('paymentToken')){
            redirect(base_url().'home', 'refresh');
        }; 

        $token = $this->input->post('paymentToken');
        $lastDigit = substr($token, -1);
        $qtysuccess = $this->resetPriceAndQty();
        $carts = $this->session->all_userdata();
        
        if(!isset($carts['choosen_items'])){
            redirect(base_url().'home', 'refresh');
        }
        
        $this->session->set_userdata('paymentticket', true);
        if($lastDigit == 1){
            $paymentType = $this->PayMentCashOnDelivery;
            $textType = 'cashondelivery';
            $response['message'] = '<div style="color:green">Your payment has been completed through Cash on Delivery.</div>';
              
        }elseif ($lastDigit == 2) {
            $paymentType = $this->PayMentDirectBankDeposit;
            $esAccountNumber = $this->xmlmap->getFilenameID('page/content_files','bank-account-number');
            $esBank = $this->xmlmap->getFilenameID('page/content_files','bank-name');
            $textType = 'directbankdeposit';
            $response['message'] = '
            <div style="color:green">Your payment has been completed through Direct Bank Deposit.</div>
            <br/>
            <div>Follow the following steps to complete your purchase.
                <ul>
                    <li>Go to your nearest '.$esBank.'</li>
                    <li>Deposit to this account number: '.$esAccountNumber .'</li>
                </ul>
            </div>
            ';
             
        }else{
            $paymentType = $this->PayMentCashOnDelivery;  
            $textType = 'cashondelivery';
            $response['message'] = '<div style="color:green">Your payment has been completed through Cash on Delivery.</div>';
        }
 
        $apiResponseArray = array();   
        $analytics = array();   
        $ItemTotalPrice = 0;
        $transactionID = "";
        $ip = $this->user_model->getRealIpAddr();
        $invoice_no = date('Ymhsd');    

        $member_id =  $this->session->userdata('member_id');
        $itemList =  $carts['choosen_items'];
        $productCount = count($itemList);   

        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland']; 

        $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
        $ItemTotalPrice = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $grandTotal = $ItemTotalPrice;

        $apiResponseArray['ProductData'] = $itemList;
        $apiResponse = json_encode($apiResponseArray);

        if($qtysuccess == $productCount){
            $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,$apiResponse,$transactionID);
            if($return['o_success'] <= 0){
                $response['message'] = '<div style="color:red"><b>Error 3: </b>'.$return['o_message'].'</div>'; 
            }else{
                $v_order_id = $return['v_order_id'];
                $invoice_no = $return['invoice_no'];
                $response['completepayment'] = true;
                $this->removeItemFromCart(); 
                $this->session->unset_userdata('choosen_items');
                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$v_order_id, 'invoice_no'=>$invoice_no));

                #google analytics data
                $analytics = $this->ganalytics($itemList,$v_order_id);
                #end of google analytics data
            }   
        }else{
            $response['message'] = ' <div style="color:red"><b>Error 1011:The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.</b></div>'; 
        } 

        $response['itemList'] = $itemList;
        $response['analytics'] = $analytics;

        if($qtysuccess == $productCount){
            $response = array_merge($response,$return);   
        }

        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());
       
        $this->session->set_userdata('headerData', $data);
        $this->session->set_userdata('bodyData', $response);

        redirect(base_url().'payment/success/'.$textType, 'refresh');
    }

  



    function payDragonPay(){

        header('Content-type: application/json');

        $paymentType = $this->PayMentDragonPay; 

        $member_id =  $this->session->userdata('member_id'); 
        $remove = $this->payment_model->releaseAllLock($member_id);
        $qtysuccess = $this->resetPriceAndQty(TRUE);
        $carts = $this->session->all_userdata();
        $itemList =  $carts['choosen_items'];
        $productCount = count($itemList);

        if($qtysuccess != $productCount){
            echo  '{"e":"0","m":"Item quantity not available."}';
            exit();
        } 

        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $email = $address['email'];

        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland']; 
    
        $ItemTotalPrice = 0;
        $name = ""; 

        $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
        $ItemTotalPrice = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];
        $name = $prepareData['productName'];

        $grandTotal = $ItemTotalPrice;
        $dpReturn = $this->dragonpay->getTxnToken($grandTotal,$name,$email);
        $dpReturnArray = json_decode($dpReturn);
        $transactionID =  $dpReturnArray->tid;
        $invoice_no = date('Ymhsd'); 
        $ip = $this->user_model->getRealIpAddr();  
         
        $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,json_encode($itemList),$transactionID);
        
        if($return['o_success'] <= 0){
            echo '{"e":"0","m":"'.$return['o_message'].'"}'; 
            exit();
        }else{

            $orderId = $return['v_order_id'];
            $locked = $this->lockItem($toBeLocked,$orderId,'insert');  
            $this->session->set_userdata('dragonpayticket', true);
            exit($dpReturn);
        }
    }

 

    function dragonPayPostBack(){

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
        $bigThree = $this->getCityRegionMajorIsland($address);

        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland'];  


        $ItemTotalPrice = 0;
        $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
        $ItemTotalPrice = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $toBeLocked = $prepareData['toBeLocked'];

        if(strtolower($status) == "p" || strtolower($status) == "s"){

            $grandTotal = $ItemTotalPrice;

            $apiResponseArray['ProductData'] = $itemList;
            $apiResponseArray['DragonPayReturn'] = array(
                "txnid" => $txnId,
                "refno" => $refNo,
                "status" => $status,
                "message" => $message,
                "digest" => $digest
                );

            $transactionID = urldecode($txnId);
            $apiResponse = json_encode($itemList);
             

            if($postBackCount == "0"){

                foreach ($itemList as $key => $value) {               
                    $productId = $value['id'];
                    $productItem =  $value['product_itemID'];
                    $orderQuantity = $value['qty'];
                    $itemComplete = $this->payment_model->deductQuantity($productId,$productItem,$orderQuantity);
                }

                $locked = $this->lockItem($toBeLocked,$orderId,'delete');
                $apiResponse = json_encode($apiResponseArray);
            }
            
            
            $orderStatus = 99;

            if(strtolower($status) == "s"){
                $orderStatus = 0;
            }else{
                $orderStatus = 99;
            }
 
            $complete = $this->payment_model->updatePaymentIfComplete($orderId,$apiResponse,$transactionID,$paymentType,0);

            if($postBackCount == "0"){
                $remove_to_cart = $this->payment_model->removeToCart($member_id,$itemList);
                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));  
            }

            // $this->removeItemFromCart(); 

           
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


    function dragonPayReturn(){
     
        if(!$this->session->userdata('dragonpayticket')){
             redirect(base_url().'home/', 'refresh'); 
             exit();
        } 

        $this->session->set_userdata('paymentticket', true);
        
        $paymentType = $this->PayMentDragonPay; 
        $apiResponseArray = array();
        $analytics = array();

        $carts = $this->session->all_userdata();
        $member_id =  $this->session->userdata('member_id'); 
        $itemList =  $carts['choosen_items'];   
        $address = $this->memberpage_model->get_member_by_id($member_id); 

        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland']; 
 
        $ItemTotalPrice = 0;
        
        $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
        $ItemTotalPrice = $prepareData['totalPrice']; 
        $itemList = $prepareData['newItemList']; 

        $grandTotal = $ItemTotalPrice;

        $txnId = $this->input->get('txnid');
        $refNo = $this->input->get('refno');
        $status =  $this->input->get('status');
        $message = $this->input->get('message');
        $digest = $this->input->get('digest');
  
        if(strtolower($status) == "p" || strtolower($status) == "s"){
            
            $return = $this->payment_model->selectFromEsOrder($txnId,$paymentType);
            $invoice = $return['invoice_no'];
            $orderId = $return['id_order'];
            $response['dateadded'] = $return['dateadded'];
            $response['total'] = $grandTotal;
            $response['completepayment'] = true;
            $response['message'] = '<div style="color:green">Your payment has been completed through Dragon Pay.</div><div style="color:black">'.urldecode($message).'</div>';
            $response = array_merge($response,$return);  
            $this->removeItemFromCart(); 
            $this->session->unset_userdata('choosen_items'); 
           
            #google analytics data
            $analytics = $this->ganalytics($itemList,$orderId);
            #end of google analytics data    

        }else{
            $response['message'] = '<div style="color:red">Transaction Not Completed.</div><div style="color:red">'.urldecode($message).'</div>';
        }
 

        $response['itemList'] = $itemList;
        $response['analytics'] = $analytics;
        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());

        $this->load->view('templates/header', $data);
        $this->load->view('pages/payment/payment_response' ,$response);  
        $this->load->view('templates/footer_full'); 
        // $this->session->set_userdata('headerData', $data);
        // $this->session->set_userdata('bodyData', $response); 
        // redirect(base_url().'payment/success/dragonpay', 'refresh');
        
  
    }


    function paymentSuccess($mode = "easyshop"){

        $ticket = $this->session->userdata('paymentticket');
        if($ticket){
            $data = $this->session->userdata('headerData');
            $response = $this->session->userdata('bodyData'); 
            $this->session->unset_userdata('paymentticket');
            $this->session->unset_userdata('headerData');
            $this->session->unset_userdata('bodyData');
            $this->load->view('templates/header', $data);
            $this->load->view('pages/payment/payment_response' ,$response);  
            $this->load->view('templates/footer_full'); 
        }else{
             redirect(base_url().'home', 'refresh');
        }

    }


    function getCityRegionMajorIsland($address)
    {
        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
        $regionDesc = $cityDetails['parent_location'];
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id'];

        $return = array(
            'city' => $city,
            'region' => $region,
            'regionDesc' => $regionDesc,
            'majorIsland' => $majorIsland
            );

        return $return;
    }

    function removeItemFromCart(){
            $carts = $this->session->all_userdata();
            if(isset($carts['choosen_items'])){
                foreach ($carts['choosen_items'] as $key => $value) {
                    
                    $carts['cart_contents'][$key]['qty'] = 0 ;
                    $this->cart->update($carts['cart_contents'][$key]);
                    unset($carts['cart_contents'][$key]);
                    $carts['cart_contents']['total_items'] =  $carts['cart_contents']['total_items'] - 1;
                    $carts['cart_contents']['cart_total'] =  $carts['cart_contents']['cart_total'] - $value['subtotal'];
                }
              
                if(sizeof($carts['cart_contents']) == 2){
                    unset($carts['cart_contents']['total_items']);
                    unset($carts['cart_contents']['cart_total']);
                }   
                $this->session->unset_userdata('choosen_items');
            }
      
    }
 
	/*
	 *	Function called upon purchasing an item. Sends notification to both buyer and seller
	 *
	 *  $data = array(
	 *		'member_id' => Member ID who made the purchase (buyerID)
	 *		'order_id'	=> Transaction Number
	 *		'invoice_no' => Invoice number
	 *	)
	 */
    function sendNotification($data) 
    {   
        //devcode
		/*$data['member_id'] = 74;
		$data['order_id'] = 102;
		$data['invoice_no']= 3;
		$data['member_id'] = 56;
		$data['order_id'] = 156;
		$data['invoice_no']= '156-2014061247';*/
		
        $transactionData = $this->payment_model->getPurchaseTransactionDetails($data);
        
        //Send email to buyer
        $buyerEmail = $transactionData['buyer_email'];
        $buyerData = $transactionData;
        unset($buyerData['seller']);
        unset($buyerData['buyer_email']);
		
		// 3 tries to send Email. Quit if success or 3 failed tries met
		$emailcounter = 0;
		do{
			$buyerEmailResult = $this->payment_model->sendNotificationEmail($buyerData, $buyerEmail, 'buyer');
			$emailcounter++;
		}while(!$buyerEmailResult && $emailcounter<3);
        
		//Send text msg to buyer if mobile provided
		$buyerMobile = trim($buyerData['buyer_contactno']);
		if($buyerMobile != '' && $buyerMobile != 0 ){
			$buyerMsg = $buyerData['buyer_name'] . $this->lang->line('notification_txtmsg_buyer');
			$buyerTxtResult = $this->payment_model->sendNotificationMobile($buyerMobile, $buyerMsg);
		}
		
        //Send email to seller of each product - once per seller
        $sellerData = array(
            'id_order' => $transactionData['id_order'],
            'dateadded' => $transactionData['dateadded'],
            'buyer_name' => $transactionData['buyer_name'],
			'invoice_no' => $transactionData['invoice_no'],
            );
			
        foreach($transactionData['seller'] as $seller){
            $sellerEmail = $seller['email'];
			$sellerData = array_merge( $sellerData, array_slice($seller,1,7) );
			$sellerData['totalprice'] = number_format($seller['totalprice'], 2, '.' , ',');
			
			// 3 tries to send Email. Quit if success or 3 failed tries met
			$emailcounter = 0;
			do{
				$sellerEmailResult = $this->payment_model->sendNotificationEmail($sellerData, $sellerEmail, 'seller');
				$emailcounter++;
			}while(!$sellerEmailResult && $emailcounter<3);
			
			//Send text msg to buyer if mobile provided
			$sellerMobile = trim($seller['seller_contactno']);
			if($sellerMobile != '' && $sellerMobile != 0 ){
				$sellerMsg = $seller['seller_name'] . $this->lang->line('notification_txtmsg_seller');
				$sellerTxtResult = $this->payment_model->sendNotificationMobile($sellerMobile, $sellerMsg);
			}
        }//close foreach seller loop
    }
	
	/*
	 *	Function to revert back order quantity when dragon pay transaction expires
	 */
	function ganalytics($itemList,$v_order_id)
	{  
        $analytics = array(); 
        foreach ($itemList as $key => $value) {

            $product = $this->product_model->getProductPreview($value['id'],$value['member_id'],"0");

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

    function processData($itemList,$city,$region,$majorIsland)
    {
        $ItemTotalPrice = 0;
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
            $shipping_amt = $details[0]['price'];
            $otherFee = ($tax_amt + $shipping_amt) * $orderQuantity;
            $othersumfee += $otherFee;
            $total =  $value['subtotal'] + $otherFee;
            $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$otherFee."{+}".$total."{+}".$productItem;
            $itemList[$key]['otherFee'] = $otherFee;
            $sellerDetails = $this->memberpage_model->get_member_by_id($sellerId); 
            $itemList[$key]['seller_username'] = $sellerDetails['username'];
            $ItemTotalPrice += $total;  
            $name .= "<br>".$value['name'];
            $toBeLocked[$productItem] = $orderQuantity;
        }
        $productstring = substr($productstring,4);
        return array(
            'totalPrice' => $ItemTotalPrice,
            'newItemList' => $itemList,
            'productstring' => $productstring,
            'productName' => $name,
            'toBeLocked' => $toBeLocked,
            'othersumfee' => $othersumfee,
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
        }else if(!is_numeric($this->input->post('c_mobile')) || strlen($this->input->post('c_mobile')) != 10 || (!preg_match("/^(9|8)[0-9]{9}$/", $this->input->post('c_mobile')))){
            echo json_encode("<b>MOBILE NUMBER</b> should be 10 digits long and starts with 9. eg: 9051235678");
            exit();
        }else if(trim($this->input->post('c_telephone')) != "" && (preg_match("/^([0-9]{4}-){3}[0-9]{4}$/", $this->input->post('c_telephone')) || !is_numeric(str_replace('-', '', $this->input->post('c_telephone'))))){
            echo json_encode("<b>TELEPHONE NUMBER</b> can only be numbers and hyphen. eg: 354-5973");
        }else{
            $postdata['default_add'] = "off";
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

            /** NEW QUANTITY **/
            $newQty = $this->product_model->getProductQuantity($productId, FALSE, $condition);
            $maxqty = $newQty[$itemId]['quantity'];
            $qty = $value['qty']; 
            $itemArray[$value['rowid']]['maxqty'] = $maxqty;
            $qtysuccess = ($maxqty >= $qty ? $qtysuccess + 1: $qtysuccess + 0);

            /** NEW PRICE **/
            $promoPrice = $this->product_model->getProductById($productId)['price']; 
            $additionalPrice = $value['additional_fee'];
            $finalPromoPrice = $promoPrice + $additionalPrice;
            $itemArray[$value['rowid']]['price'] = $finalPromoPrice;
            $subtotal = $finalPromoPrice * $qty;
            $itemArray[$value['rowid']]['subtotal'] = $subtotal;
        }

        $this->session->set_userdata('choosen_items', $itemArray); 
        return $qtysuccess;
    }


	
}


/* End of file payment.php */
/* Location: ./application/controllers/payment.php */