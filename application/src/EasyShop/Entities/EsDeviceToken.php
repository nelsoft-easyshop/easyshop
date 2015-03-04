<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsDeviceToken
 *
 * @ORM\Table(name="es_device_token", indexes={@ORM\Index(name="fk_es_device_token_1_idx", columns={"api_type"})})
 * @ORM\Entity
 */
class EsDeviceToken
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_device_token", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDeviceToken;

    /**
     * @var string
     *
     * @ORM\Column(name="device_token", type="string", length=100, nullable=false)
     */
    private $deviceToken;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateadded", type="datetime", nullable=true)
     */
    private $dateadded = 'CURRENT_TIMESTAMP';

    /**
     * @var \EasyShop\Entities\EsApiType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsApiType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="api_type", referencedColumnName="id_api_type")
     * })
     */
    private $apiType;


    const DEFAULT_ACTIVE = 1;

    /**
     * Get idDeviceToken
     *
     * @return integer 
     */
    public function getIdDeviceToken()
    {
        return $this->idDeviceToken;
    }

    /**
     * Set deviceToken
     *
     * @param string $deviceToken
     * @return EsDeviceToken
     */
    public function setDeviceToken($deviceToken)
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * Get deviceToken
     *
     * @return string 
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return EsDeviceToken
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set dateadded
     *
     * @param \DateTime $dateadded
     * @return EsDeviceToken
     */
    public function setDateadded($dateadded)
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    /**
     * Get dateadded
     *
     * @return \DateTime 
     */
    public function getDateadded()
    {
        return $this->dateadded;
    }

    /**
     * Set apiType
     *
     * @param \EasyShop\Entities\EsApiType $apiType
     * @return EsDeviceToken
     */
    public function setApiType(\EasyShop\Entities\EsApiType $apiType = null)
    {
        $this->apiType = $apiType;

        return $this;
    }

    /**
     * Get apiType
     *
     * @return \EasyShop\Entities\EsApiType 
     */
    public function getApiType()
    {
        return $this->apiType;
    }
}
