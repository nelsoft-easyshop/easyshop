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
        $this->load->model('user_model');
        $this->user_ID = $this->session->userdata('member_id');
    }

    public function index()
    {
        if ($this->session->userdata('usersession')) {
            $result = $this->messages_model->get_all_messages($this->user_ID);
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

    /**
     * Ajax : Send message and push message to websocket
     *
     * @recepient_id  recepient id
     * @sender_id  sender id
     * @msg  message
     * @return json
     */
    public function send_msg()
    {
        $session_data = $this->session->all_userdata();
	    $val = trim($this->input->post("recipient"));
        $q_result = $this->user_model->getUserByUsername($val);

        if($session_data['member_id'] == $val || $q_result === false){
            $result['success'] = 0;
            $result['msg'] = "Username does not exist";
        }else{
            $msg = trim($this->input->post("msg"));
            $result = $this->messages_model->send_message($session_data['member_id'],$q_result['id_member'],$msg);
            if($result === 1){
                $result = $this->messages_model->get_all_messages($this->user_ID);
                
                // TODO: query count only
                $recipientMessages = $this->messages_model->get_all_messages($q_result['id_member'], "Get_UnreadMsgs");
                
                $dc = new \EasyShop\WebSocket\Pusher\DataContainer();
                $dc->set('messageCount', $recipientMessages['unread_msgs']);
                $dc->set('unreadMessages', $recipientMessages);
                
                $userPusher = $this->serviceContainer['user_pusher'];
                $userPusher->push($q_result['id_member'], $dc);
            }
        }

	echo json_encode($result);
    }

    /**
     * Ajax : Delete message or conversation
     *
     * @id_msg  id of the message that will be deleted
     * @return json
     */
    public function delete_msg()
    {
        $id = $this->input->post("id_msg");

        $result = $this->messages_model->delete_msg($id, $this->user_ID);
        if ($result > 0) {
            $result = $this->messages_model->get_all_messages($this->user_ID);
        } else {
            $result = "";
        }
        echo json_encode($result);
    }

    /**
     * Ajax : Get unread message or conversation depending on the parameter
     *
     * @todo  "Get_UnreadMsgs" or FALSE
     * @return json
     */
    public function retrieve_msgs()
    {
        $todo = $this->input->post("todo");
        $result = $this->messages_model->get_all_messages($this->user_ID, $todo);
        echo json_encode($result);
    }

    /**
     * Ajax : Change the message status to seened
     *
     * @id  User id
     * @return json
     */
    public function is_seened()
    {
        $id = $this->user_ID;
        $from_ids = $this->input->post('checked');
        $result = $this->messages_model->is_seened($id, $from_ids);

        echo json_encode($result);
    }

}
