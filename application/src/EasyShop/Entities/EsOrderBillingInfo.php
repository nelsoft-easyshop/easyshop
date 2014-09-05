<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderBillingInfo
 *
 * @ORM\Table(name="es_order_billing_info")
 * @ORM\Entity
 */
class EsOrderBillingInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_es_order_billing_info", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEsOrderBillingInfo;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer", nullable=true)
     */
    private $orderId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="order_product_id", type="integer", nullable=true)
     */
    private $orderProductId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=1024, nullable=true)
     */
    private $bankName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="account_name", type="string", length=1024, nullable=true)
     */
    private $accountName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="account_number", type="string", length=1024, nullable=true)
     */
    private $accountNumber = '';



    /**
     * Get idEsOrderBillingInfo
     *
     * @return integer 
     */
    public function getIdEsOrderBillingInfo()
    {
        return $this->idEsOrderBillingInfo;
    }

    /**
     * Set orderId
     *
     * @param integer $orderId
     * @return EsOrderBillingInfo
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set orderProductId
     *
     * @param integer $orderProductId
     * @return EsOrderBillingInfo
     */
    public function setOrderProductId($orderProductId)
    {
        $this->orderProductId = $orderProductId;

        return $this;
    }

    /**
     * Get orderProductId
     *
     * @return integer 
     */
    public function getOrderProductId()
    {
        return $this->orderProductId;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     * @return EsOrderBillingInfo
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string 
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set accountName
     *
     * @param string $accountName
     * @return EsOrderBillingInfo
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get accountName
     *
     * @return string 
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     * @return EsOrderBillingInfo
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string 
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }
}
