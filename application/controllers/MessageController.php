<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MessageController extends MY_Controller
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

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->em = $this->serviceContainer['entity_manager'];
        $this->messageManager = $this->serviceContainer['message_manager'];
        $this->userId = $this->session->userdata('member_id');
        $this->emailService = $this->serviceContainer['email_notification'];
        $this->load->library('parser');
    }

    /**
     * Retrieve messages
     */
    public function messages()
    {
        if (!$this->session->userdata('usersession')) {
            redirect('/', 'refresh');
        }

        $messages = $this->messageManager->getAllMessage($this->userId);
        $title = !isset($messages['unread_msgs']) || (int) $messages['unread_msgs'] === 0
            ? 'Messages | Easyshop.ph'
            : 'Messages (' . $messages['unread_msgs'] . ') | Easyshop.ph';
        $headerData = [
            'title' => $title,
            'metadescription' => '',
            'relCanonical' => '',
            'renderSearchbar' => false,
        ];

        $this->load->spark('decorator');
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/messages/inbox_view', ['result' => $messages ]);
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
    }

    /**
     * Send message
     * @Param Recipient Username
     * @Param Message
     */
    public function send()
    {
        $sessionData = $this->session->all_userdata();
        $username = trim($this->input->post("recipient"));
        $receiverEntity = $this->em->getRepository("EasyShop\Entities\EsMember")->findOneBy(['username' => $username]);
        $memberEntity = $this->em->find("EasyShop\Entities\EsMember", $sessionData['member_id']);

        if (!$receiverEntity) {
            $result['success'] = 0;
            $result['msg'] = "The user " . html_escape($username) . ' does not exist';
        }
        else if ( (int) $sessionData['member_id'] === (int) $receiverEntity->getIdMember() ) {
            $result['success'] = 0;
            $result['msg'] = "Sorry, it seems that you are trying to send a message to yourself.";
        }
        else {
            $msg = trim($this->input->post("msg"));
            $result = $this->messageManager->send($memberEntity, $receiverEntity, $msg);
            if ($result === 1) {
                $result = $this->messageManager->getAllMessage($this->userId);
                $recipientMessages = $this->messageManager->getAllMessage($this->userId, true);

                $dc = new \EasyShop\WebSocket\Pusher\DataContainer();
                $dc->set('messageCount', $recipientMessages['unread_msgs']);
                $dc->set('unreadMessages', $recipientMessages);

                $userPusher = $this->serviceContainer['user_pusher'];
                $userPusher->push($receiverEntity->getIdMember(), $dc);

                $emailRecipient = $receiverEntity->getEmail();
                $emailSubject = $this->lang->line('new_message_notif');
                $this->config->load('email', true);
                $imageArray = $this->config->config['images'];
                $imageArray[] = "/assets/images/appbar.home.png";
                $imageArray[] = "/assets/images/appbar.message.png";

                $socialMediaLinks = $this->serviceContainer['social_media_manager']->getSocialMediaLinks();
                $parseData = [
                    'user' => $memberEntity->getUsername(),
                    'recipient' => $receiverEntity->getUsername(),
                    'home_link' => base_url(),
                    'store_link' => base_url() . $memberEntity->getSlug(),
                    'msg_link' => base_url() . "messages/#" . $memberEntity->getUsername(),
                    'msg' => $msg,
                    'facebook' => $socialMediaLinks["facebook"],
                    'twitter' => $socialMediaLinks["twitter"],
                ];

                $emailMsg = $this->parser->parse("emails/email_newmessage", $parseData, true);
                $this->emailService->setRecipient($emailRecipient)
                                   ->setSubject($emailSubject)
                                   ->setMessage($emailMsg, $imageArray)
                                   ->queueMail();
            }
        }

        echo json_encode($result);
    }

    /**
     * Delete Message/Conversation
     * @Param id_msg
     */
    public function delete()
    {
        $messageId = (int) $this->input->post("id_msg");
        $message = $this->em->getRepository("EasyShop\Entities\EsMessages")->delete($messageId, $this->userId);
        $result = '';

        if ( (bool) $message) {
            $result = $this->messageManager->getAllMessage($this->userId);
        }

        echo json_encode($result);
    }

    /**
     * Get all message
     */
    public function getAllMessage()
    {
        $getUnreadMessages = $this->input->post("todo");
        $result = $this->messages_model->get_all_messages($this->userId, $getUnreadMessages);

        echo json_encode($result);
    }

    /**
     * Update message status to seen
     */
    public function updateMessageToSeen()
    {
        $messageId = $this->input->post('checked');
        $result = $this->em->getRepository("EasyShop\Entities\EsMessages")->updateToSeen($this->userId, $messageId);

        echo json_encode($result);
    }
}
