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

    function __construct($em)
    {
        $this->em = $em;
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
        $data = [];
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
                    $messageContainer[$senderKey][$messageId]['unreadConve'] = 0;
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
            $unreadMsgPerConversation = 0;
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
                    $unreadMsgPerConversation++;
                }
                else {
                    unset($message);
                }

                $first_key = reset($conversation)['id_msg'];
            }
            $conversation[$first_key]['unreadConve'] = $unreadMsgPerConversation;
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

}
