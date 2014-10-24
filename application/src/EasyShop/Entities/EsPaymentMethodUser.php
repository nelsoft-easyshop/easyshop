<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPaymentMethodUser
 *
 * @ORM\Table(name="es_payment_method_user", indexes={@ORM\Index(name="fk_es_payment_method_user_member_id", columns={"member_id"}), @ORM\Index(name="fk_es_payment_method_user_payment_method_id", columns={"payment_method_id"})})
 * @ORM\Entity
 */
class EsPaymentMethodUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_payment_method_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPaymentMethodUser;

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
     * @var \EasyShop\Entities\EsPaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsPaymentMethod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id_payment_method")
     * })
     */
    private $paymentMethod;



    /**
     * Get idPaymentMethodUser
     *
     * @return integer 
     */
    public function getIdPaymentMethodUser()
    {
        return $this->idPaymentMethodUser;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsPaymentMethodUser
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

    /**
     * Set paymentMethod
     *
     * @param \EasyShop\Entities\EsPaymentMethod $paymentMethod
     * @return EsPaymentMethodUser
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
