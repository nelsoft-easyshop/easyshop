<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ios extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('home_xml');
    }



    public function url(){

        $ipAddress = "192.168.1.50:81";
        echo 'LIST OF URL';
        echo '<br><br>';
        echo 'LOGIN: <a  style="font-weight:bold;color:maroon" href="http://'. $ipAddress.'/ios/authenticate?uname=&upwd=">http://'. $ipAddress.'/ios/authenticate?uname=&upwd=</a>';
        echo '<br><br>'; 
        echo 'HOME: <a  style="font-weight:bold;color:maroon" href="http://'. $ipAddress.'/ios/home">http://'. $ipAddress.'/ios/home</a>';
        echo '<br><br>'; 
        echo 'PRODUCT PAGE: <a  style="font-weight:bold;color:maroon" href="http://'. $ipAddress.'/ios/getProduct?p_id=">http://'. $ipAddress.'/ios/getProduct?p_id=</a>';
        echo '<br><br>';
        echo 'http://www.google.fr/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&ved=0CB4QFjAA&url=http%3A%2F%2Fallseeing-i.com%2FASIHTTPRequest%2F&ei=-dFZULfKO7SU0QW-1YGoAw&usg=AFQjCNFpUZprrMAY9mTk0aGzEzwSG8L9sg';

    }

    public function home(){
        $items =  $this->home_xml->getFilenameID('home_files');
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
    
    public function getProduct(){
        $id = $this->input->get('p_id');   
        $this->load->model('product_model');
        $product_row = $this->product_model->getProduct($id);
        $product_options = $this->product_model->getProductAttributes($id, 'NAME');
        $product_options = $this->product_model->implodeAttributesByName($product_options);
        $data = array();
        if($product_row['o_success'] >= 1){
            $product_catid = $product_row['cat_id'];
            $data = array_merge($data,array( 
				'product' => $product_row,
				'product_options' => $product_options,
				'product_images' => $this->product_model->getProductImages($id),
				//'reviews' => $this->getReviews($id,$product_row['sellerid']),
				//'recommended_items'=> $this->product_model->getRecommendeditem($product_catid,5,$id),
				//'allowed_reviewers' => $this->product_model->getAllowedReviewers($id),
				//userdetails --- email/mobile verification info
				//'userdetails' => $this->product_model->getCurrUserDetails($uid),
                'product_quantity' => $this->product_model->getProductQuantity($id)
				));
		}
        echo json_encode($data,JSON_PRETTY_PRINT); 
    }
	
	//function searchbycategory($categoryId = 0,$url_string="string") # ROUTING: category/(:num)/(:any)
	public function searchbycategory()
	{
		$categoryId = $this->input->get('id_cat');
	
        //  Increase user preference for category
        $this->load->library("MemberCategoryPreferenceUtility");
        $memberCategoryPreferenceUtility = new MemberCategoryPreferenceUtility();
        $memberCategoryPreferenceUtility->upPreference($this->session->userdata('member_id'), $categoryId);
        
		$string_sort_a = "";
		$string_sort_c = "";
		$item_brand_string_1 ="";
		$item_brand_string_2 = "";
		$sort = "";
		$item_brand= "";
		$condition_price_string = '';
		$extra_string = '';
		$count = 0;
		$values = array();
		$start = 0;
		//$per_page = $this->per_page;
		$per_page = 6;

		$checkifexistcategory = $this->product_model->checkifexistcategory($categoryId);
		$response['subcategories'] = $this->product_model->getDownLevelNode($categoryId);
		if($categoryId != 0){
			if($checkifexistcategory != 0){

				if(isset($_GET['sop']))
				{
					$sort = $_GET['sop'];
					if($sort == "hot"){
						$string_sort_a = " ORDER BY a.is_hot desc,(`cat_id` = ".$categoryId.") DESC ";
						$string_sort_c = " ORDER BY c.is_hot desc,(`cat_id` = ".$categoryId.") DESC ";

					}elseif ($sort == "new") {
						$string_sort_a = " ORDER BY a.is_new desc ,(`cat_id` = ".$categoryId.") DESC ";
						$string_sort_c = " ORDER BY c.is_new desc,(`cat_id` = ".$categoryId.") DESC ";
					}elseif ($sort == "popular"){
						$string_sort_a = " ORDER BY clickcount desc ,(`cat_id` = ".$categoryId.") DESC ";
						$string_sort_c = " ORDER BY clickcount desc,(`cat_id` = ".$categoryId.") DESC ";
					}else{
						$string_sort_a = " ORDER BY (`cat_id` = ".$categoryId.") DESC";
						$string_sort_c = " ORDER BY (`cat_id` = ".$categoryId.") DESC";
					}
					unset($_GET['sop']);
				}

				if (isset($_GET['item_brand'])) {
					$item_brand = $_GET['item_brand'];
					$item_brand_string_1 =" AND c.name = '".$item_brand."' ";
					$item_brand_string_2 = " AND `es_brand`.`name` = '".$item_brand."' ";
					unset($_GET['item_brand']);
				} 

				//if(count($_GET) >= 1){
				if(count($_GET) >= 2){

					foreach($_GET as $parameter => $value){
						if($parameter == "condition") continue; 
						if($parameter == "price") continue; 
						if($parameter == "sop") continue; 
						if($parameter == "item_brand") continue; 
						$extra_string .= "OR  ( `name` = '".str_replace("_", " ", $parameter)."' AND attr_value = '$value' )  ";
						$count++;
					}

					$down_cat = $this->product_model->selectChild($categoryId);
					array_push($down_cat, $categoryId);
					$catlist_down = implode(",", $down_cat);

					//if(count($_GET) == 1 && isset($_GET['condition'])){
					if(count($_GET) == 2 && isset($_GET['condition'])){
						// echo '1--------------------------'; 
						$condition_price_string = $item_brand_string_1 ." AND a.condition = '".$_GET['condition']."'" .$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,$start,$per_page,$catlist_down);
						$itemparam = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,0,9999999,$catlist_down);

					//}elseif(count($_GET) == 1 && isset($_GET['price'])) {
					}elseif(count($_GET) == 2 && isset($_GET['price'])) {
						 
						if(strpos( $_GET['price'], 'to') !== false)
						{
							$price = explode('to', $_GET['price']);
						} else {
							$price = explode('to', '0.00to99999999.99');
						}
						// echo '2----------------------------';
						$condition_price_string = $item_brand_string_1 . " AND a.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1] .$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,$start,$per_page,$catlist_down);
						$itemparam = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,0,9999999,$catlist_down);

					//}elseif (count($_GET) == 2 && isset($_GET['condition']) && isset($_GET['price'])) {
					}elseif (count($_GET) == 3 && isset($_GET['condition']) && isset($_GET['price'])) {
						// echo '3---------------------';
						if(strpos( $_GET['price'], 'to') !== false)
						{
							$price = explode('to', $_GET['price']);
						} else {
							$price = explode('to', '0to99999999');
						}
						$condition_price_string = $item_brand_string_1. " AND a.condition = '".$_GET['condition']."' AND a.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1] .$string_sort_a;		
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,$start,$per_page,$catlist_down);
						$itemparam = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,0,9999999,$catlist_down);

					//}elseif(count($_GET) >= 2 && isset($_GET['condition']) && !isset($_GET['price']) ){
					}elseif(count($_GET) >= 3 && isset($_GET['condition']) && !isset($_GET['price']) ){
						// echo '4-----------------------------';
						$condition_price_string = " AND c.condition = '".$_GET['condition']."'" .$string_sort_c;		
						$extra_string = substr($extra_string, 2);	
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);
						$itemparam = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,0,9999999,$catlist_down,$item_brand_string_2);

					//}elseif(count($_GET) >= 2 && !isset($_GET['condition']) && isset($_GET['price']) ){
					}elseif(count($_GET) >= 3 && !isset($_GET['condition']) && isset($_GET['price']) ){
						// echo '5-------------------------';
						if(strpos( $_GET['price'], 'to') !== false)
						{
							$price = explode('to', $_GET['price']);
						} else {
							$price = explode('to', '0to99999999');
						}
						$condition_price_string = " AND c.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1] .$string_sort_c;		
						$extra_string = substr($extra_string, 2);
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);
						$itemparam = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,0,9999999,$catlist_down,$item_brand_string_2);

					}else{
						// echo '6-------------------------';
						$condition_price_string = "" .$string_sort_c;		
						$extra_string = substr($extra_string, 2);				  
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);
						$itemparam = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,0,9999999,$catlist_down,$item_brand_string_2);
					}	

				}else{
					// echo 'Else-------------------------';
					$usable_string = " ";
					$down_cat = $this->product_model->selectChild($categoryId);
					array_push($down_cat, $categoryId);
					$catlist_down = implode(",", $down_cat); 
					$response['items'] = $this->product_model->getProductInCategoryAndUnder($categoryId,$usable_string,$catlist_down,$start,$per_page,$item_brand_string_1.$string_sort_a);
					$itemparam = $this->product_model->getProductInCategoryAndUnder($categoryId,$usable_string,$catlist_down,0,9999999,$item_brand_string_1.$string_sort_a);

				}

				$array_condition_available = array();
				$array_brand_available = array();
				foreach ($itemparam as $row)
				{
					$values[] = $row['product_id'];
					$array_condition_available[] = $row['product_condition'];
					$array_brand_available[] = $row['product_brand'];
				} 

				$attribute = $this->product_model->getAttributesWithParam($categoryId,$values);

				for ($i=0; $i < sizeof($attribute) ; $i++) { 
					$look_up_list_item = $this->product_model->getAttributesWithParamAndName($categoryId,$values,$attribute[$i]['name']);
					array_push($attribute[$i], $look_up_list_item);
				}

				$_GET['item_brand'] = $item_brand;
				$_GET['sop'] = $sort;
				$arrayofparams = $attribute;
				$array_condition= array('name'=>'condition',array_unique($array_condition_available));
				array_unshift($arrayofparams,$array_condition);
				$array_main_brand= array('name'=>'item brand',array_unique($array_brand_available));
				array_unshift($arrayofparams,$array_main_brand);
				$response['category_id '] = $categoryId;
				$response['arrayofparams'] = $arrayofparams;  
				$response['id_cat'] = $categoryId;
				$breadcrumbs = $this->product_model->getParentId($categoryId);

				for($x=0; $x <= sizeof($response['subcategories']) -1 ; $x++){
					$id = $response['subcategories'][$x][3]; //id_cat
					$down_cat = $this->product_model->selectChild($id);		
					if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
						$down_cat = array();
					array_push($down_cat, $id);
					$db_cat_item = $this->product_model->getPopularitem($down_cat,1);
					$response['subcategories'][$x]['popular'] = $db_cat_item;
				}

				$response['breadcrumbs'] = $breadcrumbs;
				$response['main_categories'] = $this->product_model->getFirstLevelNode(true);
				
				echo json_encode($response,JSON_PRETTY_PRINT); 
				
				/*
				$data = array( 
					'title' => substr($url_string,0,-5).' | Easyshop.ph',
					); 
				$data = array_merge($data, $this->fill_header());
				$this->load->view('templates/header', $data); 
				$this->load->view('pages/product/product_search_by_category',$response);
				$this->load->view('templates/footer_full'); 
				*/

			}
			/*
			else{
				redirect('/category/all', 'refresh');
			}*/
		}
		/*else{
			redirect('/category/all', 'refresh');
		}*/
		
	}
	
	public function load_product() # ROUTING: category/load_product
	{
		$category_id = $this->input->get('id_cat');				  
		//$per_page = $this->per_page;
		$per_page = 6;
		$start = $this->input->get('page_number') * $per_page;
		
		$type = $this->input->get('type');
		$response['typeofview'] = $type;
		$extra_string = "";
		$count = 0;
		$condition_price_string = "";
		$string_sort_a = "";
		$string_sort_c ="";
		$item_brand = "";
		$item_brand_string_1 ="";
		$item_brand_string_2 = "";
        
		if($category_id != 0)
		{
			if(isset($_GET['parameters']['sop']))
			{
				$sort = $_GET['parameters']['sop'];
				if($sort == "hot"){
					$string_sort_a = " ORDER BY a.is_hot desc,(`cat_id` = ".$category_id.") DESC ";
					$string_sort_c = " ORDER BY c.is_hot desc,(`cat_id` = ".$category_id.") DESC ";

				}elseif ($sort == "new") {
					$string_sort_a = " ORDER BY a.is_new desc ,(`cat_id` = ".$category_id.") DESC ";
					$string_sort_c = " ORDER BY c.is_new desc,(`cat_id` = ".$category_id.") DESC ";
				}elseif ($sort == "popular"){
					$string_sort_a = " ORDER BY clickcount desc ,(`cat_id` = ".$category_id.") DESC ";
					$string_sort_c = " ORDER BY clickcount desc,(`cat_id` = ".$category_id.") DESC ";
				}else{
					$string_sort_a = " ORDER BY (`cat_id` = ".$category_id.") DESC";
					$string_sort_c = " ORDER BY (`cat_id` = ".$category_id.") DESC";
				}
				unset($_GET['parameters']['sop']);
			}
			if (isset($_GET['parameters']['item_brand'])) {
				$item_brand = $_GET['parameters']['item_brand'];
				if($item_brand != ""){
					$item_brand_string_1 =" AND c.name = '".$item_brand."' ";
					$item_brand_string_2 = " AND `es_brand`.`name` = '".$item_brand."' ";
				}
				unset($_GET['parameters']['item_brand']);
			}

			if(isset($_GET['parameters']) && !count($_GET['parameters']) <= 0)
			{	
				if(!count($_GET['parameters']) <= 0)
				{
					foreach($_GET['parameters'] as $parameter => $value){
						if($parameter == "condition") continue; 
						if($parameter == "price") continue; 
						if($parameter == "sop") continue; 
						if($parameter == "item_brand") continue; 
						$extra_string .= "OR  ( `name` = '".str_replace("_", " ", $parameter)."' AND attr_value = '$value' )  ";
						$count++;
					}

					$down_cat = $this->product_model->selectChild($category_id);
					array_push($down_cat, $category_id);
					$catlist_down = implode(",", $down_cat);

					if(count($_GET['parameters']) == 1 && isset($_GET['parameters']['condition'])){

						$condition_price_string =$item_brand_string_1. " AND a.condition = '".$_GET['parameters']['condition']."'".$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($category_id,$condition_price_string,$start,$per_page,$catlist_down);

					}elseif(count($_GET['parameters']) == 1 && isset($_GET['parameters']['price'])) {
						
						if(strpos( $_GET['parameters']['price'], 'to') !== false)
						{
							$price = explode('to', $_GET['parameters']['price']);
						} else {
							$price = explode('to', '0to99999999');
						}

						$condition_price_string =$item_brand_string_1. " AND a.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1].$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($category_id,$condition_price_string,$start,$per_page,$catlist_down);

					}elseif (count($_GET['parameters']) == 2 && isset($_GET['parameters']['condition']) && isset($_GET['parameters']['price'])) {

						
						if(strpos( $_GET['parameters']['price'], 'to') !== false)
						{
							$price = explode('to', $_GET['parameters']['price']);
						} else {
							$price = explode('to', '0to99999999');
						}

						$condition_price_string =$item_brand_string_1. "AND a.condition = '".$_GET['parameters']['condition']."' AND a.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1].$string_sort_a;		
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($category_id,$condition_price_string,$start,$per_page,$catlist_down);

					}elseif(count($_GET['parameters']) >= 2 && isset($_GET['parameters']['condition']) && !isset($_GET['parameters']['price']) ){

						$condition_price_string = "AND c.condition = '".$_GET['parameters']['condition']."'".$string_sort_c;		
						$extra_string = substr($extra_string, 2);	
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($category_id,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);

					}elseif(count($_GET['parameters']) >= 2 && !isset($_GET['parameters']['condition']) && isset($_GET['parameters']['price']) ){
 
						if(strpos( $_GET['parameters']['price'], 'to') !== false)
						{
							$price = explode('to', $_GET['parameters']['price']);
						} else {
							$price = explode('to', '0to99999999');
						}

						$condition_price_string = " AND c.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1].$string_sort_c;		
						$extra_string = substr($extra_string, 2);
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($category_id,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);

					}else{

						$condition_price_string = "".$string_sort_c;		
						$extra_string = substr($extra_string, 2);				  
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($category_id,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);

					}

					if(count($response['items']) <= 0)
					{
						//$data = json_encode('0');
						$data = '0';
					}else{
						//$data = json_encode($this->load->view('pages/product/product_search_by_category2',$response,TRUE));
						$data = $response;
					}  
				}	

			}else{
				 
				$attribute = $this->product_model->getAttributeByCategoryIdWithDistinct($category_id);

				for ($i=0; $i < sizeof($attribute) ; $i++) { 
					$look_up_list_item = $this->product_model->getAttributeByCategoryIdWithName($category_id,$attribute[$i]['name']);
					array_push($attribute[$i], $look_up_list_item);
				}

				$arrayofparams = $attribute;
				$response['typeofview'] = $type;
				$usable_string = "";
				$down_cat = $this->product_model->selectChild($category_id);
				array_push($down_cat, $category_id);
				$catlist_down = implode(",", $down_cat);
				$response['items'] = $this->product_model->getProductInCategoryAndUnder($category_id,$usable_string,$catlist_down,$start,$per_page,$item_brand_string_1.$string_sort_a);
				if(count($response['items']) <= 0)
				{
					//$data = json_encode('0');
					$data = '0';
				}else{
					//$data = json_encode($this->load->view('pages/product/product_search_by_category2',$response,TRUE));
					$data = $response;
				}

			}
			//echo $data;
			echo json_encode($data,JSON_PRETTY_PRINT);
		}
	}

}

/* End of file ios.php */
/* Location: ./application/controllers/home.php */
