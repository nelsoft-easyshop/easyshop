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
        $this->load->model('product_model'); 
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

        $cartData = unserialize($this->member->getUserdata()); 
        $formattedCartContents = array();
        $canContinue = false;
        $errorMessage = "You have no item in you cart";
        $paymentType = array();

        if(!empty($cartData)){
            unset($cartData['total_items'],$cartData['cart_total']);
            $this->paymentController = $this->loadController('payment');
            $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember(),"review");
            $cartData = $dataCollection['cartData']; 
            $canContinue = $dataCollection['canContinue'];
            $errorMessage = $dataCollection['errMsg'];
            $paymentType = $dataCollection['paymentType']; 
            $formattedCartContents = array();
            foreach($cartData as $rowId => $cartItem){
                $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                        ->findOneBy(['idProduct' => $cartItem['id']]);

                if($product){
                    $productId = $product->getIdProduct();
                    $member = $product->getMember();
                    $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->getAttributesByProductIds($productId);

                    $mappedAttributes = array();
                    foreach($attributes as $attribute){
                        $isSelected = false;
                        $optionalIdentifier = intval($attribute['is_other']) === 0 ? 'a_' : 'b_';

                        foreach($cartItem['options'] as $head => $option){
                            $explodedOption = explode('~',$option);
                            $fieldValue = $explodedOption[0];
                            $fieldPrice = isset($explodedOption[1]) ? $explodedOption[1] : 0;
                            if(strtolower($attribute['head']) == strtolower($head) &&
                                strtolower($attribute['value']) == strtolower($fieldValue) &&
                                strtolower($attribute['price']) == strtolower($fieldPrice)){
                                $isSelected = true;
                                break;
                            }
                        }

                        array_push($mappedAttributes, array(
                            'id' => $optionalIdentifier.$attribute['detail_id'],
                            'value' => $attribute['value'],
                            'name' => $attribute['head'],
                            'price' => $attribute['price'],
                            'imageId' => $attribute['image_id'],
                            'isSelected' => $isSelected,
                        ));
                    }

                    $formattedCartContents[$rowId] = [
                        'rowid' => $cartItem['rowid'],
                        'productId' =>  $cartItem['id'],
                        'productItemId' => $cartItem['product_itemID'],
                        'maximumAvailability' => $cartItem['maxqty'],
                        'slug' => $cartItem['slug'],
                        'name' => $cartItem['name'],
                        'quantity' => $cartItem['qty'], 
                        'originalPrice' => $cartItem['original_price'],
                        'finalPrice' => $cartItem['price'],  
                        'mapAttributes' => $mappedAttributes
                    ];

                    // format product details
                    $productDetails = array(
                                    'name' => $cartItem['name'],
                                    'description' => $product->getDescription(),
                                    'brand' => $product->getBrand()->getName(),
                                    'condition' => $product->getCondition(),
                                    'discount' => $product->getDiscount(),
                                    'basePrice' => $cartItem['price'],
                                ); 
                    $formattedCartContents[$rowId]['productDetails'] = $productDetails;

                    // format imgages
                    $productImages = array();
                    foreach($product->getImages() as $image){
                        $mergeData = [
                                    'product_image_path' =>  $image->getProductImagePath(),
                                    'id' => $image->getIdProductImage(),
                                ];
                        $productImages[] = $mergeData;
                    }
                    $formattedCartContents[$rowId]['productImages'] = $productImages;

                    // format seller details
                    $ratings = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                    ->getAverageRatings($member->getIdMember());
                    $sellerRating = array();
                    $sellerRating['rateCount'] = $ratings['count'] ;
                    $sellerRating['rateDescription'][$this->lang->line('rating')[0]] = $ratings['rating1'];
                    $sellerRating['rateDescription'][$this->lang->line('rating')[1]] = $ratings['rating2'];
                    $sellerRating['rateDescription'][$this->lang->line('rating')[2]] = $ratings['rating3'];  
                    
                    $sellerDetails = array(
                        'sellerName' => $member->getUsername(),
                        'sellerRating' => $sellerRating,
                        'sellerContactNumber' => $member->getContactno(),
                        'sellerEmail ' => $member->getEmail()
                        );
                    $formattedCartContents[$rowId]['sellerDetails'] = $sellerDetails;

                    // format product combination attributes
                    $complete = array();
                    $specification = array();
                    $productCombinationAttributes = array();

                    $productAttributes = $this->product_model->getProductAttributes($productId, 'NAME');
                    $productAttributes = $this->product_model->implodeAttributesByName($productAttributes);
                    foreach ($productAttributes as $key => $productOption) {
                        $newArrayOption = array(); 

                        for ($i=0; $i < count($productOption) ; $i++) { 
                            $type = ($productAttributes[$key][$i]['type'] == 'specific' ? 'a' : 'b');
                            $newKey = $type.'_'.$productAttributes[$key][$i]['value_id']; 
                            $newArrayOption[$newKey] = $productOption[$i];
                            $newArrayOption[$newKey]['name'] = $key; 
                            $newArrayOption[$newKey]['id'] = $newKey; 
                        }

                        foreach ($newArrayOption as $key => $value) {
                            unset($newArrayOption[$key]['type']);
                            unset($newArrayOption[$key]['datatype']);
                            unset($newArrayOption[$key]['datatype']);
                            unset($newArrayOption[$key]['img_path']);
                            unset($newArrayOption[$key]['img_file']);
                            unset($newArrayOption[$key]['value_id']);
                        }

                        if(count($productOption)>1){
                            $productCombinationAttributes[$key] = $newArrayOption; 
                        }
                        elseif((count($productOption) === 1)&&(($productOption[0]['datatype'] === '5'))||($productOption[0]['type'] === 'option')){
                            $productCombinationAttributes[$key] = $newArrayOption; 
                            $specification = $newArrayOption;
                        }
                        else{
                            $specification = $newArrayOption; 
                        }
                    }

                    // product specification
                    $productSpecification = [];
                    foreach ($specification as $key => $value) {
                        $productSpecification[] = $value;
                    }
                    $formattedCartContents[$rowId]['productSpecification'] = $productSpecification;
                    
                    foreach ($productCombinationAttributes as $key => $value) {
                        foreach ($productCombinationAttributes[$key] as $key2 => $value2) {
                            $complete[] = $value2; 
                        }
                    }
                    $formattedCartContents[$rowId]['productCombinationAttributes'] = $complete;

                    // format product combination details
                    $productQuantity = $this->product_model->getProductQuantity($productId, false, false, $product->getStartPromo());
                    $productQuantityNew = [];
                    foreach ($productQuantity as $key => $valuex) {
                        unset($productQuantity[$key]['attr_lookuplist_item_id']);
                        unset($productQuantity[$key]['attr_name']);
                        $newCombinationKey = array();

                        for ($i=0; $i < count($valuex['product_attribute_ids']); $i++) { 
                            $type = ($valuex['product_attribute_ids'][$i]['is_other'] == '0' ? 'a' : 'b'); 
                            array_push($newCombinationKey, $type.'_'.$valuex['product_attribute_ids'][$i]['id']);
                        }

                        unset($productQuantity[$key]['product_attribute_ids']);
                        $productQuantity[$key]['combinationId'] = $newCombinationKey;
                        $productQuantity[$key]['id'] = $key;
                        $productQuantityNew[] = $productQuantity[$key];
                    }
                    $formattedCartContents[$rowId]['productCombinationDetails'] = $productQuantityNew;
                }
            }
        }

        $outputData = array(
            'cartData' => $formattedCartContents,
            'canContinue' => $canContinue,
            'errorMessage' => $errorMessage,
            'paymentType' => $paymentType,
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
}
