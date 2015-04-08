<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderHistory
 *
 * @ORM\Table(name="es_order_history", indexes={@ORM\Index(name="fk_es_order_es_order_history_idx", columns={"order_id"}), @ORM\Index(name="fk_es_order_status_es_order_history_idx", columns={"order_status"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderHistoryRepository")
 */
class EsOrderHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_history", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderHistory;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    private $comment = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded = 'CURRENT_TIMESTAMP';

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
     * @var \EasyShop\Entities\EsOrderStatus
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrderStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_status", referencedColumnName="order_status")
     * })
     */
    private $orderStatus;



    /**
     * Get idOrderHistory
     *
     * @return integer 
     */
    public function getIdOrderHistory()
    {
        return $this->idOrderHistory;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return EsOrderHistory
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return EsOrderHistory
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set order
     *
     * @param \EasyShop\Entities\EsOrder $order
     * @return EsOrderHistory
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
     * Set orderStatus
     *
     * @param \EasyShop\Entities\EsOrderStatus $orderStatus
     * @return EsOrderHistory
     */
    public function setOrderStatus(\EasyShop\Entities\EsOrderStatus $orderStatus = null)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return \EasyShop\Entities\EsOrderStatus 
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }
}
