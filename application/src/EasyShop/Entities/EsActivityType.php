<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsActivityType
 *
 * @ORM\Table(name="es_activity_type")
 * @ORM\Entity
 */
class EsActivityType
{
    const INFORMATION_UPDATE = 1;

    const PRODUCT_UPDATE = 2;

    const TRANSACTION_UPDATE = 3;

    const FEEDBACK_UPDATE = 4;

    const VENDOR_SUBSCRIPTION = 5;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_activity_type", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idActivityType;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_description", type="string", length=100, nullable=true)
     */
    private $activityDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_phrase", type="text", length=100, nullable=true)
     */
    private $activityPhrase;



    /**
     * Get idActivityType
     *
     * @return integer 
     */
    public function getIdActivityType()
    {
        return $this->idActivityType;
    }

    /**
     * Set activityDescription
     *
     * @param string $activityDescription
     * @return EsActivityType
     */
    public function setActivityDescription($activityDescription)
    {
        $this->activityDescription = $activityDescription;

        return $this;
    }

    /**
     * Get activityDescription
     *
     * @return string 
     */
    public function getActivityDescription()
    {
        return $this->activityDescription;
    }

    /**
     * Set activityPhrase
     *
     * @param string $activityPhrase
     * @return EsActivityType
     */
    public function setActivityPhrase($activityPhrase)
    {
        $this->activityPhrase = $activityPhrase;

        return $this;
    }

    /**
     * Get activityPhrase
     *
     * @return string 
     */
    public function getActivityPhrase()
    {
        return $this->activityPhrase;
    }

}
