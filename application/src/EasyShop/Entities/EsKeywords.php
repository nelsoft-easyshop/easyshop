<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsKeywords
 *
 * @ORM\Table(name="es_keywords", indexes={@ORM\Index(name="fulltext", columns={"keywords"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsKeywordsRepository")
 */
class EsKeywords
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_keywords", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    private $keywords;



    /**
     * Get idKeywords
     *
     * @return integer 
     */
    public function getIdKeywords()
    {
        return $this->idKeywords;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return EsKeywords
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
