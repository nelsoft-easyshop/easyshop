<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPoint
 *
 * @ORM\Table(name="es_point", indexes={@ORM\Index(name="fk_es_point_m_id_idx", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsPointRepository")
 */
class EsPoint
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
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id_member")
     * })
     */
    private $member;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit_point", type="integer", nullable=true)
     */
    private $creditPoint = '0';


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
     * Set point
     *
     * @param integer $point
     * @return EsPoint
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
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsPoint
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
     * Set creditPoint
     *
     * @param integer $creditPoint
     * @return integer
     */
    public function setCreditPoint($creditPoint)
    {
        $this->creditPoint = $creditPoint;

        return $this;
    }

    /**
     * Get creditPoint
     *
     * @return integer 
     */
    public function getCreditPoint()
    {
        return $this->creditPoint;
    }
}
