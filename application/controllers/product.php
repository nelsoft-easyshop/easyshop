<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

use EasyShop\Entities\EsMember as EsMember; 
use EasyShop\Entities\EsCat as EsCat; 
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsSocialMediaProvider as EsSocialMediaProvider;
use EasyShop\Entities\EsProductImage as EsProductImage;
use EasyShop\Entities\EsProductItem as EsProductItem;

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

    /**
     * Load more product in category page
     * @param  string $categorySlug
     * @return view
     */
    public function loadMoreProductInCategory($categorySlug)
    {
        $searchProductService = $this->serviceContainer['search_product'];
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $categoryDetails = $EsCatRepository->findOneBy(['slug' => $categorySlug]);
        $categoryId = $categoryDetails->getIdCat(); 

        $getParameter = $this->input->get() ? $this->input->get() : [];
        $getParameter['category'] = $categoryId;
        $search = $searchProductService->getProductBySearch($getParameter);
        $typeOfView = trim($this->input->get('typeview'));
        $currentPage = (int) $this->input->get('page'); 
        $productViewData = [
            'products' => $search['collection'],
            'currentPage' => $currentPage + 1,
            'isListView' => $typeOfView === 'list',
        ];
        $data['view'] = $this->load->view('partials/search-products', $productViewData, true); 
        $data['count'] = count($search['collection']);
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
         
        $esCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');
        $categoryDetails = $esCatRepository->findOneBy(['slug' => $categorySlug]);

        if($categoryDetails){
            $response['categoryName'] = $categoryDetails->getName(); 
            $categoryId = $categoryDetails->getIdCat(); 
            $categoryDescription = $categoryDetails->getDescription();
            
            $categoryHeaderData =  $this->serviceContainer['xml_cms']
                                        ->getCategoryPageHeader($categorySlug);  
            
            $response['getParameter'] = $getParameter = $this->input->get() ? $this->input->get() : [];
            $getParameter['category'] = $categoryId; 
            $search = $searchProductService->getProductBySearch($getParameter);
            $response['products'] = $search['collection'];
            $response['productCount'] = $search['count'];
            $response['totalPage'] = ceil($search['count'] / $searchProductService::PER_PAGE);
            $response['isListView'] = isset($_COOKIE['view']) && (string)$_COOKIE['view'] === "list";
            $response['productView'] = '';
            if($search['count'] > 0){
                $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);
                $response['availableCondition'] = [];
                if(isset($response['attributes']['Condition'])){
                    $response['availableCondition'] = $response['attributes']['Condition'];
                    unset($response['attributes']['Condition']);
                }
                $response['categoryHeaderData'] = $categoryHeaderData;
                $productViewData = [
                    'products' => $search['collection'],
                    'currentPage' => 1,
                    'isListView' => $response['isListView'],
                ];
                $response['productView']  = $this->load->view('partials/search-products', $productViewData, true);
                
                $paginationData = [
                    'totalPage' => $response['totalPage'],
                ];
                $response['pagination'] = $this->load->view('pagination/search-pagination', $paginationData, true);
                $parentCategory = $esCatRepository->findBy(['parent' => $categoryId]);
                $response['categories'] = $categoryManager->applyProtectedCategory($parentCategory, false); 
            }
            else{
                $parentCategory = $esCatRepository->findBy(['parent' => EsCat::ROOT_CATEGORY_ID]);
                $response['categories'] = $categoryManager->applyProtectedCategory($parentCategory, false); 
                
                foreach ($response['categories'] as $key => $category) {
                    $nextCategory = $esCatRepository->findBy(['parent' => $category->getIdCat()]);
                    $response['categories'][$key]->nextCategory = $categoryManager->applyProtectedCategory($nextCategory, false); 
                }

            }
            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                'title' => es_string_limit(html_escape($response['categoryName']), 60, '...', ' | Easyshop.ph'), 
                'metadescription' => es_string_limit(html_escape($categoryDescription), 60),
                'relCanonical' => base_url().'category/'.$categorySlug ,
            ];
            $ancestorCategories = $esCatRepository->getAncestorsWithNestedSet($categoryId);
            $ancestorCategories[] = $categoryId;
            $response['breadCrumbs'] = $esCatRepository->findBy(['idCat' => $ancestorCategories]);
            $response['categorySlug'] = $categorySlug; 
                
            $this->load->spark('decorator');  
            $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/product/product-search-by-category-new', $response);
        }
        else{ 
            redirect('cat/all', 'refresh');
        }

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

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Easyshop.ph - All Categories',  
        ]; 
        
        $bodyData = [
            'categories' => $categories,
        ];

        
        $this->load->spark('decorator');    
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/product/all_categories_view', $bodyData); 
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
    }


    /**
     * Renders product page view
     *  
     * @return View
     */
    public function item($itemSlug = '')
    {

        $httpRequest = $this->serviceContainer['http_request'];
        $productManager = $this->serviceContainer['product_manager'];
        $cartManager = $this->serviceContainer['cart_manager'];
        $userManager = $this->serviceContainer['user_manager'];
        $collectionHelper = $this->serviceContainer['collection_helper'];
        $reviewProductService = $this->serviceContainer['review_product_service'];
        $stringUtility = $this->serviceContainer['string_utility'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');

        $productEntity = $esProductRepo->findOneBy(['slug' => $itemSlug, 'isDraft' => 0, 'isDelete' => 0]); 
        $viewerId =  (int) $this->session->userdata('member_id');
    
        $viewer = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find($viewerId);
        if($productEntity){
            $member = $productEntity->getMember();
            if($member->getIsActive() && !$member->getIsBanned()){

                if($viewerId){
                    $isIncrease = $productManager->increaseClickCount($productEntity, $viewerId);
                    if($isIncrease){
                        $productHistoryView = new \EasyShop\Entities\EsProductHistoryView();
                        $productHistoryView->setMember($viewer);
                        $productHistoryView->setProduct($productEntity);
                        $productHistoryView->setDateViewed(date_create());
                        $productHistoryView->setIpAddress($httpRequest->getClientIp());
                        $this->em->persist($productHistoryView);
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
                $productAttributes = $filterAttributes['productOptions'];
                $noMoreSelection = $productCombinationAvailable['noMoreSelection'];
                $needToSelect = $productCombinationAvailable['needToSelect'];
                $bannerView = "";
                $paymentMethod = $this->config->item('Promo')[0]['payment_method'];
                $isBuyButtonViewable = true;

                if((int) $product->getIsPromote() === EsProduct::PRODUCT_IS_PROMOTE_ON && (!$product->getEndPromo())){
                    $bannerfile = $this->config->item('Promo')[$product->getPromoType()]['banner'];
                    $externalLink = $this->em->getRepository('EasyShop\Entities\EsProductExternalLink')
                                             ->getExternalLinksByProductId($productEntity->getIdProduct());
                    if ($bannerfile) {
                        $bannedData = [
                            'product' => $product,
                            'externalLink' => $externalLink
                        ];
                        $bannerView = $this->load->view('templates/promo_banners/'.$bannerfile, $bannedData, true);
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
                    'product' => $product,
                    'viewerId' => $viewerId,
                ];
                $reviewDetailsView = $this->load->view('pages/product/productpage_view_review', $reviewDetailsData, true); 
                $recommendProducts = $productManager->getRecommendedProducts($productId,$productManager::RECOMMENDED_PRODUCT_COUNT);
                $recommendViewArray = [
                    'recommended'=> $recommendProducts,
                    'productCategorySlug' => $product->getCat()->getSlug(),
                ];
                $recommendedView = $this->load->view('pages/product/productpage_view_recommend',$recommendViewArray,true);

                $snipperMarkUpData = [
                    'product' => $product,
                    'breadCrumbs' => $breadcrumbs,
                    'reviewCount' => count($productReviews),
                    'averageRating' => $esProductRepo->getProductAverageRating($productId),
                ];
                $snippetMarkUp = $this->load->view('templates/seo/product_markup', $snipperMarkUpData, true);

                $bodyData = [
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
                    'isLoggedIn' => $this->session->userdata('usersession'),
                    'viewerId' => $viewerId,
                    'canPurchase' => $canPurchase,
                    'viewer' => $viewer,
                    'bannerView' => $bannerView,
                    'reviewDetailsView' => $reviewDetailsView,
                    'recommendedView' => $recommendedView,
                    'noMoreSelection' => $noMoreSelection, 
                    'needToSelect' => $needToSelect,
                    'isFreeShippingNationwide' => $isFreeShippingNationwide, 
                    'url' => base_url() .'item/' . $product->getSlug(),
                    'snippetMarkUp' => $snippetMarkUp,
                ];

                $briefDescription = trim($product->getBrief()) === "" ? $product->getName() :  $product->getBrief();

                $headerData = [
                    "memberId" => $this->session->userdata('member_id'),
                    'title' =>  html_escape($product->getName()). " | Easyshop.ph",
                    'metadescription' => es_string_limit(html_escape($briefDescription), \EasyShop\Product\ProductManager::PRODUCT_META_DESCRIPTION_LIMIT),
                    'relCanonical' => base_url().'item/'.$itemSlug,
                ];

                $this->load->spark('decorator');    
                $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
                $this->load->view('pages/product/productpage_primary', $bodyData); 
                $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
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
            $isAllowed = false;
            if($this->input->post('parent_review')){
                $reviewId = (int) $this->input->post('parent_review');
                $isAllowed = $reviewProductService->isReviewReplyAllowed($reviewerId,$reviewId);
            }
            else{
                $isAllowed = $reviewProductService->checkIfCanReview($reviewerId,$productId);
            }

            if($isAllowed){
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
     * Renders page for post and win promo
     *  
     * @return View
     */
    public function post_and_win_promo()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Post and Win | Easyshop.ph',
        ];

        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $socialData['facebook'] = $socialMediaLinks["facebook"];
        $socialData['twitter'] = $socialMediaLinks["twitter"];

        $this->load->spark('decorator');    
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/promo/post_and_win_view', $socialData);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
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
     * request view for express edit
     * @return view in json type
     */
    public function requestProductExpressEdit()
    {
        $productManager = $this->serviceContainer['product_manager'];
        $collectionHelper = $this->serviceContainer['collection_helper'];
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');

        $memberId = $this->session->userdata('member_id');
        $slug = trim($this->input->post('slug')); 
        $eachAttribute = [];
        $hasCombination = true;
        $soloQuantity = 0;

        $product = $esProductRepo->findOneBy([
            'slug' => $slug,
            'member' => $memberId,
            'isDraft' => EsProduct::ACTIVE,
            'isDelete' => EsProduct::ACTIVE,
        ]);

        if($product){
            $product = $productManager->getProductDetails($product);
            $productAttributes = $esProductRepo->getAttributesByProductIds($product->getIdProduct());
            $productAttributes = $collectionHelper->organizeArray($productAttributes, true); 
            foreach ($productAttributes as $attrKey => $attrValue) { 
                foreach ($attrValue as $key => $value) {
                    $eachAttribute[$value['detail_id']] = $value['value'];  
                 } 
            }

            $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                     ->getDefaultImage($product->getIdProduct());
            $product->directory = $productImage->getDirectory();
            $product->imageFileName = $productImage->getFilename();

            $combination = $productManager->getProductCombinationAvailable($product->getIdProduct());
            $soloProductItemId = $combination['noMoreSelection'];
            if(trim($soloProductItemId) !== ""){
                $hasCombination = false;
                $soloQuantity = $combination['productCombinationAvailable'][$soloProductItemId]['quantity'];
            }

            $viewData = [
                'product' => $product,
                'productAttributes' => $eachAttribute,
                'productCombination' => $combination['productCombinationAvailable'],
                'hasCombination' => $hasCombination,
                'soloQuantity' => $soloQuantity,
                'availableStock' => $esProductRepo->getProductAvailableStocks($product->getIdProduct()),
            ]; 

            echo json_encode($this->load->view('partials/dashboard-express-edit', $viewData, true)); 
        } 
    }

    /**
     * Controller for update data in express edit
     * @return json
     */
    public function updateProductExpressEdit()
    {
        $productManager = $this->serviceContainer['product_manager'];
        $stringUtility = $this->serviceContainer['string_utility'];
        $esProductItemRepo = $this->em->getRepository('EasyShop\Entities\EsProductItem');
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $esProductItemAttrRepo = $this->em->getRepository('EasyShop\Entities\EsProductItemAttr');
        $esShippingHeadRepo = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead');
        $esShippingDetailRepo = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail');

        $memberId = $this->session->userdata('member_id');
        $slug = trim($this->input->post('slug'));
        $productName = (string) $stringUtility->removeNonUTF(trim($this->input->post('productName')));
        $productPrice = (float) str_replace(',', '',trim($this->input->post('productPrice')));
        $productDiscount = (float) trim($this->input->post('discount'));
        $soloQuantity = (int) trim($this->input->post('quantity'));
        $removeCombination = json_decode(trim($this->input->post('remove')), true);
        $retainCombination = json_decode(trim($this->input->post('retain')), true);
        $serverResponse = [
            'result' => false,
        ];
        $product = $esProductRepo->findOneBy([
            'slug' => $slug,
            'member' => $memberId,
            'isDraft' => EsProduct::ACTIVE,
            'isDelete' => EsProduct::ACTIVE,
        ]);
 
        $arrayErrorMessages = [
            "PRICE_INVALID" => "Invalid price. Product price cannot be less than 0.",
            "NAME_INVALID" => "Product name must be atleast ".EsProduct::MINIMUM_PRODUCT_NAME_LEN." characters!",
            "DISCOUNT_INVALID" => "Invalid discount. Range must be 0 - 99 only.",
            "REQUEST_INVALID" => "Invalid request.",
        ];

        try {

            if((int)$productPrice <= 0){
                throw new Exception($arrayErrorMessages['PRICE_INVALID']);
            }

            if(strlen($productName) < EsProduct::MINIMUM_PRODUCT_NAME_LEN){
                throw new Exception($arrayErrorMessages['NAME_INVALID']);
            }

            if((int)$productDiscount < 0 || (int)$productDiscount > 99){
                throw new Exception($arrayErrorMessages['DISCOUNT_INVALID']); 
            }

            if(!$product || strlen($slug) <= 0){
                throw new Exception($arrayErrorMessages['REQUEST_INVALID']); 
            }

            $product->setName($productName);
            $product->setPrice($productPrice);
            $product->setDiscount($productDiscount);
            $product->setLastmodifieddate(date_create());

            if(empty($retainCombination)){
                $combination = $productManager->getProductCombinationAvailable($product->getIdProduct());
                if(trim($combination['noMoreSelection']) !== ""){
                    $productItem = $esProductItemRepo->findOneBy([
                        'product' => $product->getIdProduct(),
                        'idProductItem' => (int) $combination['noMoreSelection']
                    ]);
                    if($productItem){
                        $soloQuantity = $soloQuantity > EsProductItem::MAX_QUANTITY 
                                        ? EsProductItem::MAX_QUANTITY 
                                        : $soloQuantity;
                        $productItem->setQuantity($soloQuantity);
                    }
                }
            }
            else{
                foreach ($retainCombination as $value) {
                    $productItem = $esProductItemRepo->findOneBy([
                        'product' => $product->getIdProduct(),
                        'idProductItem' => (int) $value['itemId']
                    ]);
                    if($productItem){
                        $combQuantity = $value['quantity'] > EsProductItem::MAX_QUANTITY 
                                        ? EsProductItem::MAX_QUANTITY 
                                        : $value['quantity'];
                        $productItem->setQuantity($combQuantity);
                    }
                }

                if(empty($retainCombination) === false){
                    foreach ($removeCombination as $itemId) { 
                        $productItem = $esProductItemRepo->findOneBy([
                            'product' => $product->getIdProduct(),
                            'idProductItem' => (int) $itemId
                        ]);
                        if($productItem){
                            $itemAttr = $esProductItemAttrRepo->findBy([
                                'productItem' => (int) $itemId
                            ]);
                            if($itemAttr){
                                foreach ($itemAttr as $attr) { 
                                    $this->em->remove($attr);
                                }
                            }

                            $shippingDetails = $esShippingDetailRepo->findBy([
                                'productItem' => (int) $itemId
                            ]);

                            foreach ($shippingDetails as $detail) {
                                $shippingHead = $esShippingHeadRepo->findOneBy([
                                    'idShipping' => $detail->getShipping()->getidShipping(),
                                    'product' => $product->getIdProduct()
                                ]);
                                if($shippingHead){ 
                                    $this->em->remove($detail);
                                    $this->em->remove($shippingHead);
                                }
                            }
                            $this->em->remove($productItem);
                        }
                    }
                }
            }
            $serverResponse['result'] = true;
            $this->em->flush();
        }  
        catch (Exception $e) {
            $errorMessage = $e->getMessage();
            if(in_array($errorMessage, $arrayErrorMessages) === false){
                $errorMessage = 'We are encountering a problem right now. Please try again later';
            }
            $serverResponse['error'] = $errorMessage;
        }

        echo json_encode($serverResponse);
    }
}


/* End of file product.php */
/* Location: ./application/controllers/product.php */
