<?php
namespace EasyShop\Message;

use \DateTime;
use EasyShop\Entities\EsMessages;

class MessageManager {

    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     *  Local Config file
     *
     *  @var \EasyShop\Core\Configuration\Configuration
     */
    private $localConfig;

    /**
     *  Js Server Config file
     *
     *  @var config/param/js_config.php
     */
    private $jsServerConfig;

    function __construct($em, $localConfig, $jsServerConfig)
    {
        $this->em = $em;
        $this->localConfig = $localConfig;
        $this->jsServerConfig = $jsServerConfig;
    }

    /**
     * Send message
     * @param $sender
     * @param $recipient
     * @param $userMessage
     * @return esMessage
     */
    public function send($sender, $recipient, $userMessage)
    {
        $message = new EsMessages();
        $message->setTo($recipient);
        $message->setFrom($sender);
        $message->setMessage($userMessage);
        $message->setTimeSent(new DateTime('now'));

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    /**
     * Get all message
     * @param $userId
     * @param bool $getUnreadMessages
     * @return array
     */
    public function getAllMessage($userId, $getUnreadMessages = false)
    {
        $result = [];
        $unreadMsgCount = 0;
        $messages = $this->em->getRepository('EasyShop\Entities\EsMessages')
                             ->getAllMessage($userId);
        $messageContainer = [];
        foreach ($messages as $message) {
            $receiverKey = $message['to_id'] . '~' . $message['from_id'];
            $senderKey = $message['from_id'] . '~' . $message['to_id'];
            $status = (int) $message['from_id'] === (int) $userId ? EsMessages::MESSAGE_SENDER : EsMessages::MESSAGE_RECEIVER;
            $messageId = $message['id_msg'];

            if (array_key_exists($senderKey, $messageContainer)) {
                $messageContainer[$senderKey][$messageId] = $message;
                $messageContainer[$senderKey][$messageId]['status'] = $status;
            }
            else if (array_key_exists($receiverKey, $messageContainer)) {
                $messageContainer[$receiverKey][$messageId] = $message;
                $messageContainer[$receiverKey][$messageId]['status'] = $status;
            }
            else {
                if( $status === EsMessages::MESSAGE_SENDER &&
                    ( (int) $message['is_delete'] === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        (int) $message['is_delete'] === (int) EsMessages::MESSAGE_DELETED_BY_RECEIVER ) ) {
                    $messageContainer[$senderKey][$messageId] = $message;
                    $messageContainer[$senderKey][$messageId]['status'] = EsMessages::MESSAGE_SENDER;
                    $messageContainer[$senderKey][$messageId]['name'] = ( (int) $message['from_id'] === (int) $userId ?
                                                                            $message['recipient'] :
                                                                            $message['sender']);
                }
                else if ( $status === EsMessages::MESSAGE_RECEIVER &&
                    ( (int) $message['is_delete'] === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        (int) $message['is_delete'] === (int) EsMessages::MESSAGE_DELETED_BY_SENDER ) ) {
                    $messageContainer[$senderKey][$messageId] = $message;
                    $messageContainer[$senderKey][$messageId]['status'] = EsMessages::MESSAGE_RECEIVER;
                    $messageContainer[$senderKey][$messageId]['unreadConversationCount'] = 0;
                    $messageContainer[$senderKey][$messageId]['name'] = ( (int) $message['from_id'] === (int) $userId ?
                                                                            $message['recipient'] :
                                                                            $message['sender']);
                    if ( (int) $message['opened'] === 0 ) {
                        $unreadMsgCount++;
                    }
                }
            }
        }

        $resultMessageContainer = array_values($messageContainer);
        $result['messages'] =[];
        $result['unread_msgs_count'] = $unreadMsgCount;
        foreach ($resultMessageContainer as $conversation) {
            $unreadConversationCount = 0;
            foreach($conversation as $message) {
                $delete = (int) $message['is_delete'];
                $status = $message['status'];
                $isOpened = (bool) $message['opened'];
                if ( $status === EsMessages::MESSAGE_SENDER &&
                    ($delete === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        $delete === (int) EsMessages::MESSAGE_DELETED_BY_RECEIVER )
                ) {
                }
                else if ( ( $status === EsMessages::MESSAGE_RECEIVER &&
                    ( $delete === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        $delete === (int) EsMessages::MESSAGE_DELETED_BY_SENDER ) ) && !$isOpened
                ) {
                    $unreadConversationCount++;
                }
                else {
                    unset($message);
                }

                $first_key = reset($conversation)['id_msg'];
            }
            $conversation[$first_key]['unreadConversationCount'] = $unreadConversationCount;
            $result['messages'][] = $conversation;
        }

        if ($getUnreadMessages) {
            foreach ($result['messages'] as $conversation) {
                foreach ($conversation as $message) {
                    if (
                        ( ( isset($message['name']) && (int) $message['to_id'] === $userId ) && $message['opened'] ) ||
                        ($message['status'] === EsMessages::MESSAGE_SENDER && isset($message['name']) )
                    ) {
                        unset($conversation);
                    }
                }
            }
            $result['isUnreadMessages'] = true;
        }

        return $result;
    }

    /**
     * returns the valid host for chat messaging
     * @return string
     */
    public function getChatHost()
    {
        $host = trim($this->jsServerConfig['HOST']);
        if($this->localConfig->isConfigFileExists()) {
            $configInternalIp = $this->localConfig->getConfigValue('internal_ip');           
            if(strlen($configInternalIp) > 0){
                $host = $configInternalIp;
            }
            else{
                $configBaseUrl = $this->localConfig->getConfigValue('base_url');
                if(strlen($configBaseUrl) > 0) {
                    $host = $configBaseUrl;
                }
            }            
        }
        
        if (strpos($host, 'https://') !== false) {
            $host = str_replace('https:', '', preg_replace('{/}', '', $host));
        }
        else if (strpos($host, 'http://') !== false) {
            $host = str_replace('http:', '', preg_replace('{/}', '', $host));
        }

        return $host;
    }

    /**
     * returns the port for chat messaging
     * @return int
     */
    public function getChatPort()
    {
        return trim($this->jsServerConfig['PORT']);
    }

}
