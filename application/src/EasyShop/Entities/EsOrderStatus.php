<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderStatus
 *
 * @ORM\Table(name="es_order_status")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderStatusRepository")
 */
class EsOrderStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="order_status", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=true)
     */
    private $name = '';


    /**
     * @var integer
     */
    const STATUS_PAID = 0;
    
    /**
     * @var integer
     */
    const STATUS_COMPLETED = 1;
    
    /**
     * @var integer
     */
    const STATUS_VOID = 2;
    
    /**
     * @var integer
     */
    const STATUS_DRAFT = 99;


    /**
     * Get orderStatus
     *
     * @return integer 
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsOrderStatus
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
