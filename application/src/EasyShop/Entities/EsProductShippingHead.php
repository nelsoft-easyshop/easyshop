<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductShippingHead
 *
 * @ORM\Table(name="es_product_shipping_head", indexes={@ORM\Index(name="location_id", columns={"location_id"}), @ORM\Index(name="fk_es_product_shiiping_head_es_product_idx", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductShippingHeadRepository")
 */
class EsProductShippingHead
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_shipping", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShipping;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $price = '0.0000';

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
     * @var \EasyShop\Entities\EsLocationLookup
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsLocationLookup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="location_id", referencedColumnName="id_location")
     * })
     */
    private $location;



    /**
     * Get idShipping
     *
     * @return integer 
     */
    public function getIdShipping()
    {
        return $this->idShipping;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return EsProductShippingHead
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsProductShippingHead
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
     * Set location
     *
     * @param \EasyShop\Entities\EsLocationLookup $location
     * @return EsProductShippingHead
     */
    public function setLocation(\EasyShop\Entities\EsLocationLookup $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \EasyShop\Entities\EsLocationLookup 
     */
    public function getLocation()
    {
        return $this->location;
    }
}
