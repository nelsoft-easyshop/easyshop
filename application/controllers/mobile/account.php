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
       # header('Content-type: application/json');
    }
    
    public function test()
    {
        $this->load->view('pages/test');
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
    
        /*
        $data['username'] = $username = $this->input->get('username');
        $data['password'] = $password = $this->input->get('password');
        $data['email'] = $email = $this->input->get('email');
        $data['mobile'] = $mobile = substr($this->input->get('mobile'),1); 
        $keyGuard = $this->input->get('skey'); //mobiledevkey
        $guard = $this->user_model->authenticateWebKey($keyGuard)[0]['cnt'];

        $display = array();
        $error = array();
        if(!$this->register_model->validate_username($username)){
            array_push($error, "Username already used");
        }
        elseif ($username == "") {
            array_push($error, "Username required");
        }

        if($password == "") {
            array_push($error, "Password required");
        }

        if($this->register_model->checkEmailIfExists($email)){
            array_push($error, "Email address already used");
        }
        elseif ($email == "") {
            array_push($error, "Email address required");
        }

        if($this->register_model->checkMobileIfExists($mobile)){
            array_push($error, "Mobile already used");
        }

        if($guard == 0){
            array_push($error, "Invalid Key");
        }

        $passed = (count($error) <= 0) ? 1 : 0;

        if($passed > 0){
            $temp['mobilecode'] = $this->register_model->rand_alphanumeric(6);
            $temp['memberId'] = $data['memberId'] = $this->register_model->signupMember_landingpage($data)['id_member'];
            $temp['emailcode'] = $data['emailcode'] = sha1($this->session->userdata('session_id').time()); 
            $emailCount = 0;
            do{
                $emailResult = false;//$this->register_model->sendNotification($data, 'signup');
                $emailCount++;
            }while( !$emailResult && $emailCount < 3 );
            $temp['email'] = $emailResult ? 1 : 0;
            $result = $this->register_model->store_verifcode($temp);

            if( is_null($data['memberId']) || $data['memberId'] == 0 || $data['memberId'] == ''){
                array_push($error, 'Database registration failure');
                $passed = 0;
            }

            if(!$emailResult){
                array_push($error, 'Failed to send verification email. Please verify in user page upon logging in.');
            }

            if(!($passed > 0 && $result) ){
                $passed = 0;
            }
        }
        $display['passed'] = $passed;
        $display['error'] = $error;
        die(json_encode($display,JSON_PRETTY_PRINT));
        */
    }

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */
