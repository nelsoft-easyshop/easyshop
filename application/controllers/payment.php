<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends MY_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->library('paypal');
        $this->load->model('user_model');
        $this->load->model('payment_model');
        $this->load->model("memberpage_model"); 
        session_start();
    }

 
    
    // LIVE
    // public $PayPalMode             = ''; 
    // public $PayPalApiUsername      = 'admin_api1.easyshop.ph'; 
    // public $PayPalApiPassword      = 'GDWFS6D9ACFG45E7'; 
    // public $PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Adro7yAfl2NInYAAVfFFipJ-QQhT'; 

    // SANDBOX
    public $PayPalMode             = 'sandbox'; 
    public $PayPalApiUsername      = 'easyseller_api1.yahoo.com'; 
    public $PayPalApiPassword      = '1396000698'; 
    public $PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Au1bGvwwVcv0garAliLq12YWfivG';  
    public $PayPalCurrencyCode     = 'PHP';

    function cart_items()
    {
        $unchecked = $_POST['itm'];
        $carts = $this->cart->contents();
        for($x=0;$x < sizeof($unchecked);$x++):
            unset($carts[$unchecked[$x]]);
        endfor;
        //$this->session->unset_userdata('cart_contents');
        $cart_contentss=array('choosen_items'=>$carts);
        $this->session->set_userdata($cart_contentss);
    	return true;
    }
   
    function review()
    {  

        $carts = $this->session->all_userdata(); 
        $itemArray = $carts['choosen_items'];
        $member_id =  $this->session->userdata('member_id');
        $address = $this->memberpage_model->get_member_by_id($member_id);
  
        // $city = $address['c_cityID']; #Caloocan 
        $city = 0;
        $city = ($address['c_cityID'] > 0 ? $address['c_cityID'] :  0);
        $itemCount = count($itemArray);
        $successcount = 0;

        foreach ($itemArray as $key => $value) {

            $seller = $this->memberpage_model->get_member_by_id($member_id);

            $productId = $value['id'];
            $name = $value['name'];
            $itemId = $value['product_itemID'];
            $availability = "Not Available";
            $details = $this->payment_model->getShippingDetails($productId,$itemId);
            $locationType = $details['type'];
            $locationName = $details['location'];
            $locationId = $details['id_location'];
            $data['shippingDetails'] = false;
            if($city > 0){
                if($locationType == '1'){ # if LUZON, VISAYAS OR MINDANAO
                    $region = $this->payment_model->getRegionOrMajorIsland($city,3);
                    $majorIsland = $this->payment_model->getRegionOrMajorIsland($region,2);
                    $availability = ($locationId == $majorIsland ? "Available" : "Not Available");
                    $successcount = ($availability == "Available" ? $successcount + 1 : $successcount + 0);

                }elseif($locationType == '2') { # if REGION
                    $cityList = $this->payment_model->getCityFromRegion($locationId);
                    $availability = (in_array($city, $cityList) ? "Available" : "Not Available");
                    $successcount = ($availability == "Available" ? $successcount + 1 : $successcount + 0);

                }else{ # if CITY
                    $availability = ($locationId == $city ? "Available" : "Not Available");
                    $successcount = ($availability == "Available" ? $successcount + 1 : $successcount + 0);
                }
                $data['shippingDetails'] = true;
            } 

            $itemArray[$value['rowid']]['availability'] = ($availability == "Available" ? true : false);
            $itemArray[$value['rowid']]['seller_username'] = $seller['username'];
            // echo $name.' - '.$availability .'<br>';
        }  
        // echo '<pre>',print_r($itemArray);exit();
        if(!count($carts['choosen_items']) <=0){  
            $data['cat_item'] = $itemArray;
            $data['title'] = 'Payment | Easyshop.ph';
            $data['success'] = ($successcount == $itemCount ? true : false);
            $data = array_merge($data,$this->fill_header());
            $data = array_merge($data, $this->memberpage_model->getLocationLookup());
            $data = array_merge($data,$this->memberpage_model->get_member_by_id($member_id));
            $this->load->view('templates/header', $data);
            $this->load->view('pages/payment/payment_review' ,$data);  
        }else{
           redirect('/cart/', 'refresh'); 
       }
    }

	#SET UP PAYPAL FOR PARAMETERS
    #SEE REFERENCE SITE FOR THE PARAMETERS
    # https://developer.paypal.com/webapps/developer/docs/classic/express-checkout/integration-guide/ECCustomizing/
    function paypal_setexpresscheckout() 
    {
        $PayPalMode             = $this->PayPalMode; 
        $PayPalApiUsername      = $this->PayPalApiUsername;
        $PayPalApiPassword      = $this->PayPalApiPassword;
        $PayPalApiSignature     = $this->PayPalApiSignature;
        $PayPalCurrencyCode     = $this->PayPalCurrencyCode; 
        $PayPalReturnURL        = base_url().'payment/paypal'; 
        $PayPalCancelURL        = base_url().'payment/review'; 

        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        $cnt = 0;
        $solutionType = "Sole"; 
        $landingPage = "Billing";
        $paypalType = $this->input->post('paypal');
        $carts = $this->session->all_userdata();
        $dataitem = "";

        foreach ($carts['choosen_items'] as $key => $value) {
            $dataitem .= '&L_PAYMENTREQUEST_0_QTY'.$cnt.'='. urlencode($value['qty']).
            '&L_PAYMENTREQUEST_0_AMT'.$cnt.'='.urlencode($value['price']).
            '&L_PAYMENTREQUEST_0_NAME'.$cnt.'='.urlencode($value['name']).
            '&L_PAYMENTREQUEST_0_NUMBER'.$cnt.'='.urlencode($value['id']).
            '&L_PAYMENTREQUEST_0_DESC'.$cnt.'=' .urlencode('SAMPLE DESCRIPTION');
            $cnt++;
            $ItemTotalPrice += $value['subtotal'];
        } 

        $grandTotal= ($ItemTotalPrice+$tax_amt+$shipping_amt+$handling_amt+$insurance_amt)-$shipping_discount_amt;
        $padata =   
        '&RETURNURL='.urlencode($PayPalReturnURL ).
        '&CANCELURL='.urlencode($PayPalCancelURL).
        '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
        '&PAYMENTACTION=Sale'.
        '&ALLOWNOTE=1'.
        '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
        $dataitem.
        '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).  
        '&PAYMENTREQUEST_0_TAXAMT='.urlencode($tax_amt).
        '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_amt).
        '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($handling_amt).
        '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($insurance_amt).
        '&PAYMENTREQUEST_0_SHIPDISCAMT=-'.urlencode($shipping_discount_amt).
        '&PAYMENTREQUEST_0_AMT='.urlencode($grandTotal);

        if($paypalType == 2){
           $padata .= '&SOLUTIONTYPE='.urlencode($solutionType).'&LANDINGPAGE='.urlencode($landingPage);
        }
        $httpParsedResponseAr = $this->paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
        $pdataarray = json_encode(explode('&',substr($padata, 1)));

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {       
            $paypalmode = ($PayPalMode == 'sandbox' ? '.sandbox' : '');
            $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
            $data = '{"e":"1","d":"'.$paypalurl.'"}';   
        }else{
            $data = '{"e":"0","d":"'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'"}';
        }
        echo $data;
        exit();
    }

 
    #PROCESS PAYPAL
