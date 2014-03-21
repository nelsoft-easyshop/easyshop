<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Register extends MY_Controller
{
	var $vals;
	function __construct() 
	{
		parent::__construct();
		$this->load->model("register_model");
		$this->load->library('encrypt');

        $base_url = base_url();
		$img_url = $base_url.'assets/captcha/';
		$this->vals = array(
            'word_length'   => 6,
			'img_path' => './assets/captcha/',
			'img_url' => $img_url,
			'img_width' => '140',
            'img_height' => '30',
			'font_path' => './assets/fonts/monofont.ttf',
			'expiration' => 7200
		);	
		$this->form_validation->set_error_delimiters('', '');
	}
	
	function index()
	{
		$data = array(
				'title' => 'Easyshop.ph - Account Registration',
			);		
		$data = array_merge($data, $this->fill_header());
		$data['reg_username'] = '';
		$view = 'register_form1_view';
		
		// Registration form page 1
		/*if(($this->input->post('register_page1'))&&($this->form_validation->run('register_form1')))
		{
			$this->session->set_userdata('register_username', $this->input->post('username'));
			$this->session->set_userdata('register_password', $this->input->post('password'));
			$this->session->unset_userdata('captcha_word');
			$temp['reg_username'] = '';
			$view = 'register_form2_view';
		}*/
		if(($this->input->post('register_page1'))&&($this->form_validation->run('register_form1')))
		{
			$this->session->unset_userdata('captcha_word');
			
			$data['username'] = html_escape($this->input->post('username'));
			$data['password'] = html_escape($this->input->post('password'));
			$data['email'] = $this->input->post('email');
			
			$this->sendVerificationCode($data);
			$view = 'register_form3_view';
			$data['member_username'] = $data['username'];
			$data['verification_msg'] = $this->lang->line('success_registration');
		}
		else
		{
			$cap = create_captcha($this->vals);	
			$image = $cap['image'];
			$this->session->set_userdata('captcha_word', $cap['word']);
			$data['image'] = $image;
			$data['reg_username'] = $this->input->post('username');
		}
		$this->load->view('templates/header_plain', $data);
		$this->load->view("pages/user/".$view, $data);
		$this->load->view('templates/footer');
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
	
	function recreate_captcha()
	{
		$cap = create_captcha($this->vals);
		$this->session->set_userdata('captcha_word', $cap['word']);
		$base_url = base_url();
		$filename = $base_url.'assets/captcha/'.$cap['time'].'.jpg';
		echo $filename;
	}
		
	function sendVerificationCode($data)
	{
		//GENERATE MOBILE CONFIRMATION CODE
		$temp['mobilecode'] = $this->register_model->rand_alphanumeric(6);
		//GENERATE HASH FOR EMAIL VERIFICATION
		$temp['emailcode'] = sha1($this->session->userdata('session_id'));
		
		$temp['member_id'] = $this->register_model->signupMember($data)['id_member'];
		
		$status = $this->register_model->send_email_msg($data['email'], $data['username'], $temp['emailcode']);
		//$status = 'success';
		
		if($status === 'success'){
			$temp['email'] = 1;
		}else{
			$temp['email'] = 0;
		}
		
		$this->register_model->store_verifcode($temp);
	}
	
	/*function send_verification_code() {
		if($this->input->post('register_form2_a_btn')){
			if($this->form_validation->run('register_form2_a')){
				$data_val = array(
					'register_username' => $this->session->userdata('register_username'),
					'register_password' => $this->session->userdata('register_password'),
					'register_mobile' => $this->input->post('register_mobile'),
					'register_region' => $this->input->post('register_region'),
					'register_email' => $this->input->post('register_email')
				);
			
				//INITIALIZE VARIABLES
				$username = $data_val['register_username'];
				$data = array('mobilestat' => "", 'emailstat' => "");
				//GENERATE MOBILE CONFIRMATION CODE
				$confirmation_code = $this->register_model->rand_alphanumeric(6);
				//GENERATE HASH FOR EMAIL VERIFICATION
				$hash = sha1($this->session->userdata('session_id'));
				
				//Check if email or mobile already used by other users
				//User will not be registered until valid contact info is/are provided
				$checkdata = array(
					//'member_id' => 0,
					'contactno' => html_escape($this->input->post('register_mobile')),
					'email' => html_escape($this->input->post('register_email'))
				);
				
				if($this->session->userdata('temp_memberid')){
					$checkdata['member_id'] = $this->session->userdata('temp_memberid');
				}
				else{
					$checkdata['member_id'] = 0;
				}

				
				$check = $this->register_model->check_contactinfo($checkdata);
				if($check['mobile'] !== 0 || $check['email'] !== 0){
					$data['mobilestat'] = $check['mobile'] == 0 ? '' : 'exists';
					$data['emailstat'] = $check['email'] == 0 ? '' : 'exists';
					echo json_encode($data);
					return;
				}

				//REGISTER MEMBER IN DATABASE
				$member_id = $this->register_model->signup_member($data_val)['id_member'];
				$this->session->set_userdata('temp_memberid', $member_id);
				
				$temp = array(
					'member_id' => $member_id,
					'mobilecode' => $confirmation_code,
					'emailcode' => $hash,
					'mobile' => 0,
					'email' => 0
				);

				$stat = $this->register_model->get_verifcode($member_id);

				if($this->input->post('register_mobile')){
					if($stat['mobilecount'] < 4 || $stat['time'] > 30 ){
						$data['mobile'] = $this->input->post('register_mobile');
						$data['mobilestat'] = $this->register_model->send_mobile_msg($username, $data['mobile'], $confirmation_code);
						
						if($data['mobilestat'] === 'success'){
							$this->session->set_userdata('confirmation_code', $confirmation_code);
							//$this->session->set_userdata('temp_memberid' , $member_id);
							$temp['mobile'] = 1;
						}
					}
					else
						$data['mobilestat'] = 'exceed';
				}
				
				if($this->input->post('register_email') ){
					if($stat['emailcount'] < 4 || $stat['time'] > 30 ){
						$data['email'] = $this->input->post('register_email');
						$data['emailstat'] = $this->register_model->send_email_msg($data['email'], $username, $hash);

						if($data['emailstat'] === 'success')
							$temp['email'] = 1;
					}
					else
						$data['emailstat'] = 'exceed';
				}

				//STORE VERIFICATION CODE
				$this->register_model->store_verifcode($temp);
				
				echo json_encode($data);

			}//close if for form check
			else{
				echo 0;
			}
		}//close if for button submit
		else{
			redirect(base_url().'home');
		}
	}
	

	//Function used to verify modal form
	function mobile_verification()
	{
		if(($this->input->post('register_form2_b_btn')) && ($this->form_validation->run('register_form2_b'))){
			$member_id = $this->session->userdata('temp_memberid');

			$user_confirmation_code = $this->input->post('verification_code');
			$data = $this->register_model->get_verifcode($member_id);

			if(($user_confirmation_code == $this->session->userdata('confirmation_code')) && ($this->session->userdata('confirmation_code') == $data['mobilecode']))
			{
				$data['is_contactno_verify'] = 1;
				$this->register_model->update_verification_status($data);
				$this->session->unset_userdata('confirmation_code');
				$this->session->unset_userdata('temp_memberid');
				$this->session->set_userdata('temp_memberuname', $data['username']);
				echo true;
			}
			else 
				echo false;
		}
	}

	function success_mobile_verification(){
		if($this->input->post('mobile_verify') === 'submit_mobilenum'){
		
			//UNSET REGISTER SESSION VARIABLES
			$this->session->unset_userdata('register_username');
			$this->session->unset_userdata('register_password');
			
			$data = array(
				'verification_msg' => $this->lang->line('success_mobile_verification'),
				'member_username' => $this->session->userdata('temp_memberuname'),
				'logged_in' => false
			);

			$this->session->unset_userdata('temp_memberuname');
			echo $this->load->view('pages/user/register_form3_view', $data, true);
		}
		else{
			redirect(base_url().'register');
		}
	}
	*/
	
	function email_verification(){
	
		$this->load->library('encrypt');

		//UNSET REGISTER SESSION VARIABLES
		$this->session->unset_userdata('register_username');
		$this->session->unset_userdata('register_password');
		$this->session->unset_userdata('temp_memberid');

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


	function username_check()
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
	
	function email_check()
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
	
	function changepass(){
		$data = array(
			'title' => 'Change Password | Easyshop.com',
		);
		$data = array_merge($data, $this->fill_header());
		
		$this->load->view('templates/header_plain', $data);
		$temp['toggle_view'] = "1";
        $result = true;

		if(($this->input->post('changepass_btn')) && ($this->form_validation->run('changepass'))){  
			$data = array(
				'username' => $this->input->post('wsx'),
				'cur_password' => $this->input->post('cur_password'),
				'password' => $this->input->post('password')
			);
            $result = $this->register_model->changepass($data);       
			if($result){
                $temp['toggle_view'] = "";
            }
			$this->session->unset_userdata('user_cur_loc');
		}
        $temp['result'] = $result;
		$this->load->view('pages/user/changepassword', $temp);
		$this->load->view('templates/footer');		
		
	}
	
	function pass_check(){
		
		if(($this->input->post('pass'))){
		$username = $this->input->post('username');
		$pass = $this->input->post('pass');	
		$dataval = array('login_username' => $username, 'login_password' => $pass);
		
		$row = $this->user_model->verify_member($dataval);

			if ($row['o_success'] >= 1) {
				echo 0;
			}else
				echo 1;  
		
		}
		
	}	

	function unload(){
		if($this->session->userdata('register_username')){
			$this->session->unset_userdata('captcha_word');
			$this->session->unset_userdata('temp_memberid');
			$this->session->unset_userdata('temp_memberuname');
			$this->session->unset_userdata('register_username');
			$this->session->unset_userdata('register_password');	
		}
	}
	
	
}


  
/* End of file register.php */
/* Location: ./application/controllers/register.php */