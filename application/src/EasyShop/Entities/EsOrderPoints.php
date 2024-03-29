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
     * @var string
     *
     * @ORM\Column(name="unit_points", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $unitPoints;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_revert", type="boolean", nullable=true)
     */
    private $isRevert = '0';

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

    /**
     * Set unitPoints
     *
     * @param string $unitPoints
     * @return EsOrderPoints
     */
    public function setUnitPoints($unitPoints)
    {
        $this->unitPoints = $unitPoints;

        return $this;
    }

    /**
     * Get unitPoints
     *
     * @return string
     */
    public function getUnitPoints()
    {
        return $this->unitPoints;
    }

    /**
     * Set isRevert
     *
     * @param boolean $isRevert
     * @return EsOrderPoints
     */
    public function setIsRevert($isRevert)
    {
        $this->isRevert = $isRevert;

        return $this;
    }

    /**
     * Get isRevert
     *
     * @return boolean 
     */
    public function getIsRevert()
    {
        return $this->isRevert;
    }
}
