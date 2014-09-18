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
        
        /*
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->oauthServer->getResponse()->send();
            die;
        }
        
        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
        $this->cartData = unserialize($this->member->getUserdata());
        */
    
          $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find(2);
        $this->cartData = unserialize($this->member->getUserdata());
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

        foreach($mobileCartContents as $mobileCartContent){
                              
            $options = array();
            foreach($mobileCartContent->details->mapAttributes as $attribute => $attributeArray){
                if(intval($attributeArray['isSelected']) === 1){
                    $options[$key] = $attributeArray['value'].'~'.$attributeArray['price'];
                }
               
            }
                  
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $mobileCartContent->id]);
     
            if($product){
                $this->cartManager->addItem($product->getIdProduct(), $mobileCartContent->quantity, $options);
            }
            else{
                array_push($response, [$mobileCartContent->id => 'Item does not exist']);
            }
        }
        $this->cartImplementation->persist($this->member->getIdMember());

        print(json_encode($response,JSON_PRETTY_PRINT));
    }

    /**
     * Returns the cart data
     *
     */
    public function getCartData()
    {
        $cartData = $this->cartData;
        $formattedCartContents = array();
        foreach($cartData as $rowId => $cartItem){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['idProduct' => $cartItem['id']]);
            
            if($product){
                $member = $product->getMember();
                       
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
                    
                $relatedItems = $this->productManager->getRecommendedProducts($product->getIdProduct(),5);
                $formattedRelatedItem = array();
                foreach($relatedItems as $relatedItem){
                    
                    array_push($formattedRelatedItem, array('product' => $relatedItem->getName(),
                                                    'slug' => $relatedItem->getSlug(),
                                                    'product_image_path' => '',
                                                    'price' => $relatedItem->getFinalPrice(),
                                                    'end_promo' => $relatedItem->getEndPromo(),
                                                    'original_price' => $relatedItem->getOriginalPrice(),
                                                    'sold_price' => $relatedItem->getSoldPrice(),
                                                    'percentage' => $relatedItem->getDiscountPercentage(),
                                                    ));
                }
                print_r($formattedRelatedItem);
                exit();
                    
                $images = array();
                foreach($product->getImages() as $image){
                    $images[$image->getIdProductImage()] = $image->getProductImagePath();
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
                    'images' => $images
                ];
                

                $formattedCartContents = array_merge($formattedCartContents, 
                                                     [$rowId => $formattedCartItem]
                                        );
                                        
                                        
                                        
              
            }
        }
        
        
        
        print('<pre>');
        print_r($formattedCartContents);
      #  print(json_encode($this->cartData));
    }

}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
