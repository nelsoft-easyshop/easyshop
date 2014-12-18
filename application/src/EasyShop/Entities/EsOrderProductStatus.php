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

    const ON_GOING = 0;

    const FORWARD_SELLER = 1;

    const RETURNED_BUYER = 2;

    const CASH_ON_DELIVERY = 3;

    const PAID_FORWARDED = 4;

    const PAID_RETURNED = 5;

    const CANCEL = 6;

    /**
     * @var integer
     */
    const STATUS_REJECT = 99;

    const IS_REJECT_ACTIVE = 1;

    const IS_REJECT_NOT_ACTIVE = 0;

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
