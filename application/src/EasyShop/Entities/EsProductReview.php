<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductReview
 *
 * @ORM\Table(name="es_product_review", indexes={@ORM\Index(name="fk_es_product_review_es_product_idx", columns={"product_id"}), @ORM\Index(name="fk_es_product_review_es_member_idx", columns={"member_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductReviewRepository")
 */
class EsProductReview
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_review", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReview;

    /**
     * @var integer
     *
     * @ORM\Column(name="p_reviewid", type="integer", nullable=false)
     */
    private $pReviewid = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesubmitted", type="datetime", nullable=false)
     */
    private $datesubmitted = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=150, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="text", nullable=false)
     */
    private $review;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false)
     */
    private $isShow = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datehidden", type="datetime", nullable=false)
     */
    private $datehidden = 'CURRENT_TIMESTAMP';

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
     * @var \EasyShop\Entities\EsProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     * })
     */
    private $product;

    const PRODUCT_REVIEW_DEFAULT = 0;

    /**
     * best rating value for review
     */
    const REVIEW_BEST_RATING = 5;

    /**
     * Get idReview
     *
     * @return integer 
     */
    public function getIdReview()
    {
        return $this->idReview;
    }

    /**
     * Set pReviewid
     *
     * @param integer $pReviewid
     * @return EsProductReview
     */
    public function setPReviewid($pReviewid)
    {
        $this->pReviewid = $pReviewid;

        return $this;
    }

    /**
     * Get pReviewid
     *
     * @return integer 
     */
    public function getPReviewid()
    {
        return $this->pReviewid;
    }

    /**
     * Set datesubmitted
     *
     * @param \DateTime $datesubmitted
     * @return EsProductReview
     */
    public function setDatesubmitted($datesubmitted)
    {
        $this->datesubmitted = $datesubmitted;

        return $this;
    }

    /**
     * Get datesubmitted
     *
     * @return \DateTime 
     */
    public function getDatesubmitted()
    {
        return $this->datesubmitted;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return EsProductReview
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return EsProductReview
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
     * Set review
     *
     * @param string $review
     * @return EsProductReview
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set isShow
     *
     * @param boolean $isShow
     * @return EsProductReview
     */
    public function setIsShow($isShow)
    {
        $this->isShow = $isShow;

        return $this;
    }

    /**
     * Get isShow
     *
     * @return boolean 
     */
    public function getIsShow()
    {
        return $this->isShow;
    }

    /**
     * Set datehidden
     *
     * @param \DateTime $datehidden
     * @return EsProductReview
     */
    public function setDatehidden($datehidden)
    {
        $this->datehidden = $datehidden;

        return $this;
    }

    /**
     * Get datehidden
     *
     * @return \DateTime 
     */
    public function getDatehidden()
    {
        return $this->datehidden;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsProductReview
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
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsProductReview
     */
    public function setProduct(\EasyShop\Entities\EsProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \EasyShop\Entities\EsProduct 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
