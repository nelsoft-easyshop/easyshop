<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsBrand
 *
 * @ORM\Table(name="es_brand", uniqueConstraints={@ORM\UniqueConstraint(name="name_UNIQUE", columns={"name"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsBrandRepository")
 */
class EsBrand
{
    const IMAGE_DIRECTORY =  'assets/images/brands/';
    
    const IMAGE_UNAVAILABLE_FILE =  'unavailable/easyshop-logo.png';

    const CUSTOM_CATEGORY_ID = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_brand", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBrand;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1023, nullable=false)
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=512, nullable=false)
     */
    private $image = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="sort_order", type="boolean", nullable=false)
     */
    private $sortOrder = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=512, nullable=false)
     */
    private $url = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_main", type="boolean", nullable=false)
     */
    private $isMain = '0';



    /**
     * Get idBrand
     *
     * @return integer 
     */
    public function getIdBrand()
    {
        return $this->idBrand;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsBrand
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
     * Set description
     *
     * @param string $description
     * @return EsBrand
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return EsBrand
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set sortOrder
     *
     * @param boolean $sortOrder
     * @return EsBrand
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return boolean 
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return EsBrand
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set isMain
     *
     * @param boolean $isMain
     * @return EsBrand
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;

        return $this;
    }

    /**
     * Get isMain
     *
     * @return boolean 
     */
    public function getIsMain()
    {
        return $this->isMain;
    }
}
