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
		$this->load->vars(
			array('category_navigation' => $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat()), TRUE ),)
		);
	}

	public $per_page = 6;
	function searchbycategory($categoryId = 0,$url_string="string") # ROUTING: category/(:num)/(:any)
	{
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
		$per_page = $this->per_page;

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

				if(count($_GET) >= 1){

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

					if(count($_GET) == 1 && isset($_GET['condition'])){
						// echo '1--------------------------'; 
						$condition_price_string = $item_brand_string_1 ." AND a.condition = '".$_GET['condition']."'" .$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,$start,$per_page,$catlist_down);
						$itemparam = $items = $this->product_model->selectAllProductWithCategory($categoryId,$condition_price_string,0,9999999,$catlist_down);

					}elseif(count($_GET) == 1 && isset($_GET['price'])) {
						 
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

					}elseif (count($_GET) == 2 && isset($_GET['condition']) && isset($_GET['price'])) {
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

					}elseif(count($_GET) >= 2 && isset($_GET['condition']) && !isset($_GET['price']) ){
						// echo '4-----------------------------';
						$condition_price_string = " AND c.condition = '".$_GET['condition']."'" .$string_sort_c;		
						$extra_string = substr($extra_string, 2);	
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);
						$itemparam = $this->product_model->getProductByCategoryIdWithDistinct($categoryId,$condition_price_string,$extra_string,$count,0,9999999,$catlist_down,$item_brand_string_2);

					}elseif(count($_GET) >= 2 && !isset($_GET['condition']) && isset($_GET['price']) ){
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
				$response['main_categories'] = $this->product_model->getFirstLevelNodeAlphabetical(true);
				$data = array( 
					'title' => substr($url_string,0,-5).' | Easyshop.ph',
					);
				$data = array_merge($data, $this->fill_header());
				$this->load->view('templates/header', $data); 
				$this->load->view('pages/product/product_search_by_category',$response);
				$this->load->view('templates/footer_full'); 

			}else{
				redirect('/category/all', 'refresh');
			}
		}else{
			redirect('/category/all', 'refresh');
		}
	}

	function load_product() # ROUTING: category/load_other_product
	{
		$category_id = $_POST['id_cat'];
		$start = $_POST['page_number'];
		$type = $_POST['type'];
		$response['typeofview'] = $type;				  
		$per_page = $this->per_page;
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
			if(isset($_POST['parameters']['sop']))
			{
				$sort = $_POST['parameters']['sop'];
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
				unset($_POST['parameters']['sop']);
			}
			if (isset($_POST['parameters']['item_brand'])) {
				$item_brand = $_POST['parameters']['item_brand'];
				if($item_brand != ""){
					$item_brand_string_1 =" AND c.name = '".$item_brand."' ";
					$item_brand_string_2 = " AND `es_brand`.`name` = '".$item_brand."' ";
				}
				unset($_POST['parameters']['item_brand']);
			}

			if(isset($_POST['parameters']) && !count($_POST['parameters']) <= 0)
			{	
				if(!count($_POST['parameters']) <= 0)
				{
					foreach($_POST['parameters'] as $parameter => $value){
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

					if(count($_POST['parameters']) == 1 && isset($_POST['parameters']['condition'])){

						$condition_price_string =$item_brand_string_1. " AND a.condition = '".$_POST['parameters']['condition']."'".$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($category_id,$condition_price_string,$start,$per_page,$catlist_down);

					}elseif(count($_POST['parameters']) == 1 && isset($_POST['parameters']['price'])) {
						
						if(strpos( $_POST['parameters']['price'], 'to') !== false)
						{
							$price = explode('to', $_POST['parameters']['price']);
						} else {
							$price = explode('to', '0to99999999');
						}

						$condition_price_string =$item_brand_string_1. " AND a.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1].$string_sort_a;
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($category_id,$condition_price_string,$start,$per_page,$catlist_down);

					}elseif (count($_POST['parameters']) == 2 && isset($_POST['parameters']['condition']) && isset($_POST['parameters']['price'])) {

						
						if(strpos( $_POST['parameters']['price'], 'to') !== false)
						{
							$price = explode('to', $_POST['parameters']['price']);
						} else {
							$price = explode('to', '0to99999999');
						}

						$condition_price_string =$item_brand_string_1. "AND a.condition = '".$_POST['parameters']['condition']."' AND a.price BETWEEN ".(double)$price[0]." AND ".(double)$price[1].$string_sort_a;		
						$response['items'] = $items = $this->product_model->selectAllProductWithCategory($category_id,$condition_price_string,$start,$per_page,$catlist_down);

					}elseif(count($_POST['parameters']) >= 2 && isset($_POST['parameters']['condition']) && !isset($_POST['parameters']['price']) ){

						$condition_price_string = "AND c.condition = '".$_POST['parameters']['condition']."'".$string_sort_c;		
						$extra_string = substr($extra_string, 2);	
						$response['items'] = $this->product_model->getProductByCategoryIdWithDistinct($category_id,$condition_price_string,$extra_string,$count,$start,$per_page,$catlist_down,$item_brand_string_2);

					}elseif(count($_POST['parameters']) >= 2 && !isset($_POST['parameters']['condition']) && isset($_POST['parameters']['price']) ){
 
						if(strpos( $_POST['parameters']['price'], 'to') !== false)
						{
							$price = explode('to', $_POST['parameters']['price']);
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
						$data = json_encode('0');
					}else{
						$data = json_encode($this->load->view('pages/product/product_search_by_category2',$response,TRUE));
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
					$data = json_encode('0');
				}else{
					$data = json_encode($this->load->view('pages/product/product_search_by_category2',$response,TRUE));
				}

			}
			echo $data;
		}
	}

	function sch_onpress()
	{
		$items = $this->product_model->itemKeySearch();
		$json = 'var tipuedrop = {"pages": [';
		foreach ($items as $key => $value) {
			$json .= '{"title": "'.html_escape($value).'","loc":"sch?q_str='.urlencode($value).'&q_cat=0"},';
		}
		$json .= ']};';
		echo $json ;
	}

	function sch($string="search.html") # ROUTING: search/(:any)
	{

		$values = array();
		$string_sort = "";
		$start = 0;
		$usable_string;
		$per_page = $this->per_page;
		$category = $this->input->get('q_cat');
		
		if (isset($_GET['q_str'])) {
			if($_GET['q_str'] == "" && $_GET['q_cat'] == 1)
			{
				redirect('/category/all/', 'refresh');
			}elseif($_GET['q_str'] == "" && $_GET['q_cat'] != 1){
				redirect('/product/searchbycategory/'.$_GET['q_cat'] , 'refresh');
			}else{

				if($category == 1){
					$category_name = "";
				}else{
					$category_details = $this->product_model->selectCategoryDetails($category);
					$category_name = $category_details['name'];
				}

				$string = ' '.ltrim($_GET['q_str']); 
				$words = "+".implode("*,+",explode(" ",trim($string)))."*"; 
				$checkifexistcategory = $this->product_model->checkifexistcategory($category);
				
					if($checkifexistcategory == 0 || $category == 1)
					{
						$usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
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
					$response['category_cnt'] = '<ul><li>Categories</li>'.$list.'</ul>';
				}else{	
					$response['category_cnt'] = '<ul><li>Categories</li><ul><li>'.$category_name.'</li>'.$list.'</ul></ul>';
				}

				// end here

				$response['id_cat'] = $category;
				$data = array(
					'title' => 'Easyshop.ph',
					);
				$data = array_merge($data, $this->fill_header());
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
		$category_id = $_POST['id_cat']; 
		$start = $_POST['page_number'];
		$type = $_POST['type'];	
		$per_page = $this->per_page;

		$string = ' '.ltrim($_POST['parameters']['q_str']);
		$category = $_POST['parameters']['q_cat'];
		$words = "+".implode("*,+",explode(" ",trim($string)))."*"; 
		$checkifexistcategory = $this->product_model->checkifexistcategory($category);

		
			if($checkifexistcategory == 0 || $category == 1)
			{
				$usable_string = " AND  MATCH(a.name,keywords) AGAINST('".$words."' IN BOOLEAN MODE)";
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
        
    
		if($product_row['o_success'] >= 1){
			$this->session->set_userdata('product_id', $id);
			$product_catid = $product_row['cat_id'];
			$data = array_merge($data,array( 
				'page_javascript' => 'assets/JavaScript/productpage.js',
				'breadcrumbs' =>  $this->product_model->getParentId($product_row['cat_id']),
				'product' => $product_row,
				'product_options' => $this->product_model->getProductAttributes($id, 'NAME'),
				'product_images' => $this->product_model->getProductImages($id),
				'main_categories' => $this->product_model->getFirstLevelNodeAlphabetical(TRUE),
				'reviews' => $this->getReviews($id,$product_row['sellerid']),
				'uid' => $uid,
				'recommended_items'=> $this->product_model->getRecommendeditem($product_catid,5,$id),
				'allowed_reviewers' => $this->product_model->getAllowedReviewers($id),
				//userdetails --- email/mobile verification info
				'userdetails' => $this->product_model->getCurrUserDetails($uid),
                'product_quantity' => $this->product_model->getProductQuantity($id), 
				));

			$data['vendorrating'] = $this->product_model->getVendorRating($data['product']['sellerid']);
			$this->load->view('pages/product/productpage_view', $data); 
		}
		else
			$this->load->view('pages/product/product_error', $data); 		
		$this->load->view('templates/footer_full');

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
			//$sellerid = $this->product_model->getProduct($id)['sellerid']; eto yung dati
			$sellerid = $this->product_model->getProduct($id);
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
		$categories = $this->product_model->getFirstLevelNodeAlphabetical();
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
			'categories' => $categories,
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
	
	function updateIsDelete($productid=0, $is_delete){
		$memberid = $this->session->userdata('member_id');
		$this->product_model->updateIsDelete($productid, $memberid, $is_delete);
		redirect('memberpage', 'refresh');
	}
    
}

  
/* End of file product.php */
/* Location: ./application/controllers/product.php */
