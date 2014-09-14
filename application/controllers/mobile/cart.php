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
     * AccountManager
     *
     * @var EasyShop\Account\AccountManager
     */
    private $accountManager;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * Mobile Cart constructor
     *
     */
    function __construct() 
    {
        parent::__construct();
        $this->cartManager = $this->serviceContainer['cart_manager'];
        $this->cartImplementation = $this->cartManager->getCartObject();
        $this->accountManager = $this->serviceContainer['account_manager'];
        $this->em = $this->serviceContainer['entity_manager'];
        header('Content-type: application/json');
        
        $isAuthenticated = $this->accountManager->authenticateWebServiceClient('mobile', trim($this->input->post('skey')));

        if(!$isAuthenticated){
            $response = ['Web service error' => 'Invalid webservice key'];
            print(json_encode($response,JSON_PRETTY_PRINT));
            exit();
        }
        
        
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
        $memberId = $this->input->post('memberId');
        
        $cartContents = $this->cartManager->getValidatedCartContents($memberId);
        
        foreach($mobileCartContents as $mobileCartContent){

            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $mobileCartContent->id]);
                                
            $options = array();
            foreach($mobileCartContent->details->mapAttributes as $key => $attribute){
              #  $options[$key] = 
            }
                                
            /*
            if($product){
                $this->cartManager->addItem($product->getIdProduct(), $mobileCartContent->quantity:, $option = array())
            }
            */
            

            #$mobileCartContent
        }


        print(json_encode($response,JSON_PRETTY_PRINT));
    }

    
    public function getCartData()
    {
        $memberId = $this->input->post('memberId');
        $cartContents = $this->cartManager->getValidatedCartContents($memberId);
        
        print('<pre>');
        print_r($cartContents);
        
        print(json_encode($cartContents));
    }

}

/* End of file cart.php */
/* Location: ./application/controllers/mobile/cart.php */
