<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductShippingDetail
 *
 * @ORM\Table(name="es_product_shipping_detail", indexes={@ORM\Index(name="product_item_id", columns={"product_item_id"}), @ORM\Index(name="shipping_id", columns={"shipping_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductShippingDetailRepository")
 */
class EsProductShippingDetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_shipping_detail", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShippingDetail;

    /**
     * @var \EasyShop\Entities\EsProductItem
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProductItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_item_id", referencedColumnName="id_product_item")
     * })
     */
    private $productItem;

    /**
     * @var \EasyShop\Entities\EsProductShippingHead
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProductShippingHead")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_id", referencedColumnName="id_shipping")
     * })
     */
    private $shipping;



    /**
     * Get idShippingDetail
     *
     * @return integer 
     */
    public function getIdShippingDetail()
    {
        return $this->idShippingDetail;
    }

    /**
     * Set productItem
     *
     * @param \EasyShop\Entities\EsProductItem $productItem
     * @return EsProductShippingDetail
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

    /**
     * Set shipping
     *
     * @param \EasyShop\Entities\EsProductShippingHead $shipping
     * @return EsProductShippingDetail
     */
    public function setShipping(\EasyShop\Entities\EsProductShippingHead $shipping = null)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping
     *
     * @return \EasyShop\Entities\EsProductShippingHead 
     */
    public function getShipping()
    {
        return $this->shipping;
    }
}
