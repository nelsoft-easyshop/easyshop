<?php

namespace EasyShop\Category;

/**
 * Category Wrapper for custom categories 
 * This allows the category hierarchical structure to be more accessible
 * 
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class CategoryWrapper
{
    /**
     * Category name
     *
     * @var string
     */
    private $categoryName = '';

    /**
     * Sort Order
     *
     * @var integer
     */
    private $sortOrder = 0;

    /** 
     * Member Category Id
     *
     * @var integer
     */
    private $memberCategoryId = 0;
    
    /** 
     * EsCat Category Id
     *
     * @var integer
     */
    private $nonMemberCategoryId = 0;
    
    /** 
     * Boolean value if the category is custom or not
     *
     * @var boolean
     */
    private $isCustom = true;


    /**
     * Children categories
     *
     * @var CategoryWrapper[]
     */
    private $children = [];

    /**
     * Set the category name 
     *
     * @param string $categoryName
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }
    
    /**
     * Get the category name 
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }
    
    /**
     * Set the sort order 
     *
     * @param integer $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }
    
    /**
     * Get the sort order 
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set the member category 
     *
     * @param integer $sortOrder
     */
    public function setMemberCategoryId($memberCategoryId)
    {
        $this->memberCategoryId = $memberCategoryId;
    }
    
    /**
     * Get the member category 
     *
     * @return integer
     */
    public function getMemberCategoryId()
    {
        return $this->memberCategoryId;
    }
    
    /**
     * Add a child category
     *
     * @param CategoryWrapper $child
     */
    public function addChild(CategoryWrapper $child)
    {
        $this->children[] = $child;
    }
    
    /**
     * Get the children 
     *
     * @return CategoryWrapper
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set the isCustom flag
     *
     * @param booleam $isCustom
     */
    public function setIsCustom($isCustom)
    {
        $this->isCustom = $isCustom;
    }
    
    /**
     * Get the isCustom flag
     *
     * @return boolean
     */
    public function getIsCustom()
    {
        return $this->isCustom;
    }
    
    
    /**
     * Set the EsCat Category Id
     *
     * @param integer $isCustom
     */
    public function setNonMemberCategoryId($categoryId)
    {
        $this->nonMemberCategoryId = $categoryId;
    }
    
    /**
     * Get the EsCat Category Id
     *
     * @return integer
     */
    public function getNonMemberCategoryId()
    {
        return $this->nonMemberCategoryId;
    }
    
    /**
     * Checks if a certain ID has already been appended to the children array
     *
     * @param integer $childId
     * @return boolean
     */
    public function isChildAvailable($childId)
    {
        $childIds = $this->getChildrenAsArray();
        
        return in_array($childId, $childIds);
    }
    
    /**
     * Get childs IDs as array
     *
     * @return integer[]
     */
    public function getChildrenAsArray()
    {
        $childIds = [];
        foreach($this->children as $child){
            $childIds[] = $child->getId();
        }
        
        return $childIds;
    }
    
    
    /**
     * Get the category Id depending on if the category is custom or nit
     *
     * @return integer[]
     */
    public function getId()
    {
        return $this->isCustom ? $this->memberCategoryId : $this->nonMemberCategoryId;
    }
    
    /**
     * Convert the entire object into an array
     *
     * @return mixed
     */
    public function toArray()
    {
        $categoryArray = [
            'categoryName' => $this->categoryName,
            'sortOrder' => $this->sortOrder,
            'memberCategoryId' => $this->memberCategoryId,
            'nonMemberCategoryId' => $this->nonMemberCategoryId,
            'isCustom' => $this->isCustom,
            'children' => [],
        ];
        foreach($this->children as $child){
            $categoryArray['children'][] = $child->toArray();
        }
        return $categoryArray;
    }

}

