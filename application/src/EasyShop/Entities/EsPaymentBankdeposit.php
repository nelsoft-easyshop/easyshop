<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPaymentBankdeposit
 *
 * @ORM\Table(name="es_payment_bankdeposit", indexes={@ORM\Index(name="fk_es_payment_bankdeposit_es_order_idx", columns={"order_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsPaymentBankdepositRepository")
 */
class EsPaymentBankdeposit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_payment_bankdeposit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPaymentBankdeposit;

    /**
     * @var string
     *
     * @ORM\Column(name="bank", type="string", length=45, nullable=false)
     */
    private $bank;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_num", type="string", length=45, nullable=false)
     */
    private $refNum;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="date_deposit", type="string", length=45, nullable=false)
     */
    private $dateDeposit;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=250, nullable=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodified", type="datetime", nullable=true)
     */
    private $datemodified = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_invalid", type="boolean", nullable=false)
     */
    private $isInvalid = '0';

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
     * Get idPaymentBankdeposit
     *
     * @return integer 
     */
    public function getIdPaymentBankdeposit()
    {
        return $this->idPaymentBankdeposit;
    }

    /**
     * Set bank
     *
     * @param string $bank
     * @return EsPaymentBankdeposit
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string 
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set refNum
     *
     * @param string $refNum
     * @return EsPaymentBankdeposit
     */
    public function setRefNum($refNum)
    {
        $this->refNum = $refNum;

        return $this;
    }

    /**
     * Get refNum
     *
     * @return string 
     */
    public function getRefNum()
    {
        return $this->refNum;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return EsPaymentBankdeposit
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
     * Set dateDeposit
     *
     * @param string $dateDeposit
     * @return EsPaymentBankdeposit
     */
    public function setDateDeposit($dateDeposit)
    {
        $this->dateDeposit = $dateDeposit;

        return $this;
    }

    /**
     * Get dateDeposit
     *
     * @return string 
     */
    public function getDateDeposit()
    {
        return $this->dateDeposit;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return EsPaymentBankdeposit
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
     * Set datemodified
     *
     * @param \DateTime $datemodified
     * @return EsPaymentBankdeposit
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
     * Set isInvalid
     *
     * @param boolean $isInvalid
     * @return EsPaymentBankdeposit
     */
    public function setIsInvalid($isInvalid)
    {
        $this->isInvalid = $isInvalid;

        return $this;
    }

    /**
     * Get isInvalid
     *
     * @return boolean 
     */
    public function getIsInvalid()
    {
        return $this->isInvalid;
    }

    /**
     * Set order
     *
     * @param \EasyShop\Entities\EsOrder $order
     * @return EsPaymentBankdeposit
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
}
