<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Memberpage extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("memberpage_model");
		$this->load->model('register_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		$this->form_validation->set_error_delimiters('', '');
	}
    
	function index()
	{        
        $data = $this->fill_header();
		if(!$this->session->userdata('member_id')){
            redirect(base_url().'home', 'refresh');
        }
        $data['tab'] = $this->input->get('me');        
		$data = array_merge($data, $this->fill_view());
        $data['render_logo'] = false;
        $data['render_searchbar'] = false;
		$this->load->view('templates/header', $data);
		$this->load->view('pages/user/memberpage_view', $data);
		$this->load->view('templates/footer');
	}

	function edit_personal()
	{
		if(($this->input->post('personal_profile_main'))&&($this->form_validation->run('personal_profile_main')))
		{
			$uid = $this->session->userdata('member_id');
			$checkdata = array(
				'member_id' => $uid,
				'contactno' => $this->input->post('mobile'),
				'email' => $this->input->post('email')
			);

			$check = $this->register_model->check_contactinfo($checkdata);
			if($check['mobile'] !== 0 || $check['email'] !== 0){
				echo json_encode($check);
				return;
			}

			$uid = $this->session->userdata('member_id');
			$postdata = array(
				'fullname' => $this->input->post('fullname'),
				'nickname' => $this->input->post('nickname'),
				'gender' => $this->input->post('gender'),
				'birthday' => $this->input->post('dateofbirth'),
				'contactno' => $this->input->post('mobile'),
				'email' => $this->input->post('email')
			);

			if($postdata['email'] === $this->input->post('email_orig'))
				$postdata['is_email_verify'] = $this->input->post('is_email_verify');
			else
				$postdata['is_email_verify'] = 0;

			if($postdata['contactno'] === $this->input->post('mobile_orig'))
				$postdata['is_contactno_verify'] = $this->input->post('is_contactno_verify');
			else
				$postdata['is_contactno_verify'] = 0;

			$result = $this->memberpage_model->edit_member_by_id($uid, $postdata);

			echo 1;
		}
		else{
			echo 0;
		}
	}

	function edit_address()
	{
		if(($this->input->post('personal_profile_address_btn'))&&($this->form_validation->run('personal_profile_address')))
		{
			$postdata = array(
				'stateregion' => $this->input->post('stateregion'),
				'city' => $this->input->post('city'),
				'address' => $this->input->post('address'),
				'country' => $this->input->post('country'),
				'addresstype' => 0,
				'consignee' => '',
				'mobile' => '',
				'telephone' => '',
				'lat' => $this->input->post('temp_lat'),
				'lng' => $this->input->post('temp_lng')
			);

			$temp = array(
				'stateregion_orig' => $this->input->post('stateregion_orig'),
				'city_orig' => $this->input->post('city_orig'),
				'address_orig' => $this->input->post('address_orig'),
				'map_lat' => $this->input->post('map_lat'),
				'map_lng' => $this->input->post('map_lng')
			);

			if( ( ($temp['stateregion_orig'] != $postdata['stateregion']) || ($temp['city_orig'] != $postdata['city']) || ($temp['address_orig'] != $postdata['address']) ) 
				&& ($temp['map_lat'] == $postdata['lat'] && $temp['map_lng'] == $postdata['lng']) ) {
				$postdata['lat'] = 0;
				$postdata['lng'] = 0;
			}

			$uid = $this->session->userdata('member_id');
			$address_id = $this->memberpage_model->getAddress($uid,0)['id_address'];
			$result = $this->memberpage_model->editAddress($uid, $postdata, $address_id);
			
			$data = $this->memberpage_model->get_member_by_id($uid);
			
			$data['result'] = $result ? 'success':'fail';
			$data['errmsg'] = $result ? '' : 'Database update error.';
			
		}else{
			$data['result'] = 'error';
			$data['errmsg'] = 'Failed to validate form.';
		}
		$this->output->set_output(json_encode($data));
	}

	function edit_school()
	{
		if(($this->input->post('personal_profile_school'))&&($this->form_validation->run('personal_profile_school')))
		{
			$arr = $this->input->post();
			for($i = 1; $i<=count($arr)>>2; $i++)
			{
				$postdata = array(
					'school' => $arr['schoolname'.$i],
					'year' => $arr['schoolyear'.$i],
					'level' => $arr['schoollevel'.$i],
					'school_count' => $arr['schoolcount'.$i],
				);
				$uid = $this->session->userdata('member_id');
				$result = $this->memberpage_model->edit_school_by_id($uid, $postdata);
				// If database entry fails, break
				if(!$result){
					break;
				}
			}
			$uid = $this->session->userdata('member_id');
			$data = $this->memberpage_model->get_school_by_id($uid);
			
			$data['result'] = $result ? 'success' : 'fail';
			$data['errmsg'] = $result ? '' : 'Database update error';
		}else{
			$data['result'] = 'error';
			$data['errmsg'] = 'Failed to validate form.';
		}
		
		echo json_encode($data);
	}

	function deletePersonalInfo()
	{
		$field = html_escape($this->input->post('field'));
		if( $field !== '' ){
			$member_id = $this->session->userdata('member_id');
			$result = $this->memberpage_model->deletePersonalInformation($member_id, $field);
			if($result){
				echo 1;
			}
			else{
				echo 0;
			}
		}
	}

	function fill_view()
	{
		$uid = $this->session->userdata('member_id');
		$user_product_count = $this->memberpage_model->getUserItemCount($uid);
		$data = array(
				'title' => 'Easyshop.ph - Member Profile',
				'image_profile' => $this->memberpage_model->get_image($uid),
				'active_products' => $this->memberpage_model->getUserItems($uid,0),
				'deleted_products' => $this->memberpage_model->getUserItems($uid,1),
				'active_count' => intval($user_product_count['active']),
				'deleted_count' => intval($user_product_count['deleted']),
                'sold_count' => intval($user_product_count['sold'])
                );
		$data = array_merge($data, $this->memberpage_model->getLocationLookup());
		$data = array_merge($data,$this->memberpage_model->get_member_by_id($uid));
		$data = array_merge($data,$this->memberpage_model->get_work_by_id($uid));
		$data =  array_merge($data,$this->memberpage_model->get_school_by_id($uid));
		$data['bill'] =  $this->memberpage_model->get_billing_info($uid);
		//$data['transaction'] = $this->memberpage_model->getTransactionDetails($uid);
		
		$data['transaction'] = array(
			'buy' => $this->memberpage_model->getBuyTransactionDetails($uid, 0),
			'sell' => $this->memberpage_model->getSellTransactionDetails($uid, 0),
			'complete' => array(
				'buy' => $this->memberpage_model->getBuyTransactionDetails($uid, 1),
				'sell' => $this->memberpage_model->getSellTransactionDetails($uid, 1)
			)
		);
		//$data['transaction']['buy'] = $this->memberpage_model->getBuyTransactionDetails($uid, 0);
		//$data['transaction']['sell'] = $this->memberpage_model->getSellTransactionDetails($uid, 0);
		
		$data['transaction']['count'] = $this->memberpage_model->getTransactionCount($uid);
		$data['allfeedbacks'] = $this->memberpage_model->getFeedback($uid);
		$data['sales'] = array(
			'release' => $this->memberpage_model->getNextPayout($uid),
			'balance' => $this->memberpage_model->getUserBalance($uid)
		);
		
		return $data;
	}

	function upload_img()
	{
		$data = array(
			'x' => $this->input->post('x'),
			'y' => $this->input->post('y'),
			'w' => $this->input->post('w'),
			'h' => $this->input->post('h')
		);
		$uid = $this->session->userdata('member_id');
		$this->load->library('upload');
		$this->load->library('image_lib');
		$result = $this->memberpage_model->upload_img($uid, $data);
		//echo error may be here: $result['error']
		redirect('me');
	}

	public function external_callbacks( $postdata, $param )
	{
		 $param_values = explode( ',', $param );
		 $model = $param_values[0];
		 $this->load->model( $model );
		 $method = $param_values[1];
		 if( count( $param_values ) > 2 ) {
			  array_shift( $param_values );
			  array_shift( $param_values );
			  $argument = $param_values;
		 }
		 if( isset( $argument ))
			$callback_result = $this->$model->$method( $postdata, $argument );
		 else
			$callback_result = $this->$model->$method( $postdata );
		 return $callback_result;
	}

	function edit_consignee_address()
	{
		if(($this->input->post('c_deliver_address_btn'))&&($this->form_validation->run('c_deliver_address'))){
			$uid = $this->session->userdata('member_id');
			$result = array(false,false);
			
			$postdata = array(
				'consignee' => $this->input->post('consignee'),
				'mobile' => $this->input->post('c_mobile'),
				'telephone' => $this->input->post('c_telephone'),
				'stateregion' => $this->input->post('c_stateregion'),
				'city' => $this->input->post('c_city'),
				'address' => $this->input->post('c_address'),
				'country' => $this->input->post('c_country'),
				'lat' => $this->input->post('temp_lat'),
				'lng' => $this->input->post('temp_lng'),
				'addresstype' => 1
			);

			$temp = array(
				'stateregion' => $this->input->post('cstateregion_orig'),
				'city' => $this->input->post('ccity_orig'),
				'address' => $this->input->post('caddress_orig'),
				'map_lat' => $this->input->post('map_lat'),
				'map_lng' => $this->input->post('map_lng')
			);
			
			if( ( ($temp['stateregion'] != $postdata['stateregion']) || ($temp['city'] != $postdata['city']) || ($temp['address'] != $postdata['address']) ) 
				&& ($temp['map_lat'] == $postdata['lat'] && $temp['map_lng'] == $postdata['lng']) ) {
				$postdata['lat'] = 0;
				$postdata['lng'] = 0;
			}
			
			$address_id = $this->memberpage_model->getAddress($uid,1)['id_address'];
			$result[0] = $this->memberpage_model->editAddress($uid, $postdata, $address_id);
			
			if($this->input->post('c_def_address')){
				$address_id = $this->memberpage_model->getAddress($uid,0)['id_address'];
				$postdata['addresstype'] = 0;
				$result[1] = $this->memberpage_model->editAddress($uid, $postdata, $address_id);
				$data['default_add'] = $this->input->post('c_def_address');
			}else{
				$result[1] = true;
				$data['default_add'] = 'off';
			}
			
			$data['result'] = $result[0] && $result[1] ? 'success':'fail';
			$data['errmsg'] = $result[0] && $result[1] ? '' : 'Database update error.';
			
			$data = array_merge($data,$this->memberpage_model->get_member_by_id($uid));
			
		}else{
			$data['result'] = 'fail';
			$data['errmsg'] = 'Failed to validate form.';
		}
		
		$this->output->set_output(json_encode($data));
	}

	function edit_work()
	{
		if(($this->input->post('personal_profile_work_btn'))&&($this->form_validation->run('personal_profile_work')))
		{
			$rowcount = count($this->input->post()) - 1;
			$rowcount = $rowcount / 4;
			$postdata = array();
			for($x=1;$x<=$rowcount;$x++){
				$postdata = array(
					'companyname' => $this->input->post('companyname'.$x),
					'designation' => $this->input->post('designation'.$x),
					'year' => $this->input->post('year'.$x),
					'count' => $this->input->post('workcount'.$x)
				);
				$uid = $this->session->userdata('member_id');
				$result = $this->memberpage_model->edit_work_by_id($uid, $postdata);
				
				if(!$result){
					break;
				}
			}
			$uid = $this->session->userdata('member_id');
			$data = $this->memberpage_model->get_work_by_id($uid);
			
			$data['result'] = $result ? 'success' : 'fail';
			$data['errmsg'] = $result ? '' : 'Database update error.';
			
		}else{
			$data['result'] = 'error';
			$data['errmsg'] = 'Failed to validate form.';
		}
		
		echo json_encode($data);
	}

	/*****************	TRANSACTION CONTROLLER	*******************/

	/*
	 *	Function to add feedback to USER for every transaction made
	 */
	function addFeedback(){
		if($this->input->post('order_id') && $this->input->post('feedback-field') && $this->form_validation->run('add_feedback_transaction')){
			$result = false;
			$data = array(
				'uid' => $this->session->userdata('member_id'),
				'for_memberid' => $this->input->post('for_memberid'),
				'feedb_msg' => $this->input->post('feedback-field'),
				'feedb_kind' => $this->input->post('feedb_kind'),
				'order_id' => $this->input->post('order_id'),
				'rating1' => $this->input->post('rating1'),
				'rating2' => $this->input->post('rating2'),
				'rating3' => $this->input->post('rating3')
			);
			
			/******** Check if transaction exists based on post details ***********/
			// current user is buyer
			if($data['feedb_kind'] == 0){
				$transacData = array(					
					'buyer' => $data['uid'],
					'seller' => $data['for_memberid'],
					'order_id' => $data['order_id']
				);
			// current user is seller
			}else if($data['feedb_kind'] == 1){
				$transacData = array(					
					'buyer' => $data['for_memberid'],
					'seller' => $data['uid'],
					'order_id' => $data['order_id']
				);
			}
			$checkTransaction = $this->payment_model->checkTransaction($transacData);
			
			if(count($checkTransaction) > 0){ // if transaction exists
				/****** Check if feedback entry already exists ******/
				$checkFeedback = $this->memberpage_model->checkFeedback($data);

				if(count($checkFeedback) == 0){ // if no feedback entry
					$result = $this->memberpage_model->addFeedback($data);
				}
			}
			
			echo $result?1:0;
		}
		else
			echo 0;
	}
	
	/*
	 *	Function to handle payment transfer.
	 *	Forward to seller (status = 1) or return to buyer (status = 2).
	 */
	function transactionResponse(){
		
		$serverResponse = array(
			'result' => 'fail',
			'error' => 'Failed to validate form'
		);
		
		$data['transaction_num'] = $this->input->post('transaction_num');
		$data['invoice_num'] = $this->input->post('invoice_num');
		$data['member_id'] = $this->session->userdata('member_id');
		
		if( $this->input->post('buyer_response') || $this->input->post('seller_response') || $this->input->post('cash_on_delivery') ){
			
			$authenticateData = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'member_id' => $this->session->userdata('member_id')
			);
			
			if( ! $this->memberpage_model->authenticateUser($authenticateData) ){
				$serverResponse = array(
					'result' => 'invalid',
					'error' => 'Incorrect password.'
				);
				echo json_encode($serverResponse);
				exit;
			}
			
			// Check type of response ( if user or seller response )
			if( $this->input->post('buyer_response') ){
				$data['order_product_id'] = $this->input->post('buyer_response');
				$data['status'] = 1;
			}else if( $this->input->post('seller_response') ){
				$data['order_product_id'] = $this->input->post('seller_response');
				$data['status'] = 2;
			}else if( $this->input->post('cash_on_delivery') ){
				$data['order_product_id'] = $this->input->post('cash_on_delivery');
				$data['status'] = 3;
			}
			
			// Update database entries and retrieve update stats and buyer info
			// Also checks for data accuracy
			// Returns : o_success, o_message
			//$result['o_success'] = 1; // DEV code
			$result = $this->payment_model->updateTransactionStatus($data);
            
			// If database update is successful and response is 'return to buyer', 
			// get order_product transaction details and send notification (email mobile)
			if( $result['o_success'] >= 1 && $data['status'] == 2 ){
				$parseData = $this->payment_model->getOrderProductTransactionDetails($data);
				
				// 3 tries to send email. Exit if success or 3 fail limit reached
				$emailcounter = 0;
				do{
					$emailstat = $this->payment_model->sendNotificationEmail($parseData, $parseData['email'], 'return_payment');
					$emailcounter++;
				}while(!$emailstat && $emailcounter < 3);
				
				if($parseData['mobile'] != '' && $parseData['mobile'] != 0){
					$msg = $parseData['user'] . ' has just confirmed to return your payment for a product in Invoice # : ' . $parseData['invoice_no'];
					$mobilestat = $this->payment_model->sendNotificationMobile($parseData['mobile'], $msg);
				}
				
			}else if( $result['o_success'] >= 1 && ( $data['status'] === 1 || $data['status'] === 3) ){
				$emailstat = true;
			}
		
			$serverResponse['error'] = $result['o_success'] >= 1 ? '' : 'Server unable to update database.';
			$serverResponse['result'] = $result['o_success'] >= 1 ? 'success':'fail';
			
			if($result['o_success'] >= 1){
				if(!$emailstat){
					$serverResponse['error'] = 'Failed to send notification email.';
				}
			}
		/*************	DRAGONPAY HANDLER	************************/
		}else if( $this->input->post('dragonpay') ){
			$this->load->library('dragonpay');
			
			// Fetch transaction data
			$checkTransaction = $this->payment_model->checkTransactionBasic($data);
			$txnId = $checkTransaction[0]['transaction_id'];
			
			if(count($checkTransaction) == 1){
				// Check dragonpay transaction status - connects to Dragonpay
				$dragonpayResult = $this->dragonpay->getStatus($txnId);
				
				if($dragonpayResult == 'S'){ // Transaction Complete
					$serverResponse['result'] = 'success';
					$serverResponse['error'] = '';
				}else if($dragonpayResult == 'P' || $dragonpayResult == 'U'){
					$serverResponse['error'] = 'Kindly follow the instructions in the e-mail sent to you by Dragonpay.';
				}
			}else{
				$serverResponse['error'] = 'Transaction does not exist.';
			}
		/*************	BANK DEPOSIT HANDLER	************************/
		}else if( $this->input->post('bank_deposit') && $this->form_validation->run('bankdeposit') ) {
			// Fetch transaction data
			$checkTransaction = $this->payment_model->checkTransactionBasic($data);
			
			if( count($checkTransaction) == 1 ){
				$postData = array(
					'order_id' => $data['transaction_num'],
					'bank' => $this->input->post('bank'),
					'ref_num' => $this->input->post('ref_num'),
					'amount' => preg_replace('/,/', '', $this->input->post('amount')),
					'date_deposit' => date("Y-m-d H:i:s", strtotime($this->input->post('date'))),
					'comment' => $this->input->post('comment')
				);
				$result = $this->payment_model->addBankDepositDetails($postData);
				
				$serverResponse['result'] = $result ? 'success' : 'fail';
				$serverResponse['error'] = $result ? '' : 'Failed to insert details into database.';
			}else{
				$serverRespone['error'] = 'Transaction does not exist.';
			}
		}
		echo json_encode($serverResponse);
	}
	
	function addShippingComment()
	{
		$serverResponse['result'] = 'fail';
		$serverResponse['error'] = 'Failed to validate form.';
		
		if( $this->form_validation->run('addShippingComment') ){
			$postData = array(
				'comment' => $this->input->post('comment'),
				'order_product' => $this->input->post('order_product'),
				'member_id' => $this->session->userdata('member_id'),
				'transact_num' => $this->input->post('transact_num'),
				'courier' => $this->input->post('courier'),
				'tracking_num' => $this->input->post('tracking_num'),
				'expected_date' => date("Y-m-d H:i:s", strtotime($this->input->post('expected_date'))),
				'delivery_date' => date("Y-m-d H:i:s", strtotime($this->input->post('delivery_date')))
			);
			
			$result = $this->payment_model->checkOrderProductBasic($postData);
			
			if( count($result) == 1 ){ // insert comment
				$r = $this->payment_model->addShippingComment($postData);
				$serverResponse['result'] = $r ? 'success' : 'fail';
				$serverResponse['error'] = $r ? '' : 'Failed to insert in database.';
			} else {
				$serverResponse['result'] = 'success';
				$serverResponse['error'] = 'Server data mismatch. Possible hacking attempt';
			}
		}
		
		echo json_encode($serverResponse);
	}
	
	function rejectItem()
	{
		$data = array(
			'order_product' => $this->input->post('order_product'),
			'transact_num' => $this->input->post('transact_num'),
			'member_id' => $this->input->post('seller_id'),
			'method' => $this->input->post('method')
		);
		
		$serverResponse = array(
			'result' => 'fail',
			'error' => 'Transaction does not exist.'
		);
		
		$result = $this->payment_model->checkOrderProductBasic($data);
		
		if( count($result) == 1 ){
			$dbresult = $this->payment_model->responseReject($data);
			if($dbresult){
				$historyData['order_product_id'] = $data['order_product'];
				$historyData['order_product_status'] = 99;
				if($data['method'] === 'reject'){
					$historyData['comment'] = 'REJECTED';
				}else if($data['method'] === 'unreject'){
					$historyData['comment'] = 'UNREJECTED';
				}
				$this->payment_model->addOrderProductHistory($historyData);
			}
			$serverResponse['result'] = $dbresult ? 'success':'fail';
			$serverResponse['error'] = $dbresult ? '':'Failed to update database.';
		}
		
		echo json_encode($serverResponse);
	}

	/***	VENDOR DASHBOARD CONTROLLER	***/
	/*** 	memberpage/vendor/username	***/
	function vendor($selleruname){
		$session_data = $this->session->all_userdata();
		$vendordetails = $this->memberpage_model->getVendorDetails($selleruname);
		$data['title'] = 'Vendor Profile | Easyshop.ph';
		$data['my_id'] = (empty($session_data['member_id']) ? 0 : $session_data['member_id']);
		$data = array_merge($data, $this->fill_header());
		if($vendordetails){
            $data['render_logo'] = false;
            $data['render_searchbar'] = false;
            $this->load->view('templates/header', $data);
			$sellerid = $vendordetails['id_member'];
			$user_product_count = $this->memberpage_model->getUserItemCount($sellerid);
			$data = array_merge($data,array(
					'vendordetails' => $vendordetails,
					'image_profile' => $this->memberpage_model->get_Image($sellerid),
					'active_products' => $this->memberpage_model->getUserItems($sellerid,0),
					'deleted_products' => $this->memberpage_model->getUserItems($sellerid,1),
					'active_count' => intval($user_product_count['active']),
					'deleted_count' => intval($user_product_count['deleted']),
                    'sold_count' => intval($user_product_count['sold'])
					));
			$data['allfeedbacks'] = $this->memberpage_model->getFeedback($sellerid);
			$this->load->view('pages/user/vendor_view', $data);
            $this->load->view('templates/footer');
		}
		else{
            $this->load->view('templates/header', $data);
			$this->load->view('pages/user/user_error');
            $this->load->view('templates/footer_full');
		}
	
	}


	/**	VERIFY CONTACT DETAILS SECTION **/
	function verify(){
		if($this->input->post('reverify') === 'true'){
			$uid = $this->session->userdata('member_id');

			$data = $this->register_model->get_verifcode($uid);

			if($this->input->post('field') === 'mobile' && $this->input->post('data') == $data['contactno'])
			{
				//GENERATE NEW MOBILE CONFIRMATION CODE
				$confirmation_code = $this->register_model->rand_alphanumeric(6);
				$hash = $data['emailcode'];
				$temp = array(
					'member_id' => $uid,
					'mobilecode' => $confirmation_code,
					'emailcode' => $hash,
					'mobile' => 0,
					'email' => 0
				);

				if($data['mobilecount'] < 4 || $data['time'] > 30){
					$result = $this->register_model->send_mobile_msg($data['username'], $data['contactno'], $confirmation_code);
					if($result === 'success'){
						$this->session->set_userdata('mobilecode', $confirmation_code);
						$temp['mobile'] = 1;
					}
				}
				else
					$result = 'exceed';

				$this->register_model->store_verifcode($temp);
				echo json_encode($result);
			}
			else if($this->input->post('field') === 'email' && $this->input->post('data') == $data['email'])
			{
				//GENERATE NEW HASH FOR EMAIL VERIFICATION
				$hash = sha1($this->session->userdata('session_id').time());
				$confirmation_code = $data['mobilecode'];
				$temp = array(
					'member_id' => $uid,
					'mobilecode' => $confirmation_code,
					'emailcode' => $hash,
					'mobile' => 0,
					'email' => 0
				);

				if($data['emailcount'] < 4 || $data['time'] > 30){
					$result = $this->register_model->send_email_msg($data['email'], $data['username'], $hash);
					if($result === 'success')
						$temp['email'] = 1;
				}
				else
					$result = 'exceed';

				$this->register_model->store_verifcode($temp);
				echo json_encode($result);
			}
			else
				echo json_encode('dataerror');
		}
		else
			echo 0;
	}


	function verify_mobilecode(){
		if($this->input->post('mobileverify') === 'true'){
			$user_mobilecode = html_escape($this->input->post('data'));

			if($user_mobilecode === $this->session->userdata('mobilecode')){
				$data = array(
					'is_contactno_verify' => 1,
					'member_id' => $this->session->userdata('member_id')
				);
				$this->session->unset_userdata('mobilecode');
				$this->register_model->update_verification_status($data);
				echo 1;
			}
			else
				echo 0;
		}
	}

	function bank_info(){
		$q = $this->input->get('q');
		if(!empty($q)){
			$bank_names = $this->memberpage_model->get_bank($q, 'name');
			echo json_encode($bank_names);
		}
	}

	function billing_info(){
        # if(($this->input->post('bi_acct_no')) && ($this->form_validation->run('billing_info'))){
	
		if($this->input->post('bi_acct_no')){

			$member_id = $this->session->userdata('member_id');
			$bi_payment_type = $this->input->post('bi_payment_type');
			$bi_user_account = ""; // pang paypal ito or any online account
			$bi_bank = $this->input->post('bi_bank');
			$bi_acct_name = $this->input->post('bi_acct_name');
			$bi_acct_no = $this->input->post('bi_acct_no');
			$data = array(
				'member_id' => $member_id,
				'payment_type' => $bi_payment_type,
				'user_account' => $bi_user_account,
				'bank_id' => $bi_bank,
				'bank_account_name' => $bi_acct_name,
				'bank_account_number' => $bi_acct_no
			);
			
			if($this->memberpage_model->isBankAccountUnique($data)){
				$result = $this->memberpage_model->billing_info($data);
                echo '{"e":"1","d":"success","id":'.$result.'}';
			}else{
				echo '{"e":"0","d":"duplicate"}';
			}			
		}
		else{
			echo '{"e":"0","d":"fail"}';
		}
	}

	function billing_info_u(){
		if($this->input->post('bi_id')){
			$member_id = $this->session->userdata('member_id');
			$bi_id = $this->input->post('bi_id');
			$bi_bank = $this->input->post('bi_bank');
            $bi_payment_type = $this->input->post('bi_payment_type');
			$bi_acct_name = $this->input->post('bi_acct_name');
			$bi_acct_no = $this->input->post('bi_acct_no');
			$bi_def = $this->input->post('bi_def');
            $bi_user_account = "";
			$data = array(
					'member_id' => $member_id,
                    'payment_type' => $bi_payment_type,
					'ibi' => $bi_id,
					'bank_id' => $bi_bank,
					'bank_account_name' => $bi_acct_name,
					'bank_account_number' => $bi_acct_no,
					'is_default' => $bi_def,
                    'user_account' => $bi_user_account,
			);
			if($this->memberpage_model->isBankAccountUnique($data)){
                $this->memberpage_model->billing_info_update($data);
                $return = '{"e":"1","d":"success"}';
            }
            else{
                $return = '{"e":"0","d":"duplicate"}';
            }
		}
		else{
            $return = '{"e":"0","d":"fail"}';
		}
        echo $return;
	}

	function billing_info_d(){
		if($this->input->post('bi_id')){
			$member_id = $this->session->userdata('member_id');
			$bi_id = $this->input->post('bi_id');
            $member_id = $this->session->userdata('member_id');
			$data = array(
					'member_id' => $member_id,
					'ibi' => $bi_id
			);
			$this->memberpage_model->billing_info_delete($data);
		}
	}
	
	function billing_info_f(){
	   
		if($this->input->post('bi_id')){
			$member_id = $this->session->userdata('member_id');
			$bi_id = $this->input->post('bi_id');
            $member_id = $this->session->userdata('member_id');
			$data = array(
					'member_id' => $member_id,
					'ibi' => $bi_id
			);
			$this->memberpage_model->billing_info_default($data);
		}
	}	
	
	# FUNCTION USED BY ITEM LIST PAGING AJAX REQUESTS
	function getMoreUserItems($who = "")
	{
		$itemPerPage = 10;
		
		if($who == "vendor"){
			$activeView = 'vendor_activeproduct_view';
			$member_id = $this->input->get('mid');
		}else{
			$activeView = 'memberpage_activeproduct_view';
			$deletedView = 'memberpage_deletedproduct_view';
			$member_id = $this->session->userdata('member_id');
		}
		
		$deleteStatus = intval($this->input->get('s'));
		$start = intval($this->input->get('p'));
		$rawnf = trim((string)$this->input->get('nf'));
		$nf = '%' . $rawnf . '%'; #name_filter / searched name
		$of = (int)$this->input->get('of'); #order filter
		$osf = (int)$this->input->get('osf'); #order sequence filter
		
		switch($of){
			case 1:
				$myof = 'p.lastmodifieddate';
				break;
			case 2:
				$myof = 'p.name';
				break;
			case 3:
				$myof = 'p.price';
				break;
			case 4:
				$myof = 'availability';
				break;
			case 5:
				$myof = 'sold';
				break;
			default:
				$myof = 'p.lastmodifieddate';
		}
		
		switch($osf){
			case 1:
				$myosf = 'DESC';
				break;
			case 2:
				$myosf = 'ASC';
				break;
			default:
				$myosf = 'DESC';
		}
		
		if( $this->input->get('c') == 'count' ){
			$jsonData['count'] = 0;
			if ( $rawnf !== '' ){
				$jsonData['count'] = $this->memberpage_model->getUserItemSearchCount($member_id,$nf,$deleteStatus);
			}
		}
		
		$key = $deleteStatus === 0 ? 'active_products' : 'deleted_products';
		$data[$key] = $this->memberpage_model->getUserItems($member_id, $deleteStatus, $start, $nf,$myof,$myosf, $itemPerPage);
		
		if($deleteStatus === 0){ #if active items
			$jsonData['html'] = $this->load->view('pages/user/'.$activeView, $data, true);
		}else if($deleteStatus === 1){
			$jsonData['html'] = $this->load->view('pages/user/'.$deletedView, $data, true);
		}
		
		echo json_encode($jsonData);
	}
	
	#FUNCTION USED BY TRANSACTIONS AJAX PAGING REQUESTS
	function getMoreTransactions()
	{
		$itemPerPage = 10;
		
		$completeStatus = intval($this->input->get('s'));
		$k = (string)$this->input->get('k');
		$start = intval($this->input->get('p'));
		$member_id = $this->session->userdata('member_id');
		
		$rawnf = trim((string)$this->input->get('nf'));
		$nf = '%' . $rawnf . '%'; #Transaction Invoice Filter
		$of = (int)$this->input->get('of'); #Payment Filter
		$osf = (int)$this->input->get('osf'); #order sequence filter (ASC or DESC)
		
		switch($of){
			case 0:
				$myof = '1,2,3,5';
				break;
			case 1:
				$myof = '1';
				break;
			case 2:
				$myof = '2';
				break;
			case 3:
				$myof = '3';
				break;
			case 5:
				$myof = '5';
				break;
			default:
				$myof = '1,2,3,5';
		}
		
		switch($osf){
			case 1:
				$myosf = 'DESC';
				break;
			case 2:
				$myosf = 'ASC';
				break;
			default:
				$myosf = 'ASC';
		}
		
		switch($k){
			case 'buy':
				$data['transaction']['buy'] = $this->memberpage_model->getBuyTransactionDetails($member_id,$completeStatus,$start,$nf,$myof,$myosf);;
				$view = 'memberpage_tx_buy_view';
				$querySelect = 'buy';
				break;
			case 'sell':				
				$data['transaction']['sell'] = $this->memberpage_model->getSellTransactionDetails($member_id,$completeStatus,$start,$nf,$myof,$myosf);;
				$view = 'memberpage_tx_sell_view';
				$querySelect = 'sell';
				break;
			case 'cbuy':
				$data['transaction']['complete']['buy'] = $this->memberpage_model->getBuyTransactionDetails($member_id,$completeStatus,$start,$nf,$myof,$myosf);;
				$view = 'memberpage_tx_cbuy_view';
				$querySelect = 'buy';
				break;
			case 'csell':
				$data['transaction']['complete']['sell'] = $this->memberpage_model->getSellTransactionDetails($member_id,$completeStatus,$start,$nf,$myof,$myosf);;
				$view = 'memberpage_tx_csell_view';
				$querySelect = 'sell';
				break;
		}
		
		if( $this->input->get('c') == 'count' ){
			$jsonData['count'] = $this->memberpage_model->getFilteredTransactionCount($member_id, $completeStatus, $nf, $myof, $myosf, $querySelect);
		}
		
		$jsonData['html'] = $this->load->view('pages/user/'.$view, $data, true);
		
		echo json_encode($jsonData);
	}
	
}

/* End of file memberpage.php */
/* Location: ./application/controllers/memberpage.php */
