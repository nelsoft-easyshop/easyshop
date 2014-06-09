<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class productUpload extends MY_Controller 
{ 
    public $max_file_size_mb;
    public $img_dimension = array();

	function __construct()  
	{ 
		parent::__construct(); 
		$this->load->model("product_model");
        $this->load->helper('htmlpurifier');
		if(!$this->session->userdata('usersession') && !$this->check_cookie())
			redirect(base_url().'login', 'refresh');
        $this->max_file_size_mb = 5;

        /* Uploaded images dimensions: (w,h) */
        $this->img_dimension['usersize'] = array(1024,768);
        $this->img_dimension['small'] = array(400,535);
        $this->img_dimension['categoryview'] = array(220,200);
        $this->img_dimension['thumbnail'] = array(60,80);
	}


	function fill_view()
	{
		$data = array(
			'title' => 'Sell Product | Easyshop.ph',
			);
		$data = array_merge($data, $this->fill_header());

		return $data;
	}


	function step1() #this function for selecting categories from parent category to the last possible category
	{		
        if($this->input->post('c_id')){
            $cat_id = $this->input->post('c_id');    
            $cat_tree = $this->product_model->getParentId($cat_id);
            $data_item['cat_tree_edit'] = json_encode($cat_tree);
        }
        if($this->input->post('step2_content')){
            $data_item['step2_content'] = $this->input->post('step2_content');
        }

		$usersession = $this->session->userdata('usersession');	
		$uid = $this->session->userdata('member_id');
		$draftItems = $this->product_model->getDraftItems($uid);

		for ($i=0; $i < sizeof($draftItems); $i++) { 
			$parents = $this->product_model->getParentId($draftItems[$i]['cat_id']); # getting all the parent from selected category	
			$str_parents_to_last = "";

			$lastElement = end($parents);	
			foreach($parents as $k => $v) { # creating the bread crumbs from parent category to the last selected category
				$str_parents_to_last = $str_parents_to_last  .' '. $v['name'];
				if($v == $lastElement) {

				}else{
					$str_parents_to_last = $str_parents_to_last.' &#10140;';
				}
			}
			if($draftItems[$i]['cat_other_name'] != ""){
				$str_parents_to_last = $str_parents_to_last.' &#10140; ' . $draftItems[$i]['cat_other_name'];
			}
			$draftItems[$i]['crumbs'] = $str_parents_to_last;
		} 
 
		$data_item['draftItems'] = $draftItems;
		$data_item['firstlevel'] = $this->product_model->getFirstLevelNode(); # getting first category level from database.
		$userdetails = $this->product_model->getCurrUserDetails($uid);
		$data = $this->fill_view();
		$this->load->view('templates/header', $data); 

		if($data['logged_in'] && ($userdetails['is_contactno_verify'] || $userdetails['is_email_verify']) )
			$this->load->view('pages/product/product_upload_step1_view',$data_item);
		else
			$this->load->view('pages/product/product_upload_error');
		$this->load->view('templates/footer'); 
	}

	function getChild() # this function for getting the under category from selected category 
	{	
		header('Content-Type: application/json');
		$id = $this->input->post('cat_id'); 
		$name = $this->input->post('name'); 
		$response['cat_id'] = $id;
		$response['name'] = $name;
		$response['node'] = $this->product_model->getDownLevelNode($id); # get all down level category based on selected parent category
		$response['level'] = $this->input->post('level');
		$data = json_encode($this->load->view('pages/product/product_upload_step1_view2',$response,TRUE));
		echo $data;
	}

	function add_category(){
		$ids =(int) $this->input->post('ids');
		$name = $this->input->post('name');
		$desc = $this->input->post('desc');
		$key = $this->input->post('key');
		$sort =(int) $this->input->post('sort');
		$is_main = (int) 1;

		$data = $this->product_model->addCategory($ids,$name,$desc,$key,$sort,$is_main);

		echo json_encode($data);
	}

	function step2()
	{ 

		$data = $this->fill_view();
		$this->load->view('templates/header', $data); 
		if(isset($_POST['hidden_attribute'])){ # if no item selected cant go to the link. it will redirect to step 1
			$id = $this->input->post('hidden_attribute'); 
			$otherCategory = html_escape($this->input->post('othernamecategory'));
			$response['id'] = $id; # id is the selected category
			$response['otherCategory'] = $otherCategory; # id is the selected category
			$parents = $this->product_model->getParentId($id); # getting all the parent from selected category
			#$attribute = $this->product_model->getAttributesBySelf($id); # getting all attribute from all parent from selected category
			$attribute = $this->product_model->getAttributesByParent($parents);
            $str_parents_to_last = "";

			
			if($id ==1){
			 	$str_parents_to_last = $otherCategory;
			}else{
				$lastElement = end($parents);	
				foreach($parents as $k => $v) { # creating the bread crumbs from parent category to the last selected category
					$str_parents_to_last = $str_parents_to_last  .' '. $v['name'];
					if($v == $lastElement) {

					}else{
						$str_parents_to_last = $str_parents_to_last.' &#10140;';
					}
				}

				if(!$otherCategory == ""){
					$str_parents_to_last = $str_parents_to_last.' &#10140; ' . $otherCategory;
				}
			}
			
			$response['parent_to_last'] = $str_parents_to_last;
			for ($i=0 ; $i < sizeof($attribute) ; $i++ ) {  # getting all lookuplist from item attribute
				$lookuplist = $this->product_model->getLookItemListById($attribute[$i]['attr_lookuplist_id']);
				array_push($attribute[$i],$lookuplist);
			}
			$response['memid'] = $this->session->userdata('member_id');
		 	$response['tempId'] = strtolower(substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,50 ) ,1 ) .substr( md5( time() ), 1));
			$response['attribute'] = $attribute;
			$response['sell'] = true;

            
    		$this->session->set_userdata('tempId', $response['tempId']);
            if($this->input->post('step1_content')){
                $response['step1_content'] = $this->input->post('step1_content');
            }
               
            $response['img_max_dimension'] = $this->img_dimension['usersize'];
               
			$this->load->view('pages/product/product_upload_step2_view',$response);
			$this->load->view('templates/footer'); 
		}else{
			redirect('/sell/step1/', 'refresh');
		}

	}

 
 	public function uploadimage()
 	{

 		$counter = $this->input->post('counter');
 
    	$temp_product_id = $this->session->userdata('tempId'); 
 		$member_id =  $this->session->userdata('member_id');
 		$filescnttxt = $this->input->post('filescnttxt');
 		$afstart = $this->input->post('afstart');
 		$afstartArray = json_decode($afstart);
 		 
 		$date = date("Ymd");
	 	$fulldate = date("YmdGis");
 		$filenames_ar = array(); 
 		$text = "";
 
		$allowed =  array('gif','png' ,'jpg','jpeg'); # available format only for image

 		foreach($_FILES['files']['name'] as $key => $value ) {

 			$file_ext = explode('.', $value);
 			$file_ext = strtolower(end($file_ext)); 
 			$filenames_ar[$key] = $afstartArray[$key];  

 			if(!in_array(strtolower($file_ext),$allowed))
 			{
 				unset($_FILES['files']['name'][$key]);
 				unset($_FILES['files']['type'][$key]);
 				unset($_FILES['files']['tmp_name'][$key]);
 				unset($_FILES['files']['error'][$key]);
 				unset($_FILES['files']['size'][$key]);
 				unset($filenames_ar[$key]); 
 			}

 			if(isset($_FILES['files']['name'][$key])){
				if($_FILES['files']['size'][$key] >= ($this->max_file_size_mb * 1024) * 1024) # size of image must be 5mb only
				{
					unset($_FILES['files']['name'][$key]);
					unset($_FILES['files']['type'][$key]);
					unset($_FILES['files']['tmp_name'][$key]);
					unset($_FILES['files']['error'][$key]);
					unset($_FILES['files']['size'][$key]);
					unset($filenames_ar[$key]); 
				}
			}
 		}

 		$_FILES['files']['name'] = array_values($_FILES['files']['name']);
 		$_FILES['files']['type'] = array_values($_FILES['files']['type']);
 		$_FILES['files']['tmp_name'] = array_values($_FILES['files']['tmp_name']);
 		$_FILES['files']['error'] = array_values($_FILES['files']['error']);
 		$_FILES['files']['size'] = array_values($_FILES['files']['size']);
		$filenames_ar = array_values($filenames_ar);

 		$path_directory = './assets/temp_product/'.$temp_product_id.'_'.$member_id.'_'.$date.'/';

		if (!file_exists ($path_directory)){
			mkdir($path_directory, 0777, true);;
		}
		 
		$this->upload->initialize(array( 
			"upload_path" => $path_directory,
			"overwrite" => FALSE,
			"file_name"=> $filenames_ar,
			"encrypt_name" => FALSE,
			"remove_spaces" => TRUE,
			"allowed_types" => "jpg|jpeg|png|gif",
			"max_size" => $this->max_file_size_mb * 1024,
			"xss_clean" => FALSE
			)); 	

	 	$error = 0;

	   	if ($this->upload->do_multi_upload('files')){
            $file_data = $this->upload->get_multi_upload_data();
	   		for ($i=0; $i < sizeof($filenames_ar); $i++) { 

	   			$this->es_img_resize($filenames_ar[$i],$path_directory, 'small/', $this->img_dimension['small']); 
	   			$this->es_img_resize($filenames_ar[$i],$path_directory.'small/', '../categoryview/', $this->img_dimension['categoryview']);
	   			$this->es_img_resize($filenames_ar[$i],$path_directory.'categoryview/','../thumbnail/', $this->img_dimension['thumbnail']);
                //If user uploaded image is too large, resize and overwrite original image
	   			if(isset($file_data[$i])){
		   			if(($file_data[$i]['image_width'] > $this->img_dimension['usersize'][0]) || ($file_data[$i]['image_height'] > $this->img_dimension['usersize'][1])){
		   				$this->es_img_resize($file_data[$i]['file_name'],$path_directory,'', $this->img_dimension['usersize']);
		   			}	
	   			}
	   		}

	   	}else{
	   		for ($i=0; $i < sizeof($filenames_ar); $i++) { 
				if (file_exists($path_directory.$filenames_ar[$i])) {
					unlink($path_directory.$filenames_ar[$i]) or die('failed deleting: ' . $path);
				}
			}
	   		$text = $this->upload->display_errors();
	   		$error = 1;
	   	}
		  
		$return = array( 
			'msg' => $text,
			'dr' => $path_directory,
			'fcnt' => $filescnttxt,
			'err' => $error
			);

		echo json_encode($return);
 	}



	function step2_2() # function for processing the adding of new item
	{	   

		$combination = json_decode($this->input->post('combination'));

		$checkIfCombination = $this->input->post('noCombination');
		$inputs = $this->input->post('inputs'); 
		$inputs_exp = false;

		if(strpos($inputs, '|') !== false) {
			$explode_inputs = explode("|", substr($inputs, 1));
			$inputs_exp = true;
		}

		$data = $this->input->post('data');
		$cat_id = $this->input->post('id');
		$otherCategory = $this->input->post('otherCategory');
		$brand_id =  $this->input->post('prod_brand'); 
        $brand_valid = false;
        $otherBrand = '';

		$product_title = trim($this->input->post('prod_title'));
		$product_brief = trim($this->input->post('prod_brief_desc'));
		$product_description =  $this->input->post('desc');

		$product_price = str_replace(',', '', $this->input->post('prod_price')) ;
		$product_discount = ($this->input->post('discount'))?$this->input->post('discount'):0;
		$product_condition = $this->input->post('prod_condition');
		$sku = trim($this->input->post('prod_sku'));
		
		$keyword = es_url_clean(trim($product_title).' '.trim($this->input->post('prod_keyword')));
		$keyword = str_replace('-', ' ',$keyword);
		$style_id = 1;
		$member_id =  $this->session->userdata('member_id');
		
		$date = date("Ymd");
        $fulldate = date("YmdGis");
                
        if(intval($brand_id,10) == 1){
            $brand_valid = true;
            $otherBrand = $this->input->post('brand_sch');
            $brand_id = 1;
        }
        else{
            if($this->product_model->getBrandById($brand_id)){
                $brand_valid = true;
            }
        }

		if($brand_valid === FALSE){
			echo '{"e":"0","d":"The selected brand is unavailable. Please specify a valid one."}';	 
			exit();
		} 
        
		if (!in_array($product_condition, $this->lang->line('product_condition')))
		{
			echo '{"e":"0","d":"Condition selected not available. Please select another."}';	 
			exit();
		}

		if(strlen(trim($product_title)) == 0 || $product_title == "" 
			|| strlen(trim($product_brief)) == 0 || $product_brief == "" 
			|| strlen(trim($product_price)) == 0 || $product_price <= 0)
		{
			echo '{"e":"0","d":"Fill (*) All Required Fields Properly!"}';		
			exit();
		}else{
 			
 			$arraynameoffiles = $this->input->post('arraynameoffiles');
			$arraynameoffiles = json_decode($arraynameoffiles);
 	 
			if(count($arraynameoffiles) <= 0){ 
				echo '{"e":"0","d":"Please select at least one photo for your listing."}';
				exit();
			}

			$removeThisPictures = json_decode($this->input->post('removeThisPictures')); 
			$primaryId = $this->input->post('primaryPicture');
			$primaryName =""; 
			$arrayNameOnly = array();

			foreach($arraynameoffiles as $key => $value ) {

				$explodearraynameoffiles = explode('||', $value);
				$nameOfFile = $explodearraynameoffiles[0];
				$arrayNameOnly[$key] = strtolower($nameOfFile); 

				if($primaryId == $key){
					$primaryName =	strtolower($nameOfFile);
				}

				if (in_array($key, $removeThisPictures) || $arraynameoffiles[$key] == "") {
					unset($arraynameoffiles[$key]);
					unset($arrayNameOnly[$key]);
				} 
			}

			$arraynameoffiles = array_values($arraynameoffiles);
			$arrayNameOnly = array_values($arrayNameOnly); 
			$key = array_search ($primaryName, $arrayNameOnly);
			if(!count($arraynameoffiles) <= 0){
				$temp = $arraynameoffiles[0];
				$arraynameoffiles[0] = $arraynameoffiles[$key];
				$arraynameoffiles[$key] = $temp;

				$temp = $arrayNameOnly[0];
				$arrayNameOnly[0] = $arrayNameOnly[$key];
				$arrayNameOnly[$key] = $temp;
			}

			if(count($arraynameoffiles) <= 0){
				echo '{"e":"0","d":"Please select at least one photo for your listing."}';
				exit();
			}

			$allowed =  array('gif','png' ,'jpg','jpeg'); # available format only for image
			$x = 0; 

			if(!empty($_FILES['prod_other_img']['name'][0])){
                foreach($_FILES['prod_other_img']['name'] as $k) { # validating image format.
                	$filename = $_FILES['prod_other_img']['name'][$x];
                	$ext = pathinfo($filename, PATHINFO_EXTENSION);
                	$file_temp =  $_FILES['prod_other_img']['tmp_name'][$x];
                    if(!in_array(strtolower($ext),$allowed) || (getimagesize($file_temp) === FALSE)) 
                	{
                		echo '{"e":"0","d":"For Additional Information: File Selected not valid. \n Please choose another image."}';
                		exit();
                	}
                    if($_FILES["prod_other_img"]["size"][$x] >= $this->max_file_size_mb * 1024 * 1024) # size of image must be 5mb only
                    {
                    	echo '{"e":"0","d":"For Additional Information: File size not valid. Please choose another image with smaller size. \n The maximum allowable file size is '.$this->max_file_size_mb.'mB."}';
                    	exit();
                    }
                    $x++;
                }
            }
            
			$product_id = $this->product_model->addNewProduct($product_title,$sku,$product_brief,$product_description,$keyword,$brand_id,$cat_id,$style_id,$member_id,$product_price,$product_discount,$product_condition,$otherCategory, $otherBrand);
            # product_id = is the id_product for the new item. if 0 no new item added process will stop
            
			#image directory
			$path_directory = './assets/product/'.$product_id.'_'.$member_id.'_'.$date.'/';
			$other_path_directory = $path_directory.'other/';
			# creating new directory for each product
			if(!mkdir($path_directory)) {  
				echo '{"e":"0","d":"There was a problem. \n Please try again! - Error[0010]"}'; 
				exit();
			}   
			if(!mkdir($other_path_directory)) {  
				echo '{"e":"0","d":"There was a problem. \n Please try again! - Error[0012]"}'; 
				exit();
			}   	


			// $tempdirectory = $this->input->post('tempdirectory');
            $tempdirectory = $this->session->userdata('tempId'); 
            $tempdirectory = $tempdirectory.'_'.$member_id.'_'.$date;
            $tempdirectory = './assets/temp_product/'.$tempdirectory.'/'; 
			directory_copy($tempdirectory, $path_directory,$product_id,$arrayNameOnly); 

			if($product_id > 0) # id_product is 0 means no item inserted. the process will stop.
			{
                    foreach ($arraynameoffiles as $key => $value) {
                    	$explodearraynameoffiles = explode('||', $arraynameoffiles[$key]);
                    	$nameOfFile = $explodearraynameoffiles[0];
                    	$nameOfFileArray = explode('_', $nameOfFile);
                    	$newName = $product_id.'_'.$nameOfFileArray[1].'_'.$nameOfFileArray[2];
                    	$path = $path_directory.$newName;
                    	array_push($arrayNameOnly, $nameOfFile);
                    	$typeOfFile = $explodearraynameoffiles[1];
                	  	$is_primary = ($key == 0 ? 1 : 0);
						$product_image = $this->product_model->addNewProductImage($path,$typeOfFile,$product_id,$is_primary);
                    }

                    if($inputs_exp == true){
                        for ($i=0; $i < sizeof($explode_inputs) ; $i++) {
                            $explode_id = explode('/', $explode_inputs[$i]);
                            $explode_value = $explode_id[0];
                            $attribute_id = $explode_id[1];
                            $extraPrice = '0';
                            $dataType = substr($explode_value,0,strpos($explode_value,'_'));

                            switch ($dataType) {
                                # if the input type is checkbox possible many item will insert to the database.
                                case 'CHECKBOX': 
                                if(isset($_POST[$explode_value])){
                                    for ($x=0; $x < sizeof($_POST[$explode_value]) ; $x++) {
                                        $attributeCount = count($this->product_model->selectAttributeNameWithNameAndId($_POST[$explode_value][$x],$attribute_id));
                                        if($attributeCount > 0){
                                            $prod_attr_id = $this->product_model->addNewAttributeByProduct($product_id,$attribute_id,$_POST[$explode_value][$x],$extraPrice);
                                        }
                                    }
                                }
                                break;
                                #input type is textarea
                                case 'TEXTAREA':
                                if(isset($_POST[$explode_value]) && (strlen(trim($_POST[$explode_value])) != 0 )){
                                    $attributeCount = count($this->product_model->selectAttributeNameWithTypeAndId($attribute_id,2));
                                    if($attributeCount > 0){
                                        $prod_attr_id = $this->product_model->addNewAttributeByProduct($product_id,$attribute_id,$_POST[$explode_value],$extraPrice);
                                    }
                                }
                                break;
                                #input type is simple textbox
                                case 'TEXT':
                                if(isset($_POST[$explode_value]) && (strlen(trim($_POST[$explode_value])) != 0 )){
                                    $attributeCount = count($this->product_model->selectAttributeNameWithTypeAndId($attribute_id,1));
                                    if($attributeCount > 0){
                                        $prod_attr_id = $this->product_model->addNewAttributeByProduct($product_id,$attribute_id,$_POST[$explode_value],$extraPrice);
                                    }
                                }
                                break;

                                default: # default input type (SELECT& RADIO)
                                if(isset($_POST[$explode_value]) && strlen(trim($_POST[$explode_value])) != 0 ){
                                    $attributeCount = count($this->product_model->selectAttributeNameWithNameAndId($_POST[$explode_value],$attribute_id));
                                    if($attributeCount > 0){
                                        $prod_attr_id = $this->product_model->addNewAttributeByProduct($product_id,$attribute_id,$_POST[$explode_value],$extraPrice);
                                    }	
                                }
                                break;	
                            }			
                        }
                    }


                    # start of saving other/custom attribute


                    $newarray = array();
                    $option_image_idx = 0;
                    
                    for ($i=0; $i < sizeof($_POST['prod_other_name']); $i++) { 
                        $newarray[trim(ucfirst(strtolower($_POST['prod_other_name'][$i])))] = array();
                    }

                    for ($i=0; $i < sizeof($_POST['prod_other_name']); $i++) {
                        $other_name = "--no name";
                        $other_price = "0.00";
                        $other_image = "--no image";
                        $other_image_type = "--no type";
                        $other_tmp = "--no temp";
                        if(strlen(trim($_POST['prod_other_price'][$i])) != 0 || $_POST['prod_other_price'][$i] != ""){
                            $other_price = $_POST['prod_other_price'][$i];
                        }
                        $other_price = abs(str_replace(',','',$other_price));
                        if(isset($_FILES['prod_other_img'])){
                            if(intval($_POST['prod_other_img_idx'][$i],10) === 1){
                                if(!empty($_FILES['prod_other_img']['name'][$option_image_idx])){
                                    $other_image_type = $_FILES['prod_other_img']['type'][$option_image_idx];
                                    $file_ext = explode('.', $_FILES['prod_other_img']['name'][$option_image_idx]);
                                    $file_ext = strtolower(end($file_ext));
                                    $other_image = "{$product_id}_{$member_id}_{$fulldate}{$i}_o.{$file_ext}";
                                    $other_tmp = $_FILES["prod_other_img"]["tmp_name"][$option_image_idx];
                                }
                                $option_image_idx++;
                            }
                        }

                        if(strlen(trim($_POST['prod_other'][$i])) != 0 ||  trim($_POST['prod_other'][$i]) != ""){
                            $other_name = $_POST['prod_other'][$i];
                        }
                        array_push($newarray[trim(ucfirst(strtolower($_POST['prod_other_name'][$i])))], ucfirst(strtolower($other_name)) .'|'.$other_price.'|'.$other_image.'|'.$other_image_type.'|'.$other_tmp);
                    }
                    $filenames_ar = array();					
                    $path = $other_path_directory;
                    $is_primary = 0;
                    
                    foreach ($newarray as $key => $valuex) {

                        if(trim($key) == "" || strlen(trim($key)) <= 0 ){
                            continue;
                        }
                        if(count($valuex) <= 1 && $valuex[0] == '--no name|0.00|--no image|--no type|--no temp'){
                            continue;
                        }
                        $others_id = $this->product_model->addNewAttributeByProduct_others_name($product_id,$key);
                        foreach ($valuex as $keyvalue => $value) {
                            $eval = explode("|", $value);

                            if(trim($eval[0]) == "--no name"){
                                continue;
                            }

                            $imageid = 0;
                            if($eval[2] != "--no image"){
                                $imageid = $this->product_model->addNewProductImage($path.$eval[2],$eval[3],$product_id,$is_primary);
                                move_uploaded_file($eval[4], $path.$eval[2]);
                                list($o_width, $o_height) = getimagesize($path.$eval[2]);
                                //If user uploaded image is too large, resize and overwrite original image
                                if(($o_width > $this->img_dimension['usersize'][0])||($o_height > $this->img_dimension['usersize'][1])){
                                    $this->es_img_resize($eval[2],$path,'', $this->img_dimension['usersize']);
                                }
                                $this->es_img_resize($eval[2],$other_path_directory, 'small/', $this->img_dimension['small']);

                                $this->es_img_resize($eval[2],$other_path_directory.'small/', '../thumbnail/', $this->img_dimension['thumbnail']);
                                
 
                            }
                            $this->product_model->addNewAttributeByProduct_others_name_value($others_id,$eval[0],$eval[1],$imageid);

                        }
                    }
                    # end of other

                     # start of saving combination
                    if($checkIfCombination == 'true' || $checkIfCombination == 1){
                        $quantitySolo = 1;
                        if($this->input->post('quantitySolo')){
                            $quantitySolo = $this->input->post('quantitySolo');
                            $quantitySolo = abs(str_replace(',','',$quantitySolo));
                        }
                        $idProductItem = $this->product_model->addNewCombination($product_id,$quantitySolo);

                    }else{
                        foreach ($combination as $keyCombination) {
                            $quantitycombination = 1;
                            if(!$quantitycombination <= 0){
                                $quantitycombination = abs(str_replace(',','',$keyCombination->quantity)); 
                            }
                            $idProductItem = $this->product_model->addNewCombination($product_id,$quantitycombination);
                            if(strpos($keyCombination->value, '-') !== false) {

                                $explodeCombination = explode("-",  $keyCombination->value);
                                foreach ($explodeCombination as $value) {

                                    $explodeOther = explode(":",  $value);
                                    $otherAttrIdentifier = $explodeOther[0];
                                    $otherAttrValue = $explodeOther[1];
                                    $otherAttrGroup = $explodeOther[2];
                                    if($otherAttrIdentifier == 1){
                                        $productAttributeId = $this->product_model->selectProductAttributeOther($otherAttrGroup,$otherAttrValue,$product_id);
                                    }else{
                                        $productAttributeId = $this->product_model->selectProductAttribute($otherAttrValue,$product_id);
                                    }

                                    $this->product_model->addNewCombinationAttribute($idProductItem,$productAttributeId,$otherAttrIdentifier);
                                }
                            }else{
                                $explodeOther = explode(":",  $keyCombination->value);
                                $otherAttrIdentifier = $explodeOther[0];
                                $otherAttrValue = $explodeOther[1];
                                $otherAttrGroup = $explodeOther[2];
                                if($otherAttrIdentifier == 1){
                                    $productAttributeId = $this->product_model->selectProductAttributeOther($otherAttrGroup,$otherAttrValue,$product_id);
                                }else{
                                    $productAttributeId = $this->product_model->selectProductAttribute($otherAttrValue,$product_id);
                                }

                                $this->product_model->addNewCombinationAttribute($idProductItem,$productAttributeId,$otherAttrIdentifier);
                            }	
                        }

                    } 
                    # end of combination
                  
                    $data = '{"e":"1","d":"'.$product_id.'"}';

                    echo $data;
                    exit();	
            }else{
                $data = '{"e":"0","d":"There was a problem. \n Please try again later! - Error[0011]"}';
                echo $data;
                exit();
            }
    }
    echo $data;
}


	/**
	 *	View function for Product Upload Step 3
	 */
	function step3()
	{
		if($this->input->post('prod_h_id')){
			$id = $this->input->post('prod_h_id');
			$member_id = $this->session->userdata('member_id');

			$data = array (
				'shiploc' => $this->product_model->getLocation(),
				'attr' => $this->product_model->getPrdShippingAttr($id),
				'product_id' => $id,
				'shipping_summary' => $this->product_model->getShippingSummary($id),
				'shipping_preference' => $this->product_model->getShippingPreference($member_id), 
            );
            
			$data = array_merge($data, $this->fill_view());
			$jsonDisplayGroup = $jsonFdata = array();
			
			if($data['shipping_summary']['has_shippingsummary']){
				if($data['attr']['has_attr'] === 1){
					$i = 0;
					foreach($data['attr']['attributes'] as $attrk=>$attrarr){
						if( isset($data['shipping_summary'][$attrk]) ){
							$jsonDisplayGroup[$i][$attrk] = $attrk;
							foreach($data['shipping_summary'][$attrk] as $lockey=>$price){
								$jsonFdata[$i][$attrk][$lockey] = $price;
							}
							$i++;
						}
					}
				} else {
					$jsonDisplayGroup[0][$data['attr']['product_item_id']] = $data['attr']['product_item_id'];
					foreach($data['shipping_summary'][$data['attr']['product_item_id']] as $lockey=>$price){
						$jsonFdata[0][$data['attr']['product_item_id']][$lockey] = $price;
					}
				}
			}
			$data['json_displaygroup'] = json_encode($jsonDisplayGroup, JSON_FORCE_OBJECT);
			$data['json_fdata'] = json_encode($jsonFdata, JSON_FORCE_OBJECT);
			$data['json_id_product_item'] = json_encode($data['shipping_summary']['id_product_item']);
			
			$locationGroup = array();
			$islandLookup = [2,3,4];
			$data['inc_location'] = false;
			$data['inc_locationmsg'] = '';
			if($data['shipping_summary']['has_shippingsummary']){
				foreach($data['shipping_summary']['location'] as $locKey=>$locV){
					if(!in_array($locKey,$locationGroup)){
						$locationGroup[] = $locKey;
					}
				}
				foreach($islandLookup as $locId){
					if(!in_array($locId, $locationGroup)){
						$data['inc_location'] = true;
						switch($locId){
							case 2:
								$data['inc_locationmsg'] .= 'Luzon ';
								break;
							case 3:
								$data['inc_locationmsg'] .= 'Visayas ';
								break;
							case 4:
								$data['inc_locationmsg'] .= 'Mindanao ';
								break;
						}
					}
				}
			}
			
			$data['json_locationgroup'] = json_encode($locationGroup);
			$data['json_islandlookup'] = json_encode($islandLookup);
			
			$data['json_shippingpreference'] = json_encode($data['shipping_preference'], JSON_FORCE_OBJECT);
			
			if($this->input->post('is_edit')){
                $data['is_edit'] = true;
            }
			$this->load->view('templates/header', $data);
			$this->load->view('pages/product/product_upload_step3_view', $data);
			$this->load->view('templates/footer');
			
		}
	    else {
			redirect(base_url().'sell/step1', 'refresh');
		}
	}
	
	/**
	 *	Function used when shipping details are submitted in Product Upload Step 3. 
	 *	Stores shipping data in `es_shipping_price` and `es_product_shipping_map`
	 */
	function step3Submit(){
		$fdata = $this->input->post('fdata');
		//$arrProductItemId = json_decode($this->input->post('productitemid'));
		$arrProductItemId = $this->input->post('productitemid');
		$productId = $this->input->post('productid');
        $memberId =  $this->session->userdata('member_id');
		$attrCounter = 0;
		
		//Fetch db data
		$dbProductItem = $this->product_model->getProductItem($productId, $memberId);
		//Assemble product item id from db similar to arrProductItemId structure
		$dbtemp = array();
		foreach($dbProductItem as $db){
			$dbtemp[] = $db['id_product_item'];
		}
		//Compare 'posted' product item id array with 'server' product item id array
		if($dbtemp == $arrProductItemId){
			// Check if all attributes have assigned shipping details
			foreach($arrProductItemId as $pid){
				foreach($fdata as $arrAttr){
					if(array_key_exists($pid,$arrAttr) && count($arrAttr[$pid]) !== 0){
						$attrCounter++;
						break;
					}
				}
			}
			
			if( count($arrProductItemId) <= $attrCounter ){
				//DELETE EXISTING ENTRIES IN DATABASE
				$this->product_model->deleteShippingSummaryOnEdit($arrProductItemId);
				
				foreach($fdata as $group){
					foreach($group as $attrCombinationId=>$attrGroup){
						foreach($attrGroup as $locationKey=>$price){
							if(is_numeric($price)){
								$shippingId = $this->product_model->storeShippingPrice($locationKey, $price, $productId);
								$this->product_model->storeProductShippingMap($shippingId, $attrCombinationId);
							}
						}
					}
				}
				echo 1;
			}
			else{
				echo 0;
			}
		}
		else{
			echo 2;
		}
	}
	
	
	function step3_addPreference()
	{
		$serverResponse['result'] = 'fail';
		$serverResponse['error'] = 'Failed to validate form';
		
		if( $this->input->post('data') && $this->input->post('name') ){
			$preferenceData = $this->input->post('data');
			$preferenceName = $this->input->post('name');
			
			$member_id = $this->session->userdata('member_id');
			
			// Insert name vs memid in shipping pref head
			// Returns last id inserted on success, false on failure
			$resultHead = $this->product_model->storeShippingPreferenceHead($member_id, $preferenceName);
			
			$serverResponse['result'] = $resultHead ? 'success' : 'fail';
			$serverResponse['error'] = $resultHead ? '' : 'Failed to store preference head.';
			
			// if success, enter shipping details
			if($resultHead){
				foreach($preferenceData as $loc=>$price){
					$resultDetail = $this->product_model->storeShippingPreferenceDetail($resultHead, $loc, $price);
					
					if(!$resultDetail){
						$serverResponse['result'] = 'fail';
						$serverResponse['error'] = 'Failed to insert preference. Data stored may be incomplete.';
						break;
					}
				}
				if($resultDetail){
					$serverResponse['shipping_preference'] = $this->product_model->getShippingPreference($member_id);
				}
			}
		}
		echo json_encode($serverResponse, JSON_FORCE_OBJECT);
	}
	
	function step3_deletePreference()
	{
		$serverResponse['result'] = 'fail';
		$serverResponse['error'] = 'Failed to validate form.';
		
		if($this->input->post('head')){
			$member_id = $this->session->userdata('member_id');
			$headId = $this->input->post('head');
			
			// Check if shipping preference ID is owned by current user
			$result = $this->product_model->getShippingPreferenceHead($headId, $member_id);
		
			// if shipping preference is owned by current user
			if( count($result) > 0 ){
				$deleteResult = $this->product_model->deleteShippingPreference($headId, $member_id);
				
				$serverResponse['result'] = $deleteResult ? 'success' : 'fail';
				$serverResponse['error'] = $deleteResult ? '' : 'Failed to delete database entry.' ;
				
			} else {
				$serverResponse['result'] = 'fail';
				$serverResponse['error'] = 'Server data mismatch. Try again later.';
			}
		}
	
		echo json_encode($serverResponse, JSON_FORCE_OBJECT);
	}
	
	
	function step4() # uploading of product is successful.
	{	
        $this->load->model('memberpage_model');
		$data = $this->fill_view();
		$this->load->view('templates/header', $data); 
        $memberId =  $this->session->userdata('member_id');
		if($this->input->post('prod_h_id')){
            $billing_id = $this->input->post('prod_billing_id'); 
            $is_cod =($this->input->post('allow_cod'))?1:0;
			$response['id'] = $this->input->post('prod_h_id');
            $this->product_model->finalizeProduct($response['id'] , $memberId, $billing_id, $is_cod);
            $product = $this->product_model->getProductById($response['id']);
            $response['slug'] = $product['slug'];
            $response['productname'] = $product['product'];
            if($this->input->post('is_edit')){
                $response['is_edit'] = true;
            }
            $this->load->view('pages/product/product_upload_step4_view',$response);
			$this->load->view('templates/footer'); 
		}else{
            redirect('/sell/step1/', 'refresh');
		}
	}
	
    public function editStep1(){
        if($this->input->post('p_id'))
			 $product_id = $this->input->post('p_id');
		else
			redirect('me', 'refresh'); 
        
        $member_id = $this->session->userdata('member_id');
        $cat_tree = $this->product_model->getParentIdByProduct($product_id, $member_id);
        if(count($cat_tree)>0){  
            $data_item['firstlevel'] = $this->product_model->getFirstLevelNode(); # getting first category level from database.
            $data = $this->fill_view();
            $data['cat_tree_edit'] = json_encode($cat_tree);
            $data['product_id_edit'] = $product_id;
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/product/product_upload_step1_view',$data_item);
            $this->load->view('templates/footer'); 
        }
        else{
            redirect('me', 'refresh'); 
        }
    }
    
	public function editStep2(){
		if($this->input->post('p_id'))
			$product_id = $this->input->post('p_id');
		else
			redirect('me', 'refresh'); 

		$member_id = $this->session->userdata('member_id');
		$data = array('title'=>'Edit Product');
		$data = array_merge($data,$this->fill_header());
		$this->load->view('templates/header',$data); 
        
        $product = $this->product_model->getProductEdit($product_id, $member_id);  
        if($this->input->post('hidden_attribute')){
            $new_cat_id = $this->input->post('hidden_attribute');
            if($this->product_model->editProductCategory($new_cat_id, $product_id, $member_id)>0){
                if(intval($product['cat_id'],10)!==intval($new_cat_id,10)){
                    $this->product_model->deleteShippingInfomation($product_id);
                    $this->product_model->deleteProductQuantityCombination($product_id);
                    $this->product_model->deleteAttributeByProduct($product_id);
                }
                $product['cat_id'] = $new_cat_id;
            }           
        }
        
        $parents = $this->product_model->getParentId($product['cat_id']); # getting all the parent from selected category
		$lastElement = end($parents);	
		$str_parents_to_last = "";
		foreach($parents as $k => $v) { # creating the bread crumbs from parent category to the last selected category
			$str_parents_to_last = $str_parents_to_last  .' '. $v['name'];
			if(!($v == $lastElement))
				$str_parents_to_last = $str_parents_to_last.' ->';
		}
		$images = $this->product_model->getProductImages($product_id);
		$main_images = array();
		foreach($images as $image){
			if(strpos(($image['path']),'other') === FALSE)
				array_push($main_images, $image);
		}
		#$attribute = $this->product_model->getAttributesBySelf($product['cat_id']); # getting all attribute from all parent from selected category
		$attribute = $this->product_model->getAttributesByParent($parents);
        for ($i=0 ; $i < sizeof($attribute) ; $i++ ) {  # getting all lookuplist from item attribute
			$lookuplist = $this->product_model->getLookItemListById($attribute[$i]['attr_lookuplist_id']);
			array_push($attribute[$i],$lookuplist);
		}		
		
		$response['attribute'] = $attribute;
		$response['parent_to_last'] = $str_parents_to_last;
		$response['id'] = $product['cat_id'];
		$response['product_details'] = $product;

		$response['is_edit'] = true;

		$response['product_attributes_opt'] = array();
		$response['product_attributes_spe'] = array();
        $response['memid'] = $member_id;
        
        
		foreach($this->product_model->getProductAttributes($product_id, 'ALL') as $key=>$attribute){
			if(strtolower(gettype($key)) === 'string'){ 		#OPTIONAL ATTRIBUTES: BY NAME
				foreach($attribute as $product_attribute){
					if($product_attribute['type']  === 'option'){
						if(!array_key_exists($key,$response['product_attributes_opt']))
							$response['product_attributes_opt'][$key] = array();
						array_push($response['product_attributes_opt'][$key], $product_attribute);
					}
				}
			}
			else if(strtolower(gettype($key)) === 'integer'){  #SPECIFIC CATEGORY ATTRIBUTES: BY ID
				foreach($attribute as $product_attribute){
					if($product_attribute['type']  === 'specific'){
						if(!array_key_exists($key,$response['product_attributes_spe']))
							$response['product_attributes_spe'][$key] = array();
						array_push($response['product_attributes_spe'][$key], $product_attribute);
					}
				}			
			}
		}	
        
        $response['tempId'] = strtolower(substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,50 ) ,1 ) .substr( md5( time() ), 1));
		$response['main_images'] = $main_images;	
		$response['item_quantity'] =  $this->product_model->getProductQuantity($product_id, true);
		$response['img_max_dimension'] = $this->img_dimension['usersize'];
        $this->load->view('pages/product/product_upload_step2_view', $response);
		$this->load->view('templates/footer'); 

	}

     public function editStep2Submit()
	{
     
		$product_title = trim($this->input->post('prod_title'));
		$product_brief = trim($this->input->post('prod_brief_desc'));
		$product_description = trim($this->input->post('desc')) ;
        $product_price = str_replace(',', '', $this->input->post('prod_price')) ;
		$product_condition = $this->input->post('prod_condition');
		$sku = trim($this->input->post('prod_sku'));
        $keyword = es_url_clean(trim($this->input->post('prod_keyword')));
		$keyword = str_replace('-', ' ',$keyword);
		$product_id = $this->input->post('p_id');
		$brand_id =  $this->input->post('prod_brand');
        $brand_valid = false;
        $otherBrand = '';
        
		$style_id = 1;
		$member_id =  $this->session->userdata('member_id');
		$inputs = $this->input->post('inputs'); 
		$explode_inputs = explode("|", substr($inputs, 1));
		$combination = json_decode($this->input->post('combination'));
		$checkIfCombination = $this->input->post('noCombination');
        
		$cat_id = $this->input->post('id');
		$date = date("Ymd");
        $fulldate = date("YmdGis");

        if(intval($brand_id,10) == 1){
            $brand_valid = true;
            $otherBrand = $this->input->post('brand_sch');
            $brand_id = 1;
        }
        else{
            if($this->product_model->getBrandById($brand_id)){
                $brand_valid = true;
            }
        }

		if($brand_valid === FALSE){
			echo '{"e":"0","d":"The selected brand is unavailable. Please specify a valid one."}';	 
			exit();
		} 

		if (!in_array($product_condition, $this->lang->line('product_condition')))
		{
			echo '{"e":"0","d":"Condition selected not available. Please select another."}';	 
			exit();
		}

		if(strlen(trim($product_title)) == 0 || $product_title == "" 
			|| strlen(trim($product_brief)) == 0 || $product_brief == "" 
			|| strlen(trim($product_description)) <= 4
			|| strlen(trim($product_price)) == 0 || $product_price <= 0)
		{
			echo '{"e":"0","d":"Fill (*) All Required Fields Properly!"}';		
            exit();
		}else{

			$images = $this->product_model->getProductImages($product_id, true);
			$main_images = array();
			$other_images = array();
			foreach($images as $key=>$image){
				if(strpos(($image['path']),'other') === FALSE){
					$main_images[$key] = $image;
				}else{
					$other_images[$key] = $image;
				}
			}
			$main_image_cnt = count($main_images);

            $editRemoveThisPictures = json_decode($this->input->post('editRemoveThisPictures')); 
            $editPrimaryId = $this->input->post('editPrimaryPicture');
			$removeThisPictures = json_decode($this->input->post('removeThisPictures')); 
            $primaryId = $this->input->post('primaryPicture');
            $primaryName =""; 

            	
 			$arraynameoffiles = $this->input->post('arraynameoffiles');
			$arraynameoffiles = json_decode($arraynameoffiles);
			$arrayNameOnly = array();

            
			foreach($arraynameoffiles as $key => $value ) {
				$explodearraynameoffiles = explode('||', $value);
				$nameOfFile = $explodearraynameoffiles[0];
				$arrayNameOnly[$key] = strtolower($nameOfFile); 
                if($primaryId == $key){
					$primaryName =	strtolower($nameOfFile);
				}
				if (in_array($key, $removeThisPictures) || $arraynameoffiles[$key] == "") {
					unset($arraynameoffiles[$key]);
					unset($arrayNameOnly[$key]);
				} 
			}

			$arraynameoffiles = array_values($arraynameoffiles);
			$arrayNameOnly = array_values($arrayNameOnly); 
            # primary image is brought to the start of the array
            $key = array_search ($primaryName, $arrayNameOnly);
			if(!count($arraynameoffiles) <= 0){
				$temp = $arraynameoffiles[0];
				$arraynameoffiles[0] = $arraynameoffiles[$key];
				$arraynameoffiles[$key] = $temp;
				$temp = $arrayNameOnly[0];
				$arrayNameOnly[0] = $arrayNameOnly[$key];
				$arrayNameOnly[$key] = $temp;
			}
            
			if((count($arraynameoffiles) <= 0)  && ($main_image_cnt  === count($editRemoveThisPictures))){
                echo '{"e":"0","d":"Please select at least one photo for your listing."}';
				exit();
            }	
            
            $allowed =  array('gif','png' ,'jpg','jpeg'); # available format only for image

            $x = 0;
			if(!empty($_FILES['prod_other_img']['name'][0])){
                foreach($_FILES['prod_other_img']['name'] as $k) { # validating image format.
                    $filename = $_FILES['prod_other_img']['name'][$x];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $file_temp =  $_FILES['prod_other_img']['tmp_name'][$x];
                    if(!in_array(strtolower($ext),$allowed) || (getimagesize($file_temp) === FALSE)) 
                    {
                        echo '{"e":"0","d":"For Additional Information: File Selected not valid. \n Please choose another image."}';
                        exit();
                    }
                    if($_FILES["prod_other_img"]["size"][$x] >= $this->max_file_size_mb * 1024 * 1024 ) 
                    {
                        echo '{"e":"0","d":"For Additional Information: File size not valid. Please choose another image with smaller size. \n The maximum allowable file size is '.$this->max_file_size_mb.'mB."}';
                        exit();
                    }
                    $x++;
                }
			}
            
			#image directory
			
			if($main_image_cnt === 0){
                /* 
                 *  THIS SHOULD NEVER HAPPEN. IF IT DID, AN ERROR SAYING THE UPLOAD PATH IS INVALID WILL PROMPT THE USER
                 *  REASON FOR THIS HAPPENING: no image exists in es_product_image at the time of the edit
                 */
				$path_directory = './assets/product/'.$product_id.'_'.$member_id.'_'.$date.'/';
			}
			else{
				$path_directory = reset($main_images)['path'];
			}
			$other_path_directory = $path_directory.'other/';
			
			$product_details = array('product_id' => $product_id,
				'name' => $product_title,
				'sku' => $sku,
				'brief' => $product_brief,
				'description' => $product_description,
				'keyword' => $keyword,
				'brand_id' => $brand_id,
				'style_id' => $style_id,
				'price' => $product_price,
				'condition' => $product_condition,
                'brand_other_name' => $otherBrand,
				);
            

			$rowCount = $this->product_model->editProduct($product_details, $member_id);

			if($rowCount>0){
                $itemcombinations = $this->product_model->getProductQuantity($product_id, true);
                $update_product_item_obj = array();
                $default_retain_shipment = false;
               
                if($checkIfCombination == 'true' || $checkIfCombination == 1){
                    if(count($itemcombinations) === 1){
                        $combo_element = reset($itemcombinations);
                        $idx = key($itemcombinations);
                        if((count($combo_element['product_attribute_ids']) === 1)&& (intval($combo_element['product_attribute_ids'][0]['id'],10) === 0) &&(intval($combo_element['product_attribute_ids'][0]['is_other'],10) === 0)){
                            $quantitySolo = 1;
                            if($this->input->post('quantitySolo')){
                                $quantitySolo = $this->input->post('quantitySolo');
                                $quantitySolo = abs(str_replace(',','',$quantitySolo));
                            }
                            $default_retain_shipment = true;
                            array_push($update_product_item_obj, array('id' => $idx, 'qty' =>$quantitySolo));
                        }           
                    }
                }else{
                    foreach($combination as $index=>$new_elem){
                        $value_arr = explode('-',$new_elem->value);
                        $search_arr = array();
                        foreach($value_arr as $x){
                            $temp_arr = array();
                            $temp_arr = explode(':',$x);
                            if($temp_arr[0] == 0){
                                $temp_arr[2] = 'attr_lookuplist_item_id';
                            }
                            else if($temp_arr[0] == 1){
                                $temp_arr[2] = 'attr_name';
                            }        
                            array_push($search_arr, $temp_arr);
                        }
                        foreach($itemcombinations as $idx=>$old_elem){
                            $bool = true;
                            foreach($search_arr as $x){
                                if((!in_array($x[1],$old_elem[$x[2]])) || (count($search_arr) != count($old_elem[$x[2]]))){
                                    $bool = false;
                                    break;
                                }
                            }
                            if($bool){
                                //Form old product attribute/optional detail info string
                                $old_prod_attr_detail = '';
                                foreach($old_elem['product_attribute_ids'] as $y){
                                    $old_prod_attr_detail = $old_prod_attr_detail.(($old_prod_attr_detail=='')?'':'-').$y['is_other'].':'.$y['id'];
                                }
                                $combination->$index->item_id = $idx;
                                $combination->$index->old_prod_attr_id = $old_prod_attr_detail;
                                array_push($update_product_item_obj, array('id' => $idx, 'qty' =>$new_elem->quantity));
                                break;
                            }
                        }
                    }
                
                }

                $keep_ids = array();
                foreach($update_product_item_obj as $x){
                    array_push($keep_ids, $x['id']);
                }
                
           

                #DELETE PRODUCT ITEM, PRODUCT ITEM ATTR, SHIPPING HEAD AND SHIPPING DETAIL
                #THAT ARE NOT IN THE object
                $this->product_model->deleteShippingInfomation($product_id, $keep_ids);
                $this->product_model->deleteProductQuantityCombination($product_id, $keep_ids);

				foreach($explode_inputs as $input){
					$explode_id = explode('/', $input);
					$explode_value = $explode_id[0];
					$attribute_id = $explode_id[1];
					$post_attributes = $this->input->post($explode_value);
					$dataType = substr($explode_value,0,strpos($explode_value,'_'));
					$rowCount = $this->product_model->deleteAttributeByProduct($product_id, $attribute_id);
                    
					if($post_attributes){
						if(is_array($post_attributes)){
							$post_attributes_arr = $post_attributes;
						}else{
							$post_attributes_arr = (strlen(trim($post_attributes)) === 0)?array():array(0=>$post_attributes);
						}
						foreach($post_attributes_arr as $attribute){
                            //validate information before inserting
							$valid = false;
							switch($dataType){
								case 'TEXT':
								$valid = (count($this->product_model->selectAttributeNameWithTypeAndId($attribute_id,1))>0);
								break;
								case 'TEXTAREA':
								$valid = (count($this->product_model->selectAttributeNameWithTypeAndId($attribute_id,2))>0);
								break;
								default: 
								$valid = (count($this->product_model->selectAttributeNameWithNameAndId($attribute,$attribute_id))>0);
								break;
							}
							if($valid){
								$this->product_model->addNewAttributeByProduct($product_id, $attribute_id, $attribute, '0');
							}
						}
					}                    
				}
				
				$newarray = array();
                $option_image_idx = 0;

				$prod_other = $this->input->post('prod_other');
				$prod_other_name = $this->input->post('prod_other_name');
				$prod_other_id = $this->input->post('prod_other_id');
				$prod_other_price = $this->input->post('prod_other_price');
                $prod_other_img_idx = $this->input->post('prod_other_img_idx');

			 	for ($i=0; $i < sizeof($prod_other_name); $i++) { 
					if(!array_key_exists($prod_other_name[$i],$newarray)){
						$newarray[$prod_other_name[$i]] = array();
					}
					
					$other_name = "--no name";
					$other_price = "0.00";
					$other_image = "--no image";
					$other_image_type = "--no type";
					$other_tmp = "--no temp";
					$other_id = "--no id";
					if(strlen(trim($prod_other_price[$i])) != 0 || $prod_other_price[$i] != "") 
					{
                       $other_price = abs(str_replace(',','',$prod_other_price[$i]));
					}
                    
                    if(isset($_FILES['prod_other_img'])){
                        if(intval($prod_other_img_idx[$i],10) === 1){
                            if(!empty($_FILES['prod_other_img']['name'][$option_image_idx])){
                                $other_image_type = $_FILES['prod_other_img']['type'][$option_image_idx];
                                $file_ext = explode('.', $_FILES['prod_other_img']['name'][$option_image_idx]);
                                $other_image = $product_id.'_'.$member_id.'_'.$fulldate.$i.'_o.'.strtolower($file_ext[1]);
                                $other_tmp = $_FILES["prod_other_img"]["tmp_name"][$option_image_idx];
                            }
                            $option_image_idx++;
                        }
                    }

					if(isset($prod_other_id[$i]) && strlen(trim($prod_other_id[$i]) != 0) ){
						$other_id = $prod_other_id[$i];
					}

					if(strlen(trim($prod_other[$i])) != 0 ||  trim($_POST['prod_other'][$i]) != ""){
						$other_name = $prod_other[$i];
					}

					array_push($newarray[$prod_other_name[$i]], $other_name .'|'.$other_price.'|'.$other_image.'|'.$other_image_type.'|'.$other_tmp.'|'.$other_id);
				}
                
				$attr_opt_head = array();
				$attr_opt_det_idx = array();
				
				$product_attr = $this->product_model->getProductAttributes($product_id, 'ID');
				foreach($product_attr as $key=>$attribute){
					foreach($attribute as $attr_item){
						if($attr_item['type']  === 'option'){
							if(!in_array($key,$attr_opt_head)){
								array_push($attr_opt_head, $key);
							}
							$attr_opt_det_idx[$attr_item['value_id']] = $attr_item;
						}
					}	
				}
                
				foreach($attr_opt_head as $head_id){
					$this->product_model->deleteAttrOthers($head_id);
				}

				$is_primary = 0;
				foreach ($newarray as $key => $valuex) {
                    //If OTHER GROUP NAME is empty: skip 
					if(trim($key) == "" || strlen(trim($key)) <= 0 ){
						continue;
					}
                    //If FIRST OTHER ATTRIBUTE is empty: skip (assumption here is that there is always one
                    //attribute that is passed)
					if(count($valuex) <= 1 && $valuex[0] == '--no name|0.00|--no image|--no type|--no temp|--no id'){
						continue;
					}
					$others_id = $this->product_model->addNewAttributeByProduct_others_name($product_id,$key);
                    
					foreach ($valuex as $keyvalue => $value) {
						$imageid = 0;
                        $eval = explode("|", $value); 
                        //IF OTHER ATTRIBUTE is empty, skip the rest
						if(trim($eval[0]) == "--no name"){
							continue;
						}
						if($eval[2] != "--no image"){
							if($eval[5] != "--no id"){
								$int_img_id = intval($eval[5]);
								if(isset($attr_opt_det_idx[$int_img_id])){
									$this->product_model->deleteProductImage($product_id, $attr_opt_det_idx[$int_img_id]['img_id']);
								}
							}
							$imageid = $this->product_model->addNewProductImage($other_path_directory.$eval[2],$eval[3],$product_id,$is_primary);
							move_uploaded_file($eval[4], $other_path_directory.$eval[2]);
                            list($o_width, $o_height) = getimagesize($other_path_directory.$eval[2]);
                            //If user uploaded image is too large, resize and overwrite original image
                            if(($o_width > $this->img_dimension['usersize'][0])||($o_height > $this->img_dimension['usersize'][1])){
                                $this->es_img_resize($eval[2],$other_path_directory,'', $this->img_dimension['usersize']);
                            }
                            $this->es_img_resize($eval[2],$other_path_directory, 'small/', $this->img_dimension['small']);
                            $this->es_img_resize($eval[2],$other_path_directory.'small/', '../thumbnail/', $this->img_dimension['thumbnail']);  
						}
						else if($eval[2] == "--no image"){
							if($eval[5] != "--no id"){
								$int_img_id = intval($eval[5]);
								if(isset($attr_opt_det_idx[$int_img_id])){
									$imageid = $attr_opt_det_idx[$int_img_id]['img_id'];
								}
							}
						}  
						$this->product_model->addNewAttributeByProduct_others_name_value($others_id,$eval[0],$eval[1],$imageid);
                    }
				}
				#end other

				foreach($editRemoveThisPictures as $img_id){
					$this->product_model->deleteProductImage($product_id, $img_id);
					unset($main_images[$img_id]);
				}
				
				$currentPrimaryId = 0;
                $primary_image_bool = false;
				foreach($main_images as $main_image){
					if(intval($main_image['is_primary']) === 1){
						$currentPrimaryId = $main_image['id_product_image'];
						break;
					}
				}
                
                #New primary image from previous images, old primary image retained
                if((intval($editPrimaryId,10) > 0)&&(intval($currentPrimaryId,10)!==0)){
                    $this->product_model->updateImageIsPrimary($currentPrimaryId, 0);
                    $this->product_model->updateImageIsPrimary($editPrimaryId, 1);
                    $primary_image_bool = true;
                }
                #New primary image from previous images, old primary image deleted
                else if((intval($editPrimaryId,10) > 0)&&(intval($currentPrimaryId,10)===0)){   
                    $this->product_model->updateImageIsPrimary($editPrimaryId, 1);
                    $primary_image_bool = true;
                }
                #No new primary image from previous images, old primary image is not deleted and remains the primary
                #Simple image add/remove without affecting primary
                else if((intval($editPrimaryId,10) === 0)&&(intval($currentPrimaryId,10)!==0)){
                    $primary_image_bool = true;
                }
                #No new primary image from previous images, old primary image is not deleted
                #But new primary image is chosen from newly uploaded image
                else if((intval($editPrimaryId,10) === -1)&&(intval($currentPrimaryId,10)!==0)){
                    $this->product_model->updateImageIsPrimary($currentPrimaryId, 0);
                    $primary_image_bool = false;
                }
                #No new primary image from previous images, old primary image is deleted
                #New primary image is chosen from newly uploaded images
                else if((intval($editPrimaryId,10) === -1)&&(intval($currentPrimaryId,10)===0)){
                     $primary_image_bool = false;
                }
                
                # start of saving combination qty

                if($checkIfCombination == 'true' || $checkIfCombination == 1){
                    #SHIPMENT RETAIN: UPDATE
                    if(($default_retain_shipment)&&(count($update_product_item_obj) == 1)){
                        $x = reset($update_product_item_obj);
                        $this->product_model->updateCombination($product_id, $x['id'], $x['qty']);
                    }
                    #NEW ATTRIBUTE: INSERT
                    else{
                        $quantitySolo = 1;
                        if($this->input->post('quantitySolo')){
                            $quantitySolo = $this->input->post('quantitySolo');
                            $quantitySolo = abs(str_replace(',','',$quantitySolo));
                        }
                        $idProductItem = $this->product_model->addNewCombination($product_id,$quantitySolo);
                    }
                }else{
                    foreach ($combination as $keyCombination) {
                        if(isset($keyCombination->item_id)){
                            $this->product_model->updateCombination($product_id, $keyCombination->item_id, $keyCombination->quantity);
                            $explodeOldProdAttr = explode("-",  $keyCombination->old_prod_attr_id);
                            $explodeCombination = explode("-",  $keyCombination->value);
                            for($i = 0;$i < count($explodeCombination);$i++){
                                $explodeOld = explode(":",  $explodeOldProdAttr[$i]);
                                $is_other = $explodeOld[0];
                                $old_product_attr_id = $explodeOld[1];
                                $product_item_attr_id = $this->product_model->selectProductItemAttr($keyCombination->item_id,$old_product_attr_id, $is_other);
                                $explodeOther = explode(":",  $explodeCombination[$i]);
                                $otherAttrIdentifier = $explodeOther[0];
                                $otherAttrValue = $explodeOther[1];
                                $otherAttrGroup = $explodeOther[2];
                                if($otherAttrIdentifier == 1){
                                    $productAttributeId = $this->product_model->selectProductAttributeOther($otherAttrGroup, $otherAttrValue,$product_id);
                                }else{
                                    $productAttributeId = $this->product_model->selectProductAttribute($otherAttrValue,$product_id);
                                }
                                $this->product_model->updateCombinationAttribute($keyCombination->item_id, $productAttributeId  ,$otherAttrIdentifier, $product_item_attr_id);
                            }
                        }
                        else{
                            $quantitycombination = 1;
                            if(!$quantitycombination <= 0){
                                $quantitycombination = $keyCombination->quantity;
                            }
                            $idProductItem = $this->product_model->addNewCombination($product_id,$quantitycombination);
                            if(strpos($keyCombination->value, '-') !== false) {
                                $explodeCombination = explode("-",  $keyCombination->value);
                                foreach ($explodeCombination as $value) {
                                    $explodeOther = explode(":",  $value);
                                    $otherAttrIdentifier = $explodeOther[0];
                                    $otherAttrValue = $explodeOther[1];
                                    $otherAttrGroup = $explodeOther[2];
                                    if($otherAttrIdentifier == 1){
                                        $productAttributeId = $this->product_model->selectProductAttributeOther($otherAttrGroup,$otherAttrValue,$product_id);
                                    }else{
                                        $productAttributeId = $this->product_model->selectProductAttribute($otherAttrValue,$product_id);
                                    }

                                    $this->product_model->addNewCombinationAttribute($idProductItem,$productAttributeId,$otherAttrIdentifier);
                                }
                            }else{
                                $explodeOther = explode(":",  $keyCombination->value);
                                $otherAttrIdentifier = $explodeOther[0];
                                $otherAttrValue = $explodeOther[1];
                                $otherAttrGroup = $explodeOther[2];
                                if($otherAttrIdentifier == 1){
                                    $productAttributeId = $this->product_model->selectProductAttributeOther($otherAttrGroup,$otherAttrValue,$product_id);
                                }else{
                                    $productAttributeId = $this->product_model->selectProductAttribute($otherAttrValue,$product_id);
                                }
                                $this->product_model->addNewCombinationAttribute($idProductItem,$productAttributeId,$otherAttrIdentifier);
                            }
                        }   
                    }
                } 
                
                #end combination qty    

                $i = 0;
                foreach ($arraynameoffiles as $key => $value) {
                    $is_primary = 0;
                    if($i == 0)
                    {
                        $tempdirectory = $this->input->post('tempdirectory');
                        if(dirname($tempdirectory) !== './assets/temp_product'){
                            exit();
                        }
                        directory_copy($tempdirectory, $path_directory,$product_id,$arrayNameOnly); 
                        $is_primary = $primary_image_bool?0:1;
                    }
                    $explodearraynameoffiles = explode('||', $arraynameoffiles[$key]);
                    $nameOfFile = $explodearraynameoffiles[0];
                    $nameOfFileArray = explode('_', $nameOfFile);
                    $newName = $product_id.'_'.$nameOfFileArray[1].'_'.$nameOfFileArray[2];
                    $path = $path_directory.$newName;
                    $typeOfFile = $explodearraynameoffiles[1];                 
                    $this->product_model->addNewProductImage($path,$typeOfFile,$product_id,$is_primary);
                    $i++;
                }
                
				$data = '{"e":"1","d":"'.$product_id.'"}';
			}
			else{
				$data = '{"e":"0","d":"There was a problem. \n Please try again later! - Error[0011]"}';	
			}
		}
		echo $data;        
	}
    

	public function deleteDraft(){
		$productId = $this->input->post('p_id');
		$memberId =  $this->session->userdata('member_id');
		$output = $this->product_model->deleteDraft($memberId,$productId);
		if($output == 0){
			$data = '{"e":"0","m":"Sorry, something went wrong. Please try again later"}';	
		}else{
			$data = '{"e":"1","d":"Draft item successfully removed."}';	
		}
		echo $data; 
	}
    
    public function previewItem(){
        if($this->input->post('p_id'))
			$id = $this->input->post('p_id');
		else
			redirect('sell/step1', 'refresh'); 
        #$modal is true unless the string 'false' modal parameter is posted
        if($this->input->post('modal') == 'false'){
            $modal = false;
        }else{
            $modal = true;
        }
        $this->load->model("memberpage_model");
        $memberId =  $this->session->userdata('member_id');
        $product_row = $this->product_model->getProductPreview($id, $memberId);
        if(empty($product_row)){
            redirect('sell/step1', 'refresh');
        }
        $quantities = $this->product_model->getProductQuantity($id);
        $availability = "varies";
 
        foreach($quantities as $qty){
            if(count($qty['product_attribute_ids'])===1){
                if(($qty['product_attribute_ids'][0]['id'] == 0)&&($qty['product_attribute_ids'][0]['is_other'] == 0)){
                    $availability = $qty['quantity'];
                }
            }   
        }
        
        $product_options = $this->product_model->getProductAttributes($id, 'NAME');
		$product_options = $this->product_model->implodeAttributesByName($product_options);

        $preview_data = array(
            'breadcrumbs' =>  $this->product_model->getParentId($product_row['cat_id']),
            'product' => $product_row,
            'billing_info' => $this->memberpage_model->get_billing_info($memberId),
            'bank_list' =>  $this->memberpage_model->getAllBanks(),
            'main_categories' => $this->product_model->getFirstLevelNode(TRUE),
            'product_images' => $this->product_model->getProductImages($id),
            'product_options' => $product_options,
            'availability' => $availability,
            'modal' => $modal,
        );
        if($modal){ 
            $this->load->view('pages/product/product_upload_preview',$preview_data);
        }
		else{
            $data = $this->fill_view();
            $this->load->view('templates/header', $data);
            $this->load->view('pages/product/product_upload_preview',$preview_data);
            $this->load->view('templates/footer_full');
        }
    }
    

    
    public function getAllChildren(){
        $cat_arr = json_decode($this->input->get('cat_array'));
        $level = 0;
        $prev_cat_name ='';
        foreach($cat_arr as $idx=>$id){
            $response['cat_id'] = $id;
            $response['name'] = $this->product_model->getCategoryDetails($id)['name'];
            $response['node'] = $this->product_model->getDownLevelNode($id); # get all down level category based on selected parent category            
            $response['level'] = $level;
            $data[$idx] = $this->load->view('pages/product/product_upload_step1_view2',$response,TRUE);
            $level++;
        }
        echo json_encode($data);
    }

    private function es_img_resize($filename,$path_directory,$added_path,$dimension){
        $filename = strtolower($filename);
		$path_to_result_directory = $path_directory.$added_path; 
		$path_to_image_directory = $path_directory;

		$config['image_library'] = 'GD2';
		$config['source_image'] = $path_to_image_directory . $filename;
		$config['maintain_ratio'] = true;
		$config['quality'] = '85%';
		$config['new_image'] = $path_to_result_directory . $filename;
		$config['width'] = $dimension[0];
		$config['height'] = $dimension[1];
 
		if(!file_exists($path_to_result_directory)) {  
			if(!mkdir($path_to_result_directory)) {  
				die('{"e":"0","d":"There was a problem. \n Please try again later! - Error[0015]"}');  
			}   
		}
		
		$this->image_lib->initialize($config); 
		$this->image_lib->resize();
        $this->image_lib->clear();
    }


}


/* End of file productUpload.php */
/* Location: ./application/controllers/productUpload.php */