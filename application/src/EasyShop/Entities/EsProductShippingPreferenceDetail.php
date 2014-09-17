<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductShippingPreferenceDetail
 *
 * @ORM\Table(name="es_product_shipping_preference_detail", indexes={@ORM\Index(name="edasd_idx", columns={"location_id"}), @ORM\Index(name="es_sp_head_idx", columns={"shipping_pref_head_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductShippingPreferenceDetailRepository")
 */
class EsProductShippingPreferenceDetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_shipping_pref_detail", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShippingPrefDetail;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $price;

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
     * @var \EasyShop\Entities\EsProductShippingPreferenceHead
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProductShippingPreferenceHead")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_pref_head_id", referencedColumnName="id_shipping_pref_head")
     * })
     */
    private $shippingPrefHead;



    /**
     * Get idShippingPrefDetail
     *
     * @return integer 
     */
    public function getIdShippingPrefDetail()
    {
        return $this->idShippingPrefDetail;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return EsProductShippingPreferenceDetail
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
     * Set location
     *
     * @param \EasyShop\Entities\EsLocationLookup $location
     * @return EsProductShippingPreferenceDetail
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

    /**
     * Set shippingPrefHead
     *
     * @param \EasyShop\Entities\EsProductShippingPreferenceHead $shippingPrefHead
     * @return EsProductShippingPreferenceDetail
     */
    public function setShippingPrefHead(\EasyShop\Entities\EsProductShippingPreferenceHead $shippingPrefHead = null)
    {
        $this->shippingPrefHead = $shippingPrefHead;

        return $this;
    }

    /**
     * Get shippingPrefHead
     *
     * @return \EasyShop\Entities\EsProductShippingPreferenceHead 
     */
    public function getShippingPrefHead()
    {
        return $this->shippingPrefHead;
    }
}
