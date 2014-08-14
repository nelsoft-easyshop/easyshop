<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AccountService extends MY_Controller
{
    public function __construct()
    {   
        parent::__construct();
        if($this->input->post()){
            $this->authentication($this->input->post(), $this->input->post('hash'));
        }  

    }

    public function index() {
        $this->load->view("pages/accounts");
    }

    public function getProductCount()
    {   
        $this->load->model("product_model");
        $id = $this->input->post("id");
        $count = $this->product_model->getProdCount($id);

        return $count;
    }

    public function getUserCount()
    {
        $this->load->model("user_model");
        $count = $this->user_model->CountUsers();

        return $count;
    }

    
}
 ?>