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
     * @var string
     *
     * @ORM\Column(name="point", type="decimal",  precision=15, scale=4, nullable=false)
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
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime", nullable=false)
     */
    private $expirationDate = 'CURRENT_TIMESTAMP';

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
     * @param string $point
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
     * @return string 
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
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     * @return EsMember
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime 
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}
