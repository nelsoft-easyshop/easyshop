<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Messaging controller
 * 
 * This class is marked for a complete re-factor.
 * Messaging functionality is to be moved to
 * a separate class.
 */ 
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
        }
        else {
            redirect(base_url() . 'home', 'refresh');
        }
    }

    /**
     * Ajax : Send message and push message to websocket
     *
     * @param string $username
     * @param integer $sender id
     * @param string $message
     * @return json
     */
    public function send_msg()
    {
        $sessionData = $this->session->all_userdata();
        $username = trim($this->input->post("recipient"));
        $qResult = $this->user_model->getUserByUsername($username);

        if($qResult === false){
            $result['success'] = 0;
            $result['msg'] = "The user ". html_escape($username). ' does not exist';
        }
        else if($sessionData['member_id'] == $qResult['id_member']){
            $result['success'] = 0;
            $result['msg'] = "Sorry, it seems that you are trying to send a message to yourself.";
        }
        else{
            $msg = trim($this->input->post("msg"));
            $result = $this->messages_model->send_message($sessionData['member_id'],$qResult['id_member'],$msg);
            if($result === 1){
                $result = $this->messages_model->get_all_messages($this->user_ID);
                
                // TODO: query count only
                $recipientMessages = $this->messages_model->get_all_messages($qResult['id_member'], "Get_UnreadMsgs");
                
                $dc = new \EasyShop\WebSocket\Pusher\DataContainer();
                $dc->set('messageCount', $recipientMessages['unread_msgs']);
                $dc->set('unreadMessages', $recipientMessages);
                
                $userPusher = $this->serviceContainer['user_pusher'];
                $userPusher->push($qResult['id_member'], $dc);
            }
        }

    echo json_encode($result);
    }

    /**
     * Ajax : Delete message or conversation
     *
     * @param inetger $id_msg  id of the message that will be deleted
     * @return json
     */
    public function delete_msg()
    {
        $id = $this->input->post("id_msg");

        $result = $this->messages_model->delete_msg($id, $this->user_ID);
        if ($result > 0) {
            $result = $this->messages_model->get_all_messages($this->user_ID);
        }
        else {
            $result = "";
        }

        echo json_encode($result);
    }

    /**
     * Ajax : Get unread message or conversation depending on the parameter
     *
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
