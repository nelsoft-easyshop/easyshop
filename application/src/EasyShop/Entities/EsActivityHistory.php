<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsActivityHistory
 *
 * @ORM\Table(name="es_activity_history", indexes={@ORM\Index(name="activity_id", columns={"activity_type_id"})})
 * @ORM\Entity
 */
class EsActivityHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_activity_history", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idActivityHistory;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_string", type="text", nullable=true)
     */
    private $activityString;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activity_datetime", type="datetime", nullable=true)
     */
    private $activityDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var \EasyShop\\Entities\EsActivityType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\\Entities\EsActivityType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_type_id", referencedColumnName="id_activity_type")
     * })
     */
    private $activityType;



    /**
     * Get idActivityHistory
     *
     * @return integer 
     */
    public function getIdActivityHistory()
    {
        return $this->idActivityHistory;
    }

    /**
     * Set activityString
     *
     * @param string $activityString
     * @return EsActivityHistory
     */
    public function setActivityString($activityString)
    {
        $this->activityString = $activityString;

        return $this;
    }

    /**
     * Get activityString
     *
     * @return string 
     */
    public function getActivityString()
    {
        return $this->activityString;
    }

    /**
     * Set activityDatetime
     *
     * @param \DateTime $activityDatetime
     * @return EsActivityHistory
     */
    public function setActivityDatetime($activityDatetime)
    {
        $this->activityDatetime = $activityDatetime;

        return $this;
    }

    /**
     * Get activityDatetime
     *
     * @return \DateTime 
     */
    public function getActivityDatetime()
    {
        return $this->activityDatetime;
    }

    /**
     * Set activityType
     *
     * @param \EasyShop\\Entities\EsActivityType $activityType
     * @return EsActivityHistory
     */
    public function setActivityType(\EasyShop\\Entities\EsActivityType $activityType = null)
    {
        $this->activityType = $activityType;

        return $this;
    }

    /**
     * Get activityType
     *
     * @return \EasyShop\\Entities\EsActivityType 
     */
    public function getActivityType()
    {
        return $this->activityType;
    }
}
