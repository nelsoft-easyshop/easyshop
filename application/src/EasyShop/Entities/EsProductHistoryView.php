<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductHistoryView
 *
 * @ORM\Table(name="es_product_history_view", indexes={@ORM\Index(name="fk_es_product_history_view_1_idx", columns={"product_id"}), @ORM\Index(name="fk_es_product_history_view_1_idx1", columns={"member_id"})})
 * @ORM\Entity
 */
class EsProductHistoryView
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_product_history_view", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductHistoryView;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=25, nullable=true)
     */
    private $ipAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_viewed", type="datetime", nullable=true)
     */
    private $dateViewed;

    /**
     * @var \EasyShop\Entities\EsProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     * })
     */
    private $product;

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
     * Get idProductHistoryView
     *
     * @return integer 
     */
    public function getIdProductHistoryView()
    {
        return $this->idProductHistoryView;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return EsProductHistoryView
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
     * Set dateViewed
     *
     * @param \DateTime $dateViewed
     * @return EsProductHistoryView
     */
    public function setDateViewed($dateViewed)
    {
        $this->dateViewed = $dateViewed;

        return $this;
    }

    /**
     * Get dateViewed
     *
     * @return \DateTime 
     */
    public function getDateViewed()
    {
        return $this->dateViewed;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsProductHistoryView
     */
    public function setProduct(\EasyShop\Entities\EsProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \EasyShop\Entities\EsProduct 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsProductHistoryView
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
}
