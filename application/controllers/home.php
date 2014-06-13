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
    
    public function policy(){
        $data = array('title' => 'Privacy Policy | Easyshop.ph',);
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/policy');
        $this->load->view('templates/footer_full');
    }
    
    public function terms(){
        $data = array('title' => 'Terms and Conditions | Easyshop.ph',);
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/terms');
        $this->load->view('templates/footer_full');
    }
    
    public function faq(){
        $data = array('title' => 'F.A.Q. | Easyshop.ph',);
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/faq');
        $this->load->view('templates/footer_full');
    }
    
    public function contact(){
        $data = array('title' => 'Contact us | Easyshop.ph',);
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/contact');
        $this->load->view('templates/footer_full');
    }
    
    /*
	 *	Subscription Handler
	 */
	public function subscribe()
	{
		if($this->input->post('subscribe_btn') && $this->form_validation->run('subscription_form')){
            $this->load->model('register_model');
			$data['email'] = $this->input->post('subscribe_email');
            $result = $this->register_model->subscribe($data['email']);
			// Send notification email to user 
			if($result){		
              $this->register_model->sendNotification($data, 'subscribe');
              $data['title'] = 'Successful Subscription | Easyshop.ph';
              $data['content'] = 'You have successfully Subscribed!';
              $data['sub_content'] =  'Thank you for choosing to keep in touch with Easyshop.ph. Expect to hear many things from us soon.';
              $this->load->view('pages/landingpage_success', $data);
            }else{
                redirect('home','refresh');
            }
		}else{
            redirect('home','refresh');
        }

	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
