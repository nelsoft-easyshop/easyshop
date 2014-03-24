<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class New_login extends MY_Controller {
	#Load the user model that will be used in the functions below
    function __construct() {
        parent::__construct();
        $this->load->library('cart');
		$this->load->model('register_model');
		$this->load->library('encrypt');
    }
	
	#Passing the data to login form and loads it.
    function index() { 
        $data = array(
            'title' => 'Login | Easyshop.ph',
            'page_javascript' => 'assets/JavaScript/new_login.js',
			);
		$data = array_merge($data, $this->fill_header());
        $view = 'login_view';
        $this->load->view('templates/header_plain', $data);
        //$this->load->view('pages/user/login_view');
		$this->load->view('pages/user/new_loginview');
        $this->load->view('templates/footer');
    }

	#when login button is clicked, username pass will check if it exsits in the database
	function authenticate() {
		if(($this->input->post('login_form'))&&($this->form_validation->run('login_form'))){
			$uname = $this->input->post('login_username');
			$pass = $this->input->post('login_password');	
			$dataval = array('login_username' => $uname, 'login_password' => $pass);
			$row = $this->user_model->verify_member($dataval);               

			if ($row['o_success'] >= 1) {///////////////////////////////////////////if exsist,member id and usersession(defined in the sql query) will be set in the session
				$this->session->set_userdata('member_id', $row['o_memberid']);
				$this->session->set_userdata('usersession', $row['o_session']);
                $this->session->set_userdata('cart_contents', $this->cart_model->cartdata($row['o_memberid']));
				if($this->input->post('keepmeloggedin') == 'on'){ //create cookie bound to memberid||ip||browser 
					$temp = array(
						'member_id' => $this->session->userdata('member_id'),
						'ip' => $this->session->userdata('ip_address'),
						'useragent' => $this->session->userdata('user_agent'),
						'session' => $this->session->userdata('session_id'),
					);
					$cookieval = $this->user_model->dbsave_cookie_keeplogin($temp)['o_token'];
					$this->user_model->create_cookie($cookieval);
				}
			}  
			echo json_encode($row);//////////////////////////////////////////////////else return o_message(set in the query) to ajax and post it
        }
    }

    function logout() {/////////////////////////////////////////////////////////when log out button is clicked,session will be set to empty and redirect to home
        
        $cart_items = serialize($this->session->userdata('cart_contents'));
        $id = $this->session->userdata('member_id');
        $this->cart_model->save_cartitems($cart_items,$id);
        
        $this->user_model->logout();
        $temp = array(
                'member_id' => $this->session->userdata('member_id'),
                'ip' => $this->session->userdata('ip_address'),
                'useragent' => $this->session->userdata('user_agent'),
                'token' => get_cookie('es_usr')
        );

        $this->user_model->dbdelete_cookie_keeplogin($temp);
        delete_cookie('es_usr');
		
        $this->session->sess_destroy();
		$referrer = $this->input->get('referrer');
		if(trim($referrer))
			redirect(base_url().$referrer);
		else
			redirect(base_url().'login');		
    }
	
	
	
	/**	SIGNUP in Login Page**/
	function signup()
	{
		if(($this->input->post('register_page1'))&&($this->form_validation->run('register_form1'))){
			$data['username'] = html_escape($this->input->post('username'));
			$data['password'] = html_escape($this->input->post('password'));
			$data['email'] = $this->input->post('email');
			
			
			$this->sendVerificationCode($data);

			$temp['member_username'] = $data['username'];
			$temp['verification_msg'] = $this->lang->line('success_registration');
			$temp = array_merge($temp, $this->fill_header());
		
			$this->load->view("pages/user/register_form3_view", $temp);
		}
	}
	
	function sendVerificationCode($data)
	{
		//GENERATE MOBILE CONFIRMATION CODE
		$temp['mobilecode'] = $this->register_model->rand_alphanumeric(6);
		//GENERATE HASH FOR EMAIL VERIFICATION
		$temp['emailcode'] = sha1($this->session->userdata('session_id').time());
		
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
	/** End of Signup in Login Page **/
	
	
	
	function identify(){
		
        $data = array(
            'title' => 'Forgot Password | Easyshop.ph',
            'page_javascript' => 'assets/JavaScript/login.js',
		);
		$data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header_plain', $data);
		
		$temp['toggle_view'] = "";
		if(($this->input->post('identify_btn'))&& ($this->form_validation->run('identify_form'))){
			
            $email = $this->input->post('email');
            $result = $this->register_model->validate_email($email);
         
			if (isset($result['username'])){
				// magsesend na siya ng email dito at magra-write sa db.
				if ($this->register_model->forgotpass($email, $result['username'], $result['id_member']) == 1){
					$temp['toggle_view'] = "1";
				}else{
					$temp['toggle_view'] = "3";		
				}
			}else{
				$temp['toggle_view'] = "2";
			}
        }
        $this->load->view('pages/user/forgotpass', $temp);
        $this->load->view('templates/footer');	
	}
	
	function resetconfirm(){

		$data = array(
			'title' => 'Reset Password | Easyshop.ph'
			//'page_javascript' => 'assets/JavaScript/register_js.php',
		);
		$data = array_merge($data, $this->fill_header());
		$this->load->view('templates/header_plain', $data);		
        
		$hash = html_escape($this->input->get('confirm'));
        
        $result = $this->register_model->forgotpass_email($hash);
        
        if (isset($result['username'])){
            $user = $result['username'];
            $curpass = $result['password'];
            
			if(($this->input->post('forgotpass_btn')) && ($this->form_validation->run('forgotpass'))){
				$data = array(
					'username' => $user,
					'cur_password' => $curpass,
					'password' => $this->input->post('password')
				);
				$temp['toggle_view'] = "";
				$this->register_model->forgotpass_update($data); // pasok pa sa oras pero magsasave na.
				$this->session->unset_userdata('user_cur_loc');
			}
			else{
				$temp['toggle_view'] = "1";                      // pasok pa sa oras pero hindi pa magsasave ito.
			}			
		}else{
			$temp['toggle_view'] = "2";                        
		}
        $this->load->view('pages/user/forgotpass_confirm', $temp);
		$this->load->view('templates/footer');
	}
	
}


/* End of file login.php */
/* Location: ./application/controllers/login.php */