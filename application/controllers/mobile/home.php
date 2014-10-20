<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsProductImage as EsProductImage;

class Home extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct(); 
        $this->load->library("xmlmap"); 
        $this->em = $this->serviceContainer['entity_manager'];
        $this->pm = $this->serviceContainer['product_manager'];

        //Making response json type
        header('Content-type: application/json'); 
    }

    public function index()
    { 
        $mobileHomeContent = $this->serviceContainer['xml_cms']->getMobileHomeData();
        

        echo json_encode($mobileHomeContent,JSON_PRETTY_PRINT);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/mpobile/home.php */
