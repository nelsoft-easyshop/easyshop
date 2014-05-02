<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class product_search_v2 extends MY_Controller {
	
	function __construct()  
	{ 
		parent::__construct(); 
		$this->load->helper('htmlpurifier');
		$this->load->model("search_model");				
	}
	
	function arrvwr($target){
		if(is_array($target)){
			echo "<pre>";
			print_r($target);
			echo "</pre>";
		}else{
			echo "<br>----------------<br> No array found or array is empty <br>----------------<br>";
		}
		return;
	}
	
	function getBranch()
	{
		$q = $this->input->get('q');
		
		if(!empty($q)){			
			$brand_names = $this->search_model->getBrandName($q,'name');
			echo json_encode($brand_names);			
		}		
	}
	
	function getChild() # this function for getting the under category from selected category 
	{	

		header('Content-Type: application/json');
		$id = $this->input->post('cat_id'); 
		$name = $this->input->post('name');
		$parents = $this->search_model->getDownLevelNode($id);
		$response['cat_id'] = $id;
		$response['node'] = $parents;
		$response['level'] = html_escape($this->input->post('level'));	

		$attribute = $this->search_model->getAttributesByParent($id);
		
		for ($i=0 ; $i < sizeof($attribute) ; $i++ ) {  # getting all lookuplist from item attribute
				$lookuplist = $this->search_model->getLookItemListById($attribute[$i]['attr_lookuplist_id']);
				array_push($attribute[$i],$lookuplist);
		}
		$response['attribute'] = $attribute;		

		$data = json_encode($this->load->view('pages/search/advance_search_carousel',$response,TRUE));
		echo $data;
	}
	
	
	public $per_page = 10; # number of displayed products
	function advsrch2(){

		$data = array(
			'shiploc' => $this->product_model->getLocation(),
			'title' => 'Easyshop.com - Advanced Search'
		);
		$data = array_merge($data, $this->fill_header());
		$response['firstlevel'] = $this->search_model->getFirstLevelNode();
		$response['ctrl_subcat'] = $this->product_model->getDownLevelNode(1);
		$response['items'] = "";

		$condition = $this->input->get();
		$response['condition'] = $condition;
		
		if($condition){
			$category = $this->input->get('_cat');
			if($category){ // kapag may laman ang main category magsesearch
				
				$response['ctrl_subcat'] = $this->product_model->getDownLevelNode($category);
				/////////////////////////////////////////////////////////////////////////////
				
					$category = $this->input->get('_cat');	
					$start = 0; # start series
					$per_page = $this->per_page; # no of display
					$test = "";			
								
					$getParentId = $this->search_model->getParentId($category);
					if(!empty($getParentId)){
						foreach($getParentId as $row){
							$ParentID[] = $row['id_cat'];		
						}
						$ParentIDList = implode(',', $ParentID);
					}else{
						$ParentIDList = $id;
					}
		
					$child = $this->search_model->selectChild($category);				
					if($child[0] == 0 && $child[1] == 0){
						$catID = $category;
					}else{
						$catID = implode(',', $child);
					} // end - array check
								
					$attr_values = "";
					$attr_values_array = "";
					$attr_brand = ""; # Brands
					$ctr = 0;
					$ctrA = 0;
					foreach ($condition as $name => $val) { # get all values from querystring
						
						$chk = strpos($name, "_");
						if($chk === false && $name != "BRAND") { # other item attributes			
							if (is_array($val)){ # this is for checkboxes with multiple values.
								foreach($val as $row => $values){
									$ctrA = $ctrA + 1;
									$attr_values_array = $attr_values_array . " OR (REPLACE(UPPER(ea.`name`),' ','') = '". strtoupper($name) ."' AND UPPER(epa.`attr_value`) = '" . strtoupper($values) ."') ";
								}
							}else{ # single value
								if(!empty($val)){
									$ctr = $ctr + 1;
									$attr_values = " OR (REPLACE(UPPER(ea.`name`),' ','') = '". strtoupper($name) ."' AND UPPER(epa.`attr_value`) = '" . strtoupper($val) ."') ";
								}
							}// end
						} # other item attributes - end
						
						if($name == "BRAND"){ # brand					
							if(is_array($val)){
								$arr_brand = "";
								foreach($val as $row => $brands){
									if(!empty($brands)){
										$arr_brand = $arr_brand . "'" . $brands . "',";
									}
								}
								
								$fin_arr_brand = substr($arr_brand, 0, strlen($arr_brand) - 1 );
								
								if(!empty($fin_arr_brand)){
									$attr_brand = $attr_brand . " AND eb.`name` IN (". $fin_arr_brand . ") ";
								}
							}	
						} # brand end
					} # get all values from querystring - end
					
					$raw_attr_values = $attr_values . $attr_values_array;
					$fix_attr_values = substr($raw_attr_values, 3, strlen($raw_attr_values));
					
					$fin_attr_values = "";
					$fin_count = 0;
					if(isset($fix_attr_values)){
						$fin_attr_values = " AND (" . $fix_attr_values . ")";
						$fin_count = ($ctr + $ctrA);
					}
					
					##### Parameters starts here ####################################################
					
					///// ATTRIBUTES ////////////////////////////
					
					$QAtt = "";
					if (!empty($fin_attr_values) && !empty($fin_count)){	
						$QAtt = " AND ep.`id_product` IN (SELECT epa.`product_id` FROM `es_attr` ea
								LEFT JOIN `es_product_attr` epa ON ea.`id_attr` = epa.`attr_id`
								WHERE epa.`product_id` IS NOT NULL 
									AND ea.`cat_id` IN (". $catID .", ". $ParentIDList .")
									". $fin_attr_values ."
								GROUP BY epa.`product_id`
								HAVING COUNT(*) = ". $fin_count ." 
								)";
					}
					
					///////////////////////////////////////////
		
					$sort = $this->input->get('_sop');
					if($sort){
						switch($sort){
							case "hot": $colsort = "ep.is_hot"; break;
							case "new": $colsort = "ep.is_new"; break;
							case "popular": $colsort = "ep.clickcount"; break;
							case "con": $colsort = "ep.condition"; break;
							default: $colsort = "ep.clickcount";							
						}
						unset($sort);
					}else{
						$colsort = "ep.`id_product`";
					}
					
					$sc = "";
					$gsc = $this->input->get('_subcat');
					$child = $this->search_model->selectChild($gsc);				
					if($child[0] == 0 && $child[1] == 0){
						$gsubcat = $gsc;
					}else{
						$gsubcat = implode(',', $child);
					} // end - array check					
					
					if($gsc){
						$sc = " AND ep.`cat_id` IN (" . $gsubcat . ") ";
					}
					
					$loc = "";
					$gloc = $this->input->get('_loc');
					if($gloc){
						$loc = " AND ep.`id_product` IN (SELECT `product_id` FROM `es_product_shipping_head` WHERE `location_id` = " . $gloc . ") ";
					}					
					
					$is = "";
					$gis = $this->input->get('_is');			
					if(strlen($gis) > 0){
						$is = " AND MATCH(ep.`name`,keywords) AGAINST(CONCAT('".$gis."','*') IN BOOLEAN MODE) ";
					}
					
					$con = "";
					$gcon = $this->input->get('_con');
					if(strlen($gcon) > 0){
						$con = " AND ep.`condition` = '". $gcon ."' ";
					}
					
					$gp1 = $this->input->get('_price1');
					$gp2 = $this->input->get('_price2');
					
					$gp = "";
					if(strlen($gp1) > 0 && strlen($gp2) > 0){
						$gp = " AND ep.`price` BETWEEN " . $gp1 . " AND " . $gp2 . " ";
					}
					
					##### Parameters end here ####################################################
										
					# get all items here (right pane)
					
					$items = $this->search_model->SearchProduct($catID, $start, $per_page, $colsort, $is, $con, $gp, $attr_brand, $QAtt, $sc, $loc, $test);
				
					if(!empty($items)){ # check if it has items
							
						$response['items'] = $items; ### pass to view

						$product_id = $this->search_model->getProductID($catID);
						foreach ($product_id as $row){
							$pid_values[] = $row['product_id'];
							$bid_values[] = $row['brand_id'];
						}	
						
						# get all attributes here (left pane)
						
						# brands
						$get_brand_array = array();
						$brand_names = $this->search_model->getBrandName($bid_values,'id');
						foreach ($brand_names as $bn) {
							array_push($get_brand_array,$bn['name']);	
						}
						$fin_brand_array = array('name'=>'BRAND',$get_brand_array);
						
						# attribute group
						$attribute = $this->search_model->getAttributesWithParam($catID,$pid_values);
						
						# attribute values		
						for ($i=0; $i < sizeof($attribute) ; $i++) { 
							$attrib_values = $this->search_model->getAttributesWithParamAndName($catID,$pid_values,$attribute[$i]['name']);
							array_push($attribute[$i], $attrib_values);	
						}
						
						# merging of attributes and brand names
						array_unshift($attribute,$fin_brand_array);
								
						$response['arrayofparams'] = $attribute; ### pass to view
						#$this->arrvwr($attribute);
						
						###########################################

					}
				/////////////////////////////////////////////////////////////////////////////
					
				$this->load->view('templates/header_plain', $data); 
				$this->load->view('pages/search/search_display_v2',$response);
				$this->load->view('templates/footer_full');
				
			}else{
				// no category found
				$this->load->view('templates/header_plain', $data); 
				$this->load->view('pages/search/search_display_v2',$response);
				$this->load->view('templates/footer_full');
			} // end check category
		}else{
			// no query string found
			$this->load->view('templates/header_plain', $data); 
			$this->load->view('pages/search/search_display_v2',$response);
			$this->load->view('templates/footer_full');
		} // end check get
				
	}
	
