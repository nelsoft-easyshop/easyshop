<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderPoints
 *
 * @ORM\Table(name="es_order_points", indexes={@ORM\Index(name="fk_es_order_points_1_idx", columns={"order_id"})})
 * @ORM\Entity
 */
class EsOrderPoints
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_points", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit_points", type="integer", nullable=false)
     */
    private $creditPoints;

    /**
     * @var \EasyShop\Entities\EsOrder
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id_order")
     * })
     */
    private $order;



    /**
     * Get idOrderPoints
     *
     * @return integer 
     */
    public function getIdOrderPoints()
    {
        return $this->idOrderPoints;
    }

    /**
     * Set creditPoints
     *
     * @param integer $creditPoints
     * @return EsOrderPoints
     */
    public function setCreditPoints($creditPoints)
    {
        $this->creditPoints = $creditPoints;

        return $this;
    }

    /**
     * Get creditPoints
     *
     * @return integer 
     */
    public function getCreditPoints()
    {
        return $this->creditPoints;
    }

    /**
     * Set order
     *
     * @param \EasyShop\Entities\EsOrder $order
     * @return EsOrderPoints
     */
    public function setOrder(\EasyShop\Entities\EsOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \EasyShop\Entities\EsOrder 
     */
    public function getOrder()
    {
        return $this->order;
    }
}
