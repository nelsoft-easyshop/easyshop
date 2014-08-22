<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authenticate extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct();
        $this->load->helper('htmlpurifier');
        $this->load->model("register_model");
        $this->load->model("user_model");
        header('Content-type: application/json');
    }

    /**
     * [index description]
     * @return JSON
     */
    public function register()
    {
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
    }

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */
