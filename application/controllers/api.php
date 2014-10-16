<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsAddress;

class Api extends MY_Controller
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

    public function __construct()
    {
        parent::__construct();
        $this->oauthServer = $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager'];

        header('Content-type: application/json');
    }

    /**
     * Create the token
     *
     */
    public function doCreateToken()
    {
        $this->oauthServer->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * Returns user credentials depending on the token given
     *
     * Data Returned:
     * fullname, email, city, is_email_verify
     *
     * @return JSON
     */
    public function doGetUserDetails()
    {
        //Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->oauthServer->getResponse()->send();
            die;
        }

        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->find(intval($oauthToken['user_id']));

        $userDetails = [];

        if($member){
            $userShippingAddress = $this->em->getRepository("EasyShop\Entities\EsAddress")
                        ->findOneBy(["idMember"=>$member->getIdMember(),"type"=> EsAddress::TYPE_DELIVERY]);
            if($userShippingAddress){
                $userDetails['userDetails']['fullname'] = $member->getFullname() === null || $member->getFullname() === '' ? $member->getUsername() : $member->getFullname();
                $userDetails['userDetails']['email'] = $member->getEmail();
                $userDetails['userDetails']['city'] = $userShippingAddress->getCity()->getLocation();
                $userDetails['userDetails']['is_email_verify'] = $member->getIsEmailVerify();
            }
        }

        echo json_encode($userDetails, JSON_PRETTY_PRINT);
    }

}

