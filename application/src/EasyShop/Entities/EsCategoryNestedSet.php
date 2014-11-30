<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsCategoryNestedSet
 *
 * @ORM\Table(name="es_category_nested_set", indexes={@ORM\Index(name="fk_es_category_nested_set_es_cat_idx", columns={"original_category_id"})})
 * @ORM\Entity
 */
class EsCategoryNestedSet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_category_nested_set", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategoryNestedSet;

    /**
     * @var integer
     *
     * @ORM\Column(name="left", type="integer", nullable=false)
     */
    private $left = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="right", type="integer", nullable=false)
     */
    private $right = '0';

    /**
     * @var \EasyShop\Entities\EsCat
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsCat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="original_category_id", referencedColumnName="id_cat")
     * })
     */
    private $originalCategory;



    /**
     * Get idCategoryNestedSet
     *
     * @return integer 
     */
    public function getIdCategoryNestedSet()
    {
        return $this->idCategoryNestedSet;
    }

    /**
     * Set left
     *
     * @param integer $left
     * @return EsCategoryNestedSet
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left
     *
     * @return integer 
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set right
     *
     * @param integer $right
     * @return EsCategoryNestedSet
     */
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * Get right
     *
     * @return integer 
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set originalCategory
     *
     * @param \EasyShop\Entities\EsCat $originalCategory
     * @return EsCategoryNestedSet
     */
    public function setOriginalCategory(\EasyShop\Entities\EsCat $originalCategory = null)
    {
        $this->originalCategory = $originalCategory;

        return $this;
    }

    /**
     * Get originalCategory
     *
     * @return \EasyShop\Entities\EsCat 
     */
    public function getOriginalCategory()
    {
        return $this->originalCategory;
    }
}
