<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sitemap extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('url_model');
    }
    

    public function index($sub = 'sitemap_index.xml')
    {        
        $file = substr(substr($sub, 8), 0, strpos(substr($sub, 8),'.'));
        $data['urlslist'] = $this->url_model->getURLS($file);    
        $this->load->view("templates/sitemap_view",$data);
    }
    
}





/* End of file sitemap.php */
/* Location: ./application/controllers/sitemap.php */