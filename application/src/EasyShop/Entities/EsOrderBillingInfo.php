<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOrderBillingInfo
 *
 * @ORM\Table(name="es_order_billing_info")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOrderBillingInfoRepository")
 */
class EsOrderBillingInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_billing_info", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrderBillingInfo;


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
     * Get idOrderBillingInfo
     *
     * @return integer 
     */
    public function getIdOrderBillingInfo()
    {
        return $this->idOrderBillingInfo;
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
