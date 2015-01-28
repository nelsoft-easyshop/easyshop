<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsCat
 *
 * @ORM\Table(name="es_cat", indexes={@ORM\Index(name="fk_es_cat_es_cat1_idx", columns={"parent_id"}), @ORM\Index(name="ft_es_cat", columns={"name"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsCatRepository")
 */
class EsCat
{

    const ROOT_CATEGORY_ID = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_cat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCat;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=false)
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=512, nullable=false)
     */
    private $keywords = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="sort_order", type="boolean", nullable=false)
     */
    private $sortOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_main", type="boolean", nullable=false)
     */
    private $isMain;

    /**
     * @var string
     *
     * @ORM\Column(name="design1", type="string", length=255, nullable=false)
     */
    private $design1 = '';

    /**
     * @var string
     *
     * @ORM\Column(name="design2", type="string", length=255, nullable=false)
     */
    private $design2 = '';

    /**
     * @var string
     *
     * @ORM\Column(name="design3", type="string", length=255, nullable=false)
     */
    private $design3 = '';

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug = '';

    /**
     * @var \EasyShop\Entities\EsCat
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsCat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id_cat")
     * })
     */
    private $parent;

    /**
     * @var string
     */
    private $image = '';

    /**
     * Get idCat
     *
     * @return integer 
     */
    public function getIdCat()
    {
        return $this->idCat;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsCat
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
     * @return EsCat
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
     * Set keywords
     *
     * @param string $keywords
     * @return EsCat
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set sortOrder
     *
     * @param boolean $sortOrder
     * @return EsCat
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
     * Set isMain
     *
     * @param boolean $isMain
     * @return EsCat
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

    /**
     * Set design1
     *
     * @param string $design1
     * @return EsCat
     */
    public function setDesign1($design1)
    {
        $this->design1 = $design1;

        return $this;
    }

    /**
     * Get design1
     *
     * @return string 
     */
    public function getDesign1()
    {
        return $this->design1;
    }

    /**
     * Set design2
     *
     * @param string $design2
     * @return EsCat
     */
    public function setDesign2($design2)
    {
        $this->design2 = $design2;

        return $this;
    }

    /**
     * Get design2
     *
     * @return string 
     */
    public function getDesign2()
    {
        return $this->design2;
    }

    /**
     * Set design3
     *
     * @param string $design3
     * @return EsCat
     */
    public function setDesign3($design3)
    {
        $this->design3 = $design3;

        return $this;
    }

    /**
     * Get design3
     *
     * @return string 
     */
    public function getDesign3()
    {
        return $this->design3;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return EsCat
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set parent
     *
     * @param \EasyShop\Entities\EsCat $parent
     * @return EsCat
     */
    public function setParent(\EasyShop\Entities\EsCat $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \EasyShop\Entities\EsCat 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     *  Set $image
     *
     *  @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    
    /**
     *  Get $image
     *
     *  @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
}
