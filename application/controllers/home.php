<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
 
class Home extends MY_Controller 
{
    /**
     * Load class dependencies
     *
     */
    public function __construct() 
    {
        parent::__construct();
        $this->productManager = $this->serviceContainer['product_manager'];
        $this->userManager = $this->serviceContainer['user_manager'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->memberFeatureRestrictManager = $this->serviceContainer['member_feature_restrict_manager'];
    }

    /**
     * Renders home page if not logged in, otherwise render feed page
     *
     * @return View
     */
    public function index() 
    {      
        $view = $this->input->get('view') ? $this->input->get('view') : null;
        $memberId = $this->session->userdata('member_id');
        $headerData = [
            'memberId' => $memberId,
            'title' => 'Your Online Shopping Store in the Philippines | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
            'relCanonical' => base_url(),
        ];

        $homeContent = $this->serviceContainer['xml_cms']->getHomeData();
        $sliderSection = $homeContent['slider']; 
        $homeContent['slider'] = [];
        foreach($sliderSection as $slide){
            $sliderView = $this->load->view($slide['template'], $slide, true);
            $homeContent['slider'][] = $sliderView;
        }
        $data['homeContent'] = $homeContent; 

        if ($memberId) {
            $data['featuredCategorySection'] = $this->serviceContainer['xml_cms']->getFeaturedProducts($memberId);
            /**
             * Uncomment to activate real time char restriction
             */
            //$this->memberFeatureRestrictManager->addMemberToFeature($memberId, \EasyShop\Entities\EsFeatureRestrict::REAL_TIME_CHAT);
        }

        
        $data['messageboxHtml'] = $this->load->view('pages/home/reminder', [], true);
        
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/home/home_primary', $data);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));

    }
    
    
    /**
     * Renders home page if not logged in, otherwise render feed page
     *
     * @return View
     */
    public function under_construction()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Under Construction | Easyshop.ph'
        ];
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/underconstruction_view');
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
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
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Privacy Policy | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Privacy Policy",
        ];
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/policy');
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }
  
    /**
     * Renders terms and conditions page
     *
     * @return View
     */
    public function terms()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Terms and Conditions | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Terms and Conditions",
        ];

        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/terms');
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }
    
    
    /**
     * Renders FAQ page
     *
     * @return View
     */
    public function faq()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'F.A.Q. | Easyshop.ph',
            'metadescription' => 'Get in the know, read the Frequently Asked Questions at Easyshop.ph',
        ];
    
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/faq');
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
     
    }
    
    
    /**
     * Renders contact-us page
     *
     * @return View
     */
    public function contact()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Contact us | Easyshop.ph',
            'metadescription' => 'Get in touch with our Customer Support',
        ];
        
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/contact');
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }
    
    
    /**
     * Redirect old how-to-page buyer to new page: SEO purposes
     */
    public function guide_buyer_old()
    {
        redirect('/how-to-buy', 'location', 301);
    }
    
    /**
     * Redirect old how-to-page seller to new page: SEO purposes
     */
    public function guide_seller_old()
    {
        redirect('/how-to-sell', 'location', 301);
    }

    /**
     * Renders how-to-page buyer
     *
     * @return View
     */
    public function guide_buyer()
    {
        $this->load->view('pages/web/how-to-buy');
    }
    
    /**
     * Renders how-to-page seller
     *
     * @return View
     */
    public function guide_seller()
    {
        $this->load->view('pages/web/how-to-sell');
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
                            ->add('captcha', 'text', array('required' => false, 'label' => false, 'constraints' => $rules['captcha']))
                            ->add('submit', 'submit', array('label' => 'SEND'))
                            ->getForm();

        $emptyForm = clone $form;
        $captchaBuilder = $this->serviceContainer['captcha_builder'];
        $form->handleRequest($request);

        $captchaMessage = '';
        if ($form->isValid()) {
            $formData = $form->getData();
            $captchaInput = $formData['captcha'];
            unset($formData['captcha']);
            if($captchaInput === $this->session->userdata('bugreport_captcha_phrase')) {
                $bugReporter = $this->serviceContainer['bug_reporter'];
                $bugReporter->createReport($formData);
                $isValid = true;
                $form = $emptyForm;
            }
            else {
                $captchaMessage = '* Characters do not match';
            }
        }
   
        $captchaBuilder->build();
        $this->session->set_userdata('bugreport_captcha_phrase', $captchaBuilder->getPhrase());
        $formData =  $twig->render('pages/web/report-a-problem.html.twig', [
            'form' => $form->createView(), 
            'ES_FILE_VERSION' => ES_FILE_VERSION,
            'assetsDomain' => getAssetsDomain(),
            'isValid' => $isValid,
            'captchaImage' => $captchaBuilder->inline(),
            'captchaMessage' => $captchaMessage, 
            'appEnvironment' => strtolower(ENVIRONMENT),
        ]);

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Report a Problem | Easyshop.ph',
            'metadescription' => 'Found a bug? Let us know so we can work on it.',
        ];

        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->output->append_output($formData); 
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));        
    }
    
    /**
     * Refresh capctha image
     */
    public function refreshBugReportCaptcha()
    {
        if($this->input->post()){
            $captchaBuilder = $this->serviceContainer['captcha_builder'];
            $captchaBuilder->build();
            $image = $captchaBuilder->inline();
            $this->session->set_userdata('bugreport_captcha_phrase', $captchaBuilder->getPhrase());
            echo "<img src='".$image."'/>";
        }
        else{
            show_404();
        }
    }
    
    /**
     * Renders the category product section view
     *
     */
    public function getCategorySectionProducts()
    {
        $productSlugs = json_decode($this->input->post('productSlugs'));
        $productSlugs = $productSlugs ? $productSlugs : [];
        if(!is_array($productSlugs)){
            $productSlugs = [ $productSlugs ];
        }
        
        $productCounter = 0;
        $data['productSections'] = [];
        foreach($productSlugs as $productSlug){
            $product = $this->serviceContainer['entity_manager']
                            ->getRepository('EasyShop\Entities\EsProduct')
                            ->findOneBy(['slug' => $productSlug]);
            if($product){
                if($this->productManager->isProductActive($product)){
                    $data['productSections'][$productCounter]['product'] =  $this->serviceContainer['product_manager']->getProductDetails($product);
                    $secondaryImage =  $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsProductImage')
                                            ->getSecondaryImage($product->getIdProduct());
                    $data['productSections'][$productCounter]['productSecondaryImage'] = $secondaryImage;
                    $data['productSections'][$productCounter]['userimage'] =   $this->serviceContainer['user_manager']->getUserImage($product->getMember()->getIdMember());
                    $productCounter++;
                }
            }
        }

        echo json_encode($this->load->view('partials/home-productlist', $data, true));
    }

    /**
     * Retrieves the redirect page for external links
     */
    public function redirect()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Redirect | Easyshop.ph',
            'metadescription' => '',
            'relCanonical' => '',
        ];

        if (!$this->input->get('url')) {
            redirect('/', 'refresh');
        }

        $urlData = $this->serviceContainer['url_utility']->parseExternalUrl(trim($this->input->get('url')));
        if($urlData['targetString'] === '_self'){
            redirect($urlData['url']);
        }
        
        $this->load->spark('decorator');
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/web/redirect', $urlData);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }

    public function widgets()
    {
        $this->load->view('pages/widgets/widget-selector');
    }

    public function widget1()
    {
        $this->load->view('pages/widgets/widget-1');
    }

    public function widget2()
    {
        $this->load->view('pages/widgets/widget-2');
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */

