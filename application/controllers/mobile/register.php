<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct(); 
        $this->load->library("xmlmap");
        $this->load->model("product_model");
        header('Content-type: application/json');
    }

    /**
     * [username_check description]
     * @return [type]
     */
    public function username_check()
    {
        if($this->input->post('username')){
            $username = $this->input->post('username');
            if($this->register_model->validate_username($username))
                echo 1;
            else
                echo 0;
        }
    }
    
    /**
     * [email_check description]
     * @return [type]
     */
    public function email_check()
    {
        if($this->input->post('email')){
            $email = $this->input->post('email');
            if($this->register_model->checkEmailIfExists($email)){
                echo 0;
            }
            else {
                echo 1;
            }
        }
    }
    
    /**
     * [mobile_check description]
     * @return [type]
     */
    public function mobile_check()
    {
        if($this->input->post('mobile')){
            $mobile = $this->input->post('mobile');
            if($this->register_model->checkMobileIfExists($mobile)){
                echo 0;
            }
            else {
                echo 1;
            }
        }
    }

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */
