<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

use EasyShop\Entities\EsAddress as EsAddress; 
use EasyShop\Entities\EsMember as EsMember; 
use EasyShop\Category\CategoryManager as CategoryManager;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Upload\AssetsUploader as AssetsUploader;
    
class Store extends MY_Controller
{
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
     * Number of followers per page
     *
     * @var integer
     */
    public $followerPerPage = 6;    

    /**
     * Renders vendorpage
     *
     * @param string $tab
     * @return View
     */
    public function userprofile()
    {
        $em = $this->serviceContainer["entity_manager"];
        $um = $this->serviceContainer['user_manager'];
        $searchProductService = $this->serviceContainer['search_product'];
        $sessionData = $this->session->all_userdata();
                
        $vendorSlug = $this->uri->segment(1);
        $memberEntity = $em->getRepository("EasyShop\Entities\EsMember")
                           ->getActiveMemberWithSlug($vendorSlug);                
        if( !empty($memberEntity) ){
            $pageSection = $this->uri->segment(2);
            if($pageSection === 'about'){
                $this->aboutUser($vendorSlug);
            }
            else if($pageSection === 'contact'){
                $this->contactUser($vendorSlug);
            }
            else if($pageSection === 'followers'){
                $this->followers($vendorSlug);
            }
            else{
                $viewerId = intval(!isset($sessionData['member_id']) ? 0 : $sessionData['member_id']);
                $bannerData = $this->generateUserBannerData($vendorSlug, $viewerId);

                if ($bannerData['hasNoItems']){
                    redirect($vendorSlug.'/about');
                }
                
                $getUserProduct = $this->getInitialCategoryProductsByMemberId($bannerData['arrVendorDetails']['id_member']);

                $productView['categoryProducts'] = $getUserProduct;
                
                if($this->input->get() && !$bannerData['hasNoItems']){

                    $productView['isSearching'] = true;
                    $parameter = $this->input->get();
                    $parameter['seller'] = "seller:".$memberEntity->getUsername();
                    $parameter['limit'] = $this->vendorProdPerPage;

                    $search = $searchProductService->getProductBySearch($parameter);
                    $searchProduct = $search['collection'];
                    $count = $search['count'];
                    $searchCategoryWrapper = new \EasyShop\Category\CategoryWrapper();
                    $searchCategoryWrapper->setCategoryName('Search Result');
                    $searchCategoryWrapper->setSortOrder(0);
                    $productView['categoryProducts']['search']['category'] = $searchCategoryWrapper;
                    $productView['categoryProducts']['search']['products'] = $searchProduct;
                    $productView['categoryProducts']['search']['non_categorized_count'] = $count;
                    $productView['categoryProducts']['search']['json_subcat'] = "{}";
                    $productView['categoryProducts']['search']['cat_type'] = CategoryManager::CATEGORY_SEARCH_TYPE;

                    $paginationData = array(
                        'totalPage' => ceil($count/$this->vendorProdPerPage),
                        'hasHashtag' => false,
                    );
                    $pagination = $this->load->view('pagination/search-pagination', $paginationData, true);

                    $view = array(
                        'arrCat' => array(
                            'products'=>$searchProduct,
                            'page' => 1,
                        )
                    );
                    $productView['categoryProducts']['search']['product_html_data'] = $this->load->view("pages/user/display_product", $view, true);
                    $productView['categoryProducts']['search']['pagination'] = $pagination;
                }

                //HEADER DATA
                $storeColor = $memberEntity->getStoreColor();
                $bannerData['storeColorScheme'] = $storeColor;
                $bannerData['isLoggedIn'] = $this->session->userdata('usersession');
                $bannerData['vendorLink'] = "";

                $headerData = [
                    "memberId" => $this->session->userdata('member_id'),
                    'title' => html_escape($bannerData['arrVendorDetails']['store_name'])." | Easyshop.ph",
                    'metadescription' => html_escape($bannerData['arrVendorDetails']['store_desc']),
                    'relCanonical' => base_url().$vendorSlug,
                ];

                $viewData = array(
                    "categoryProducts" => $productView['categoryProducts'],
                    "product_condition" => $this->lang->line('product_condition'),
                    "isLoggedIn" => $this->session->userdata('usersession'),
                    "prodLimit" => $this->vendorProdPerPage,
                    "storeColorScheme" => $storeColor,
                );
 
                // count the followers 
                $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                          ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 
                $data["followerCount"] = $EsVendorSubscribe->getFollowers($bannerData['arrVendorDetails']['id_member'])['count'];
             
                $this->load->spark('decorator');
                $this->load->view('templates/header_alt',  array_merge($this->decorator->decorate('header', 'view', $headerData),$bannerData) );
                $this->load->view('templates/vendor_banner',$bannerData);
                $this->load->view('pages/user/vendor_view', $viewData);
                $this->load->view('templates/footer_alt', ['sellerSlug' => $vendorSlug]);
            }
        }
        else{
            show_404();
        }

    }

    /**
     *  Used to upload avatar image on both Member page and Vendor page
     *  Reloads page on success.
     *
     *  NOTE: For browsers with minimal JS capabilities, this function reloads the page
     *      and displays an error for 3 seconds, then reloads the page to the original URL,
     *      member page or vendor page
     */
    public function upload_img()
    {
        $cropData = [
            'x' => $this->input->post('x'),
            'y' => $this->input->post('y'),
            'w' => $this->input->post('w'),
            'h' => $this->input->post('h')
        ];

        $memberId = $this->session->userdata('member_id');
        $assetsUploader = $this->serviceContainer['assets_uploader'];
        $uploadResult = $assetsUploader->uploadUserAvatar($memberId, "userfile", $cropData);

        $memberObj = $uploadResult['member'];
        $userImage = $this->serviceContainer['user_manager']
                          ->getUserImage($memberId);                                                             

        if(!(bool)$this->input->post('isAjax')) {
            redirect($memberObj->getSlug().'/'.html_escape($this->input->post('vendorLink')));
        }

        $response = [
            'isSuccessful' => empty($uploadResult['error']) ? true : false,
            'image' => $userImage,
            'message' => empty($uploadResult['error']) ? "" : "Please select valid image type.\nAllowed type: ".AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES." \nAllowed max size: ".AssetsUploader::MAX_ALLOWABLE_SIZE_KB." kb",
        ];


        echo json_encode($response);
    }    



