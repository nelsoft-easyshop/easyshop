<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsStyle
 *
 * @ORM\Table(name="es_style")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsStyleRepository")
 */
class EsStyle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_style", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=512, nullable=false)
     */
    private $value = '';

    const DEFAULT_STYLE_ID = 1;



    /**
     * Get idStyle
     *
     * @return integer 
     */
    public function getIdStyle()
    {
        return $this->idStyle;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsStyle
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
     * Set value
     *
     * @param string $value
     * @return EsStyle
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
