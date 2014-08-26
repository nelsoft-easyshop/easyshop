<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPointHistory
 *
 * @ORM\Table(name="es_point_history", indexes={@ORM\Index(name="fk_es_point_history_m_id_idx", columns={"m_id"}), @ORM\Index(name="fk_es_point_history_pt_id_idx", columns={"type"})})
 * @ORM\Entity
 */
class EsPointHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="point", type="integer", nullable=false)
     */
    private $point = '0';

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="m_id", referencedColumnName="id_member")
     * })
     */
    private $m;

    /**
     * @var \EasyShop\Entities\EsPointType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsPointType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return EsPointHistory
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set point
     *
     * @param integer $point
     * @return EsPointHistory
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set m
     *
     * @param \EasyShop\Entities\EsMember $m
     * @return EsPointHistory
     */
    public function setM(\EasyShop\Entities\EsMember $m = null)
    {
        $this->m = $m;

        return $this;
    }

    /**
     * Get m
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getM()
    {
        return $this->m;
    }

    /**
     * Set type
     *
     * @param \EasyShop\Entities\EsPointType $type
     * @return EsPointHistory
     */
    public function setType(\EasyShop\Entities\EsPointType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \EasyShop\Entities\EsPointType 
     */
    public function getType()
    {
        return $this->type;
    }
}
