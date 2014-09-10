<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderProductHistory
 *
 * @ORM\Table(name="es_order_product_history", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE", columns={"order_product_status", "order_product_id"})}, indexes={@ORM\Index(name="fk_es_order_product_idx", columns={"order_product_id"}), @ORM\Index(name="IDX_201D83A4710CF408", columns={"order_product_status"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderProductHistoryRepository")
 */
class EsOrderProductHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_product_history", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderProductHistory;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=150, nullable=false)
     */
    private $comment = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=true)
     */
    private $dateAdded = 'CURRENT_TIMESTAMP';

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
     * @var \EasyShop\Entities\EsOrderProductStatus
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrderProductStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_product_status", referencedColumnName="id_order_product_status")
     * })
     */
    private $orderProductStatus;



    /**
     * Get idOrderProductHistory
     *
     * @return integer 
     */
    public function getIdOrderProductHistory()
    {
        return $this->idOrderProductHistory;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return EsOrderProductHistory
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
     * @return EsOrderProductHistory
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
     * Set orderProduct
     *
     * @param \EasyShop\Entities\EsOrderProduct $orderProduct
     * @return EsOrderProductHistory
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
     * Set orderProductStatus
     *
     * @param \EasyShop\Entities\EsOrderProductStatus $orderProductStatus
     * @return EsOrderProductHistory
     */
    public function setOrderProductStatus(\EasyShop\Entities\EsOrderProductStatus $orderProductStatus = null)
    {
        $this->orderProductStatus = $orderProductStatus;

        return $this;
    }

    /**
     * Get orderProductStatus
     *
     * @return \EasyShop\Entities\EsOrderProductStatus 
     */
    public function getOrderProductStatus()
    {
        return $this->orderProductStatus;
    }
}
