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

        $data = [
            'result' => $this->messageManager->getAllMessage($this->userId),
            'userEntity' => $this->em->find("EasyShop\Entities\EsMember", $this->userId),
            'chatServerHost' => $this->messageManager->getChatHost(true),
            'chatServerPort' => $this->messageManager->getChatPort()
        ];
        $title = !isset($messages['unread_msgs']) || (int) $messages['unread_msgs'] === 0
            ? 'Messages | Easyshop.ph'
            : 'Messages (' . $messages['unread_msgs'] . ') | Easyshop.ph';
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => $title,
            'metadescription' => '',
            'relCanonical' => '',
            'renderSearchbar' => false,
        ];

        $this->load->spark('decorator');
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/messages/inbox_view', $data );
        $this->load->view('templates/footer_full', $this->decorator->decorate('footer', 'view'));
    }

    /**
     * Send message
     * @Param Recipient Username
     * @Param Message
     */
    public function send()
    {
        $storeName = trim($this->input->post("recipient"));
        $receiverEntity = $this->em->getRepository("EasyShop\Entities\EsMember")
                                   ->getUserWithStoreName($storeName);
        $memberEntity = $this->em->find("EasyShop\Entities\EsMember", $this->userId);

        if (!$receiverEntity) {
            $result['success'] = 0;
            $result['errorMessage'] = "The user " . html_escape($storeName) . ' does not exist';
        }
        else if ( (int) $this->userId === (int) $receiverEntity[0]->getIdMember() ) {
            $result['success'] = 0;
            $result['errorMessage'] = "Sorry, it seems that you are trying to send a message to yourself.";
        }
        else {
            $receiverEntity = $receiverEntity[0];
            $msg = trim($this->input->post("msg"));
            $isSendingSuccesful = $this->messageManager->send($memberEntity, $receiverEntity, $msg);
            if ($isSendingSuccesful) {
                $messages = $this->messageManager->getAllMessage($this->userId);
                $recipientMessages = $this->messageManager->getAllMessage($receiverEntity->getIdMember(), true);
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
                $result = [
                    'success' => 1,
                    'message' => $messages,
                    'recipientMessage' => $recipientMessages
                ];
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
        $messageId = trim($this->input->post("id_msg"));
        $messageIdArray = [
            $messageId
        ];
        if ( (bool) stripos($messageId, ',')) {
            $messageIdArray = explode(',', $messageId);
        }

        $isDeleteSuccesful = $this->em->getRepository("EasyShop\Entities\EsMessages")
                                      ->delete($messageIdArray, $this->userId);
        $message = '';

        if ( (bool) $isDeleteSuccesful) {
            $message = $this->messageManager->getAllMessage($this->userId);
        }

        echo json_encode($message);
    }

    /**
     * Get all message
     */
    public function getAllMessage()
    {
        $getUnreadMessages = $this->input->post("isUnread");
        $message = $this->messageManager->getAllMessage($this->userId, $getUnreadMessages);

        echo json_encode($message);
    }

    /**
     * Update message status to seen
     */
    public function updateMessageToSeen()
    {
        $messageId = $this->input->post('checked');
        $messageIdArray = [
            $messageId
        ];
        if ( (bool) stripos($messageId, '-')) {
            $messageIdArray = explode('-', $messageId);
        }

        $result = $this->em->getRepository("EasyShop\Entities\EsMessages")
                           ->updateToSeen($this->userId, $messageIdArray);

        echo json_encode($result);
    }

    /**
     * Sends message from vendor page
     */
    public function simpleSend()
    {
        $recipientSlug = trim($this->input->post('recipientSlug'));
        $recipient = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                              ->find((int) $this->input->post('recipient'));
        $member = $this->serviceContainer['entity_manager']->getRepository('EasyShop\Entities\EsMember')
                                                           ->find($this->userId);
        $redirectUrl = '/' . $recipientSlug . '/contact#Failed';
        if ($recipient) {
            $this->messageManager->send($member, $recipient, trim($this->input->post('msg')));
            $redirectUrl = '/' . $recipientSlug . '/contact#SendMessage';
        }

        redirect($redirectUrl ,'refresh');
    }

}
