<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Messaging controller
 * 
 * Refactored Class is moved in MessageController.
 */ 
class messages extends MY_Controller
{

    /**
     * The message manager
     *
     * @var EasyShop\Message\MessageManager
     */
    private $messageManager;
    
    /**
     * Member ID of currently logged in used
     * 
     * @var integer
     */
    private $userId;


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('htmlpurifier');
        $this->load->library('session');
        $this->load->model('messages_model');
        $this->load->model('user_model');
        
        $this->userId = $this->session->userdata('member_id');
        $this->messageManager = $this->serviceContainer['message_manager'];
    }

    public function oldMessage()
    {
        if ($this->session->userdata('usersession')) {
            $messages = $this->messages_model->get_all_messages($this->userId);
            $title = !isset($messages['unread_msgs']) || (int)$messages['unread_msgs'] === 0
                    ? 'Messages | Easyshop.ph'
                    : 'Messages (' . $messages['unread_msgs'] . ') | Easyshop.ph';
            $headerData = [
                'title' => $title,  
                'metadescription' => '',
                'relCanonical' => '',
                'renderSearchbar' => false,
            ];

            $this->load->spark('decorator');  
            $this->load->view('templates/header',  $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/messages/inbox_view',  ['result' => $messages ]);
            $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view')); 
        }
        else {
            redirect('/', 'refresh');
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
        $this->load->library('parser');
        
        $sessionData = $this->session->all_userdata();
        $username = trim($this->input->post("recipient"));
        $qResult = $this->user_model->getUserByUsername($username);

        $em = $this->serviceContainer['entity_manager'];
        $emailService = $this->serviceContainer['email_notification'];

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
                $result = $this->messages_model->get_all_messages($this->userId);
                
                // TODO: query count only
                $recipientMessages = $this->messages_model->get_all_messages($qResult['id_member'], "Get_UnreadMsgs");
                
                $dc = new \EasyShop\WebSocket\Pusher\DataContainer();
                $dc->set('messageCount', $recipientMessages['unread_msgs']);
                $dc->set('unreadMessages', $recipientMessages);
                
                $userPusher = $this->serviceContainer['user_pusher'];
                $userPusher->push($qResult['id_member'], $dc);

                # Queue email notification
                $memberEntity = $em->find("EasyShop\Entities\EsMember", $sessionData['member_id']);
                $emailRecipient = $qResult['email'];
                $emailSubject = $this->lang->line('new_message_notif');
                $this->config->load('email', true);
                $imageArray = $this->config->config['images'];
                $imageArray[] = "/assets/images/appbar.home.png";
                $imageArray[] = "/assets/images/appbar.message.png";

                $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                         ->getSocialMediaLinks();
                $parseData = array(
                    'user' => $memberEntity->getUsername()
                    , 'recipient' => $qResult['username']
                    , 'home_link' => base_url()
                    , 'store_link' => base_url() . $memberEntity->getSlug()
                    , 'msg_link' => base_url() . "messages/#" . $memberEntity->getUsername()
                    , 'msg' => $msg
                    , 'facebook' => $socialMediaLinks["facebook"]
                    , 'twitter' => $socialMediaLinks["twitter"]
                );
               
                $emailMsg = $this->parser->parse("emails/email_newmessage", $parseData, TRUE);

                $emailService->setRecipient($emailRecipient)
                             ->setSubject($emailSubject)
                             ->setMessage($emailMsg, $imageArray)
                             ->queueMail();
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

        $result = $this->messages_model->delete_msg($id, $this->userId);
        if ($result > 0) {
            $result = $this->messages_model->get_all_messages($this->userId);
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
        $result = $this->messages_model->get_all_messages($this->userId, $todo);

        echo json_encode($result);
    }

    /**
     * Ajax : Change the message status to seened
     *
     * @return json
     */
    public function is_seened()
    {
        $id = $this->userId;
        $from_ids = $this->input->post('checked');
        $result = $this->messages_model->is_seened($id, $from_ids);

        echo json_encode($result);
    }
    
    /**
     * New action for sending a message
     *
     * @param recipient int
     * @param msg string
     */
    public function doSendMessage()
    {
        $recipient = intval($this->input->post('recipient'));
        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find($recipient);
        $this->messageManager->send( $this->userId, $recipient, $this->input->post('msg'));
        redirect('/'.$member->getSlug().'/contact#SendMessage' ,'refresh');
    }

}