    /**
     *  Used for uploading banner in vendor page. 
     *  @return JSON
     */
    public function banner_upload()
    {    
        $cropData = [
            'x' => $this->input->post('x'),
            'y' => $this->input->post('y'),
            'w' => $this->input->post('w'),
            'h' => $this->input->post('h')
        ];

        $memberId = $this->session->userdata('member_id');
        $assetsUploader = $this->serviceContainer['assets_uploader'];
        $uploadResult = $assetsUploader->uploadUserBanner($memberId, "userfile", $cropData);
        $memberObj = $uploadResult['member'];
        $userImage = $this->serviceContainer['user_manager']
                          ->getUserImage($memberId, 'banner');                                                             

        if(!(bool)$this->input->post('isAjax')) {
            redirect($memberObj->getSlug().'/'.html_escape($this->input->post('vendorLink')));
        }
        
        $response = [
            'isSuccessful' => empty($uploadResult['error']) ? true : false,
            'banner' => $userImage,
            'message' => empty($uploadResult['error']) ? "" : "Please select valid image type.\nAllowed type: ".AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES." \nAllowed max size: ".AssetsUploader::MAX_ALLOWABLE_SIZE_KB." kb",
        ];
        
        
        echo json_encode($response);
    }

    
    /**
     * Transition controller action for old vendor page
     * Performs 301 redirect to new page. Needed for SEO purposes.
     *
     * @param string $vendorSlug
     */
    public function oldUserProfile($vendorSlug)
    {
        redirect('/'.$vendorSlug, 'location', 301);
    }

    /**
     * Render users follower page
     * @param  string $sellerslug
     */
    private function followers($sellerslug)
    {
        $viewerId = $this->session->userdata('member_id');
        $bannerData = $this->generateUserBannerData($sellerslug, $viewerId);
        $memberId = $bannerData['arrVendorDetails']['id_member'];
        $bannerData['isLoggedIn'] = $this->session->userdata('usersession');
        $bannerData['vendorLink'] = "about";

        // get followers
        $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                    ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 
        $pageOffset = 0;
        $followers = $EsVendorSubscribe->getFollowers($memberId,$pageOffset,$this->followerPerPage);
        if($followers['followers']){
            foreach ($followers['followers'] as $key => $value) {
                $value->subscriptionStatus = $this->serviceContainer['user_manager']
                          ->getVendorSubscriptionStatus($viewerId, $value->getMember()->getUsername());
                $value->avatarImage = $this->serviceContainer['user_manager']->getUserImage($value->getMember()->getIdMember());
                $value->bannerImage = $this->serviceContainer['user_manager']->getUserImage($value->getMember()->getIdMember(),"banner");
                $userAddress = $this->serviceContainer['entity_manager']->getRepository("EasyShop\Entities\EsAddress")
                                            ->findOneBy(['idMember' => $value->getMember()->getIdMember(),'type' => EsAddress::TYPE_DEFAULT]);
                $value->location = false;
                if($userAddress){
                    $value->location = TRUE;
                    $value->city = $userAddress->getCity()->getLocation();
                    $value->stateRegion = $userAddress->getStateregion()->getLocation();
                }
            } 
        }

        $bannerData['storeColorScheme'] = $this->serviceContainer['entity_manager']
                                               ->getRepository('EasyShop\Entities\EsMember')
                                               ->getActiveMemberWithSlug($sellerslug)
                                               ->getStoreColor();
        
        $followerData['followerCount'] = $bannerData["followerCount"];
        $followerData['storeName'] = strlen($bannerData['arrVendorDetails']['store_name']) > 0 ? $bannerData['arrVendorDetails']['store_name'] : $bannerData['arrVendorDetails']['username'];
        $followerData['followers'] = $followers['followers'];
        $followerData['isLoggedIn'] = $this->session->userdata('usersession');
        $followerData['viewerId'] = $viewerId;
        $followerData['memberId'] = $memberId;
        $followerData['page'] = 0; 

        // get who to follow
        $followerData['recommendToFollow'] = $EsVendorSubscribe->getRecommendToFollow($memberId,$viewerId);

        $followerData['memberIdsDisplay'] = []; 
        foreach ($followerData['recommendToFollow'] as $key => $value) {
            $followerData['memberIdsDisplay'][] = $value->getIdMember();
            // get user images for display
            $value->avatarImage = $this->serviceContainer['user_manager']->getUserImage($value->getIdMember());
            
            // get user address
            $userAddress = $this->serviceContainer['entity_manager']->getRepository("EasyShop\Entities\EsAddress")
                                        ->findOneBy(['idMember' => $value->getIdMember(),'type' => EsAddress::TYPE_DEFAULT]);
            $value->location = false;
            if($userAddress){
                $value->location = TRUE;
                $value->city = $userAddress->getCity()->getLocation();
                $value->stateRegion = $userAddress->getStateregion()->getLocation();
            }
        }

        // Generate pagination view
        $paginationData = array(
            'lastPage' => ceil($followers['count']/$this->followerPerPage)
            ,'isHyperLink' => false
        );

        $followerData['pagination'] = $this->load->view('pagination/default', $paginationData, true);
        $followerData['follower_view'] = $this->load->view('pages/user/followers_content', $followerData, true);
        $followerData['follower_recommed_view'] = $this->load->view('pages/user/followers_recommend', $followerData, true);

        // Load View
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => html_escape($bannerData['arrVendorDetails']['store_name'])." | Easyshop.ph",
            'metadescription' => html_escape($bannerData['arrVendorDetails']['store_desc']),
            'relCanonical' => base_url().$sellerslug.'/followers',
        ];
        
