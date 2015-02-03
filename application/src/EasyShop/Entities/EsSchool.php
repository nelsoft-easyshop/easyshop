<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsSchool
 *
 * @ORM\Table(name="es_school")
 * @ORM\Entity
 */
class EsSchool
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_school", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name = '';



    /**
     * Get idSchool
     *
     * @return integer 
     */
    public function getIdSchool()
    {
        return $this->idSchool;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsSchool
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
