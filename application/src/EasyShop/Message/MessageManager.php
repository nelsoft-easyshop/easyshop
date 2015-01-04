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
     * @return boolean
     */
    public function send($sender, $recipient, $userMessage)
    {
        $senderObj = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find($sender);
        $recipientObj = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find( $recipient);
        $message = new EsMessages();
        $message->setTo($recipientObj);
        $message->setFrom($senderObj);
        $message->setMessage($userMessage);
        $message->setTimeSent(new DateTime('now'));

        $this->em->persist($message);
        $this->em->flush();

        return $message ? TRUE : FALSE;
    }

    public function getAllMessages($member)
    {

    }

    public function getConversation($sender, $recipient)
    {

    }

    public function updateMessageToSeen($sender, $recipient)
    {

    }

    public function deleteMessage($member)
    {

    }
}
