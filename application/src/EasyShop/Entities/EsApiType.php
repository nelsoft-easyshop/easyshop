<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsApiType
 *
 * @ORM\Table(name="es_api_type")
 * @ORM\Entity
 */
class EsApiType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_api_type", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idApiType;

    /**
     * @var string
     *
     * @ORM\Column(name="api_type", type="string", length=45, nullable=false)
     */
    private $apiType;

    const TYPE_IOS = 1;
    const TYPE_ANDROID = 2;

    /**
     * Get idApiType
     *
     * @return integer 
     */
    public function getIdApiType()
    {
        return $this->idApiType;
    }

    /**
     * Set apiType
     *
     * @param string $apiType
     * @return EsApiType
     */
    public function setApiType($apiType)
    {
        $this->apiType = $apiType;

        return $this;
    }

    /**
     * Get apiType
     *
     * @return string 
     */
    public function getApiType()
    {
        return $this->apiType;
    }
}
