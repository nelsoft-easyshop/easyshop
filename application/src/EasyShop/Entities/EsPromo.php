<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPromo
 *
 * @ORM\Table(name="es_promo")
 * @ORM\Entity
 */
class EsPromo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_promo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPromo;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_id", type="integer", nullable=true)
     */
    private $memberId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=true)
     */
    private $productId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="promo_type", type="boolean", nullable=true)
     */
    private $promoType = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt = '0000-00-00 00:00:00';



    /**
     * Get idPromo
     *
     * @return integer 
     */
    public function getIdPromo()
    {
        return $this->idPromo;
    }

    /**
     * Set memberId
     *
     * @param integer $memberId
     * @return EsPromo
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * Get memberId
     *
     * @return integer 
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     * @return EsPromo
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return EsPromo
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set promoType
     *
     * @param boolean $promoType
     * @return EsPromo
     */
    public function setPromoType($promoType)
    {
        $this->promoType = $promoType;

        return $this;
    }

    /**
     * Get promoType
     *
     * @return boolean 
     */
    public function getPromoType()
    {
        return $this->promoType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return EsPromo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
