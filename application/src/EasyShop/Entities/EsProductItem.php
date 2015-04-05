<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductItem
 *
 * @ORM\Table(name="es_product_item", indexes={@ORM\Index(name="fk_es_product_item_es_product_idx", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductItemRepository")
 */
class EsProductItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_product_item", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductItem;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var \EasyShop\Entities\EsProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     * })
     */
    private $product;

    const MAX_QUANTITY = 9999;

    /**
     * Get idProductItem
     *
     * @return integer 
     */
    public function getIdProductItem()
    {
        return $this->idProductItem;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return EsProductItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsProductItem
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
