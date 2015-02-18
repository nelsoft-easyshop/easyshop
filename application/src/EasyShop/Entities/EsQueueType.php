<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsQueueType
 *
 * @ORM\Table(name="es_queue_type")
 * @ORM\Entity
 */
class EsQueueType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_type", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    const TYPE_EMAIL = 1;

    const TYPE_MOBILE = 2;

    /**
     * Get idType
     *
     * @return integer 
     */
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsQueueType
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
