<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MessageController extends MY_Controller
{

    const CONVERSATIONS_PER_PAGE = 10;

    const MESSAGES_PER_CONVERSATION_PAGE = 20;

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
        $conversationHeaderData = $this->messageManager->getConversationHeaders($this->userId, 0, self::CONVERSATIONS_PER_PAGE);
        $member = $this->em->find('EasyShop\Entities\EsMember', $this->userId);
        $data = [
            'conversationHeaders' => json_encode($conversationHeaderData['conversationHeaders'], true),
            'unreadConversationCount' => $conversationHeaderData['totalUnreadMessages'],
            'userEntity' => $member,
            'chatServerHost' => $this->messageManager->getChatHost(true),
            'chatServerPort' => $this->messageManager->getChatPort()
        ];

        $title = $conversationHeaderData['totalUnreadMessages'] > 0
                ? 'Messages (' . $conversationHeaderData['totalUnreadMessages'] . ') | Easyshop.ph'
                : 'Messages | Easyshop.ph';
        $headerData = [
            "memberId" => $this->userId,
            'title' => $title,
        ];

        $this->load->spark('decorator');
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/messages/inbox_view', $data );
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }

    /**
     * Get conversation messages between loggin user and posted partnerId
     *
     * @return JSON
     */
    public function getConversationMessages()
    {
        $partnerId = (int) $this->input->get('partnerId');
        $page = $this->input->get('page') ? (int) $this->input->get('page') : 1;
        $page--;
        $offset = $page * self::MESSAGES_PER_CONVERSATION_PAGE;
        $messages = $this->messageManager->getConversationMessages(
                                             $this->userId, 
                                             $partnerId, 
                                             $offset, 
                                             self::MESSAGES_PER_CONVERSATION_PAGE
                                         );
       
        echo json_encode($messages);
    }
    
    /**
     * Retrieve more conversation headers
     *
     * @return JSON
     */
    public function getConversationHeaders()
    {
        $searchString = $this->input->get('searchString') ? $this->input->get('searchString') : NULL;
        $page = $this->input->get('page') ? (int) $this->input->get('page') : 1;
        $page--;
        $offset = $page * self::CONVERSATIONS_PER_PAGE;
        $conversationHeaderData = $this->messageManager->getConversationHeaders(
                                                             $this->userId, 
                                                             $offset, 
                                                             self::CONVERSATIONS_PER_PAGE,
                                                             $searchString
                                                         );

        echo json_encode($conversationHeaderData);        
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
        $senderEntity = $this->em->find("EasyShop\Entities\EsMember", $this->userId);
        $message = trim($this->input->post("message"));

        $result = [
            'success' => false,
            'errorMessage' => '',
            'messageDetails' => [],
        ];
        
        $messageSendingResult = $this->messageManager->sendMessage($senderEntity, $receiverEntity, $message);
        if($messageSendingResult['isSuccessful']){
            $result['success'] = true;
            $result['messageDetails'] = $this->messageManager->getMessageDetailsById($messageSendingResult['messageId']);
        }
        else{
            switch($messageSendingResult['error']){
                case EasyShop\Message\MessageManager::RECIPIENT_DOES_NOT_EXIST_ERROR:
                    $result['errorMessage'] = "The user " . html_escape($storeName) . ' does not exist';
                    break;
                case EasyShop\Message\MessageManager::SELF_SENDING_ERROR:
                    $result['errorMessage'] = "Oops, it seems that you are trying to send a message to yourself";
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
    public function deleteMessage()
    {
        $rawMessageIds = $this->input->post("message_ids") ? json_decode($this->input->post("message_ids")) : [];
        $messageIds = [];
        foreach($rawMessageIds as $rawMessageId){
            $messageIds[] = (int) $rawMessageId;
        }
        $numberOfDeletedMessages = 0;
        if(empty($messageIds) === false){
            $numberOfDeletedMessages = $this->em->getRepository("EasyShop\Entities\EsMessages")
                                            ->deleteMessages($messageIds, $this->userId);
        }

        echo json_encode([
            'numberOfDeletedMessages' => $numberOfDeletedMessages,
        ]);
    }

    /**
     * Delete conversation between authenticated user and partner
     *
     * @return JSON
     */
    public function deleteConversation()
    {
        $partnerId = (int) $this->input->post('partnerId');
        $numberOfDeletedMessages = $this->em->getRepository('EasyShop\Entities\EsMessages')
                                 ->deleteConversation($this->userId, $partnerId);
        echo json_encode([
            'numberOfDeletedMessages' => $numberOfDeletedMessages,
        ]); 
    }
    

    /**
     * Mark message as read
     *
     * @return json
     */
    public function markMessageAsRead()
    {
        $partnerId = (int) $this->input->post('partnerId');
        $numberOfUpdatedMessages = $this->em->getRepository("EasyShop\Entities\EsMessages")
                                        ->updateToSeen($this->userId, $partnerId);
        if($numberOfUpdatedMessages > 0){
            $member = $this->serviceContainer['entity_manager']
                           ->find('EasyShop\Entities\EsMember', $this->userId);
            $redisChatChannel = $this->messageManager->getRedisChannelName();
            try{
                $this->serviceContainer['redis_client']->publish($redisChatChannel, json_encode([
                    'event' => 'message-opened',
                    'reader' => $member->getStorename(),
                ]));
            }
            catch(\Exception $e){
                /**
                 * Catch any exception but do nothing just so that the functionality
                 * does not break if the redis channel is not available
                 */
            }
        }
        
        echo json_encode([
            'numberOfUpdatedMessages' => $numberOfUpdatedMessages,
        ]);
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

    /**
     * Renders new message page
     *
     * @return View
     */
    public function chat()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Chat | Easyshop.ph',
            'metadescription' => "Read Easyshop.ph's Chat",
        ];

        $this->load->spark('decorator');
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/messages/chat_view');
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }


}
