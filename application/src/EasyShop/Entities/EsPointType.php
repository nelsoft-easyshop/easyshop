<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsPointType
 *
 * @ORM\Table(name="es_point_type")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsPointTypeRepository")
 */
class EsPointType
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="point", type="integer", nullable=false)
     */
    private $point = '0';

    /**
     * @var integer
     */
    const TYPE_REGISTER = 1;
    
    /**
     * @var integer
     */
    const TYPE_LOGIN = 2;
    
    /**
     * @var integer
     */
    const TYPE_SHARE = 3;
    
    /**
     * @var integer
     */
    const TYPE_PURCHASE = 4;
    
    /**
     * @var integer
     */
    const TYPE_TRANSACTION_FEEDBACK = 5;
    
    /**
     * @var integer
     */
    const TYPE_REVERT = 6;

    /**
     * @var integer
     */
    const TYPE_EXPIRED = 7;


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
     * Set name
     *
     * @param string $name
     * @return EsPointType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set point
     *
     * @param integer $point
     * @return EsPointType
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
}
