<?php
namespace EasyShop\Message;

use \DateTime;
use EasyShop\Entities\EsMessages;

class MessageManager {

    const SENDER_DOES_NOT_EXIST_ERROR = 1;
    
    const RECIPIENT_DOES_NOT_EXIST_ERROR = 2;
    
    const SELF_SENDING_ERROR = 3;

    const MESSAGE_IS_EMPTY_ERROR = 4;

    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     *  Config Loader
     *
     *  @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;
    
    /**
     *  Language Loader
     *
     *  @var EasyShop\LanguageLoader\LanguageLoader
     */
    private $languageLoader;
    
    /**
     * Social Media Manager
     *
     * @var EasyShop\SocialMedia\SocialMediaManager
     */
    private $socialManager;
    
    
    /**
     * Email Notification Service
     *
     * @var EasyShop\Notifications\EmailNotification
     */
    private $emailService;
    
    /**
     * Codeigniter Parser
     *
     * @var \CI_Parser
     */
    private $parser;
    
    /**
     * Redis Client
     *
     * @var \Predis\Client
     */
    private $redisClient;
    
    /**
     * Redis Client
     *
     * @var \EasyShop\Core\Configuration\Configuration
     */
    private $localConfig;
    
    /**
     * JS Server config
     *
     * @var mixed
     */
    private $jsServerConfig;
 
    /**
     * User manager
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;
    

    function __construct(
        $em, 
        $configLoader, 
        $languageLoader, 
        $socialManager, 
        $emailService, 
        $userManager,
        $parser, 
        $redisClient, 
        $localConfig)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->languageLoader = $languageLoader;
        $this->socialManager = $socialManager;
        $this->emailService = $emailService;
        $this->parser = $parser;
        $this->redisClient = $redisClient;
        $this->localConfig = $localConfig;
        $this->jsServerConfig = $this->configLoader->getItem('nodejs');
        $this->userManager = $userManager;
    }

    /**
     * Sends a message
     * @param EasyShop\Entities\EsMember $sender
     * @param EasyShop\Entities\EsMember $recipient
     * @param string $userMessage
     * @return mixed
     */
    public function sendMessage($sender, $recipient, $userMessage)
    {
        $response = [
            'error' => '',
            'isSuccessful' => false,
            'messageId' => 0,
        ];
    
        if(!$sender){
            $response['error'] = self::SENDER_DOES_NOT_EXIST_ERROR;
        }
        else if(!$recipient){
            $response['error'] = self::RECIPIENT_DOES_NOT_EXIST_ERROR;
        }
        else if($sender->getIdMember() === $recipient->getIdMember()){
            $response['error'] = self::SELF_SENDING_ERROR;
        }
        else if(strlen(trim($userMessage)) === 0){
            $response['error'] = self::MESSAGE_IS_EMPTY_ERROR;
        }
        else{
            $message = new EsMessages();
            $message->setTo($recipient);
            $message->setFrom($sender);
            $message->setMessage($userMessage);
            $message->setTimeSent(new DateTime('now'));

            $this->em->persist($message);
            $this->em->flush();
        
            $messageId = $message->getIdMsg();
            $response['messageId'] = $messageId;
            $response['messageDetails'] = $this->getMessageDetailsById($messageId);

            /**
             * uncomment to queue mail
             *
            $emailRecipient = $recipient->getEmail();
            $emailSubject = $this->languageLoader->getLine('new_message_notif');
            $imageArray = $this->configLoader->getItem('email', 'images');

            $socialMediaLinks =  $this->socialManager->getSocialMediaLinks();
            $parseData = [
                'user' => $sender->getUsername(),
                'recipient' => $recipient->getUsername(),
                'msg_link' => base_url() . "messages/#" . $sender->getUsername(),
                'msg' => $message->getMessage(),
                'facebook' => $socialMediaLinks["facebook"],
                'twitter' => $socialMediaLinks["twitter"],
                'baseUrl' => base_url(),
            ];

            $emailMsg = $this->parser->parse("emails/email_newmessage", $parseData, true);



            $this->emailService->setRecipient($emailRecipient)
                               ->setSubject($emailSubject)
                               ->setMessage($emailMsg, $imageArray)
                               ->queueMail();
            */
            
            $updatedMessageListForReciever = $this->getAllMessage($recipient->getIdMember());
            $redisChatChannel = $this->getRedisChannelName();
            try{   
                $this->redisClient->publish($redisChatChannel, json_encode([
                    'event' => 'message-sent',
                    'recipient' => $recipient->getStorename(),
                    'messageData' => [
                        'message' => $response['messageDetails'],
                    ],
                ]));
            }
            catch(\Exception $e){
               
                /**
                 * Catch any exception but do nothing just so that the functionality
                 * does not break if the redis channel is not available
                 */

            }
           
            $response['isSuccessful'] = true;
        }
        
        return $response;
    }
    

