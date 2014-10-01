<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager'];
        header('Content-type: application/json');

        //Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->oauthServer->getResponse()->send();
            die;
        }

        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']); 
    }

    public function getAddress()
    { 
        $userShippingAddress = $this->em->getRepository("EasyShop\Entities\EsAddress")
                        ->findOneBy(["idMember"=>$this->member->getIdMember(),"type"=>"1"]);
     
        $address['delivery_address']['consignee_name'] = $userShippingAddress->getConsignee();
        $address['delivery_address']['mobile'] = $userShippingAddress->getMobile();
        $address['delivery_address']['telephone'] = $userShippingAddress->getTelephone();
        $address['delivery_address']['address'] = $userShippingAddress->getAddress();
        $address['delivery_address']['state_region'] = $userShippingAddress->getStateregion()->getIdLocation();
        $address['delivery_address']['city'] = $userShippingAddress->getCity()->getIdLocation();
        $address['delivery_address']['country'] = $userShippingAddress->getCountry()->getIdLocation();

        echo json_encode($address,JSON_PRETTY_PRINT);
    }

    public function setShippingAddress()
    {   
        // Load services
        $userManager = $this->serviceContainer['user_manager']; 
        $parameter['memberId'] = $this->member->getIdMember();

        $consignee = ($this->input->post('consignee')) ? trim($this->input->post('consignee')) : "";
        $mobileNumber = ($this->input->post('mobilenumber')) ? trim($this->input->post('mobilenumber')) : "";
        $telephoneNumber = ($this->input->post('telephonenumber')) ? trim($this->input->post('telephonenumber')) : "";
        $streetAddress = ($this->input->post('streetaddress')) ? trim($this->input->post('streetaddress')) : "";
        $region = ($this->input->post('region')) ? trim($this->input->post('region')) : "";
        $city = ($this->input->post('city')) ? trim($this->input->post('city')) : "";
        $memberId = $this->member->getIdMember();
        $type = 1;
        $result = $userManager->setAddress($streetAddress,$region,$city,$memberId,$type,$consignee,$mobileNumber,$telephoneNumber);
        unset($result['errors']);
        echo json_encode($result,JSON_PRETTY_PRINT);
    }
}