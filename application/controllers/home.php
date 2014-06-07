<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->load->model('product_model');
		$data = array('title' => 'Home | Easyshop.ph',
                'data' => $this->product_model->getHomeContent(),
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

    /*  Returns server time in Month Day, Year 24Hour:Min:Sec format
     *  Timezone is set to Asia/Manila
     */
    
    public function getServerTime(){
        date_default_timezone_set('Asia/Manila');
        echo date('M d,Y H:i:s');
    }
    


}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
