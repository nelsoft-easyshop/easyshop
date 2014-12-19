<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsKeywordsTemp
 *
 * @ORM\Table(name="es_keywords_temp")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsKeywordsTemp")
 */
class EsKeywordsTemp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_keywords_temp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idKeywordsTemp;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=1024, nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=20, nullable=false)
     */
    private $ipAddress = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     */
    private $timestamp = 'CURRENT_TIMESTAMP';



    /**
     * Get idKeywordsTemp
     *
     * @return integer 
     */
    public function getIdKeywordsTemp()
    {
        return $this->idKeywordsTemp;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return EsKeywordsTemp
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return EsKeywordsTemp
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return EsKeywordsTemp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
