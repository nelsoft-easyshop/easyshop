<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrder
 *
 * @ORM\Table(name="es_order", indexes={@ORM\Index(name="fk_es_order_es_member_idx", columns={"buyer_id"}), @ORM\Index(name="fk_es_order_es_order_status_idx", columns={"order_status"}), @ORM\Index(name="fk_es_order_es_payment_method_idx", columns={"payment_method_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderRepository")
 */
class EsOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_no", type="string", length=45, nullable=true)
     */
    private $invoiceNo = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_address_id", type="integer", nullable=false)
     */
    private $paymentAddressId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="shipping_address_id", type="integer", nullable=false)
     */
    private $shippingAddressId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $total = '0.0000';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateadded", type="datetime", nullable=true)
     */
    private $dateadded = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodified", type="datetime", nullable=true)
     */
    private $datemodified = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=45, nullable=true)
     */
    private $ip = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="data_response", type="text", nullable=true)
     */
    private $dataResponse;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=1024, nullable=false)
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="easyshop_charge", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $easyshopCharge = '0.0000';

    /**
     * @var string
     *
     * @ORM\Column(name="payment_method_charge", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $paymentMethodCharge = '0.0000';

    /**
     * @var string
     *
     * @ORM\Column(name="net", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $net = '0.0000';

    /**
     * @var integer
     *
     * @ORM\Column(name="postbackcount", type="integer", nullable=true)
     */
    private $postbackcount = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_flag", type="boolean", nullable=true)
     */
    private $isFlag = '0';

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="buyer_id", referencedColumnName="id_member")
     * })
     */
    private $buyer;

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
     * @var \EasyShop\Entities\EsPaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsPaymentMethod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id_payment_method")
     * })
     */
    private $paymentMethod;



    /**
     * Get idOrder
     *
     * @return integer 
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }

    /**
     * Set invoiceNo
     *
     * @param string $invoiceNo
     * @return EsOrder
     */
    public function setInvoiceNo($invoiceNo)
    {
        $this->invoiceNo = $invoiceNo;

        return $this;
    }

    /**
     * Get invoiceNo
     *
     * @return string 
     */
    public function getInvoiceNo()
    {
        return $this->invoiceNo;
    }

    /**
     * Set paymentAddressId
     *
     * @param integer $paymentAddressId
     * @return EsOrder
     */
    public function setPaymentAddressId($paymentAddressId)
    {
        $this->paymentAddressId = $paymentAddressId;

        return $this;
    }

    /**
     * Get paymentAddressId
     *
     * @return integer 
     */
    public function getPaymentAddressId()
    {
        return $this->paymentAddressId;
    }

    /**
     * Set shippingAddressId
     *
     * @param integer $shippingAddressId
     * @return EsOrder
     */
    public function setShippingAddressId($shippingAddressId)
    {
        $this->shippingAddressId = $shippingAddressId;

        return $this;
    }

    /**
     * Get shippingAddressId
     *
     * @return integer 
     */
    public function getShippingAddressId()
    {
        return $this->shippingAddressId;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return EsOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set dateadded
     *
     * @param \DateTime $dateadded
     * @return EsOrder
     */
    public function setDateadded($dateadded)
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    /**
     * Get dateadded
     *
     * @return \DateTime 
     */
    public function getDateadded()
    {
        return $this->dateadded;
    }

    /**
     * Set datemodified
     *
     * @param \DateTime $datemodified
     * @return EsOrder
     */
    public function setDatemodified($datemodified)
    {
        $this->datemodified = $datemodified;

        return $this;
    }

    /**
     * Get datemodified
     *
     * @return \DateTime 
     */
    public function getDatemodified()
    {
        return $this->datemodified;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return EsOrder
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set dataResponse
     *
     * @param string $dataResponse
     * @return EsOrder
     */
    public function setDataResponse($dataResponse)
    {
        $this->dataResponse = $dataResponse;

        return $this;
    }

    /**
     * Get dataResponse
     *
     * @return string 
     */
    public function getDataResponse()
    {
        return $this->dataResponse;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return EsOrder
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set easyshopCharge
     *
     * @param string $easyshopCharge
     * @return EsOrder
     */
    public function setEasyshopCharge($easyshopCharge)
    {
        $this->easyshopCharge = $easyshopCharge;

        return $this;
    }

    /**
     * Get easyshopCharge
     *
     * @return string 
     */
    public function getEasyshopCharge()
    {
        return $this->easyshopCharge;
    }

    /**
     * Set paymentMethodCharge
     *
     * @param string $paymentMethodCharge
     * @return EsOrder
     */
    public function setPaymentMethodCharge($paymentMethodCharge)
    {
        $this->paymentMethodCharge = $paymentMethodCharge;

        return $this;
    }

    /**
     * Get paymentMethodCharge
     *
     * @return string 
     */
    public function getPaymentMethodCharge()
    {
        return $this->paymentMethodCharge;
    }

    /**
     * Set net
     *
     * @param string $net
     * @return EsOrder
     */
    public function setNet($net)
    {
        $this->net = $net;

        return $this;
    }

    /**
     * Get net
     *
     * @return string 
     */
    public function getNet()
    {
        return $this->net;
    }

    /**
     * Set postbackcount
     *
     * @param boolean $postbackcount
     * @return EsOrder
     */
    public function setPostbackcount($postbackcount)
    {
        $this->postbackcount = $postbackcount;

        return $this;
    }

    /**
     * Get postbackcount
     *
     * @return boolean 
     */
    public function getPostbackcount()
    {
        return $this->postbackcount;
    }

    /**
     * Set isFlag
     *
     * @param boolean $isFlag
     * @return EsOrder
     */
    public function setIsFlag($isFlag)
    {
        $this->isFlag = $isFlag;

        return $this;
    }

    /**
     * Get isFlag
     *
     * @return boolean 
     */
    public function getIsFlag()
    {
        return $this->isFlag;
    }

    /**
     * Set buyer
     *
     * @param \EasyShop\Entities\EsMember $buyer
     * @return EsOrder
     */
    public function setBuyer(\EasyShop\Entities\EsMember $buyer = null)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set orderStatus
     *
     * @param \EasyShop\Entities\EsOrderStatus $orderStatus
     * @return EsOrder
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

    /**
     * Set paymentMethod
     *
     * @param \EasyShop\Entities\EsPaymentMethod $paymentMethod
     * @return EsOrder
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
