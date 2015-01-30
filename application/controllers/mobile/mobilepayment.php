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
     * Review cart before proceeding on payment.
     * @return json
     */
    public function reviewPayment()
    {   
        $apiFormatter = $this->serviceContainer['api_formatter'];
        $checkoutService = $this->serviceContainer['checkout_service']; 

        $canContinue = false;
        $errorMessage = "";
        $formattedCartContents = [];
        $paymentType = trim($this->input->post('paymentType'));
        $mobileCartContents = $this->input->post('cartData') 
                              ? json_decode($this->input->post('cartData')) 
                              : [];

        $cartData = $apiFormatter->updateCart($mobileCartContents, $this->member->getIdMember());
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
        $paymentController = $this->loadController('payment'); 
        $checkoutService = $this->serviceContainer['checkout_service']; 

        $paymentType = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
        $cartData = unserialize($this->member->getUserdata()); 

        $validatedCart = $checkoutService->validateCartContent($this->member);
        $canContinue = $checkoutService->checkoutCanContinue($validatedCart, "cash_delivery"); 

        if(empty($cartData) === false && $canContinue){
            $updatedCart = $paymentController->mobileReviewBridge($cartData, $this->member->getIdMember());
            $txnid = $paymentController->generateReferenceNumber($paymentType, $this->member->getIdMember());
            $dataProcess = $paymentController->cashOnDeliveryProcessing($this->member->getIdMember(), 
                                                                        $txnid, 
                                                                        $updatedCart, 
                                                                        $paymentType);
            $isSuccess = strtolower($dataProcess['status']) === PaymentService::STATUS_SUCCESS;
            $returnArray = array_merge(['isSuccess' => $isSuccess,'txnid' => $txnid], $dataProcess);
        }
        else{
            $returnArray = [
                'isSuccess' => false,
                'status' => PaymentService::STATUS_FAIL,
                'message' => "You can't proceed this checkout.",
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
        $requestUrl = "";
        $message = "";
        $isSuccess = false;
        $cartData = unserialize($this->member->getUserdata());

        $paymentController = $this->loadController('payment');
        $checkoutService = $this->serviceContainer['checkout_service']; 
        $this->load->config('payment', true);

        $paymentConfig = strtolower(ENVIRONMENT) === 'production'
                         ? $this->config->item('production', 'payment')
                         : $this->config->item('testing', 'payment');

        $validatedCart = $checkoutService->validateCartContent($this->member);
        $postPaymentType = trim(strtolower($this->input->post('paymentType')));
        $canContinue = $checkoutService->checkoutCanContinue($validatedCart, $postPaymentType);
        if(empty($cartData) === false 
           && strlen($postPaymentType) > 0
           && $canContinue){ 

            if($postPaymentType === "paypal"){
                $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
            }
            elseif($postPaymentType === "dragonpay"){
                $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
            }

            $requestData = $paymentController->mobilePayBridge($cartData, 
                                                               $this->member->getIdMember(), 
                                                               $paymentType);

            if($requestData['e']){
                $isSuccess = true;
                if($postPaymentType === "paypal"){
                    $returnUrl = $requestData['returnUrl'];
                    $cancelUrl = $requestData['cancelUrl'];
                    $requestUrl = $requestData['d'];
                }
                elseif($postPaymentType === "dragonpay"){
                    $returnUrl = $paymentConfig['payment_type']['dragonpay']['Easyshop']['return_url'];
                    $requestUrl = $requestData['u'];
                }
            }
            else{
                $message = $requestData['m'];
            }
        }
        else{
            $message = "You can't proceed this checkout request.";
        }

        $returnArray = [
            'isSuccess' => $isSuccess, 
            'message' => $message,
            'url' => $requestUrl,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
        ];

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
        $paymentController = $this->loadController('payment'); 
        $checkoutService = $this->serviceContainer['checkout_service'];

        $validatedCart = $checkoutService->validateCartContent($this->member); 
        $canContinue = $checkoutService->checkoutCanContinue($validatedCart, "paypal");

        $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
        $cartData = unserialize($this->member->getUserdata()); 
        if(empty($cartData) === false 
           && $this->input->post('PayerID')
           && $this->input->post('token')
           && $canContinue){ 
            $payerId = trim($this->input->post('PayerID'));
            $token = trim($this->input->post('token'));
            $requestData = $paymentController->mobilePayPersist($cartData,
                                                                $this->member->getIdMember(),
                                                                $paymentType,
                                                                $token,
                                                                $payerId);
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
}
