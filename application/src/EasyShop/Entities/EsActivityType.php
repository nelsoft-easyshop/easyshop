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
}
