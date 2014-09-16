<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct(); 
        $this->load->library("xmlmap");
        $this->load->model("product_model");  

        //Making response json type
        header('Content-type: application/json'); 
    }

    public function index()
    {
        $pageContent = $this->xmlmap->getFilename("page/mobile_home_files"); 
        echo json_encode($pageContent,JSON_PRETTY_PRINT);
    }

}

/* End of file home.php */
/* Location: ./application/controllers/mpobile/home.php */
