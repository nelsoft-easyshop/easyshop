<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Account extends MY_Controller 
{


    /**
     * The oauth2 server
     *
     */
    private $oauthServer;

    /**
     * Account constructor
     *
     */
    function __construct() 
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

    
    /**
     * Registers a user
     * 
     * @return JSON
     */
    public function register()
    {
        $accountManager = $this->serviceContainer['account_manager'];
        $errors = [];
        $isSuccessful = false;

        $username =  trim($this->input->post('username'));
        $password = trim($this->input->post('password'));
        $email = trim($this->input->post('email'));
        $contactno = trim($this->input->post('mobile'));

        $registrationResult = $accountManager->registerMember($username, $password, $email, $contactno, true);
        if(empty($registrationResult['errors'])){
            $isSuccessful = true;
            $accountManager->sendAccountVerificationLinks($registrationResult["member"], true, true);
        }
        else{
            $errors = array_merge($errors, $registrationResult['errors']);
        }

        $response['errors'] = $errors;
        $response['isSuccessful'] = $isSuccessful;
        print(json_encode($response,JSON_PRETTY_PRINT));
    }

}

/* End of file account.php */
/* Location: ./application/controllers/mobile/account.php */
