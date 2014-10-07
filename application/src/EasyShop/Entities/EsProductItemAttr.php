<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductItemAttr
 *
 * @ORM\Table(name="es_product_item_attr", indexes={@ORM\Index(name="fk_es_product_item_attr_es_product_item_idx", columns={"product_id_item"}), @ORM\Index(name="fk_es_product_item_attr_es_product_attr_idx", columns={"product_attr_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductItemAttrRepository")
 */
class EsProductItemAttr
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_product_item_attr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductItemAttr;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_attr_id", type="integer", nullable=false)
     */
    private $productAttrId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_other", type="boolean", nullable=false)
     */
    private $isOther = '0';

    /**
     * @var \EasyShop\Entities\EsProductItem
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProductItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id_item", referencedColumnName="id_product_item")
     * })
     */
    private $productItem;



    /**
     * Get idProductItemAttr
     *
     * @return integer 
     */
    public function getIdProductItemAttr()
    {
        return $this->idProductItemAttr;
    }

    /**
     * Set productAttrId
     *
     * @param integer $productAttrId
     * @return EsProductItemAttr
     */
    public function setProductAttrId($productAttrId)
    {
        $this->productAttrId = $productAttrId;

        return $this;
    }

    /**
     * Get productAttrId
     *
     * @return integer 
     */
    public function getProductAttrId()
    {
        return $this->productAttrId;
    }

    /**
     * Set isOther
     *
     * @param boolean $isOther
     * @return EsProductItemAttr
     */
    public function setIsOther($isOther)
    {
        $this->isOther = $isOther;

        return $this;
    }

    /**
     * Get isOther
     *
     * @return boolean 
     */
    public function getIsOther()
    {
        return $this->isOther;
    }

    /**
     * Set productItem
     *
     * @param \EasyShop\Entities\EsProductItem $productItem
     * @return EsProductItemAttr
     */
    public function setProductItem(\EasyShop\Entities\EsProductItem $productItem = null)
    {
        $this->productItem = $productItem;

        return $this;
    }

    /**
     * Get productItem
     *
     * @return \EasyShop\Entities\EsProductItem 
     */
    public function getProductItem()
    {
        return $this->productItem;
    }
}
