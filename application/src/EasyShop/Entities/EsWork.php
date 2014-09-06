<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsWork
 *
 * @ORM\Table(name="es_work", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE PAIR", columns={"id_member", "count"})}, indexes={@ORM\Index(name="fk_es_work_es_member_idx", columns={"id_member"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsWorkRepository")
 */
class EsWork
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
     * @ORM\Column(name="companyname", type="string", length=45, nullable=true)
     */
    private $companyname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=45, nullable=true)
     */
    private $designation = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date", nullable=true)
     */
    private $year = '2001';

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
     * Set companyname
     *
     * @param string $companyname
     * @return EsWork
     */
    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;

        return $this;
    }

    /**
     * Get companyname
     *
     * @return string 
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * Set designation
     *
     * @param string $designation
     * @return EsWork
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     * @return EsWork
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
     * Set count
     *
     * @param boolean $count
     * @return EsWork
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
     * @return EsWork
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
