<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderPoints
 *
 * @ORM\Table(name="es_order_points", indexes={@ORM\Index(name="fk_es_order_points_1_idx", columns={"order_product_id"})})
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
     * @var string
     *
     * @ORM\Column(name="points", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $points;

    /**
     * @var \EasyShop\Entities\EsOrderProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrderProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_product_id", referencedColumnName="id_order_product")
     * })
     */
    private $orderProduct;



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
     * Set points
     *
     * @param string $points
     * @return EsOrderPoints
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return string 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set orderProduct
     *
     * @param \EasyShop\Entities\EsOrderProduct $orderProduct
     * @return EsOrderPoints
     */
    public function setOrderProduct(\EasyShop\Entities\EsOrderProduct $orderProduct = null)
    {
        $this->orderProduct = $orderProduct;

        return $this;
    }

    /**
     * Get orderProduct
     *
     * @return \EasyShop\Entities\EsOrderProduct 
     */
    public function getOrderProduct()
    {
        return $this->orderProduct;
    }
}
