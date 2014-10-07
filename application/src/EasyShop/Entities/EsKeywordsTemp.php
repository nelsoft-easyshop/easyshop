<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsKeywordsTemp
 *
 * @ORM\Table(name="es_keywords_temp")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsKeywordsTempRepository")
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
}
