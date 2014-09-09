<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    

class product_search extends MY_Controller {
	
    function __construct()  
    { 
      parent::__construct(); 
      $this->load->helper('htmlpurifier');
      $this->load->model("product_model");
      $this->load->model("search_model");
    }
  
    /*   
     *   Number of returned products per request
     */
    public $per_page = 15; 
	
	/**
	*  Advance search main function
	*  route: advsrch
	*/
	function advance_search(){

		$data = array(
			'shiploc' => $this->product_model->getLocation(),
			'title' => 'Easyshop.com - Advanced Search'
		);
		$data = array_merge($data, $this->fill_header());

		$condition = $this->input->get();
		$category = $this->input->get('_cat');
		$gsc = $this->input->get('_subcat');
		$gis = $this->input->get('_is');
		$gus = $this->input->get('_us');
		$gloc = $this->input->get('_loc');
		$gcon = $this->input->get('_con');
		$gp1 = $this->input->get('_price1');
		$gp2 = $this->input->get('_price2');
		$sort = $this->input->get('_sop');
		
		if(!is_numeric($category)){
			$category = 1;
		}
		
		if(!is_numeric($gsc)){
			$gsc = "";
		}
		
		if(!is_numeric($gloc)){
			$gloc = "";
		}
		
		if(strlen($gp1) > 0 && strlen($gp2) > 0){
			if(!is_numeric($gp1) && !is_numeric($gp2)){
				$gp1 = "";
				$gp2 = "";
			}
		}		
		
		$response['condition'] = $condition;
		$response['getcat'] = $category;
		$response['getsubcat'] = $gsc;
		$response['getis'] = $gis;
		$response['getus'] = $gus;
		$response['getloc'] = $gloc;
		$response['getcon'] = $gcon;
		$response['getprice1'] = $gp1;
		$response['getprice2'] = $gp2;
		$response['getsop'] = $sort;			
		$response['items'] = "";
		$response['firstlevel'] = $this->product_model->getFirstLevelNode();
		$response['ctrl_subcat'] = $this->product_model->getDownLevelNode(1);

		
		if($condition){

			$response['ctrl_subcat'] = $this->product_model->getDownLevelNode($category);	
			$start = 0; # start series
			$per_page = $this->per_page; # no of display
			$catID = $category;
			$test = "";

			##### Parameters starts here ####################################################		

			$main_cat = $this->product_model->selectChild($catID);

			if($main_cat[0] != 0 && $main_cat[1] != 0){
				$cat = implode(',', $main_cat);
				$catID = $category . ", " . $cat;
			}
        
			
			$sub_cat = $this->product_model->selectChild($gsc);	 
			if($sub_cat[0] == 0 && $sub_cat[1] == 0){
				$gsubcat = $gsc;
			}else{
				$gsubcat = implode(',', $sub_cat);
			} // end - subcat check			
						
			$othr_att = array();
			$brnd_att = array();
			$ctrO = 0;
			foreach ($condition as $name => $val) { # get all values from querystring
				
				$chk = strpos($name, "_");
				if($chk === false && $name != "BRAND") { # other item attributes			
					if (is_array($val)){ # this is for checkboxes with multiple values.
						foreach($val as $row => $values){
							array_push($othr_att, array(
									"c" => $ctrO,
									"q" => " (REPLACE(UPPER(ea.`name`),' ','') = :sn". $ctrO ." AND UPPER(epa.`attr_value`) = :sa". $ctrO .") ",
									"n" => strtoupper($name),
									"a" => strtoupper($values)
								)							
							);
							$ctrO = $ctrO + 1;	
						}
					}
				} # other item attributes - end
								
				if($name == "BRAND"){ # brand					
					if(is_array($val)){
						$brnd_att = $val;
					}	
				} # brand - end
				
			} # get all values from querystring - end

			##### Parameters end here ####################################################
								
			# get all items here (right pane)			
			$items = $this->search_model->advance_search($catID, $start, $per_page, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att);
			$cntr = count($this->search_model->advance_search($catID, 0, PHP_INT_MAX, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att));
	
			$response['items'] = $items; ### pass to view
			$response['cntr'] = $cntr;   ### pass to view
		
			$all_products_in_category = $this->product_model->getProductsByCategory($catID);

			$pid_values = array();
			$bid_values = array();
			foreach ($all_products_in_category as $p){
				$pid_values[] = $p['product_id'];
				$bid_values[] = $p['brand_id'];
			}						
			
			# get all attributes here (left pane)
			
			# brands
			$get_brand_array = array();
			$brand_names = $this->product_model->getBrandById($bid_values);
			$brand_names = ($brand_names)?$brand_names:array();
 
			foreach ($brand_names as $bn) {
				array_push($get_brand_array,$bn['name']);	
			}
			$fin_brand_array = array('name'=>'BRAND',$get_brand_array);
			
			# attribute group
			$attribute = $this->product_model->getAttributesWithParam($catID,$pid_values);
			# attribute values		
			for ($i=0; $i < sizeof($attribute) ; $i++) { 
				$attrib_values = $this->product_model->getAttributesWithParamAndName($catID,$pid_values,$attribute[$i]['name']);
				array_push($attribute[$i], $attrib_values);	
			}
			# merging of attributes and brand names
			array_unshift($attribute,$fin_brand_array);
					
			$response['arrayofparams'] = $attribute; ### pass to view
			
			###########################################
        }
        $response['default'] = true;
        $data['render_searchbar'] = false;
        $this->load->view('templates/header', $data); 
        $this->load->view('pages/search/advance_search_main_responsive',$response);
        $this->load->view('templates/footer');		
    }


    
    /*   
     *  Return more advanced search results via ajax request
     *  route: advsrch/more
     */
	
