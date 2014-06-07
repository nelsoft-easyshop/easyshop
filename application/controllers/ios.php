<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ios extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct();
		$this->load->helper('htmlpurifier');
		$this->load->model('product_model');
        $this->per_page = 20;
    }

    public function home(){
        $items =  $this->product_model->getHomeContent();
        echo json_encode($items,JSON_PRETTY_PRINT);
    }

    public function authenticate() {

        $uname = $this->input->get('uname');
        $pass = $this->input->get('upwd');   
        $dataval = array('login_username' => $uname, 'login_password' => $pass);
        $row = $this->user_model->verify_member($dataval);               

        if ($row['o_success'] >= 1) {
            $this->session->set_userdata('member_id', $row['o_memberid']);
            $this->session->set_userdata('usersession', $row['o_session']);
            $this->session->set_userdata('cart_contents', $this->cart_model->cartdata($row['o_memberid']));
            if($this->input->post('keepmeloggedin') == 'on'){ 
                $temp = array(
                    'member_id' => $this->session->userdata('member_id'),
                    'ip' => $this->session->userdata('ip_address'),
                    'useragent' => $this->session->userdata('user_agent'),
                    'session' => $this->session->userdata('session_id'),
                    );
                $cookieval = $this->user_model->dbsave_cookie_keeplogin($temp)['o_token'];
                $this->user_model->create_cookie($cookieval);
            }
        }  
        echo json_encode($row,JSON_PRETTY_PRINT);

    }
    
    function getMainCategories(){
        $categories = $this->product_model->getCatItemsWithImage(1);
        echo json_encode($categories,JSON_PRETTY_PRINT);
    }
    
    public function getProduct(){
        $id = $this->input->get('p_id');   
        $width = $this->input->get('width');   
		$uid = $this->session->userdata('member_id');
        $slug = $this->product_model->getSlug($id);
        $product_row = $this->product_model->getProductBySlug($slug);  
        
        $doc = new DOMDocument();
        //@ = error message suppressor, just to be safe
        @$doc->loadHTML($product_row['description']);
        $tags = $doc->getElementsByTagName('img');
        foreach($tags as $img){
            $img->setAttribute('style', 'display: block; max-width:'.$width.'px; width: auto; height: auto;');
        }
        $product_row['description'] = @$doc->saveHTML($doc);
        $product_options = $this->product_model->getProductAttributes($id, 'NAME');
        $product_options = $this->product_model->implodeAttributesByName($product_options);
        $data = array();
        if($product_row['o_success'] >= 1){
            $product_catid = $product_row['cat_id'];
            $data = array_merge($data,array( 
				'product' => $product_row,
				'product_options' => $product_options,
				'product_images' => $this->product_model->getProductImages($id),
				'reviews' => $this->getReviews($id,$product_row['sellerid']),
				//'recommended_items'=> $this->product_model->getRecommendeditem($product_catid,5,$id),
				'allowed_reviewers' => $this->product_model->getAllowedReviewers($id),
				//userdetails --- email/mobile verification info
				'userdetails' => $this->product_model->getCurrUserDetails($uid),
				'uid' => $uid,
                'product_quantity' => $this->product_model->getProductQuantity($id)
				));
		}
        echo json_encode($data,JSON_PRETTY_PRINT); 
    }
    

	public function searchbycategory()
	{
		$categoryId = $this->input->get('id_cat');		 
		$start = 0;
		$count = 0;
		$perPage = $this->per_page;
		$condition = array();
		$operator = " = ";
		$data =  $this->fill_header();	
		$checkifexistcategory = $this->product_model->checkifexistcategory($categoryId);
		$sortString = "";
		$conditionArray = array();
 
	 if($categoryId != 0){
    		if($checkifexistcategory != 0){
    			$downCategory = $this->product_model->selectChild($categoryId);
    			array_push($downCategory, $categoryId);
    			$categories = implode(",", $downCategory);
    			$items = $this->product_model->getProductsByCategory($categories,$conditionArray,$count,$operator,$start,$perPage,$sortString);

    			 
    			$subcategories = $this->product_model->getDownLevelNode($categoryId);
    			$breadcrumbs = $this->product_model->getParentId($categoryId);


    			$data = array( 
    				'title' => 'Category | Easyshop.ph',
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
			echo json_encode($response);exit();
			}
		} 

	}
	
	public function load_product() 
	{
		$categoryId = $this->input->get('id_cat');		 
		$count = 0;
		$perPage = $this->per_page;
		$start = $this->input->get('page_number') * $perPage;
		$condition = array();
		$operator = " = ";
		$data =  $this->fill_header();	
		$checkifexistcategory = $this->product_model->checkifexistcategory($categoryId);
		$sortString = "";
		$conditionArray = array();
        $response = array();
		if($categoryId != 0){
    		if($checkifexistcategory != 0){
    			$downCategory = $this->product_model->selectChild($categoryId);
    			array_push($downCategory, $categoryId);
    			$categories = implode(",", $downCategory);
    			$items = $this->product_model->getProductsByCategory($categories,$conditionArray,$count,$operator,$start,$perPage,$sortString);
    			$subcategories = $this->product_model->getDownLevelNode($categoryId);
    			$breadcrumbs = $this->product_model->getParentId($categoryId);
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
			}
		} 
        echo json_encode($response);

	}

	/*************************************************************************/
	/*********************** Review and Replies Section	**********************/
	/*************************************************************************/
	
	# Accepts product review data from post method and writes to es_product_review table.
	# Arguments: subject, comment, score
	function submit_review()
	{

           
			$subject = html_purify($this->input->get('subject'));
			$comment =  html_purify($this->input->get('comment'));
            $member_id = $this->input->get('member_id');
            $productid =  $this->input->get('product_id');
			$rating = $this->input->get('score');
			
			if((trim($subject) !== '')||(trim($comment) !== '')){
				$rating = $rating===''?'0':$rating;
				$this->product_model->addProductReview($member_id, $productid, $rating, $subject, $comment);
				echo 1;
			}
			else{
				echo 0;
			}
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
	# Arguments: last_id = id of the latest loaded review
	#			 product_id = product id
	#			 member_id = id of logged in user
	function get_more_reviews()
	{	
		$reviews = $replies = array();
		$lastreview_id = $this->input->get('last_id');
		$id = $this->input->get('product_id');
		$userid = $this->input->get('member_id');
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
			//'is_loggedin' => $this->session->userdata('usersession') !== '' ?'yes':'no'
			);
		
		echo json_encode($data, JSON_PRETTY_PRINT);
	}
	
	# Submit reply
	# Arguments: reply, p_reviewid, id_product
	function submit_reply()
	{
		$reply = html_purify($this->input->get('reply'));
		if($this->input->get('p_reviewid') && trim($reply) !== ''){
			$data = array(
				'review' => $reply,
				'p_reviewid' => $this->input->get('p_reviewid'),
				'product_id' => $this->input->get('product_id'),
				'member_id' => $this->input->get('member_id'),
				);
			$this->product_model->addReply($data);
			echo 1;
		}
		else
			echo 0;
	}
	
    
    function sch_onpress()
	{  
       if($this->input->get('q')){

			$html = "";
			$stringData =  $this->input->get('q');
			// $stringData = preg_replace('/[^A-Za-z0-9\-]/', '', $stringData);
			$string = ltrim($stringData); 
			// $words = "+".implode("*,+",explode(" ",trim($string)))."*"; 
			$words = explode(" ",trim($string)); 
			$keywords = $this->product_model->itemKeySearch($words);
 			echo json_encode($keywords);
			 
		}

	}
    
	function displaycategory()
	{
        echo json_encode($this->product_model->getFirstLevelNode(), JSON_PRETTY_PRINT);exit();
	}
        

    
    

	function search()
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
        $response['items'] = array();
		if (isset($_GET['q_str'])) {            
            if($_GET['q_str'] != ""){
				if($category == 1){
					$category_name = "";
				}else{
					$category_details = $this->product_model->selectCategoryDetails($category);
					$category_name = $category_details['name'];
				}
				$string = ltrim($this->input->get('q_str')); 
				$ins = $this->product_model->insertSearch($string);
				// $words = "+".implode("*,+",explode(" ",trim($string)))."*";
				$words = explode(" ",trim($string)); 
				$checkifexistcategory = $this->product_model->checkifexistcategory($category);
				if($checkifexistcategory == 0 || $category == 1)
				{		 
					// $usable_string = " AND  MATCH(`name`,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
					$response['items'] = $this->product_model->itemSearchNoCategory($words,$start,$per_page);
				}else{
					// $usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
					$down_cat = $this->product_model->selectChild($category);
					array_push($down_cat, $category);
					$catlist_down = implode(",", $down_cat);
					$response['items'] = $this->product_model->getProductInCategoryAndUnder($words,$catlist_down,$start,$per_page);
				}   
			}
		}
        echo json_encode($response['items']);    
	}

    public function version(){
        $this->load->library("xmlmap");
        $xml_element = $this->xmlmap->getFilenameID('page/mobile_files','version');
        echo $xml_element;
    }
    
    public function getKeywords(){
        $this->load->model('search_model');
        echo json_encode($this->search_model->getAllKeywords(), JSON_PRETTY_PRINT);
        exit();
    }
    
   


	
}

/* End of file ios.php */
/* Location: ./application/controllers/home.php */
