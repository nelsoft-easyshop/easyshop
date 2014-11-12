<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsAddress;
use EasyShop\Entities\OauthTokenLookup;

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
        $request = $this->oauthServer->handleTokenRequest(OAuth2\Request::createFromGlobals());
        $tokens = $request->getParameters();
        
        // If request is successful, persist partner tokens to database
        if(!isset($tokens['error'])){
            $lookupEntry = new OauthTokenLookup();
            $lookupEntry->setAccessToken($tokens['access_token']);
            $lookupEntry->setRefreshToken($tokens['refresh_token']);
            $lookupEntry->setClientId($this->input->post('client_id'));
            $lookupEntry->setClientSecret($this->input->post('client_secret'));

            $this->em->persist($lookupEntry);
            $this->em->flush();        
        }
        
        $request->send();
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
                $userDetails['userDetails']['id_member'] = $member->getIdMember();
                $userDetails['userDetails']['fullname'] = $member->getFullname() === null || $member->getFullname() === '' ? $member->getUsername() : $member->getFullname();
                $userDetails['userDetails']['email'] = $member->getEmail();
                $userDetails['userDetails']['city'] = $userShippingAddress->getCity()->getLocation();
                $userDetails['userDetails']['is_email_verify'] = $member->getIsEmailVerify();
            }
        }

        echo json_encode($userDetails, JSON_PRETTY_PRINT);
    }


    public function doRevokeToken()
    {
        // Check if given access_token is even valid to begin with
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->oauthServer->getResponse()->send();
            die;
        }

        // Assert complete post params
        if(!$this->input->post('client_id') || !$this->input->post('client_secret')){
            header('HTTP/1.1 400 Bad Request', true, 400);
            $msg['error'] = "invalid_client";
            $msg['error_description'] = "Client credentials were not found in the headers or body";
            echo json_encode($msg, JSON_PRETTY_PRINT);
            die;
        }

        // Retrieve row from lookup table using given access token
        $token = $this->em->getRepository('EasyShop\Entities\OauthTokenLookup')
                ->find($this->input->post('access_token'));

        // Double check whether the token still exists or not
        if(!$token){
            header('HTTP/1.1 400 Bad Request', true, 400);
            $msg['error'] = "invalid_client";
            $msg['error_description'] = "Token does not exist or has been revoked already";
            echo json_encode($msg, JSON_PRETTY_PRINT);
            die;
        }

        $clientId = $token->getClientId();
        $clientSecret = $token->getClientSecret();

        // Check if the requestor is a valid app of EasyShop
        if($clientId !== $this->input->post('client_id') || $clientSecret !== $this->input->post('client_secret')){
            header('HTTP/1.1 400 Bad Request', true, 400);
            $msg['error'] = "invalid_client";
            $msg['error_description'] = "The client credentials are invalid";
            echo json_encode($msg, JSON_PRETTY_PRINT);
            die;
        }

        // Delete tokens
        $accessToken = $this->em->getRepository('EasyShop\Entities\OauthAccessToken')
                ->find($token->getAccessToken());

        $refreshToken = $this->em->getRepository('EasyShop\Entities\OauthRefreshToken')
                ->find($token->getRefreshToken());

        $this->em->remove($accessToken);
        $this->em->remove($refreshToken);
        $this->em->remove($token);
        $this->em->flush();

        $msg['status'] = "success";
        echo json_encode($msg, JSON_PRETTY_PRINT);
    }

}

