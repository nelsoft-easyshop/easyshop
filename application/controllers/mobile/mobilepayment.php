<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
     * Cash on delivery payment type number
     */
    const CASH_ON_DELIVERY = 3;

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

        $cartData = unserialize($this->member->getUserdata()); 
        $formattedCartContents = array();
        $canContinue = false;
        $errorMessage = "You have no item in you cart";
        $paymentType = array();

        if(!empty($cartData)){
            unset($cartData['total_items'],$cartData['cart_total']);
            $this->paymentController = $this->loadController('payment');
            $dataCollection = $this->paymentController->mobileReviewBridge($cartData,$this->member->getIdMember());
            $cartData = $dataCollection['cartData']; 
            $canContinue = $dataCollection['canContinue'];
            $errorMessage = $dataCollection['errMsg'];
            $paymentType = $dataCollection['paymentType'];

            foreach($cartData as $rowId => $cartItem){
                $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findOneBy(['idProduct' => $cartItem['id']]);
                
                if($product){
                    $member = $product->getMember();
                    $productId = $product->getIdProduct();
                           
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
                        
                    $images = array();
                    foreach($product->getImages() as $image){
                        $images[$image->getIdProductImage()] = $image->getProductImagePath();
                    }

                    
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
                    
                    $formattedCartItem = [
                        'rowid' => $cartItem['rowid'],
                        'productId' =>  $cartItem['id'],
                        'productItemId' => $cartItem['product_itemID'],
                        'maximumAvailability' => $cartItem['maxqty'],
                        'slug' => $cartItem['slug'],
                        'name' => $cartItem['name'],
                        'quantity' => $cartItem['qty'],
                        'description' => $product->getDescription(),
                        'brand' => $product->getBrand()->getName(),
                        'originalPrice' => $cartItem['original_price'],
                        'finalPrice' => $cartItem['price'],
                        'sellerDetails' => $sellerDetails,
                        'images' => $images, 
                        'mapAttributes' => $mappedAttributes,
                        'cashOnDelivery' =>  (isset($cartItem['cash_delivery'])) ? $cartItem['cash_delivery'] : 0 ,
                        'locationAvalailability' => $cartItem['availability'],
                    ];
                    
                    $formattedCartContents = array_merge($formattedCartContents, [$rowId => $formattedCartItem]);
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

    public function doMobilePayCod()
    {   
        $paymentType = self::CASH_ON_DELIVERY;
        $cartData = unserialize($this->member->getUserdata()); 
        if(!empty($cartData)){
            unset($cartData['total_items'],$cartData['cart_total']);
            $this->paymentController = $this->loadController('payment');
            $txnid = $this->paymentController->generateReferenceNumber($paymentType,$this->member->getIdMember());
            $dataProcess = $this->paymentController->cashOnDeliveryProcessing($this->member->getIdMember(),$txnid,$cartData,$paymentType);
            $isSuccess = (strtolower($dataProcess['status']) == 's') ? true : false;
            $returnArray = array_merge(['isSuccess' => $isSuccess],$dataProcess);
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
