<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsBanType
 *
 * @ORM\Table(name="es_ban_type")
 * @ORM\Entity
 */
class EsBanType
{
    const NOT_BANNED = 0;

    const PAYPAL_DISPUTE = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_ban_type", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBanType;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=false)
     */
    private $message = '';



    /**
     * Get idBanType
     *
     * @return integer 
     */
    public function getIdBanType()
    {
        return $this->idBanType;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return EsBanType
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return EsBanType
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
}