        $this->load->spark('decorator');    
        $this->load->view('templates/header_alt',  array_merge($this->decorator->decorate('header', 'view', $headerData),$bannerData) );
        $this->load->view('templates/vendor_banner',$bannerData);
        $this->load->view('pages/user/followers' ,$followerData);
        $this->load->view('templates/footer_alt', ['sellerSlug' => $sellerslug]);
    }

    public function getMoreFollowers()
    {
        $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                    ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 
        $pageOffset = $this->input->get('page') - 1; // start count page in 1.
        $viewerId = $this->session->userdata('member_id');
        $memberId = $this->input->get('vendorId'); 
        $followers = $EsVendorSubscribe->getFollowers($memberId,$pageOffset,$this->followerPerPage);
        if($followers['followers']){
            foreach ($followers['followers'] as $key => $value) {
                $value->subscriptionStatus = $this->serviceContainer['user_manager']
                          ->getVendorSubscriptionStatus($viewerId, $value->getMember()->getUsername());
                $value->avatarImage = $this->serviceContainer['user_manager']->getUserImage($value->getMember()->getIdMember());
                $value->bannerImage = $this->serviceContainer['user_manager']->getUserImage($value->getMember()->getIdMember(),"banner");
                $userAddress = $this->serviceContainer['entity_manager']->getRepository("EasyShop\Entities\EsAddress")
                              ->findOneBy(['idMember' => $value->getMember()->getIdMember(),'type' => EsAddress::TYPE_DEFAULT]);
                $value->location = false;
                if($userAddress){
                    $value->location = TRUE;
                    $value->city = $userAddress->getCity()->getLocation();
                    $value->stateRegion = $userAddress->getStateregion()->getLocation();
                }
            } 
        }

        $followerData['followers'] = $followers['followers'];
        $followerData['isLoggedIn'] = $this->session->userdata('usersession');
        $followerData['viewerId'] = $viewerId; 
        $followerData['page'] = $pageOffset;


        $paginationData = array(
            'lastPage' => ceil($followers['count']/$this->followerPerPage)
            , 'isHyperLink' => false
            , 'currentPage' => $pageOffset + 1
        );

        $followerData['pagination'] = $this->load->view('pagination/default', $paginationData, true);
        $response['html'] = $this->load->view('pages/user/followers_content', $followerData, true);

        echo json_encode($response);
    }

    public function getMoreRecommendToFollow()
    {
        $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                    ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 

        $viewerId = $this->session->userdata('member_id');
        $memberId = $this->input->get('vendorId'); 
        $ids = json_decode($this->input->get('ids'));

       // get who to follow
        $followerData['recommendToFollow'] = $EsVendorSubscribe->getRecommendToFollow($memberId,$viewerId,1,$ids);

        foreach ($followerData['recommendToFollow'] as $key => $value) {
            $ids[] = $value->getIdMember();
            // get user images for display
            $value->avatarImage = $this->serviceContainer['user_manager']->getUserImage($value->getIdMember());
            
            // get user address
            $userAddress = $this->serviceContainer['entity_manager']->getRepository("EasyShop\Entities\EsAddress")
                                        ->findOneBy(['idMember' => $value->getIdMember(),'type' => EsAddress::TYPE_DEFAULT]);
            $value->location = false;
            if($userAddress){
                $value->location = TRUE;
                $value->city = $userAddress->getCity()->getLocation();
                $value->stateRegion = $userAddress->getStateregion()->getLocation();
            }
        }
        $response['count'] = count($followerData['recommendToFollow']);
        $response['ids'] = json_encode($ids);
        $response['html'] = $this->load->view('pages/user/followers_recommend', $followerData, true);
        
        echo json_encode($response);
    }
    
    /**
     *  Fetch categories and initial products for first load of page.
     *
     *  @return array
     */
    private function getInitialCategoryProductsByMemberId($memberId)
    {
        $em = $this->serviceContainer['entity_manager'];
        $categoryManager = $this->serviceContainer['category_manager'];
        $prodLimit = $this->vendorProdPerPage;

        $parentCategories = $categoryManager->getUserCategories($memberId);

        $totalCountNonCategorizedProducts = $em->getRepository('EasyShop\Entities\EsProduct')
                                               ->getCountNonCategorizedProducts($memberId);
        if($totalCountNonCategorizedProducts > 0){
            $nonCategorizedCategory = new \EasyShop\Category\CategoryWrapper();
            $nonCategorizedCategory->setCategoryName('Uncategorized');
            $nonCategorizedCategory->setSortOrder(PHP_INT_MAX);
            $parentCategories['custom-noncategorized'] = $nonCategorizedCategory;
        }
        /**
         * Append the data for the 2nd level categories
         */
        foreach( $parentCategories as $categoryProperties ){
            if($categoryProperties->getIsCustom()){
                $children = $categoryProperties->getChildren();
                if(!empty($children)){
                    foreach($children as $child){
                        $child->setIsHidden(true);
                        $childId = (int)$child->getId();
                        $parentCategories[$childId] = $child;
                    }
                }
            }
        }

        $categoryData = [];        
        foreach( $parentCategories as $key => $categoryWrapper ){ 

            $categoryIdCollection = [];
            if($key !== 'custom-noncategorized'){
                $categoryIdCollection[] = $categoryWrapper->getId();
            }
            $childrenCategories = $categoryWrapper->getChildrenAsArray();
            $categoryIdCollection = array_merge($categoryIdCollection, $childrenCategories);

            $isCustom = $categoryWrapper->getIsCustom();
            $result = $categoryManager->getProductsWithinCategory(
                                            $memberId, 
                                            $categoryIdCollection, 
                                            $isCustom, 
                                            $prodLimit
                                        );
            if( (int)$result['filtered_product_count'] === 0){
                unset($parentCategories[$key]);
                continue;
            }

            $categoryData[$key]['category'] = $categoryWrapper;
            $categoryData[$key]['products'] = $result['products'];
            $categoryData[$key]['non_categorized_count'] = $result['filtered_product_count'];
            $categoryData[$key]['json_subcat'] = json_encode($categoryIdCollection, JSON_FORCE_OBJECT);
            $categoryData[$key]['cat_type'] = CategoryManager::CATEGORY_NONSEARCH_TYPE;

            $paginationData = [
                'totalPage' => ceil($result['filtered_product_count']/$this->vendorProdPerPage),
                'hasHashtag' => false,
            ];
            $pagination = $this->load->view('pagination/search-pagination', $paginationData, true);
            $view = [
                'arrCat' => [
                    'products' => $result['products'],
                    'page' => 1,
                ]
            ];
            $categoryData[$key]['product_html_data'] = $this->load->view("pages/user/display_product", $view, true);          
            $categoryData[$key]['pagination'] = $pagination;
        }    

        return $categoryData;
    }


       
    /**
     * Renders the user about page
     *
     * @param string $sellerslug
     */
    private function aboutUser($sellerslug)
    {
        $limit = $this->feedbackPerPage;
        $this->lang->load('resources');

        $member = $this->serviceContainer['entity_manager']
                       ->getRepository('EasyShop\Entities\EsMember')
                       ->getActiveMemberWithSlug($sellerslug);                                

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
                                                                   'isHyperLink' => false), true);
        $subViewData = [
            'isActive' => true,
            'feedbacks' => $feedbacks,
            'pagination' => $pagination,
            'id' => 'as-buyer',
            'ratingHeaders' => $ratingHeaders,
            'feedbackType' =>  EasyShop\Entities\EsMemberFeedback::TYPE_AS_BUYER,
        ];
        $feedbackTabsDesktop['asBuyer'] = $this->load->view('/partials/feedback-desktopview', $subViewData, true); 
        $subViewData['isActive'] = false;
        $feedbackTabsMobile['asBuyer'] = $this->load->view('/partials/feedback-mobileview', $subViewData, true);  
        
        
        $feedbacks  = $this->serviceContainer['user_manager']
                           ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_AS_SELLER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['otherspost_seller'])/$limit),
                                                                   'isHyperLink' => false), true);
        $subViewData = [
            'isActive' => false,
            'feedbacks' => $feedbacks,
            'pagination' => $pagination,
            'id' => 'as-seller',
            'ratingHeaders' => $ratingHeaders,
            'feedbackType' =>  EasyShop\Entities\EsMemberFeedback::TYPE_AS_SELLER,
        ];
        $feedbackTabsDesktop['asSeller'] = $this->load->view('/partials/feedback-desktopview', $subViewData, true);
        $feedbackTabsMobile['asSeller'] = $this->load->view('/partials/feedback-mobileview', $subViewData, true);

        $feedbacks  = $this->serviceContainer['user_manager']
                           ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['youpost_seller'])/$limit),
                                                                   'isHyperLink' => false), true);
        $subViewData = [
            'isActive' => false,
            'feedbacks' => $feedbacks,
            'pagination' => $pagination,
            'id' => 'for-other-seller',
            'ratingHeaders' => $ratingHeaders,
            'feedbackType' =>  EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER,
        ];
        
        $feedbackTabsDesktop['forOthersAsSeller'] = $this->load->view('/partials/feedback-desktopview', $subViewData, true);                                                          
        $feedbackTabsMobile['forOthersAsSeller'] = $this->load->view('/partials/feedback-mobileview', $subViewData, true);    
                                                                
        $feedbacks  = $this->serviceContainer['user_manager']
                           ->getFormattedFeedbacks($idMember, EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER, $limit);                                             
        $pagination = $this->load->view('/pagination/default', array('lastPage' => ceil(count($allFeedbacks['youpost_buyer'])/$limit),
                                                                   'isHyperLink' => false), true);
        $subViewData = [
            'isActive' => false,
            'feedbacks' => $feedbacks,
            'pagination' => $pagination,
            'id' => 'for-other-buyer',
            'ratingHeaders' => $ratingHeaders,
            'feedbackType' =>  EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER,
        ];
        
        $feedbackTabsDesktop['forOthersAsBuyer'] = $this->load->view('/partials/feedback-desktopview', $subViewData, true);
        $feedbackTabsMobile['forOthersAsBuyer'] = $this->load->view('/partials/feedback-mobileview', $subViewData, true);

        $viewerId = intval(!$this->session->userdata('member_id') ? 0 : $this->session->userdata('member_id'));
        
        $orderRelations = array();
        $viewer = null;
        if($viewerId !== 0){
            $orderRelations = $this->serviceContainer['entity_manager']
                                   ->getRepository('EasyShop\Entities\EsOrder')
                                   ->getOrderRelations($viewerId, $idMember, true);
            $viewer = $this->serviceContainer['entity_manager']
                           ->getRepository('EasyShop\Entities\EsMember')
                           ->find($viewerId);
        }
        $bannerData = $this->generateUserBannerData($sellerslug, $viewerId);
        $userDetails = $this->userDetails($sellerslug, 'about',  $bannerData['stateRegionLookup'], $bannerData['cityLookup']);
        $bannerData['arrVendorDetails'] = $this->serviceContainer['entity_manager']
                                                ->getRepository("EasyShop\Entities\EsMember")
                                                ->getVendorDetails($sellerslug);
        $bannerData['hasAddress'] = strlen($bannerData['arrVendorDetails']['stateregionname']) > 0 && strlen($bannerData['arrVendorDetails']['cityname']) > 0;

        $bannerData['storeColorScheme'] = $member->getStoreColor();
        $bannerData['isLoggedIn'] = $this->session->userdata('usersession');
        $bannerData['vendorLink'] = "about";
        
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => html_escape($bannerData['arrVendorDetails']['store_name'])." | Easyshop.ph",
            'metadescription' => html_escape($bannerData['arrVendorDetails']['store_desc']),
            'relCanonical' => base_url().$sellerslug.'/about'
        ];

        $this->load->spark('decorator');    
        $this->load->view('templates/header_alt',  array_merge($this->decorator->decorate('header', 'view', $headerData),$bannerData) );
        $this->load->view('templates/vendor_banner', $bannerData);
        $this->load->view('pages/user/about', ['feedbackSummary' => $feedbackSummary,
                                               'ratingHeaders' => $ratingHeaders,
                                               'feedbackTabsDesktopView' => $feedbackTabsDesktop,
                                               'feedbackTabsMobileView' => $feedbackTabsMobile,
                                               'member' => $member,
                                               'viewer' => $viewer,
                                               'orderRelations' => $orderRelations,
                                               'isEditable' =>  $bannerData['isEditable'],
                                               'userDetails' => $userDetails,
                                              ]);
        $this->load->view('templates/footer_alt', ['sellerSlug' => $sellerslug]);
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
        $description = substr($description, 0, $maxLength);
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
        $reviewer = $this->serviceContainer['entity_manager']
                         ->getRepository('EasyShop\Entities\EsMember')
                         ->find(intval($this->session->userdata('member_id')));
        $reviewee = $this->serviceContainer['entity_manager']
                         ->getRepository('EasyShop\Entities\EsMember')
                         ->find(intval($this->input->post('userId')));
        $orderToReview = $this->serviceContainer['entity_manager']
                              ->getRepository('EasyShop\Entities\EsOrder')
                              ->find(intval($this->input->post('feeback-order')));   
        $rating1 = (int) $this->input->post('rating1');
        $rating2 = (int) $this->input->post('rating2');
        $rating3 = (int) $this->input->post('rating3');
        $areAllRatingsSet = $rating1 > 0 && $rating2 > 0 && $rating3 > 0;
        $message = $this->input->post('feedback-message');
                
        if($reviewer && $reviewee && $orderToReview && strlen($message) > 0 && $areAllRatingsSet){
        
            $formValidation = $this->serviceContainer['form_validation'];
            $formFactory = $this->serviceContainer['form_factory'];
            $formErrorHelper = $this->serviceContainer['form_error_helper'];
            $rules = $formValidation->getRules('user_feedback');
            $formBuild = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                     ->setMethod('POST');                    
            $formBuild->add('message', 'text', ['constraints' => $rules['message']]);
            $formBuild->add('rating1', 'text', ['constraints' => $rules['rating']]);
            $formBuild->add('rating2', 'text', ['constraints' => $rules['rating']]);
            $formBuild->add('rating3', 'text', ['constraints' => $rules['rating']]);
            $formData["message"] =  $message;
            $formData["rating1"] =  $rating1;
            $formData["rating2"] =  $rating2;
            $formData["rating3"] =  $rating3;
            $form = $formBuild->getForm();
            $form->submit($formData); 

            if($form->isValid()){
                $feedbackType = false;
                if($reviewer->getIdMember() === $orderToReview->getBuyer()->getIdMember()){
                    $feedbackType = EasyShop\Entities\EsMemberFeedback::REVIEWER_AS_BUYER;
                }
                else if($reviewee->getIdMember() === $orderToReview->getBuyer()->getIdMember()){
                    $feedbackType = EasyShop\Entities\EsMemberFeedback::REVIEWER_AS_SELLER;
                }
                if($feedbackType !== false){
                    $feedback = new EasyShop\Entities\EsMemberFeedback();
                    $feedback->setMember($reviewer);
                    $feedback->setForMemberid($reviewee);
                    $feedback->setOrder($orderToReview);
                    $feedback->setFeedbMsg($message);
                    $feedback->setDateadded(new DateTime('now'));
                    $feedback->setRating1($rating1);
                    $feedback->setRating2($rating2);
                    $feedback->setRating3($rating3);
                    $feedback->setFeedbKind($feedbackType);
                    $em->persist($feedback);
                    $em->flush();
                }
            }
            redirect('/'.$reviewee->getSlug().'/about');
        }
        redirect('/');
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
        $feedbackview = $this->input->get('isMobile') === 'true' ? 'feedback-mobileview' : 'feedback-desktopview';

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
            $pagination = $this->load->view('/pagination/default', [
                                                'lastPage' => ceil($totalCount/$limit),
                                                'isHyperLink' => false,
                                                'currentPage' => $page,
                                            ] , true);
        }
        
        
        $feedbackTabs = $this->load->view('/partials/'.$feedbackview, [
                                                'isActive' => true,
                                                'feedbacks' => $feedbacks,
                                                'pagination' => $pagination,
                                                'id' => $tab,
                                                'ratingHeaders' => $ratingHeaders,
                                                'feedbackType' =>  $feedbackType,
                                        ], true); 
        
        echo $feedbackTabs;
    }

    /**
     * Renders the user contact page
     *
     */
    private function contactUser($sellerslug)
    {
        $viewerId = $this->session->userdata('member_id');
        $bannerData = $this->generateUserBannerData($sellerslug, $viewerId);
        $bannerData['isLoggedIn'] = $this->session->userdata('usersession');
        
        // assign header_vendor data
        $member = $this->serviceContainer['entity_manager']
                       ->getRepository('EasyShop\Entities\EsMember')
                       ->getActiveMemberWithSlug($sellerslug);                         
        $bannerData['storeColorScheme'] = $member->getStoreColor();
        $bannerData['vendorLink'] = "contact";
        $userDetails = $this->userDetails($sellerslug, 'contact',  $bannerData['stateRegionLookup'], $bannerData['cityLookup']);
        $bannerData['arrVendorDetails'] = $this->serviceContainer['entity_manager']
                                                ->getRepository("EasyShop\Entities\EsMember")
                                                ->getVendorDetails($sellerslug);
        $bannerData['hasAddress'] = strlen($bannerData['arrVendorDetails']['stateregionname']) > 0 && strlen($bannerData['arrVendorDetails']['cityname']) > 0;

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Contact '.html_escape($bannerData['arrVendorDetails']['store_name']).'| Easyshop.ph',
            'metadescription' => html_escape($bannerData['arrVendorDetails']['store_desc']),
            'relCanonical' => base_url().$sellerslug.'/contact',
        ];

        $bodyData = [
            'userDetails' => $userDetails,
            'seller' => $member,
        ];
        
        $this->load->spark('decorator');    
        $this->load->view('templates/header_alt',  array_merge($this->decorator->decorate('header', 'view', $headerData),$bannerData) );
        $this->load->view('templates/vendor_banner',$bannerData);
        $this->load->view('pages/user/contact', $bodyData);
        $this->load->view('templates/footer_alt', ['sellerSlug' => $sellerslug]);
    }

    /**
     * Generates the banner data
     *
     */
    private function generateUserBannerData($sellerslug, $viewerId)
    {   
        $EsLocationLookupRepository = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsLocationLookup');
        $arrVendorDetails = $this->serviceContainer['entity_manager']
                                 ->getRepository("EasyShop\Entities\EsMember")
                                 ->getVendorDetails($sellerslug);
        $sellerId = $arrVendorDetails['id_member'];
        $userProduct = $this->serviceContainer['entity_manager']->getRepository("EasyShop\Entities\EsProduct")
                                    ->findBy(['member' => $sellerId, 'isDelete' => 0,'isDraft' => 0]);

        $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                    ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 

        $followers = $EsVendorSubscribe->getFollowers($sellerId);
        $bannerData = array(
                  "arrVendorDetails" => $arrVendorDetails 
                , "hasAddress" => strlen($arrVendorDetails['stateregionname']) > 0 && strlen($arrVendorDetails['cityname']) > 0
                , "avatarImage" => $this->serviceContainer['user_manager']->getUserImage($sellerId)
                , "bannerImage" => $this->serviceContainer['user_manager']->getUserImage($sellerId,"banner")
                , "isEditable" => ($viewerId && intval($sellerId) === intval($viewerId))
                , "hasNoItems" => (count($userProduct) > 0) ? false : true
                , "subscriptionStatus" => $this->serviceContainer['user_manager']->getVendorSubscriptionStatus($viewerId, $arrVendorDetails['username'])
                , "followerCount" => $followers['count']
                , "snippetMarkUp" => $this->load->view('templates/seo/person_markup', $arrVendorDetails, true)
            ); 
        $bannerData = array_merge($bannerData, $EsLocationLookupRepository->getLocationLookup());

        return $bannerData;
    }


    /**
     *  Handles Vendor Contact Detail View
     *
     *  @param string $sellerslug
     *  @param string $targetPage
     *  @param string[] $regionList
     *  @param string[] $cityPerRegionList
     */
    private function userDetails($sellerslug, $targetPage, $regionList = NULL, $cityPerRegionList = NULL)
    {
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $rules = $formValidation->getRules('vendor_contact');
        $data['isValid'] = false;
        $data['targetPage'] = $targetPage;
        $data['errors'] = [];
        $viewerId = intval($this->session->userdata('member_id'));
        $um = $this->serviceContainer['user_manager'];
        
        $member = $this->serviceContainer['entity_manager']
                       ->getRepository('EasyShop\Entities\EsMember')
                       ->getActiveMemberWithSlug($sellerslug);    
        $data['validatedStoreName'] = $data['storeName'] = $member->getStoreName();
        $data['validatedContactNo'] = $data['contactNo'] = $member->getContactno() === "" ? '' : '0' . $member->getContactno();
        $data['validatedWebsite'] = $data['website'] = $member->getWebsite();
        $data['isEditable'] = $viewerId === intval($member->getIdMember());

        $addr = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsAddress')
                            ->findOneBy(['idMember' => $member->getIdMember(), 'type' => EsAddress::TYPE_DEFAULT]);

        if($addr === NULL){
            $data['validatedStreetAddr'] = $data['streetAddr'] = "Location not set ";
            $data['validatedCity'] = $data['city'] = '';
            $data['validatedRegion'] = $data['region'] = '';
        }
        else{
            $data['validatedStreetAddr'] = $data['streetAddr'] = strlen(trim($addr->getAddress())) > 0 ? $addr->getAddress() . ", " : "";
            $data['validatedCity'] = $addr->getCity()->getLocation(). ", ";
            $data['city'] = $addr->getCity()->getIdLocation();
            $data['validatedRegion'] = $data['region'] = $addr->getStateregion()->getLocation();
        }

        if($regionList === NULL || $cityPerRegionList === NULL){
            $locationData = $EsLocationLookupRepository->getLocationLookup();
            if($regionList === NULL){
                $regionList = $locationData['stateRegionLookup'];
            }
            if($cityPerRegionList === NULL){
                $regionList = $locationData['cityLookup'];
            }
        }
        
        $storeName = $this->input->post('storeName');
        $contactNumber = $this->input->post('contactNumber');
        $streetAddress = $this->input->post('streetAddress');
        $website = $this->input->post('website');
        $citySelect = $this->input->post('citySelect');
        $regionSelect = $this->input->post('regionSelect');

        // Check if blank string or string submitted can be converted to an int and if that int is a valid region id
        $isRegionValid = $regionSelect === '' || (intval($regionSelect) !== 0 && array_key_exists(intval($regionSelect), $regionList));

        if($storeName !== false || $contactNumber !== false || $streetAddress !== false || $website !== false || $citySelect !== false || $regionSelect !== false){

            /**
             * Overload default IsMobileUnique constraint
             */
            $contactNumberConstraints = $rules['contact_number'];
            foreach($contactNumberConstraints as $key => $mobileRule){
                if($mobileRule instanceof EasyShop\FormValidation\Constraints\IsMobileUnique){
                    unset($contactNumberConstraints[$key]);
                    break;
                }
            }
            $contactNumberConstraints[] = new EasyShop\FormValidation\Constraints\IsMobileUnique(['memberId' => $member->getIdMember()]);  
            $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                ->setMethod('POST')
                                ->add('shop_name', 'text', ['constraints' => $rules['shop_name']])
                                ->add('contact_number', 'text', ['constraints' => $contactNumberConstraints])
                                ->add('street_address', 'text')
                                ->add('city', 'text')
                                ->add('region', 'text')
                                ->add('website', 'text')
                                ->getForm();

            $form->submit([ 
                'shop_name' => $storeName,
                'contact_number' => $contactNumber,
                'street_address' => $streetAddress,
                'city' => $citySelect,
                'region' => $regionSelect,
                'website' => $website
            ]);

            // Do not allow whitespaces as streetAddress
            $streetAddressTrimmed = trim($streetAddress);

            $isAddressValid = (
                ($isRegionValid && $citySelect !== '' && $streetAddressTrimmed !== '') || 
                ($regionSelect === '' && $citySelect === '' && $streetAddressTrimmed === '')
            );
            
            $um->setUser($member->getIdMember());
            if($form->isValid() && $isAddressValid && $data['isEditable']){
                $formData = $form->getData();
                
                $um->setStoreName($formData['shop_name'])
                    ->setMobile($formData['contact_number'])
                    ->setMemberMisc([
                        'setWebsite' => $formData['website'], 
                        'setLastmodifieddate' => date_create(date("Y-m-d H:i:s"))
                    ]);

                if(!$formData['street_address'] && !$formData['region'] && !$formData['city']){
                    $um->deleteAddressTable(EasyShop\Entities\EsAddress::TYPE_DEFAULT);
                }
                else{
                    $um->setAddressTable(
                        $formData['region'], 
                        $formData['city'], 
                        $formData['street_address'],
                        EasyShop\Entities\EsAddress::TYPE_DEFAULT
                    );

                    $city = $this->serviceContainer['entity_manager']
                                 ->getRepository('EasyShop\Entities\EsLocationLookup')
                                 ->find((int)$formData['city']);

                    $region = $this->serviceContainer['entity_manager']
                                   ->getRepository('EasyShop\Entities\EsLocationLookup')
                                   ->find((int)$formData['region']);
                }

                if($um->errorInfo()){
                    foreach($um->errorInfo() as $key => $error){
                        $data['errors'][$key] = [$error];
                    }
                }
                else{
                    $um->save();
                    $data['isValid'] = true;
                    $data['validatedStoreName'] = $member->getStoreName();
                    $data['validatedContactNo'] = !$formData['contact_number'] ? "" : '0' . $member->getContactno();
                    $data['validatedWebsite'] = $formData['website'];
                    $data['validatedStreetAddr'] = $formData['street_address'] ? $formData['street_address'] . ", " : "Location not set ";
                    $data['validatedCity'] = !isset($city) ? '' : $city->getLocation(). ", ";
                    $data['validatedRegion'] = !isset($region) ? '' : $region->getLocation();
                }
            }
            else{
                $data['errors'] =  $this->serviceContainer['form_error_helper']->getFormErrors($form);
                if(!$isAddressValid){
                    $data['errors']['location'] = ["address must be complete"];
                }
                if($um->errorInfo()){
                    foreach($um->errorInfo() as $key => $error){
                        $data['errors'][$key] = [$error];
                    }
                }
            }

            $data['storeName'] = $storeName;
            $data['contactNo'] = $contactNumber;
            $data['streetAddr'] = strlen(trim($streetAddress)) > 0 ? $streetAddress . ", " : "";
            $data['city'] = $citySelect == '' ? '' : $citySelect;
            $data['website'] = $website;
            
            if($isRegionValid){
                if($regionSelect === ''){
                    $data['region'] = '';
                }
                else{
                    $data['region'] = $regionList[intval($regionSelect)];
                }
            }
            else{
                $data['region'] = '';
            }

            if(array_key_exists('shop_name', $data['errors'])){
                $data['storeName'] = '';
            }
            if(array_key_exists('contact_number', $data['errors'])){
                $data['contactNo'] = '';
            }
            if(array_key_exists('website', $data['errors'])){
                $data['website'] = '';
            }
        }

        $data['regions'] = $regionList;
        $data['cityList'] = $cityPerRegionList;
        return $this->load->view('/partials/userdetails', array_merge($data,['member'=>$member]), TRUE);
    }    

    
    /**
     * AJAX REQUEST HANDLER FOR LOADING PRODUCTS
     *
     * @return JSON
     */
    public function vendorLoadProducts()
    {
        $prodLimit = $this->vendorProdPerPage;
        $vendorId = $this->input->get('vendorId');
        $vendorName = $this->input->get('vendorName');
        $catId = json_decode($this->input->get('catId'), true);
        $catType = $this->input->get('catType');
        $page = $this->input->get('page');
        $rawOrderBy = intval($this->input->get('orderby'));
        $rawOrder = intval($this->input->get('order'));
        $isCount = intval($this->input->get('count')) === 1;
        $isCustom = $this->input->get('isCustom') === 'true';
        $condition = $this->input->get('condition') !== "" 
                    && isset($this->lang->line('product_condition')[$this->input->get('condition')]) 
                    ? $this->lang->line('product_condition')[$this->input->get('condition')] : "";
        $lprice = $this->input->get('lowerPrice') !== "" ? floatval($this->input->get('lowerPrice')) : "";
        $uprice = $this->input->get('upperPrice') !== "" ? floatval($this->input->get('upperPrice')) : "";

        $parameter = json_decode($this->input->get('queryString'),true);

        $em = $this->serviceContainer["entity_manager"];
        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer["category_manager"];

        switch($rawOrder){
            case 1:
                $order = "DESC";
                break;
            case 2:
                $order = "ASC";
                break;
            default:
                $order = "DESC";
                break;
        }

        switch($rawOrderBy){
            case 1:
                $orderBy = [ CategoryManager::ORDER_PRODUCTS_BY_CLICKCOUNT => $order ];
                break;
            case 2:
                $orderSearch = EsProduct::SEARCH_SORT_NEW;
                $orderBy = [ CategoryManager::ORDER_PRODUCTS_BY_LASTCHANGE => $order ];
                break;
            case 3:
                $orderSearch = EsProduct::SEARCH_SORT_HOT;
                $orderBy = [ CategoryManager::ORDER_PRODUCTS_BY_HOTNESS => $order ];
                break;
            default:
                $orderSearch = "";
                $orderBy = [ CategoryManager::ORDER_PRODUCTS_BY_SORTORDER => $order ];
                break;
        }

        switch($catType){
            case CategoryManager::CATEGORY_SEARCH_TYPE: 
                if($rawOrderBy > 1){
                    $parameter['sortby'] = $orderSearch;
                    $parameter['sorttype'] = $order;
                }
                if($condition != ""){
                    $parameter['condition'] = $condition;
                }
                if(is_numeric($lprice)){
                    $parameter['startprice'] = $lprice;
                }
                if(is_numeric($uprice)){
                    $parameter['endprice'] = $uprice;
                }
                $parameter['seller'] = "seller:".$vendorName;
                $parameter['limit'] = $prodLimit;
                $parameter['page'] = $page - 1;
                $search = $searchProductService->getProductBySearch($parameter);
                $products = $search['collection']; 
                $productCount = $search['count'];
                break;
            case CategoryManager::CATEGORY_NONSEARCH_TYPE: 
            default:
                    $result = $categoryManager->getProductsWithinCategory(
                                                    $vendorId, 
                                                    $catId, 
                                                    $isCustom, 
                                                    $prodLimit, 
                                                    $page, 
                                                    $orderBy, 
                                                    $condition, 
                                                    $lprice, 
                                                    $uprice
                                                );
                    $products = $result['products'];
                    $productCount = $result['filtered_product_count'];
                break;
        }

        $arrCat = 
        $parseData = [ 
            'arrCat' => [
                'page' => $page,
                'products' => $products,
            ],
        ];

        $pageCount = $productCount > 0 ? ceil($productCount/$prodLimit) : 1;
        $serverResponse = [
            'htmlData' => $this->load->view("pages/user/display_product", $parseData, true),
            'isCount' => $isCount,
            'pageCount' => $pageCount,
        ];

        echo json_encode($serverResponse);
    }

    /**
     *  Handles details in vendorpage
     *
     *  @return JSON
     */
    public function updateStoreBannerDetails()
    {
        $memberId = $this->session->userdata('member_id');
        $um = $this->serviceContainer['user_manager'];

        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];

        $rules = $formValidation->getRules('personal_info');
        $formBuild = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                            ->setMethod('POST');
        /**
         * Overload default IsMobileUnique constraint
         */
        foreach($rules['mobile'] as $key => $mobileRule){
            if($mobileRule instanceof EasyShop\FormValidation\Constraints\IsMobileUnique){
                unset($rules['mobile'][$key]);
                break;
            }
        }
        $rules['mobile'][] = new EasyShop\FormValidation\Constraints\IsMobileUnique(['memberId' => $memberId]);  
        
        $formBuild->add('store_name', 'text', ['constraints' => $rules['shop_name']])
                  ->add('mobile', 'text', ['constraints' => $rules['mobile']])
                  ->add('city', 'text')
                  ->add('stateregion', 'text');
        $formData["store_name"] = $this->input->post('store_name');
        $formData["mobile"] = $this->input->post('mobile');
        $formData["city"] = $this->input->post('city');
        $formData["stateregion"] = $this->input->post('stateregion');                            
        $form = $formBuild->getForm();
        $form->submit($formData); 

        if( $form->isValid() ){
            $formData = $form->getData();
            $validStoreName = (string)$formData['store_name'];
            $validMobile = (string)$formData['mobile'];
            $validCity = $formData['city'];
            $validStateRegion = $formData['stateregion'];

            $um->setUser($memberId)
               ->setStoreName($validStoreName)
               ->setMobile($validMobile)
               ->setMemberMisc([
                    'setLastmodifieddate' => new DateTime('now')
                ]);

            if( $validCity === "0" && $validStateRegion === "0" ){
                $um->deleteAddressTable(EasyShop\Entities\EsAddress::TYPE_DEFAULT);
            }
            else{
                $um->setAddressTable($validStateRegion, $validCity, "", EasyShop\Entities\EsAddress::TYPE_DEFAULT);
            }

            $boolResult = $um->save();
            if(!$boolResult){
                $errors = $um->errorInfo();
                $newData = [];
            }
            else{
                $errors = '';
                $newData = [
                    "store_name" => $validStoreName,
                    "mobile" => $validMobile,
                    "state_region_id" => $validStateRegion,
                    "city_id" => $validCity,
                ];
            }
           
            $serverResponse = [
                'result' => $boolResult,
                'error' => $errors,
                'new_data' => $newData,
            ];
        }
        else{
            $serverResponse = [
                'result' => false,
                'error' => $formErrorHelper->getFormErrors($form)
            ];
        }

        echo json_encode($serverResponse);
    }

}


