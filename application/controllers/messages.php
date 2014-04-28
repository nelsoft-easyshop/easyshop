<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

    class messages extends MY_Controller {
	public $user_ID = null;
	function __construct(){
	    parent::__construct();
		$this->load->helper('htmlpurifier');
	    $this->load->library('session');
	    $this->load->library('cart');
	    $this->load->model('messages_model');
	    $this->load->model('user_model');
		$this->user_ID = $this->session->userdata('member_id');
	}
    public function index() {
		$result = $this->messages_model->get_all_messages($this->user_ID);
		$title = ($result['unread_msgs'] == 0 ? 'Message | Easyshop.ph' :'Message ('.$result['unread_msgs'].') | Easyshop.ph' );
		$data['title'] = $title;
		$data['result'] = $result;
		$data = array_merge($data,$this->fill_header());
		$this->load->view('templates/header_topnavsolo', $data);
		$this->load->view('pages/messages/inbox_view');
		//$this->load->view('pages/sample');
		$this->load->view('templates/footer_full');
    }    
    public function send_msg() { // walaa pang validation
        $session_data = $this->session->all_userdata();
		$rec = trim($this->input->post("recipient"));
		if(!is_numeric($rec)){
			$result = $this->messages_model->get_recepientID($rec);
		}
		if($result != "false"){
			$msg = trim($this->input->post("msg"));			
			$result = $this->messages_model->send_message($session_data['member_id'],$result,$msg);
			if($result = 1){
				$result = $this->messages_model->get_all_messages($this->user_ID);			
			}
		}
		echo json_encode($result);
	
    }
    public function delete_msg(){
		$id = $this->input->post("id_msg");
		$result = $this->messages_model->delete_msg($id,$this->user_ID);
		if($result > 0 ){
			$result = $this->messages_model->get_all_messages($this->user_ID);	
		} else {
			$result = "";
		}
		echo json_encode($result);
    }
	public function get_all_msgs(){
		$result = $this->messages_model->get_all_messages($this->user_ID);	
		echo json_encode($result);
	}
    
}
?>