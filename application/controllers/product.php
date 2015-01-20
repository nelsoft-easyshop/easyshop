<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

use EasyShop\Entities\EsMember as EsMember; 
use EasyShop\Entities\EsCat as EsCat; 
use EasyShop\Entities\EsProduct as EsProduct; 

class product extends MY_Controller 
{ 

    public $feeds_prodperpage = 5;
    public $per_page = 15;
    public $start_irrelevant = 0;

    public function __construct()  
    { 
        parent::__construct(); 
        $this->load->helper('htmlpurifier');
        $this->load->model("product_model"); 
        $this->load->model("messages_model");

        // Loading entity manager 
        $this->em = $this->serviceContainer['entity_manager']; 
    }

    public function loadMoreProductInCategory($categorySlug)
    {
        $searchProductService = $this->serviceContainer['search_product'];

        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $categoryDetails = $EsCatRepository->findOneBy(['slug' => $categorySlug]);
        $categoryId = $categoryDetails->getIdCat(); 
        $getParameter = $this->input->get() ? $this->input->get() : array();
        $getParameter['category'] = $EsCatRepository->getChildCategoryRecursive($categoryId,TRUE);
        $search = $searchProductService->getProductBySearch($getParameter); 
        $response['products'] = $search['collection'];

        $response['typeOfView'] = trim($this->input->get('typeview'));
        $data['view'] = $this->load->view('pages/search/product_search_by_searchbox_more',$response,TRUE);
        $data['count'] = count($response['products']);
        echo json_encode($data);
    }

