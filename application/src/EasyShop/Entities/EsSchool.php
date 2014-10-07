<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsSchool
 *
 * @ORM\Table(name="es_school", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE PAIR", columns={"id_member", "count"})}, indexes={@ORM\Index(name="fk_es_school_es_member_idx", columns={"id_member"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsSchoolRepository")
 */
class EsSchool
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
     * @ORM\Column(name="schoolname", type="string", length=45, nullable=true)
     */
    private $schoolname = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date", nullable=true)
     */
    private $year = '2001';

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=45, nullable=true)
     */
    private $level = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="count", type="boolean", nullable=true)
     */
    private $count = '0';

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_member", referencedColumnName="id_member")
     * })
     */
    private $idMember;



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
     * Set schoolname
     *
     * @param string $schoolname
     * @return EsSchool
     */
    public function setSchoolname($schoolname)
    {
        $this->schoolname = $schoolname;

        return $this;
    }

    /**
     * Get schoolname
     *
     * @return string 
     */
    public function getSchoolname()
    {
        return $this->schoolname;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     * @return EsSchool
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return EsSchool
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set count
     *
     * @param boolean $count
     * @return EsSchool
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return boolean 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set idMember
     *
     * @param \EasyShop\Entities\EsMember $idMember
     * @return EsSchool
     */
    public function setIdMember(\EasyShop\Entities\EsMember $idMember = null)
    {
        $this->idMember = $idMember;

        return $this;
    }

    /**
     * Get idMember
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getIdMember()
    {
        return $this->idMember;
    }
}
