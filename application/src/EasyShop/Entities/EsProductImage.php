<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProductImage
 *
 * @ORM\Table(name="es_product_image", indexes={@ORM\Index(name="fk_es_product_es_product1", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsProductImageRepository")
 */
class EsProductImage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_product_image", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductImage;

    /**
     * @var string
     *
     * @ORM\Column(name="product_image_path", type="text", nullable=false)
     */
    private $productImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="product_image_type", type="string", length=1024, nullable=false)
     */
    private $productImageType = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_primary", type="boolean", nullable=false)
     */
    private $isPrimary = '0';

    /**
     * @var \EasyShop\Entities\EsProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProduct", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     * })
     */
    private $product;

    /**
     * @var string
     */
    private $directory = '';
    
    /**
     * @var string
     */
    private $filename = '';

    /**
     * Get idProductImage
     *
     * @return integer 
     */
    public function getIdProductImage()
    {
        return $this->idProductImage;
    }

    /**
     * Set productImagePath
     *
     * @param string $productImagePath
     * @return EsProductImage
     */
    public function setProductImagePath($productImagePath)
    {
        $this->productImagePath = $productImagePath;

        return $this;
    }

    /**
     * Get productImagePath
     *
     * @return string 
     */
    public function getProductImagePath()
    {
        return $this->productImagePath;
    }

    /**
     * Set productImageType
     *
     * @param string $productImageType
     * @return EsProductImage
     */
    public function setProductImageType($productImageType)
    {
        $this->productImageType = $productImageType;

        return $this;
    }

    /**
     * Get productImageType
     *
     * @return string 
     */
    public function getProductImageType()
    {
        return $this->productImageType;
    }

    /**
     * Set isPrimary
     *
     * @param boolean $isPrimary
     * @return EsProductImage
     */
    public function setIsPrimary($isPrimary)
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * Get isPrimary
     *
     * @return boolean 
     */
    public function getIsPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsProductImage
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

    /**
     * Returns the path of the image
     *
     * @return string
     */
    public function getDirectory()
    {
        $this->explodeImagePath();
        return $this->directory;
    }
    
    /**
     * Returns the filename of the image
     *
     * @return string
     */
    public function getFilename()
    {
        $this->explodeImagePath();
        return $this->filename;
    }
    
    /**
     * Separates the image directory and file name
     *
     */
    private function explodeImagePath()
    {
        if($this->directory === '' && $this->filename === ''){
            if(trim($this->productImagePath) === ''){
                $this->directory = 'assets/product/default/';
                $this->filename = 'default_product_img.jpg';
            }
            else{
                if(file_exists($this->productImagePath)){
                    $reversedPath = strrev($this->productImagePath);
                    $this->directory = substr($this->productImagePath,0,strlen($reversedPath)-strpos($reversedPath,'/'));
                    $this->filename  = substr($this->productImagePath,strlen($reversedPath)-strpos($reversedPath,'/'),strlen($reversedPath));
                }
                else{
                    $this->directory = 'assets/product/unavailable/';
                    $this->filename = 'unavailable_product_img.jpg';
                }
            }                
        }
    }
    
    
}