function paypal(){

    $PayPalMode             = $this->PayPalMode; 
    $PayPalApiUsername      = $this->PayPalApiUsername;
    $PayPalApiPassword      = $this->PayPalApiPassword;
    $PayPalApiSignature     = $this->PayPalApiSignature; 
    $PayPalCurrencyCode     = $this->PayPalCurrencyCode; 
    $response['message_status'] = "";
    $response['message'] = "";

    $apiResponseArray = array(); 
    $apiResponse  = ""; 
    $tax_amt = 0;
    $shipping_amt = 0;    
    $handling_amt = 0;
    $shipping_discount_amt = 0;
    $insurance_amt = 0;
    $ItemTotalPrice = 0;
    $cnt = 0;
    $carts = $this->session->all_userdata();
    $member_id =  $this->session->userdata('member_id');
    $productCount = count($carts['choosen_items']); 
    $invoice_no = date('Ymhs'); 
    $ip = $this->user_model->getRealIpAddr();   
    $productstring = ""; 

    if(isset($_GET["token"]) && isset($_GET["PayerID"]))
    {
        $playerid = $_GET["PayerID"];
        foreach ($carts['choosen_items'] as $key => $value){
            $ItemTotalPrice += $value['subtotal'];
        }

        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        $cnt = 0;
        $carts = $this->session->all_userdata();
        $member_id =  $this->session->userdata('member_id');

        if(isset($_GET["token"]) && isset($_GET["PayerID"]))
        {
            $playerid = $_GET["PayerID"];
            foreach ($carts['choosen_items'] as $key => $value) {
                $ItemTotalPrice += $value['subtotal'];
            }

            $grandTotal= ($ItemTotalPrice+$tax_amt+$shipping_amt+$handling_amt+$insurance_amt)-$shipping_discount_amt;
            $token= $_GET["token"];
            $padata =   '&TOKEN='.urlencode($token).
            '&PAYERID='.urlencode($playerid).
            '&PAYMENTACTION='.urlencode("SALE").
            '&AMT='.urlencode($grandTotal).
            '&CURRENCYCODE='.urlencode($PayPalCurrencyCode);

            $httpParsedResponseAr = $this->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
            $apiResponseArray['DoExpressCheckoutPayment'] = $httpParsedResponseAr;

            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
            {
                $response['message_status'] = 'Your Transaction ID :'.urldecode($httpParsedResponseAr["TRANSACTIONID"]);

                // if('Completed' == $httpParsedResponseAr["PAYMENTSTATUS"]){
                //     $response['message_status'] .= '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
                // }elseif('Pending' == $httpParsedResponseAr["PAYMENTSTATUS"]){
                //     $response['message_status'] .= '<div style="color:red">Transaction Complete, but payment is still pending! You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
                // }

                $transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
                $nvpStr = "&TRANSACTIONID=".$transactionID;
                $httpParsedResponseAr =  $this->paypal->PPHttpPost('GetTransactionDetails', $nvpStr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
                $apiResponseArray['GetTransactionDetails'] =  $httpParsedResponseAr;

                if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
 
                    # START SAVING TO DATABASE HERE

                    foreach ($carts['choosen_items'] as $key => $value) {

                        $sellerId = $value['member_id'];
                        $productId = $value['id'];
                        $orderQuantity = $value['qty'];
                        $price = $value['price'];
                        $tax_amt = $tax_amt;
                        $total =  $value['subtotal'] + $tax_amt;
                        $productItem =  $value['product_itemID'];
                        $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$tax_amt."{+}".$total."{+}".$productItem;

                    }

                    $productstring = substr($productstring,4);
                    $apiResponseArray['ProductData'] =  $carts['choosen_items'];

                    // apiResponse 
                    $apiResponse = json_encode($apiResponseArray);
                    $return = $this->payment_model->payment($invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,$apiResponse);

                    if($return['o_success'] <= 0){
                        $response['message'] = '<div style="color:red"><b>Error 3: </b>'.$return['o_message'].'</div>'; 


                    }else{
                        $response['message'] = '';
                        $this->removeItemFromCart();
                        // $notificationData = array(
                        //     'order_id' => $return['order_id'],
                        //     'member_id' => $return['member_id']
                        //     );
                        // $this->sendNotification($notificationData);
                    }
                }else{
                    $response['message'] = '<div style="color:red"><b>Error 2: (GetTransactionDetails failed):</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                }
            }else{
                $response['message'] = '<div style="color:red"><b>Error 1: </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
            }
        }
    }

        $member_id =  $this->session->userdata('member_id'); 
        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/payment/payment_response' ,$response);  
        $this->load->view('templates/footer_full'); 
    }


    function removeItemFromCart(){
 
        $carts = $this->session->all_userdata();
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
        unset($carts['choosen_items']); 
    }
	
    function sendNotification($data) 
    {
		//$mobilestat = $this->payment_model->sendNotificationMobile();
		//die($mobilestat);
	
        //$data = array();
        $transactionData = $this->payment_model->getTransactionDetails($data);
        
        //Send email to buyer
        $buyerEmail = $transactionData['buyer_email'];
        $buyerData = $transactionData;
        unset($buyerData['seller']);
        unset($buyerData['buyer_email']);
        $buyerResult = $this->payment_model->sendNotificationEmail($buyerData, $buyerEmail, 'buyer');
        
        //Send email to seller of each product - once per seller
        $sellerData = array(
            'id_order' => $transactionData['id_order'],
            'dateadded' => $transactionData['dateadded'],
            'buyer_name' => $transactionData['buyer_name']
            );
        foreach($transactionData['seller'] as $seller){
            $sellerEmail = $seller['email'];
            $sellerData['totalprice'] = $seller['totalprice'];
            $sellerData['seller_name'] = $seller['seller_name'];
            $sellerData['products'] = $seller['products'];
            $sellerResult = $this->payment_model->sendNotificationEmail($sellerData, $sellerEmail, 'seller');
        }
    }	 
}


/* End of file payment.php */
/* Location: ./application/controllers/payment.php */