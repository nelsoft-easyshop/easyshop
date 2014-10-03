<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller 
{
 
    
    /**
     * Number of feeds item per page
     *
     * @var integer
     */
    public $feedsProdPerPage = 7;
    
    /**
     * Product count for vendor page
     *
     * @var integer
     */
    private $vendorProdPerPage = 12;

    /**
     * Number of feedbacks loaded for a page
     *
     * @var integer
     */
    public $feedbackPerPage = 15;
    
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
        $this->cartManager = $this->serviceContainer['cart_manager'];
        $this->cartImplementation = $this->cartManager->getCartObject();
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
     * Renders vendorpage
     *
     * @param string $tab
     * @return View
     */
    public function userprofile()
    {
        // Load Services
        $em = $this->serviceContainer["entity_manager"];
        $pm = $this->serviceContainer['product_manager'];
        $um = $this->serviceContainer['user_manager'];
        $searchProductService = $this->serviceContainer['search_product'];

        // Load Repository 
        $EsLocationLookupRepository = $em->getRepository('EasyShop\Entities\EsLocationLookup');

        $vendorSlug = $this->uri->segment(1);
        $session_data = $this->session->all_userdata();

        $memberEntity = $em->getRepository("EasyShop\Entities\EsMember")
                           ->findOneBy(['slug' => $vendorSlug]);

        // User found - valid slug
        if( !empty($memberEntity) ){
            $pageSection = $this->uri->segment(2);
            
            if($pageSection === 'about'){
                $this->aboutUser($vendorSlug);
            }
            else if($pageSection === 'contact'){
                $this->contactUser($vendorSlug);
            }
            else{
                $arrVendorDetails = $em->getRepository("EasyShop\Entities\EsMember")
                                       ->getVendorDetails($vendorSlug);

                $headerData = $this->fill_header();
                $headerData = array_merge($headerData, array(
                    "title" => html_escape( $arrVendorDetails['store_name'] ? $arrVendorDetails['store_name'] : $arrVendorDetails['username'])." | Easyshop.ph",
                    "my_id" => (empty($session_data['member_id']) ? 0 : $session_data['member_id']),
                ));

                $userProduct = $em->getRepository("EasyShop\Entities\EsProduct")->findBy(['member' => $arrVendorDetails['id_member'],
                                                                          'isDelete' => 0,'isDraft' => 0]);


                if (count($userProduct) <= 0) { 
                    redirect($vendorSlug.'/about'); 
                }

                $productView['defaultCatProd'] = [];
                if (count($userProduct) > 0) { 
                    $getUserProduct = $this->getUserDefaultCategoryProducts($arrVendorDetails['id_member']);
                    $productView['defaultCatProd'] = $getUserProduct['parentCategory'];
                }

                // If searching in page
                if($this->input->get() && count($userProduct) > 0){

                    $productView['isSearching'] = TRUE;
                    $parameter = $this->input->get();
                    $parameter['seller'] = "seller:".$vendorSlug;
                    $parameter['limit'] = 12;
                    
                    // getting all products
                    $searchProduct = $searchProductService->getProductBySearch($parameter);

                    $parameter['limit'] = PHP_INT_MAX;
                    $count = count($searchProductService->getProductBySearch($parameter));

                    $productView['defaultCatProd'][0]['name'] ='Search Result';
                    $productView['defaultCatProd'][0]['products'] = $searchProduct; 
                    $productView['defaultCatProd'][0]['non_categorized_count'] = $count;
                    $productView['defaultCatProd'][0]['json_subcat'] = "{}";
                    $productView['defaultCatProd'][0]['cat_type'] = EasyShop\Entities\EsCat::CUSTOM_TYPE_OTHERS;

                    $paginationData = array(
                        'lastPage' => ceil($count/$this->vendorProdPerPage)
                        ,'isHyperLink' => false
                    );
                    $productView['defaultCatProd'][0]['pagination'] = $this->load->view('pagination/default', $paginationData, true);

                    $view = array(
                        'arrCat' => array(
                            'products'=>$searchProduct,
                            'page' => 1
                        )
                    );
                    $productView['defaultCatProd'][0]['product_html_data'] = $this->load->view("pages/user/display_product", $view, true);
                }
                
                // Data for the view
                $data = array(
                    "arrVendorDetails" => $arrVendorDetails 
                    , "storeNameDisplay" => strlen($arrVendorDetails['store_name']) > 0 ? $arrVendorDetails['store_name'] : $arrVendorDetails['username']
                    , "defaultCatProd" => $productView['defaultCatProd']
                    , "hasAddress" => strlen($arrVendorDetails['stateregionname']) > 0 && strlen($arrVendorDetails['cityname']) > 0 ? TRUE : FALSE
                    , "product_condition" => $this->lang->line('product_condition')
                    , "avatarImage" => $um->getUserImage($arrVendorDetails['id_member'])
                    , "bannerImage" => $um->getUserImage($arrVendorDetails['id_member'],"banner")
                    , "isEditable" => ($this->session->userdata('member_id') && $arrVendorDetails['id_member'] == $this->session->userdata('member_id')) ? TRUE : FALSE
                    , "noItem" => (count($userProduct) > 0) ? TRUE : FALSE
                    , "subscriptionStatus" => $um->getVendorSubscriptionStatus($headerData['my_id'], $arrVendorDetails['username'])
                    , "isLoggedIn" => $headerData['logged_in'] ? TRUE : FALSE
                    , "prodLimit" => $this->vendorProdPerPage
                    , "vendorLink" => ""
                );

                //Determine active Div for first load
                $showFirstDiv = TRUE;
                foreach($data['defaultCatProd'] as $catId => $catDetails){
                    if( isset($productView['isSearching']) ){
                        $data['defaultCatProd'][$catId]['isActive'] = (int)$catId === 0 ? TRUE : FALSE;
                    }
                    else{
                        $data['defaultCatProd'][$catId]['isActive'] = $showFirstDiv;
                        $showFirstDiv = FALSE;
                    }
                }

                // Load Location
                $data = array_merge($data, $EsLocationLookupRepository->getLocationLookup());
                $cart = array();
                $cartSize = 0;
                if ($this->session->userdata('usersession')) {
                    $memberId = $this->session->userdata('member_id');
                    $cart = array_values($this->cartManager->getValidatedCartContents($memberId));
                    $cartSize = $this->cartImplementation->getSize(TRUE);
                }
                $headerData['cart_items'] = $cart;
                $headerData['cart_size'] = $cartSize;
                $headerData['total'] = $headerData['cart_size'] ? $this->cartImplementation->getTotalPrice() : 0;

                // Load View
                $this->load->view('templates/header_new', $headerData);
                $this->load->view('templates/header_vendor',$data);
                $this->load->view('pages/user/vendor_view', $data);
                $this->load->view('templates/footer_vendor', ['sellerSlug' => $vendorSlug]);
            }
        }
        // Load invalid link error page
        else{
            $this->pagenotfound();
        }

    }
    
    /**
     *  Fetch Default categories and initial products for first load of page.
     *
     *  @return array
     */
    private function getUserDefaultCategoryProducts($memberId)
    {
        $em = $this->serviceContainer['entity_manager'];
        $pm = $this->serviceContainer['product_manager'];
        $prodLimit = $this->vendorProdPerPage;

        $parentCat = $pm->getAllUserProductParentCategory($memberId);

        $categoryProducts = array();
        $totalProductCount = 0; 

        foreach( $parentCat as $idCat=>$categoryProperties ){ 
            $result = $pm->getVendorDefaultCategoryAndProducts($memberId, $categoryProperties['child_cat']);
            $parentCat[$idCat]['products'] = $result['products'];
            $parentCat[$idCat]['non_categorized_count'] = $result['filtered_product_count']; 
            $totalProductCount += count($result['products']);
            $parentCat[$idCat]['json_subcat'] = json_encode($categoryProperties['child_cat'], JSON_FORCE_OBJECT);

            // Generate pagination view
            $paginationData = array(
                'lastPage' => ceil($result['filtered_product_count']/$this->vendorProdPerPage)
                ,'isHyperLink' => false
            );
            $parentCat[$idCat]['pagination'] = $this->load->view('pagination/default', $paginationData, true);

            $view = array(
                'arrCat' => array(
                    'products'=>$result['products'],
                    'page' => 1
                )
            );

            $parentCat[$idCat]['product_html_data'] = $this->load->view("pages/user/display_product", $view, true);
        }

        $returnData['totalProductCount'] = $totalProductCount;
        $returnData['parentCategory'] = $parentCat;

        return $returnData;
    }


       
    /**
     * Renders the user about page
     *
     * @param string $sellerslug
     */
    private function aboutUser($sellerslug)
    {
        $userDetails = $this->doUpdateUserDetails($sellerslug,'about');
        
        $limit = $this->feedbackPerPage;
        $this->lang->load('resources');

        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->findOneBy(['slug' => $sellerslug]);                                
        $data['title'] = 'About '.html_escape($member->getStoreName() ? $member->getStoreName() : $member->getUsername()).' | Easyshop.ph';
        $data = array_merge($data, $this->fill_header());   
        $idMember = $member->getIdMember();
        $memberUsername = $member->getUsername();
        $ratingHeaders = $this->lang->line('rating');

        $allFeedbacks = $this->serviceContainer['user_manager']->getFormattedFeedbacks($idMember);
        $feedbackSummary = array(
                                    'rating1' => $allFeedbacks['rating1Summary'],
                                    'rating2' => $allFeedbacks['rating2Summary'],
                                    'rating3' => $allFeedbacks['rating3Summary'],
                                   );
                      
        $feedbacks  = $this->serviceContainer['user_manager']
                         ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_AS_BUYER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['otherspost_buyer'])/$limit),
                                                                   'isHyperLink' => false), TRUE);

        $feedbackTabs['asBuyer'] = $this->load->view('/partials/feedback', array(
                                                                            'isActive' => true,
                                                                            'feedbacks' => $feedbacks,
                                                                            'pagination' => $pagination,
                                                                            'id' => 'as-buyer',
                                                                            'ratingHeaders' => $ratingHeaders,
                                                                            ), TRUE);                                                          
                                          
        $feedbacks  = $this->serviceContainer['user_manager']
                         ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_AS_SELLER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['otherspost_seller'])/$limit),
                                                                   'isHyperLink' => false), TRUE);

        $feedbackTabs['asSeller'] = $this->load->view('/partials/feedback', array('isActive' => false,
                                                                            'feedbacks' => $feedbacks,
                                                                            'pagination' => $pagination,
                                                                            'id' => 'as-seller',
                                                                            'ratingHeaders' => $ratingHeaders,
                                                                            ), TRUE);

        $feedbacks  = $this->serviceContainer['user_manager']
                         ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['youpost_seller'])/$limit),
                                                                   'isHyperLink' => false), TRUE);

        $feedbackTabs['forOthersAsSeller'] = $this->load->view('/partials/feedback', array(
                                                                            'isActive' => false,
                                                                            'feedbacks' => $feedbacks,
                                                                            'pagination' => $pagination,
                                                                            'id' => 'for-other-seller',
                                                                            'ratingHeaders' => $ratingHeaders,
                                                                            ), TRUE);                                                          
                                                                
        $feedbacks  = $this->serviceContainer['user_manager']
                         ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['youpost_buyer'])/$limit),
                                                                   'isHyperLink' => false), TRUE);
        $feedbackTabs['forOthersAsBuyer'] = $this->load->view('/partials/feedback', array(
                                                                              'isActive' => false,
                                                                              'feedbacks' => $feedbacks,
                                                                              'pagination' => $pagination,
                                                                              'id' => 'for-other-buyer',
                                                                              'ratingHeaders' => $ratingHeaders,
                                                                              ), TRUE);

        $viewerId = $this->session->userdata('member_id');
        $orderRelations = array();
        if($viewerId){
            $orderRelations = $this->serviceContainer['entity_manager']
                                   ->getRepository('EasyShop\Entities\EsOrder')
                                   ->getOrderRelations($viewerId, $idMember, true);
        }
        $isEditable = $viewerId && $member->getIdMember() === intval($viewerId);

        // assign header_vendor data  
        $EsLocationLookupRepository = $this->serviceContainer['entity_manager']
                                           ->getRepository('EasyShop\Entities\EsLocationLookup');
        $arrVendorDetails = $this->serviceContainer['entity_manager']
                                 ->getRepository("EasyShop\Entities\EsMember")
                                 ->getVendorDetails($sellerslug);
        $getUserProduct = $this->getUserDefaultCategoryProducts($member->getIdMember());
     
        $headerVendorData = array(
                    "arrVendorDetails" => $arrVendorDetails 
                    , "storeNameDisplay" => strlen($member->getStoreName()) > 0 ? $member->getStoreName() : $memberUsername
                    , "hasAddress" => strlen($arrVendorDetails['stateregionname']) > 0 && strlen($arrVendorDetails['cityname']) > 0 ? TRUE : FALSE 
                    , "avatarImage" => $this->serviceContainer['user_manager']->getUserImage($member->getIdMember())
                    , "bannerImage" => $this->serviceContainer['user_manager']->getUserImage($member->getIdMember(),"banner")
                    , "isEditable" => ($this->session->userdata('member_id') && $member->getIdMember() == $this->session->userdata('member_id')) ? TRUE : FALSE
                    , "noItem" => ($getUserProduct['totalProductCount'] > 0) ? TRUE : FALSE
                    , "subscriptionStatus" => $this->serviceContainer['user_manager']->getVendorSubscriptionStatus($viewerId, $memberUsername)
                    , "isLoggedIn" => $data['logged_in'] ? TRUE : FALSE
                    , "vendorLink" => "about"
                ); 

        $headerVendorData = array_merge($headerVendorData, $EsLocationLookupRepository->getLocationLookup());
        $cart = array();
        $cartSize = 0;
        if ($this->session->userdata('usersession')) {
            $memberId = $this->session->userdata('member_id');
            $cart = array_values($this->cartManager->getValidatedCartContents($memberId));
            $cartSize = $this->cartImplementation->getSize(TRUE);
        }
        $data['cart_items'] = $cart;
        $data['cart_size'] = $cartSize;
        $data['total'] = $data['cart_size'] ? $this->cartImplementation->getTotalPrice() : 0;

        $this->load->view('templates/header_new', $data);
        $this->load->view('templates/header_vendor',$headerVendorData);
        $this->load->view('pages/user/about', ['feedbackSummary' => $feedbackSummary,
                                               'ratingHeaders' => $ratingHeaders,
                                               'feedbackTabs' => $feedbackTabs,
                                               'member' => $member,
                                               'viewer' => $data['user'],
                                               'orderRelations' => $orderRelations,
                                               'isEditable' =>  $isEditable,
                                               'userDetails' => $userDetails,
                                              ]);
        $this->load->view('templates/footer_vendor', ['sellerSlug' => $sellerslug]);
    }
    
    
    /**
     * Updated store description
     *
     */
    public function doUpdateDescription()
    {
        $em = $this->serviceContainer['entity_manager'];
        $rules = $this->serviceContainer['form_validation']->getRules('personal_info')['storeDescription'];
        $maxLength = $rules[0]->max;
        
        $description = $this->input->post('description');
        $description = trim($description, 0, $maxLength);
        $userId = intval($this->input->post('userId'));
        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find($userId);
        if($member && ($member->getIdMember() === intval($this->session->userdata('member_id')))){
            $member->setStoreDesc($description);
            $member->setLastmodifieddate(new DateTime('now'));                        
            $em->flush();
        }
        redirect('/'.$member->getSlug().'/about');

    }
    
    /**
     * Creates a feedback
     *
     */
    public function doCreateFeedback()
    {
        $em = $this->serviceContainer['entity_manager'];
        $reviewer = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find(intval($this->session->userdata('member_id')));
        $reviewee = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find(intval($this->input->post('userId')));
        $orderToReview = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsOrder')
                                                           ->find(intval($this->input->post('feeback-order')));   
             
        $message = $this->input->post('feedback-message');
                
        if($reviewer && $reviewee && $orderToReview && strlen($message) > 0){
            if($reviewer->getIdMember() === $orderToReview->getBuyer()->getIdMember()){
                $feedbackType = EasyShop\Entities\EsMemberFeedback::REVIEWER_AS_BUYER;
            }
            else if($reviewee->getIdMember() === $orderToReview->getBuyer()->getIdMember()){
                $feedbackType = EasyShop\Entities\EsMemberFeedback::REVIEWER_AS_SELLER;
            }
            else{
                return false;
            }
            $feedback = new EasyShop\Entities\EsMemberFeedback();
            $feedback->setMember($reviewer);
            $feedback->setForMemberid($reviewee);
            $feedback->setOrder($orderToReview);
            $feedback->setFeedbMsg($message);
            $feedback->setDateadded(new DateTime('now'));
            $feedback->setRating1(intval($this->input->post('rating1')));
            $feedback->setRating2(intval($this->input->post('rating2')));
            $feedback->setRating3(intval($this->input->post('rating3')));
            $feedback->setFeedbKind($feedbackType);
            $em->persist($feedback);
            $em->flush();
        }
        redirect('/'.$reviewee->getSlug().'/about');
    }
    

    /**
     * Returns more feedback JSON
     *
     * @return JSON
     */
    public function feedback()
    {
        $page = intval($this->input->get('page'));
        $memberId = intval($this->input->get('memberid'));
        $tab = $this->input->get('tab');
        $limit = $this->feedbackPerPage;
        $ratingHeaders = $this->lang->line('rating');

        switch($tab){
            case 'as-buyer':
                $feedbackType =  EasyShop\Entities\EsMemberFeedback::TYPE_AS_BUYER;
                break;
            case 'as-seller':
                $feedbackType =  EasyShop\Entities\EsMemberFeedback::TYPE_AS_SELLER;
                break;
            case 'for-other-buyer':
                $feedbackType =  EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER;
                break;
            case 'for-other-seller':
                $feedbackType =  EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER;
                break;
            default:
                $feedbackType = null;
                break;
        }
        
        $feedbacks = array();
        $pagination = null;
        if($feedbackType !== null){
            $feedbacks = $this->serviceContainer['user_manager']
                             ->getFormattedFeedbacks($memberId, $feedbackType, $limit, $page);
            $totalCount = count($this->serviceContainer['user_manager']
                             ->getFormattedFeedbacks($memberId, $feedbackType));
            $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil($totalCount/$limit),
                                                                         'isHyperLink' => false,
                                                                         'currentPage' => $page,
                                                                        ), TRUE);
        }
        
        $feedbackTabs = $this->load->view('/partials/feedback', array('isActive' => true,
                                                                    'feedbacks' => $feedbacks,
                                                                    'pagination' => $pagination,
                                                                    'id' => $tab,
                                                                    'ratingHeaders' => $ratingHeaders,
                                                                    ), TRUE); 
        
        echo $feedbackTabs;
    }

    /**
     * Renders the user contact page
     *
     */
    private function contactUser($sellerslug)
    {
       
        $data = $this->fill_header();
        $cart = array();
        $cartSize = 0;
        if ($this->session->userdata('usersession')) {
            $memberId = $this->session->userdata('member_id');
            $cart = array_values($this->cartManager->getValidatedCartContents($memberId));
            $cartSize = $this->cartImplementation->getSize(TRUE);
        }
        $data['cart_items'] = $cart;
        $data['cart_size'] = $cartSize;
        $data['total'] = $data['cart_size'] ? $this->cartImplementation->getTotalPrice() : 0;

        // assign header_vendor data
        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                   ->findOneBy(['slug' => $sellerslug]);
        $data['title'] = 'Contact '.html_escape($member->getStoreName() ? $member->getStoreName() : $member->getUsername()).' | Easyshop.ph';                                           
                                         
        $memberUsername = $member->getUsername();
        $memberId = $member->getIdMember();
        $viewerId = $this->session->userdata('member_id');
        $EsLocationLookupRepository = $this->serviceContainer['entity_manager']
                                           ->getRepository('EasyShop\Entities\EsLocationLookup');
        $arrVendorDetails = $this->serviceContainer['entity_manager']
                                 ->getRepository("EasyShop\Entities\EsMember")
                                 ->getVendorDetails($sellerslug);
        $getUserProduct = $this->getUserDefaultCategoryProducts($member->getIdMember());

        $headerVendorData = array(
                    "arrVendorDetails" => $arrVendorDetails 
                    , "storeNameDisplay" => strlen($member->getStoreName()) > 0 ? $member->getStoreName() : $memberUsername
                    , "hasAddress" => strlen($arrVendorDetails['stateregionname']) > 0 && strlen($arrVendorDetails['cityname']) > 0 ? TRUE : FALSE 
                    , "avatarImage" => $this->serviceContainer['user_manager']->getUserImage($memberId)
                    , "bannerImage" => $this->serviceContainer['user_manager']->getUserImage($memberId,"banner")
                    , "isEditable" => ($viewerId && $memberId == $viewerId) ? TRUE : FALSE
                    , "noItem" => ($getUserProduct['totalProductCount'] > 0) ? TRUE : FALSE
                    , "subscriptionStatus" => $this->serviceContainer['user_manager']->getVendorSubscriptionStatus($viewerId, $memberUsername)
                    , "isLoggedIn" => $data['logged_in'] ? TRUE : FALSE
                    , "vendorLink" => "contact"
                ); 

        $headerVendorData = array_merge($headerVendorData, $EsLocationLookupRepository->getLocationLookup());
        $userDetails = $this->doUpdateUserDetails($sellerslug,'contact');
        $data['title'] = 'Contact '.html_escape($member->getStoreName()).'| Easyshop.ph';
        $data['message_recipient'] = $member;
        $data = array_merge($data, $this->fill_header());

        $this->load->view('templates/header_new', $data);
        $this->load->view('templates/header_vendor',$headerVendorData);
        $this->load->view('pages/user/contact', ['userDetails' => $userDetails]);
        $this->load->view('templates/footer_vendor', ['sellerSlug' => $sellerslug]);
    }


    /**
     *  NOT YET USED !!!
     *  Fetch custom categories and initial products for first load of page.
     *
     *  @return array
     */
    private function getVendorCustomCatAndProd($memberId)
    {
        $em = $this->serviceContainer['entity_manager'];
        $prodLimit = $this->vendorProdPerPage;

        $customCat = $em->getRepository("EasyShop\Entities\EsMemberCat")
                        ->getCustomCategoriesArray($memberId);

        foreach( $customCat as $category ){
            $result[$category["id_memcat"]] = array(
                "name" => $category["cat_name"],
                "is_featured" => $category["is_featured"],
                "products" => $em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                ->getCustomCategoryProduct($memberId, $category["id_memcat"], $prodLimit)
            );
        }

        return $result;
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
    
    /**
     *  Handles bug report form
     *
     */
    public function bugReport()
    {
        $isValid = false;
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $request = $this->serviceContainer['http_request'];
        $twig = $this->serviceContainer['twig'];

        $rules = $formValidation->getRules('bug_report');

        $form = $formFactory->createBuilder()
        //->setAction('target_route')
        ->setMethod('POST')
        ->add('title', 'text', array('required' => false, 'label' => false, 'constraints' => $rules['title']))
        ->add('description', 'textarea', array('required' => false, 'label' => false, 'constraints' => $rules['description']))
        ->add('file', 'file', array('label' => false, 'required' => false, 'constraints' => $rules['image']))
        ->add('submit', 'submit', array('label' => 'SEND'))
        ->getForm();

        $emptyForm = clone $form;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $bugReporter = $this->serviceContainer['bug_reporter'];
            $bugReporter->createReport($form->getData());
            $isValid = true;
            $form = $emptyForm;
        }
        
        $formData =  $twig->render('pages/web/report-a-problem.html.twig', array(
            'form' => $form->createView(), 
            'ES_FILE_VERSION' => ES_FILE_VERSION,
            'isValid' => $isValid
            ));

        $data = array(
            'title' => 'Report a Problem | Easyshop.ph',
            'metadescription' => 'Found a bug? Let us know so we can work on it.',
        );

        $data = array_merge($data, $this->fill_header()); 
        $this->load->view('templates/header', $data);
        $this->output->append_output($formData); 
        $this->load->view('templates/footer_full');
    }

    /**
     *  Handles Vendor Contact Detail View
     *
     *  @param string $sellerslug
     *
     */
    public function doUpdateUserDetails($sellerslug, $targetPage)
    {
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $rules = $formValidation->getRules('vendor_contact');
        $data['isValid'] = false;
        $data['targetPage'] = $targetPage;
        $data['errors'] = [];

        $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                        ->setMethod('POST')
                        ->add('shop_name', 'text', array('constraints' => $rules['shop_name']))
                        ->add('contact_number', 'text', array('constraints' => $rules['contact_number']))
                        ->add('street_address', 'text')
                        ->add('city', 'text')
                        ->add('region', 'text')
                        ->add('website', 'text')
                        ->getForm();

        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                               ->findOneBy(['slug' => $sellerslug]);

        $data['validatedStoreName'] = $data['storeName'] = $member->getStoreName();
        $data['validatedContactNo'] = $data['contactNo'] = $member->getContactno() === "" ? '' : '0' . $member->getContactno();
        $data['validatedWebsite'] = $data['website'] = $member->getWebsite();
        $data['isEditable'] = intval($this->session->userdata('member_id')) === $member->getIdMember() ? true : false;

        $addr = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsAddress')
                            ->findOneBy(['idMember' => $member->getIdMember(), 'type' => '0']);

        if($addr === NULL){
            $data['cities'] = '';
            $data['validatedStreetAddr'] = $data['streetAddr'] = '';
            $data['validatedCity'] = $data['city'] = '';
            $data['validatedRegion'] = $data['region'] = '';
        }
        else{
            $data['cities'] = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                ->getCities($addr->getStateregion()->getLocation());
            $data['validatedStreetAddr'] = $data['streetAddr'] = strlen(trim($addr->getAddress())) > 0 ? $addr->getAddress() . ", " : "";
            $data['validatedCity'] = $data['city'] = $addr->getCity()->getLocation(). ", ";
            $data['validatedRegion'] = $data['region'] = $addr->getStateregion()->getLocation();
        }

        if($this->input->post('storeName') !== false || $this->input->post('contactNumber') !== false || $this->input->post('streetAddress') !== false || 
            $this->input->post('website') !== false || $this->input->post('citySelect') !== false || $this->input->post('regionSelect') !== false){

            $form->submit([ 
              'shop_name' => $this->input->post('storeName'),
              'contact_number' => $this->input->post('contactNumber'),
              'street_address' => $this->input->post('streetAddress'),
              'city' => $this->input->post('citySelect'),
              'region' => $this->input->post('regionSelect'),
              'website' => $this->input->post('website')
            ]);

            $isAddressValid = (($this->input->post('regionSelect') !== '' && $this->input->post('citySelect') !== '' && $this->input->post('streetAddress') !== '') 
                                || ($this->input->post('regionSelect') === '' && $this->input->post('streetAddress') === ''));

            if($form->isValid() && $isAddressValid){
                $formData = $form->getData();

                $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(['slug' => $sellerslug]);

                $member->setStoreName($formData['shop_name']);
                $member->setContactno(substr($formData['contact_number'], 1));
                $member->setWebsite($formData['website']);

                $addr = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsAddress')
                            ->findOneBy(['idMember' => $member->getIdMember(), 'type' => '0']);

                if($addr === null){
                    if($formData['city'] !== null || $formData['region'] !== null){
                        $country = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->find(1);

                        $city = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                    ->findOneBy(['location' => $formData['city']]);

                        $region = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                    ->findOneBy(['location' => $formData['region']]);

                        $addr = new EasyShop\Entities\EsAddress();
                        $addr->setAddress($formData['street_address']);
                        $addr->setCity($city);
                        $addr->setStateregion($region);
                        $addr->setCountry($country);
                        $addr->setIdMember($member);
                        $addr->setMobile($member->getContactno());
                        $addr->setType(EasyShop\Entities\EsAddress::TYPE_DEFAULT);

                        $this->serviceContainer['entity_manager']->persist($addr);

                        $data['validatedStreetAddr'] = strlen(trim($addr->getAddress())) > 0 ? $addr->getAddress() . ", " : "";
                        $data['validatedCity'] = $city->getLocation(). ", ";
                        $data['validatedRegion'] = $region->getLocation();
                    }
                }
                else{
                    if($formData['city'] !== null || $formData['region'] !== null){
                        $city = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                    ->findOneBy(['location' => $formData['city']]);

                        $region = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                    ->findOneBy(['location' => $formData['region']]);


                        $addr->setAddress($formData['street_address']);
                        $addr->setCity($city);
                        $addr->setStateregion($region);
                        $addr->setMobile($member->getContactno());

                        $data['validatedStreetAddr'] = strlen(trim($addr->getAddress())) > 0 ? $addr->getAddress() . ", " : "";
                        $data['validatedCity'] = $addr->getCity()->getLocation(). ", ";
                        $data['validatedRegion'] = $addr->getStateregion()->getLocation();
                    }
                    else{
                        $this->serviceContainer['entity_manager']->remove($addr);

                        $data['validatedStreetAddr'] = '';
                        $data['validatedCity'] = '';
                        $data['validatedRegion'] = '';
                    }
                }
                $this->serviceContainer['entity_manager']->flush();
                $data['isValid'] = true;

                $data['validatedStoreName'] = $member->getStoreName();
                $data['validatedContactNo'] = $member->getContactno() === false ? "" : '0' . $member->getContactno();
                $data['validatedWebsite'] = $member->getWebsite();
            }
            else{
                $data['errors'] =  $this->serviceContainer['form_error_helper']->getFormErrors($form);
                if(!$isAddressValid){
                    $data['errors']['location'] = ["address must be complete"];
                }
            }
            $data['storeName'] = $this->input->post('storeName');
            $data['contactNo'] = $this->input->post('contactNumber');
            $data['streetAddr'] = strlen(trim($this->input->post('streetAddress'))) > 0 ? $this->input->post('streetAddress') . ", " : "";
            $data['region'] = $this->input->post('regionSelect');
            $data['city'] = $this->input->post('citySelect') == '' ? '' : $this->input->post('citySelect') . ", ";
            $data['website'] = $this->input->post('website');

            if(array_key_exists('shop_name', $data['errors'])){
                $data['storeName'] = '';
            }
            if(array_key_exists('contact_number', $data['errors'])){
                $data['contactNo'] = '';
            }
            if(array_key_exists('website', $data['errors'])){
                $data['website'] = '';
            }

            if($data['region'] !== ''){
                $data['cities'] = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                            ->getCities($data['region']);
            }
            else{
                $data['cities'];
            }
        }

        $data['regions'] = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                ->getAllLocationType(3);

        $data['cityList'] = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup')
                                ->getAllLocationType(3,true);

        return $this->load->view('/partials/userdetails', array_merge($data,['member'=>$member]), TRUE);
    }    

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */

