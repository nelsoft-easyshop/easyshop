<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mobilePayment extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager'];
        header('Content-type: application/json');

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        // if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
        //     $this->oauthServer->getResponse()->send();
        //     die;
        // }
        
        // $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        // $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find(128);

        // Loading required model
        $this->load->model('payment_model');

        // Set Cart Data to session Data;
        $this->setDataToSession($this->member->getIdMember());
    }

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

    public function setDataToSession($memberId)
    {
        $cartData = unserialize($this->member->getUserdata());
        print_r( $cartData );
        unset($cartData['total_items'],$cartData['cart_total']); 
        $this->session->set_userdata('choosen_items', $cartData);
        $this->session->set_userdata('member_id', $memberId); 
    }

    public function doPaymentReview()
    { 
        $paymentController = $this->loadController('payment');
        $remove = $this->payment_model->releaseAllLock($this->member->getIdMember());
        $qtySuccess = $paymentController->resetPriceAndQty();
        
    }
}