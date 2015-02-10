<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductExternalLink
 *
 * @ORM\Table(name="es_product_external_link")
 * @ORM\Entity(repositoryClass="EasyShop\Entities\EsProductExternalLink")
 */
class EsProductExternalLink
{
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
     * @ORM\Column(name="link", type="string", length=45, nullable=false)
     */
    private $link = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId = '0';



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
}
