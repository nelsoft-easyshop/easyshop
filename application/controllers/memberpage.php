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
		if(!$this->session->userdata('member_id'))
		    redirect(base_url().'home', 'refresh');
		$data = $this->fill_view();
		
        $this->load->view('templates/header_topnavsolo', $data); 
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
				'contactno' => html_escape($this->input->post('mobile')),
				'email' => html_escape($this->input->post('email'))
			);
			
			$check = $this->register_model->check_contactinfo($checkdata);
			if($check['mobile'] !== 0 || $check['email'] !== 0){
				echo json_encode($check);
				return;
			}
		
			$uid = $this->session->userdata('member_id'); 
            $postdata = array( 
                'fullname' => html_escape($this->input->post('fullname')), 
                'nickname' => html_escape($this->input->post('nickname')), 
                'gender' => $this->input->post('gender'),
                'birthday' => $this->input->post('dateofbirth'),
				'contactno' => html_escape($this->input->post('mobile')),
				'email' => html_escape($this->input->post('email'))
            ); 
			
			if($postdata['email'] === $this->input->post('email_orig'))
				$postdata['is_email_verify'] = $this->input->post('is_email_verify');
			else
				$postdata['is_email_verify'] = 0;
				
			if($postdata['contactno'] === $this->input->post('mobile_orig'))
				$postdata['is_contactno_verify'] = $this->input->post('is_contactno_verify');
			else
				$postdata['is_contactno_verify'] = 0;
			
            $this->memberpage_model->edit_member_by_id($uid, $postdata); 
			
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
				'city' => $this->input->post('city'),
				'province' => $this->input->post('province'),
				'address' => $this->input->post('address'),
				'country' => $this->input->post('country'),
				'addresstype' => $this->input->post('addresstype'),
				'lat' => $this->input->post('temp_lat'),
				'lng' => $this->input->post('temp_lng')
			); 
			
			$temp = array(
				'city_orig' => $this->input->post('city_orig'),
				'province_orig' => $this->input->post('province_orig'),
				'address_orig' => $this->input->post('address_orig'),
				'map_lat' => $this->input->post('map_lat'),
				'map_lng' => $this->input->post('map_lng')
			);
			
			if( ( ($temp['city_orig'] != $postdata['city']) || ($temp['province_orig'] != $postdata['province']) || ($temp['address_orig'] != $postdata['address']) )  
				&& ($temp['map_lat'] == $postdata['lat'] && $temp['map_lng'] == $postdata['lng']) ) {
				$postdata['lat'] = 0;
				$postdata['lng'] = 0;
			}
			
			$uid = $this->session->userdata('member_id');
			$this->memberpage_model->edit_address_by_id($uid, $postdata);	
			$data = $this->memberpage_model->get_member_by_id($uid);
			
			$this->output->set_output(json_encode($data));
		}		
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
				$this->memberpage_model->edit_school_by_id($uid, $postdata);
			}
		} 
		$uid = $this->session->userdata('member_id'); 
		$data = $this->memberpage_model->get_school_by_id($uid);
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
		$user_products = $this->memberpage_model->getUserItems($uid);
		$data = array( 
                'title' => 'Easyshop.ph - Member Profile', 
				'image_profile' => $this->memberpage_model->get_image($uid), 
				'active_products' => $user_products['active'],
				'deleted_products' => $user_products['deleted'],
                ); 
		$data = array_merge($data, $this->fill_header());
		$data = array_merge($data, $this->memberpage_model->getLocationLookup());
		$data = array_merge($data,$this->memberpage_model->get_member_by_id($uid));
        $data = array_merge($data,$this->memberpage_model->get_work_by_id($uid));
		$data =  array_merge($data,$this->memberpage_model->get_school_by_id($uid));
		$data['bill'] =  $this->memberpage_model->get_billing_info($uid);
		$data['bank'] = $this->memberpage_model->get_bank('', 'all');			
		$data['transaction'] = $this->memberpage_model->getTransactionDetails($uid);
		$data['allfeedbacks'] = $this->memberpage_model->getFeedback($uid);
		
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
		redirect('memberpage'); 
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
			$postdata = array(
				'consignee' => $this->input->post('consignee'),
				'mobile' => $this->input->post('c_mobile'),
				'telephone' => $this->input->post('c_telephone'),
				'city' => $this->input->post('c_city'),
				'province' => $this->input->post('c_province'),
				'address' => $this->input->post('c_address'),
				'country' => $this->input->post('c_country'),
				'lat' => $this->input->post('map_lat'),
				'lng' => $this->input->post('map_lng')
			);
			
			if($this->input->post('c_def_address'))
			{
				$postdata['default_add'] = $this->input->post('c_def_address');
			}
			else
			{
				$postdata['default_add'] = "off";
			}
			
			$this->memberpage_model->edit_consignee_address_by_id($uid, $postdata);
			$data['default_add'] = $postdata['default_add'];
			$data = array_merge($data,$this->memberpage_model->get_member_by_id($uid));	
			//print_r($data);
			$this->output->set_output(json_encode($data));
		}
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
				$this->memberpage_model->edit_work_by_id($uid, $postdata);
			}
			$uid = $this->session->userdata('member_id');
			$data = $this->memberpage_model->get_work_by_id($uid);
			echo json_encode($data);
		}
	}
	
	/*****************	TRANSACTION CONTROLLER	*******************/
	
	/*
	 *	Function to add feedback to USER for every transaction made
	 */
	function addFeedback(){
		if($this->input->post('order_id') && $this->input->post('feedback-field') && $this->form_validation->run('add_feedback_transaction')){
			$data = array(
				'uid' => $this->session->userdata('member_id'),
				'for_memberid' => $this->input->post('for_memberid'),
				'feedb_msg' => html_escape($this->input->post('feedback-field')),
				'feedb_kind' => $this->input->post('feedb_kind'),
				'order_id' => $this->input->post('order_id'),
				'rating1' => $this->input->post('rating1'),
				'rating2' => $this->input->post('rating2'),
				'rating3' => $this->input->post('rating3')
			);
			$result = $this->memberpage_model->addFeedback($data);
			
			echo $result?1:0;
		}
		else
			echo 0;
	}
	
	/*
	 *	Function to handle payment transfer.
	 *	Forward to seller or return to user.
	 */
	function transactionResponse(){
		if($this->input->post('buyer_response')){
			$data['order_product_id'] = $this->input->post('buyer_response');
			$data['status'] = 1;
		}
		else if($this->input->post('seller_response')){
			$data['order_product_id'] = $this->input->post('seller_response');
			$data['status'] = 2;
		}
		$data['transaction_num'] = $this->input->post('transaction_num');
		$result = $this->payment_model->updateTransactionStatus($data);
		
		echo $result ? 1:'An error was encountered while submitting your request';
	}
	
	
	/***	VENDOR DASHBOARD CONTROLLER	***/
	/*** 	memberpage/vendor/username	***/
	function vendor($selleruname){
		$vendordetails = $this->memberpage_model->getVendorDetails($selleruname);
		$data['title'] = 'Vendor Profile | Easyshop.ph';
		$data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header_topnavsolo', $data); 
		if($vendordetails){
			$sellerid = $vendordetails['id_member'];
			$user_products = $this->memberpage_model->getUserItems($sellerid);
			$data = array_merge($data,array( 
					'vendordetails' => $vendordetails,
					'image_profile' => $this->memberpage_model->get_Image($sellerid),
					'active_products' => $user_products['active'],
					'deleted_products' => $user_products['deleted'],
					)); 
			$data['transaction'] = $this->memberpage_model->getTransactionDetails($sellerid);
			$data['allfeedbacks'] = $this->memberpage_model->getFeedback($sellerid);
			$this->load->view('pages/user/vendor_view', $data); 
		}
		else{
			$this->load->view('pages/user/user_error');
		}
		$this->load->view('templates/footer'); 
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
###    di ko mapaandar ulit yung form_validation
###		if(($this->input->post('bi_acct_no')) && ($this->form_validation->run('billing_info'))){
		
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
			$this->memberpage_model->billing_info($data);
			$get_info = $this->memberpage_model->get_billing_info($member_id);
			echo json_encode($get_info);		
		
		}
	}
	
	function billing_info_u(){
					
		if($this->input->post('bi_id')){
			$bi_id = $this->input->post('bi_id');
			$bi_bank = $this->input->post('bi_bank');
			$bi_acct_name = $this->input->post('bi_acct_name');	  
			$bi_acct_no = $this->input->post('bi_acct_no');
	
			$data = array(
					'ibi' => $bi_id,
					'bank_id' => $bi_bank,
					'bank_account_name' => $bi_acct_name,
					'bank_account_number' => $bi_acct_no				
			);
			$this->memberpage_model->billing_info_update($data);		
		}
	}	
	
	function billing_info_d(){
					
		if($this->input->post('bi_id')){
			$bi_id = $this->input->post('bi_id');	
			$data = array(
					'ibi' => $bi_id			
			);
			$this->memberpage_model->billing_info_delete($data);		
		}
	}		
	
} 
  
/* End of file memberpage.php */
/* Location: ./application/controllers/memberpage.php */
