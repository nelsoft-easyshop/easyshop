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
	
	function index()
	{
		// Returns product id, trimmed lower case product name
		$product = $this->my_model->getNoSlugProduct();
		$flagger = TRUE;
		
		foreach($product as $p){
			$slugVal = $this->product_model->createSlug($p['name']);
			$result = $this->my_model->updateSlug($p['id_product'], $slugVal);
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
}

?>