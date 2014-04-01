<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	
class Landingpage extends MY_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->load->model("register_model");
		$this->load->library('encrypt');
		$this->form_validation->set_error_delimiters('', '');
	}
	
	function index()
	{
		$data = array(
			'title' => 'Easyshop.ph - Welcome to Easyshop.ph'
		);
		$this->load->view('pages/landingpage', $data);
	}
	
	/*
	 *	Subscription Handler
	 */
	public function subscribe()
	{
		if($this->input->post('subscribe_btn') && $this->form_validation->run('subscription_form')){
			$email = $this->input->post('subscribe_email');
			$result = $this->register_model->subscribe($email);
			
			echo $result ? 1 : 0;
		}
	}
	
	/*
	 *	Registration Handler
	 */
	public function signup()
	{
		if(($this->input->post('register_form1'))&&($this->form_validation->run('landing_form'))){
			$data['username'] = html_escape($this->input->post('username'));
			$data['password'] = html_escape($this->input->post('password'));
			$data['email'] = $this->input->post('email');
			
			$data['member_id'] = $this->register_model->signupMember($data)['id_member'];
			$result = $this->sendVerificationCode($data);
			
			if($result){
				echo 1;
			}
			else{
				echo 0;
			}
		}
	}
	
	/*
	 *	Send Email Verification and store to es_verifcode
	 */
	public function sendVerificationCode($data)
	{
		//GENERATE MOBILE CONFIRMATION CODE
		$temp['mobilecode'] = $this->register_model->rand_alphanumeric(6);
		//GENERATE HASH FOR EMAIL VERIFICATION
		$temp['emailcode'] = sha1($this->session->userdata('session_id').time());
		$temp['member_id'] = $data['member_id'];
		
		//Send confirmation email
		$status = $this->register_model->send_email_msg($data['email'], $data['username'], $temp['emailcode']);
		//$status = 'success';
		
		//Determine if count limit for verification will be increased
		if($status === 'success'){
			$temp['email'] = 1;
		}else{
			$temp['email'] = 0;
		}
		//Store verification details and increase limit count when necessary
		$result = $this->register_model->store_verifcode($temp);
		
		if($status==='success' && $result){
			return true;
		}
		else{
			return false;
		}
	}
	
	/*
	 *	Email verification (link click in email) handler
	 */
	function email_verification(){
	
		$this->load->library('encrypt');

		//Decrypt and re-assign data
		$enc = html_escape($this->input->get('h'));
		$enc = str_replace(" ", "+", $enc);
		$decrypted = $this->encrypt->decode($enc);
		$getdata = explode("|", $decrypted);
		
		$email = $getdata[0];
		$username = $getdata[1];
		$hash = $getdata[2];

		$data = array(
			'title' => 'Easyshop.ph - Email Verification',
			'member_username' => $username,
			'email' => $email
		);
		$data = array_merge($data, $this->fill_header());

		$member_id = $this->register_model->get_memberid($username)['id_member'];

		if($member_id === 0){
			$this->load->view('templates/header_topnavsolo', $data);
			$this->load->view('pages/user/err_email_verif', $data);
			$this->load->view('templates/footer');
			return;
		}

		$data_val = $this->register_model->get_verifcode($member_id);


		if($email === $data_val['email'] && $hash === $data_val['emailcode'] && $username === $data_val['username'])
		{
		
			if($data_val['is_email_verify'] == 1){
				$data['verification_msg'] = $this->lang->line('expired_email_verification');
				$this->load->view('templates/header_topnavsolo', $data);
				$this->load->view('pages/user/register_form3_view', $data);
				$this->load->view('templates/footer');
				return;
			}

			$temp = array(
				'is_email_verify' => 1,
				'member_id' => $member_id
			);

			$this->register_model->update_verification_status($temp);
			
			$data['verification_msg'] = $this->lang->line('success_email_verification');
			$this->load->view('templates/header_topnavsolo', $data);
			$this->load->view('pages/user/register_form3_view', $data);
			$this->load->view('templates/footer');
		}
		else{
			$this->load->view('templates/header_topnavsolo', $data);
			$this->load->view('pages/user/err_email_verif', $data);
			$this->load->view('templates/footer');
		}
	}
	
	public function username_check()
	{
		if($this->input->post('username')){
			//$username = htmlspecialchars($this->input->post('username'));
			$username = $this->input->post('username');
			if($this->register_model->get_member_by_username($username))
				echo 0;
			else
				echo 1;
		}
	}
	
	public function email_check()
	{
		if($this->input->post('email')){
			$email = $this->input->post('email');
			if($this->register_model->checkEmailIfExists($email)){
				echo 0;
			}
			else {
				echo 1;
			}
		}
	}

	public function external_callbacks( $postdata, $param )
	{
		 $param_values = explode( ',', $param ); 
		 $model = $param_values[0];
		 $this->load->model( $model );
		 $method = $param_values[1];
		 
		 if( count( $param_values ) > 2 )
		 {
			  array_shift( $param_values );
			  array_shift( $param_values );
			  $argument = $param_values;
		 }
	
		 if( isset( $argument ) )
		 {
			if($method == 'checkifrequired'){
				$thisdata = trim(html_escape($postdata));
				$checkwith = trim(html_escape($this->input->post($argument[0])));
				$callback_result = $this->$model->$method($thisdata, $checkwith);
			}
			else
				$callback_result = $this->$model->$method( $postdata, $argument );
		 }
		 else
		 {
			if($method == 'validate_captcha')
				$callback_result = $this->$model->$method( $postdata, $this->session->userdata('captcha_word'));
			else	 
				$callback_result = $this->$model->$method( $postdata );
		 }
		 return $callback_result;
	}

}// close class



?>