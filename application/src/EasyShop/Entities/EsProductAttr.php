<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductAttr
 *
 * @ORM\Table(name="es_product_attr", indexes={@ORM\Index(name="product_id", columns={"product_id"}), @ORM\Index(name="fk_es_product_attr_es_attr1_idx", columns={"attr_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductAttrRepository")
 */
class EsProductAttr
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_product_attr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductAttr;

    /**
     * @var string
     *
     * @ORM\Column(name="attr_value", type="text", nullable=false)
     */
    private $attrValue;

    /**
     * @var string
     *
     * @ORM\Column(name="attr_price", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $attrPrice = '0.0000';

    /**
     * @var \EasyShop\Entities\EsAttr
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsAttr")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attr_id", referencedColumnName="id_attr")
     * })
     */
    private $attr;

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
     * Get idProductAttr
     *
     * @return integer 
     */
    public function getIdProductAttr()
    {
        return $this->idProductAttr;
    }

    /**
     * Set attrValue
     *
     * @param string $attrValue
     * @return EsProductAttr
     */
    public function setAttrValue($attrValue)
    {
        $this->attrValue = $attrValue;

        return $this;
    }

    /**
     * Get attrValue
     *
     * @return string 
     */
    public function getAttrValue()
    {
        return $this->attrValue;
    }

    /**
     * Set attrPrice
     *
     * @param string $attrPrice
     * @return EsProductAttr
     */
    public function setAttrPrice($attrPrice)
    {
        $this->attrPrice = $attrPrice;

        return $this;
    }

    /**
     * Get attrPrice
     *
     * @return string 
     */
    public function getAttrPrice()
    {
        return $this->attrPrice;
    }

    /**
     * Set attr
     *
     * @param \EasyShop\Entities\EsAttr $attr
     * @return EsProductAttr
     */
    public function setAttr(\EasyShop\Entities\EsAttr $attr = null)
    {
        $this->attr = $attr;

        return $this;
    }

    /**
     * Get attr
     *
     * @return \EasyShop\Entities\EsAttr 
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsProductAttr
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
}
