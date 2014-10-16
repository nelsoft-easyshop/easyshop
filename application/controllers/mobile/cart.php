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
        $mobileCartContents = json_decode($this->input->post('cartData'));
        $mobileCartContents = $mobileCartContents ? $mobileCartContents : array();
        $this->serviceContainer['api_formatter']->updateCart($mobileCartContents,$this->member->getIdMember());

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
        $formattedCartContents = $this->serviceContainer['api_formatter']->formatCart($cartData);

        print(json_encode($formattedCartContents,JSON_PRETTY_PRINT));
    }
}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
