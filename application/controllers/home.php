<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
		$data = array('title' => 'Home | Easyshop.ph',
                'page_javascript' => 'assets/JavaScript/home.js',
                'data' => $this->getHomeXML('page/home_files'),
                'category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE ),
				);
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/home_view', $data);
        $this->load->view('templates/footer_full');
    }
    
	public function under_construction(){
		$data = array('title' => 'Under Construction | Easyshop.ph',);
		$data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/underconstruction_view');
        $this->load->view('templates/footer_full');
	}
	
	public function underConstructionSubscribe()
	{
        $this->load->model('register_model');
		if( $this->input->post('uc_subscribe') && $this->form_validation->run('subscription_form')){
			$data['email'] = $this->input->post('subscribe_email');
			$this->register_model->subscribe($data['email']);
			
			// Send notification email to user
			$this->register_model->sendNotification($data, 'subscribe');
			
			redirect(base_url().'home/under_construction');
		}
	}
	
	public function pagenotfound(){
		$data = array('title' => 'Page Not Found | Easyshop.ph',);
				$data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/general_error');
        $this->load->view('templates/footer_full');
	}
    
    public function splash(){

        $this->load->view('pages/undermaintenance.php');

    }

    private function getHomeXML($file){
        $this->load->model('product_model');
        $xml = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");
       
        $simple = json_decode(json_encode($xml), 1);
        $data = array();
        foreach ($simple as $key => $product){
            if (is_array($product) && $key != "mainSlide"){
                foreach ($product as $id => $key2){
                    $result = $this->product_model->getProductById($key2);
                    if (!empty($result)){
                        $data[$key][$id] = $result;
                    }
                    else{
                        $data[$key][$id] = "empty";
                    }
                }
            }
            else{
                $data[$key] = $product;
            }
       }
       $data['category1_pid_main'] = array($this->product_model->getProductById($data['category1_pid_main']));

       return $data;
    }
    
	

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
