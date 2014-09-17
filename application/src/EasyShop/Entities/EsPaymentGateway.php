<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPaymentGateway
 *
 * @ORM\Table(name="es_payment_gateway", indexes={@ORM\Index(name="fk_es_point_gateway_order_id_idx", columns={"order_id"}), @ORM\Index(name="fk_es_point_gateway_payment_method_idx", columns={"payment_method_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsPaymentGatewayRepository")
 */
class EsPaymentGateway
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $amount = '0.0000';

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
     * @var \EasyShop\Entities\EsPaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsPaymentMethod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id_payment_method")
     * })
     */
    private $paymentMethod;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return EsPaymentGateway
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return EsPaymentGateway
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
     * @return EsPaymentGateway
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
     * Set paymentMethod
     *
     * @param \EasyShop\Entities\EsPaymentMethod $paymentMethod
     * @return EsPaymentGateway
     */
    public function setPaymentMethod(\EasyShop\Entities\EsPaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return \EasyShop\Entities\EsPaymentMethod 
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}
