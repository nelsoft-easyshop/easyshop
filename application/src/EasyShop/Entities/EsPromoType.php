<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPromoType
 *
 * @ORM\Table(name="es_promo_type")
 * @ORM\Entity
 */
class EsPromoType
{
    /**
     * @var integer
     */
    const NO_PROMO = 0;

    /**
     * @var integer
     */
    const COUNTDOWN_SALE = 1;

    /**
     * @var integer
     */
    const FIXED_DISCOUNT = 2;

    /**
     * @var integer
     */
    const PEAK_HOUR_SALE = 3;

    /**
     * @var integer
     */
    const LISTING_PROMO = 4;

    /**
     * @var integer
     */
    const SCRATCH_AND_WIN = 5;

    /**
     * @var integer
     */
    const BUY_AT_ZERO = 6;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_promo_type", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPromoType;

    /**
     * @var string
     *
     * @ORM\Column(name="promo_name", type="string", length=45, nullable=true)
     */
    private $promoName = '';


    /**
     * Get idPromoType
     *
     * @return integer 
     */
    public function getIdPromoType()
    {
        return $this->idPromoType;
    }

    /**
     * Set promoName
     *
     * @param string $promoName
     * @return EsPromoType
     */
    public function setPromoName($promoName)
    {
        $this->promoName = $promoName;

        return $this;
    }

    /**
     * Get promoName
     *
     * @return string 
     */
    public function getPromoName()
    {
        return $this->promoName;
    }
}
