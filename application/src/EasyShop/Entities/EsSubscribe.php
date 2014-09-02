<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsSubscribe
 *
 * @ORM\Table(name="es_subscribe", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsSubscribeRepository")
 */
class EsSubscribe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_subscribe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSubscribe;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
     */
    private $datecreated = 'CURRENT_TIMESTAMP';



    /**
     * Get idSubscribe
     *
     * @return integer 
     */
    public function getIdSubscribe()
    {
        return $this->idSubscribe;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return EsSubscribe
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return EsSubscribe
     */
    public function setDatecreated($datecreated)
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    /**
     * Get datecreated
     *
     * @return \DateTime 
     */
    public function getDatecreated()
    {
        return $this->datecreated;
    }
}
