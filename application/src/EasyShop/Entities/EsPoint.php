<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPoint
 *
 * @ORM\Table(name="es_point", indexes={@ORM\Index(name="fk_es_point_m_id_idx", columns={"m_id"})})
 * @ORM\Entity
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
     *   @ORM\JoinColumn(name="m_id", referencedColumnName="id_member")
     * })
     */
    private $m;



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
     * Set m
     *
     * @param \EasyShop\Entities\EsMember $m
     * @return EsPoint
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
}
