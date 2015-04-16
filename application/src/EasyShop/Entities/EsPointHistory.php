<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPointHistory
 *
 * @ORM\Table(name="es_point_history", indexes={@ORM\Index(name="fk_es_point_history_m_id_idx", columns={"member_id"}), @ORM\Index(name="fk_es_point_history_pt_id_idx", columns={"type"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsPointHistoryRepository")
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
     * @var string
     *
     * @ORM\Column(name="point", type="decimal",  precision=15, scale=4, nullable=false)
     */
    private $point = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=1024, nullable=false)
     */
    private $data = '';

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
     * @param string $point
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
     * @return string 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return EsPointHistory
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsPointHistory
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