    /**     
     *  Displays products in each category
     *
     *  @return View
     */
    public function categoryPage($categorySlug)
    {
        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager'];
        
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');
        $categoryDetails = $EsCatRepository->findOneBy(['slug' => $categorySlug]);

        if($categoryDetails){
            $categoryName = $categoryDetails->getName(); 
            $categoryId = $categoryDetails->getIdCat(); 
            $categoryDescription = $categoryDetails->getDescription();
            
            $response['getParameter'] = $getParameter = $this->input->get() ? $this->input->get() : [];
            $getParameter['category'] = $EsCatRepository->getChildCategoryRecursive($categoryId, true);
            $subCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->findBy(['parent' => $categoryId]);

            $subCategoryList = $searchProductService->getPopularProductOfCategory($subCategory);

            $search = $searchProductService->getProductBySearch($getParameter);
            $response['products'] = $search['collection'];
            $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);

            $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                ->findBy(['parent' => 1]);
 
            $response['subCategoryList'] = $subCategoryList;
            $response['categorySlug'] = $categorySlug;

            $protectedCategory = $categoryManager->applyProtectedCategory($parentCategory, false);

            $response['parentCategory'] = $categoryManager->setCategoryImage($protectedCategory);
            $response['category_navigation_desktop'] = $this->load->view('templates/category_navigation_responsive',
                    array('parentCategory' =>  $response['parentCategory'],
                        'environment' => 'desktop'), true );

            $response['category_navigation_mobile'] = $this->load->view('templates/category_navigation_responsive',
                    array('parentCategory' =>  $response['parentCategory'],
                        'environment' => 'mobile'), true );

            $response['breadcrumbs'] = $this->em->getRepository('EasyShop\Entities\EsCat')
                                        ->getParentCategoryRecursive($categoryId);

            $data = array( 
                'title' => es_string_limit(html_escape($categoryName), 60, '...', ' | Easyshop.ph'),
                'metadescription' => es_string_limit(html_escape($categoryDescription), 60),
                'relCanonical' => base_url().'category/'.$categorySlug ,
                ); 
            $data = array_merge($data, $this->fill_header());

            $this->load->view('templates/header', $data); 
            $this->load->view('pages/product/product_search_by_category_final_responsive', $response);
            $this->load->view('templates/footer'); 
        }
        else{ 
            redirect('cat/all', 'refresh');
        }

    }

    /**
     * Assemble SEO Review tags
     *
     * @param array $data
     * @return JSON
     */
    public function assembleJsonReviewSchemaData($data)
    {
        $productQuantity = false;
        // Check for product availability
        foreach($data['product_quantity'] as $pq){
            if($pq['quantity'] > 0){
                $productQuantity = true;
                break;
            }
        }
        $jsonReviewSchemaData = array(
            '@context' => 'http://schema.org',
            '@type' => 'Product',
            'description' => html_escape($data['product']['brief']),
            'name' => html_escape($data['product']['product_name']),
            'offers' => array(
                '@type' => 'Offer',
                'availability' => 'http://schema.org/' . $productQuantity ? 'InStock':'OutOfStock',
                'price' => 'Php' . $data['product']['price']
                ),
            'review' => array()
            );
        foreach($data['reviews'] as $review){
            $arrReview = array(
                '@type' => 'Review',
                'author' => $review['reviewer'],
                'datePublished' => $review['ISOdate'],
                'name' => html_escape($review['title']),
                'reviewBody' => html_escape($review['review']),
                'reviewRating' => array(
                    '@type' => 'Rating',
                    'bestRating' => '5',
                    'ratingValue' => $review['rating'],
                    'worstRating' => '0'
                    )
                );
            array_push($jsonReviewSchemaData['review'], $arrReview);
        }
        return json_encode( $jsonReviewSchemaData, JSON_UNESCAPED_SLASHES );
    }

    /**
     * Renders view for the list of all categories
     *
     * @return View
     */
    public function categories_all() 
    {
        $categories = $this->product_model->getFirstLevelNode(false, true);
        foreach($categories as $index=>$category){
            $categories[$index]['subcategories'] = $this->product_model->getDownLevelNode($category['id_cat']);
            foreach($categories[$index]['subcategories'] as $inner_index=>$subcategory){

                $down_cat = $this->product_model->selectChild($subcategory['id_cat']);      
                if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
                    $down_cat = array();
                array_push($down_cat, $subcategory['id_cat']);
                $categories[$index]['subcategories'][$inner_index]['product_count'] = $this->product_model->getProductCount($down_cat)['product_count'];
            }
        }

        $data = array( 
            'title' => 'Easyshop.ph - All Categories',  
            'categories' => $categories
            ); 
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data); 
        $this->load->view('pages/product/all_categories_view', $data); 

        $socialMediaLinks = $this->getSocialMediaLinks();
        $viewData['facebook'] = $socialMediaLinks["facebook"];
        $viewData['twitter'] = $socialMediaLinks["twitter"];

        $this->load->view('templates/footer_full', $viewData);     
    }

    /**
     * Updates the delete status of a product
     *
     */
    public function changeDelete()
    {
        if($this->input->post('p_id') && $this->input->post('action')){
            $memberid = $this->session->userdata('member_id');
            $productid = $this->input->post('p_id');
            $action = $this->input->post('action');
            if($action === 'delete')
                $this->product_model->updateIsDelete($productid, $memberid, 1);
            else if($action === 'restore')
                $this->product_model->updateIsDelete($productid, $memberid, 0);
            else if($action === 'fulldelete')
                $this->product_model->updateIsDelete($productid, $memberid, 2);
        }
        redirect('me', 'refresh');
    }

    /**
     * Renders product page view
     *  
     * @return View
     */
    public function item($itemSlug = '')
    {
        $headerData = $this->fill_header(); 

        $productManager = $this->serviceContainer['product_manager'];
        $cartManager = $this->serviceContainer['cart_manager'];
        $userManager = $this->serviceContainer['user_manager'];
        $collectionHelper = $this->serviceContainer['collection_helper'];
        $reviewProductService = $this->serviceContainer['review_product_service'];
        $stringUtility = $this->serviceContainer['string_utility'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $productEntity = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                  ->findOneBy(['slug' => $itemSlug, 'isDraft' => 0, 'isDelete' => 0]); 
        $viewerId =  $this->session->userdata('member_id');
        
        
        
        if($productEntity){
            $member = $productEntity->getMember();
            if($member->getIsActive() && !$member->getIsBanned()){
                if($viewerId){
                    if((int)$viewerId !== $member->getIdMember()){
                        $productEntity->setClickcount($productEntity->getClickcount() + 1);
                        $this->em->flush();
                    }
                }
                $productId = $productEntity->getIdProduct();
                $categoryId = $productEntity->getCat()->getIdCat();

                $product = $productManager->getProductDetails($productEntity);
                $breadcrumbs = $this->em->getRepository('EasyShop\Entities\EsCat')
                                        ->getParentCategoryRecursive($categoryId);

                $avatarImage = $userManager->getUserImage($member->getIdMember());
                
                $productImages = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                        ->getProductImages($productId);
                $imagesView =  $this->load->view('pages/product/product_image_gallery',['images'=>$productImages],true);

                $productAttributeDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                                    ->getProductAttributeDetailByName($productId);
                $productAttributes = $collectionHelper->organizeArray($productAttributeDetails,true,true);
                $shippingLocation = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                            ->getLocation();

                $isFreeShippingNationwide = $productManager->isFreeShippingNationwide($productId);

                $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                            ->getShippingDetailsByProductId($productId);

                $productCombinationAvailable = $productManager->getProductCombinationAvailable($productId);
                $productCombination = $productCombinationAvailable['productCombinationAvailable'];
                $filterAttributes = $productManager->separateAttributesOptions($productAttributes);
                $additionalInformation = $filterAttributes['additionalInformation'];
                $productAttributes = $filterAttributes['productOptions'];
                $noMoreSelection = $productCombinationAvailable['noMoreSelection'];
                $needToSelect = $productCombinationAvailable['needToSelect'];
                $bannerView = "";
                $paymentMethod = $this->config->item('Promo')[0]['payment_method'];
                $isBuyButtonViewable = true;

                if((int) $product->getIsPromote() === EsProduct::PRODUCT_IS_PROMOTE_ON && (!$product->getEndPromo())){
                    $bannerfile = $this->config->item('Promo')[$product->getPromoType()]['banner'];
                    if($bannerfile){
                        $bannerView = $this->load->view('templates/promo_banners/'.$bannerfile, ['product' => $product], true); 
                    }
                    $paymentMethod = $this->config->item('Promo')[$product->getPromoType()]['payment_method'];
                    $isBuyButtonViewable = $this->config->item('Promo')[$product->getPromoType()]['viewable_button_product_page'];
                    if( $product->getIsDelete() ) {
                        show_404();
                    }
                }

                $canPurchase = $cartManager->canBuyerPurchaseProduct($product,$viewerId);

                $productDescription = $stringUtility->purifyHTML($product->getDescription());

                $productReviews = $reviewProductService->getProductReview($productId);
                $canReview = $reviewProductService->checkIfCanReview($viewerId,$productId); 

                $reviewDetailsData = [
                            'productDetails' => $productDescription,
                            'productAttributes' => $productAttributes,
                            'productReview' => $productReviews,
                            'canReview' => $canReview,
                            'additionalInformation' => $additionalInformation
                        ];

                $reviewDetailsView = $this->load->view('pages/product/productpage_view_review', $reviewDetailsData, true); 

                $recommendProducts = $productManager->getRecommendedProducts($productId,$productManager::RECOMMENDED_PRODUCT_COUNT);
                $recommendViewArray = [
                                    'recommended'=> $recommendProducts,
                                    'productCategorySlug' => $product->getCat()->getSlug(),
                                ];

                $recommendedView = $this->load->view('pages/product/productpage_view_recommend',$recommendViewArray,true);

                $viewData = [
                                'product' => $product,
                                'breadCrumbs' => $breadcrumbs,
                                'ownerAvatar' => $avatarImage,
                                'imagesView' => $imagesView,
                                'productAttributes' => $productAttributes,
                                'productCombinationQuantity' => json_encode($productCombination),
                                'shippingInfo' => $shippingDetails,
                                'shiploc' => $shippingLocation,
                                'paymentMethod' => $paymentMethod,
                                'isBuyButtonViewable' => $isBuyButtonViewable,
                                'isLoggedIn' => $headerData['logged_in'],
                                'viewerId' => $viewerId,
                                'canPurchase' => $canPurchase,
                                'userData' => $headerData['user'],
                                'bannerView' => $bannerView, 
                                'reviewDetailsView' => $reviewDetailsView,
                                'recommendedView' => $recommendedView,
                                'noMoreSelection' => $noMoreSelection, 
                                'needToSelect' => $needToSelect,
                                'isFreeShippingNationwide' => $isFreeShippingNationwide, 
                                'url' => base_url() .'item/' . $product->getSlug()
                            ];

                if($this->session->userdata('member_id')) {
                    $headerData['user_details'] = $this->fillUserDetails();
                }

                $briefDescription = trim($product->getBrief()) === "" ? $product->getName() :  $product->getDescription();
                $headerData['metadescription'] = es_string_limit(html_escape($briefDescription), \EasyShop\Product\ProductManager::PRODUCT_META_DESCRIPTION_LIMIT);
                $headerData['title'] = html_escape($product->getName()). " | Easyshop.ph";
                $headerData['relCanonical'] = base_url().'item/'.$itemSlug;
                $headerData['homeContent'] = $this->fillCategoryNavigation();
        
                
                $headerData = array_merge($headerData, $this->fill_header());

                $socialMediaLinks = $this->getSocialMediaLinks();
                $footerData['facebook'] = $socialMediaLinks["facebook"];
                $footerData['twitter'] = $socialMediaLinks["twitter"];

                $this->load->view('templates/header_primary', $headerData);
                $this->load->view('pages/product/productpage_primary', $viewData);
                $this->load->view('templates/footer_primary',$footerData);
            }
            else{
                show_404();
            }
        }
        else{
            show_404();
        }
    }

    /**
     * Submit reply for review
     *  
     * @return JSON
     */
    public function submitReply()
    {
        $reviewProductService = $this->serviceContainer['review_product_service'];
        $reviewerId =  $this->session->userdata('member_id');
        $productId = trim($this->input->post('product_id'));
        $isSuccess = FALSE;
        $errorMessage = "";
        if(trim($this->input->post('review'))){
            $canReview = $reviewProductService->checkIfCanReview($reviewerId,$productId); 
            if($canReview){
                $reply = $reviewProductService->submitReview($reviewerId,$this->input->post());
                $response['html'] = $this->load->view('partials/review_reply', $reply, true);
                $isSuccess = $isSuccess = TRUE; 
            }
            else{
                $errorMessage = "You can't submit reply on this product.";
            }
        }
        else{
            $errorMessage = "Reply cannot be empty!";
        }

        $response['isSuccess'] = $isSuccess;
        $response['error'] = $errorMessage;

        echo json_encode($response);
    }

    /**
     * Submit review for individual product
     * @return JSON
     */
    public function submitReview()
    {
        $reviewProductService = $this->serviceContainer['review_product_service'];
        $reviewerId =  $this->session->userdata('member_id');
        $productId = trim($this->input->post('product_id'));
        $isSuccess = FALSE;
        $errorMessage = "";
        if(trim($this->input->post('review'))){
            $canReview = $reviewProductService->checkIfCanReview($reviewerId,$productId); 
            if($canReview){
                $reply = $reviewProductService->submitReview($reviewerId,$this->input->post());
                $response['html'] = $this->load->view('partials/review_review', $reply, true);
                $isSuccess = TRUE;
            }
            else{
                $errorMessage = "You can't submit review on this product.";
            }
        }
        else{
            $errorMessage = "Reply cannot be empty!";
        }

        $response['isSuccess'] = $isSuccess;
        $response['error'] = $errorMessage;

        echo json_encode($response);
    }

    /**
     * Renders view for the promo page
     *  
     * @return View
     */
    public function category_promo()
    {
        $this->load->config('protected_category', TRUE);
        $category_id = $this->config->item('promo', 'protected_category');
        $this->load->library('xmlmap');
        $data = $this->fill_header();
        $data['title'] = 'Deals | Easyshop.ph';
        $data['metadescription'] = 'Get the best price offers for the day at Easyshop.ph.';
        
        $banner_data = array();
        $view_data['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data, TRUE);
        #$view_data['items'] = $this->product_model->getProductsByCategory($category_id,array(),0,"<",0,$this->per_page);
        $view_data['items'] = $this->product_model->getProductsByCategory($category_id,array(),0,"<",0,PHP_INT_MAX);
        #PEAK HOUR PROMO ,To activate: change deals_banner = easydeals
        #$categoryId = $this->config->item('peak_hour_promo', 'protected_category');
        #$view_data['peak_hour_items'] =$this->product_model->getProductsByCategory($categoryId,array(),0,"<",0,PHP_INT_MAX,'createddate ASC,');

        $this->load->view('templates/header', $data); 
        $this->load->view('pages/product/product_promo_category', $view_data); 
        $this->load->view('templates/footer');
    }

    /**
     * Renders page for post and win promo
     *  
     * @return View
     */
    public function post_and_win_promo()
    {
        $data = $this->fill_header();
        $data['title'] = 'Post and Win | Easyshop.ph';

        $socialMediaLinks = $this->getSocialMediaLinks();
        $socialData['facebook'] = $socialMediaLinks["facebook"];
        $socialData['twitter'] = $socialMediaLinks["twitter"];

        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/post_and_win_view', $socialData);
        $this->load->view('templates/footer');
    }

    /**
     * Checks the status of a particular user for the post and win promo
     *  
     * @return JSON
     */
    public function PromoStatusCheck()
    {
        $this->load->model('user_model');
        $username = $this->input->post('username');
        $query_result = $this->user_model->getUserByUsername($username);
        if(isset($query_result['is_promo_valid'])){
            echo json_encode(intval($query_result['is_promo_valid']));
        }else{
            echo json_encode(3);
        }
        #return 1 if account has promo = true (QUALIFIED)
        #return 2 if account has promo = false (PENDING)
        #return 3 if username doesnt exist (NOT-QUALIFIED)
    }

    /**
     *  Function to handle isDelete field in es_product
     *  Handles delete, restore, and remove (full delete) functions for products
     *  in memberpage
     *
     *  @return JSON
     */
    public function bulkProductOptions()
    {
        if( $this->input->post('bulk_action') ){
            $pm = $this->serviceContainer['product_manager'];

            $arrProductId = json_decode($this->input->post('bulk_p_id'), true);
            $memberId = $this->session->userdata('member_id');
            $action = $this->input->post('bulk_action');

            $pm->editBulkIsDelete($arrProductId, $memberId, $action);

            redirect('me', 'refresh');
        }
    }

}


/* End of file product.php */
/* Location: ./application/controllers/product.php */
