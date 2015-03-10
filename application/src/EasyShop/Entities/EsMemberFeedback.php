<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMemberFeedback
 *
 * @ORM\Table(name="es_member_feedback", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE  order and for", columns={"order_id", "for_memberid"})}, indexes={@ORM\Index(name="id_member_idx", columns={"member_id"}), @ORM\Index(name="fk_es_member_feedback_es_member2_idx", columns={"for_memberid"}), @ORM\Index(name="fk_es_member_feedback_es_order_idx", columns={"order_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsMemberFeedbackRepository")
 */
class EsMemberFeedback
{
    /**
     * @var integer
     */
    const TYPE_AS_SELLER  = 1;
    
    /**
     * @var integer
     */
    const TYPE_AS_BUYER  = 2;
    
    /**
     * @var integer
     */
    const TYPE_FOR_OTHERS_AS_SELLER  = 3;
    
    /**
     * @var integer
     */
    const TYPE_FOR_OTHERS_AS_BUYER  = 4;
    
    /**
     * @var integer
     */
    const TYPE_ALL  = 5;

    /**
     * @var integer
     */
    const REVIEWER_AS_BUYER = 0;
  
    /**
     * @var integer
     */
    const REVIEWER_AS_SELLER = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_feedback", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFeedback;

    /**
     * @var string
     *
     * @ORM\Column(name="feedb_msg", type="string", length=255, nullable=false)
     */
    private $feedbMsg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="feedb_kind", type="boolean", nullable=false)
     */
    private $feedbKind;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateadded", type="datetime", nullable=false)
     */
    private $dateadded = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="rating1", type="integer", nullable=false)
     */
    private $rating1 = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="rating2", type="integer", nullable=false)
     */
    private $rating2 = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="rating3", type="integer", nullable=false)
     */
    private $rating3 = '0';

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
     *   @ORM\JoinColumn(name="for_memberid", referencedColumnName="id_member")
     * })
     */
    private $forMemberid;

    /**
     * @var \EasyShop\Entities\EsOrder
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOrder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id_order")
     * })
     */
    private $order;



    /**
     * Get idFeedback
     *
     * @return integer 
     */
    public function getIdFeedback()
    {
        return $this->idFeedback;
    }

    /**
     * Set feedbMsg
     *
     * @param string $feedbMsg
     * @return EsMemberFeedback
     */
    public function setFeedbMsg($feedbMsg)
    {
        $this->feedbMsg = $feedbMsg;

        return $this;
    }

    /**
     * Get feedbMsg
     *
     * @return string 
     */
    public function getFeedbMsg()
    {
        return $this->feedbMsg;
    }

    /**
     * Set feedbKind
     *
     * @param boolean $feedbKind
     * @return EsMemberFeedback
     */
    public function setFeedbKind($feedbKind)
    {
        $this->feedbKind = $feedbKind;

        return $this;
    }

    /**
     * Get feedbKind
     *
     * @return boolean 
     */
    public function getFeedbKind()
    {
        return $this->feedbKind;
    }

    /**
     * Set dateadded
     *
     * @param \DateTime $dateadded
     * @return EsMemberFeedback
     */
    public function setDateadded($dateadded)
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    /**
     * Get dateadded
     *
     * @return \DateTime 
     */
    public function getDateadded()
    {
        return $this->dateadded;
    }

    /**
     * Set rating1
     *
     * @param integer $rating1
     * @return EsMemberFeedback
     */
    public function setRating1($rating1)
    {
        $this->rating1 = $rating1;

        return $this;
    }

    /**
     * Get rating1
     *
     * @return integer 
     */
    public function getRating1()
    {
        return $this->rating1;
    }

    /**
     * Set rating2
     *
     * @param integer $rating2
     * @return EsMemberFeedback
     */
    public function setRating2($rating2)
    {
        $this->rating2 = $rating2;

        return $this;
    }

    /**
     * Get rating2
     *
     * @return integer 
     */
    public function getRating2()
    {
        return $this->rating2;
    }

    /**
     * Set rating3
     *
     * @param integer $rating3
     * @return EsMemberFeedback
     */
    public function setRating3($rating3)
    {
        $this->rating3 = $rating3;

        return $this;
    }

    /**
     * Get rating3
     *
     * @return integer 
     */
    public function getRating3()
    {
        return $this->rating3;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsMemberFeedback
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
     * Set forMemberid
     *
     * @param \EasyShop\Entities\EsMember $forMemberid
     * @return EsMemberFeedback
     */
    public function setForMemberid(\EasyShop\Entities\EsMember $forMemberid = null)
    {
        $this->forMemberid = $forMemberid;

        return $this;
    }

    /**
     * Get forMemberid
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getForMemberid()
    {
        return $this->forMemberid;
    }

    /**
     * Set order
     *
     * @param \EasyShop\Entities\EsOrder $order
     * @return EsMemberFeedback
     */
    public function setOrder(\EasyShop\Entities\EsOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \EasyShop\Entities\EsOrder 
     */
    public function getOrder()
    {
        return $this->order;
    }
}
