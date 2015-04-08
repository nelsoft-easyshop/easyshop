<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMember
 *
 * @ORM\Table(name="es_member", indexes={@ORM\Index(name="username_idx", columns={"username"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsMemberRepository")
 */
class EsMember
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_member", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMember;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username = '';

    /**
     * @var string
     *
     * @ORM\Column(name="usersession", type="string", length=255, nullable=true)
     */
    private $usersession;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password = '';

    /**
     * @var string
     *
     * @ORM\Column(name="contactno", type="string", length=45, nullable=false)
     */
    private $contactno = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_contactno_verify", type="boolean", nullable=false)
     */
    private $isContactnoVerify = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_email_verify", type="boolean", nullable=false)
     */
    private $isEmailVerify = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     */
    private $gender = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=false)
     */
    private $birthday = '0001-01-01';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
     */
    private $datecreated = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastmodifieddate", type="datetime", nullable=false)
     */
    private $lastmodifieddate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login_datetime", type="datetime", nullable=false)
     */
    private $lastLoginDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="last_login_ip", type="string", length=45, nullable=false)
     */
    private $lastLoginIp = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="login_count", type="integer", nullable=false)
     */
    private $loginCount = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     */
    private $fullname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, nullable=true)
     */
    private $nickname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="imgurl", type="string", length=255, nullable=true)
     */
    private $imgurl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="userdata", type="text", nullable=true)
     */
    private $userdata;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="string", length=255, nullable=true)
     */
    private $remarks = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=false)
     */
    private $isAdmin = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="store_desc", type="string", length=1024, nullable=true)
     */
    private $storeDesc = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_promo_valid", type="boolean", nullable=false)
     */
    private $isPromoValid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="store_name", type="string", length=1024, nullable=true)
     */
    private $storeName;

    /**
     * @var string
     * @ORM\Column(name="oauth_id", type="string", length=255, nullable=false)
     */
    private $oauthId = '0';

    /**
     * @var string
     * @ORM\Column(name="oauth_provider", type="string", length=255, nullable=false)
     */
    private $oauthProvider = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="failed_login_count", type="integer", nullable=true)
     */
    private $failedLoginCount = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_failed_login_datetime", type="datetime", nullable=true)
     */
    private $lastFailedLoginDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="support_email", type="string", length=255, nullable=true)
     */
    private $supportEmail = '';

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hide_avatar", type="boolean", nullable=false)
     */
    private $isHideAvatar = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hide_banner", type="boolean", nullable=false)
     */
    private $isHideBanner = '0';
    
        
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_slug_changed", type="boolean", nullable=false)
     */
    private $isSlugChanged = '0';


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive = '1';    

    /**
     * @var \EasyShop\Entities\EsStoreColor
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsStoreColor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="store_color_id", referencedColumnName="id_store_color")
     * })
     */
    private $storeColor = '1';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_banned", type="boolean", nullable=false)
     */
    private $isBanned = '0';   
    
    /**
     * @var \EasyShop\Entities\EsBanType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsBanType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ban_type", referencedColumnName="id_ban_type")
     * })
     */
    private $banType = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="temp_id", type="string", length=45, nullable=false)
     */
    private $tempId = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_banner_changed", type="datetime", nullable=false)
     */
    private $lastBannerChanged = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_avatar_changed", type="datetime", nullable=false)
     */
    private $lastAvatarChanged = 'CURRENT_TIMESTAMP';

    
    /**
     *
     *  @var string
     */
    const DEFAULT_DATE = "0001-01-01";

    /**
     *  @var string
     */
    const DEFAULT_GENDER = "0";

    /**
     *  @var string
     */
    const DEFAULT_IMG_PATH = 'assets/user/default';

    /**
     *  @var string
     */
    const DEFAULT_IMG_SMALL_SIZE = '60x60.png';

    /**
     *  @var string
     */
    const DEFAULT_IMG_NORMAL_SIZE = '150x150.png';
    
    /**
     *  @var string
     */
    const DEFAULT_IMG_BANNER = 'banner.png';

    /**
     *  @var boolean
     */
    const DEFAULT_IMG_AVATAR = 'usersize.png';    

    /**
     *  @var string
     */
    const DEFAULT_ACTIVE = 1; 

    /**
     *  @var int
     */
    const DEFAULT_AVATAR_VISIBILITY = 0;       

    /**
     * @var boolean
     */
    const NOT_BANNED = 0;    

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="EasyShop\Entities\CiSessions", inversedBy="member")
     * @ORM\JoinTable(name="es_authenticated_session",
     *   joinColumns={
     *     @ORM\JoinColumn(name="member_id", referencedColumnName="id_member")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="session_id", referencedColumnName="session_id")
     *   }
     * )
     */
    private $session;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get idMember
     *
     * @return integer 
     */
    public function getIdMember()
    {
        return $this->idMember;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return EsMember
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set usersession
     *
     * @param string $usersession
     * @return EsMember
     */
    public function setUsersession($usersession)
    {
        $this->usersession = $usersession;

        return $this;
    }

    /**
     * Get usersession
     *
     * @return string 
     */
    public function getUsersession()
    {
        return $this->usersession;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return EsMember
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set contactno
     *
     * @param string $contactno
     * @return EsMember
     */
    public function setContactno($contactno)
    {
        $this->contactno = $contactno;

        return $this;
    }

    /**
     * Get contactno
     *
     * @return string 
     */
    public function getContactno()
    {
        return $this->contactno;
    }

    /**
     * Set isContactnoVerify
     *
     * @param boolean $isContactnoVerify
     * @return EsMember
     */
    public function setIsContactnoVerify($isContactnoVerify)
    {
        $this->isContactnoVerify = $isContactnoVerify;

        return $this;
    }

    /**
     * Get isContactnoVerify
     *
     * @return boolean 
     */
    public function getIsContactnoVerify()
    {
        return $this->isContactnoVerify;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return EsMember
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
     * Set isEmailVerify
     *
     * @param boolean $isEmailVerify
     * @return EsMember
     */
    public function setIsEmailVerify($isEmailVerify)
    {
        $this->isEmailVerify = $isEmailVerify;

        return $this;
    }

    /**
     * Get isEmailVerify
     *
     * @return boolean 
     */
    public function getIsEmailVerify()
    {
        return $this->isEmailVerify;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return EsMember
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return EsMember
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return EsMember
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

    /**
     * Set lastmodifieddate
     *
     * @param \DateTime $lastmodifieddate
     * @return EsMember
     */
    public function setLastmodifieddate($lastmodifieddate)
    {
        $this->lastmodifieddate = $lastmodifieddate;

        return $this;
    }

    /**
     * Get lastmodifieddate
     *
     * @return \DateTime 
     */
    public function getLastmodifieddate()
    {
        return $this->lastmodifieddate;
    }

    /**
     * Set lastLoginDatetime
     *
     * @param \DateTime $lastLoginDatetime
     * @return EsMember
     */
    public function setLastLoginDatetime($lastLoginDatetime)
    {
        $this->lastLoginDatetime = $lastLoginDatetime;

        return $this;
    }

    /**
     * Get lastLoginDatetime
     *
     * @return \DateTime 
     */
    public function getLastLoginDatetime()
    {
        return $this->lastLoginDatetime;
    }

    /**
     * Set lastLoginIp
     *
     * @param string $lastLoginIp
     * @return EsMember
     */
    public function setLastLoginIp($lastLoginIp)
    {
        $this->lastLoginIp = $lastLoginIp;

        return $this;
    }

    /**
     * Get lastLoginIp
     *
     * @return string 
     */
    public function getLastLoginIp()
    {
        return $this->lastLoginIp;
    }

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     * @return EsMember
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer 
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return EsMember
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return EsMember
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set imgurl
     *
     * @param string $imgurl
     * @return EsMember
     */
    public function setImgurl($imgurl)
    {
        $this->imgurl = $imgurl;

        return $this;
    }

    /**
     * Get imgurl
     *
     * @return string 
     */
    public function getImgurl()
    {
        return $this->imgurl;
    }

    /**
     * Set userdata
     *
     * @param string $userdata
     * @return EsMember
     */
    public function setUserdata($userdata)
    {
        $this->userdata = $userdata;

        return $this;
    }

    /**
     * Get userdata
     *
     * @return string 
     */
    public function getUserdata()
    {
        return $this->userdata;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     * @return EsMember
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string 
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     * @return EsMember
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set storeDesc
     *
     * @param string $storeDesc
     * @return EsMember
     */
    public function setStoreDesc($storeDesc)
    {
        $this->storeDesc = $storeDesc;

        return $this;
    }

    /**
     * Get storeDesc
     *
     * @return string 
     */
    public function getStoreDesc()
    {
        return $this->storeDesc;
    }

    /**
     * Set isPromoValid
     *
     * @param boolean $isPromoValid
     * @return EsMember
     */
    public function setIsPromoValid($isPromoValid)
    {
        $this->isPromoValid = $isPromoValid;

        return $this;
    }

    /**
     * Get isPromoValid
     *
     * @return boolean 
     */
    public function getIsPromoValid()
    {
        return $this->isPromoValid;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return EsMember
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set storeName
     *
     * @param string $storeName
     * @return EsMember
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * Get storeName
     *
     * @return string 
     */
    public function getStoreName()
    {   
        if ( strlen(trim($this->storeName)) <= 0 
           || !trim($this->storeName) ){ 
            return $this->getUsername();
        }
        return $this->storeName;
    }

    /**
     * Set OauthId
     *
     * @param string $oauthId
     * @return EsMember
     */
    public function setOauthId($oauthId)
    {
        $this->oauthId = $oauthId;
    }

    /**
     * Set failedLoginCount
     *
     * @param integer $failedLoginCount
     * @return EsMember
     */
    public function setFailedLoginCount($failedLoginCount)
    {
        $this->failedLoginCount = $failedLoginCount;
        return $this;
    }

    /**
     * Get OauthId
     *
     * @return string
     */
    public function getOauthId()
    {
        return $this->oauthId;
    }

    /**
     * Set OauthProvider
     *
     * @param string $oauthProvider
     * @return string
     */
    public function setOauthProvider($oauthProvider)
    {
        $this->oauthProvider = $oauthProvider;
    }

    /**
     * Get failedLoginCount
     *
     * @return integer 
     */
    public function getFailedLoginCount()
    {
        return $this->failedLoginCount;
    }

    /**
     * Set lastFailedLoginDatetime
     *
     * @param \DateTime $lastFailedLoginDatetime
     * @return EsMember
     */
    public function setLastFailedLoginDatetime($lastFailedLoginDatetime)
    {
        $this->lastFailedLoginDatetime = $lastFailedLoginDatetime;
        return $this;
    }

    /**
     * Get getOauthProvider
     *
     * @return string
     */
    public function getOauthProvider()
    {
        return $this->oauthProvider;
    }

    /**
     * Get lastFailedLoginDatetime
     *
     * @return \DateTime 
     */
    public function getLastFailedLoginDatetime()
    {
        return $this->lastFailedLoginDatetime;
    }

    /**
     * Add session
     *
     * @param \EasyShop\Entities\CiSessions $session
     * @return EsMember
     */
    public function addSession(\EasyShop\Entities\CiSessions $session)
    {
        $this->session[] = $session;

        return $this;
    }

    /**
     * Remove session
     *
     * @param \EasyShop\Entities\CiSessions $session
     */
    public function removeSession(\EasyShop\Entities\CiSessions $session)
    {
        $this->session->removeElement($session);
    }

    /**
     * Get session
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set Support Email
     *
     * @param string $supportEmail
     * @return EsMember
     */
    public function setSupportEmail($supportEmail)
    {
        $this->supportEmail = $supportEmail;

        return $this;
    }

    /**
     * Get Support Email
     *
     * @return string 
     */
    public function getSupportEmail()
    {
        return $this->supportEmail;
    }

    /**
     * Set Website
     *
     * @param string $website
     * @return EsMember
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get Website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set isHideAvatar
     *
     * @param boolean $isHideAvatar
     * @return EsMember
     */
    public function setIsHideAvatar($isHideAvatar)
    {
        $this->isHideAvatar = $isHideAvatar;

        return $this;
    }

    /**
     * Get isHideAvatar
     *
     * @return boolean 
     */
    public function getIsHideAvatar()
    {
        return $this->isHideAvatar;
    }

    /**
     * Set isHideBanner
     *
     * @param boolean $isHideBanner
     * @return EsMember
     */
    public function setIsHideBanner($isHideBanner)
    {
        $this->isHideBanner = $isHideBanner;

        return $this;
    }

    /**
     * Get isHideBanner
     *
     * @return boolean 
     */
    public function getIsHideBanner()
    {
        return $this->isHideBanner;
    }

    /**
     * Set isSlugChanged
     *
     * @param bool $isSlugChanged
     */
    public function setIsSlugChanged($isSlugChanged)
    {
        $this->isSlugChanged = $isSlugChanged;
    }
    
    /**
     * Get isSlugChanged
     *
     * @return bool 
     */
    public function getIsSlugChanged()
    {
        return $this->isSlugChanged;
    }
    
    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return EsMember
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }    

    /**
     * Set storeColor
     *
     * @param EasyShop\Entities\EsStoreColor $storeColor
     */
    public function setStoreColor($storeColor)
    {
        $this->storeColor = $storeColor;
    }

    /**
     * Get storeColor
     *
     * @return EasyShop\Entities\EsStoreColor 
     */
    public function getStoreColor()
    {
        return $this->storeColor;
    }    
    
    
    /**
     * Set isBanned
     *
     * @param boolean
     */
    public function setIsBanned($isBanned)
    {
        $this->isBanned = $isBanned;
    }

    /**
     * Get banType
     *
     * @return boolean
     */
    public function getIsBanned()
    {
        return $this->isBanned;
    }    
    
    /**
     * Set banType
     *
     * @param EasyShop\Entities\EsBanType $banType
     */
    public function setBanType($banType)
    {
        $this->banType = $banType;
    }

    /**
     * Get banType
     *
     * @return EasyShop\Entities\EsBanType 
     */
    public function getBanType()
    {
        return $this->banType;
    }    

    /**
     * Get tempId
     *
     * @return integer 
     */
    public function getTempId()
    {
        return $this->tempId;
    }

    /**
     * Set tempId
     *
     * @param string $tempId
     * @return EsMember
     */
    public function setTempId($tempId)
    {
        $this->tempId = $tempId; 
    }

    /**
     * Set lastBannerChanged
     *
     * @param \DateTime $lastBannerChanged
     * @return EsMember
     */
    public function setLastBannerChanged($lastBannerChanged)
    {
        $this->lastBannerChanged = $lastBannerChanged;

        return $this;
    }

    /**
     * Get lastBannerChanged
     *
     * @return \DateTime 
     */
    public function getLastBannerChanged()
    {
        return $this->lastBannerChanged;
    }

    /**
     * Set lastAvatarChanged
     *
     * @param \DateTime $lastAvatarChanged
     * @return EsMember
     */
    public function setLastAvatarChanged($lastAvatarChanged)
    {
        $this->lastAvatarChanged = $lastAvatarChanged;

        return $this;
    }

    /**
     * Get lastAvatarChanged
     *
     * @return \DateTime 
     */
    public function getLastAvatarChanged()
    {
        return $this->lastAvatarChanged;
    }
}
