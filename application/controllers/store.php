<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

use EasyShop\Entities\EsAddress as EsAddress; 
use EasyShop\Category\CategoryManager as CategoryManager;
    
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
                           ->findOneBy(['slug' => $vendorSlug]);

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
                $headerData = $this->fill_header();
                $bannerData = $this->generateUserBannerData($vendorSlug, $viewerId);

                if ($bannerData['hasNoItems']){
                    redirect($vendorSlug.'/about');
                }
                
                $getUserProduct = $this->getUserDefaultCategoryProducts($bannerData['arrVendorDetails']['id_member']);
                $productView['defaultCatProd'] = $getUserProduct['parentCategory'];
                
                // If searching in  page
                if($this->input->get() && !$bannerData['hasNoItems']){

                    $productView['isSearching'] = TRUE;
                    $parameter = $this->input->get();
                    $parameter['seller'] = "seller:".$memberEntity->getUsername();
                    $parameter['limit'] = 12;
                    
                    // getting all products
                    $search = $searchProductService->getProductBySearch($parameter);
                    $searchProduct = $search['collection'];
                    $count = $search['count'];

                    $productView['defaultCatProd'][0]['name'] ='Search Result';
                    $productView['defaultCatProd'][0]['products'] = $searchProduct; 
                    $productView['defaultCatProd'][0]['non_categorized_count'] = $count;
                    $productView['defaultCatProd'][0]['json_subcat'] = "{}";

                    $paginationData = array(
                        'lastPage' => ceil($count/$this->vendorProdPerPage)
                        ,'isHyperLink' => false
                    );
                    $productView['defaultCatProd'][0]['pagination'] = $this->load->view('pagination/default', $paginationData, true);

                    $view = array(
                        'arrCat' => array(
                            'products'=>$searchProduct,
                            'page' => 1,
                            'pagination' => $productView['defaultCatProd'][0]['pagination'],
                        )
                    );
                    $productView['defaultCatProd'][0]['product_html_data'] = $this->load->view("pages/user/display_product", $view, true);
                }

                //HEADER DATA
                $storeColor = $memberEntity->getStoreColor();
                $bannerData['storeColorScheme'] = $storeColor;
                $bannerData['title'] = html_escape($bannerData['arrVendorDetails']['store_name'])." | Easyshop.ph";
                $headerData['metadescription'] = html_escape($bannerData['arrVendorDetails']['store_desc']);
                $headerData['relCanonical'] = base_url().$vendorSlug;
                $bannerData['isLoggedIn'] = $headerData['logged_in'];
                $bannerData['vendorLink'] = "";

                $viewData = array(
                    "customCatProd" => [],
                    "defaultCatProd" => $productView['defaultCatProd'],
                    "product_condition" => $this->lang->line('product_condition'),
                    "isLoggedIn" => $headerData['logged_in'],
                    "prodLimit" => $this->vendorProdPerPage,
                    "storeColorScheme" => $storeColor,
                );
 
                // count the followers 
                $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                          ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 
        
                $data["followerCount"] = $EsVendorSubscribe->getFollowers($bannerData['arrVendorDetails']['id_member'])['count'];

                if(isset($productView['isSearching']) && isset($viewData['defaultCatProd'][0])){
                    $viewData['defaultCatProd'][0]['isActive'] = true;
                }
                else{
                    reset($viewData['defaultCatProd']);
                    $firstCategoryId = key($viewData['defaultCatProd']);
                    $viewData['defaultCatProd'][$firstCategoryId]['isActive'] = true;
                }

                
                // Load View
                $headerData = array_merge($headerData, $bannerData);
                $this->load->view('templates/header_alt', $headerData);
                $this->load->view('templates/vendor_banner',$bannerData);
                $this->load->view('pages/user/vendor_view', $viewData);
                $this->load->view('templates/footer_alt', ['sellerSlug' => $vendorSlug]);
            }
        }
        // Load invalid link error page
        else{
            show_404();
        }

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
        $headerData = $this->fill_header();
        $bannerData['isLoggedIn'] = $headerData['logged_in'];
        $bannerData['vendorLink'] = "about";
        $headerData['title'] = html_escape($bannerData['arrVendorDetails']['store_name'])." | Easyshop.ph";
        $headerData['metadescription'] = html_escape($bannerData['arrVendorDetails']['store_desc']);
        $headerData['relCanonical'] = base_url().$sellerslug.'/followers';

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
                                               ->findOneBy(['slug' => $sellerslug])
                                               ->getStoreColor();
        
        $followerData['followerCount'] = $bannerData["followerCount"];
        $followerData['storeName'] = strlen($bannerData['arrVendorDetails']['store_name']) > 0 ? $bannerData['arrVendorDetails']['store_name'] : $bannerData['arrVendorDetails']['username'];
        $followerData['followers'] = $followers['followers'];
        $followerData['isLoggedIn'] = $headerData['logged_in'] ? TRUE : FALSE;
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
        $headerData = array_merge($headerData, $bannerData);
        $this->load->view('templates/header_alt', $headerData);
        $this->load->view('templates/vendor_banner',$bannerData);
        $this->load->view('pages/user/followers' ,$followerData);
        $this->load->view('templates/footer_alt', ['sellerSlug' => $sellerslug]);
    }

    public function getMoreFollowers()
    {
        $EsVendorSubscribe = $this->serviceContainer['entity_manager']
                                    ->getRepository('EasyShop\Entities\EsVendorSubscribe'); 
        $data = $this->fill_header();
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
        $followerData['isLoggedIn'] = $data['logged_in'] ? TRUE : FALSE;
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
     *  Fetch Default categories and initial products for first load of page.
     *
     *  @return array
     */
    private function getUserDefaultCategoryProducts($memberId, $catType = "default")
    {
        $em = $this->serviceContainer['entity_manager'];
        $categoryManager = $this->serviceContainer['category_manager'];
        $prodLimit = $this->vendorProdPerPage;

        switch($catType){
            case "custom":
                $parentCat = $categoryManager->getAllUserProductCustomCategory($memberId);
                break;
            default:
                $parentCat = $categoryManager->getAllUserProductParentCategory($memberId);
                break;
        }

        $categoryProductCount = array();
        $totalProductCount = 0; 

        foreach( $parentCat as $idCat=>$categoryProperties ){ 
            $result = $categoryManager->getVendorDefaultCategoryAndProducts($memberId, $categoryProperties['child_cat'], $catType);
            
            if( (int)$result['filtered_product_count'] === 0){
                unset($parentCat[$idCat]);
                break;
            }

            $parentCat[$idCat]['products'] = $result['products'];
            $parentCat[$idCat]['non_categorized_count'] = $result['filtered_product_count']; 
            $totalProductCount += count($result['products']);
            $parentCat[$idCat]['json_subcat'] = json_encode($categoryProperties['child_cat'], JSON_FORCE_OBJECT);
            $parentCat[$idCat]['hasMostProducts'] = false; 
            $categoryProductCount[$idCat] = count($result['products']);
            
            // Generate pagination view
            $paginationData = array(
                'lastPage' => ceil($result['filtered_product_count']/$this->vendorProdPerPage)
                ,'isHyperLink' => false
            );
            $parentCat[$idCat]['pagination'] = $this->load->view('pagination/default', $paginationData, true);

            $view = array(
                'arrCat' => array(
                    'products'=>$result['products'],
                    'page' => 1,
                    'pagination' => $parentCat[$idCat]['pagination'],
                )
            );

            $parentCat[$idCat]['product_html_data'] = $this->load->view("pages/user/display_product", $view, true);
        }
        
        $categoryWithMostProducts = reset(array_keys($categoryProductCount, max($categoryProductCount)));
        $parentCat[$categoryWithMostProducts]['hasMostProducts'] = true;

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
        $limit = $this->feedbackPerPage;
        $this->lang->load('resources');

        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->findOneBy(['slug' => $sellerslug]);                                

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

        $viewerId = intval(!$this->session->userdata('member_id') ? 0 : $this->session->userdata('member_id'));
        
        $orderRelations = array();
        
        if($viewerId !== 0){
            $orderRelations = $this->serviceContainer['entity_manager']
                                   ->getRepository('EasyShop\Entities\EsOrder')
                                   ->getOrderRelations($viewerId, $idMember, true);
        }
        $bannerData = $this->generateUserBannerData($sellerslug, $viewerId);
        $headerData = $this->fill_header();
        
        $bannerData['storeColorScheme'] = $member->getStoreColor();
        $bannerData['isLoggedIn'] = $headerData['logged_in'];
        $bannerData['vendorLink'] = "about";
        $headerData['title'] = html_escape($bannerData['arrVendorDetails']['store_name'])." | Easyshop.ph";
        $headerData['metadescription'] = html_escape($bannerData['arrVendorDetails']['store_desc']);
        $headerData['relCanonical'] = base_url().$sellerslug.'/about';
        $userDetails = $this->userDetails($sellerslug, 'about',  $bannerData['stateRegionLookup'], $bannerData['cityLookup']);

        $headerData = array_merge($headerData, $bannerData);
        $this->load->view('templates/header_alt', $headerData);
        $this->load->view('templates/vendor_banner', $bannerData);
        $this->load->view('pages/user/about', ['feedbackSummary' => $feedbackSummary,
                                               'ratingHeaders' => $ratingHeaders,
                                               'feedbackTabs' => $feedbackTabs,
                                               'member' => $member,
                                               'viewer' => $headerData['user'],
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
        $reviewer = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find(intval($this->session->userdata('member_id')));
        $reviewee = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find(intval($this->input->post('userId')));
        $orderToReview = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsOrder')
                                                           ->find(intval($this->input->post('feeback-order')));   
        $rating1 = (int) $this->input->post('rating1');
        $rating2 = (int) $this->input->post('rating2');
        $rating3 = (int) $this->input->post('rating3');
        $areAllRatingsSet = $rating1 > 0 && $rating2 > 0 && $rating3 > 0;
        $message = $this->input->post('feedback-message');
                
        if($reviewer && $reviewee && $orderToReview && strlen($message) > 0 && $areAllRatingsSet){
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
            $feedback->setRating1($rating1);
            $feedback->setRating2($rating2);
            $feedback->setRating3($rating3);
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
       
        $headerData = $this->fill_header();
        $viewerId = $this->session->userdata('member_id');
        $bannerData = $this->generateUserBannerData($sellerslug, $viewerId);
        $bannerData['isLoggedIn'] = $headerData['logged_in'];
        
        // assign header_vendor data
        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                   ->findOneBy(['slug' => $sellerslug]);                                  
        $bannerData['storeColorScheme'] = $member->getStoreColor();
        $headerData['title'] = 'Contact '.$bannerData['arrVendorDetails']['store_name'].'| Easyshop.ph';
        $headerData['metadescription'] = html_escape($bannerData['arrVendorDetails']['store_desc']);
        $headerData['relCanonical'] = base_url().$sellerslug.'/contact';
        $bannerData['vendorLink'] = "contact";
        $headerData['message_recipient'] = $member;
        $userDetails = $this->userDetails($sellerslug, 'contact',  $bannerData['stateRegionLookup'], $bannerData['cityLookup']);

        $headerData = array_merge($headerData, $bannerData);
        $this->load->view('templates/header_alt', $headerData);
        $this->load->view('templates/vendor_banner',$bannerData);
        $this->load->view('pages/user/contact', ['userDetails' => $userDetails]);
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
                , "hasAddress" => strlen($arrVendorDetails['stateregionname']) > 0 && strlen($arrVendorDetails['cityname']) > 0 ? TRUE : FALSE 
                , "avatarImage" => $this->serviceContainer['user_manager']->getUserImage($sellerId)
                , "bannerImage" => $this->serviceContainer['user_manager']->getUserImage($sellerId,"banner")
                , "isEditable" => ($viewerId && intval($sellerId) === intval($viewerId)) ? TRUE : FALSE
                , "hasNoItems" => (count($userProduct) > 0) ? FALSE : TRUE
                , "subscriptionStatus" => $this->serviceContainer['user_manager']->getVendorSubscriptionStatus($viewerId, $arrVendorDetails['username'])
                , "followerCount" => $followers['count']
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

        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                               ->findOneBy(['slug' => $sellerslug]);

        $data['validatedStoreName'] = $data['storeName'] = $member->getStoreName() === "" || $member->getStoreName() === null ? $member->getUsername() : $member->getStoreName();
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
            $data['validatedCity'] = $data['city'] = $addr->getCity()->getLocation(). ", ";
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

            $contactNumberConstraint = $contactNumber === $data['validatedContactNo'] ? array() :  array('constraints' => $rules['contact_number']);
            $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                ->setMethod('POST')
                                ->add('shop_name', 'text', array('constraints' => $rules['shop_name']))
                                ->add('contact_number', 'text', $contactNumberConstraint)
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
            
            if($form->isValid() && $isAddressValid && $data['isEditable']){
                $formData = $form->getData();
                $formData['region'] = $formData['region'] === null ? $formData['region'] : $regionList[intval($formData['region'])];
                
                $member->setStoreName($formData['shop_name']);
                $member->setContactno(substr($formData['contact_number'], 1));
                $member->setWebsite($formData['website']);
                $member->setLastmodifieddate(date_create(date("Y-m-d H:i:s")));

                $addr = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsAddress')
                            ->findOneBy(['idMember' => $member->getIdMember(), 'type' => EsAddress::TYPE_DEFAULT]);

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

                        $data['validatedStreetAddr'] = "Location not set ";
                        $data['validatedCity'] = '';
                        $data['validatedRegion'] = '';
                    }
                }
                $this->serviceContainer['entity_manager']->flush();
                $data['isValid'] = true;
                $data['validatedStoreName'] = $member->getStoreName() === "" || $member->getStoreName() === null ? $member->getUsername() : $member->getStoreName();
                $data['validatedContactNo'] = $member->getContactno() === false ? "" : '0' . $member->getContactno();
                $data['validatedWebsite'] = $member->getWebsite();
            }
            else{
                $data['errors'] =  $this->serviceContainer['form_error_helper']->getFormErrors($form);
                if(!$isAddressValid){
                    $data['errors']['location'] = ["address must be complete"];
                }
            }

            $data['storeName'] = $storeName;
            $data['contactNo'] = $contactNumber;
            $data['streetAddr'] = strlen(trim($streetAddress)) > 0 ? $streetAddress . ", " : "";
            $data['city'] = $citySelect == '' ? '' : $citySelect . ", ";
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
     * AJAX REQUEST HANDLER FOR LOADING PRODUCTS W/O FILTER
     *
     * @return JSON
     */
    public function vendorLoadProducts()
    {
        $prodLimit = $this->vendorProdPerPage;
        $vendorId = $this->input->get('vendorId');
        $vendorName = $this->input->get('vendorName');
        $catId = json_decode($this->input->get('catId'), true);
        $catType = $this->input->get('catType') ?  $this->input->get('catType') : CategoryManager::CATEGORY_DEFAULT_TYPE;
        $page = $this->input->get('page');
        $rawOrderBy = intval($this->input->get('orderby'));
        $rawOrder = intval($this->input->get('order'));
        $isCount = intval($this->input->get('count')) === 1 ? TRUE : FALSE;

        $condition = $this->input->get('condition') !== "" ? $this->lang->line('product_condition')[$this->input->get('condition')] : "";
        $lprice = $this->input->get('lowerPrice') !== "" ? floatval($this->input->get('lowerPrice')) : "";
        $uprice = $this->input->get('upperPrice') !== "" ? floatval($this->input->get('upperPrice')) : "";

        $parameter = json_decode($this->input->get('queryString'),TRUE);

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
                $orderBy = array("clickcount" => $order);
                break;
            case 2:
                $orderSearch = "NEW";
                $orderBy = array("lastmodifieddate" => $order);
                break;
            case 3:
                $orderSearch = "HOT";
                $orderBy = array("isHot"=>$order, "clickcount"=>$order);
                break;
            default:
                $orderSearch = "NULL";
                $orderBy = array("lastmodifieddate"=>$order);
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
                if(is_numeric($lprice) && is_numeric($uprice)){
                    $parameter['startprice'] = $lprice;
                    $parameter['endprice'] = $uprice;
                }
                $parameter['seller'] = "seller:".$vendorName;
                $parameter['limit'] = $prodLimit;
                $parameter['page'] = $page - 1;
                $search = $searchProductService->getProductBySearch($parameter);
                $products = $search['collection']; 
                $productCount = $search['count'];;
                break;
            case CategoryManager::CATEGORY_CUSTOM_TYPE: 
                $result = $categoryManager->getVendorDefaultCategoryAndProducts($vendorId, $catId, "custom", $prodLimit, $page, $orderBy, $condition, $lprice, $uprice);
                $products = $result['products'];
                $productCount = $result['filtered_product_count'];
                break;
            case CategoryManager::CATEGORY_DEFAULT_TYPE: 
            default:
                $result = $categoryManager->getVendorDefaultCategoryAndProducts($vendorId, $catId, "default", $prodLimit, $page, $orderBy, $condition, $lprice, $uprice);
                $products = $result['products'];
                $productCount = $result['filtered_product_count'];
                break;
        }

        $arrCat = array(
            'page' => $page,
            'products' => $products
        );
        $parseData = array('arrCat'=>$arrCat);
        
        $pageCount = $productCount > 0 ? ceil($productCount/$prodLimit) : 1;

        $paginationData = array(
            'lastPage' => $pageCount
            , 'isHyperLink' => false
            , 'currentPage' => $page
        );
        $parseData['arrCat']['pagination'] = $this->load->view("pagination/default", $paginationData, true);
        $serverResponse = array(
            'htmlData' => $this->load->view("pages/user/display_product", $parseData, true)
            , 'isCount' => $isCount
            , 'pageCount' => $pageCount
            , 'paginationData' => $this->load->view("pagination/default", $paginationData, true)
        );

        echo json_encode($serverResponse);
    }
  

}


