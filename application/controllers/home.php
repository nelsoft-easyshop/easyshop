<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {

        $this->load->model('product_model');

        $home_content = $this->product_model->getHomeContent();

        $layout_arr = array();

        foreach($home_content['section'] as $section){
            array_push($layout_arr,$this->load->view('templates/home_layout/'.$section['category_detail']['layout'], array('section' => $section), TRUE));
        }

	$data = array('title' => ' Shopping made easy | Easyshop.ph',
	    'data' => $home_content,
	    'sections' => $layout_arr,
	    'category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE ),
	    'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
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
        $data = array('title' => 'Privacy Policy | Easyshop.ph',
                      'metadescription' => "Read Easyshop.ph's Privacy Policy",);
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/policy');
        $this->load->view('templates/footer_full');
    }
    
    public function terms(){
        $data = array('title' => 'Terms and Conditions | Easyshop.ph',
                      'metadescription' => "Read Easyshop.ph's Terms and Conditions",
                   );
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/terms');
        $this->load->view('templates/footer_full');
    }
    
    public function faq(){
        $data = array('title' => 'F.A.Q. | Easyshop.ph',
                      'metadescription' => 'Get in the know, read the Frequently Asked Questions at Easyshop.ph',
                      );
        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
        $this->load->view('pages/web/faq');
        $this->load->view('templates/footer_full');
    }
    
    public function contact(){
        $data = array('title' => 'Contact us | Easyshop.ph',
                      'metadescription' => 'Get in touch with our Customer Support',
                );
        $data = array_merge($data, $this->fill_header());
	$this->load->view('templates/header', $data);
        $this->load->view('pages/web/contact');
        $this->load->view('templates/footer_full');
    }
    
    public function guide_buy(){
        $data = array('title' => 'How to buy | Easyshop.ph',
                      'metadescription' => 'Learn how to purchase at Easyshop.ph',
                );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
    	$this->load->view('pages/web/how-to-buy');
    }
    
    public function guide_sell(){
        $data = array('title' => 'How to sell | Easyshop.ph',
                      'metadescription' => 'Learn how to sell your items at Easyshop.ph',
                );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
	    $this->load->view('pages/web/how-to-sell');
    }

	function userprofile(){
		$this->load->model('memberpage_model');
		
		$sellerslug = $this->uri->segment(1);
		
		$session_data = $this->session->all_userdata();
		$vendordetails = $this->memberpage_model->getVendorDetails($sellerslug);
		
		if($vendordetails){
			$data['title'] = 'Vendor Profile | Easyshop.ph';
			$data['my_id'] = (empty($session_data['member_id']) ? 0 : $session_data['member_id']);
			$data = array_merge($data, $this->fill_header());
            $data['render_logo'] = false;
            $data['render_searchbar'] = false;
            $this->load->view('templates/header', $data);
			$sellerid = $vendordetails['id_member'];
			$user_product_count = $this->memberpage_model->getUserItemCount($sellerid);
			$data = array_merge($data,array(
					'vendordetails' => $vendordetails,
					'image_profile' => $this->memberpage_model->get_Image($sellerid),
					'banner' => $this->memberpage_model->get_Image($sellerid,'vendor'),
					'products' => $this->memberpage_model->getVendorCatItems($sellerid,$sellerslug),
					'active_count' => intval($user_product_count['active']),
					'deleted_count' => intval($user_product_count['deleted']),
                    'sold_count' => intval($user_product_count['sold']),
					));
			$data['allfeedbacks'] = $this->memberpage_model->getFeedback($sellerid);
			
			$data['hasStoreDesc'] = (string)$data['vendordetails']['store_desc'] !== '' ? true : false;
			$data['product_count'] = count($data['products']);
			$data['renderEdit'] = (int)$sellerid === (int)$data['my_id'] ? true : false;
			#if 0 : no entry - unfollowed, hence display follow
			#if 1 : has entry - followed, hence display unfollow
			$data['subscribe_status'] = $this->memberpage_model->checkVendorSubscription($data['my_id'],$sellerslug)['stat'];
			$data['subscribe_count'] = (int)$this->memberpage_model->countVendorSubscription($data['my_id'], $sellerslug)['subscription_count'];
			
			$this->load->view('pages/user/vendor_view', $data);
            $this->load->view('templates/footer');
			
		}
		else{
			$data = array('title' => 'Page Not Found | Easyshop.ph',);
			$data = array_merge($data, $this->fill_header());
			$this->load->view('templates/header', $data);
			$this->load->view('pages/general_error');
			$this->load->view('templates/footer_full');
		}
	}

    
    

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
