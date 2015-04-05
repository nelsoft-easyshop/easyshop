<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsActivityHistory
 *
 * @ORM\Table(name="es_activity_history", indexes={@ORM\Index(name="activity_id", columns={"activity_type_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsActivityHistoryRepository")
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
     * @ORM\Column(name="json_data", type="text", nullable=true)
     */
    private $jsonData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activity_datetime", type="datetime", nullable=false)
     */
    private $activityDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var \EasyShop\Entities\EsActivityType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsActivityType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_type_id", referencedColumnName="id_activity_type")
     * })
     */
    private $activityType;

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
     * Get idActivityHistory
     *
     * @return integer 
     */
    public function getIdActivityHistory()
    {
        return $this->idActivityHistory;
    }

    /**
     * Set json data
     *
     * @param string $jsonData
     * @return EsActivityHistory
     */
    public function setJsonData($jsonData)
    {
        $this->jsonData = $jsonData;

        return $this;
    }

    /**
     * Get jsonData
     *
     * @return string 
     */
    public function getJsonData()
    {
        return $this->jsonData;
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
     * @param \EasyShop\Entities\EsActivityType $activityType
     * @return EsActivityHistory
     */
    public function setActivityType(\EasyShop\Entities\EsActivityType $activityType = null)
    {
        $this->activityType = $activityType;

        return $this;
    }

    /**
     * Get activityType
     *
     * @return \EasyShop\Entities\EsActivityType 
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsProduct
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