    /**
     * Get all messages for a particular user.
     * The name and no. of unread messages per discussion
     * is placed in the first message element of the conversations array
     *
     * @param integer $userId
     * @return mixed
     */
    public function getAllMessage($userId)
    {
        $userId = (int) $userId;
        $messages = $this->em->getRepository('EasyShop\Entities\EsMessages')
                             ->getAllMessage($userId);
        /**
         * Form message container
         */
        $formattedMessageContainer = [];
        foreach($messages as $message){
        
            if((int) $message['from_id'] === (int) $userId){
                $status = EsMessages::MESSAGE_SENDER ;
                $otherMemberId = $message['to_id'];
                $otherMemberName = $message['recipient'];
                $isShow = (int) $message['is_delete'] === (int) EsMessages::MESSAGE_NOT_DELETED || (int) $message['is_delete'] === (int) EsMessages::MESSAGE_DELETED_BY_RECEIVER;
            }
            else{
                $status = EsMessages::MESSAGE_RECEIVER;
                $otherMemberId = $message['from_id'];
                $otherMemberName = $message['sender'];
                $isShow = (int) $message['is_delete'] === (int) EsMessages::MESSAGE_NOT_DELETED || (int) $message['is_delete'] === (int) EsMessages::MESSAGE_DELETED_BY_SENDER;
            }
            
            $keyPair = $userId.'~'.$otherMemberId;
            $messageId = $message['id_msg'];
            if($isShow){
                $messageData = $message;
                $messageData['status'] = $status;
                if(!array_key_exists($keyPair, $formattedMessageContainer)){
                    $messageData = array_merge($messageData, ['name' => $otherMemberName]);
                }
                $formattedMessageContainer[$keyPair][$messageId] = $messageData;
            }
        }
        
        /**
         * Count number of unread messages per discussion
         */
        $result['messages'] = []; 
        foreach($formattedMessageContainer as $discussion){
            $unreadMessageCount = 0;
            foreach($discussion as $singleMessage){
                $isOpened = (bool) $message['opened'];
                if($status === EsMessages::MESSAGE_RECEIVER && !$isOpened){
                    $unreadMessageCount++;
                }
            }
            $firstMessageId = reset($discussion)['id_msg'];
            $discussion[$firstMessageId]['unreadConversationCount'] = $unreadMessageCount;
            $result['messages'][] = $discussion;
        }
        $result['unread_msgs_count'] = $this->em->getRepository('EasyShop\Entities\EsMessages')
                                            ->getUnreadMessageCount($userId);
        
        return $result;
    }

    /**
     * Returns the valid host for chat messaging
     *
     * @param boolean $isBaseUrlOnly
     * @return string
     */
    public function getChatHost($isBaseUrlOnly = false)
    {
        $host = trim($this->jsServerConfig['HOST']);
        $this->configLoader->getItem('social_media_links');     
        
        if($this->localConfig->isConfigFileExists()) {
            $configInternalIp = $this->localConfig->getConfigValue('internal_ip');           
            if(strlen($configInternalIp) > 0 && !$isBaseUrlOnly){
                $host = $configInternalIp;
            }
            else{
                $host = base_url();
            }            
        }
        
        $host = rtrim($host, '/');
        $host = str_replace('https://', '', $host);
        $host = str_replace('http://', '', $host);

        return $host;
    }

    /**
     * Returns the port for chat messaging
     *
     * @return int
     */
    public function getChatPort()
    {
        return trim($this->jsServerConfig['NODE_PORT']);
    }

    /**
     * Retrieves the JWT secret
     *
     * @return string
     */
    public function getWebTokenSecret()
    {
        return $this->jsServerConfig['JWT_SECRET'];
    }
    
    
    /**
     * Retrieves the REDIS PORT
     *
     * @return int
     */
    public function getRedisPort()
    {
        return $this->jsServerConfig['REDIS_PORT'];
    }
    
    /**
     * Retrieves the REDIS HOST
     *
     * @return int
     */
    public function getRedisHost()
    {
        return $this->jsServerConfig['REDIS_HOST'];
    }
    
