<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Mycontroller extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('my_model');
		$this->load->model('product_model');
	}
	
	function generateProductSlug()
	{
		// Returns product id, trimmed lower case product name
		$product = $this->my_model->getNoSlugProduct();
		$flagger = TRUE;
		
		foreach($product as $p){
			$slugVal = $this->product_model->createSlug($p['name']);
			$result = $this->my_model->updateProductSlug($p['id_product'], $slugVal);
			if(!$result){
				echo 'Error updating slug field of current product.' ;
				$flagger = FALSE;
				break;
			}
		}
		if($flagger){
			echo 'Successfully updated slug for all products.';
		}
	}
	
	function generateCategorySlug()
	{
		// Returns product id, trimmed lower case product name
		$category = $this->my_model->getNoSlugCategory();
		$flagger = TRUE;
		
		foreach($category as $p){
			$slugVal = $this->product_model->createSlug($p['name'],1);
			$result = $this->my_model->updateCategorySlug($p['id_cat'], $slugVal);
			if(!$result){
				echo 'Error updating slug field of current category.' ;
				$flagger = FALSE;
				break;
			}
		}
		if($flagger){
			echo 'Successfully updated slug for all categories.';
		}
	}
	
	function mobileTest($mobile, $msg, $from)
	{
		$fields = array();
		$fields["api"] = "dgsMQ8q77hewW766aqxK";
		$fields["number"] = $mobile; //safe use 63
		$fields["message"] = $msg;
		$fields["from"] = $from;
		$fields_string = http_build_query($fields);
		$outbound_endpoint = "http://api.semaphore.co/api/sms";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $outbound_endpoint);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		
		return $output;
	}

}

?>