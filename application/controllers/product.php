<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

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
        // Loading Services
        $searchProductService = $this->serviceContainer['search_product'];

        // Loading Repositories
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        // Getting category details by slug
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
        header ('Content-type: text/html; charset=ISO-8859-1');
        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager'];
        
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');
        $categoryDetails = $EsCatRepository->findOneBy(['slug' => $categorySlug]);

        if($categoryDetails){
            $categoryName = $categoryDetails->getName(); 
            $categoryId = $categoryDetails->getIdCat(); 
            $categoryDescription = $categoryDetails->getDescription();
            
            $getParameter = $this->input->get() ? $this->input->get() : array();
            $getParameter['category'] = $EsCatRepository->getChildCategoryRecursive($categoryId,TRUE);
            $subCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->findBy(['parent' => $categoryId]);

            $subCategoryList = $searchProductService->getPopularProductOfCategory($subCategory);

            // get all product available
            $search = $searchProductService->getProductBySearch($getParameter);
            $response['products'] = $search['collection'];
            
            // get all attributes to by products
            $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);

            $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                ->findBy(['parent' => 1]);
 
            $response['subCategoryList'] = $subCategoryList;
            $response['categorySlug'] = $categorySlug;

            // Apply protected category
            $protectedCategory = $categoryManager->applyProtectedCategory($parentCategory, FALSE);

             // Set image in every category
            $response['parentCategory'] = $categoryManager->setCategoryImage($protectedCategory);

            // category navigation of desktop version
            $response['category_navigation_desktop'] = $this->load->view('templates/category_navigation_responsive',
                    array('parentCategory' =>  $response['parentCategory'],
                        'environment' => 'desktop'), TRUE );

            // category navigation of mobile version
            $response['category_navigation_mobile'] = $this->load->view('templates/category_navigation_responsive',
                    array('parentCategory' =>  $response['parentCategory'],
                        'environment' => 'mobile'), TRUE );

            $response['breadcrumbs'] = $this->em->getRepository('EasyShop\Entities\EsCat')
                                        ->getParentCategoryRecursive($categoryId);

            $data = array( 
                'title' => es_string_limit(html_escape($categoryName), 60, '...', ' | Easyshop.ph'),
                'metadescription' => es_string_limit(html_escape($categoryDescription), 60),
                ); 
            $data = array_merge($data, $this->fill_header());

            // Load view
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
        $this->load->view('templates/footer_full');     
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
    public function newItem($itemSlug = '')
    {
        header ('Content-type: text/html; charset=ISO-8859-1');
        // Load Header
        $headerData = $this->fill_header(); 
        $headerData['title'] = " | Easyshop.ph";
        $this->load->view('templates/header_new', $headerData); 
        // Load Services
        $productManager = $this->serviceContainer['product_manager'];
        $cartManager = $this->serviceContainer['cart_manager'];
        $userManager = $this->serviceContainer['user_manager'];
        $collectionHelper = $this->serviceContainer['collection_helper'];
        $reviewProductService = $this->serviceContainer['review_product_service'];

        // Load Product Section
        // check of slug exist
        $productEntity = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findOneBy(['slug' => $itemSlug]);

        // user id of the viewer
        $viewerId =  $this->session->userdata('member_id');

        if($productEntity){

            // variables
            $productId = $productEntity->getIdProduct();
            $categoryId = $productEntity->getCat()->getIdCat();

            // get product details
            $product = $productManager->getProductDetails($productEntity);

            // generate bread crumbs category of product
            $breadcrumbs = $this->em->getRepository('EasyShop\Entities\EsCat')
                                                ->getParentCategoryRecursive($categoryId);

            // get owner avatar image of the product
            $avatarImage = $userManager->getUserImage($product->getMember()->getIdMember());
            
            // get all images of the product
            $productImages = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                        ->getProductImages($productId);
            $imagesView =  $this->load->view('pages/product/product_image_gallery',['images'=>$productImages],TRUE);

            // getting attributes
            $productAttributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                          ->getProductAttributeDetailByName($productId);
            $productAttributes = $collectionHelper->organizeArray($productAttributes,true,true);

            // get combination quantity
            $productQuantityObject = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                                    ->getProductInventoryDetail($productId);

            // get product shipping location
            $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                ->getShippingDetailsByProductId($product->getIdProduct());

            $shippingLocation = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                            ->getLocation();

            $finalCombinationQuantity = [];
            foreach ($productQuantityObject as $key => $value) {
                if(!array_key_exists($value['id_product_item'],$finalCombinationQuantity)){

                    $locationArray = [];
                    foreach ($shippingDetails as $shipKey => $shipValue) {
                        if(intval($shipValue['product_item_id']) === intval($value['id_product_item'])){
                            $locationArray[] = array(
                                    'location_id' => $shipValue['location_id'],
                                    'price' => $shipValue['price'],
                                );
                        }
                    }

                    $finalCombinationQuantity[$value['id_product_item']] = array(
                        'quantity' => $value['quantity'],
                        'product_attribute_ids' => [$value['product_attr_id']],
                        'location' => $locationArray,
                    );
                }
                else{
                    $finalCombinationQuantity[$value['id_product_item']]['product_attribute_ids'][] = $value['product_attr_id'];
                }
            }

            $totallyFreeShipping = FALSE;
            // check if totally free shipping
            if(count($finalCombinationQuantity) === 1 
            && count(array_values($finalCombinationQuantity)[0]['location']) === 1 
            && intval(array_values($finalCombinationQuantity)[0]['location'][0]['location_id']) === 1 
            && floatval(array_values($finalCombinationQuantity)[0]['location'][0]['price']) === floatval(0))
            {
                $totallyFreeShipping = TRUE; 
            }

            // check if combination available
            $noMoreSelection = "";
            if(count($productQuantityObject) <= 1 && intval($productQuantityObject[0]['product_attr_id']) === 0){
                $noMoreSelection = $productQuantityObject[0]['id_product_item'];
            }

            // get payment type available and if button is viewable
            $bannerView = "";
            $paymentMethod = $this->config->item('Promo')[0]['payment_method'];
            $isBuyButtonViewable = true;

            if(intval($product->getIsPromote()) === 1 && (!$product->getEndPromo()) ){
                $bannerfile = $this->config->item('Promo')[$product->getPromoType()]['banner'];
                if(strlen(trim($bannerfile)) > 0){
                    $bannerView = $this->load->view('templates/promo_banners/'.$bannerfile, ['product' => $product], TRUE); 
                }
                $paymentMethod = $this->config->item('Promo')[$product->getPromoType()]['payment_method'];
                $isBuyButtonViewable = $this->config->item('Promo')[$product->getPromoType()]['viewable_button_product_page'];
            }

            // check if the item can be purchase
            $canPurchase = $cartManager->canBuyerPurchaseProduct($product,$viewerId);

            // product details
            // clean product details
            $clean_desc = html_purify($product->getDescription());
            $us_ascii = mb_convert_encoding($clean_desc, 'HTML-ENTITIES', 'UTF-8');
            $doc = new DOMDocument();
            //@ = error message suppressor, just to be safe
            @$doc->loadHTML($us_ascii);
            $tags = $doc->getElementsByTagName('a');
            foreach($tags as $a){
                $a->setAttribute('rel', 'nofollow');
            }
            $productDescription = @$doc->saveHTML($doc);

            // get reviews
            $productReviews = $reviewProductService->getProductReview($productId);

            // check user if allowed to review
            $canReview = $reviewProductService->checkIfCanReview($viewerId,$productId); 

            $reviewDetailsData = array(
                        'productDetails' => $productDescription,
                        'productReview' => $productReviews,
                        'canReview' => $canReview,
                    );

            $reviewDetailsView = $this->load->view('pages/product/productpage_view_review', $reviewDetailsData, TRUE); 

            // get recommended products
            $subCategoryIds = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getChildCategoryRecursive($product->getCat()->getIdCat());
            $popularProductId = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->getPopularItem(15,0,0,$subCategoryIds);
            $recommendProducts = [];
            foreach ($popularProductId as $key) {
                $productRecommend = $productManager->getProductDetails($key);
                $productRecommend->ownerAvatar = $userManager->getUserImage($productRecommend->getMember()->getIdMember());
                $recommendProducts[] = $productRecommend;
                
                if(!$productRecommend->getDefaultImage()){
                    $productRecommend->directory = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                    $productRecommend->imageFileName = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_FILE;
                }
                else{
                    $productRecommend->directory = $productRecommend->getDefaultImage()->getDirectory();
                    $productRecommend->imageFileName = $productRecommend->getDefaultImage()->getFilename();
                }
            }

            $recommendViewArray = [
                                'recommended'=> $recommendProducts,
                                'productCategorySlug' => $product->getCat()->getSlug(),
                            ];

            $recommendedView = $this->load->view('pages/product/productpage_view_recommend',$recommendViewArray,TRUE);

            $viewData = array(
                        'product' => $product,
                        'breadCrumbs' => $breadcrumbs,
                        'ownerAvatar' => $avatarImage,
                        'imagesView' => $imagesView,
                        'productAttributes' => $productAttributes,
                        'productCombinationQuantity' => json_encode($finalCombinationQuantity),
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
                        'totallyFreeShipping' => $totallyFreeShipping, 
                        'url' => '/item/' . $product->getSlug() 
                    );
            $this->load->view('pages/product/productpage_primary', $viewData);
        }
        else{
            $this->load->view('pages/general_error'); 
        }
    }

    /**
     * Submit reply for review
     *  
     * @return mixed
     */
    public function submitReply()
    {
        $reviewProductService = $this->serviceContainer['review_product_service'];
        $reviewerId =  $this->session->userdata('member_id');
        $reply = $reviewProductService->submitReview($reviewerId,$this->input->post());
        $response['html'] = $this->load->view('partials/review_reply', $reply, true);
        $response['isSuccess'] = $reply['isSuccess'];
        $response['error'] = $reply['error'];

        echo json_encode($response);
    }

    public function submitReview()
    {
        $reviewProductService = $this->serviceContainer['review_product_service'];
        $reviewerId =  $this->session->userdata('member_id');
        $reply = $reviewProductService->submitReview($reviewerId,$this->input->post());
        $response['html'] = $this->load->view('partials/review_review', $reply, true);
        $response['isSuccess'] = $reply['isSuccess'];
        $response['error'] = $reply['error'];

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
        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/post_and_win_view');
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


}


/* End of file product.php */
/* Location: ./application/controllers/product.php */
