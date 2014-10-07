<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CiSessions
 *
 * @ORM\Table(name="ci_sessions", indexes={@ORM\Index(name="last_activity_idx", columns={"last_activity"}), @ORM\Index(name="old_session_id", columns={"old_session_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\CiSessionsRepository")
 */
class CiSessions
{
    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="string", length=40, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessionId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=45, nullable=false)
     */
    private $ipAddress = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=120, nullable=false)
     */
    private $userAgent;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_activity", type="integer", nullable=false)
     */
    private $lastActivity = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="user_data", type="text", nullable=false)
     */
    private $userData;

    /**
     * @var string
     *
     * @ORM\Column(name="old_session_id", type="string", length=40, nullable=true)
     */
    private $oldSessionId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="EasyShop\Entities\EsMember", mappedBy="session")
     */
    private $member;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->member = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return CiSessions
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return CiSessions
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set lastActivity
     *
     * @param integer $lastActivity
     * @return CiSessions
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return integer 
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set userData
     *
     * @param string $userData
     * @return CiSessions
     */
    public function setUserData($userData)
    {
        $this->userData = $userData;

        return $this;
    }

    /**
     * Get userData
     *
     * @return string 
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * Set oldSessionId
     *
     * @param string $oldSessionId
     * @return CiSessions
     */
    public function setOldSessionId($oldSessionId)
    {
        $this->oldSessionId = $oldSessionId;

        return $this;
    }

    /**
     * Get oldSessionId
     *
     * @return string 
     */
    public function getOldSessionId()
    {
        return $this->oldSessionId;
    }

    /**
     * Add member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return CiSessions
     */
    public function addMember(\EasyShop\Entities\EsMember $member)
    {
        $this->member[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \EasyShop\Entities\EsMember $member
     */
    public function removeMember(\EasyShop\Entities\EsMember $member)
    {
        $this->member->removeElement($member);
    }

    /**
     * Get member
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMember()
    {
        return $this->member;
    }
}
