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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name = '';

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
     * Set name
     *
     * @param string $name
     * @return EsProductExternalLink
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
