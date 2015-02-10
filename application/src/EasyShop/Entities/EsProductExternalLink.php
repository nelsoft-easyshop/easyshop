<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductExternalLink
 *
 * @ORM\Table(name="es_product_external_link")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductExternalLinkRepository")
 */
class EsProductExternalLink
{
    const DEFAULT_LINK = 'https://www.facebook.com/EasyShopPhilippines/';

    /**
     * @var integer
     *
     * @ORM\Column(name="id_product_external_link", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductExternalLink;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=500, nullable=false)
     */
    private $link = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="social_media_provider_id", type="integer", nullable=false)
     */
    private $socialMediaProviderId = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_announcement", type="datetime", nullable=false)
     */
    private $dateOfAnnouncement = '0000-00-00 00:00:00';



    /**
     * Get idProductExternalLink
     *
     * @return integer 
     */
    public function getIdProductExternalLink()
    {
        return $this->idProductExternalLink;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return EsProductExternalLink
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     * @return EsProductExternalLink
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set socialMediaProviderId
     *
     * @param integer $socialMediaProviderId
     * @return EsProductExternalLink
     */
    public function setSocialMediaProviderId($socialMediaProviderId)
    {
        $this->socialMediaProviderId = $socialMediaProviderId;

        return $this;
    }

    /**
     * Get socialMediaProviderId
     *
     * @return integer 
     */
    public function getSocialMediaProviderId()
    {
        return $this->socialMediaProviderId;
    }

    /**
     * Set dateOfAnnouncement
     *
     * @param \DateTime $dateOfAnnouncement
     * @return EsProductExternalLink
     */
    public function setDateOfAnnouncement($dateOfAnnouncement)
    {
        $this->dateOfAnnouncement = $dateOfAnnouncement;

        return $this;
    }

    /**
     * Get dateOfAnnouncement
     *
     * @return \DateTime 
     */
    public function getDateOfAnnouncement()
    {
        return $this->dateOfAnnouncement;
    }
}
