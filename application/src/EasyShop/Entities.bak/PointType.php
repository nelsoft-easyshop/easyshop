<?php

namespace EasyShop\Entities;

/**
 * @Table(name="es_point_type")
 * @Entity
 *
 */
class PointType
{
    /**
    * @Id
    * @Column(name="id", type="integer", length=10, options={"unsigned"=true})
    * @GeneratedValue(strategy="AUTO")
    * @var int
    */
    protected $id;

    /**
    * @Column(name="name", type="string", length=255, options={"default"=""})
    * @var string
    */
    protected $name;

    /**
    * @Column(name="point", type="integer", length=10, options={"unsigned"=true})
    * @var int
    */
    protected $point;


   
    /* Getters */
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getPoint()
    {
        return $this->point;
    }
    

    /* Setters */

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function setPoint($point)
    {
        $this->point = $point;
        return $this;
    }
}