#####################################################################################
####################################################################################
###################################################################################

	// pag nag scroll down iloload yung ibang items
	
	function load_product() # ROUTING
	{

		if($this->input->post()){
				
			$category = $this->input->post('id_cat');
			$condition = $this->input->post('parameters');
			$start = $this->input->post('page_number') * $this->per_page; # start series
			$per_page = $this->per_page ; # no of display
			$test = "";	
			
			$getParentId = $this->search_model->getParentId($category);
			if(!empty($getParentId)){
				foreach($getParentId as $row){
					$ParentID[] = $row['id_cat'];		
				}
				$ParentIDList = implode(',', $ParentID);
			}else{
				$ParentIDList = $id;
			}			
			
			$child = $this->search_model->selectChild($category);		
			if($child[0] == 0 && $child[1] == 0){
				$catID = $category;
			}else{
				$catID = implode(',', $child);
			} // end - array check

			$attr_values = "";
			$attr_values_array = "";
			$attr_brand = ""; # Brands
			$sopA = "";	
			$scA = "";
			$locA = "";		
			$isA = "";
			$conA = "";
			$price1A = "";
			$price2A = "";
			$ctr = 0;
			$ctrA = 0;		
			foreach ($condition as $name => $val) {
				
				$chk = strpos($name, "_");
				if($chk === false && $name != "BRAND") {			
					
					if (is_array($val)){ # this is for checkboxes with multiple values.
						
						foreach($val as $row => $values){
							$ctrA = $ctrA + 1;
							$attr_values_array = $attr_values_array . " OR (REPLACE(UPPER(ea.`name`),' ','') = '". strtoupper($name) ."' AND UPPER(epa.`attr_value`) = '" . strtoupper($values) ."') ";
						}
					}else{
						if(!empty($val)){
							$ctr = $ctr + 1;
							$attr_values = " OR (REPLACE(UPPER(ea.`name`),' ','') = '". strtoupper($name) ."' AND UPPER(epa.`attr_value`) = '" . strtoupper($val) ."') ";
						}
					}// end
													
				}else{

					if($name == "BRAND"){					
						if(is_array($val)){
							$arr_brand = "";
							foreach($val as $row => $brands){
								if(!empty($brands)){
									$arr_brand = $arr_brand . "'" . $brands . "',";
								}
							}
							
							$fin_arr_brand = substr($arr_brand, 0, strlen($arr_brand) - 1 );
							
							if(!empty($fin_arr_brand)){
								$attr_brand = $attr_brand . " AND eb.`name` IN (". $fin_arr_brand . ") ";
							}
						}	
					}
				
					if($name == "_sop" && !empty($val)){ $sopA = $val; }
					if($name == "_subcat" && !empty($val)){ $scA = $val; }
					if($name == "_loc" && !empty($val)){ $locA = $val; }	
					if($name == "_is" && !empty($val)){ $isA = $val; }
					if($name == "_con" && !empty($val)){ $conA = $val; }
					if($name == "_price1" && !empty($val)){ $price1A = $val; }
					if($name == "_price2" && !empty($val)){ $price2A = $val; }
				}
			}
			
			$raw_attr_values = $attr_values . $attr_values_array;
			$fix_attr_values = substr($raw_attr_values, 3, strlen($raw_attr_values));
			
			$fin_attr_values = "";
			$fin_count = 0;
			if(strlen($fix_attr_values) > 0){
				$fin_attr_values = " AND (" . $fix_attr_values . ") ";
				$fin_count = ($ctr + $ctrA);
			}
					
			$QAtt = "";
			if (!empty($fin_attr_values) && !empty($fin_count)){
				$QAtt = " AND ep.`id_product` IN (SELECT epa.`product_id` FROM `es_attr` ea
						LEFT JOIN `es_product_attr` epa ON ea.`id_attr` = epa.`attr_id`
						WHERE epa.`product_id` IS NOT NULL 
							AND ea.`cat_id` IN (". $catID .", ". $ParentIDList .")
							". $fin_attr_values ."
						GROUP BY epa.`product_id`
						HAVING COUNT(*) = ". $fin_count ."
						)";
			}	
						
			##### Parameters starts here ####################################################
			
			if(!empty($sopA)){
				switch($sopA){
					case "hot": $colsort = "ep.`is_hot`"; break;
					case "new": $colsort = "ep.`is_new`"; break;
					case "popular": $colsort = "ep.`clickcount`"; break;
					case "con": $colsort = "ep.`condition`"; break;
					default: $colsort = "ep.`id_product`";							
				}
			}else{
				$colsort = "ep.`id_product`";
			}
			
			$sc = "";
			if(!empty($scA)){
				$sc = " AND ep.`cat_id` = " . $scA . " ";
			}
			
			$loc = "";
			if($locA){
				$loc = " AND ep.`id_product` IN (SELECT `product_id` FROM `es_product_shipping_head` WHERE `location_id` = " . $locA . ") ";
			}					
			
			$is = "";		
			if(!empty($isA)){
				$is = " AND MATCH(ep.`name`,keywords) AGAINST('+". $isA ."' IN BOOLEAN MODE) ";
			}
			
			$con = "";
			if(!empty($conA)){
				$con = " AND ep.`condition` = '". $conA ."' ";
			}			
			
			$gp1 = $price1A;
			$gp2 = $price2A;
			
			$gp = "";
			if(strlen($gp1) > 0 && strlen($gp2) > 0){
				$gp = " AND ep.`price` BETWEEN " . $gp1 . " AND " . $gp2 . " ";
			}
			##### Parameters end here ####################################################
					
			# get all items here (right pane)
			$items = $this->search_model->SearchProduct($catID, $start, $per_page, $colsort, $is, $con, $gp, $attr_brand, $QAtt, $sc, $loc, $test);
			
			if(isset($items) && !empty($items)){ # check if it has items		
				$response['items'] = $items; ### pass to view	
				$data = json_encode($this->load->view('pages/search/search_display_scroll',$response,TRUE));
				echo $data;
			}						
		} // end
	} # load_product end	

// End  ////////////////////	
}

/* End of file search.php */
/* Location: ./application/controllers/product_search.php */