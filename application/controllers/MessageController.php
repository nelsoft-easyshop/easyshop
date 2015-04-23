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
     *
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('usersession')) {
            redirect('/', 'refresh');
        }
        $this->em = $this->serviceContainer['entity_manager'];
        $this->messageManager = $this->serviceContainer['message_manager'];
        $this->userId = $this->session->userdata('member_id');
        $this->emailService = $this->serviceContainer['email_notification'];
        $this->load->library('parser');
    }

    /**
     * Renders the inbox view
     *
     */
    public function messages()
    {
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
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/messages/inbox_view', $data );
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }

    /**
     * Sends a message
     *
     * @return json
     */
    public function send()
    {
        $storeName = trim($this->input->post("recipient"));
        $receiverEntity = $this->em->getRepository("EasyShop\Entities\EsMember")
                                   ->getUserWithStoreName($storeName);
        $receiverEntity = $receiverEntity ? $receiverEntity : null;        
        $senderEntity = $this->em->find("EasyShop\Entities\EsMember", $this->userId);
        $msg = trim($this->input->post("msg"));

        $result = [
            'success' => false,
            'errorMessage' => '',
            'updatedMessageList' => '',
        ];
        
        $messageSendingResult = $this->messageManager->sendMessage($senderEntity, $receiverEntity, $msg);
        if($messageSendingResult['isSuccessful']){
            $result['success'] = true;
            $result['updatedMessageList'] = $messageSendingResult['allMessages'];
        }
        else{
            switch($messageSendingResult['error']){
                case EasyShop\Message\MessageManager::RECIPIENT_DOES_NOT_EXIST_ERROR:
                    $result['errorMessage'] = "The user " . html_escape($storeName) . ' does not exist';
                    break;
                case EasyShop\Message\MessageManager::SELF_SENDING_ERROR:
                    $result['errorMessage'] = "Sorry, it seems that you are trying to send a message to yourself";
                    break;
                case EasyShop\Message\MessageManager::MESSAGE_IS_EMPTY_ERROR:
                    $result['errorMessage'] = "Please write a message.";
                    break;
                default:
                    $result['errorMessage'] = "Sorry, we cannot process your request at this time. Please try again later";
                    break;
            }
        }

        echo json_encode($result);
    }

    /**
     * Deletes Message/Conversation
     *
     * @return json
     */
    public function delete()
    {
        $messageId = trim($this->input->post("id_msg"));
        $temporaryIdArray = [
            $messageId
        ];
        if ( (bool) stripos($messageId, ',')) {
            $temporaryIdArray = explode(',', $messageId);
        }
        
        $messageIdArray = [];
        foreach($temporaryIdArray as $messageId){
            $messageIdArray[] = (int) $messageId;
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
     * Update message status to seen
     *
     * @return json
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
        if($result){
            $member = $this->serviceContainer['entity_manager']
                           ->find('EasyShop\Entities\EsMember', $this->userId);
            $redisChatChannel = $this->messageManager->getRedisChannelName();
            try{
                $this->serviceContainer['redis_client']->publish($redisChatChannel, json_encode([
                    'event' => 'message-opened',
                    'reader' => $member->getStorename(),
                ]));
            }
            catch(\Exception $e){}
        }
        
        echo json_encode($result);
    }

    /**
     * Sends a message from vendor page
     *
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
            $this->messageManager->sendMessage($member, $recipient, trim($this->input->post('msg')));
            $redirectUrl = '/' . $recipientSlug . '/contact#SendMessage';
        }

        redirect($redirectUrl ,'refresh');
    }
    
    /**
     * Gets the number of unread messages of the logged in user
     *
     * @return json
     */
    public function getNumberOfUnreadMessages()
    {
        $count = $this->serviceContainer['entity_manager']
                      ->getRepository('EasyShop\Entities\EsMessages')
                      ->getUnreadMessageCount($this->userId);
        echo json_encode($count);
    }

}
