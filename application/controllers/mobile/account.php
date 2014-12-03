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
        $emailService = $this->serviceContainer['email_notification'];

        $this->load->library('parser');
        $this->config->load('email', true);
        $imageArray = $this->config->config['images'];
        $emailSubject = $this->lang->line('registration_subject');
        $errors = [];
        $isSuccessful = false;

        $username =  trim($this->input->post('username'));
        $password = trim($this->input->post('password'));
        $email = trim($this->input->post('email'));
        $contactno = trim($this->input->post('mobile'));

        $registrationResult = $accountManager->registerMember($username, $password, $email, $contactno, true);
        if(empty($registrationResult['errors'])){
            $isSuccessful = true;
            $emailContentData = [
                'user' => $username,
                'emailVerified' => true
            ];
            $emailContent = $this->parser->parse('templates/landingpage/lp_reg_email', 
                                                  $emailContentData, 
                                                  true);
            $emailService->setRecipient($email)
                         ->setSubject($emailSubject)
                         ->setMessage($emailContent, $imageArray)
                         ->sendMail();
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
