<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsLocationLookup
 *
 * @ORM\Table(name="es_location_lookup", indexes={@ORM\Index(name="fk_es_location_lookup_es_location_lookup_idx", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsLocationLookupRepository")
 */
class EsLocationLookup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_location", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=45, nullable=false)
     */
    private $location = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type = '0';

    /**
     * @var \EasyShop\Entities\EsLocationLookup
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsLocationLookup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id_location")
     * })
     */
    private $parent;

    /**
     *  @var integer
     */
    const TYPE_COUNTRY = 0;

    /**
     *  @var integer
     */
    const TYPE_STATEREGION = 3;

    /**
     *  @var integer
     */
    const TYPE_CITY = 4;

    /**
     *  @var integer
     */
    const DEFAULT_REGION = 39;

    /**
     *  @var integer
     */
    const PHILIPPINES_LOCATION_ID = 1;

    /**
     * Get idLocation
     *
     * @return integer 
     */
    public function getIdLocation()
    {
        return $this->idLocation;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return EsLocationLookup
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return EsLocationLookup
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parent
     *
     * @param \EasyShop\Entities\EsLocationLookup $parent
     * @return EsLocationLookup
     */
    public function setParent(\EasyShop\Entities\EsLocationLookup $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \EasyShop\Entities\EsLocationLookup 
     */
    public function getParent()
    {
        return $this->parent;
    }
}
