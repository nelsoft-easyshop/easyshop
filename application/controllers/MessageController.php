<?php

class MessageController extends MY_Controller
{
    public $user_ID = null;

    function __construct()
    {
        $this->user_ID = $this->session->userdata('member_id');
        $this->messageManager = $this->serviceContainer['message_manager'];
    }

    public function getMessages()
    {

    }
    public function send()
    {

    }

}
