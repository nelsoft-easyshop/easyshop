<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsBankInfo
 *
 * @ORM\Table(name="es_bank_info")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsBankInfoRepository")
 */
class EsBankInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_bank", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBank;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=120, nullable=false)
     */
    private $bankName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="bank_short_name", type="string", length=45, nullable=true)
     */
    private $bankShortName;



    /**
     * Get idBank
     *
     * @return integer 
     */
    public function getIdBank()
    {
        return $this->idBank;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     * @return EsBankInfo
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string 
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set bankShortName
     *
     * @param string $bankShortName
     * @return EsBankInfo
     */
    public function setBankShortName($bankShortName)
    {
        $this->bankShortName = $bankShortName;

        return $this;
    }

    /**
     * Get bankShortName
     *
     * @return string 
     */
    public function getBankShortName()
    {
        return $this->bankShortName;
    }
}
