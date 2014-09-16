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
                    $options[$key] = $attributeArray['value'].'~';
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

    
    public function getCartData()
    {
        print(json_encode($this->cartData));
    }

}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
