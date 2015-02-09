<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsStudent
 *
 * @ORM\Table(name="es_student", indexes={@ORM\Index(name="fk_es_student_1_idx", columns={"school_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsStudentRepository")
 */
class EsStudent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_student", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var \EasyShop\Entities\EsSchool
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsSchool")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="school_id", referencedColumnName="id_school")
     * })
     */
    private $school;



    /**
     * Get idStudent
     *
     * @return integer
     */
    public function getIdStudent()
    {
        return $this->idStudent;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsStudent
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
     * Set school
     *
     * @param \EasyShop\Entities\EsSchool $school
     * @return EsStudent
     */
    public function setSchool(\EasyShop\Entities\EsSchool $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return \EasyShop\Entities\EsSchool
     */
    public function getSchool()
    {
        return $this->school;
    }
}
