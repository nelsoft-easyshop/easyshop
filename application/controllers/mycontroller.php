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
	
	function generateMemberSlug()
	{
		$member = $this->my_model->getNoSlugMember();
		$flagger = TRUE;
		
		foreach( $member as $m ){
			$slugVal = $this->my_model->createSlug($m['username']);
			$result = $this->my_model->updateMemberSlug($m['id_member'], $slugVal);
			if(!$result){
				echo 'Error updating slug field of current member with username : ' . $m['username'] ;
				$flagger = FALSE;
				break;
			}
		}
		if($flagger){
			echo 'Successfully updated slug for ' . count($member) . ' members.';
		}
	}
	
	function mobileTest()
	{
		$mobile = $this->input->get('m');
		$msg = $this->input->get('msg');
		$from = $this->input->get('f');
		
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
	
	function emailTest($msg){
		$this->load->library('email');	
		
		$this->email->set_newline("\r\n");
		$this->email->to('janz.stephen@gmail.com');
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');		
		$this->email->subject('Easyshop Test email.');

		$this->email->message($msg);
		$result = $this->email->send();

		print('Email Data response:<br>');
		print_r($this->email->print_debugger());
		print_r($result);
	}
	
	public function emailServiceTest()
	{
		$emailService = $this->serviceContainer['email_notification'];

		$successCount = $emailService->setRecipient('stephenjanz@yahoo.com')
									->setSubject("Test Email")
									->setMessage("Test image file <img src='img_logo.png'>", getAssetsDomain()."assets/images/img_logo.png")
									->sendMail();

		print('successfully sent ' . $successCount . " emails");
	}

	public function emailQueueServiceTest()
	{
		$emailService = $this->serviceContainer['email_notification'];

		$boolQueueStat = $emailService->setRecipient('stephenjanz@yahoo.com')
									->setSubject("Test Email")
									->setMessage("Test image file <img src='img_logo.png'>", getAssetsDomain()."/assets/images/img_logo.png")
									->queueMail();

		if($boolQueueStat){
			print("Email Queued");
		}
		else{
			print("Failed to queue mail.");
		}
	}
	
}

