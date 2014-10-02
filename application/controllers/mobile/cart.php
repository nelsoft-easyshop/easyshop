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
        $this->getCartData();
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
                    
                $relatedItems = $this->productManager->getRecommendedProducts($productId,5);
                $formattedRelatedItem = array();
                foreach($relatedItems as $relatedItem){
                    
                    $defaultImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                         ->getDefaultImage($relatedItem->getIdProduct());
                    
                    array_push($formattedRelatedItem, array('product' => $relatedItem->getName(),
                                                    'slug' => $relatedItem->getSlug(),
                                                    'product_image_path' => $defaultImage->getProductImagePath(),
                                                    'price' => $relatedItem->getFinalPrice(),
                                                    'end_promo' => $relatedItem->getEndPromo(),
                                                    'original_price' => $relatedItem->getOriginalPrice(),
                                                    'sold_price' => $relatedItem->getSoldPrice(),
                                                    'percentage' => $relatedItem->getDiscountPercentage(),
                                                    ));
                }
                
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
                    'relatedItems' => $formattedRelatedItem,
                    'mapAttributes' => $mappedAttributes
                ];
                
                $formattedCartContents = array_merge($formattedCartContents, [$rowId => $formattedCartItem]);
            }
        }
       
        print(json_encode($formattedCartContents));
    }

}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