	function advance_search_more() # ROUTING
	{
		if($this->input->post()){
				
			$start = $this->input->post('page_number') * $this->per_page; # start series
			$per_page = $this->per_page ; # no of display
			$condition = $this->input->post('parameters');		
			$category = $this->input->post('id_cat');
			$test = "";
						
			if(!is_numeric($category)){
				$category = 1;
			}
			
			$catID = $category;
			$gsc = ""; 
			$gis = ""; 
			$gus = "";	
			$gloc = ""; 
			$gcon = "";		
			$gp1 = "";
			$gp2 = "";		
			$sort = "";			

			##### Parameters starts here ####################################################	

			$othr_att = array();
			$brnd_att = array();
			$ctrO = 0;

			$condition = ($condition !== 'false')?$condition:array();
		      
			foreach ($condition as $name => $val) {
				
				$chk = strpos($name, "_");
				if($chk === false && $name != "BRAND") {			
					if (is_array($val)){ # this is for checkboxes with multiple values.
						foreach($val as $row => $values){
							array_push($othr_att, array(
									"c" => $ctrO,
									"q" => " (REPLACE(UPPER(ea.`name`),' ','') = :sn". $ctrO ." AND UPPER(epa.`attr_value`) = :sa". $ctrO .") ",
									"n" => strtoupper($name),
									"a" => strtoupper($values)
								)							
							);
							$ctrO = $ctrO + 1;	
						}
					}
				}else{
					if($name == "BRAND"){					
						if(is_array($val)){
							$brnd_att = $val;
						}	
					}
				
					if($name == "_subcat" && !empty($val)){ 
						if(!is_numeric($val)){
							$val = "";
						}						
						$gsc = $val;
					}
					if($name == "_is" && !empty($val)){ $gis = $val; }
					if($name == "_us" && !empty($val)){ $gus = $val; }
					if($name == "_loc" && !empty($val)){ 
						if(!is_numeric($val)){
							$val = "";
						}
						$gloc = $val;
					}
					if($name == "_con" && !empty($val)){ $conA = $val; }
					if($name == "_price1" && !empty($val)){
						if(!is_numeric($val)){
							$val = "";
						}
						$gp1 = $val;
					}
					if($name == "_price2" && !empty($val)){
						if(!is_numeric($val)){
							$val = "";
						}						
						$gp2 = $val;
					}
					if($name == "_sop" && !empty($val)){ $sort = $val; }

				}
			}

			$main_cat = $this->product_model->selectChild($catID);
			if($main_cat[0] != 0 && $main_cat[1] != 0){
				$cat = implode(',', $main_cat);
				$catID = $category . ", " . $cat;
			}
			
			$sub_cat = $this->product_model->selectChild($gsc);	 
			if($sub_cat[0] == 0 && $sub_cat[1] == 0){
				$gsubcat = $gsc;
			}else{
				$gsubcat = implode(',', $sub_cat);
			}

			##### Parameters end here ####################################################
			
					
			# get all items here (right pane)
			$items = $this->search_model->advance_search($catID, $start, $per_page, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att);
			if(isset($items) && !empty($items)){ # check if it has items		
				$response['items'] = $items; ### pass to view	
				$data = json_encode($this->load->view('pages/search/advance_search_more',$response,TRUE));
				echo $data;
			}else{
				echo "0";
			}						
		} 
	} 

