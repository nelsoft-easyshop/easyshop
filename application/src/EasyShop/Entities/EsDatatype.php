<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsDatatype
 *
 * @ORM\Table(name="es_datatype")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsDatatypeRepository")
 */
class EsDatatype
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_datatype", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDatatype;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;



    /**
     * Get idDatatype
     *
     * @return integer 
     */
    public function getIdDatatype()
    {
        return $this->idDatatype;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsDatatype
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
