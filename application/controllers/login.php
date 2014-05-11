<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('register_model');
        $this->load->model('user_model');
        $this->load->model('cart_model');
		$this->load->library('encrypt');	
    }
	
    function index() {
        $data = array(
            'title' => 'Login | Easyshop.ph',
            'page_javascript' => 'assets/JavaScript/login.js',
			);
		$data = array_merge($data, $this->fill_header());
        $view = 'login_view';
        $this->load->view('templates/header_plain', $data);
        $this->load->view('pages/user/login_view', array('url' => $this->session->userdata('uri_string'),));
        $this->load->view('templates/footer');
    }

	function authenticate() {
		if(($this->input->post('login_form'))&&($this->form_validation->run('login_form'))){
			$uname = $this->input->post('login_username');
			$pass = $this->input->post('login_password');	
			$dataval = array('login_username' => $uname, 'login_password' => $pass);
			$row = $this->user_model->verify_member($dataval);               
            #if user is valid: member i, usersession and cart_contents will be set in the session
			if ($row['o_success'] >= 1) {
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
			echo json_encode($row);
        }
    }

    function logout() {
        
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
	
	function identify(){
		
        $data = array(
            'title' => 'Forgot Password | Easyshop.ph'
		);
		$data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header_plain', $data);
		
		$temp['toggle_view'] = "";
		if(($this->input->post('identify_btn')) && ($this->form_validation->run('identify_form'))){
            $email = $this->input->post('email');
            $result = $this->register_model->check_registered_email($email);
			if (isset($result['username'])){
				// Send email and update database 
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

    function resetconfirm()
    {
        $data = array(
                'title' => 'Reset Password | Easyshop.ph'
        );
        $response['toggle_view'] = '';
        if($this->input->post()){
            $response['toggle_view'] = $this->input->post('tgv');
        }
        else{
            $response['hash'] = $this->input->get('confirm');
        }      
        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header_plain', $data);		
        $this->load->view('pages/user/forgotpass_confirm', $response);
        $this->load->view('templates/footer');
    }	
	
	function xresetconfirm()
    {
		$pass = $this->input->post('password');
		$hash = $this->input->post('hash');
		$result = $this->register_model->forgotpass_email($hash);
		if(isset($pass) && !empty($pass) && $this->form_validation->run('forgotpass')){
			if(isset($result['username'])){
				$user = $result['username'];
				$curpass = $result['password'];
				$mid = $result['member_id'];			
				$data = array(
						'username' => $user,
						'cur_password' => $curpass,
						'member_id' => $mid,
						'password' => $pass
				);
				$this->register_model->forgotpass_update($data); 
				echo "1";	
			}else{
				echo "0";
			}	
		}else{
			echo "0";
		}
	}	
	
}


/* End of file login.php */
/* Location: ./application/controllers/login.php */