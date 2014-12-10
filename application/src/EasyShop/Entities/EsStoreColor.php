<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsStoreColor
 *
 * @ORM\Table(name="es_store_color")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsStoreColorRepository")
 */
class EsStoreColor
{

    /**
     * @var integer
     * 
     */
    const DEFAULT_COLOR_ID = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_store_color", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStoreColor;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="hexadecimal", type="string", length=45, nullable=false)
     */
    private $hexadecimal;



    /**
     * Get idStoreColor
     *
     * @return integer 
     */
    public function getIdStoreColor()
    {
        return $this->idStoreColor;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsStoreColor
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

    /**
     * Set hexadecimal
     *
     * @param string $hexadecimal
     * @return EsStoreColor
     */
    public function setHexadecimal($hexadecimal)
    {
        $this->hexadecimal = $hexadecimal;

        return $this;
    }

    /**
     * Get hexadecimal
     *
     * @return string 
     */
    public function getHexadecimal()
    {
        return $this->hexadecimal;
    }
}
