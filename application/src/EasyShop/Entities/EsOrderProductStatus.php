<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderProductStatus
 *
 * @ORM\Table(name="es_order_product_status")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderProductStatusRepository")
 */
class EsOrderProductStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_product_status", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderProductStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;



    /**
     * Get idOrderProductStatus
     *
     * @return integer 
     */
    public function getIdOrderProductStatus()
    {
        return $this->idOrderProductStatus;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsOrderProductStatus
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
