<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsProductImage as EsProductImage;
use EasyShop\PaymentService\PaymentService as PaymentService;

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
        $apiFormatter = $this->serviceContainer['api_formatter'];

        // Load controller
        $this->paymentController = $this->loadController('payment');

        $mobileCartContents = $this->input->post('cartData') 
                              ? json_decode($this->input->post('cartData')) 
                              : [];
        $cartData = $apiFormatter->updateCart($mobileCartContents,$this->member->getIdMember());
        $formattedCartContents = [];
        $canContinue = false;
        $paymentType = [];
        $errorMessage = "Please verify your email address.";
        if((int)$this->member->getIsEmailVerify() > 0){
            $errorMessage = "You have no item in you cart";
            if(empty($cartData) === false){
                unset($cartData['total_items'],$cartData['cart_total']);
                $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember(),"review");
                $cartData = $dataCollection['cartData']; 
                $canContinue = $dataCollection['canContinue'];
                $errorMessage = $dataCollection['errMsg'];
                $paymentType = $dataCollection['paymentType'];
                $formattedCartContents = $apiFormatter->formatCart($cartData);
            }
        }

        $finalPaymentType = [];
        foreach ($paymentType as $key => $value) {
            $finalPaymentType[] = $value;
        }

        $outputData = [
            'cartData' => $formattedCartContents,
            'canContinue' => $canContinue,
            'errorMessage' => $errorMessage,
            'paymentType' => $finalPaymentType,
        ];

        print(json_encode($outputData,JSON_PRETTY_PRINT));
    }

    
    /**
     * Review cart before proceeding on payment.
     * @return json
     */
    public function reviewPayment()
    {   
        $apiFormatter = $this->serviceContainer['api_formatter'];
        $checkoutService = $this->serviceContainer['checkout_service']; 

        $canContinue = false;
        $errorMessage = "";
        $paymentType = trim($this->input->post('paymentType'));
        $mobileCartContents = $this->input->post('cartData') 
                              ? json_decode($this->input->post('cartData')) 
                              : [];

        $cartData = $apiFormatter->updateCart($mobileCartContents,$this->member->getIdMember());
        $memberCartData = unserialize($this->member->getUserdata());
        $isCartNotEmpty = empty($memberCartData) === false;
        $cartData = $isCartNotEmpty ? $memberCartData : []; 
        if((int)$this->member->getIsEmailVerify()){ 
            if($isCartNotEmpty){
                unset($cartData['total_items'],$cartData['cart_total']); 
                $validatedCart = $checkoutService->validateCartContent($this->member);
                $canContinue = $checkoutService->checkoutCanContinue($validatedCart, $paymentType); 
                $formattedCartContents = $apiFormatter->formatCart($validatedCart, true, $paymentType);
            }
            else{
                $errorMessage = "You have no item in you cart";
            }
        }
        else{
            $errorMessage = "Please verify your email address.";
        }

        $outputData = [
            'cartData' => $formattedCartContents,
            'canContinue' => $canContinue,
            'errorMessage' => $errorMessage,
        ];

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

        $this->paymentController = $this->loadController('payment');
        $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember(),"review");
        $cartData = $dataCollection['cartData']; 
        $check = $this->checkAvailableInPayment($cartData,$paymentType);

        if(empty($cartData) === false){
            unset($cartData['total_items'],$cartData['cart_total']); 
            $txnid = $this->paymentController->generateReferenceNumber($paymentType,$this->member->getIdMember());
            $dataProcess = $this->paymentController->cashOnDeliveryProcessing($this->member->getIdMember(),$txnid,$cartData,$paymentType);
            $isSuccess = strtolower($dataProcess['status']) === PaymentService::STATUS_SUCCESS;
            $returnArray = array_merge(['isSuccess' => $isSuccess,'txnid' => $txnid],$dataProcess);
        }
        else{
            $returnArray = [
                    'isSuccess' => false,
                    'status' => PaymentService::STATUS_FAIL,
                    'message' => 'You have no item in your cart',
                ];
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

        $this->load->config('payment', true);
        $paymentConfig = strtolower(ENVIRONMENT) === 'production'
                         ? $this->config->item('production', 'payment')
                         : $this->config->item('testing', 'payment');

        if(empty($cartData) === false && $this->input->post('paymentType')){
            unset($cartData['total_items'],$cartData['cart_total']);

            $postPaymentType = trim(strtolower($this->input->post('paymentType')));
            if($postPaymentType === "paypal"){
                $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
            }
            elseif($postPaymentType === "dragonpay"){
                $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
            }
 
            $this->paymentController = $this->loadController('payment');
            $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember(),"review");
            $cartData = $dataCollection['cartData']; 
            $check = $this->checkAvailableInPayment($cartData,$paymentType);

            $requestData = $this->paymentController->mobilePayBridge($cartData,$this->member->getIdMember(),$paymentType);
            $urlReturn = ""; 

            if($postPaymentType === "paypal"){
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
            elseif($postPaymentType === "dragonpay"){
                if($requestData['e'] == 1){
                    $isSuccess = true;
                    $urlReturn = $requestData['u'];
                    $message = "";
                    $returnUrl = $paymentConfig['payment_type']['dragonpay']['Easyshop']['return_url'];
                }
                else{
                    $message = $requestData['m'];
                }
            }

            $returnArray = [
                'isSuccess' => $isSuccess, 
                'message' => '',
                'url' => $urlReturn,
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
            ];
        }
        else{
            $returnArray = [
                'isSuccess' => $isSuccess, 
                'message' => 'You have no item in your cart',
                'url' => '',
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
            ];
        }

        echo json_encode($returnArray,JSON_PRETTY_PRINT);
    }

    /**
     * Return url for payment in webview
     * @return json
     */
    public function paypalReturn()
    {
        echo json_encode(['isSuccess' => true], JSON_PRETTY_PRINT);
    }

    /**
     * Cancel url for payment in webview
     * @return json
     */
    public function paypalCancel()
    {
        echo json_encode(['isSuccess' => true], JSON_PRETTY_PRINT);
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
        if(empty($cartData) === false){
            unset($cartData['total_items'],$cartData['cart_total']);
            $this->paymentController = $this->loadController('payment');

            $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember(),"review");
            $cartData = $dataCollection['cartData']; 
            $check = $this->checkAvailableInPayment($cartData,$paymentType);

            $requestData = $this->paymentController->mobilePayPersist($cartData,$this->member->getIdMember(),$paymentType,$token,$payerId);
            $isSuccess = strtolower($requestData['status']) === PaymentService::STATUS_SUCCESS;
            $returnArray = array_merge(['isSuccess' => $isSuccess],$requestData);
        }
        else{
            $returnArray = [
                'isSuccess' => false,
                'status' => PaymentService::STATUS_FAIL,
                'message' => 'You have no item in your cart',
            ];
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

        $displayArray = [
            'transaction_details' => [
                'grand_total' => $paymentDetails->getTotal(), 
                'transaction_id' => $txnId,
                'reference_number' => $paymentDetails->getInvoiceNo(),
                'transaction_date' => $paymentDetails->getDateadded()->format('Y-m-d H:i:s'),
            ],
        ];

        $paymentProductDetails = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                          ->findBy(['order' => $paymentDetails->getIdOrder()]);

        foreach ($paymentProductDetails as $value) {

            $productDetails = $this->em->getRepository('EasyShop\Entities\EsProductItem')
                                       ->findOneBy(['idProductItem' => $value->getProductItemId()]);

            $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                     ->getDefaultImage($productDetails->getProduct()->getIdProduct());

            $imageDirectory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
            $imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

            if($productImage != null){
                $imageDirectory = $productImage->getDirectory();
                $imageFileName = $productImage->getFilename();
            }

            $displayArray['products'][] = [
                'quantity' => $value->getOrderQuantity(),
                'price' => $value->getTotal(),
                'name' => $productDetails->getProduct()->getName(),
                'product_image' => $imageDirectory.'categoryview/'.$imageFileName,
            ];
        }

        echo json_encode($displayArray,JSON_PRETTY_PRINT);
    }

    private function checkAvailableInPayment($itemArray,$paymentType)
    {
        if($paymentType == EsPaymentMethod::PAYMENT_PAYPAL){
            $keyString = "paypal";
            $label = "Paypal";
        }
        elseif($paymentType == EsPaymentMethod::PAYMENT_DRAGONPAY){
            $keyString = "dragonpay";
            $label = "Dragonpay";
        }
        elseif($paymentType == EsPaymentMethod::PAYMENT_CASHONDELIVERY){
            $keyString = "cash_delivery";
            $label = "Cash on Delivery";
        }

        $error = 0;
        foreach ($itemArray as $key => $value) {
            $value['isAvailable'] = "true";
            if(!$value[$keyString]){
                $itemArray[$key]['isAvailable'] = "false";
                $error++;
            }
        }

        if($error > 0){
            $returnArray = [
                'isSuccess' => false, 
                'message' => 'One of you item is not avaialble in '.$label,
                'url' => '',
                'returnUrl' => '',
                'cancelUrl' => '',
                'cartData' => $this->serviceContainer['api_formatter']->formatCart($itemArray),
            ];
            echo json_encode($returnArray,JSON_PRETTY_PRINT);
            exit();
        }


        return $itemArray;
    }
}
