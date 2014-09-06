<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderShippingAddress
 *
 * @ORM\Table(name="es_order_shipping_address", indexes={@ORM\Index(name="fk_es_address_region_idx", columns={"stateregion"}), @ORM\Index(name="fk_es_address_city_idx", columns={"city"}), @ORM\Index(name="fk_es_address_country_idx", columns={"country"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderShippingAddressRepository")
 */
class EsOrderShippingAddress
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_shipping_address", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderShippingAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=250, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee", type="string", length=45, nullable=true)
     */
    private $consignee = '';

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=45, nullable=true)
     */
    private $mobile = '';

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=45, nullable=true)
     */
    private $telephone = '';

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", precision=10, scale=0, nullable=true)
     */
    private $lat = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", precision=10, scale=0, nullable=true)
     */
    private $lng = '0';

    /**
     * @var \EasyShop\Entities\EsLocationLookup
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsLocationLookup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city", referencedColumnName="id_location")
     * })
     */
    private $city;

    /**
     * @var \EasyShop\Entities\EsLocationLookup
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsLocationLookup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="id_location")
     * })
     */
    private $country;

    /**
     * @var \EasyShop\Entities\EsLocationLookup
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsLocationLookup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stateregion", referencedColumnName="id_location")
     * })
     */
    private $stateregion;



    /**
     * Get idOrderShippingAddress
     *
     * @return integer 
     */
    public function getIdOrderShippingAddress()
    {
        return $this->idOrderShippingAddress;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return EsOrderShippingAddress
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set consignee
     *
     * @param string $consignee
     * @return EsOrderShippingAddress
     */
    public function setConsignee($consignee)
    {
        $this->consignee = $consignee;

        return $this;
    }

    /**
     * Get consignee
     *
     * @return string 
     */
    public function getConsignee()
    {
        return $this->consignee;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return EsOrderShippingAddress
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return EsOrderShippingAddress
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return EsOrderShippingAddress
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return EsOrderShippingAddress
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set city
     *
     * @param \EasyShop\Entities\EsLocationLookup $city
     * @return EsOrderShippingAddress
     */
    public function setCity(\EasyShop\Entities\EsLocationLookup $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \EasyShop\Entities\EsLocationLookup 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param \EasyShop\Entities\EsLocationLookup $country
     * @return EsOrderShippingAddress
     */
    public function setCountry(\EasyShop\Entities\EsLocationLookup $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \EasyShop\Entities\EsLocationLookup 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set stateregion
     *
     * @param \EasyShop\Entities\EsLocationLookup $stateregion
     * @return EsOrderShippingAddress
     */
    public function setStateregion(\EasyShop\Entities\EsLocationLookup $stateregion = null)
    {
        $this->stateregion = $stateregion;

        return $this;
    }

    /**
     * Get stateregion
     *
     * @return \EasyShop\Entities\EsLocationLookup 
     */
    public function getStateregion()
    {
        return $this->stateregion;
    }
}
