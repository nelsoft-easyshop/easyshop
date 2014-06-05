<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class messages extends MY_Controller
{

    public $user_ID = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('htmlpurifier');
        $this->load->library('session');
        $this->load->model('messages_model');
        $this->user_ID = $this->session->userdata('member_id');
    }

    public function index()
    {
        if ($this->session->userdata('usersession')) {
            $result = $this->messages_model->get_all_messages($this->user_ID, "kurt");
            $title = (!isset($result['unread_msgs']) || $result['unread_msgs'] == 0
                    ? 'Message | Easyshop.ph'
                    : 'Message (' . $result['unread_msgs'] . ') | Easyshop.ph' );
            $data['title'] = $title;
            $data['result'] = $result;
            $data = array_merge($data, $this->fill_header());
            $data['render_searchbar'] = false;
            $this->load->view('templates/header', $data);
            $this->load->view('pages/messages/inbox_view');
            $this->load->view('templates/footer_full');
        } else {
            redirect(base_url() . 'home', 'refresh');
        }
    }

    public function send_msg() {
        $session_data = $this->session->all_userdata();
		$val = trim($this->input->post("recipient"));
//		if(!is_numeric($result)){
//			$result = $this->messages_model->get_recepientID($result);
//		}
        $q_result = $this->messages_model->get_recepientID($val);
        if($session_data['member_id'] == $val || $q_result == "false"){
            $result['success'] = 0;
            $result['msg'] = "Username doesnt exist";
        }else{
            $msg = trim($this->input->post("msg"));
            $result = $this->messages_model->send_message($session_data['member_id'],$q_result,$msg);
            if($result === 1){
                $result = $this->messages_model->get_all_messages($this->user_ID,"kurt");

            }
        }
		echo json_encode($result);
    }

    public function delete_msg() {
        $id = $this->input->post("id_msg");

        $result = $this->messages_model->delete_msg($id, $this->user_ID);
        if ($result > 0) {
            $result = $this->messages_model->get_all_messages($this->user_ID, "kurt");
        } else {
            $result = "";
        }
        echo json_encode($result);
    }

    public function retrieve_msgs() {
        $todo = $this->input->post("todo");
        $result = $this->messages_model->get_all_messages($this->user_ID, $todo);
        echo json_encode($result);
    }

    public function is_seened() {
        $id = $this->user_ID;
        $from_ids = $this->input->post('checked');
        $result = $this->messages_model->is_seened($id, $from_ids);

        echo json_encode($result);
    }

}

