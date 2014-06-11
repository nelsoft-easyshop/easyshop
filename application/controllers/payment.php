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
        $this->load->library("xmlmap");
        $this->load->model('user_model');
        $this->load->model('cart_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('memberpage_model'); 
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

    	return true;
    }

    function review()
    {
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        }

        $qtysuccess = $this->resetPriceAndQty();

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
        $successcount = 0;
        $codCount = 0; 
        $data['shippingDetails'] = false; 


        foreach ($itemArray as $key => $value) {

            $productId = $value['id']; 
            $itemId = $value['product_itemID']; 

            $availability = "Not Available";

            if($city > 0){  
                $details = $this->payment_model->getShippingDetails($productId,$itemId,$city,$region,$majorIsland);

                if(count($details) >= 1){
                    $successcount++;
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

        
        if(!count($itemArray) <= 0){ 
  
            $data['cat_item'] = $itemArray;
            $data['title'] = 'Payment | Easyshop.ph';
            $data['qtysuccess'] = ($qtysuccess == $itemCount ? true : false);
            $data['success'] = ($successcount == $itemCount ? true : false);
            $data['codsuccess'] = ($codCount == $itemCount ? true : false);

            $data = array_merge($data,$this->fill_header()); 
            $data = array_merge($data, $this->memberpage_model->getLocationLookup());
            $data = array_merge($data,$this->memberpage_model->get_member_by_id($member_id));

            $this->load->view('templates/header', $data);
            $this->load->view('pages/payment/payment_review' ,$data);  
            $this->load->view('templates/footer' ,$data);  
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
        
        $PayPalMode         = $this->PayPalMode; 
        $PayPalApiUsername  = $this->PayPalApiUsername;
        $PayPalApiPassword  = $this->PayPalApiPassword;
        $PayPalApiSignature = $this->PayPalApiSignature;
        $PayPalCurrencyCode = $this->PayPalCurrencyCode; 
        $PayPalReturnURL    = base_url().'pay/paypal'; 
        $PayPalCancelURL    = base_url().'payment/review'; 


        $qtysuccess = $this->resetPriceAndQty();
        $carts = $this->session->all_userdata();

        if(count($carts['choosen_items']) <= 0){
            echo  '{"e":"0","d":"No item in cart."}';
            exit();
        } 

        $itemList = $carts['choosen_items'];
        $productCount = count($itemList);  

        if($qtysuccess != $productCount){
            echo  '{"e":"0","d":"Item quantity not available."}';
            exit();
        } 


        $member_id =  $this->session->userdata('member_id');
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
                $data = '{"e":"0","d":"Less than 50 of amount purchased is not allowed."}';
                echo $data;
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
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
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
        $httpParsedResponseAr = $this->paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
        $pdataarray = json_encode(explode('&',substr($padata, 1)));
        

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {   
            $transactionID = urldecode($httpParsedResponseAr["TOKEN"]);
            $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,"",$transactionID);
            
            if($return['o_success'] <= 0){
                $data = '{"e":"0","d":"'.$return['o_message'].'"}'; 
            }else{
                $orderId = $return['v_order_id'];
                $locked = $this->lockItem($toBeLocked,$orderId,'insert');
                $paypalmode = ($PayPalMode == 'sandbox' ? '.sandbox' : '');
                $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$transactionID.'';
                $data = '{"e":"1","d":"'.$paypalurl.'"}';  
            } 
             
        }else{
            $data = '{"e":"0","d":"'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'"}';
        }
        echo $data;
        exit();
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
        $PayPalMode         = $this->PayPalMode; 
        $PayPalApiUsername  = $this->PayPalApiUsername;
        $PayPalApiPassword  = $this->PayPalApiPassword;
        $PayPalApiSignature = $this->PayPalApiSignature; 
        $PayPalCurrencyCode = $this->PayPalCurrencyCode; 

        $response['message_status'] = "";
        $response['message'] = "";

        $paymentType = $this->PayMentPayPal; #paypal
        $apiResponseArray = array(); 
        $analytics = array(); 
        $apiResponse  = "";      
        $ItemTotalPrice = 0;
        
        $member_id =  $this->session->userdata('member_id'); 
        $itemList =  $carts['choosen_items'];     
        $productstring = "";  
        $address = $this->memberpage_model->get_member_by_id($member_id); 

        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland'];   

        $transactionID = "";

        if(isset($_GET["token"]) && isset($_GET["PayerID"]))
        {
       
            $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
            $ItemTotalPrice = $prepareData['totalPrice'];
            $productstring = $prepareData['productstring'];
            $itemList = $prepareData['newItemList'];
            $toBeLocked = $prepareData['toBeLocked'];

            $response['itemList'] = $itemList;
            $grandTotal= $ItemTotalPrice;
            $payerid = $_GET["PayerID"];
            $token= $_GET["token"];

            $padata =   '&TOKEN='.urlencode($token).
            '&PAYERID='.urlencode($payerid).
            '&PAYMENTACTION='.urlencode("SALE").
            '&AMT='.urlencode($grandTotal).
            '&CURRENCYCODE='.urlencode($PayPalCurrencyCode);
            
            $return = $this->payment_model->selectFromEsOrder($token,$paymentType);
            $invoice = $return['invoice_no'];
            $orderId = $return['id_order'];
            $response['dateadded'] = $return['dateadded'];
            $response['total'] = $grandTotal;

            $httpParsedResponseArGECD = $this->paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
            $apiResponseArray['GetExpressCheckoutDetails '] =  $httpParsedResponseArGECD;
               
            $httpParsedResponseAr = $this->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
            $apiResponseArray['DoExpressCheckoutPayment'] = $httpParsedResponseAr;
            
            if(("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) && ("SUCCESS" == strtoupper($httpParsedResponseArGECD["ACK"])))
            {
                $transactionID = urldecode($httpParsedResponseAr["TRANSACTIONID"]);
                $response['message_status'] = 'Your PayPal Transaction ID : '.$transactionID; 
                $transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
                $nvpStr = "&TRANSACTIONID=".$transactionID;
                $httpParsedResponseAr =  $this->paypal->PPHttpPost('GetTransactionDetails', $nvpStr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
                $apiResponseArray['GetTransactionDetails'] =  $httpParsedResponseAr;
  
                if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
 
                    # START SAVING TO DATABASE HERE
              
                    $apiResponseArray['ProductData'] =   $itemList;
                    $apiResponse = json_encode($apiResponseArray);
                    foreach ($itemList as $key => $value) {               
                        $productId = $value['id'];
                        $productItem =  $value['product_itemID'];
                        $orderQuantity = $value['qty'];
                        $itemComplete = $this->payment_model->deductQuantity($productId,$productItem,$orderQuantity);
                    }
                    $locked = $this->lockItem($toBeLocked,$orderId,'delete');
                    $complete = $this->payment_model->updatePaymentIfComplete($orderId,$apiResponse,$token . '-' .$transactionID,$paymentType);
                     
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
                        $response['message'] = '<div style="color:green">Your payment is completed through Paypal</div>';            
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
        $carts = $this->session->all_userdata();
        
        if(!isset($carts['choosen_items'])){
            redirect(base_url().'home', 'refresh');
        }
        
        $this->session->set_userdata('paymentticket', true);
        if($lastDigit == 1){
            $paymentType = $this->PayMentCashOnDelivery;
            $textType = 'cashondelivery';
            $response['message'] = '<div style="color:green">Your payment is completed through Cash on Delivery.</div>';
              
        }elseif ($lastDigit == 2) {
            $paymentType = $this->PayMentDirectBankDeposit;
            $esAccountNumber = $this->xmlmap->getFilenameID('page/content_files','bank-account-number');
            $textType = 'directbankdeposit';
            $response['message'] = '
            <div style="color:green">Your payment is completed through Direct Bank Deposit.</div>
            <div>Step to complete to your transactions:
                <ul>
                    <li>Go to your bank.</li>
                    <li>Deposit to this account number: '.$esAccountNumber .'</li>
                </ul>
            </div>
            ';
             
        }else{
            $paymentType = $this->PayMentCashOnDelivery;  
            $textType = 'cashondelivery';
            $response['message'] = '<div style="color:green">Your payment is completed through Cash on Delivery.</div>';
        }
 
        $apiResponseArray = array();   
        $analytics = array(); 

        $member_id =  $this->session->userdata('member_id');
        $itemList =  $carts['choosen_items'];
        $productCount = count($itemList); 
        $invoice_no = date('Ymhsd'); 
        $ip = $this->user_model->getRealIpAddr();     
        $address = $this->memberpage_model->get_member_by_id($member_id); 
       
        $bigThree = $this->getCityRegionMajorIsland($address);
        $city = $bigThree['city'];  
        $region = $bigThree['region'];  
        $majorIsland = $bigThree['majorIsland']; 

 
        $ItemTotalPrice = 0;
        $transactionID = "";

        $prepareData = $this->processData($itemList,$city,$region,$majorIsland);
        $ItemTotalPrice = $prepareData['totalPrice'];
        $productstring = $prepareData['productstring'];
        $itemList = $prepareData['newItemList'];
        $grandTotal = $ItemTotalPrice;

        $apiResponseArray['ProductData'] = $itemList;
        $apiResponse = json_encode($apiResponseArray);
       
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

        $response['itemList'] = $itemList;
        $response['analytics'] = $analytics;
        $response = array_merge($response,$return);   
        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());
       
        $this->session->set_userdata('headerData', $data);
        $this->session->set_userdata('bodyData', $response);

        redirect(base_url().'payment/success/'.$textType, 'refresh');
    }

  



    function payDragonPay(){
        header('Content-type: application/json');

        $qtysuccess = $this->resetPriceAndQty();

        $carts = $this->session->all_userdata();
        $member_id =  $this->session->userdata('member_id'); 
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
        $paymentType = $this->PayMentDragonPay; 
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

    function dragonPayPostBack2(){

        header("Content-Type:text/plain");
        $status = $this->input->post('status');
        if(strtolower($status) == "p" || strtolower($status) == "s"){
            $transactionID = $this->input->post('txnid').'-'.$this->input->post('refno');
            $this->payment_model->checkMyDp($transactionID);
        }


    }

    function test(){
        echo "<form method='POST' action='http://nelsoft.dyndns.org:81/payment/dragonPayPostBack'>
        <input type='text'  name='status' placeholder='status' />
        <input type='text'  name='refno' placeholder='refno' />
        <input type='text'  name='txnid' placeholder='txnid' />
        <input type='submit'>
        </form>";
    }

    function dragonPayPostBack(){

        header("Content-Type:text/plain");

        $paymentType = $this->PayMentDragonPay; 

        $txnId = $this->input->post('txnid');
        $refNo = $this->input->post('refno');
        $status =  $this->input->post('status');
        $message = $this->input->post('message');
        $digest = $this->input->post('digest');

        if(strtolower($status) == "p" || strtolower($status) == "s"){

            $payDetails = $this->payment_model->selectFromEsOrder($txnId,$paymentType);
 
            $invoice = $payDetails['invoice_no'];
            $orderId = $payDetails['id_order'];
            $member_id = $payDetails['buyer_id'];
            $itemList = json_decode($payDetails['data_response'],true); 
             
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
            $paymentType = 2;

            if(strtolower($status) == "s"){

                foreach ($itemList as $key => $value) {               
                    $productId = $value['id'];
                    $productItem =  $value['product_itemID'];
                    $orderQuantity = $value['qty'];
                    $itemComplete = $this->payment_model->deductQuantity($productId,$productItem,$orderQuantity);
                }

                $locked = $this->lockItem($toBeLocked,$orderId,'delete');
                $paymentType = 4;
                $apiResponse = json_encode($apiResponseArray);
                
            }
             
            $complete = $this->payment_model->updatePaymentIfComplete($orderId,$apiResponse,$transactionID,$paymentType);
            $remove_to_cart = $this->payment_model->removeToCart($member_id,$itemList);

            if(strtolower($status) == "s"){
                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));  
            }

            // $this->removeItemFromCart(); 

           
        }


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
            $paymentType = (strtolower($status) == "p") ? 2 : 4;
            $return = $this->payment_model->selectFromEsOrder($txnId,$paymentType);
            $invoice = $return['invoice_no'];
            $orderId = $return['id_order'];
            $response['dateadded'] = $return['dateadded'];
            $response['total'] = $grandTotal;
            $response['completepayment'] = true;
            $response['message'] = '<div style="color:green">Your payment is completed through Dragon Pay.</div><div style="color:red">'.urldecode($message).'</div>';
            $response = array_merge($response,$return);  
            $this->removeItemFromCart(); 
            $this->session->unset_userdata('choosen_items');
            $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$orderId, 'invoice_no'=>$invoice));
           
            #google analytics data
            $analytics = $this->ganalytics($itemList,$orderId);
            #end of google analytics data    

        }else{
            $response['message'] = '<div style="color:red">Transaction Not Completed.</div>';
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
        // if($ticket){
        $data = $this->session->userdata('headerData');
        $response = $this->session->userdata('bodyData'); 

        // $this->session->unset_userdata('paymentticket');
        // $this->session->unset_userdata('headerData');
        // $this->session->unset_userdata('bodyData');
 
        $this->load->view('templates/header', $data);
        $this->load->view('pages/payment/payment_response' ,$response);  
        $this->load->view('templates/footer_full'); 
        // }else{
        //     redirect(base_url().'home/', 'refresh');
        // }

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
  //       // if(!$this->session->userdata('member_id')){
  //       //     redirect(base_url().'home', 'refresh');
  //       // };
        
  //       //devcode
		// /*$data['member_id'] = 74;
		// $data['order_id'] = 102;
		// $data['invoice_no']= 3;
		// $data['member_id'] = 74;
		// $data['order_id'] = 105;
		// $data['invoice_no']= '22-1231-2';*/
		
  //       $transactionData = $this->payment_model->getPurchaseTransactionDetails($data);
        
  //       //Send email to buyer
  //       $buyerEmail = $transactionData['buyer_email'];
  //       $buyerData = $transactionData;
  //       unset($buyerData['seller']);
  //       unset($buyerData['buyer_email']);
		// // 3 tries to send Email. Quit if success or 3 failed tries met
		// $emailcounter = 0;
		// do{
		// 	$buyerEmailResult = $this->payment_model->sendNotificationEmail($buyerData, $buyerEmail, 'buyer');
		// 	$emailcounter++;
		// }while(!$buyerEmailResult && $emailcounter<3);
        
		// //Send text msg to buyer if mobile provided
		// $buyerMobile = trim($buyerData['buyer_contactno']);
		// if($buyerMobile != '' && $buyerMobile != 0 ){
		// 	$buyerMsg = $buyerData['buyer_name'] . $this->lang->line('notification_txtmsg_buyer');
		// 	$buyerTxtResult = $this->payment_model->sendNotificationMobile($buyerMobile, $buyerMsg);
		// }
		
  //       //Send email to seller of each product - once per seller
  //       $sellerData = array(
  //           'id_order' => $transactionData['id_order'],
  //           'dateadded' => $transactionData['dateadded'],
  //           'buyer_name' => $transactionData['buyer_name'],
		// 	'invoice_no' => $transactionData['invoice_no']
  //           );
			
  //       foreach($transactionData['seller'] as $seller){
  //           $sellerEmail = $seller['email'];
  //           $sellerData['totalprice'] = number_format($seller['totalprice'], 2, '.' , ',');
		 
  //           $sellerData['seller_name'] = $seller['seller_name'];
  //           $sellerData['products'] = $seller['products'];
		// 	// 3 tries to send Email. Quit if success or 3 failed tries met
		// 	$emailcounter = 0;
		// 	do{
		// 		$sellerEmailResult = $this->payment_model->sendNotificationEmail($sellerData, $sellerEmail, 'seller');
		// 		$emailcounter++;
		// 	}while(!$sellerEmailResult && $emailcounter<3);
			
		// 	//Send text msg to buyer if mobile provided
		// 	$sellerMobile = trim($seller['seller_contactno']);
		// 	if($sellerMobile != '' && $sellerMobile != 0 ){
		// 		$sellerMsg = $seller['seller_name'] . $this->lang->line('notification_txtmsg_seller');
		// 		$sellerTxtResult = $this->payment_model->sendNotificationMobile($sellerMobile, $sellerMsg);
		// 	}
  //       }//close foreach seller loop
    }
	
	/*
	 *	Function to revert back order quantity when dragon pay transaction expires
	 *
	 *
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
            $otherFee = $tax_amt + $shipping_amt;
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
                'lng' => $this->input->post('temp_lng')
            );
            if(trim($this->input->post('consignee')) == "" || $this->input->post('c_city') == 0 || $this->input->post('c_stateregion') == 0 || trim($this->input->post('c_address')) == "" || trim($this->input->post('c_mobile')) == "")
            {
                echo json_encode("Fill the required fields!");
                exit();
            }else if(!is_numeric($this->input->post('c_mobile')) || strlen($this->input->post('c_mobile')) != 10){
                echo json_encode("<b>MOBILE NUMBER</b> should be numeric and 10 digits. eg: 9051235678");
                exit();
            }else if(trim($this->input->post('c_telephone')) != "" && (preg_match("/^([0-9]{4}-){3}[0-9]{4}$/", $this->input->post('c_telephone')) || !is_numeric(str_replace('-', '', $this->input->post('c_telephone'))))){
                echo json_encode("<b>MOBILE NUMBER</b> should be numeric and hypen only. eg: 123-45-67");
            }else{
                $postdata['default_add'] = "off";
                $addressId = $this->memberpage_model->getAddress($uid,'1')['id_address'];
                
                $data = $this->memberpage_model->editDeliveryAddress($uid, $postdata,$addressId);
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

    function resetPriceAndQty()
    {
 
        $carts = $this->session->all_userdata(); 
        $itemArray = $carts['choosen_items'];
        $qtysuccess = 0;

        foreach ($itemArray as $key => $value) {

            $productId = $value['id']; 
            $itemId = $value['product_itemID']; 

        /** NEW QUANTITY **/
            $newQty = $this->product_model->getProductQuantity($productId);
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
        // echo '<pre>',print_r($itemArray);exit();
        return $qtysuccess;
    }


	
}


/* End of file payment.php */
/* Location: ./application/controllers/payment.php */