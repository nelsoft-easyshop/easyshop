<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EsProduct
 *
 * @ORM\Table(name="es_product", indexes={@ORM\Index(name="fk_es_product_es_cat1_idx", columns={"cat_id"}), @ORM\Index(name="fk_es_product_es_brand1_idx", columns={"brand_id"}), @ORM\Index(name="fk_es_product_es_style1_idx", columns={"style_id"}), @ORM\Index(name="fk_es_product_es_member1_idx", columns={"member_id"}), @ORM\Index(name="fk_es_product_es_billing_info_idx", columns={"billing_info_id"}), @ORM\Index(name="slug", columns={"slug"}), @ORM\Index(name="fk_es_product_es_keywords1_idx", columns={"name", "keywords"}), @ORM\Index(name="fulltext_search_keyword", columns={"search_keyword"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductRepository")
 */
class EsProduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_product", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduct;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=45, nullable=false)
     */
    private $sku = '';

    /**
     * @var string
     *
     * @ORM\Column(name="brief", type="string", length=255, nullable=false)
     */
    private $brief = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="`condition`", type="string", length=255, nullable=true)
     */
    private $condition = '';

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=1024, nullable=false)
     */
    private $keywords = '';

    /**
     * @var string
     *
     * @ORM\Column(name="search_keyword", type="string", length=1024, nullable=true)
     */
    private $searchKeyword = '';

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $price = '0.0000';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_real", type="smallint", nullable=false)
     */
    private $isReal = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_delete", type="smallint", nullable=false)
     */
    private $isDelete = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_new", type="smallint", nullable=false)
     */
    private $isNew = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_hot", type="smallint", nullable=false)
     */
    private $isHot = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_promote", type="smallint", nullable=false)
     */
    private $isPromote = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="member_memo", type="string", length=1024, nullable=false)
     */
    private $memberMemo = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createddate", type="datetime", nullable=false)
     */
    private $createddate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastmodifieddate", type="datetime", nullable=false)
     */
    private $lastmodifieddate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="clickcount", type="integer", nullable=false)
     */
    private $clickcount = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="cat_other_name", type="string", length=150, nullable=false)
     */
    private $catOtherName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="brand_other_name", type="string", length=150, nullable=false)
     */
    private $brandOtherName = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_draft", type="boolean", nullable=false)
     */
    private $isDraft = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="billing_info_id", type="integer", nullable=false)
     */
    private $billingInfoId = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_cod", type="boolean", nullable=false)
     */
    private $isCod = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug = '';

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $discount = '0.0000';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime", nullable=false)
     */
    private $startdate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime", nullable=false)
     */
    private $enddate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="promo_type", type="integer", nullable=false)
     */
    private $promoType = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sold_out", type="boolean", nullable=false)
     */
    private $isSoldOut = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_meetup", type="boolean", nullable=false)
     */
    private $isMeetup = '1';

    /**
     * @var \EasyShop\Entities\EsBrand
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsBrand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id_brand")
     * })
     */
    private $brand;

    /**
     * @var \EasyShop\Entities\EsCat
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsCat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="id_cat")
     * })
     */
    private $cat;

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id_member")
     * })
     */
    private $member;

    /**
     * @var \EasyShop\Entities\EsStyle
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsStyle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="style_id", referencedColumnName="id_style")
     * })
     */
    private $style;

    /**
     * @var boolean
     *
     */
    private $startPromo = '0';
    
        
    /**
     * @var boolean
     *
     */
    private $endPromo = '0';
    
    
    /**
     * @var string
     *
     */
    private $originalPrice = '0.0000';
    
    
    /**
     * @var string
     *
     */
    private $finalPrice = '0.0000';
     
    /**
     * @var string
     *
     */
    private $discountPercentage = '0.0000';

    
    /**
     *
     * @var bool
     *
     */
    private $isFreeShipping = false;
    
    /**
     * @var string
     *
     */
    private $soldPrice = '0.0000';
    
    
    /**
     * @var EasyShop\Entities\EsProductImage
     * 
     *
     */
    private $defaultImage;

    
    /**
     * @var \EasyShop\Entities\EsProductImage
     * @ORM\OneToMany(targetEntity="EasyShop\Entities\EsProductImage", mappedBy="product")
     **/

    private $images;

    /**
     * @var string
     *
     * @ORM\Column(name="max_allowable_point", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $maxAllowablePoint = '0.0000';

    /**
     *  @var integer
     *
     *  isDelete value for active items
     */
    const ACTIVE = 0;

    /**
     *  @var integer
     *
     *  isDelete value for deleted items
     */
    const DISABLE = 2;

    /**
     *  @var integer
     *
     *  isDraft value for drafted items ( item can be restored )
     */
    const DRAFT = 1;

    /**
     *  @var integer
     *
     *  isDelete value for deleted items ( item can be restored )
     */
    const DELETE = 1;

    /**
     *  @var integer
     *
     *  isDelete value for fully deleted items ( item cannot be restored )
     */
    const FULL_DELETE = 2;

    /**
     * Default value if the product is promoted
     */
    const PRODUCT_IS_PROMOTE_ON = 1;

    const SEARCH_SORT_POPULAR = 'POPULAR';
    const SEARCH_SORT_NEW = 'NEW';
    const SEARCH_SORT_HOT = 'HOT';
    const SEARCH_SORT_NAME = 'NAME';

    const MINIMUM_PRODUCT_NAME_LEN = 3; 

    /**
     * @var integer
     *
     * @ORM\Column(name="ships_within_days", type="integer", nullable=true)
     */
    private $shipsWithinDays;

    public function __construct() 
    {
        $this->images = new ArrayCollection();
    }
    
    /**
     * Get idProduct
     *
     * @return integer 
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsProduct
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return EsProduct
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set brief
     *
     * @param string $brief
     * @return EsProduct
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * Get brief
     *
     * @return string 
     */
    public function getBrief()
    {
        return $this->brief;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return EsProduct
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set condition
     *
     * @param string $condition
     * @return EsProduct
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string 
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return EsProduct
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set searchKeyword
     *
     * @param string $searchKeyword
     * @return EsProduct
     */
    public function setSearchKeyword($searchKeyword)
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    /**
     * Get searchKeyword
     *
     * @return string 
     */
    public function getSearchKeyword()
    {
        return $this->searchKeyword;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return EsProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set isReal
     *
     * @param integer $isReal
     * @return EsProduct
     */
    public function setIsReal($isReal)
    {
        $this->isReal = $isReal;

        return $this;
    }

    /**
     * Get isReal
     *
     * @return integer 
     */
    public function getIsReal()
    {
        return $this->isReal;
    }

    /**
     * Set isDelete
     *
     * @param integer $isDelete
     * @return EsProduct
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return integer 
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * Set isNew
     *
     * @param integer $isNew
     * @return EsProduct
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return integer 
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set isHot
     *
     * @param integer $isHot
     * @return EsProduct
     */
    public function setIsHot($isHot)
    {
        $this->isHot = $isHot;

        return $this;
    }

    /**
     * Get isHot
     *
     * @return integer 
     */
    public function getIsHot()
    {
        return $this->isHot;
    }

    /**
     * Set isPromote
     *
     * @param integer $isPromote
     * @return EsProduct
     */
    public function setIsPromote($isPromote)
    {
        $this->isPromote = $isPromote;

        return $this;
    }

    /**
     * Get isPromote
     *
     * @return integer 
     */
    public function getIsPromote()
    {
        return $this->isPromote;
    }

    /**
     * Set memberMemo
     *
     * @param string $memberMemo
     * @return EsProduct
     */
    public function setMemberMemo($memberMemo)
    {
        $this->memberMemo = $memberMemo;

        return $this;
    }

    /**
     * Get memberMemo
     *
     * @return string 
     */
    public function getMemberMemo()
    {
        return $this->memberMemo;
    }

    /**
     * Set createddate
     *
     * @param \DateTime $createddate
     * @return EsProduct
     */
    public function setCreateddate($createddate)
    {
        $this->createddate = $createddate;

        return $this;
    }

    /**
     * Get createddate
     *
     * @return \DateTime 
     */
    public function getCreateddate()
    {
        return $this->createddate;
    }

    /**
     * Set lastmodifieddate
     *
     * @param \DateTime $lastmodifieddate
     * @return EsProduct
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
     * Set clickcount
     *
     * @param integer $clickcount
     * @return EsProduct
     */
    public function setClickcount($clickcount)
    {
        $this->clickcount = $clickcount;

        return $this;
    }

    /**
     * Get clickcount
     *
     * @return integer 
     */
    public function getClickcount()
    {
        return $this->clickcount;
    }

    /**
     * Set catOtherName
     *
     * @param string $catOtherName
     * @return EsProduct
     */
    public function setCatOtherName($catOtherName)
    {
        $this->catOtherName = $catOtherName;

        return $this;
    }

    /**
     * Get catOtherName
     *
     * @return string 
     */
    public function getCatOtherName()
    {
        return $this->catOtherName;
    }

    /**
     * Set brandOtherName
     *
     * @param string $brandOtherName
     * @return EsProduct
     */
    public function setBrandOtherName($brandOtherName)
    {
        $this->brandOtherName = $brandOtherName;

        return $this;
    }

    /**
     * Get brandOtherName
     *
     * @return string 
     */
    public function getBrandOtherName()
    {
        return $this->brandOtherName;
    }

    /**
     * Set isDraft
     *
     * @param boolean $isDraft
     * @return EsProduct
     */
    public function setIsDraft($isDraft)
    {
        $this->isDraft = $isDraft;

        return $this;
    }

    /**
     * Get isDraft
     *
     * @return boolean 
     */
    public function getIsDraft()
    {
        return $this->isDraft;
    }

    /**
     * Set billingInfoId
     *
     * @param integer $billingInfoId
     * @return EsProduct
     */
    public function setBillingInfoId($billingInfoId)
    {
        $this->billingInfoId = $billingInfoId;

        return $this;
    }

    /**
     * Get billingInfoId
     *
     * @return integer 
     */
    public function getBillingInfoId()
    {
        return $this->billingInfoId;
    }

    /**
     * Set isCod
     *
     * @param boolean $isCod
     * @return EsProduct
     */
    public function setIsCod($isCod)
    {
        $this->isCod = $isCod;

        return $this;
    }

    /**
     * Get isCod
     *
     * @return boolean 
     */
    public function getIsCod()
    {
        return $this->isCod;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return EsProduct
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
     * Set discount
     *
     * @param string $discount
     * @return EsProduct
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return EsProduct
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return EsProduct
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set promoType
     *
     * @param integer $promoType
     * @return EsProduct
     */
    public function setPromoType($promoType)
    {
        $this->promoType = $promoType;

        return $this;
    }

    /**
     * Get promoType
     *
     * @return integer 
     */
    public function getPromoType()
    {
        return $this->promoType;
    }

    /**
     * Set isSoldOut
     *
     * @param boolean $isSoldOut
     * @return EsProduct
     */
    public function setIsSoldOut($isSoldOut)
    {
        $this->isSoldOut = $isSoldOut;

        return $this;
    }

    /**
     * Get isSoldOut
     *
     * @return boolean 
     */
    public function getIsSoldOut()
    {
        return $this->isSoldOut;
    }

    /**
     * Set isMeetup
     *
     * @param boolean $isMeetup
     * @return EsProduct
     */
    public function setIsMeetup($isMeetup)
    {
        $this->isMeetup = $isMeetup;

        return $this;
    }

    /**
     * Get isMeetup
     *
     * @return boolean 
     */
    public function getIsMeetup()
    {
        return $this->isMeetup;
    }

    /**
     * Set brand
     *
     * @param \EasyShop\Entities\EsBrand $brand
     * @return EsProduct
     */
    public function setBrand(\EasyShop\Entities\EsBrand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \EasyShop\Entities\EsBrand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set cat
     *
     * @param \EasyShop\Entities\EsCat $cat
     * @return EsProduct
     */
    public function setCat(\EasyShop\Entities\EsCat $cat = null)
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return \EasyShop\Entities\EsCat 
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsProduct
     */
    public function setMember(\EasyShop\Entities\EsMember $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set style
     *
     * @param \EasyShop\Entities\EsStyle $style
     * @return EsProduct
     */
    public function setStyle(\EasyShop\Entities\EsStyle $style = null)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return \EasyShop\Entities\EsStyle 
     */
    public function getStyle()
    {
        return $this->style;
    }
    
    /**
     * Set isStartPromo
     *
     * @param bool $isStart
     */
    public function setStartPromo($isStart)
    {
        $this->startPromo = $isStart;
    }
    
    /**
     * Get isStartPromo
     *
     * @return bool
     */
    public function getStartPromo()
    {
        return $this->startPromo;
    }
    
    /**
     * Set isEndPromo
     *
     * @param bool $isStart
     */
    public function setEndPromo($isEnd)
    {
        $this->endPromo = $isEnd;
    }
    
    /**
     * Get isEndPromo
     *
     * @return bool
     */
    public function getEndPromo()
    {
        return $this->endPromo;
    }
    
    /**
     *  Set $originalPrice
     *
     *  @param string $originalPrice
     */
    public function setOriginalPrice($originalPrice)
    {
        $this->originalPrice = $originalPrice;
    }
    
    /**
     *  Get $originalPrice
     *
     *  @return string
     */
    public function getOriginalPrice()
    {
        return $this->originalPrice;
    }
    
    /**
     *  Set the final price
     *
     *  @param string $finalPrice
     */
    public function setFinalPrice($finalPrice)
    {
        $this->finalPrice = $finalPrice;
    }
    
    /**
     *  Get finalPrice
     *
     *  @return string
     */
    public function getFinalPrice()
    {
        return $this->finalPrice;
    }
    
    
    /**
     *  Set $discountPercentage
     *
     *  @param string $discountPercentage
     */
    public function setDiscountPercentage($discountPercentage)
    {
        $this->discountPercentage = $discountPercentage;
    }
    
    /**
     *  Get $discountPercentage
     *
     *  @return string
     */
    public function getDiscountPercentage()
    {
        return $this->discountPercentage;
    }
    
    
    /**
     *  Set $isFreeShipping
     *
     *  @param bool $isFreeShipping
     */
    public function setIsFreeShipping($isFreeShipping)
    {
        $this->isFreeShipping = $isFreeShipping;
    }
    
    /**
     *  Get $isFreeShipping
     *
     *  @return bool
     */
    public function getIsFreeShipping()
    {
        return $this->isFreeShipping;
    }
    
    /**
     *  Set $soldPrice
     *
     *  @param string $soldPrice
     */
    public function setSoldPrice($soldPrice)
    {
        $this->soldPrice = $soldPrice;
    }
    
    /**
     *  Get $soldPrice
     *
     *  @return string
     */
    public function getSoldPrice()
    {
        return $this->soldPrice;
    }
    

    /**
     * Set maxAllowablePoint
     *
     * @param string $maxAllowablePoint
     * @return EsProduct
     */
    public function setMaxAllowablePoint($maxAllowablePoint)
    {
        $this->maxAllowablePoint = $maxAllowablePoint;

        return $this;
    }

    /**
     * Get maxAllowablePoint
     *
     * @return string 
     */
    public function getMaxAllowablePoint()
    {
        return $this->maxAllowablePoint;
    }
    
    
    /**
     * Set the default image
     *
     * @param EasyShop\Entities\EsProductImage $defaultImage
     */
    public function setDefaultImage($defaultImage)
    {
        $this->defaultImage = $defaultImage;
    }
    
    /**
     * Returns the default image
     *
     * @return EasyShop\Entities\EsProductImage
     */
    public function getDefaultImage()
    {
        return $this->defaultImage;
    }
    
    /** 
     * Returns the images of a product
     *
     * @return EasyShop\Entities\EsProductImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Get shippedWithinCount
     *
     * @return integer 
     */
    public function getShipsWithinDays()
    {
        return $this->shipsWithinDays;
    }
    
    /**
     * Set shipsWithinDays
     *
     * @param integer $shipsWithinDays
     * @return EsProduct
     */
    public function setShipsWithinDays($shipsWithinDays)
    {
        $this->shipsWithinDays = $shipsWithinDays;

        return $this;
    }
        
}
