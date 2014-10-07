<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsHolidaydetails
 *
 * @ORM\Table(name="es_holidaydetails", indexes={@ORM\Index(name="fk_es_holidaydetails_es_holidaytype_idx", columns={"type"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsHolidaydetailsRepository")
 */
class EsHolidaydetails
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
     * @ORM\Column(name="date_d", type="date", nullable=true)
     */
    private $dateD = '0001-01-01';

    /**
     * @var string
     *
     * @ORM\Column(name="memo", type="string", length=45, nullable=true)
     */
    private $memo = '';

    /**
     * @var \EasyShop\Entities\EsHolidaytype
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsHolidaytype")
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
     * Set dateD
     *
     * @param \DateTime $dateD
     * @return EsHolidaydetails
     */
    public function setDateD($dateD)
    {
        $this->dateD = $dateD;

        return $this;
    }

    /**
     * Get dateD
     *
     * @return \DateTime 
     */
    public function getDateD()
    {
        return $this->dateD;
    }

    /**
     * Set memo
     *
     * @param string $memo
     * @return EsHolidaydetails
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * Get memo
     *
     * @return string 
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Set type
     *
     * @param \EasyShop\Entities\EsHolidaytype $type
     * @return EsHolidaydetails
     */
    public function setType(\EasyShop\Entities\EsHolidaytype $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \EasyShop\Entities\EsHolidaytype 
     */
    public function getType()
    {
        return $this->type;
    }
}
