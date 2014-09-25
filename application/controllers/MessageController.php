<?php

class MessageController extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->user_ID = $this->session->userdata('member_id');
        $this->messageManager = $this->serviceContainer['message_manager'];
    }

    /**
     * Send Message
     * @param recipient int
     * @param msg string
     * @return mixed
     */
    public function send()
    {
        $message = $this->messageManager->send(
                                $this->user_ID,
                                $this->input->post(''),
                                $this->input->post('msg')
                            );
        Redirect('/home/contactUser' ,'refresh');
    }

}
