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
    

    function __construct($em, $configLoader, $languageLoader, $socialManager, $emailService, $parser, $redisClient, $localConfig)
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
            
            $emailRecipient = $recipient->getEmail();
            $emailSubject = $this->languageLoader->getLine('new_message_notif');
            $imageArray = $this->configLoader->getItem('email', 'images');
            $imageArray[] = "/assets/images/appbar.home.png";
            $imageArray[] = "/assets/images/appbar.message.png";

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

            /*
             uncomment to queue mail
            $this->emailService->setRecipient($emailRecipient)
                               ->setSubject($emailSubject)
                               ->setMessage($emailMsg, $imageArray)
                               ->queueMail();
            */
            
            $updatedMessageListForSender = $this->getAllMessage($sender->getIdMember());
            $updatedMessageListForReciver = $this->getAllMessage($recipient->getIdMember());

            $redisChatChannel = $this->getRedisChannelName();
            try{
                $this->redisClient->publish($redisChatChannel, json_encode([
                    'event' => 'message-sent',
                    'recipient' => $recipient->getStorename(),
                    'message' => $updatedMessageListForReciver,
                ]));
            }
            catch(\Exception $e){}
            $response['isSuccessful'] = true;
            $response['allMessages'] = $updatedMessageListForSender;
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
    
    
}
