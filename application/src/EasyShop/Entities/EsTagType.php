<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsTagType
 *
 * @ORM\Table(name="es_tag_type")
 * @ORM\Entity
 */
class EsTagType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_tag_type", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTagType;

    /**
     * @var string
     *
     * @ORM\Column(name="tag_description", type="string", length=45, nullable=false)
     */
    private $tagDescription;



    /**
     * Get idTagType
     *
     * @return integer 
     */
    public function getIdTagType()
    {
        return $this->idTagType;
    }

    /**
     * Set tagDescription
     *
     * @param string $tagDescription
     * @return EsTagType
     */
    public function setTagDescription($tagDescription)
    {
        $this->tagDescription = $tagDescription;

        return $this;
    }

    /**
     * Get tagDescription
     *
     * @return string 
     */
    public function getTagDescription()
    {
        return $this->tagDescription;
    }
}
