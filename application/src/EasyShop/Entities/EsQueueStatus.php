<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsQueueStatus
 *
 * @ORM\Table(name="es_queue_status")
 * @ORM\Entity
 */
class EsQueueStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_status", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    const QUEUED = 1;
    const SENT = 2;
    const FAILED = 3;

    /**
     * Get idStatus
     *
     * @return integer 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsQueueStatus
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
