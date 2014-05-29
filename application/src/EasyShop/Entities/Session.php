<?php

namespace Easyshop\Entities;



/**
 * @Entity
 * @Table(name="ci_sessions", indexes={
 *     @Index(name="last_activity_idx", columns={"last_activity"}),
 *     @Index(name="old_session_id", columns={"old_session_id"})
 * })
 */
class Session
{
    /**
     * @Id
     * @Column(name="session_id", type="string", length=40, options={"default"="0"})
     *  @var string 
     */
    protected $id;
    
    /**
     * @Column(name="ip_address", type="string", length=45, options={"default"="0"})
     *  @var string 
     */
    protected $ipAddress;
    
    /**
     * @Column(name="user_agent", type="string", length=120)
     *  @var string 
     */
    protected $userAgent;
    
    /**
     * @Column(name="last_activity", type="integer", length=10, options={"default"=0, "unsigned"=true})
     *  @var int 
     */
    protected $lastActivity;
    
    /**
     * @Column(name="user_data", type="text")
     *  @var int 
     */
    protected $userData;
    
    /**
     * @Column(name="old_session_id", type="string", length=40, nullable=true, options={"comment"="old session id"})
     *  @var int 
     */
    protected $oldSessionId;
    
    
    /* Getters / setters */
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getIpAddress()
    {
        return $this->ipAddress;
    }
    
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }
    
    public function getUserAgent()
    {
        return $this->userAgent;
    }
    
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }
    
    public function getLastActivity()
    {
        return $this->lastActivity;
    }
    
    public function setLastActivity($lastActivyty)
    {
        $this->lastActivity = $lastActivyty;
        return $this;
    }
    
    public function getUserData()
    {
        return $this->userData;
    }
    
    public function setUserData($userData)
    {
        $this->userData = $userData;
        return $this;
    }
    
    public function getOldSessionId()
    {
        return $this->oldSessionId;
    }
    
    public function setOldSessionId($oldSessionId)
    {
        $this->oldSessionId = $oldSessionId;
        return $this;
    }
}
