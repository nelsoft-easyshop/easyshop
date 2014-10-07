<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderProduct
 *
 * @ORM\Table(name="es_order_product", indexes={@ORM\Index(name="fk_es_order_product_es_order_idx", columns={"order_id"}), @ORM\Index(name="fk_es_order_product_es_member_idx", columns={"seller_id"}), @ORM\Index(name="fk_es_order_product_es_product_idx", columns={"product_id"}), @ORM\Index(name="fk_es_order_product_es_order_product_status_idx", columns={"status"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderProductRepository")
 */
class EsOrderProduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_product", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderProduct;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_quantity", type="integer", nullable=true)
     */
    private $orderQuantity = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $price = '0.0000';

    /**
     * @var string
     *
     * @ORM\Column(name="handling_fee", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $handlingFee = '0.0000';

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $total = '0.0000';

    /**
     * @var integer
     *
     * @ORM\Column(name="product_item_id", type="integer", nullable=false)
     */
    private $productItemId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="buyer_billing_id", type="integer", nullable=false)
     */
    private $buyerBillingId = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_reject", type="boolean", nullable=false)
     */
    private $isReject = '0';

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
     * @ORM\Column(name="seller_billing_id", type="integer", nullable=false)
     */
    private $sellerBillingId = '0';

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seller_id", referencedColumnName="id_member")
     * })
     */
    private $seller;

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
     * @var \EasyShop\Entities\EsOrderProductStatus
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrderProductStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id_order_product_status")
     * })
     */
    private $status;

    /**
     * @var \EasyShop\Entities\EsProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     * })
     */
    private $product;



    /**
     * Get idOrderProduct
     *
     * @return integer 
     */
    public function getIdOrderProduct()
    {
        return $this->idOrderProduct;
    }

    /**
     * Set orderQuantity
     *
     * @param integer $orderQuantity
     * @return EsOrderProduct
     */
    public function setOrderQuantity($orderQuantity)
    {
        $this->orderQuantity = $orderQuantity;

        return $this;
    }

    /**
     * Get orderQuantity
     *
     * @return integer 
     */
    public function getOrderQuantity()
    {
        return $this->orderQuantity;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return EsOrderProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set handlingFee
     *
     * @param string $handlingFee
     * @return EsOrderProduct
     */
    public function setHandlingFee($handlingFee)
    {
        $this->handlingFee = $handlingFee;

        return $this;
    }

    /**
     * Get handlingFee
     *
     * @return string 
     */
    public function getHandlingFee()
    {
        return $this->handlingFee;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return EsOrderProduct
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
     * Set productItemId
     *
     * @param integer $productItemId
     * @return EsOrderProduct
     */
    public function setProductItemId($productItemId)
    {
        $this->productItemId = $productItemId;

        return $this;
    }

    /**
     * Get productItemId
     *
     * @return integer 
     */
    public function getProductItemId()
    {
        return $this->productItemId;
    }

    /**
     * Set buyerBillingId
     *
     * @param integer $buyerBillingId
     * @return EsOrderProduct
     */
    public function setBuyerBillingId($buyerBillingId)
    {
        $this->buyerBillingId = $buyerBillingId;

        return $this;
    }

    /**
     * Get buyerBillingId
     *
     * @return integer 
     */
    public function getBuyerBillingId()
    {
        return $this->buyerBillingId;
    }

    /**
     * Set isReject
     *
     * @param boolean $isReject
     * @return EsOrderProduct
     */
    public function setIsReject($isReject)
    {
        $this->isReject = $isReject;

        return $this;
    }

    /**
     * Get isReject
     *
     * @return boolean 
     */
    public function getIsReject()
    {
        return $this->isReject;
    }

    /**
     * Set easyshopCharge
     *
     * @param string $easyshopCharge
     * @return EsOrderProduct
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
     * @return EsOrderProduct
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
     * @return EsOrderProduct
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
     * Set sellerBillingId
     *
     * @param integer $sellerBillingId
     * @return EsOrderProduct
     */
    public function setSellerBillingId($sellerBillingId)
    {
        $this->sellerBillingId = $sellerBillingId;

        return $this;
    }

    /**
     * Get sellerBillingId
     *
     * @return integer 
     */
    public function getSellerBillingId()
    {
        return $this->sellerBillingId;
    }

    /**
     * Set seller
     *
     * @param \EasyShop\Entities\EsMember $seller
     * @return EsOrderProduct
     */
    public function setSeller(\EasyShop\Entities\EsMember $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set order
     *
     * @param \EasyShop\Entities\EsOrder $order
     * @return EsOrderProduct
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
     * Set status
     *
     * @param \EasyShop\Entities\EsOrderProductStatus $status
     * @return EsOrderProduct
     */
    public function setStatus(\EasyShop\Entities\EsOrderProductStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \EasyShop\Entities\EsOrderProductStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsOrderProduct
     */
    public function setProduct(\EasyShop\Entities\EsProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \EasyShop\Entities\EsProduct 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
