<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsKeywords
 *
 * @ORM\Table(name="es_keywords", indexes={@ORM\Index(name="ft_es_keywords_idx", columns={"keywords"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsKeywordsRepository")
 */
class EsKeywords
{
    const SUGGESTION_LIMIT = 12;

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
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=true)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="occurences", type="integer", nullable=false)
     */
    private $occurences = '0';



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

    /**
     * Set occurences
     *
     * @param integer $occurences
     * @return EsKeywords
     */
    public function setOccurences($occurences)
    {
        $this->occurences = $occurences;

        return $this;
    }

    /**
     * Get occurences
     *
     * @return integer 
     */
    public function getOccurences()
    {
        return $this->occurences;
    }
}
