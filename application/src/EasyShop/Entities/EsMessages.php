<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMessages
 *
 * @ORM\Table(name="es_messages", indexes={@ORM\Index(name="fk_es_member_es_messages_idx", columns={"to_id"}), @ORM\Index(name="fk_sender_idx", columns={"from_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsMessagesRepository")
 */
class EsMessages
{
    const MESSAGE_UNREAD = 0;

    const MESSAGE_READ = 1;
    
    const MESSAGE_NOT_DELETED = '0';
    
    const MESSAGE_DELETED_BY_RECEIVER = '1';
    
    const MESSAGE_DELETED_BY_SENDER = '2';
    
    const MESSAGE_DELETED_BY_BOTH = '3';

    /**
     * @var integer
     *
     * @ORM\Column(name="id_msg", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMsg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_sent", type="datetime", nullable=true)
     */
    private $timeSent;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var boolean
     *
     * @ORM\Column(name="opened", type="boolean", nullable=true)
     */
    private $opened = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="is_delete", type="string", nullable=true)
     */
    private $isDelete = '0';

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_id", referencedColumnName="id_member")
     * })
     */
    private $to;

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="from_id", referencedColumnName="id_member")
     * })
     */
    private $from;



    /**
     * Get idMsg
     *
     * @return integer 
     */
    public function getIdMsg()
    {
        return $this->idMsg;
    }

    /**
     * Set timeSent
     *
     * @param \DateTime $timeSent
     * @return EsMessages
     */
    public function setTimeSent($timeSent)
    {
        $this->timeSent = $timeSent;

        return $this;
    }

    /**
     * Get timeSent
     *
     * @return \DateTime 
     */
    public function getTimeSent()
    {
        return $this->timeSent;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return EsMessages
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set opened
     *
     * @param boolean $opened
     * @return EsMessages
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * Get opened
     *
     * @return boolean 
     */
    public function getOpened()
    {
        return $this->opened;
    }

    /**
     * Set isDelete
     *
     * @param string $isDelete
     * @return EsMessages
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return string 
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * Set to
     *
     * @param \EasyShop\Entities\EsMember $to
     * @return EsMessages
     */
    public function setTo(\EasyShop\Entities\EsMember $to = null)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set from
     *
     * @param \EasyShop\Entities\EsMember $from
     * @return EsMessages
     */
    public function setFrom(\EasyShop\Entities\EsMember $from = null)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getFrom()
    {
        return $this->from;
    }
}
