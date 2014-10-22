<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;

class mobilePayment extends MY_Controller 
{
    /**
     * The oauth2 server
     *
     */
    private $oauthServer;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * The authenticated member
     *
     * @var EasyShop\Entities\EsMember
     */
    private $member;

    /**
     * Mobile payment constructor
     */
    function __construct() 
    {
        parent::__construct();  
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager'];
        header('Content-type: application/json');

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->oauthServer->getResponse()->send();
            die;
        }
        
        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
    }

    /**
     * Load controller to access the controller function 
     * @param  string $fileName
     * @return object
     */
    private function loadController($fileName)
    {
        $CI = & get_instance();
        $file_path = APPPATH.'controllers/'.$fileName.'.php';
        $object_name = $fileName;
        $class_name = ucfirst($fileName);
        if(file_exists($file_path)){
            require $file_path;
        }
        else{
            show_error("Unable to load the requested controller class: ".$class_name);
        } 

        return $CI->$object_name = new $class_name();
    }
 
    /**
     * Review cart data and validate 
     * @return JSON
     */
    public function doPaymentReview()
    { 
        // Load controller
        $this->paymentController = $this->loadController('payment');

        // refresh member object to get update cart content
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($this->member->getIdMember());
        
        $cartData = unserialize($this->member->getUserdata()); 
        $formattedCartContents = array();
        $canContinue = false;
        $errorMessage = "You have no item in you cart";
        $paymentType = array();

        if(!empty($cartData)){
            unset($cartData['total_items'],$cartData['cart_total']);
            $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember(),"review");
            $cartData = $dataCollection['cartData']; 
            $canContinue = $dataCollection['canContinue'];
            $errorMessage = $dataCollection['errMsg'];
            $paymentType = $dataCollection['paymentType'];
            $formattedCartContents = $this->serviceContainer['api_formatter']->formatCart($cartData);
        }

        $finalPaymentType = [];
        foreach ($paymentType as $key => $value) {
            $finalPaymentType[] = $value;
        }

        $outputData = array(
            'cartData' => $formattedCartContents,
            'canContinue' => $canContinue,
            'errorMessage' => $errorMessage,
            'paymentType' => $finalPaymentType,
        );

        print(json_encode($outputData,JSON_PRETTY_PRINT));
    }

    /**
     * Persist Cash on delivery payment
     * @return JSON
     */
    public function doMobilePayCod()
    {   
        $paymentType = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
        $cartData = unserialize($this->member->getUserdata()); 
        if(!empty($cartData)){
            unset($cartData['total_items'],$cartData['cart_total']);
            $this->paymentController = $this->loadController('payment');
            $txnid = $this->paymentController->generateReferenceNumber($paymentType,$this->member->getIdMember());
            $dataProcess = $this->paymentController->cashOnDeliveryProcessing($this->member->getIdMember(),$txnid,$cartData,$paymentType);
            $isSuccess = (strtolower($dataProcess['status']) == 's') ? true : false;
            $returnArray = array_merge(['isSuccess' => $isSuccess,'txnid' => $txnid],$dataProcess);
        }
        else{
            $returnArray = array(
                    'isSuccess' => false,
                    'status' => 'f',
                    'message' => 'You have no item in your cart',
                );
        }

        echo json_encode($returnArray,JSON_PRETTY_PRINT);
    }

    /**
     * Request payment token
     * @return JSON
     */
    public function doPayRequestToken()
    {
        $returnUrl = "";
        $cancelUrl = "";
        $isSuccess = false;
        $cartData = unserialize($this->member->getUserdata()); 
        if(!empty($cartData) && $this->input->post('paymentType')){
            unset($cartData['total_items'],$cartData['cart_total']);

            if($this->input->post('paymentType') == "paypal"){
                $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
            }
            elseif($this->input->post('paymentType') == "dragonpay"){
                $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
            }

            $this->paymentController = $this->loadController('payment');
            $requestData = $this->paymentController->mobilePayBridge($cartData,$this->member->getIdMember(),$paymentType);
            $urlReturn = ""; 

            if($this->input->post('paymentType') == "paypal"){
                if($requestData['e'] == 1){
                    $isSuccess = true;
                    $urlReturn = $requestData['d'];
                    $message = "";
                    $returnUrl = $requestData['returnUrl'];
                    $cancelUrl = $requestData['cancelUrl'];
                }
                else{
                    $message = $requestData['d'];
                }
            }
            elseif($this->input->post('paymentType') == "dragonpay"){
                if($requestData['e'] == 1){
                    $isSuccess = true;
                    $urlReturn = $requestData['u'];
                    $message = "";
                    $returnUrl = base_url().'payment/dragonPayReturn'; 
                }
                else{
                    $message = $requestData['m'];
                }
            }

            $returnArray = array(
                    'isSuccess' => $isSuccess, 
                    'message' => '',
                    'url' => $urlReturn,
                    'returnUrl' => $returnUrl,
                    'cancelUrl' => $cancelUrl,
                );
        }
        else{
            $returnArray = array(
                    'isSuccess' => $isSuccess, 
                    'message' => 'You have no item in your cart',
                    'url' => '',
                    'returnUrl' => $returnUrl,
                    'cancelUrl' => $cancelUrl,
                );
        }

        echo json_encode($returnArray,JSON_PRETTY_PRINT);
    }

    /**
     * Return url for payment in webview
     * @return json
     */
    public function paypalReturn()
    {
        echo json_encode(array('isSuccess' => 1),JSON_PRETTY_PRINT);
    }
    /**
     * Cancel url for payment in webview
     * @return json
     */
    public function paypalCancel()
    {
        echo json_encode(array('isSuccess' => 1),JSON_PRETTY_PRINT);
    }

    /**
     * Persist Payment in paypal
     * @return json
     */
    public function doPaypalPersistPayment()
    {
        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
        $payerId = $this->input->post('PayerID');
        $token = $this->input->post('token');
        $cartData = unserialize($this->member->getUserdata()); 
        if(!empty($cartData)){
            unset($cartData['total_items'],$cartData['cart_total']);
            $this->paymentController = $this->loadController('payment');
            $requestData = $this->paymentController->mobilePayPersist($cartData,$this->member->getIdMember(),$paymentType,$token,$payerId);
            $isSuccess = (strtolower($requestData['status']) == 's') ? true : false;
            $returnArray = array_merge(['isSuccess' => $isSuccess],$requestData);
        }
        else{
            $returnArray = array(
                    'isSuccess' => false,
                    'status' => 'f',
                    'message' => 'You have no item in your cart',
                );
        }

        echo json_encode($returnArray,JSON_PRETTY_PRINT);
    }

    /**
     * Display transaction details of payment.
     * @return json
     */
    public function getTransactionDetails()
    {
        $txnId = $this->input->post('txnid');
        $paymentDetails = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                                ->findOneBy(['transactionId' => $txnId]);

        $displayArray = array(
                        'transaction_details' => array(
                            'grand_total' => $paymentDetails->getTotal(), 
                            'transaction_id' => $txnId,
                            'reference_number' => $paymentDetails->getInvoiceNo(),
                            'transaction_date' => $paymentDetails->getDateadded()->format('Y-m-d H:i:s'),
                        ),
                    );

        echo json_encode($displayArray,JSON_PRETTY_PRINT);
    }
}
