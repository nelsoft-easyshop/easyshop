<?php

namespace Easyshop\Entities;



/**
 * @Entity
 * @Table(name="es_member", indexes={@Index(name="username_idx", columns={"username"})})
 */
class User
{
    /** 
     * @Id
     * @Column(name="id_member", type="integer", length=10, options={"unsigned"=true})
     * @GeneratedValue(strategy="AUTO")
     *  @var int 
     */
    protected $id;
    
    /**
     * @Column(name="username", type="string", length=255, options={"default"=""})
     * @var string 
     */
    protected $username;
    
    /**
     * @Column(name="usersession", type="string", length=255, nullable=true)
     * @var string 
     */
    protected $session;
    
    /**
     * @Column(name="password", type="string", length=255, nullable=false, options={"default"=""})
     * @var string
     */
    protected $password;
    
    /**
     * @Column(name="contactno", type="string", length=45, options={"default"=""})
     * @var string
     */
    protected $contactNumber;
    
    /**
     * @Column(name="is_contactno_verify", type="boolean", options={"default"=false})
     * @var boolean
     */
    protected $isContactNumberVerified;
    
    /**
     * @Column(name="email", type="string", length=255, options={"default"=""})
     * @var string
     */
    protected $email;
    
    /**
     * @Column(name="is_email_verify", type="boolean", options={"default"=false})
     * @var boolean
     */
    protected $isEmailVerified;
    
    /**
     * @Column(name="gender", type="string", length=1, options={"default"="0"})
     * @var string
     */
    protected $gender;
    
    /**
     * @Column(name="birthday", type="date", options={"default"="0001-01-01"})
     * @var DateTime
     */
    protected $birthday;
    
    /**
     * @Column(name="datecreated", type="datetime", options={"default"="0001-01-01 01:01:01"})
     * @var DateTime
     */
    protected $creationDate;
    
    /**
     * @Column(name="lastmodifieddate", type="datetime", options={"default"="0001-01-01 01:01:01"})
     * @var DateTime
     */
    protected $lastModifiedDate;
    
    /**
     * @Column(name="last_login_datetime", type="datetime", options={"default"="0001-01-01 01:01:01"})
     * @var DateTime
     */
    protected $lastLoginDate;
    
    /**
     * @Column(name="last_login_ip", type="string", length=45, options={"default"=""})
     * @var string
     */
    protected $loginIp;
    
    /**
     * @Column(name="login_count", type="integer", length=10, options={"unsigned"=true, "default"=0})
     * @var int 
     */
    protected $loginCount;
    
    /**
     * @Column(name="fullname", type="string", length=255, nullable=true, options={"default"=""})    
     * @var string
     */
    protected $fullname;
    
    /**
     * @Column(name="nickname", type="string", length=255, nullable=true, options={"default"=""})    
     * @var string
     */
    protected $nickname;
    
    /**
     * @Column(name="imgurl", type="string", length=255, nullable=true, options={"default"=""})    
     * @var string
     */
    protected $imageUrl;
    
    /**
     * @Column(name="userdata", type="text", nullable=true)    
     * @var string
     */
    protected $data;
    
    
    /* Getters / setters */
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    
    public function getContactNumber()
    {
        return $this->contactNumber;
    }
    
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
        return $this;
    }
    
    public function getIsContactNumberVerified()
    {
        return $this->isContactNumberVerified;
    }
    
    public function setIsContactNumberVerified($isContactNumberVerified)
    {
        $this->isContactNumberVerified = $isContactNumberVerified;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function getIsEmailVerified()
    {
        return $this->isEmailVerified;
    }
    
    public function getGender()
    {
        return $this->gender;
    }
    
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }
    
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }
    
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    
    // No setter for creation date
    
    public function getLastModifiedDate()
    {
        return $this->lastModifiedDate;
    }
    
    public function setLastModifiedDate($lastModifiedDate)
    {
        $this->lastModifiedDate = $lastModifiedDate;
        return $this;
    }
    
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }
    
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
        return $this;
    }
    
    public function getLoginIp()
    {
        return $this->loginIp;
    }
    
    public function setLoginIp($loginIp)
    {
        $this->loginIp = $loginIp;
        return $this;
    }
    
    public function getLoginCount()
    {
        return $this->loginCount;
    }
    
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;
        return $this;
    }
    
    public function getFullname()
    {
        return $this->fullname;
    }
    
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }
    
    public function getNickname()
    {
        return $this->nickname;
    }
    
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }
    
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
    
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
// TODO add nullable fields
/* to remove:
 * +   region
 * +   member_type_id
 * +   address_id
 * +   rank
 */
