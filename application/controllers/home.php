<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('register_model');
		$this->load->library('cart');
        $this->load->library('home_xml');
    }
    
    
    public function index() {
		$data = array('title' => 'Home | Easyshop.ph',
                'page_javascript' => 'assets/JavaScript/home.js',
                'data' => $this->home_xml->getFilenameID('home_files'),
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
		if( $this->input->post('uc_subscribe') && $this->form_validation->run('subscription_form')){
			$data['email'] = $this->input->post('subscribe_email');
			$this->register_model->subscribe($data['email']);
			
			// Send notification email to user
			$this->register_model->sendNotification($data, 'subscribe');
			
			redirect(base_url().'home/under_construction');
		}
	}
	
	
	#(Hnd pa na iimpliment)function to get the Parent in the es_cat, 
	public function getfirstlevel() {
        $row = $this->home_model->get_firstlevel();
        echo json_encode($row);
    }


	public function pagenotfound(){
		$data = array('title' => 'Page Not Found | Easyshop.ph',);
				$data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/general_error');
        $this->load->view('templates/footer_full');
	}

	public function comingSoon(){
        $this->load->view('pages/coming_soon');
	}
    
	

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
