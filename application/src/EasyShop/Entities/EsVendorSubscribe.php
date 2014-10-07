<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsVendorSubscribe
 *
 * @ORM\Table(name="es_vendor_subscribe")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsVendorSubscribeRepository")
 */
class EsVendorSubscribe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vendor_subscribe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVendorSubscribe;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_id", type="integer", nullable=true)
     */
    private $memberId;

    /**
     * @var integer
     *
     * @ORM\Column(name="vendor_id", type="integer", nullable=true)
     */
    private $vendorId;



    /**
     * Get idVendorSubscribe
     *
     * @return integer 
     */
    public function getIdVendorSubscribe()
    {
        return $this->idVendorSubscribe;
    }

    /**
     * Set memberId
     *
     * @param integer $memberId
     * @return EsVendorSubscribe
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * Get memberId
     *
     * @return integer 
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * Set vendorId
     *
     * @param integer $vendorId
     * @return EsVendorSubscribe
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;

        return $this;
    }

    /**
     * Get vendorId
     *
     * @return integer 
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
}
