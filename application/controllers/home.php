<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
		$this->load->library('xmlmap');
		$this->load->model('product_model');
    }
    
	public $feeds_prodperpage = 5;
	
    public function index() {

        $home_content = $this->product_model->getHomeContent();

        $layout_arr = array();
        if(!$this->session->userdata('member_id')){
            foreach($home_content['section'] as $section){
                array_push($layout_arr,$this->load->view('templates/home_layout/'.$section['category_detail']['layout'], array('section' => $section), TRUE));
            }
        }

    	$data = array('title' => ' Shopping made easy | Easyshop.ph',
    	    'data' => $home_content,
    	    'sections' => $layout_arr,
    	    'category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE ),
    	    'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
    	);

        $data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header', $data);
		
		if( $data['logged_in'] ){
			$data = array_merge($data, $this->getFeed());
			$this->load->view("templates/home_layout/layoutF",$data);
		}else{
			$this->load->view('pages/home_view', $data);
		}
        
        
		
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

    public function getFeed()
	{
    	$perPage = $this->feeds_prodperpage;
		$memberId = $this->session->userdata('member_id');
		$easyshopId = trim($this->xmlmap->getFilenameID('page/content_files','easyshop-member-id'));
		$partnersId = explode(',',trim($this->xmlmap->getFilenameID('page/content_files','partners-member-id')));
		array_push($partnersId, $easyshopId);

		$prodId = ($this->input->post('ids')) ? $this->input->post('ids') : 0; 

		$data = array(
			'featured_prod' => $this->product_model->getProductFeed($memberId,$partnersId,$prodId,$perPage),
			'new_prod' => $this->product_model->getNewProducts($perPage),
			'followed_users' => $this->product_model->getVendorSubscription($memberId),
			'banners' => $this->product_model->getStaticBannerFeed(),
			'promo_items' => $this->product_model->getStaticProductFeed('promo'),
			'popular_items' => $this->product_model->getStaticProductFeed('popular'),
			'featured_product' => $this->product_model->getStaticProductFeed('featured')
		);
		
		#Assemble featured product ID array for exclusion on LOAD MORE request
		$fpID = array();
		foreach( $data['featured_prod'] as $fp ){
			if( !in_array($fp['id_product'],$fpID) ){
				$fpID[] = $fp['id_product'];
			}
		}
		$data['fpID'] = json_encode($fpID);
		
		return $data;
	}
	
	public function getMoreFeeds()
	{
		if( $this->input->post("feed_page") && $this->input->post("feed_set") ){
			$perPage = $this->feeds_prodperpage;
			$memberId = $this->session->userdata('member_id');
			
			$page = $this->input->post("feed_page") * 10 - $perPage;
			$productFeedSet = $this->input->post("feed_set");
			
			switch( (int)$productFeedSet ){
				case 1: #Featured Tab
					$easyshopId = trim($this->xmlmap->getFilenameID('page/content_files','easyshop-member-id'));
					$partnersId = explode(',',trim($this->xmlmap->getFilenameID('page/content_files','partners-member-id')));
					array_push($partnersId, $easyshopId);
					$prodIdRaw = ($this->input->post('ids')) ? json_decode($this->input->post('ids')) : array(0); 
					$prodId = implode(",",$prodIdRaw);
					
					$products = $this->product_model->getProductFeed($memberId,$partnersId,$prodId,$perPage,$page);
					
					#Assemble featured product ID array for exclusion on LOAD MORE request
					$fpID = array();
					foreach( $products as $fp ){
						if( !in_array($fp['id_product'],$fpID) ){
							$fpID[] = $fp['id_product'];
						}
					}
					
					$prodIDArray = array_merge($prodIdRaw,$fpID);
					$data['fpID'] = json_encode($prodIDArray);
					
					break;
				case 2: #New Products Tab
					$products = $this->product_model->getNewProducts($perPage,$page);
					break;
			}
			
			$temp['products'] = $products;
			$data['view'] = $this->load->view("templates/home_layout/layoutF_products",$temp,true);
			
			echo json_encode($data);
		}
	}
    

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
