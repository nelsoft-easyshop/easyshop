<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class product extends MY_Controller 
{ 
	function __construct()  
	{ 
		parent::__construct(); 
		$this->load->helper('htmlpurifier');
		$this->load->model("product_model");
	}

	public $per_page = 6;
	public $start_irrelevant = 0;

	function categorySearch($categoryId = 0,$url_string="string")
	{
		$start = 0;
		$count = 0;
		$perPage = $this->per_page;
		$condition = "";
		$operator = " = ";
		$data =  $this->fill_header();	
		$checkifexistcategory = $this->product_model->checkifexistcategory($categoryId);
		$dontGetBrand = false;
		$filter = false;
		$haveSort = false;
		$sortString = "";
		$tempcondition = "";
		if(!count($_GET) <= 0){
			foreach ($_GET as $key => $value) {

				if($key == "Brand"){

					if(strpos($_GET[$key], '-|-') !== false) {
						$var = explode('-|-',$_GET[$key]);
						$needString = array();
						foreach ($var as $varkey => $varvalue) {
							array_push($needString, "'$varvalue'");
						}
						$condition .= "AND  brand IN (".implode(",", $needString).")"; 
					}else{
						$condition .= "AND  brand = '$value' ";	 
					}	
				}
				elseif (ucfirst(strtolower($key)) == "Condition") { 

					$dontGetBrand = true;
					$condition .= "AND  `condition` = '$value' ";	 

				}elseif (ucfirst(strtolower($key)) == "Sop") { 
 	 				$haveSort = true;
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
					$dontGetBrand = true;

				}elseif (ucfirst(strtolower($key)) == "Price") { 

					if(strpos($_GET[$key], 'to') !== false) {	

						$price = explode('to',$_GET[$key]);
						$price = explode('to', $_GET[$key]);
					}else{

						$price = explode('to', '0.00to99999999.99');
					}	
					$dontGetBrand = true;
					$condition .= "AND  price BETWEEN ".(double)$price[0]." AND ".(double)$price[1]." " ;
				}else{

					$dontGetBrand = true;
					if(strpos($_GET[$key], '-|-') !== false) {

						$var = explode('-|-',$_GET[$key]);
						foreach ($var as $varkey => $varvalue) {

							$count++; 
							$tempcondition .= " OR  ( `attr_name` = '".str_replace("_", " ", $key)."' AND attr_value = '$varvalue' )";
						}	 
					}else{

						$tempcondition .= " OR  ( `attr_name` = '".str_replace("_", " ", $key)."' AND attr_value = '$value' )";
						$count++; 
					}			
				
				}
			}
			if($count > 0){
				$tempcondition = substr_replace($tempcondition,"AND",0,3); 
			}
				$condition  = $tempcondition .' '. $condition;
 
			if(!count($_GET) == 1 && $haveSort == false){
				$condition = substr_replace($condition,"AND",0,3); 	
			} 
		}  
		if($categoryId != 0){
			if($checkifexistcategory != 0){
				$downCategory = $this->product_model->selectChild($categoryId);
				array_push($downCategory, $categoryId);
				$categories = implode(",", $downCategory);
				$items = $this->product_model->getProductsByCategory($categories,$condition,$count,$operator,$start,$perPage,$sortString);
				$ids = array();
				foreach ($items as $key) {
					array_push($ids, $key['product_id']);
				} 
				$ids = implode(',',$ids);
				$attributes = $this->product_model->getProductAttributesByCategory($ids,"",$start,$perPage);
				$itemCondition = $this->product_model->getProductConditionByCategory($ids,"",$start,$perPage);
				

				$brand = $this->product_model->getProductBrandsByCategory($categories,"",$start,$perPage); 
				if($dontGetBrand){
					$brand = $this->product_model->getProductBrandsByCategory($categories,$condition,$start,$perPage); 
				} 

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
					'title' => substr($url_string,0,-5).' | Easyshop.ph',
					); 
				$data = array_merge($data, $this->fill_header());


				$response['main_categories'] = $this->product_model->getFirstLevelNode(true);
				$response['breadcrumbs'] = $breadcrumbs;
				$response['subcategories'] = $subcategories;
				for($x=0; $x <= sizeof($response['subcategories']) -1 ; $x++){
					$id = $response['subcategories'][$x][3]; //id_cat
					$down_cat = $this->product_model->selectChild($id);		
					if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
						$down_cat = array();
					array_push($down_cat, $id);
					$db_cat_item = $this->product_model->getPopularitem($down_cat,1);
					$response['subcategories'][$x]['popular'] = $db_cat_item;
				}
				
				$response['items'] = $items;
				$response['attributes'] = $organizedAttribute;
				$response['id_cat'] = $categoryId;
                $response['category_navigation'] = $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE );
				$this->load->view('templates/header', $data); 
				$this->load->view('pages/product/product_search_by_category_final',$response);
				$this->load->view('templates/footer_full'); 
			}
			else{
				redirect('/category/all', 'refresh');
			}
		}else{
			redirect('/category/all', 'refresh');
		}

	}

	function loadOtherCategorySearch(){

		$categoryId = $_POST['id_cat'];				  
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

		if(!count($get) <= 0){

			foreach ($get as $key => $value) {

				if($key == "Brand"){
					if(strpos($get[$key], '-|-') !== false) {
						$var = explode('-|-',$get[$key]);
						$needString = array();
						foreach ($var as $varkey => $varvalue) {
							array_push($needString, "'$varvalue'");
						}
						$condition .= "AND  brand IN (".implode(",", $needString).")"; 
					}else{
						$condition .= "AND  brand = '$value' ";	 
					}	
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
					$dontGetBrand = true;

				}elseif (ucfirst(strtolower($key)) == "Condition") { 

					$dontGetBrand = true;
					$condition .= "AND  `condition` = '$value' ";	 

				}elseif (ucfirst(strtolower($key)) == "Price") { 

					if(strpos($get[$key], 'to') !== false) {	

						$price = explode('to',$get[$key]);
						$price = explode('to', $get[$key]);
					}else{

						$price = explode('to', '0.00to99999999.99');
					}	
					$dontGetBrand = true;
					$condition .= "AND  price BETWEEN ".(double)$price[0]." AND ".(double)$price[1]." " ;
				}else{

					$dontGetBrand = true;
					if(strpos($get[$key], '-|-') !== false) {

						$var = explode('-|-',$get[$key]);
						foreach ($var as $varkey => $varvalue) {

							$count++; 
							$tempcondition .= " OR  ( `attr_name` = '".str_replace("_", " ", $key)."' AND attr_value = '$varvalue' )";
						}	 
					}else{

						$tempcondition .= " OR  ( `attr_name` = '".str_replace("_", " ", $key)."' AND attr_value = '$value' )";
						$count++; 
					}	
 

				}
			}
			if($count > 0){
				$tempcondition = substr_replace($tempcondition,"AND",0,3); 
			}
			$condition  = $tempcondition .' '. $condition;
			
			if(!count($get) == 1 && $haveSort == false){
				$condition = substr_replace($condition,"AND",0,3); 	
			} 
		}

		$downCategory = $this->product_model->selectChild($categoryId);
		array_push($downCategory, $categoryId);
		$categories = implode(",", $downCategory);

		session_start();

		$items = $this->product_model->getProductsByCategory($categories,$condition,$count,$operator,$start,$perPage,$sortString);
		$response['items'] = $items; 
		$response['id_cat'] = $categoryId;
		$response['typeofview'] = $type;

		if(count($items) <= 0)
		{	  
			if($count <= 0){

				$data = json_encode('0');
				echo $data;
				exit();
			}else{

				$notIrrelivant = TRUE;
			}

		}else{

			$response['irrelivant'] = false;
			$data = json_encode($this->load->view('pages/product/product_search_by_category2_final',$response,TRUE));
			echo $data;
			exit();
		}

		if($notIrrelivant){
			$newoperator = ' < ';
			$start = $_SESSION['start'] * $perPage;
			$items = $this->product_model->getProductsByCategory($categories,$condition,$count,$newoperator,$start,$perPage,$sortString);
			$response['items'] = $items; 
			$response['id_cat'] = $categoryId;
			$response['typeofview'] = $type;
			$response['irrelivant'] = true;
			$response['count'] = $_SESSION['start'];
			if(count($items) <= 0)
			{
				$data = json_encode('0');
				echo $data;
				exit();
			}else{
				 
				$data = json_encode($this->load->view('pages/product/product_search_by_category2_final',$response,TRUE));
				echo $data;
				$_SESSION['start'] += 1;
			   
				exit();
			}
			echo $data;
			exit();
		}


		echo $data;
		exit();
	}
 

	function highlight($text, $words)
	{

		$words = preg_replace('/\s+/', ' ',$words);
		$split_words = explode(" ", $words);
		foreach($split_words as $word)
		{
			$color = "#e5e5e5";
			$text = preg_replace("|($word)|Ui","<b>$1</b>" , $text );
		}
		return $text;
	}


	function sch_onpress()
	{  

		header('Content-Type: text/plain'); 	 
		if($this->input->get('q')){

			$html = "";
			$stringData =  $this->input->get('q');
			// $stringData = preg_replace('/[^A-Za-z0-9\-]/', '', $stringData);
			$string = ' '.ltrim($stringData); 
			$words = "+".implode("*,+",explode(" ",trim($string)))."*"; 
			$keywords = $this->product_model->itemKeySearch($words);
 	
			if(count($keywords) <= 0){
				$html = 0;
			}else{
                $html .= "<ul>";
				foreach ($keywords as $value) {
					$showValue = $this->highlight($value,$stringData);
					$html .= "<li><a href='".base_url()."search/search.html?q_str=".urlencode($value)."&q_cat=1'>".$showValue."</a></li>";
				}
                $html .= "</ul>";
			}
	
			echo $html;
		}

	}

	function sch($string="search.html") # ROUTING: search/(:any)
	{
		$values = array();
		$string_sort = "";
		$start = 0;
		$usable_string;
		$per_page = $this->per_page;
		$category = $this->input->get('q_cat');
        if(!is_numeric($category)){
            $category = 1;
        }
		
		if (isset($_GET['q_str'])) {
			if($_GET['q_str'] == "" && $_GET['q_cat'] == 1)
			{
				redirect('/category/all/', 'refresh');
			}elseif($_GET['q_str'] == "" && $_GET['q_cat'] != 1){
                redirect('/category/'.$_GET['q_cat'].'/'.es_url_clean($_GET['q_catname']).'.html' , 'refresh');
			}else{

				if($category == 1){
					$category_name = "";
				}else{
					$category_details = $this->product_model->selectCategoryDetails($category);
					$category_name = $category_details['name'];
				}

				$string = ' '.ltrim($_GET['q_str']); 
				$ins = $this->product_model->insertSearch($_GET['q_str']);
				$words = "+".implode("*,+",explode(" ",trim($string)))."*"; 
				$checkifexistcategory = $this->product_model->checkifexistcategory($category);
				if($checkifexistcategory == 0 || $category == 1)
				{		 
					$usable_string = " AND  MATCH(`name`,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
					$response['items'] = $this->product_model->itemSearchNoCategory($usable_string,$start,$per_page);

	 

				}else{
					$usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
					$down_cat = $this->product_model->selectChild($category);
					array_push($down_cat, $category);
					$catlist_down = implode(",", $down_cat);
					$response['items'] = $this->product_model->getProductInCategoryAndUnder($category,$usable_string,$catlist_down,$start,$per_page,$string_sort);
				}


				// start here
				$firstdownlevel = $this->product_model->getDownLevelNode($category);
				$newbuiltarray = array();
				$cnt = 0;
				$item_total_cnt = 0;

				foreach ($firstdownlevel as $keyfirstlevel => $value) {
					$count_main = 0;
					$noitem = false; 
					$newcat = $value['id_cat']; 
					$usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
					$down_cat = $this->product_model->selectChild($newcat);
					array_push($down_cat,$value['id_cat']);
					$catlist_down = implode(",", $down_cat);
					$count_main_new = count($this->product_model->getProductInCategoryAndUnder($category,$usable_string,$catlist_down,0,9999999999,$string_sort));
					$count_main += $count_main_new;
					$item_total_cnt += $count_main;
					$cnt = $keyfirstlevel;
					array_push($newbuiltarray, array("name"=>$value['name'],"item_id"=> $value['id_cat'],"parent_id"=> $value['parent_id'],"count"=>0,"children"=>array()));
					$secondlevel = $this->product_model->getDownLevelNode($value['id_cat']);
					
					foreach ($secondlevel as $key){
						$count = 0;
						$newcat = $key['id_cat']; 
						$usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
						$down_cat = $this->product_model->selectChild($newcat);
						array_push($down_cat,$newcat);
						$catlist_down = implode(",", $down_cat); 
						$count_new = count($this->product_model->getProductInCategoryAndUnder($newcat,$usable_string,$catlist_down,0,9999999999,$string_sort));	
						$count += $count_new;
						if(!$count <= 0)
						{
							array_push($newbuiltarray[$cnt]['children'], array("name"=>$key['name'],"item_id"=> $key['id_cat'],"parent_id"=> $key['parent_id'],"count"=>$count));
						}
					}
					$newbuiltarray[$cnt]['count'] = $count_main;
				}

				$newbuiltarray = array("name"=>$category_name,"children" => $newbuiltarray);
				// echo '<pre>',print_r($newbuiltarray);exit();
				$list = $this->toUL($newbuiltarray['children']);

				if($category_name == "PARENT" || $category == 1){
					$response['category_cnt'] = '<h3>Categories1</h3>'.$list;
				}else{	
					$response['category_cnt'] = '<h3>Categories2</h3><ul><li>'.$category_name.'</li><li>'.$list.'</li></ul>';
				}

				// end here

				$response['id_cat'] = $category;
				$data = array(
					'title' => 'Easyshop.ph',
					);
				$data = array_merge($data, $this->fill_header());
                $response['category_navigation'] = $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE );
				$this->load->view('templates/header', $data); 
				$this->load->view('pages/product/product_search_by_searchbox',$response);
				$this->load->view('templates/footer_full'); 
			}
		}else{
			redirect('/category/all', 'refresh');
		}
	}	

	

	function toUL(array $array)
	{
		$html = '<ul>' . PHP_EOL;

		foreach ($array as $value)
		{
			if($value['count'] <= 0){
				continue;
			}
			$html .= '<li><a href="search.html?q_str=' . $_GET['q_str'] .'&q_cat='.$value['item_id'].'">' . $value['name'].'('.$value['count'].')</a>';
			if (!empty($value['children']))
			{
				$html .= $this->toUL($value['children']);
			}
			$html .= '</li>' . PHP_EOL;
		}

		$html .= '</ul>' . PHP_EOL;

		return $html;
	}


	function sch_scroll() ##ROUTING : search/load_search_other_product
	{
		$string_sort = "";
		$per_page = $this->per_page;
		$category_id = $_POST['id_cat']; 
		$start = $_POST['page_number'] * $per_page;
		$type = $_POST['type'];	

		$string = ' '.ltrim($_POST['parameters']['q_str']);
		$category = $_POST['parameters']['q_cat'];
		$words = "+".implode("*,+",explode(" ",trim($string)))."*"; 
		$checkifexistcategory = $this->product_model->checkifexistcategory($category);

		
		if($checkifexistcategory == 0 || $category == 1)
		{
			$usable_string = " AND  MATCH(`name`,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
			$response['items'] = $this->product_model->itemSearchNoCategory($usable_string,$start,$per_page);	
		}else{
			$usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
			$down_cat = $this->product_model->selectChild($category);
			array_push($down_cat, $category);
			$catlist_down = implode(",", $down_cat);
			$response['items'] = $this->product_model->getProductInCategoryAndUnder($category,$usable_string,$catlist_down,$start,$per_page,$string_sort);

		}

		
		$response['typeofview'] = $type;
		$response['id_cat'] = $category; 
		if(count($response['items']) <= 0)
		{
			$data = json_encode('0');
		}else{
			$data = json_encode($this->load->view('pages/product/product_search_by_searchbox2',$response,TRUE));
		}
		
		echo $data; 
	}

	# Generate the product view page
	# Arguments: $id -> product id of product to be displayed
	function view($id=0,$url_string="string") 
	{ 
		$product_row = $this->product_model->getProduct($id);

		$data['title'] = 'Easyshop.ph - Product Page';
		$uid = $this->session->userdata('member_id');
		$data = array_merge($data,$this->fill_header());
		$this->load->view('templates/header', $data); 
		$product_options = $this->product_model->getProductAttributes($id, 'NAME');
		$product_options = $this->product_model->implodeAttributesByName($product_options);
        if($product_row['o_success'] >= 1){
			$this->session->set_userdata('product_id', $id);
			$product_catid = $product_row['cat_id'];
			$data = array_merge($data,array( 
				'page_javascript' => 'assets/JavaScript/productpage.js',
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
				'userdetails' => $this->product_model->getCurrUserDetails($uid),
				'product_quantity' => $this->product_model->getProductQuantity($id),
				'shipment_information' => $this->product_model->getShipmentInformation($id),
                'shiploc' => $this->product_model->getLocation(),
                'category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE ),
                ));
			$data['vendorrating'] = $this->product_model->getVendorRating($data['product']['sellerid']);
			
			$data['jsonReviewSchemaData'] = $this->assembleJsonReviewSchemaData($data);
			
			$this->load->view('pages/product/productpage_view', $data); 
		}
		else
			$this->load->view('pages/general_error', $data); 		
		$this->load->view('templates/footer_full');

	}
	
	// Assemble SEO Review tags
	function assembleJsonReviewSchemaData($data)
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
	

	# Accepts product review data from post method and writes to es_product_review table.
	# Arguments: none
	function submit_review()
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
	
	#new query for getting reviews (top5 Main reviews) - Janz
	function getReviews($product_id, $sellerid)
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

	# Retrieve more reviews from es_product_review_table
	# Arguments: $lastreview_id = id of the latest loaded review
	function get_more_reviews()
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
			$sellerid = $this->product_model->getProduct($id)['sellerid'];
			//$sellerid = $this->product_model->getProduct($id);
		}

		$data = array(
			'reviews' => $reviews,
			'is_seller' => $userid===$sellerid?'yes':'no',
			'is_loggedin' => $this->session->userdata('usersession') !== '' ?'yes':'no'
			);
		
		echo json_encode($data);
	}

	# Submit reply
	function submit_reply()
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
	
	function categories_all() # ROUTING: category/all
	{
		$categories = $this->product_model->getFirstLevelNode(false, true);
		foreach($categories as $index=>$category){
			$categories[$index]['subcategories'] = $this->product_model->getDownLevelNode($category['id_cat']);
			foreach($categories[$index]['subcategories'] as $inner_index=>$subcategory){

				$down_cat = $this->product_model->selectChild($subcategory['id_cat']);		
				if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
					$down_cat = array();
				array_push($down_cat, $subcategory['id_cat']);
				#$catlist_down = implode(",", $down_cat);
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

	#Function that will receive id from category ajax, and id will be passed to model and retrieves the data
	function getdatafromcat($id){
		$row = $this->product_model-> getCatItemsWithImage($id);
		return $row;
	}
	
	function changeDelete(){
		if($this->input->post('p_id') && $this->input->post('action')){
			$memberid = $this->session->userdata('member_id');
			$productid = $this->input->post('p_id');
			$action = $this->input->post('action');
			if($action === 'delete')
				$this->product_model->updateIsDelete($productid, $memberid, 1);
			else if($action === 'restore')
				$this->product_model->updateIsDelete($productid, $memberid, 0);
		}
		redirect('me', 'refresh');
	}

	function searchCategory(){  
		$string = $this->input->get('data');
		$rows = $this->product_model->searchCategory($string);
		foreach($rows as $idx=>$row){
			$rows[$idx]['parent'] = $this->product_model->getParentId($row['id_cat']);
		}
		echo json_encode($rows);
	}

	function searchBrand(){
		$string = $this->input->get('data');
		$rows = $this->product_model->searchBrand($string);
		echo json_encode($rows);
	}

	function prodjson(){
		$data = array(
			'@test' => 'value1',
			'parameter' => array(
				array('test1'=>'value1'),
				array('test2'=>'value2')
			)
		);
		echo ( json_encode($data) );
	}
	
    
    function item($slug = ''){
        $product_row = $this->product_model->getProductBySlug($slug);
		$data['title'] = 'Easyshop.ph - Product Page';
		$uid = $this->session->userdata('member_id');
		$data = array_merge($data,$this->fill_header());
		$this->load->view('templates/header', $data); 
        if($product_row['o_success'] >= 1){
            $id = $product_row['id_product'];
            $product_options = $this->product_model->getProductAttributes($id, 'NAME');
		    $product_options = $this->product_model->implodeAttributesByName($product_options);
			$this->session->set_userdata('product_id', $id);
			$product_catid = $product_row['cat_id'];
			$data = array_merge($data,array( 
				'page_javascript' => 'assets/JavaScript/productpage.js',
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
				'userdetails' => $this->product_model->getCurrUserDetails($uid),
				'product_quantity' => $this->product_model->getProductQuantity($id),
				'shipment_information' => $this->product_model->getShipmentInformation($id),
                'shiploc' => $this->product_model->getLocation(),
                'category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE ),
                ));
			$data['vendorrating'] = $this->product_model->getVendorRating($data['product']['sellerid']);
			
			$data['jsonReviewSchemaData'] = $this->assembleJsonReviewSchemaData($data);
			
			$this->load->view('pages/product/productpage_view', $data); 
		}
		else
			$this->load->view('pages/general_error', $data); 		
		$this->load->view('templates/footer_full');
    } 
    
}


/* End of file product.php */
/* Location: ./application/controllers/product.php */
