<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends MY_Controller {


    /**
     * Authentication constructor
     *
     */
    function __construct() 
    {
        parent::__construct();
        header('Content-type: application/json');
    }


    /**
     * Registers a user
     * 
     * @return JSON
     */
    public function register()
    {
        $accountManager = $this->serviceContainer['account_manager']; 

        $errors = array();
        $isSuccessful = false;
        $isAuthenticated = $accountManager->authenticateWebServiceClient('mobile', $this->input->post('skey'));

        if($isAuthenticated){
            $username =  $this->input->post('username');
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $contactno = $this->input->post('mobile');
            $registrationResult = $accountManager->registerMember($username, $password, $email, $contactno);
            if(empty($registrationResult['errors'])){
                $isSuccessful = true;
            }
            else{
                $errors = array_merge($errors, $registrationResult['errors']);
            }
        }
        else{
            array_push($errors, ['Web service error' => 'Invalid webservice key']);
        }
        
        $response['errors'] = $errors;
        $response['isSuccessful'] = $isSuccessful;
        print(json_encode($response,JSON_PRETTY_PRINT));
    }

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */
