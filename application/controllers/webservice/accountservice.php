<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AccountService extends MY_Controller
{

    /**
     *  Constructor Declaration
     *  1. All posted data will be brought first for authentication evaluation
     *  2. Authentication method is located under MY_Controller.php
     *  @return true/false
     */
    public function __construct()
    {   
        parent::__construct();
        if($this->input->post()){
            $this->authentication($this->input->post(), $this->input->post('hash'));
        }  

    }

    /**
     *  Loading of test view for accountservice
     *  @return View
     */
    public function index() {
        $this->load->view("pages/accounts");
    }

    /**
     *  method to access the product_model with the getProdCount method to return the count of a certain product
     *  @return View
     */
    public function getProductCount() { 
        
        $this->load->model("product_model");
        $id = $this->input->post("id");
        $count = $this->product_model->getProdCount($id);

        return $count;
    }

    /**
     *  method to access the user_model with the CountUsers method to return the count of users
     *  @return View
     */
    public function getUserCount() {

        $this->load->model("user_model");
        $count = $this->user_model->CountUsers();
        return $count;
    }
}

?> 


