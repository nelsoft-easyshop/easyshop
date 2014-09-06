<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductShippingPreferenceHead
 *
 * @ORM\Table(name="es_product_shipping_preference_head", indexes={@ORM\Index(name="fk_es_sp_head_es_member_idx", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductShippingPreferenceHeadRepository")
 */
class EsProductShippingPreferenceHead
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_shipping_pref_head", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShippingPrefHead;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title = '';

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
     * Get idShippingPrefHead
     *
     * @return integer 
     */
    public function getIdShippingPrefHead()
    {
        return $this->idShippingPrefHead;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return EsProductShippingPreferenceHead
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsProductShippingPreferenceHead
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
