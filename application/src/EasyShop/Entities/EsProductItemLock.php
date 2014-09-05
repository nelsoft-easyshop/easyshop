<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductItemLock
 *
 * @ORM\Table(name="es_product_item_lock", indexes={@ORM\Index(name="fk_es_product_item_idx", columns={"product_item_id"}), @ORM\Index(name="fk_esPorudct_item_lock_es_order_idx", columns={"order_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductItemLockRepository")
 */
class EsProductItemLock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_item_lock", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idItemLock;

    /**
     * @var integer
     *
     * @ORM\Column(name="qty", type="integer", nullable=true)
     */
    private $qty = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

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
     * @var \EasyShop\Entities\EsProductItem
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProductItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_item_id", referencedColumnName="id_product_item")
     * })
     */
    private $productItem;



    /**
     * Get idItemLock
     *
     * @return integer 
     */
    public function getIdItemLock()
    {
        return $this->idItemLock;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     * @return EsProductItemLock
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return integer 
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return EsProductItemLock
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set order
     *
     * @param \EasyShop\Entities\EsOrder $order
     * @return EsProductItemLock
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

    /**
     * Set productItem
     *
     * @param \EasyShop\Entities\EsProductItem $productItem
     * @return EsProductItemLock
     */
    public function setProductItem(\EasyShop\Entities\EsProductItem $productItem = null)
    {
        $this->productItem = $productItem;

        return $this;
    }

    /**
     * Get productItem
     *
     * @return \EasyShop\Entities\EsProductItem 
     */
    public function getProductItem()
    {
        return $this->productItem;
    }
}
