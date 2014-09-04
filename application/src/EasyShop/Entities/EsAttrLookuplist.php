<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsAttrLookuplist
 *
 * @ORM\Table(name="es_attr_lookuplist")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsAttrLookuplistRepository")
 */
class EsAttrLookuplist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_attr_lookuplist", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAttrLookuplist;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;



    /**
     * Get idAttrLookuplist
     *
     * @return integer 
     */
    public function getIdAttrLookuplist()
    {
        return $this->idAttrLookuplist;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsAttrLookuplist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
