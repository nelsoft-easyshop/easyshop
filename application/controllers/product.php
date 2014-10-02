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
    }


    /**     
     *  Displays products in each category
     *
     *  @return View
     */
    public function category_page($url_string="")
    {
        $start = 0;
        $count = 0;
        $perPage = $this->per_page;
        $operator = " = ";
        $data =  $this->fill_header();	
        $category_array = $this->product_model->getCategoryBySlug($url_string);
        
        $categoryId = $category_array['id_cat'];
        $categoryName = $category_array['name'];
        $categoryDescription = $category_array['description'];

        $this->load->config('protected_category', TRUE);
        $protected_categories = $this->config->config['protected_category'];
        
        if(in_array(intval($categoryId,10), $protected_categories)){
            if(intval($categoryId) === $protected_categories['promo']){
                redirect('/deals', 'refresh');
            }else{          
                redirect('/cat/all', 'refresh');
            }
        }

        $sortString = ""; 
        $conditionArray = array(); 
        
        if(count($_GET) > 0){
            foreach ($_GET as $key => $value) {

                if(ucfirst(strtolower($key)) == "Brand"){

                    if(strpos($_GET[$key], '-|-') !== false) {
                        $var = explode('-|-',$_GET[$key]);
                        $needString = array();
                        foreach ($var as $varkey => $varvalue) {
                            array_push($needString, "'$varvalue'");
                        } 
                        $conditionArray['brand'] = array(
                            'value' => $var,
                            'count' => count($var)
                            );
                    }else{ 
                        $conditionArray['brand'] = array(
                            'value' => $value,
                            'count' => 1
                            );
                    }
                }
                elseif (ucfirst(strtolower($key)) == "Condition") { 

                    $conditionArray['condition'] = array(
                        'value' => $value,
                        );

                }elseif (ucfirst(strtolower($key)) == "Sop") { 

                    $sortValue = ucfirst(strtolower($_GET[$key]));
                    if($sortValue == ucfirst(strtolower('hot'))){
                        $sortString = " is_hot DESC , ";
                    }elseif ($sortValue == ucfirst(strtolower('new'))) {
                        $sortString = " is_new DESC , ";
                    }elseif ($sortValue == ucfirst(strtolower('popular'))) {
                        $sortString = " clickcount DESC , ";
                    }else{
                        $sortString = "";
                    } 

                }elseif (ucfirst(strtolower($key)) == "Price") { 

                    if(strpos($_GET[$key], 'to') !== false) {	

                        $price = explode('to', $_GET[$key]);
                    }else{

                        $price = explode('to', '0.00to99999999.99');
                    }
                    $a = str_replace( ',', '', $price[0]);
                    $b = str_replace( ',', '', $price[1]);
                    if(is_numeric($a) && is_numeric($b)){

                        $conditionArray['price'] = array(
                            'start' => $a,
                            'end'=> $b
                            );
                    }

                }else{

                    $count++; 
                    if(!isset($conditionArray['attributes'])){
                        $conditionArray['attributes'] = array();
                    }

                    if(strpos($_GET[$key], '-|-') !== false) {
                        $var = explode('-|-',$_GET[$key]);		
                        $key = strtolower(str_replace("_", " ", $key));
                        foreach ($var as $varkey => $varvalue) {
                            $conditionArray['attributes'][$key] = $var;
                        }
                    }else{
                        $key = strtolower(str_replace("_", " ", $key)); 
                        $conditionArray['attributes'][$key] = $value; 
                    }
                }
            }
        }    

        $downCategory = $this->product_model->selectChild($categoryId);
        if(empty($downCategory)){
            $downCategory = array();
        }
        array_push($downCategory, $categoryId);
        $categories = implode(",", $downCategory);
        $items = $this->product_model->getProductsByCategory($categories,$conditionArray,$count,$operator,$start,$perPage,$sortString);
        
        $ids = array();
        foreach ($items as $key) {
            array_push($ids, $key['product_id']);
        }
        $ids = implode(',',$ids);
        $attributes = $this->product_model->getProductAttributesByCategory($ids);
        $itemCondition = $this->product_model->getProductConditionByCategory($ids);
        $brand = $this->product_model->getProductBrandsByCategory($ids); 

        $organizedAttribute = array();
        $organizedAttribute['Brand'] = $brand;
        $organizedAttribute['Condition'] = $itemCondition;
        for ($i=0; $i < sizeof($attributes) ; $i++) { 
            $head = urlencode($attributes[$i]['attr_name']);
            if(!array_key_exists($head,$organizedAttribute)){
                $organizedAttribute[$head] = array();	
            }
            array_push($organizedAttribute[$head],  $attributes[$i]['attr_value']);
        }
        $subcategories = $this->product_model->getDownLevelNode($categoryId);
        $breadcrumbs = $this->product_model->getParentId($categoryId);
        
        $data = array( 
            'title' => es_string_limit(html_escape($categoryName), 60, '...', ' | Easyshop.ph'),
            'metadescription' => es_string_limit(html_escape($categoryDescription), 60),
            ); 
        $data = array_merge($data, $this->fill_header());

        $response['main_categories'] = $this->product_model->getFirstLevelNode(true);
        $response['breadcrumbs'] = $breadcrumbs;
        $response['subcategories'] = $subcategories;
        for($x=0; $x <= sizeof($response['subcategories']) -1 ; $x++){
            $id = $response['subcategories'][$x][3]; //id_cat
            $down_cat = $this->product_model->selectChild($id);
            if((count($down_cat) === 1)&&(trim($down_cat[0]) === '')){
                $down_cat = array();
            }
            array_push($down_cat, $id);
            $db_cat_item = $this->product_model->getPopularitem($down_cat,1);
            $response['subcategories'][$x]['popular'] = $db_cat_item;
        }
        
        $response['items'] = $items;
        $response['attributes'] = $organizedAttribute;
        $response['id_cat'] = $categoryId;
        $response['category_navigation'] = $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE );
        $response['category_slug'] = $category_array['slug'];
        
        $this->load->view('templates/header', $data); 
        $this->load->view('pages/product/product_search_by_category_final',$response);
        $this->load->view('templates/footer'); 

    }

    /**     
     * Returns more products as user scrolls down the page  
     *
     * @return JSON 
     */
    public function category_page_more()
    {

        $categoryId = $this->input->post('id_cat');

        $this->load->config('protected_category',TRUE);
        $promo_category_id = $this->config->item('promo');
        
        if(intval($categoryId) === intval($promo_category_id)){
            exit();
        }

        $perPage = $this->per_page;
        $start = $this->input->post('page_number') * $perPage;
        $count = 0;
        $parameter = $this->input->post('parameters');
        $get = json_decode($parameter,true);
        $operator = ' = ';
        $type = $this->input->post('type');
        $condition = "";
        $notIrrelivant = false;
        $haveSort = false;
        $sortString = "";
        $tempcondition = "";

        $conditionArray = array();

        if(!count($get) <= 0){
            foreach ($get as $key => $value) {

                if(ucfirst(strtolower($key)) == "Brand"){
        
                    if(strpos($get[$key], '-|-') !== false) {
                        $var = explode('-|-',$get[$key]);
                        $needString = array();
                        foreach ($var as $varkey => $varvalue) {
                            array_push($needString, "'$varvalue'");
                        } 
                        $conditionArray['brand'] = array(
                            'value' => $var,
                            'count' => count($var)
                            );
                    }else{ 
                        $conditionArray['brand'] = array(
                            'value' => $value,
                            'count' => 1
                            );
                    }

                }elseif (ucfirst(strtolower($key)) == "Condition") { 

                    $conditionArray['condition'] = array(
                        'value' => $value,
                    );
                }elseif (ucfirst(strtolower($key)) == "Sop") { 
                    $haveSort = true;
                    $sortValue = ucfirst(strtolower($get[$key]));
                    if($sortValue == ucfirst(strtolower('hot'))){
                        $sortString = " is_hot DESC , ";
                    }elseif ($sortValue == ucfirst(strtolower('new'))) {
                        $sortString = " is_new DESC , ";
                    }elseif ($sortValue == ucfirst(strtolower('popular'))) {
                        $sortString = " clickcount DESC , ";
                    }else{
                        $sortString = "";
                    } 

                }elseif (ucfirst(strtolower($key)) == "Price") { 

                    if(strpos($get[$key], 'to') !== false) {	

                        $price = explode('to', $get[$key]);
                    }else{

                        $price = explode('to', '0.00to99999999.99');
                    } 
                    if(is_numeric($price[0]) && is_numeric($price[1])){
                        $conditionArray['price'] = array(
                            'start' => $price[0],
                            'end'=> $price[1]
                            );
                    }

                }else{

                    if(strpos($get[$key], '-|-') !== false) {
                        $var = explode('-|-',$get[$key]);		
                        $key = strtolower(str_replace("_", " ", $key));
                        if(!isset($conditionArray['attributes'])){
                            $conditionArray['attributes'] = array();
                        } 
                        $count++; 
                        foreach ($var as $varkey => $varvalue) {
                            $conditionArray['attributes'][$key] = $var;
                        }
                    }else{
                        $key = strtolower(str_replace("_", " ", $key)); 
                        $count++;
                        if(!isset($conditionArray['attributes'])){
                            $conditionArray['attributes'] = array();
                        } 
                        $conditionArray['attributes'][$key] = $value; 
                    }
                }
            }
        }

        if($categoryId == 1 || $categoryId == 0){
            $categories = "SELECT cat_id FROM es_product WHERE is_delete = 0 AND is_draft = 0";
        }else{
            $downCategory = $this->product_model->selectChild($categoryId);
            array_push($downCategory, $categoryId);
            $categories = implode(",", $downCategory);
        }

        session_start();
        
        $items = $this->product_model->getProductsByCategory($categories,$conditionArray,$count,$operator,$start,$perPage,$sortString);

        $response['items'] = $items; 
        $response['id_cat'] = $categoryId;
        $response['typeofview'] = $type;

        if(count($items) <= 0){ 
            $data = json_encode('0');
            echo $data;
            exit();
        }else{

            $response['irrelivant'] = false;
            $data = json_encode($this->load->view('pages/product/product_search_by_category2_final',$response,TRUE));
            echo $data;
            exit();
        }

        echo $data;
        exit();
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
     * Accepts product review data from post method and writes to es_product_review table.
     *
     * @return boolean
     */
    public function submit_review()
    {
        if(($this->input->post('review_form'))&&($this->form_validation->run('review_form'))){
            $subject = html_purify($this->input->post('subject'));
            $comment =  html_purify($this->input->post('comment'));
            if((trim($subject) === '')||(trim($comment) === ''))
                return false;
            $rating = $this->input->post('score');
            $productid = $this->session->userdata('product_id');
            $rating = $rating===''?'0':$rating;
            $memberid =  $this->session->userdata('member_id');
            $this->product_model->addProductReview($memberid, $productid, $rating, $subject, $comment);
            return true;
        }
        else
            return false;
    }

    /** 
     * Return the top 5 main reviews
     * 
     * @param integer $product_id
     * @param inetger $sellerid
     * @return array
     */
    public function getReviews($product_id, $sellerid)
    {
        $recent = array();
        $recent = $this->product_model->getProductReview($product_id);
        
        if(count($recent)>0){
            $retrieve = array();
            foreach($recent as $data){
                array_push($retrieve, $data['id_review']);
            }
            $replies = $this->product_model->getReviewReplies($retrieve, $product_id);
            foreach($replies as $key=>$temp){
                $temp['review'] = html_escape($temp['review']);
            }
            $i = 0;
            $userid = $this->session->userdata('member_id');
            foreach($recent as $review){
                $recent[$i]['replies'] = array();
                $recent[$i]['reply_count'] = 0;
                if($userid === $review['reviewerid'])
                    $recent[$i]['is_reviewer'] = 1;
                else
                    $recent[$i]['is_reviewer'] = 0;

                foreach($replies as $reply){
                    if($review['id_review'] == $reply['replyto']){
                        array_push($recent[$i]['replies'], $reply);
                        $recent[$i]['reply_count']++;
                    }
                }
                $i++;
            }
        }
        
        return $recent;
    }

    
    /**
     * Retrieve more reviews from es_product_review_table
     * 
     * @return JSON
     */
    public function get_more_reviews()
    {   
        $reviews = $replies = array();
        $lastreview_id = $this->input->post('last_id');
        $id=$this->session->userdata('product_id');
        $userid = $this->session->userdata('member_id');
        $sellerid = '';

        $reviews = $this->product_model->getProductReview($id, $lastreview_id);
        if(count($reviews) > 0){
            $retrieve = array();
            foreach($reviews as $key=>$review)
            {
                $review['title']=html_escape($review['title']);
                $review['review']=html_escape($review['review']);
                $reviews[$key] = $review;

                array_push($retrieve, $review['id_review']);
            }
            $replies = $this->product_model->getReviewReplies($retrieve, $id);
            foreach($replies as $key=>$temp){
                $temp['review'] = html_escape($temp['review']);
                $replies[$key] = $temp;
            }

            $i = 0;
            foreach($reviews as $review){
                $reviews[$i]['replies'] = array();
                $reviews[$i]['reply_count'] = 0;

                if($userid === $review['reviewerid'])
                    $reviews[$i]['is_reviewer'] = 1;
                else
                    $reviews[$i]['is_reviewer'] = 0;

                foreach($replies as $reply){
                    if($review['id_review'] == $reply['replyto']){
                        array_push($reviews[$i]['replies'], $reply);
                        $reviews[$i]['reply_count']++;
                    }
                }
                $i++;
            }
            $sellerid = $this->product_model->getProductById($id)['sellerid'];
        }

        $data = array(
            'reviews' => $reviews,
            'is_seller' => $userid===$sellerid?'yes':'no',
            'is_loggedin' => $this->session->userdata('usersession') !== '' ?'yes':'no'
            );
        
        echo json_encode($data);
    }

    /**
     * Submit a reply to a review
     *
     * @return integer
     */
    public function submit_reply()
    {
        $reply = html_purify($this->input->post('reply_field'));
        if($this->input->post('p_reviewid') && trim($reply) !== ''){
            $data = array(
                'review' => $reply,
                'p_reviewid' => $this->input->post('p_reviewid'),
                'product_id' => $this->input->post('id_product'),
                'member_id' => $this->session->userdata('member_id')
                );
            $this->product_model->addReply($data);
            echo 1;
        }
        else
            echo 2;
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
     * Displays the product page
     *  
     * @param string $slug
     * @return View
     */
    public function item($slug = '')
    {
        $this->load->model("user_model");
        $this->load->config('promo', TRUE);
        $uid = $this->session->userdata('member_id');
        $product_row = $this->product_model->getProductBySlug($slug);  	
        $data = $this->fill_header();
        
        if($product_row['o_success'] >= 1){
            $id = $product_row['id_product'];
            $product_options = $this->product_model->getProductAttributes($id, 'NAME');
            $product_options = $this->product_model->implodeAttributesByName($product_options);
            $this->session->set_userdata('product_id', $id);
            $product_catid = $product_row['cat_id'];
            
            $banner_view = '';
            $payment_method_array = $this->config->item('Promo')[0]['payment_method'];
            $isBuyButtonViewable = true;

            if(intval($product_row['is_promote']) === 1 && (!$product_row['end_promo']) ){
                $bannerfile = $this->config->item('Promo')[$product_row['promo_type']]['banner'];
                if(strlen(trim($bannerfile)) > 0){
                    $banner_view = $this->load->view('templates/promo_banners/'.$bannerfile, $product_row, TRUE); 
                }
                $payment_method_array = $this->config->item('Promo')[$product_row['promo_type']]['payment_method'];
                $isBuyButtonViewable = $this->config->item('Promo')[$product_row['promo_type']]['viewable_buy_button'];
            }
            
            $data = array_merge($data,array( 
                'breadcrumbs' =>  $this->product_model->getParentId($product_row['cat_id']),
                'product' => $product_row,
                'product_options' => $product_options,
                'product_images' => $this->product_model->getProductImages($id),
                'main_categories' => $this->product_model->getFirstLevelNode(TRUE),
                'reviews' => $this->getReviews($id,$product_row['sellerid']),
                'uid' => $uid,
                'recommended_items'=> $this->product_model->getRecommendeditem($product_catid,5,$id),
                'allowed_reviewers' => $this->product_model->getAllowedReviewers($id),
                //userdetails --- email/mobile verification info
                'userdetails' => $this->user_model->getUserById($uid),
                'product_quantity' => $this->product_model->getProductQuantity($id, false, false, $product_row['start_promo']),
                'shipment_information' => $this->product_model->getShipmentInformation($id),
                'shiploc' => $this->product_model->getLocation(),
                'banner_view' => $banner_view,
                'payment_method' => $payment_method_array,
                'is_buy_button_viewable' => $isBuyButtonViewable,
                'category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE ),
                ));
            $data['vendorrating'] = $this->product_model->getVendorRating($data['product']['sellerid']);
            $data['jsonReviewSchemaData'] = $this->assembleJsonReviewSchemaData($data);
            $data['title'] = es_string_limit(html_escape($product_row['product_name']), 60, '...', ' | Easyshop.ph');
            $data['metadescription'] = es_string_limit(html_escape($product_row['brief']), 155);
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/product/productpage_view', $data);
        }
        else{
            $data['title'] =  'Easyshop.ph | Page Not Found';
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/general_error', $data); 
        }
        $this->load->view('templates/footer_full');
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
