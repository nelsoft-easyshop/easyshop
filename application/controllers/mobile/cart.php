<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cart extends MY_Controller 
{
    
    /**
     * The cartManager
     *
     * @var EasyShop\Cart\CartManager
     */
    private $cartManager;
    
    /**
     * Product manager
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;
    
    /**
     * The cart object
     * 
     * @var EasyShop\Cart\CartInterface
     */
    private $cartImplementation;

    /**
     * Oauth2 server
     *
     * @var Oauth\Server
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
     * Cart Data
     *
     * @var mixed
     */
    private $cartData;
    
    /**
     * Mobile Cart constructor
     *
     */
    function __construct() 
    {
        parent::__construct();

        $this->load->model('product_model'); 
        $this->cartManager = $this->serviceContainer['cart_manager'];
        $this->productManager = $this->serviceContainer['product_manager'];
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->cartImplementation = $this->cartManager->getCartObject();
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
     * Persists the cart object in the database
     * 
     * @return JSON
     */
    public function persist()
    {
        $response = array();
        

        $mobileCartContents = json_decode($this->input->post('cartData'));
        $mobileCartContents = $mobileCartContents ? $mobileCartContents : array();
        foreach($mobileCartContents as $mobileCartContent){
                              
            $options = array();
            foreach($mobileCartContent->mapAttributes as $attribute => $attributeArray){
                if(intval($attributeArray->isSelected) === 1){
                    $options[trim($attributeArray->name, "'")] = $attributeArray->value.'~'.$attributeArray->price;
                }
               
            }
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $mobileCartContent->slug]);
            if($product){
                $this->cartManager->addItem($product->getIdProduct(), $mobileCartContent->quantity, $options);
            }
        }
        $this->cartImplementation->persist($this->member->getIdMember());
        
        return $this->getCartData();
    }

    /**
     * Returns the cart data
     *
     */
    public function getCartData()
    {
        $cartData = unserialize($this->member->getUserdata());
        $cartData = $cartData ? $cartData : array();
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
                
                // get reviews
                $reviews = $this->getReviews($productId,$member->getIdMember());
                foreach ($reviews as $key => $value) {
                    unset($reviews[$key]['reviewerid']);
                    unset($reviews[$key]['ISOdate']); 

                    foreach ($reviews[$key]['replies'] as $key2 => $value2) {
                        unset($reviews[$key]['replies'][$key2]['replyto']);
                        unset($reviews[$key]['replies'][$key2]['reviewerid']);
                        unset($reviews[$key]['replies'][$key2]['title']);
                    }
                }
                $formattedCartContents[$rowId]['reviews'] = $reviews;
            }
        }

        print(json_encode($formattedCartContents,JSON_PRETTY_PRINT));
    }

       /**
     * Get review of the seller
     * @param  integer $product_id
     * @param  integer $sellerid
     * @return mixed
     */
    public function getReviews($product_id, $sellerid)
    {
        $recent = array();
        $recent = $this->product_model->getProductReview($product_id);

        if(count($recent)>0){
            $retrieve = array();
            foreach($recent as $data){
                array_push($retrieve, $data['id_review']);
            }
            $replies = $this->product_model->getReviewReplies($retrieve, $product_id);
            foreach($replies as $key=>$temp){
                $temp['review'] = html_escape($temp['review']);
            }
            $i = 0;
            $userid = $this->session->userdata('member_id');
            foreach($recent as $review){
                $recent[$i]['replies'] = array();
                $recent[$i]['reply_count'] = 0;
                if($userid === $review['reviewerid']){
                    $recent[$i]['is_reviewer'] = 1;
                }
                else{
                    $recent[$i]['is_reviewer'] = 0;
                }

                foreach($replies as $reply){
                    if($review['id_review'] == $reply['replyto']){
                        array_push($recent[$i]['replies'], $reply);
                        $recent[$i]['reply_count']++;
                    }
                }
                $i++;
            }
        }

        return $recent;
    }

}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