     /**
     *   Returns recommended keywords for search bar
     */
    function sch_onpress()
    {  
    	header('Content-Type: text/plain'); 
    	if($this->input->get('q')){

    		$html = "";
    		$stringData =  $this->input->get('q'); 
    		$string = ltrim($stringData);  
    		$words = explode(" ",trim($string)); 
    		$keywords = $this->search_model->itemKeySearch($words);
           
    		if(count($keywords) <= 0){
    			$html = 0;
    		}else{
    			$html .= "<ul>";
    			foreach ($keywords as $value) {
    				$showValue = $this->highlights($value,$stringData);
    				$html .= "<li><a href='".base_url()."search/search.html?q_str=".urlencode($value)."&q_cat=1'>".$showValue."</a></li>";

    			}
    			$html .= "</ul>";
    		}

    		echo $html;
    	}
    }

    /*   
     *   Returns results of searching products through the search bar
     *   Route: search/(:any)
     */
    function searchfaster()
    {
        $category = $this->input->get('c')?$this->input->get('c') : 1;
        $string =  $this->input->get('s')?$this->input->get('s') : '';
    	if($string !== ''){
            /* Prepare words for query. */
            $words = explode(" ",html_escape(trim(urldecode($string))));
            $implodeWords = implode('* ', $words) . '*';
            echo 'Words: '. $implodeWords .'<br/>';
            $get = $this->input->get();
            unset($_GET['s']);
            unset($_GET['c']);

            echo 'Other parameters: ', print_r($this->input->get());
        }
        else{
            echo 'no words';
        }
    }
    
    /**
     *   Load products as user search through the search bar
     *   Route: search_more
     *   @param string $string
     *   @return JSON
     */
    function search($string="search.html") 
    {
        $values = array();
        $string_sort = "";
        $start = 0;
        $usable_string;
        $perPage = $this->per_page;
        $category = $this->input->get('q_cat')?$this->input->get('q_cat'):1;
        $string =  $this->input->get('q_str')?$this->input->get('q_str'):'';
        $string = html_escape(ltrim($string));
        $response['string'] = $string;
        $category_details = $this->product_model->selectCategoryDetails($category);
        $category_name = $category_details['name'];
        $this->load->config('protected_category',TRUE);
        $promo_category_id = $this->config->item('promo');

        if(intval($category) === intval($promo_category_id)){
            redirect('/cat/all/', 'refresh');
        }

        if($string == "" && $category == 1){
            redirect('/cat/all/', 'refresh');
        }
        elseif($string == "" && $category != 1){
            redirect('/category/'. $category_details['slug'], 'refresh');
        }
        else{
            $response['get_params'] = $this->input->get(); 
            $words = explode(" ",trim($string)); 
            $checkifexistcategory = $this->product_model->checkifexistcategory($category);
            unset($_GET['q_str']);
            unset($_GET['q_cat']);

            if($checkifexistcategory == 0 || $category == 1){
                $categories = "SELECT cat_id FROM es_product WHERE is_delete = 0 AND is_draft = 0";
            }
            else{
                $down_cat = $this->product_model->selectChild($category);
                array_push($down_cat, $category);
                $categories = implode(",", $down_cat);
            }

            $count = 0; 
            $operator = " = ";    
            $sortString = ""; 
            $conditionArray = array(); 

            if(!count($_GET) <= 0){
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
                        }
                        else{ 
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

                    }
                    elseif (ucfirst(strtolower($key)) == "Sop") { 

                        $sortValue = ucfirst(strtolower($_GET[$key]));
                        if($sortValue == ucfirst(strtolower('hot'))){
                            $sortString = " is_hot DESC , ";
                        }
                        elseif ($sortValue == ucfirst(strtolower('new'))) {
                            $sortString = " is_new DESC , ";
                        }
                        elseif ($sortValue == ucfirst(strtolower('popular'))) {
                            $sortString = " clickcount DESC , ";
                        }
                        else{
                            $sortString = "";
                        } 

                    }
                    elseif (ucfirst(strtolower($key)) == "Price") { 

                        if(strpos($_GET[$key], 'to') !== false) {   

                            $price = explode('to', $_GET[$key]);
                        }
                        else{

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
                    }
                    else{
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
                        }
                        else{
                            $key = strtolower(str_replace("_", " ", $key)); 
                            $conditionArray['attributes'][$key] = $value; 
                        }
                    }
                }
            }

            $response['items'] = $this->product_model->getProductsByCategory($categories,$conditionArray,$count,$operator,$start,$perPage,$sortString,$words);

            // GETTING ALL ATTRIBUTES FOR FILTERS

