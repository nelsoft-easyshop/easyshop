<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AccountService extends MY_Controller
{

    /**
     *  Constructor Declaration
     *  1. All posted data will be brought first for authentication evaluation
     *  2. Authentication method is located under MY_Controller.php
     *  @return boolean
     */
    public function __construct()
    {   
        parent::__construct();
        if($this->input->post()){
            $this->authentication($this->input->post(), $this->input->post('hash'));
        }  

    }

    /**
     *  Method to access the product_model with the getProdCount method to return the count of a certain product
     *
     *  @return integer $count
     */
    public function getProductCount() 
    {   
        
        $this->load->model("product_model");
        $id = $this->input->post("id");
        $count = $this->product_model->getProdCount($id);

        return $count;
    }

    /**
     *  Method to access the user_model with the CountUsers method to return the count of users
     *
     *  @return integer $count
     */
    public function getUserCount() 
    {

        $this->load->model("user_model");
        $count = $this->user_model->CountUsers();
        return $count;
    }

}



