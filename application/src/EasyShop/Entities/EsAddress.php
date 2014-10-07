<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsAddress
 *
 * @ORM\Table(name="es_address", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE PAIR", columns={"id_member", "type"})}, indexes={@ORM\Index(name="idx_id_member", columns={"id_member"}), @ORM\Index(name="fk_es_location_lookup_es_address_idx", columns={"stateregion", "country", "city"}), @ORM\Index(name="fk_es_location_city_es_address_idx", columns={"city"}), @ORM\Index(name="fk_es_location_es_countyr_es_address_idx", columns={"country"}), @ORM\Index(name="IDX_14E09C7F7C999E6", columns={"stateregion"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsAddressRepository")
 */
class EsAddress
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_address", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=250, nullable=true)
     */
    private $address = '';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=45, nullable=true)
     */
    private $telephone = '';

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=45, nullable=true)
     */
    private $mobile = '';

    /**
     * @var string
     *
     * @ORM\Column(name="consignee", type="string", length=45, nullable=true)
     */
    private $consignee = '';

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
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_member", referencedColumnName="id_member")
     * })
     */
    private $idMember;

    /**
     * @var integer
     */
    const TYPE_DEFAULT = 0;

    /**
     * @var integer
     */
    const TYPE_DELIVERY = 1;


    /**
     * Get idAddress
     *
     * @return integer 
     */
    public function getIdAddress()
    {
        return $this->idAddress;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return EsAddress
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
     * Set type
     *
     * @param string $type
     * @return EsAddress
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return EsAddress
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
     * Set mobile
     *
     * @param string $mobile
     * @return EsAddress
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
     * Set consignee
     *
     * @param string $consignee
     * @return EsAddress
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
     * Set lat
     *
     * @param float $lat
     * @return EsAddress
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
     * @return EsAddress
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
     * @return EsAddress
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
     * @return EsAddress
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
     * @return EsAddress
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

    /**
     * Set idMember
     *
     * @param \EasyShop\Entities\EsMember $idMember
     * @return EsAddress
     */
    public function setIdMember(\EasyShop\Entities\EsMember $idMember = null)
    {
        $this->idMember = $idMember;

        return $this;
    }

    /**
     * Get idMember
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getIdMember()
    {
        return $this->idMember;
    }
}
