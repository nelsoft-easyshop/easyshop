<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class product_search extends MY_Controller {
	
	function __construct()  
	{ 
		parent::__construct(); 
		$this->load->helper('htmlpurifier');
		$this->load->model("search_model");		
        $this->load->model("product_model");				
	}
	
	function arrvwr($target){
		if(is_array($target)){
			echo "<pre>";
			print_r($target);
			echo "</pre>";
		}else{
			echo "<br>No array found or array is empty<br>";
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

	
	
	public $per_page = 10; # number of displayed products
	function advsrch(){

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

			$main_cat = $this->search_model->selectChild($catID);
			if($main_cat[0] != 0 && $main_cat[1] != 0){
				$cat = implode(',', $main_cat);
				$catID = $category . ", " . $cat;
			}
			
			$sub_cat = $this->search_model->selectChild($gsc);	 
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
			
			//$this->arrvwr($brnd_att);
			//echo "<br>";
			
			##### Parameters end here ####################################################
								
			# get all items here (right pane)			
			$items = $this->search_model->SearchProduct($catID, $start, $per_page, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att, $test);
			$cntr = $this->search_model->ProductCount($catID, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att);
	
			$response['items'] = $items; ### pass to view
			$response['cntr'] = $cntr; ### pass to view
		
			$product_id = $this->search_model->getProductID($catID);
			
			$pid_values = "";
			$bid_values = "";
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

			$this->load->view('templates/header_plain', $data); 
			$this->load->view('pages/search/search_display_main',$response);
			$this->load->view('templates/footer_full');
		}else{
			$this->load->view('templates/header_plain', $data); 
			$this->load->view('pages/search/search_display_main',$response);
			$this->load->view('templates/footer_full');		
		}// end check get
	}
	
#####################################################################################
	
	function load_product() # ROUTING
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

			$main_cat = $this->search_model->selectChild($catID);
			if($main_cat[0] != 0 && $main_cat[1] != 0){
				$catID = implode(',', $main_cat);
			}
			
			$sub_cat = $this->search_model->selectChild($gsc);	 
			if($sub_cat[0] == 0 && $sub_cat[1] == 0){
				$gsubcat = $gsc;
			}else{
				$gsubcat = implode(',', $sub_cat);
			} // end - subcat check	

			##### Parameters end here ####################################################
			
					
			# get all items here (right pane)
			$items = $this->search_model->SearchProduct($catID, $start, $per_page, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att, $test);
			if(isset($items) && !empty($items)){ # check if it has items		
				$response['items'] = $items; ### pass to view	
				$data = json_encode($this->load->view('pages/search/search_display_scroll',$response,TRUE));
				echo $data;
			}else{
				echo "0";
			}						
		} // end
	} # load_product end	

// End  ////////////////////	
}

/* End of file search.php */
/* Location: ./application/controllers/product_search.php */