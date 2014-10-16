<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends MY_Controller
{

    /**
     * The oauth2 server
     *
     */
    private $oauthServer;

    public function __construct()
    {
        parent::__construct();
        $this->oauthServer = $this->serviceContainer['oauth2_server'];
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

}

