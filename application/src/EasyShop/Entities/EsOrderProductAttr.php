<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderProductAttr
 *
 * @ORM\Table(name="es_order_product_attr", indexes={@ORM\Index(name="fk_es_order_product_attr_es_order_product_idx", columns={"order_product_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderProductAttrRepository")
 */
class EsOrderProductAttr
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_option", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderOption;

    /**
     * @var string
     *
     * @ORM\Column(name="attr_name", type="string", length=150, nullable=false)
     */
    private $attrName;

    /**
     * @var string
     *
     * @ORM\Column(name="attr_value", type="string", length=150, nullable=false)
     */
    private $attrValue;

    /**
     * @var string
     *
     * @ORM\Column(name="attr_price", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $attrPrice;

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
     * Get idOrderOption
     *
     * @return integer 
     */
    public function getIdOrderOption()
    {
        return $this->idOrderOption;
    }

    /**
     * Set attrName
     *
     * @param string $attrName
     * @return EsOrderProductAttr
     */
    public function setAttrName($attrName)
    {
        $this->attrName = $attrName;

        return $this;
    }

    /**
     * Get attrName
     *
     * @return string 
     */
    public function getAttrName()
    {
        return $this->attrName;
    }

    /**
     * Set attrValue
     *
     * @param string $attrValue
     * @return EsOrderProductAttr
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
     * @return EsOrderProductAttr
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
     * Set orderProduct
     *
     * @param \EasyShop\Entities\EsOrderProduct $orderProduct
     * @return EsOrderProductAttr
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
}
