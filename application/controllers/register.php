<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	
class Register extends MY_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->load->model("register_model");
		$this->load->library('encrypt');
        $this->load->library('session');
		$this->form_validation->set_error_delimiters('', '');
	}
	
    public function index()
    {
        $url = 'landingpage';
        $is_promo = FALSE;
        if (strpos($this->session->userdata('uri_string'), 'ScratchCard') !== FALSE) {
            $code = trim($this->session->userdata('uri_string'), 'promo/ScratchCard/claimScratchCardPrize/claim/');
            $url = 'promo/ScratchCard/claimScratchCardPrize/claim/'.$code;
            $is_promo = TRUE;
         }
        $data = array(
            'title' => 'Easyshop.ph - Welcome to Easyshop.ph',
            'metadescription' => 'Register now at Easyshop.ph to start your buying and selling experience',
            'redirect_url' => $url,
            'is_promo' =>$is_promo
        );
        $data = array_merge($data, $this->fill_header());
        $this->load->view('pages/user/register', $data);
    }


    /**
     *   Registration Handler
     */
    public function signup()
    {
        $serverResponse = array(
            'result' => 0,
            'error' => array()
        );

        if (($this->input->post('register_form1'))&&($this->form_validation->run('landing_form'))) {
            $data['fullname'] = $this->input->post('fullname');
            $data['username'] = $this->input->post('username');
            $data['password'] = $this->input->post('password');
            $data['email'] = $this->input->post('email');
            $data['mobile'] = substr($this->input->post('mobile'),1);

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
                array_push($serverResponse['error'], 'Failed to submit form.');
            }
            if( !($this->form_validation->run('landing_form')) ){
                array_push($serverResponse['error'], 'Failed to validate form.');
            }
        }

        echo json_encode($serverResponse);
    }
	
	
	public function username_check()
	{
		if($this->input->post('username')){
			$username = $this->input->post('username');
			if($this->register_model->validate_username($username))
				echo 1;
			else
				echo 0;
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

    public function success($action = '')
    {
        $data['title'] = 'Easyshop.ph - Thank You';
        $referrer = ($this->input->post('referrer'))?$this->input->post('referrer'):'';
        if(!($referrer)){
            $data['title'] = 'Page not found';
            $data = array_merge($data,$this->fill_header());
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/general_error');
            $this->load->view('templates/footer_full');
        }
        else{
            if ($referrer === 'landingpage') {
                $data['content'] = 'You have successfully registered!';
                $data['sub_content'] = 'You have successfully registered with Easyshop.ph. Verify your e-mail to begin selling your products online.';
                $this->load->view('pages/user/register_subscribe_success', $data);
            }
            else {
                $this->session->set_userdata('uri_string', $referrer);
                redirect('/login', 'refresh');
            }
        }
    } 
    
    /*
     *	Subscription Handler
     */
	public function subscribe()
	{
		if($this->input->post('subscribe_btn') && $this->form_validation->run('subscription_form')){
            $this->load->model('register_model');
			$data['email'] = $this->input->post('subscribe_email');
            $result = $this->register_model->subscribe($data['email']);
			// Send notification email to user 
			if($result){		
              $this->register_model->sendNotification($data, 'subscribe');
              $data['title'] = 'Successful Subscription | Easyshop.ph';
              $data['content'] = 'You have successfully Subscribed!';
              $data['sub_content'] =  'Thank you for choosing to keep in touch with Easyshop.ph. Expect to hear many things from us soon.';
              $this->load->view('pages/user/register_subscribe_success', $data);
            }else{
                redirect('home','refresh');
            }
		}else{
            redirect('home','refresh');
        }

	}
    
    /**
     * Renders change password view
     *
     */
    public function changepass()
    {
        $data = array(
            'title' => 'Change Password | Easyshop.com',
            'render_searchbar' => false,
        );
        $data = array_merge($data, $this->fill_header());
        
        $this->load->view('templates/header', $data);
        $temp['toggle_view'] = "1";
        $temp['err'] = "";
        $result = true;

        $username = $this->input->post('wsx');
        $cur_password = $this->input->post('cur_password');
        $password = $this->input->post('password');			
        
        if(($username) && ($this->form_validation->run('changepass'))){  
        
            $dataval = array('login_username' => $username, 'login_password' => $cur_password);
            $row = $this->user_model->verify_member($dataval);

            if ($row['o_success'] >= 1){
                $data = array(
                    'username' => $username,
                    'cur_password' => $cur_password,
                    'password' => $password
                );
                
                $result = $this->register_model->changepass($data);       
                if($result){
                    $temp['toggle_view'] = "";
                }
            }else{
                $temp['toggle_view'] = "1";
                $temp['err'] = "69";
            }
        }
        
        $temp['result'] = $result;
        $this->load->view('pages/user/changepassword', $temp);
        $this->load->view('templates/footer');		
		
	}
    
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
			'email' => $email,
            'render_logo' => false,
            'render_searchbar' => false,
		);
		$data = array_merge($data, $this->fill_header());

		$member_id = $this->register_model->get_memberid($username)['id_member'];

		if($member_id === 0){
			$this->load->view('templates/header', $data);
			$this->load->view('pages/user/err_email_verif', $data);
			$this->load->view('templates/footer_full');
			return;
		}

		$data_val = $this->register_model->get_verifcode($member_id);


		if($email === $data_val['email'] && $hash === $data_val['emailcode'] && $username === $data_val['username'])
		{
		
			if($data_val['is_email_verify'] == 1){
				$data['verification_msg'] = $this->lang->line('expired_email_verification');
				$this->load->view('templates/header', $data);
				$this->load->view('pages/user/register_form3_view', $data);
				$this->load->view('templates/footer_full');
				return;
			}

			$temp = array(
				'is_email_verify' => 1,
				'member_id' => $member_id
			);

			$this->register_model->update_verification_status($temp);
			
			$data['verification_msg'] = $this->lang->line('success_email_verification');
            $data['render_searchbar'] = false;
            $data['render_logo'] = false;
			$this->load->view('templates/header', $data);
			$this->load->view('pages/user/register_form3_view', $data);
			$this->load->view('templates/footer_full');
		}
		else{
			$this->load->view('templates/header', $data);
			$this->load->view('pages/user/err_email_verif', $data);
			$this->load->view('templates/footer_full');
		}
	}
 
 
    
}// close class

