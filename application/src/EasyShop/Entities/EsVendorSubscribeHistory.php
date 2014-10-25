<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsVendorSubscribeHistory
 *
 * @ORM\Table(name="es_vendor_subscribe_history", indexes={@ORM\Index(name="fk_es_vendor_subscribe_member_id_idx", columns={"member_id"}), @ORM\Index(name="fk_es_vendor_subscribe_vendor_id_idx", columns={"vendor_id"})})
 * @ORM\Entity
 */
class EsVendorSubscribeHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vendor_subscribe_history", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVendorSubscribeHistory;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=45, nullable=false)
     */
    private $action;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

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
     * Get idVendorSubscribeHistory
     *
     * @return integer 
     */
    public function getIdVendorSubscribeHistory()
    {
        return $this->idVendorSubscribeHistory;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return EsVendorSubscribeHistory
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return EsVendorSubscribeHistory
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsVendorSubscribeHistory
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
     * @return EsVendorSubscribeHistory
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
