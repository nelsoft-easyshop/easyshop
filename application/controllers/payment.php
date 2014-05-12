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
        $this->load->model('user_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('memberpage_model'); 
        session_start();
    }

 
    
    public $PayMentPayPal = 1;
    public $PayMentDragonPay = 2;
    public $PayMentCashOnDelivery = 3;
    public $PayMentDragonPayOnlineBanking = 4;

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
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };
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
 
        if(!count($carts['choosen_items']) <= 0){  
            $data['cat_item'] = $itemArray;
            $data['title'] = 'Payment | Easyshop.ph';
            $data['success'] = ($successcount == $itemCount ? true : false);
            $data['codsuccess'] = ($codCount == $itemCount ? true : false);
 
             
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
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };
        
        $PayPalMode             = $this->PayPalMode; 
        $PayPalApiUsername      = $this->PayPalApiUsername;
        $PayPalApiPassword      = $this->PayPalApiPassword;
        $PayPalApiSignature     = $this->PayPalApiSignature;
        $PayPalCurrencyCode     = $this->PayPalCurrencyCode; 
        $PayPalReturnURL        = base_url().'pay/paypal'; 
        $PayPalCancelURL        = base_url().'payment/review'; 

        $carts = $this->session->all_userdata(); 
        $itemArray = $carts['choosen_items'];
        $member_id =  $this->session->userdata('member_id');
        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $name = $address['consignee'];
        $street = $address['c_address']; 
        $cityDescription = $address['c_city'];
        $zipCode = "";
        $email = $address['email']; 
        $telephone = $address['c_telephone'];

        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
        $regionDesc = $cityDetails['parent_location'];
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id'];
        $shipping_amt = 0;  

        if(count($carts['choosen_items']) <= 0){
            echo  '{"e":"0","d":"No item in cart."}';
            exit();
        }

        $tax_amt = 0;   
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
            $productId = $value['id'];
            $productItem =  $value['product_itemID'];
            $tax_amt = $tax_amt;
            $details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
            $shipping_amt += $details[0]['price'];
            $total =  $value['subtotal'] ;
            $ItemTotalPrice += $total;  
        } 
 
        $grandTotal= ($ItemTotalPrice+$tax_amt+$shipping_amt+$handling_amt+$insurance_amt)-$shipping_discount_amt;
 
        $padata =   
        '&RETURNURL='.urlencode($PayPalReturnURL ).
        '&CANCELURL='.urlencode($PayPalCancelURL).
        '&PAYMENTACTION=Sale'. 
        '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
        '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
        $dataitem. 
        '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).  
        '&PAYMENTREQUEST_0_TAXAMT='.urlencode($tax_amt).
        '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_amt).
        '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($handling_amt).
        '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($insurance_amt).
        '&PAYMENTREQUEST_0_SHIPDISCAMT=-'.urlencode($shipping_discount_amt).
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
            $paypalmode = ($PayPalMode == 'sandbox' ? '.sandbox' : '');
            $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
            $data = '{"e":"1","d":"'.$paypalurl.'"}';   
        }else{
            $data = '{"e":"0","d":"'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'"}';
        }
        echo $data;
        exit();
    }

    function changeAddress()
    {
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
                $postdata['default_add'] = "off";
            $data = $this->memberpage_model->edit_consignee_address_by_id($uid, $postdata);
            $this->output->set_output(json_encode($data));
            echo $data;
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
        $PayPalMode             = $this->PayPalMode; 
        $PayPalApiUsername      = $this->PayPalApiUsername;
        $PayPalApiPassword      = $this->PayPalApiPassword;
        $PayPalApiSignature     = $this->PayPalApiSignature; 
        $PayPalCurrencyCode     = $this->PayPalCurrencyCode; 
        $response['message_status'] = "";
        $response['message'] = "";

        $paymentType = $this->PayMentPayPal; ; #paypal
        $apiResponseArray = array(); 
        $apiResponse  = ""; 
        $otherFee = 0;
        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        $cnt = 0;
        $member_id =  $this->session->userdata('member_id');
        $productCount = count($carts['choosen_items']); 
        $itemList =  $carts['choosen_items'];
        $invoice_no = date('Ymhs'); 
        $ip = $this->user_model->getRealIpAddr();   
        $productstring = ""; 
        $analytics = array();  
        

        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
        $regionDesc = $cityDetails['parent_location'];
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id'];  
        
        $transactionID = "";
        if(isset($_GET["token"]) && isset($_GET["PayerID"]))
        {
       
            foreach ($itemList as $key => $value) {
                $sellerId = $value['member_id'];
                $productId = $value['id'];
                $orderQuantity = $value['qty'];
                $price = $value['price'];
                $productItem =  $value['product_itemID'];
                $details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
                $shipping_amt = $details[0]['price'];
                $otherFee = $shipping_amt + $tax_amt;
                $total =  $value['subtotal'] + $otherFee;
                $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$otherFee."{+}".$total."{+}".$productItem;
                $itemList[$key]['otherFee'] = $otherFee;
                $sellerDetails = $this->memberpage_model->get_member_by_id($sellerId); 
                $itemList[$key]['seller_username'] = $sellerDetails['username'];
                $ItemTotalPrice += $total;  
            }



            $productstring = substr($productstring,4);
            $response['itemList'] = $itemList;
            $grandTotal= ($ItemTotalPrice+$handling_amt+$insurance_amt)-$shipping_discount_amt;

            $playerid = $_GET["PayerID"];
            $token= $_GET["token"];
            $padata =   '&TOKEN='.urlencode($token).
            '&PAYERID='.urlencode($playerid).
            '&PAYMENTACTION='.urlencode("SALE").
            '&AMT='.urlencode($grandTotal).
            '&CURRENCYCODE='.urlencode($PayPalCurrencyCode);

            $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,"",$transactionID);
            
            if($return['o_success'] > 0){
                
                $httpParsedResponseArGECD =  $this->paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
                $apiResponseArray['GetExpressCheckoutDetails '] =  $httpParsedResponseArGECD;
                   
                $httpParsedResponseAr = $this->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
                $apiResponseArray['DoExpressCheckoutPayment'] = $httpParsedResponseAr;

                if(("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) && ("SUCCESS" == strtoupper($httpParsedResponseArGECD["ACK"])))
                {
                    $transactionID = urldecode($httpParsedResponseAr["TRANSACTIONID"]);
                    $response['message_status'] = 'Your PayPal Transaction ID : '.$transactionID; 
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
                  
                        $apiResponseArray['ProductData'] =  $carts['choosen_items'];

                        // apiResponse 
                        $apiResponse = json_encode($apiResponseArray);
                        foreach ($itemList as $key => $value) {               
                            $productId = $value['id'];
                            $productItem =  $value['product_itemID'];
                            $orderQuantity = $value['qty'];
                            $itemComplete = $this->payment_model->deductQuantity($productItem,$productId,$orderQuantity);
                            #   UPDATE `es_product_item` SET `quantity` = `quantity` - v_quantity WHERE `product_id` = v_product_id AND `id_product_item` = v_product_item;
        
                        }
                        $complete = $this->payment_model->updatePaymentIfComplete($return['v_order_id'],$apiResponse,$transactionID);
                         
                        if($complete <= 0){
                            $response['message'] = '
                            <div style="color:red"><b>Error 4: </b>Someting went wrong. Please contact us immediately. Your INVOICE NUMBER: '.$return['invoice_no'].'</div>
                            '; 
                        }else{
							$orderHistory = array(
								'order_id' => $return['v_order_id'],
								'order_status' => 0,
								'comment' => 'Paypal transaction confirmed'
							);
							$this->payment_model->addOrderHistory($orderHistory);
                            $response['completepayment'] = true;
                            $response['message'] = '<div style="color:green">Your payment is completed through Paypal</div>';            
                            $response = array_merge($response,$return);
                            $this->removeItemFromCart();
                            $this->session->unset_userdata('choosen_items');
                            $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$return['v_order_id'], 'invoice_no'=>$return['invoice_no']));
                    
                            #google analytics data
                            foreach ($itemList as $key => $value) {

                                $product = $this->product_model->getProductPreview($value['id'],$value['member_id'],"0");
                              
                                $tempAnalytics = array(
                                    'id' => $return['v_order_id'],
                                    'affiliation' => $value['seller_username'],
                                    'revenue' => $value['subtotal'],
                                    'shipping'=> $value['otherFee'],
                                    'tax'=> '0.00',
                                    'currency' => 'PHP', 
                                    'data' => array(
                                        'id' => $return['v_order_id'],
                                        'name' => $value['name'],
                                        'sku' => $value['id'],
                                        'category' => $product['category'],
                                        'price' => $value['price'],
                                        'quantity' => $value['qty']
                                        )
                                    );  
                                    array_push($analytics, $tempAnalytics); 
                            }
                            #end of google analytics data
                        }
                    }else{
                        $response['message'] = '<div style="color:red"><b>Error 3: (GetTransactionDetails failed):</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					}
                }else{
                    $response['message'] = '<div style="color:red"><b>Error 2: </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                }
            }else{
                $response['message'] = '<div style="color:red"><b>Error 1: </b>'.$return['o_message'].'</div>'; 
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

        $carts = $this->session->all_userdata();
        
        if(!isset($carts['choosen_items'])){
            redirect(base_url().'home', 'refresh');
        }
        
        $this->session->set_userdata('paymentticket', true);

        $paymentType = $this->PayMentCashOnDelivery; 
        $apiResponseArray = array(); 
        
        $member_id =  $this->session->userdata('member_id');
        $productCount = count($carts['choosen_items']); 
        $itemList =  $carts['choosen_items'];
        $invoice_no = date('Ymhsd'); 
        $ip = $this->user_model->getRealIpAddr();   
        $productstring = "";  
        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
        $regionDesc = $cityDetails['parent_location'];
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id']; 
        $analytics = array(); 

        $otherFee = 0;
        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        
        $transactionID = "";
        
        foreach ($itemList as $key => $value) {
            $sellerId = $value['member_id'];
            $productId = $value['id'];
            $orderQuantity = $value['qty'];
            $price = $value['price'];
            $tax_amt = $tax_amt;
            $productItem =  $value['product_itemID'];
            $details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
            $shipping_amt = $details[0]['price'];
            $otherFee = $tax_amt + $shipping_amt;
            $total =  $value['subtotal'] + $otherFee;
            $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$otherFee."{+}".$total."{+}".$productItem;
            $itemList[$key]['otherFee'] = $otherFee;
            $sellerDetails = $this->memberpage_model->get_member_by_id($sellerId); 
            $itemList[$key]['seller_username'] = $sellerDetails['username'];
            $ItemTotalPrice += $total;  
        }
    
        $response['itemList'] = $itemList;
        $grandTotal= ($ItemTotalPrice+$handling_amt+$insurance_amt)-$shipping_discount_amt;


        $productstring = substr($productstring,4);
        $apiResponseArray['ProductData'] =  $carts['choosen_items'];

        $apiResponse = json_encode($apiResponseArray);
        $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,$apiResponse,$transactionID);


        if($return['o_success'] <= 0){
            $response['message'] = '<div style="color:red"><b>Error 3: </b>'.$return['o_message'].'</div>'; 
        }else{
            $response['completepayment'] = true;
            $response['message'] = '<div style="color:green">Your payment is completed through Cash on Delivery.</div>';
            $this->removeItemFromCart(); 
            $this->session->unset_userdata('choosen_items');
			$this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$return['v_order_id'], 'invoice_no'=>$return['invoice_no']));
        }   

        

        $response['analytics'] = $analytics;
        $response = array_merge($response,$return);  
        $member_id =  $this->session->userdata('member_id'); 
        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());
       
        $this->session->set_userdata('headerData', $data);
        $this->session->set_userdata('bodyData', $response);

        
        redirect(base_url().'payment/success/cashondelivery', 'refresh');
    }

  
    function payDragonPay(){
        
        $carts = $this->session->all_userdata();
        $member_id =  $this->session->userdata('member_id'); 
        $itemList =  $carts['choosen_items'];

        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $email = $address['email'];
        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
        $regionDesc = $cityDetails['parent_location'];
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id']; 

        $otherFee = 0;
        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        $name = ""; 

        foreach ($itemList as $key => $value) {
            $sellerId = $value['member_id'];
            $productId = $value['id'];
            $orderQuantity = $value['qty'];
            $price = $value['price'];
            $tax_amt = $tax_amt;
            $productItem =  $value['product_itemID'];
            $details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
            $shipping_amt = $details[0]['price'];
            $otherFee = $tax_amt + $shipping_amt;
            $total =  $value['subtotal'] + $otherFee; 
            $sellerDetails = $this->memberpage_model->get_member_by_id($sellerId);  
            $ItemTotalPrice += $total;  
            $name .= "<br>".$value['name'];
        }    

        $grandTotal = ($ItemTotalPrice+$handling_amt+$insurance_amt)-$shipping_discount_amt;
        $dpReturn = $this->dragonpay->getTxnToken($grandTotal,$name,$email);

        $this->session->set_userdata('dragonpayticket', true);
        exit($dpReturn);

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
            redirect(base_url().'home/', 'refresh');
        }

    }


    function dragonPayPostBack(){


        header("Content-Type:text/plain");
        $status = $this->input->post('status');
        if(strtolower($status) == "p" || strtolower($status) == "s"){
            $transactionID = $this->input->post('txnid').'-'.$this->input->post('refno');
            $this->payment_model->checkMyDp($transactionID);
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
        
        $carts = $this->session->all_userdata();
        $member_id =  $this->session->userdata('member_id');
        $productCount = count($carts['choosen_items']); 
        $itemList =  $carts['choosen_items'];
        $invoice_no = date('Ymhsd'); 
        $ip = $this->user_model->getRealIpAddr();   
        $productstring = "";  
        $address = $this->memberpage_model->get_member_by_id($member_id); 
        $city = ($address['c_stateregionID'] > 0 ? $address['c_stateregionID'] :  0);
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($city);
        $regionDesc = $cityDetails['parent_location'];
        $region = $cityDetails['parent_id'];
        $cityDetails = $this->payment_model->getCityOrRegionOrMajorIsland($region);
        $majorIsland = $cityDetails['parent_id']; 
        $analytics = array(); 

        $otherFee = 0;
        $tax_amt = 0;
        $shipping_amt = 0;    
        $handling_amt = 0;
        $shipping_discount_amt = 0;
        $insurance_amt = 0;
        $ItemTotalPrice = 0;
        
        foreach ($itemList as $key => $value) {
            $sellerId = $value['member_id'];
            $productId = $value['id'];
            $orderQuantity = $value['qty'];
            $price = $value['price'];
            $tax_amt = $tax_amt;
            $productItem =  $value['product_itemID'];
            $details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
            $shipping_amt = $details[0]['price'];
            $otherFee = $tax_amt + $shipping_amt;
            $total =  $value['subtotal'] + $otherFee;
            $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$otherFee."{+}".$total."{+}".$productItem;
            $itemList[$key]['otherFee'] = $otherFee;
            $sellerDetails = $this->memberpage_model->get_member_by_id($sellerId); 
            $itemList[$key]['seller_username'] = $sellerDetails['username'];
            $ItemTotalPrice += $total;  
        }
    
        $response['itemList'] = $itemList;
        $grandTotal= ($ItemTotalPrice+$handling_amt+$insurance_amt)-$shipping_discount_amt;


        $txnId = $this->input->get('txnid');
        $refNo = $this->input->get('refno');
        $status =  $this->input->get('status');
        $message = $this->input->get('message');
        $digest = $this->input->get('digest');

        $productstring = substr($productstring,4);
        $apiResponseArray['ProductData'] =  $carts['choosen_items'];
        $apiResponseArray['DragonPayReturn'] = array(
                "txnid" => $txnId,
                "refno" => $refNo,
                "status" => $status,
                "message" => $message,
                "digest" => $digest
            );

        $transactionID = urldecode($txnId).'-'.urldecode($refNo);
        $apiResponse = json_encode($apiResponseArray);
        
        if(strtolower($status) == "p" || strtolower($status) == "s"){

            $paymentType = (strtolower($status) == "s" ? 4 : 2);
            $return = $this->payment_model->payment($paymentType,$invoice_no,$grandTotal,$ip,$member_id,$productstring,$productCount,$apiResponse,$transactionID);
            if($return['o_success'] <= 0){
                $response['message'] = '<div style="color:red"><b>Error 3: </b>'.$return['o_message'].'</div>'; 
            }else{
                $response['completepayment'] = true;
                $response['message'] = '<div style="color:green">Your payment is completed through Dragon Pay.</div>
                <div style="color:red">'.urldecode($message).'</div>';
                $response = array_merge($response,$return);  
                $this->removeItemFromCart(); 
                $this->session->unset_userdata('choosen_items');
                $this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$return['v_order_id'], 'invoice_no'=>$return['invoice_no']));
            } 

        }else{
             $response['message'] = '<div style="color:red">Transaction Not Completed.</div>';
        }
 

        $response['analytics'] = $analytics;
        $data['cat_item'] = $this->cart->contents();
        $data['title'] = 'Payment | Easyshop.ph';
        $data = array_merge($data,$this->fill_header());

        $this->session->set_userdata('headerData', $data);
        $this->session->set_userdata('bodyData', $response);

        $this->session->unset_userdata('dragonpayticket');
        
        redirect(base_url().'payment/success/dragonpay', 'refresh');
        
  
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

    function xx()
    {
        $return = $this->dragonpay->getStatus('049');
       echo $return;
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
        if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        };
        
        //devcode
		/*$data['member_id'] = 74;
		$data['order_id'] = 102;
		$data['invoice_no']= 3;
		
		$data['member_id'] = 74;
		$data['order_id'] = 105;
		$data['invoice_no']= '22-1231-2';*/
		
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
			'invoice_no' => $transactionData['invoice_no']
            );
			
        foreach($transactionData['seller'] as $seller){
            $sellerEmail = $seller['email'];
            $sellerData['totalprice'] = $seller['totalprice'];
            $sellerData['seller_name'] = $seller['seller_name'];
            $sellerData['products'] = $seller['products'];
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
	 *
	 *
	 */
	function test($test, $abs)
	{
	
	}
	
}


/* End of file payment.php */
/* Location: ./application/controllers/payment.php */