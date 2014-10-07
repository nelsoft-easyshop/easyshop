<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsKeeplogin
 *
 * @ORM\Table(name="es_keeplogin", uniqueConstraints={@ORM\UniqueConstraint(name="token_UNIQUE", columns={"token"}), @ORM\UniqueConstraint(name="UNIQUE PAIR", columns={"id_member", "last_ip", "useragent"})}, indexes={@ORM\Index(name="fk_es_keeplogin_es_member_idx", columns={"id_member"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsKeeploginRepository")
 */
class EsKeeplogin
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_keeplogin", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idKeeplogin;

    /**
     * @var string
     *
     * @ORM\Column(name="last_ip", type="string", length=255, nullable=false)
     */
    private $lastIp;

    /**
     * @var string
     *
     * @ORM\Column(name="useragent", type="string", length=255, nullable=false)
     */
    private $useragent;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=false)
     */
    private $token;

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_member", referencedColumnName="id_member")
     * })
     */
    private $idMember;



    /**
     * Get idKeeplogin
     *
     * @return integer 
     */
    public function getIdKeeplogin()
    {
        return $this->idKeeplogin;
    }

    /**
     * Set lastIp
     *
     * @param string $lastIp
     * @return EsKeeplogin
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;

        return $this;
    }

    /**
     * Get lastIp
     *
     * @return string 
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * Set useragent
     *
     * @param string $useragent
     * @return EsKeeplogin
     */
    public function setUseragent($useragent)
    {
        $this->useragent = $useragent;

        return $this;
    }

    /**
     * Get useragent
     *
     * @return string 
     */
    public function getUseragent()
    {
        return $this->useragent;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return EsKeeplogin
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set idMember
     *
     * @param \EasyShop\Entities\EsMember $idMember
     * @return EsKeeplogin
     */
    public function setIdMember(\EasyShop\Entities\EsMember $idMember = null)
    {
        $this->idMember = $idMember;

        return $this;
    }

    /**
     * Get idMember
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getIdMember()
    {
        return $this->idMember;
    }
}
