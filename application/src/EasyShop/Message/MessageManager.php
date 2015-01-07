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
        $message = $this->em->getRepository('EasyShop\Entities\EsMessages')
                                ->getAllMessage($userId);
        $data = [];
        $result = [];
        $unreadMsg = 0;
        // TODO : make use of foreach
        // TODO : make this array more readable
        for ( $ctr = 0; $ctr < sizeof($message); $ctr++ ) {
            $inbox = $message[$ctr]['to_id'] . $message[$ctr]['from_id'];
            $sentBox = $message[$ctr]['from_id'] . $message[$ctr]['to_id'];
            $status = ($message[$ctr]['from_id'] == $userId ? EsMessages::MESSAGE_SENDER : EsMessages::MESSAGE_RECEIVER);
            $messageId = $message[$ctr]['id_msg'];
            if ( array_key_exists($sentBox, $data) ) {
                $data[$sentBox][$messageId] = $message[$ctr];
                $data[$sentBox][$messageId]['status'] = $status;
            }
            elseif ( array_key_exists($inbox, $data) ) {
                $data[$inbox][$messageId] = $message[$ctr];
                $data[$inbox][$messageId]['status'] = $status;
            }
            else {
                if( $status === EsMessages::MESSAGE_SENDER &&
                    ( (int) $message[$ctr]['is_delete'] === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        (int) $message[$ctr]['is_delete'] === (int) EsMessages::MESSAGE_DELETED_BY_RECEIVER ) ) {
                    $data[$sentBox][$messageId] = $message[$ctr];
                    $data[$sentBox][$messageId]['status'] = EsMessages::MESSAGE_SENDER;
                    $data[$sentBox][$messageId]['name'] = ( (int) $message[$ctr]['from_id'] === (int) $userId ?
                                                            $message[$ctr]['recipient'] :
                                                            $message[$ctr]['sender']);
                }
                else if ( $status === EsMessages::MESSAGE_RECEIVER &&
                    ( (int) $message[$ctr]['is_delete'] === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        (int) $message[$ctr]['is_delete'] === (int) EsMessages::MESSAGE_DELETED_BY_SENDER ) ) {
                    $data[$sentBox][$messageId] = $message[$ctr];
                    $data[$sentBox][$messageId]['status'] = EsMessages::MESSAGE_RECEIVER;
                    $data[$sentBox][$messageId]['unreadConve'] = 0;
                    $data[$sentBox][$messageId]['name'] = ( (int) $message[$ctr]['from_id'] === (int) $userId ?
                                                            $message[$ctr]['recipient'] :
                                                            $message[$ctr]['sender']);
                    if ( (int) $message[$ctr]['opened'] === 0 ) {
                        $unreadMsg++;
                    }
                }
            }
        }

        $result['messages'] = array_values($data);
        $size = sizeof($result['messages']);
        // TODO : Foreach is much cleaner
        for ($x = 0; $x < $size; $x++) {
            $unreadMsgPerConversation = 0;
            foreach($result['messages'][$x] as $key =>$row) {
                $delete = (int) $result['messages'][$x][$key]['is_delete'];
                $status = $result['messages'][$x][$key]['status'];
                $isOpened = (bool) $result['messages'][$x][$key]['opened'];
                if ( $status === EsMessages::MESSAGE_SENDER &&
                    ($delete === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        $delete === (int) EsMessages::MESSAGE_DELETED_BY_RECEIVER )
                ) {
                }
                else if ( $status === EsMessages::MESSAGE_RECEIVER &&
                    ( $delete === (int) EsMessages::MESSAGE_NOT_DELETED ||
                        $delete === (int) EsMessages::MESSAGE_DELETED_BY_SENDER )
                ) {
                    // TODO Fix condition
                    if (!$isOpened) {
                        $unreadMsgPerConversation++;
                    }
                }
                else {
                    unset($result['messages'][$x][$key]);
                }

                $first_key = reset($result['messages'][$x])['id_msg'];
            }
            $result['messages'][$x][$first_key]['unreadConve'] = $unreadMsgPerConversation;
            $result['unread_msgs'] = $unreadMsg;
        }

        if ($getUnreadMessages) {
            $size  = sizeof($result['messages']);
            // TODO Foreach is cleaner
            for ($x = 0; $size > $x; $x++) {
                foreach ($result['messages'][$x] as $data) {
                    if (
                        ( ( isset($data['name']) && (int) $data['to_id'] === $userId ) && $data['opened']) ||
                        ($data['status'] === EsMessages::MESSAGE_SENDER && isset($data['name']) )
                    ) {
                        unset($result['messages'][$x]);
                    }
                }
            }

            $result['Case'] = "UnreadMsgs";
        }

        return $result;
    }

}
