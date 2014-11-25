<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderProductTag
 *
 * @ORM\Table(name="es_order_product_tag", indexes={@ORM\Index(name="fk_es_order_product_tag_1_idx", columns={"tag_type_id"}), @ORM\Index(name="fk_es_order_product_tag_order_product_idx", columns={"order_product_id"}), @ORM\Index(name="fk_es_order_product_tag_admin_member_id_idx", columns={"admin_member_id"}), @ORM\Index(name="fk_es_order_product_tag_seller_id_idx", columns={"seller_id"})})
 * @ORM\Entity
 */
class EsOrderProductTag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_product_tag", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderProductTag;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $dateUpdated = 'CURRENT_TIMESTAMP';

    /**
     * @var \EasyShop\Entities\EsAdminMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsAdminMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_member_id", referencedColumnName="id_admin_member")
     * })
     */
    private $adminMember;

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
     *   @ORM\JoinColumn(name="seller_id", referencedColumnName="id_member")
     * })
     */
    private $seller;

    /**
     * @var \EasyShop\Entities\EsTagType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsTagType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_type_id", referencedColumnName="id_tag_type")
     * })
     */
    private $tagType;



    /**
     * Get idOrderProductTag
     *
     * @return integer 
     */
    public function getIdOrderProductTag()
    {
        return $this->idOrderProductTag;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return EsOrderProductTag
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set adminMember
     *
     * @param \EasyShop\Entities\EsAdminMember $adminMember
     * @return EsOrderProductTag
     */
    public function setAdminMember(\EasyShop\Entities\EsAdminMember $adminMember = null)
    {
        $this->adminMember = $adminMember;

        return $this;
    }

    /**
     * Get adminMember
     *
     * @return \EasyShop\Entities\EsAdminMember 
     */
    public function getAdminMember()
    {
        return $this->adminMember;
    }

    /**
     * Set orderProduct
     *
     * @param \EasyShop\Entities\EsOrderProduct $orderProduct
     * @return EsOrderProductTag
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
     * Set seller
     *
     * @param \EasyShop\Entities\EsMember $seller
     * @return EsOrderProductTag
     */
    public function setSeller(\EasyShop\Entities\EsMember $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set tagType
     *
     * @param \EasyShop\Entities\EsTagType $tagType
     * @return EsOrderProductTag
     */
    public function setTagType(\EasyShop\Entities\EsTagType $tagType = null)
    {
        $this->tagType = $tagType;

        return $this;
    }

    /**
     * Get tagType
     *
     * @return \EasyShop\Entities\EsTagType 
     */
    public function getTagType()
    {
        return $this->tagType;
    }
}
