<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsFailedLoginHistory
 *
 * @ORM\Table(name="es_failed_login_history")
 * @ORM\Entity
 */
class EsFailedLoginHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_failed_login", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFailedLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="login_username", type="string", length=255, nullable=false)
     */
    private $loginUsername = '';

    /**
     * @var string
     *
     * @ORM\Column(name="login_ip", type="string", length=45, nullable=false)
     */
    private $loginIp = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="login_datetime", type="datetime", nullable=false)
     */
    private $loginDatetime = 'CURRENT_TIMESTAMP';



    /**
     * Get idFailedLogin
     *
     * @return integer 
     */
    public function getIdFailedLogin()
    {
        return $this->idFailedLogin;
    }

    /**
     * Set loginUsername
     *
     * @param string $loginUsername
     * @return EsFailedLoginHistory
     */
    public function setLoginUsername($loginUsername)
    {
        $this->loginUsername = $loginUsername;

        return $this;
    }

    /**
     * Get loginUsername
     *
     * @return string 
     */
    public function getLoginUsername()
    {
        return $this->loginUsername;
    }

    /**
     * Set loginIp
     *
     * @param string $loginIp
     * @return EsFailedLoginHistory
     */
    public function setLoginIp($loginIp)
    {
        $this->loginIp = $loginIp;

        return $this;
    }

    /**
     * Get loginIp
     *
     * @return string 
     */
    public function getLoginIp()
    {
        return $this->loginIp;
    }

    /**
     * Set loginDatetime
     *
     * @param \DateTime $loginDatetime
     * @return EsFailedLoginHistory
     */
    public function setLoginDatetime($loginDatetime)
    {
        $this->loginDatetime = $loginDatetime;

        return $this;
    }

    /**
     * Get loginDatetime
     *
     * @return \DateTime 
     */
    public function getLoginDatetime()
    {
        return $this->loginDatetime;
    }
}
