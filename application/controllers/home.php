<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller 
{

    /**
     *  Product count for feeds page.
     */
    public $feedsProdPerPage = 7;
    
    /**
     *  Product count for vendor page.
     */
    private $vendorProdPerPage = 12;

    
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
        // Load Services
        $em = $this->serviceContainer["entity_manager"];
        $pm = $this->serviceContainer['product_manager'];
        $um = $this->serviceContainer['user_manager'];
        $searchProductService = $this->serviceContainer['search_product'];

        // Load Repository 
        $EsLocationLookupRepository = $em->getRepository('EasyShop\Entities\EsLocationLookup');

        $vendorSlug = $this->uri->segment(1);
        $session_data = $this->session->all_userdata();

        $arrVendorDetails = $em->getRepository("EasyShop\Entities\EsMember")
                            ->getVendorDetails($vendorSlug);

        // User found - valid slug
        if( !empty($arrVendorDetails) ){ 

            $headerData = $this->fill_header();
            $headerData = array_merge($headerData, array(
                "title" => "Vendor Profile | Easyshop.ph",
                "my_id" => (empty($session_data['member_id']) ? 0 : $session_data['member_id']),
                "render_logo" => false,
                "render_searchbar" => false
            ));

            // Load Product By Category -- Refractor soon..
            $collectionCategoryProduct = $this->getVendorDefaultCatAndProd($arrVendorDetails['id_member']); 

            $productView['defaultCatProd'] = $collectionCategoryProduct['parentCat'];
            $productObjCollection = $collectionCategoryProduct['productObjCollection'];

            $productView['productAttribute'] = $searchProductService->getProductAttributesByProductIds( $productObjCollection);
 
            // If searching in page
            if(count($_GET)>0){
                $productView['defaultCatProd'][0]['name'] ='Search Result';
                $productView['defaultCatProd'][0]['non_categorized_count'] = 3;
                $productView['defaultCatProd'][0]['json_subcat'] = "{}";

                $productView['isSearching'] = TRUE;
                $parameter = $this->input->get();
                $parameter['seller'] = "seller:".$vendorSlug;
                 // getting all products
                $searchProduct = $searchProductService->getProductBySearch($parameter);
                $productView['defaultCatProd'][0]['products'] = $searchProduct;
                // get all attributes to by products
                $productView['productAttribute'] = $searchProductService->getProductAttributesByProductIds( $searchProduct);
            }

            // Data for the view
            $data = array(
                "arrVendorDetails" => $arrVendorDetails 
                , "arrLocation" => $em->getRepository("EasyShop\Entities\EsLocationLookup")->getLocation()
                , "storeNameDisplay" => strlen($arrVendorDetails['store_name']) > 0 ? $arrVendorDetails['store_name'] : $arrVendorDetails['username']
                , "defaultCatProd" => $productView['defaultCatProd']
                , "hasAddress" => strlen($arrVendorDetails['stateregionname']) > 0 && strlen($arrVendorDetails['cityname']) > 0 ? TRUE : FALSE
                , "product_condition" => $this->lang->line('product_condition')
                , "avatarImage" => $um->getUserImage($arrVendorDetails['id_member'])
                , "bannerImage" => $um->getUserImage($arrVendorDetails['id_member'],"banner")
                , "isEditable" => ($this->session->userdata('member_id') && $arrVendorDetails['id_member'] == $this->session->userdata('member_id')) ? TRUE : FALSE
            ); 
            
            // Load Location
            $data = array_merge($data, $EsLocationLookupRepository->getLocationLookup());
            
            // Load Product View
            $data['viewProductCategory'] = $this->load->view("pages/user/display_product",$productView,TRUE);

            // Load View
            $this->load->view('templates/header', $headerData);
            $this->load->view('pages/user/vendor_view', $data);
            $this->load->view('templates/footer');
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
    private function getVendorDefaultCatAndProd($memberId)
    {
        $em = $this->serviceContainer['entity_manager'];
        $pm = $this->serviceContainer['product_manager'];
        $prodLimit = $this->vendorProdPerPage;

        $parentCat = $pm->getAllUserProductParentCategory($memberId);

        $productObjects = new stdClass();

        foreach( $parentCat as $idCat=>$category ){
            $parentCat[$idCat]['non_categorized_count'] = 0;
            $categoryProducts = $em->getRepository("EasyShop\Entities\EsProduct")
                                ->getNotCustomCategorizedProducts($memberId, $category['child_cat'], $prodLimit);

            $parentCat[$idCat]['products'] = $categoryProducts;            
            $parentCat[$idCat]['non_categorized_count'] = (int)$em->getRepository("EasyShop\Entities\EsProduct")
                                ->countNotCustomCategorizedProducts($memberId, $category['child_cat']);

            $parentCat[$idCat]['json_subcat'] = json_encode($category['child_cat'], JSON_FORCE_OBJECT);

            $productIdCollection = [];
            foreach($categoryProducts as $product => $value){
                $productId = $value->getIdProduct();
                $objImage = $em->getRepository("EasyShop\Entities\EsProductImage")
                                ->getDefaultImage($productId); 
                $value->directory = $objImage->getDirectory();
                $value->imageFileName = $objImage->getFilename();
            }

            $productObjects = (object) array_merge((array) $productObjects, (array) $categoryProducts);
        }
        $dataReturn['parentCat'] = $parentCat;
        $dataReturn['productObjCollection'] = $productObjects;
        return $dataReturn;
    }
	
	/**
	 * Renders the user about page
	 *
	 */
	private function aboutuser()
	{
        $data['title'] = 'Vendor Information | Easyshop.ph';
        $data = array_merge($data, $this->fill_header());                
        $this->load->view('templates/header_new', $data);
        $this->load->view('templates/header_vendor');
        $this->load->view('pages/user/about');
        $this->load->view('templates/footer');
	}

	/**
	 * Renders the user contact page
	 *
	 */
	private function contactuser()
	{
        $data['title'] = 'Vendor Contact | Easyshop.ph';
        $data = array_merge($data, $this->fill_header());                
        $this->load->view('templates/header_new', $data);
        $this->load->view('templates/header_vendor');
        $this->load->view('pages/user/contact');
        $this->load->view('templates/footer');
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
            'easytreats_prod' => $this->product_model->getProductsByCategory($categoryId,array(),0,"<",0,$perPage),
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
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
