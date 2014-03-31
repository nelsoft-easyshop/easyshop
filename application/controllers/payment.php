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
        $this->session->unset_userdata('cart_contents');
        $cart_contentss=array('cart_contents'=>$carts);
        $this->session->set_userdata($cart_contentss);
        return true;
    }

    function review(){
        $carts = $this->session->all_userdata();
        if(!count($carts['cart_contents']) <=0){
   
            $member_id =  $this->session->userdata('member_id');
            $data['address'] = $this->payment_model->getUserAddress($member_id);
            $data['cat_item'] = $carts['cart_contents'];
            $data['title'] = 'Payment | Easyshop.ph';
            $data = array_merge($data,$this->fill_header());

            $this->load->view('templates/header', $data);
            $this->load->view('pages/payment/payment_options' ,$data); 
            $this->load->view('templates/footer_full'); 
        }else{
           redirect('/cart/', 'refresh'); 
        }
    }

    function payment_option(){
        $carts = $this->session->all_userdata();
        print_r($carts['cart_contents']);
    }


	#SET UP PAYPAL FOR PARAMETERS
    #SEE REFERENCE SITE FOR THE PARAMETERS
    # https://developer.paypal.com/webapps/developer/docs/classic/express-checkout/integration-guide/ECCustomizing/
    public function paypal_setexpresscheckout() 
    {


        $PayPalMode             = $this->PayPalMode; 
        $PayPalApiUsername      = $this->PayPalApiUsername;
        $PayPalApiPassword      = $this->PayPalApiPassword;
        $PayPalApiSignature     = $this->PayPalApiSignature;
        $PayPalCurrencyCode     = $this->PayPalCurrencyCode; 
        $PayPalReturnURL        = base_url().'payment/paypal'; 
        $PayPalCancelURL        = base_url().'payment/shipping'; 

        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        $cnt = 0;
        $solutionType = "Sole";
        $landingPage = "Billing";

        $carts = $this->session->all_userdata();
        $dataitem = "";
        foreach ($carts['cart_contents'] as $key => $value) {
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
        '&PAYMENTREQUEST_0_AMT='.urlencode($grandTotal).
        '&SOLUTIONTYPE='.urlencode($solutionType).
        '&LANDINGPAGE='.urlencode($landingPage);
        
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
    
     $firstResponse = "";
     $secondResponse = "";

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
            foreach ($carts['cart_contents'] as $key => $value) {
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
            $firstResponse = json_encode($httpParsedResponseAr);
   
            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
            {
                $response['message_status'] = 'Your Transaction ID :'.urldecode($httpParsedResponseAr["TRANSACTIONID"]);
                
                if('Completed' == $httpParsedResponseAr["PAYMENTSTATUS"]){
                    $response['message_status'] .= '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
                }
                elseif('Pending' == $httpParsedResponseAr["PAYMENTSTATUS"]){
                    $response['message_status'] .= '<div style="color:red">Transaction Complete, but payment is still pending! You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
                }

                $transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
                $nvpStr = "&TRANSACTIONID=".$transactionID;
                
                $httpParsedResponseAr =  $this->paypal->PPHttpPost('GetTransactionDetails', $nvpStr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
                $secondResponse = json_encode($httpParsedResponseAr);
                if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                    // echo 'Query here<pre>';
                    // echo '<pre>',print_r($httpParsedResponseAr),'</pre>';
                    #address details  
                    $invoice_no = date('YmhDs');
                    $ip = $this->user_model->getRealIpAddr();
                    $consignee = urldecode($httpParsedResponseAr['SHIPTONAME']);
                    $streetno = '';
                    $street = urldecode($httpParsedResponseAr['SHIPTOSTREET']);
                    $city = urldecode($httpParsedResponseAr['SHIPTOCITY']);
                    $brgy = urldecode($httpParsedResponseAr['SHIPTOSTATE']);
                    $country = urldecode($httpParsedResponseAr['SHIPTOCOUNTRYNAME']);
                    $zipcode = urldecode($httpParsedResponseAr['SHIPTOZIP']);
                    $phone = "000-00-00";
                    $cellphone = "0000-000-00-00";
                    $data_response = json_encode($httpParsedResponseAr);
                    $data_item = json_encode($carts['cart_contents']);
                    $payment_type = 2;
                    $member_id = $this->session->userdata('member_id'); 
                    $item_count = count($carts['cart_contents']);
                    $option_count = 0;
                    $tax = 0;
                    $productstring = ""; # productid,qty,price,tax,total
                    $optionstring = ""; # productid,name,value

                    foreach ($carts['cart_contents'] as $key => $value) {
                    $productstring .= '<||>'.$value['id']."{+}".$value['qty']."{+}".$value['price']."{+}".$tax."{+}".$value['subtotal']."{+}".$value['rowid'].'{+}'.$value['member_id'];

                        if(!count($value['options']) <= 0)
                            foreach ($value['options'] as $key_opt => $value_opt) {
                            $optionstring .= '<||>'.$value['id'].'{+}'.$key_opt.'{+}'.$value_opt."{+}".$value['rowid'];;
                            $option_count++;
                        }
                    }

                  $productstring = substr($productstring,4);
                  $optionstring = substr($optionstring,4);

                  $return = $this->payment_model->payment($invoice_no,$ItemTotalPrice,$ip,$productstring,$item_count,$optionstring,$option_count,$member_id,$payment_type,$data_item,$data_response,$consignee,$streetno,$street,$city,$brgy,$country,$zipcode,$phone,$cellphone);
                  
                  if($return['o_success'] <= 0){
                       $response['message'] = '<div style="color:red"><b>Error 3: </b>'.$return['o_message'].'</div>'; 
                  }else{
                        $response['message'] = '';
                        $notificationData = array(
                            'order_id' => $return['order_id'],
                            'member_id' => $return['member_id']
                        );
                        $this->sendNotification($notificationData);
                  }
                }else{
                    $response['message'] = '<div style="color:red"><b>Error 2: (GetTransactionDetails failed):</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                }
            }else{
                $response['message'] = '<div style="color:red"><b>Error 1: </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
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


    public function sendNotification($data) 
    {
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

    function xx(){


       
       $carts = $this->session->all_userdata();
       $item_count = count($carts['cart_contents']);
       $option_count = 0;
       $tax = 0;

        // productid,qty,price,tax,total
       $productstring = "";
        // productid,name,value

       $optionstring = "";
       foreach ($carts['cart_contents'] as $key => $value)
       {
           $productstring .= '<||>'.$value['id']."{+}".$value['qty']."{+}".$value['price']."{+}".$tax."{+}".$value['subtotal']."{+}".$value['rowid'];

           if(!count($value['options']) <= 0)
               foreach ($value['options'] as $key_opt => $value_opt) {
                  $optionstring .= '<||>'.$value['id'].'{+}'.$key_opt.'{+}'.$value_opt."{+}".$value['rowid'];;
                  $option_count++;
              }
        }

        $productstring = substr($productstring,4);
        $optionstring = substr($optionstring,4);
        $data_item = json_encode($carts['cart_contents']);
        echo '<pre>',print_r($carts['cart_contents']),'</pre>';
                   
    }
	
	
}

  
/* End of file payment.php */
/* Location: ./application/controllers/payment.php */