<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller 
{

    public $feedsProdPerPage = 7;

    
    /**
     * Load class dependencies
     *
     */
    function __construct() 
    {
        parent::__construct();
        $this->load->library('xmlmap');
        $this->load->model('product_model');
        $this->load->model('user_model');
    }

    /**
     * Renders home page if not logged in, otherwise render feed page
     *
     * @return View
     */
    public function index() 
    {
            
        $xmlResourceService = $this->serviceContainer['xml_resource'];
        $home_content = $this->product_model->getHomeContent($xmlResourceService->getHomeXMLfile());

        $layout_arr = array();
        if(!$this->session->userdata('member_id')){
            foreach($home_content['section'] as $section){
                array_push($layout_arr,$this->load->view('templates/home_layout/'.$section['category_detail']['layout'], array('section' => $section), TRUE));
            }
        }

        $data = array(
            'title' => ' Shopping made easy | Easyshop.ph',
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
            $this->load->view('templates/footer', array('minborder' => true));
        }
        else{
            $this->load->view('pages/home_view', $data);
            $this->load->view('templates/footer_full');
        }
    }
    
    
    /**
     * Renders home page if not logged in, otherwise render feed page
     *
     * @return View
     */
    public function under_construction()
    {
      $data = array('title' => 'Under Construction | Easyshop.ph',);
      $data = array_merge($data, $this->fill_header());
      $this->load->view('templates/header', $data);
      $this->load->view('pages/underconstruction_view');
      $this->load->view('templates/footer_full');
    }
    
    /**
     * Renders 404 page
     *
     * @return View
     */
    public function pagenotfound()
    {
        $this->output->set_status_header('404'); 
        $page = $_SERVER['REQUEST_URI'];
        log_message('error', '404 Page Not Found --> '.$page);
        $data = array('title' => 'Page Not Found | Easyshop.ph',);
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/general_error');
        $this->load->view('templates/footer_full');
    }
    
    
    /**
     * Renders splash page
     *
     * @return View
     */
    public function splash()
    {
        $this->load->view('pages/undermaintenance.php');
    }

    /** 
     * Returns server time in Month Day, Year 24Hour:Min:Sec format
     * Timezone is set to Asia/Manila
     *
     * @return String
     */
    public function getServerTime()
    {
        date_default_timezone_set('Asia/Manila');
        echo date('M d,Y H:i:s');
    }
    
    /**
     * Renders privacy policy page
     *
     * @return View
     */
    public function policy()
    {
        $data = array(
            'title' => 'Privacy Policy | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Privacy Policy",
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/web/policy');
        $this->load->view('templates/footer_full');
    }
    
    
    /**
     * Renders terms and conditions page
     *
     * @return View
     */
    public function terms()
    {
        $data = array(
            'title' => 'Terms and Conditions | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Terms and Conditions",
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/web/terms');
        $this->load->view('templates/footer_full');
    }
    
    
    /**
     * Renders FAQ page
     *
     * @return View
     */
    public function faq()
    {
        $data = array(
            'title' => 'F.A.Q. | Easyshop.ph',
            'metadescription' => 'Get in the know, read the Frequently Asked Questions at Easyshop.ph',
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/web/faq');
        $this->load->view('templates/footer_full');
    }
    
    
    /**
     * Renders contact-us page
     *
     * @return View
     */
    public function contact()
    {
        $data = array(
            'title' => 'Contact us | Easyshop.ph',
            'metadescription' => 'Get in touch with our Customer Support',
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/web/contact');
        $this->load->view('templates/footer_full');
    }
    
    
      
    /**
     * Renders how-to-buy infographic
     *
     * @return View
     */
    public function guide_buy()
    {
        $data = array(
            'title' => 'How to buy | Easyshop.ph',
            'metadescription' => 'Learn how to purchase at Easyshop.ph',
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/web/how-to-buy');
    }
    
    
    /**
     * Renders how-to-sell infographic
     *
     * @return View
     */
    public function guide_sell()
    {
        $data = array(
            'title' => 'How to sell | Easyshop.ph',
            'metadescription' => 'Learn how to sell your items at Easyshop.ph',
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        $this->load->view('pages/web/how-to-sell');
    }
    
    /**
     * Renders memberpage
     *
     * @param string $tab
     * @return View
     */
    public function userprofile()
    {
        $this->load->model('memberpage_model');

        $sellerslug = $this->uri->segment(1);
        $tab = $this->input->get('tab') ? $this->input->get('tab') : '';
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
            $usersFollowing = $this->user_model->getFollowing($sellerid);
            $usersFollower = $this->user_model->getFollowers($sellerid);
            $user_product_count = $this->memberpage_model->getUserItemCount($sellerid);
            $data = array_merge($data,array(
                    'vendordetails' => $vendordetails,
                    'image_profile' => $this->memberpage_model->get_Image($sellerid),
                    'banner' => $this->memberpage_model->get_Image($sellerid,'vendor'),
                    'products' => $this->memberpage_model->getVendorCatItems($sellerid,$vendordetails['username']),
                    'active_count' => intval($user_product_count['active']),
                    'deleted_count' => intval($user_product_count['deleted']),
                    'sold_count' => intval($user_product_count['sold']),
                    'followers' =>  $usersFollower,
                    'following' =>  $usersFollowing,
                    'tab' => $tab,
                    ));
            $data['allfeedbacks'] = $this->memberpage_model->getFeedback($sellerid);

            $data['hasStoreDesc'] = (string)$data['vendordetails']['store_desc'] !== '' ? true : false;
            $data['product_count'] = count($data['products']);
            $data['renderEdit'] = (int)$sellerid === (int)$data['my_id'] ? true : false;
            #if 0 : no entry - unfollowed, hence display follow
            #if 1 : has entry - followed, hence display unfollow
            $data['subscribe_status'] = $this->memberpage_model->checkVendorSubscription($data['my_id'],$vendordetails['username'])['stat'];   
            $this->load->view('pages/user/vendor_view', $data);
            $this->load->view('templates/footer');
        }
        else{
            $this->pagenotfound();
        }
    }

    /**
     *  Fetch information to be display in feeds page
     *
     *  @return array
     */
    public function getFeed()
    {
        $xmlResourceService = $this->serviceContainer['xml_resource'];
        $xmlfile =  $xmlResourceService->getContentXMLfile();
    
        $perPage = $this->feedsProdPerPage;
        $memberId = $this->session->userdata('member_id');
        $userdata = $this->user_model->getUserById($memberId);

        $easyshopId = trim($this->xmlmap->getFilenameID($xmlfile,'easyshop-member-id'));
        $partnersId = explode(',',trim($this->xmlmap->getFilenameID($xmlfile,'partners-member-id')));
        
        array_push($partnersId, $easyshopId);
        $prodId = ($this->input->post('ids')) ? $this->input->post('ids') : 0; 
        $followedSellers = $this->user_model->getFollowing($memberId);
        
        $this->load->config('protected_category', TRUE);
        $categoryId = $this->config->item('promo', 'protected_category');

        $data = array(
            'featured_prod' => $this->product_model->getFeaturedProductFeed($memberId,$partnersId,$prodId,$perPage),
            'new_prod' => $this->product_model->getNewProducts($perPage),
            'easytreats_prod' => $this->product_model->getProductsByCategory($categoryId,array(),0,"<",0,$perPage, " lastmodifieddate DESC , "),
            'followed_users' =>  $followedSellers,
            'banners' => $this->product_model->getStaticBannerFeed($xmlfile),
            'promo_items' => $this->product_model->getStaticProductFeed('promo', $xmlfile),
            'popular_items' => $this->product_model->getStaticProductFeed('popular', $xmlfile),
            'featured_product' => $this->product_model->getStaticProductFeed('featured', $xmlfile),
            'isCollapseCategories' => count($followedSellers) > 2,
            'userslug' => $userdata['slug'],
            'maxDisplayableSellers' => 7
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
    
    /**
     *  Used by AJAX Requests to fetch for products in Feeds page
     *
     *  @return JSON
     */
    public function getMoreFeeds()
    {
        if( $this->input->post("feed_page") && $this->input->post("feed_set") ){
            $perPage = $this->feedsProdPerPage;
            $memberId = $this->session->userdata('member_id');
            
            $page = ((int)$this->input->post("feed_page") + 1) * $perPage - $perPage;
            $productFeedSet = (int)$this->input->post("feed_set");

            switch( (int)$productFeedSet ){
                case 1: #Featured Tab

                    $xmlResourceService = $this->serviceContainer['xml_resource'];
                    $xmlfile =  $xmlResourceService->getContentXMLfile();

                    $easyshopId = trim($this->xmlmap->getFilenameID($xmlfile,'easyshop-member-id'));
                    $partnersId = explode(',',trim($this->xmlmap->getFilenameID($xmlfile,'partners-member-id')));

                    array_push($partnersId, $easyshopId);
                    $prodIdRaw = ($this->input->post('ids')) ? json_decode($this->input->post('ids')) : array(0); 
                    $prodId = implode(",",$prodIdRaw);
                    
                    $products = $this->product_model->getFeaturedProductFeed($memberId,$partnersId,$prodId,$perPage,$page);
                    
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
                case 3: #EasyTreats Products Tab
                    $this->load->config('protected_category', TRUE);
                    $categoryId = $this->config->item('promo', 'protected_category');
                    $products = $this->product_model->getProductsByCategory($categoryId,array(),0,"<",$page,$perPage);
                    break;
                default:
                    $data['error'] = "Unable to load prouct list.";
                    echo json_encode($data);
                    exit();
                    break;
            }
            
            $temp['products'] = $products;
            $data['view'] = $this->load->view("templates/home_layout/layoutF_products",$temp,true);
            
            echo json_encode($data);
        }
    }
    
    public function bugReport()
    {
        $data = array(
            'title' => 'Report a Problem | Easyshop.ph',
            'metadescription' => 'Found a bug? Let us know so we can work on it.',
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data);
        //LOAD YOUR VIEW HERE
        //$this->load->view('pages/web/faq');
        $this->load->view('templates/footer_full');
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
