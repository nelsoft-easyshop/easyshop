<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMemberMerge
 *
 * @ORM\Table(name="es_member_merge", indexes={@ORM\Index(name="fk_es_member_merge_2_idx", columns={"social_media_provider_id"}), @ORM\Index(name="idx_id_member", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsMemberMergeRepository")
 */
class EsMemberMerge
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_member_merge", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMemberMerge;

    /**
     * @var string
     *
     * @ORM\Column(name="social_media_id", type="string", length=255, nullable=false)
     */
    private $socialMediaId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

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
     * @var \EasyShop\Entities\EsSocialMediaProvider
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsSocialMediaProvider")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="social_media_provider_id", referencedColumnName="id_social_media_provider")
     * })
     */
    private $socialMediaProvider;



    /**
     * Get idMemberMerge
     *
     * @return integer 
     */
    public function getIdMemberMerge()
    {
        return $this->idMemberMerge;
    }

    /**
     * Set socialMediaId
     *
     * @param string $socialMediaId
     * @return EsMemberMerge
     */
    public function setSocialMediaId($socialMediaId)
    {
        $this->socialMediaId = $socialMediaId;

        return $this;
    }

    /**
     * Get socialMediaId
     *
     * @return string 
     */
    public function getSocialMediaId()
    {
        return $this->socialMediaId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return EsMemberMerge
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsMemberMerge
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
     * Set socialMediaProvider
     *
     * @param \EasyShop\Entities\EsSocialMediaProvider $socialMediaProvider
     * @return EsMemberMerge
     */
    public function setSocialMediaProvider(\EasyShop\Entities\EsSocialMediaProvider $socialMediaProvider = null)
    {
        $this->socialMediaProvider = $socialMediaProvider;

        return $this;
    }

    /**
     * Get socialMediaProvider
     *
     * @return \EasyShop\Entities\EsSocialMediaProvider 
     */
    public function getSocialMediaProvider()
    {
        return $this->socialMediaProvider;
    }
}
