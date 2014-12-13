<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
 
class Home extends MY_Controller 
{
 
    /**
     * Number of feeds item per page
     *
     * @var integer
     */
    public $feedsProdPerPage = 7;
    
    
    /**
     * Load class dependencies
     *
     */
    function __construct() 
    {
        parent::__construct();
    }

    /**
     * Renders home page if not logged in, otherwise render feed page
     *
     * @return View
     */
    public function index() 
    {
        $view = $this->input->get('view') ? $this->input->get('view') : NULL;
        $headerData = array(
            'title' => 'Your Online Shopping Store in the Philippines | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
            'relCanonical' => base_url(),
        );

        if( $this->session->userdata('member_id') && $view !== 'basic'){
            $bodyData = $this->getFeed();   
            $bodyData['category_navigation'] = $this->load->view('templates/category_navigation', [
                                                                    'cat_items' =>  $this->getcat()
                                                                ], true );
            $this->load->spark('decorator');  
            $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));  
            $this->load->view("templates/home_layout/layoutF",$bodyData);
            $this->load->view('templates/footer', array('minborder' => true));
        }
        else{
            $homeContent = $this->serviceContainer['xml_cms']->getHomeData();
            $sliderSection = $homeContent['slider']; 
            $homeContent['slider'] = array();
            foreach($sliderSection as $slide){
                $sliderView = $this->load->view($slide['template'],$slide, true);
                array_push($homeContent['slider'], $sliderView);
            }
            $data['homeContent'] = $homeContent;
            $this->load->spark('decorator');  
            $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/home/home_primary', $data);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
        }

    }
    
    
    /**
     * Renders home page if not logged in, otherwise render feed page
     *
     * @return View
     */
    public function under_construction()
    {
        $headerData = [
            'title' => 'Under Construction | Easyshop.ph'
        ];
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/underconstruction_view');
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
    }


    
    /**
     * Renders splash page
     *
     * @return View
     */
    public function splash()
    {
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $viewData['facebook'] = $socialMediaLinks["facebook"];
        $viewData['twitter'] = $socialMediaLinks["twitter"];      
        $this->load->view('pages/undermaintenance', $viewData);
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
        $headerData = [
            'title' => 'Privacy Policy | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Privacy Policy",
        ];
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/policy');
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
    }
  
    /**
     * Renders terms and conditions page
     *
     * @return View
     */
    public function terms()
    {
        $headerData = [
            'title' => 'Terms and Conditions | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Terms and Conditions",
        ];

        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/terms');
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
    }
    
    
    /**
     * Renders FAQ page
     *
     * @return View
     */
    public function faq()
    {
        $headerData = [
            'title' => 'F.A.Q. | Easyshop.ph',
            'metadescription' => 'Get in the know, read the Frequently Asked Questions at Easyshop.ph',
        ];
    
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/faq');
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
     
    }
    
    
    /**
     * Renders contact-us page
     *
     * @return View
     */
    public function contact()
    {
        $headerData = [
            'title' => 'Contact us | Easyshop.ph',
            'metadescription' => 'Get in touch with our Customer Support',
        ];
        
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/contact');
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
    }
    
    
      
    /**
     * Renders how-to-buy infographic
     *
     * @return View
     */
    public function guide_buy()
    {
        $headerData = [
            'title' => 'How to buy | Easyshop.ph',
            'metadescription' => 'Learn how to purchase at Easyshop.ph',
        ];
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $bodyData['facebook'] = $socialMediaLinks["facebook"];
        $bodyData['twitter'] = $socialMediaLinks["twitter"];    
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/how-to-buy', $bodyData);
    }
    
    
    /**
     * Renders how-to-sell infographic
     *
     * @return View
     */
    public function guide_sell()
    {
        $headerData = [
            'title' => 'How to sell | Easyshop.ph',
            'metadescription' => 'Learn how to sell your items at Easyshop.ph',
        ];
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $bodyData['facebook'] = $socialMediaLinks["facebook"];
        $bodyData['twitter'] = $socialMediaLinks["twitter"];    
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/how-to-sell', $bodyData);
    }
    
    
  

    /**
     *  Fetch information to be display in feeds page
     *
     *  @return array
     */
    public function getFeed()
    {
        $this->load->library('xmlmap');
        $this->load->model('product_model');
        $this->load->model('user_model');
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
        $this->load->library('xmlmap');
        $this->load->model('product_model');
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

        $headerData = [
            'title' => 'Report a Problem | Easyshop.ph',
            'metadescription' => 'Found a bug? Let us know so we can work on it.',
        ];

        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->output->append_output($formData); 
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));        
    }


}

/* End of file home.php */
/* Location: ./application/controllers/home.php */

