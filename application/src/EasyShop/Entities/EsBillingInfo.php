<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsBillingInfo
 *
 * @ORM\Table(name="es_billing_info", indexes={@ORM\Index(name="fk_es_product_es_member_idx", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsBillingInfoRepository")
 */
class EsBillingInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_billing_info", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBillingInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", length=45, nullable=false)
     */
    private $paymentType = '';

    /**
     * @var string
     *
     * @ORM\Column(name="user_account", type="string", length=255, nullable=false)
     */
    private $userAccount = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="bank_id", type="integer", nullable=false)
     */
    private $bankId;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_account_name", type="string", length=255, nullable=false)
     */
    private $bankAccountName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="bank_account_number", type="string", length=60, nullable=false)
     */
    private $bankAccountNumber = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=false)
     */
    private $isDefault = '0';

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
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", nullable=false)
     */
    private $isDelete = '0';

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id_member")
     * })
     */
    private $member;

    /**
     * Default billing id.
     */
    const DEFAULT_BILLING_ID = 0;

    /**
     * Get idBillingInfo
     *
     * @return integer 
     */
    public function getIdBillingInfo()
    {
        return $this->idBillingInfo;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     * @return EsBillingInfo
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string 
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set userAccount
     *
     * @param string $userAccount
     * @return EsBillingInfo
     */
    public function setUserAccount($userAccount)
    {
        $this->userAccount = $userAccount;

        return $this;
    }

    /**
     * Get userAccount
     *
     * @return string 
     */
    public function getUserAccount()
    {
        return $this->userAccount;
    }

    /**
     * Set bankId
     *
     * @param integer $bankId
     * @return EsBillingInfo
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;

        return $this;
    }

    /**
     * Get bankId
     *
     * @return integer 
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * Set bankAccountName
     *
     * @param string $bankAccountName
     * @return EsBillingInfo
     */
    public function setBankAccountName($bankAccountName)
    {
        $this->bankAccountName = $bankAccountName;

        return $this;
    }

    /**
     * Get bankAccountName
     *
     * @return string 
     */
    public function getBankAccountName()
    {
        return $this->bankAccountName;
    }

    /**
     * Set bankAccountNumber
     *
     * @param string $bankAccountNumber
     * @return EsBillingInfo
     */
    public function setBankAccountNumber($bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;

        return $this;
    }

    /**
     * Get bankAccountNumber
     *
     * @return string 
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     * @return EsBillingInfo
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set dateadded
     *
     * @param \DateTime $dateadded
     * @return EsBillingInfo
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
     * @return EsBillingInfo
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
     * Set isDelete
     *
     * @param boolean $isDelete
     * @return EsBillingInfo
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean 
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsBillingInfo
     */
    public function setMember(\EasyShop\Entities\EsMember $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getMember()
    {
        return $this->member;
    }
}
