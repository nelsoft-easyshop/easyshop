<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cart extends MY_Controller 
{

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
  
        $this->oauthServer =  $this->serviceContainer['oauth2_server']; 
        $this->em = $this->serviceContainer['entity_manager'];
        $this->apiFormatter = $this->serviceContainer['api_formatter'];
        $this->carManager = $this->serviceContainer['cart_manager'];

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
        $mobileCartContents = $this->input->post('cartData') 
                              ? json_decode($this->input->post('cartData')) 
                              : [];
        $cartData = $this->apiFormatter->updateCart($mobileCartContents,$this->member->getIdMember()); 

        print(json_encode($cartData,JSON_PRETTY_PRINT));
    }

    /**
     * Returns the cart data
     *
     * @return JSON
     */
    public function getCartData()
    {
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find($this->member->getIdMember());
        $arrayCart = unserialize($member->getUserdata());
        $cartData = empty($arrayCart) ? [] : $arrayCart;
        $formattedCartContents = $this->apiFormatter->formatCart($cartData);

        print(json_encode($formattedCartContents,JSON_PRETTY_PRINT));
    }
}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
