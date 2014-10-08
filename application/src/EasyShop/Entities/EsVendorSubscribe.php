<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsVendorSubscribe
 *
 * @ORM\Table(name="es_vendor_subscribe", indexes={@ORM\Index(name="fk_es_vendor_subscribe_memberId_idx", columns={"member_id"}), @ORM\Index(name="fk_es_vendor_subscribe_vendorId_idx", columns={"vendor_id"})})
 * @ORM\Entity
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
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id_member")
     * })
     */
    private $member;

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vendor_id", referencedColumnName="id_member")
     * })
     */
    private $vendor;



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
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsVendorSubscribe
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

    /**
     * Set vendor
     *
     * @param \EasyShop\Entities\EsMember $vendor
     * @return EsVendorSubscribe
     */
    public function setVendor(\EasyShop\Entities\EsMember $vendor = null)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getVendor()
    {
        return $this->vendor;
    }
}
