<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductShippingComment
 *
 * @ORM\Table(name="es_product_shipping_comment", uniqueConstraints={@ORM\UniqueConstraint(name="Unique Pair", columns={"order_product_id", "member_id"})}, indexes={@ORM\Index(name="sdfs_idx", columns={"member_id"}), @ORM\Index(name="IDX_46E797E6F65E9B0F", columns={"order_product_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductShippingCommentRepository")
 */
class EsProductShippingComment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_shipping_comment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShippingComment;

    /**
     * @var string
     *
     * @ORM\Column(name="courier", type="string", length=45, nullable=true)
     */
    private $courier = '';

    /**
     * @var string
     *
     * @ORM\Column(name="tracking_num", type="string", length=45, nullable=true)
     */
    private $trackingNum = '';

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=450, nullable=false)
     */
    private $comment = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expected_date", type="datetime", nullable=true)
     */
    private $expectedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodified", type="datetime", nullable=false)
     */
    private $datemodified = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="datetime", nullable=false)
     */
    private $deliveryDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \EasyShop\Entities\EsOrderProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrderProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_product_id", referencedColumnName="id_order_product")
     * })
     */
    private $orderProduct;

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
     * Get idShippingComment
     *
     * @return integer
     */
    public function getIdShippingComment()
    {
        return $this->idShippingComment;
    }

    /**
     * Set courier
     *
     * @param string $courier
     * @return EsProductShippingComment
     */
    public function setCourier($courier)
    {
        $this->courier = $courier;

        return $this;
    }

    /**
     * Get courier
     *
     * @return string
     */
    public function getCourier()
    {
        return $this->courier;
    }

    /**
     * Set trackingNum
     *
     * @param string $trackingNum
     * @return EsProductShippingComment
     */
    public function setTrackingNum($trackingNum)
    {
        $this->trackingNum = $trackingNum;

        return $this;
    }

    /**
     * Get trackingNum
     *
     * @return string
     */
    public function getTrackingNum()
    {
        return $this->trackingNum;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return EsProductShippingComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set expectedDate
     *
     * @param \DateTime $expectedDate
     * @return EsProductShippingComment
     */
    public function setExpectedDate($expectedDate)
    {
        $this->expectedDate = $expectedDate;

        return $this;
    }

    /**
     * Get expectedDate
     *
     * @return \DateTime
     */
    public function getExpectedDate()
    {
        return $this->expectedDate;
    }

    /**
     * Set datemodified
     *
     * @param \DateTime $datemodified
     * @return EsProductShippingComment
     */
    public function setDatemodified($datemodified)
    {
        $this->datemodified = $datemodified;

        return $this;
    }

    /**
     * Get datemodified
     *
     * @return \DateTime
     */
    public function getDatemodified()
    {
        return $this->datemodified;
    }

    /**
     * Set deliveryDate
     *
     * @param \DateTime $deliveryDate
     * @return EsProductShippingComment
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set orderProduct
     *
     * @param \EasyShop\Entities\EsOrderProduct $orderProduct
     * @return EsProductShippingComment
     */
    public function setOrderProduct(\EasyShop\Entities\EsOrderProduct $orderProduct = null)
    {
        $this->orderProduct = $orderProduct;

        return $this;
    }

    /**
     * Get orderProduct
     *
     * @return \EasyShop\Entities\EsOrderProduct
     */
    public function getOrderProduct()
    {
        return $this->orderProduct;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsProductShippingComment
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
