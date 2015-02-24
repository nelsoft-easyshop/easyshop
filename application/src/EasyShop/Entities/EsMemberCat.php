<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMemberCat
 *
 * @ORM\Table(name="es_member_cat", indexes={@ORM\Index(name="fk_es_member_cat_1_idx", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsMemberCatRepository")
 */
class EsMemberCat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_memcat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMemcat;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_name", type="string", length=45, nullable=true)
     */
    private $catName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_featured", type="boolean", nullable=false)
     */
    private $isFeatured = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate = 'CURRENT_TIMESTAMP';

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
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=false)
     */
    private $sortOrder = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", nullable=false)
     */
    private $isDelete = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_date_modified", type="datetime", nullable=false)
     */
    private $lastModifiedDate = 'CURRENT_TIMESTAMP';    

    /**
     *
     *  @var int
     */
    const DEFAULT_IS_DELETE = 0;

    /**
     * Get idMemcat
     *
     * @return integer 
     */
    public function getIdMemcat()
    {
        return $this->idMemcat;
    }

    /**
     * Set catName
     *
     * @param string $catName
     * @return EsMemberCat
     */
    public function setCatName($catName)
    {
        $this->catName = $catName;

        return $this;
    }

    /**
     * Get catName
     *
     * @return string 
     */
    public function getCatName()
    {
        return $this->catName;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     * @return EsMemberCat
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return boolean 
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return EsMemberCat
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsMemberCat
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
     * Set sortOrder
     *
     * @param integer $sortOrder
     * @return EsMemberCat
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }


    /**
     * Set lastModifiedDate
     *
     * @param \DateTime $lastModifiedDate
     * @return EsMemberCat
     */
    public function setlastModifiedDate($lastModifiedDate)
    {
        $this->lastModifiedDate = $lastModifiedDate;

        return $this;
    }

    /**
     * Get lastModifiedDate
     *
     * @return \DateTime 
     */
    public function getlastModifiedDate()
    {
        return $this->lastModifiedDate;
    }

    /**
     * Get IsDelete
     *
     * @return boolean 
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * Set isDelete
     *
     * @param integer $isDelete
     * @return EsMemberCat
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;
        return $this;
    }    

    /**
     * Get sortOrder
     *
     * @return integer 
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

}


