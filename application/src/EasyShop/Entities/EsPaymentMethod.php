<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPaymentMethod
 *
 * @ORM\Table(name="es_payment_method")
 * @ORM\Entity
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
