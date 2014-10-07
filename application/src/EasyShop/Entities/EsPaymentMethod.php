<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPaymentMethod
 *
 * @ORM\Table(name="es_payment_method")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsPaymentMethodRepository")
 */
class EsPaymentMethod
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_payment_method", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPaymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var int
     */
    const PAYMENT_PAYPAL = 1;

    /**
     * @var int
     */
    const PAYMENT_DRAGONPAY = 2;

    /**
     * @var int
     */
    const PAYMENT_CASHONDELIVERY = 3;

    /**
     * @var int
     */
    const PAYMENT_PESOPAYCC = 4;

    /**
     * @var int
     */
    const PAYMENT_DIRECTBANKDEPOSIT = 5;

    /**
     * @var int
     */
    const PAYMENT_POINTS = 6;

    /**
     * Get idPaymentMethod
     *
     * @return integer 
     */
    public function getIdPaymentMethod()
    {
        return $this->idPaymentMethod;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsPaymentMethod
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
