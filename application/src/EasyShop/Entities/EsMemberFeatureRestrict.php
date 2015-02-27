<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMemberFeatureRestrict
 *
 * @ORM\Table(name="es_member_feature_restrict", indexes={@ORM\Index(name="fk_es_member_feature_restrict_1_idx", columns={"member_id"}), @ORM\Index(name="fk_es_member_feature_restrict_2_idx", columns={"feature_restrict_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repository\EsMemberFeatureRestrictRepository")
 */
class EsMemberFeatureRestrict
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_member_feature_restrict", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMemberFeatureRestrict;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_delete", type="integer", nullable=true)
     */
    private $isDelete = '0';

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
     * @var \EasyShop\Entities\EsFeatureRestrict
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsFeatureRestrict")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="feature_restrict_id", referencedColumnName="id_feature_restrict")
     * })
     */
    private $featureRestrict;



    /**
     * Get idMemberFeatureRestrict
     *
     * @return integer 
     */
    public function getIdMemberFeatureRestrict()
    {
        return $this->idMemberFeatureRestrict;
    }

    /**
     * Set isDelete
     *
     * @param integer $isDelete
     * @return EsMemberFeatureRestrict
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return integer 
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsMemberFeatureRestrict
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
     * Set featureRestrict
     *
     * @param \EasyShop\Entities\EsFeatureRestrict $featureRestrict
     * @return EsMemberFeatureRestrict
     */
    public function setFeatureRestrict(\EasyShop\Entities\EsFeatureRestrict $featureRestrict = null)
    {
        $this->featureRestrict = $featureRestrict;

        return $this;
    }

    /**
     * Get featureRestrict
     *
     * @return \EasyShop\Entities\EsFeatureRestrict 
     */
    public function getFeatureRestrict()
    {
        return $this->featureRestrict;
    }
}