            $ids = array();
            foreach ($response['items'] as $key) {
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

            $response['attributes'] = $organizedAttribute;

            // start here GET DOWN CATEGORIES
            $firstdownlevel = $this->product_model->getDownLevelNode($category); 
            $newbuiltarray = array();
            $cnt = 0;
            $item_total_cnt = 0;

            foreach ($firstdownlevel as $keyfirstlevel => $value) {
            
                $count_main = 0;
                $noitem = false; 
                $newcat = $value['id_cat'];  
                $down_cat = $this->product_model->selectChild($newcat);
                if(empty($down_cat)){
                    $down_cat = array();
                }
                
                array_push($down_cat,$value['id_cat']);
                $catlist_down = implode(",", $down_cat);
                $count_main_new = count($this->product_model->getProductInCategoryAndUnder($words,$catlist_down,0,9999999999));
                $count_main += $count_main_new;
                $item_total_cnt += $count_main;
                $cnt = $keyfirstlevel;
                array_push($newbuiltarray, array("name"=>$value['name'],"item_id"=> $value['id_cat'],"parent_id"=> $value['parent_id'],"count"=>0,"children"=>array()));
                $secondlevel = $this->product_model->getDownLevelNode($value['id_cat']);

                foreach ($secondlevel as $key){
                    $count = 0;
                    $newcat = $key['id_cat'];  
                    $down_cat = $this->product_model->selectChild($newcat);
                    array_push($down_cat,$newcat);
                    $catlist_down = implode(",", $down_cat); 
                    $count_new = count($this->product_model->getProductInCategoryAndUnder($words,$catlist_down,0,9999999999));	
                    $count += $count_new;
                    if(!$count <= 0)
                    {
                        array_push($newbuiltarray[$cnt]['children'], array("name"=>$key['name'],"item_id"=> $key['id_cat'],"parent_id"=> $key['parent_id'],"count"=>$count));
                    }
                }
                $newbuiltarray[$cnt]['count'] = $count_main;
            }

            $newbuiltarray = array("name"=>$category_name,"children" => $newbuiltarray);

            $list = $this->toUL($newbuiltarray['children'],$string);

            if($category_name == "PARENT" || $category == 1){
                $response['category_cnt'] = '<h3>Categories</h3>' . $list;
            }else{
                $vcnt = "";
                if($item_total_cnt > 0){
                    $vcnt = "(" . $item_total_cnt . ")";
                }
                $response['category_cnt'] = '<h3>Categories</h3><ul><li>'.$category_name. $vcnt .'</li><li>'.$list.'</li></ul>';
            }
            
            $response['cntr'] = $item_total_cnt;

            // end here

            $response['id_cat'] = $category;
            $data = array(
                'title' => ($string==='')?'Search | Easyshop.ph':$string.' | Easyshop.ph',
                );

            $data = array_merge($data, $this->fill_header());
            $response['category_navigation'] = $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE );
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/search/product_search_by_searchbox',$response);
            $this->load->view('templates/footer'); 
        }
    }

    /**
     *   Load more products as user scroll through the search results through
     *   the search bar
     *   Route: search_more
     *   @return JSON
     */
    function search_more() 
    {
    	$string_sort = "";
    	$per_page = $this->per_page;

        $post_data = $this->input->post();
        $category_id = $post_data['id_cat']; 

        $this->load->config('protected_category', TRUE);
        $promo_category_id = $this->config->item('promo');
        if(intval($category_id) === intval($promo_category_id)){
            exit();
        }

        $start = $post_data['page_number'] * $per_page;
        $type = $post_data['type'];
        $params = get_object_vars(json_decode($post_data['parameters']));
        $string = ' '.ltrim($params['q_str']);
        $category =  $params['q_cat']; 
        $words = explode(" ",trim($string));  
        $checkifexistcategory = $this->product_model->checkifexistcategory($category);
        unset($params['q_str']);
        unset($params['q_cat']);

        if($checkifexistcategory == 0 || $category == 1){ 
            $categories = "SELECT cat_id FROM es_product WHERE is_delete = 0 AND is_draft = 0";
        }
        else{ 
            $down_cat = $this->product_model->selectChild($category);
            array_push($down_cat, $category);
            $catlist_down = implode(",", $down_cat);
        }
        
        $count = 0; 
        $operator = " = ";    
        $sortString = ""; 
        $conditionArray = array(); 
        
        if(!count($params) <= 0){
            foreach ($params as $key => $value) {
                if(ucfirst(strtolower($key)) == "Brand"){
                    if(strpos($params[$key], '-|-') !== false) {
                        $var = explode('-|-',$params[$key]);
                        $needString = array();
                        foreach ($var as $varkey => $varvalue) {
                            array_push($needString, "'$varvalue'");
                        } 
                        $conditionArray['brand'] = array(
                            'value' => $var,
                            'count' => count($var)
                            );
                    }
                    else{ 
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

                }
                elseif (ucfirst(strtolower($key)) == "Sop") { 

                    $sortValue = ucfirst(strtolower($params[$key]));
                    if($sortValue == ucfirst(strtolower('hot'))){
                        $sortString = " is_hot DESC , ";
                    }
                    elseif ($sortValue == ucfirst(strtolower('new'))) {
                        $sortString = " is_new DESC , ";
                    }
                    elseif ($sortValue == ucfirst(strtolower('popular'))) {
                        $sortString = " clickcount DESC , ";
                    }
                    else{
                        $sortString = "";
                    } 

                }
                elseif (ucfirst(strtolower($key)) == "Price") { 

                    if(strpos($params[$key], 'to') !== false) {   

                        $price = explode('to', $params[$key]);
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
                }
                else{
                    $count++; 
                    if(!isset($conditionArray['attributes'])){
                        $conditionArray['attributes'] = array();
                    }

                    if(strpos($params[$key], '-|-') !== false) {
                        $var = explode('-|-',$params[$key]);      
                        $key = strtolower(str_replace("_", " ", $key));
                        foreach ($var as $varkey => $varvalue) {
                            $conditionArray['attributes'][$key] = $var;
                        }    
                    }
                    else{
                        $key = strtolower(str_replace("_", " ", $key)); 
                        $conditionArray['attributes'][$key] = $value; 
                    }           
                }
            }
        }    
        
        $response['items'] = $this->product_model->getProductsByCategory($categories,$conditionArray,$count,$operator,$start,$per_page,$sortString,$words);
        $response['typeofview'] = $type;
        $response['id_cat'] = $category;

        if(count($response['items']) <= 0){
            $data = json_encode('0');
        }
        else{
            $data = json_encode($this->load->view('pages/search/product_search_by_searchbox_more',$response,TRUE));
        }
        echo $data; 
    }
    
    /**
     *   Search category list using string and organized based on its parent
     *   through the search bar
     *   Route: searchCategory
     *   @return JSON
     */
    public function searchCategory(){  
        
        $userId = $this->session->userdata('member_id');
        $isAdmin = false;
        if($userId){
            $this->load->model('user_model');
            $userdetails = $this->user_model->getUserById($userId);
            $isAdmin = (intval($userdetails['is_admin']) === 1);
        }

        $this->config->load('protected_category', TRUE);
        $protected_categories = $this->config->config['protected_category'];

        $string = $this->input->get('data');
        $explodString = explode(' ', trim($string));
        $newString = '+'.implode('* +', $explodString).'*';  
        $rows = $this->search_model->searchCategory($newString);
        foreach($rows as $idx=>$row){
            if(in_array($row['id_cat'],$protected_categories) && !$isAdmin){
                unset($rows[$idx]);
                continue;
            }
            $rows[$idx]['parent'] = $this->product_model->getParentId($row['id_cat']);
        }

        echo json_encode($rows);
    }

    /**
     *   Search Brand avaialable usjng given string
     *   Route: searchBrand
     *   @return JSON
     */
    public function searchBrand()
    {
	    $string = $this->input->get('data');
        $explodString = explode(' ', trim($string));
        $newString = '+'.implode('* +', $explodString).'*';  
	    $rows = $this->search_model->searchBrand($newString);
	    echo json_encode($rows);
    }

    /**
     *  Hightlight string search to the available words
     *  @param string $text
     *  @param string $words
     *  @return string $text
     */
    private function highlights($text, $words)
    {
        $words = preg_replace('/\s+/', ' ',$words);
        $splitWords = explode(" ", $words);
        foreach($splitWords as $word){
            $color = "#e5e5e5";
            $text = preg_replace("|($word)|Ui","<mark>$1</mark>" , $text );
        } 

        return $text;
    }


    private function toUL($array = array(), $string = '')
    {
        $html = '<ul>' . PHP_EOL;
        foreach ($array as $value)
        {
            if($value['count'] <= 0){
                continue;
            }
            $html .= '<li><a href="search.html?q_str=' . $string .'&q_cat='.$value['item_id'].'">' . $value['name'].'('.$value['count'].')</a>';
            if (!empty($value['children'])){
                $html .= $this->toUL($value['children'], $string);
            }
            $html .= '</li>' . PHP_EOL;
        }

        $html .= '</ul>' . PHP_EOL;

	    return $html;
    }


}

/* End of file product_search.php */
/* Location: ./application/controllers/product_search.php */