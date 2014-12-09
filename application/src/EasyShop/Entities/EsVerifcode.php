<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsVerifcode
 *
 * @ORM\Table(name="es_verifcode", uniqueConstraints={@ORM\UniqueConstraint(name="member_id_UNIQUE", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsVerifcodeRepository")
 */
class EsVerifcode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_verifcode", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVerifcode;

    /**
     * @var string
     *
     * @ORM\Column(name="emailcode", type="string", length=255, nullable=false)
     */
    private $emailcode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="mobilecode", type="string", length=255, nullable=false)
     */
    private $mobilecode = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="emailcount", type="boolean", nullable=false)
     */
    private $emailcount = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="mobilecount", type="boolean", nullable=false)
     */
    private $mobilecount = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fp_timestamp", type="datetime", nullable=false)
     */
    private $fpTimestamp = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="fp_code", type="text", nullable=true)
     */
    private $fpCode;

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
     *  @var int
     */
    const DEFAULT_MOBILE_COUNT = 0;     

    /**
     * Get idVerifcode
     *
     * @return integer 
     */
    public function getIdVerifcode()
    {
        return $this->idVerifcode;
    }

    /**
     * Set emailcode
     *
     * @param string $emailcode
     * @return EsVerifcode
     */
    public function setEmailcode($emailcode)
    {
        $this->emailcode = $emailcode;

        return $this;
    }

    /**
     * Get emailcode
     *
     * @return string 
     */
    public function getEmailcode()
    {
        return $this->emailcode;
    }

    /**
     * Set mobilecode
     *
     * @param string $mobilecode
     * @return EsVerifcode
     */
    public function setMobilecode($mobilecode)
    {
        $this->mobilecode = $mobilecode;

        return $this;
    }

    /**
     * Get mobilecode
     *
     * @return string 
     */
    public function getMobilecode()
    {
        return $this->mobilecode;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return EsVerifcode
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set emailcount
     *
     * @param boolean $emailcount
     * @return EsVerifcode
     */
    public function setEmailcount($emailcount)
    {
        $this->emailcount = $emailcount;

        return $this;
    }

    /**
     * Get emailcount
     *
     * @return boolean 
     */
    public function getEmailcount()
    {
        return $this->emailcount;
    }

    /**
     * Set mobilecount
     *
     * @param boolean $mobilecount
     * @return EsVerifcode
     */
    public function setMobilecount($mobilecount)
    {
        $this->mobilecount = $mobilecount;

        return $this;
    }

    /**
     * Get mobilecount
     *
     * @return boolean 
     */
    public function getMobilecount()
    {
        return $this->mobilecount;
    }

    /**
     * Set fpTimestamp
     *
     * @param \DateTime $fpTimestamp
     * @return EsVerifcode
     */
    public function setFpTimestamp($fpTimestamp)
    {
        $this->fpTimestamp = $fpTimestamp;

        return $this;
    }

    /**
     * Get fpTimestamp
     *
     * @return \DateTime 
     */
    public function getFpTimestamp()
    {
        return $this->fpTimestamp;
    }

    /**
     * Set fpCode
     *
     * @param string $fpCode
     * @return EsVerifcode
     */
    public function setFpCode($fpCode)
    {
        $this->fpCode = $fpCode;

        return $this;
    }

    /**
     * Get fpCode
     *
     * @return string 
     */
    public function getFpCode()
    {
        return $this->fpCode;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsVerifcode
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
}