    /**
     * Retrieves the REDIS PORT
     *
     * @return int
     */
    public function getRedisChannelName()
    {
        return $this->jsServerConfig['REDIS_CHANNEL_NAME'];
    }
    
    
    /**
     * Retrieve conversation headers
     *
     * @param integer $memberId
     * @param integer $offset
     * @param integer $limit
     * @param string $searchString
     * @return mixed
     */
    public function getConversationHeaders($memberId, $offset = 0, $limit = PHP_INT_MAX, $searchString = NULL)
    {
        $memberId = (int) $memberId;
        $conversationHeaders = $this->em->getRepository('EasyShop\Entities\EsMessages')
                                    ->getConversationHeaders($memberId, $offset, $limit, $searchString);

        $numberOfUnreadConversations = $this->em->getRepository('EasyShop\Entities\EsMessages')
                                            ->getUnreadMessageCount($memberId);

        foreach($conversationHeaders as $key => $conversationHeader){
            $conversationHeaders[$key]['partner_image'] = $this->userManager->getUserImage($conversationHeader['partner_member_id'], 'small');
        }
              
        return [
            'conversationHeaders' => $conversationHeaders,
            'totalUnreadMessages' => $numberOfUnreadConversations,
        ];
    }

    /**
     * Get messages between two users
     *
     * @param integer $memberId
     * @param integer $partnerId
     * @param integer $offset
     * @param integer $limit
     * @return mixed
     */
    public function getConversationMessages($memberId, $partnerId, $offset = 0, $limit = PHP_INT_MAX)
    {
        $memberId = (int) $memberId;
        $partnerId = (int) $partnerId;
        $messages =  $this->em->getRepository('EasyShop\Entities\EsMessages')
                          ->getConversationMessages($memberId, $partnerId, $offset, $limit);
        $memberImage = $this->userManager->getUserImage($memberId, 'small');
        $partnerImage = $this->userManager->getUserImage($partnerId, 'small');
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                       ->find($memberId);
        $partner = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->find($partnerId);
        $memberStorename = $member->getStorename();
        $partnerStorename = $partner->getStorename();
       
        foreach($messages as $key => $message){
            if( (int) $message['sender_member_id'] === $memberId){
                $message['senderImage'] = $memberImage;
                $message['senderStorename'] = $memberStorename;
            }
            else{
                $message['senderImage'] = $partnerImage;
                $message['senderStorename'] = $partnerStorename;
            }
            $message['isSender'] = (int) $message['is_sender'] === 1;
            unset($message['is_sender']);
            $messages[$key] = $message;
        }

        return $messages;
    }

    /**
     * Retrieve message details by ID
     *
     * @param integer $messageId
     * @return mixed
     */
    public function getMessageDetailsById($messageId)
    {
        $message = $this->em->find('EasyShop\Entities\EsMessages', $messageId);
        $sender = $this->em->getRepository('EasyShop\Entities\EsMember')
                       ->find($message->getFrom());
        $recipient = $this->em->getRepository('EasyShop\Entities\EsMember')
                          ->find($message->getTo());
        $senderImage = $this->userManager->getUserImage($message->getFrom(), 'small');
        $recipientImage = $this->userManager->getUserImage($message->getTo(), 'small');
        $messageData = [
            'id_msg' => $message->getIdMsg(),
            'message' => $message->getMessage(),
            'time_sent' => $message->getTimeSent()->format('Y-m-d H:i:s'),
            'senderImage' => $senderImage,
            'senderStorename' => $sender->getStorename(),
            'senderMemberId' => $sender->getIdMember(),
            'recipientImage' => $recipientImage,
            'recipientStorename' => $recipient->getStorename(),
            'recipientMemberId' => $recipient->getIdMember(),
        ];
        
        return $messageData;
    }

    /**
     * Set messages between a user and his converstaion partner as read
     *
     * @param integer $userId
     * @param integer $partnerId
     * @return integer Number of marked messages
     */
    public function setConversationAsRead($userId, $partnerId)
    {
        $numberOfUpdatedMessages = $this->em->getRepository("EasyShop\Entities\EsMessages")
                                       ->updateToSeen($userId, $partnerId);
        if($numberOfUpdatedMessages > 0){
            $member = $this->em->find('EasyShop\Entities\EsMember', $userId);
            $redisChatChannel = $this->getRedisChannelName();
            try{
                $this->redisClient->publish($redisChatChannel, json_encode([
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
        return $numberOfUpdatedMessages;
    }
    

}
