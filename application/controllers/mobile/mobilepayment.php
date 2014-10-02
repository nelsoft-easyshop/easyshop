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


        // Loading required model
        $this->load->model('payment_model');

        // Set Cart Data to session Data;
        $this->setDataToSession($this->member->getIdMember());
    }

    /**
     * [loadController description]
     * @param  [type] $fileName [description]
     * @return [type]           [description]
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
     * [setDataToSession description]
     * @param [type] $memberId [description]
     */
    private function setDataToSession($memberId)
    {
        $cartData = unserialize($this->member->getUserdata()); 
        unset($cartData['total_items'],$cartData['cart_total']); 
        $this->session->set_userdata('choosen_items', $cartData);
        $this->session->set_userdata('member_id', $memberId); 
    }

    /**
     * [doPaymentReview description]
     * @return [type] [description]
     */
    public function doPaymentReview()
    { 
        $this->paymentController = $this->loadController('payment');
        $remove = $this->payment_model->releaseAllLock($this->member->getIdMember());
        $itemArray = $this->session->userdata('choosen_items');

        $qtySuccess = $this->paymentController->mobileBridge($itemArray,$this->member->getIdMember());

        // print_r( $itemArray );exit();
    }
}
