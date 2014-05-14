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
			$data['email'] = $this->input->post('subscribe_email');
			$result = $this->register_model->subscribe($data['email']);
			
			// Send notification email to user
			$this->register_model->sendNotification($data, 'subscribe');
			
			echo $result ? 1 : 0;
		}
	}
	
	/*
	 *	Registration Handler
	 */
	public function signup()
	{
		$serverResponse = array(
			'result' => 0,
			'error' => array()
		);
		
		if(($this->input->post('register_form1'))&&($this->form_validation->run('landing_form'))){
			
			$data['username'] = $this->input->post('username');
			$data['password'] = $this->input->post('password');
			$data['email'] = $this->input->post('email');
			$data['mobile'] = $this->input->post('mobile');
			
			$registrationFlag = false;
			
			// REGISTER MEMBER IN DATABASE
			$data['member_id'] = $this->register_model->signupMember_landingpage($data)['id_member'];
			
			//GENERATE MOBILE CONFIRMATION CODE
			$temp['mobilecode'] = $this->register_model->rand_alphanumeric(6);
			//GENERATE HASH FOR EMAIL VERIFICATION
			$temp['emailcode'] = sha1($this->session->userdata('session_id').time());
			$temp['member_id'] = $data['member_id'];
			
			// Send notification email to user, max try = 3
			$data['emailcode'] = $temp['emailcode'];
			$emailCount = 0;
			do{
				$emailResult = $this->register_model->sendNotification($data, 'signup');
				$emailCount++;
			}while(!$emailResult && $emailCount < 3);
			
			$temp['email'] = $emailResult ? 1 : 0;
			
			//Store verification details and increase limit count when necessary
			$result = $this->register_model->store_verifcode($temp);
			
			// If verification code failed to enter database
			if(!$result){
				array_push($serverResponse['error'], 'Database verifcode error <br>');
			}
			// If registration failed
			if( is_null($data['member_id']) || $data['member_id'] == 0 || $data['member_id'] == ''){
				array_push($serverResponse['error'], 'Database registration failure <br>');
				$registrationFlag = false;
			}else{
				$registrationFlag = true;
			}
			if(!$emailResult){
				array_push($serverResponse['error'], 'Failed to send verification email. Please verify in user page upon logging in.');
			}
			
			if( $registrationFlag && $result ){
				$serverResponse['result'] = 1;
			}
			else{
				$serverResponse['result'] = 0;
			}
			
		}
		else{
			if( !($this->input->post('register_form1')) ){
				array_push($serverResponse['error'], 'Failed to submit form. <br>');
			}
			if( !($this->form_validation->run('landing_form')) ){
				array_push($serverResponse['error'], 'Failed to validate form. <br>');
			}
		}
		
		echo json_encode($serverResponse);
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
	
	public function mobile_check()
	{
		if($this->input->post('mobile')){
			$mobile = $this->input->post('mobile');
			if($this->register_model->checkMobileIfExists($mobile)){
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

    public function success($action = ''){
        $data['title'] = 'Easyshop.ph - Thank You';
        $referrer = ($this->input->post('referrer'))?$this->input->post('referrer'):'';
        if($referrer != 'landingpage'){
		    $data['title'] = 'Page not found';
            $data = array_merge($data,$this->fill_header());
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/general_error'); 		
            $this->load->view('templates/footer_full');
        }
        else{
            if($action === 'register'){
            	$data['content'] = 'You have successfully registered!';
                // $data['content'] = 'img_success_register.png';
            }
            else if($action === 'subscribe'){
                $data['content'] = 'You have successfully Subscribed!';
                // $data['content'] = 'img_success_subscribe.png';
            }
            else{
                redirect('', 'refresh');  //redirect to index page
            }
            $this->load->view('pages/landingpage_success', $data);
        }
    } 
    
}// close class

