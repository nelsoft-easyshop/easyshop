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
    private $categoryName;

    /**
     * Sort Order
     *
     * @var integer
     */
    private $sortOrder;

    /** 
     * Member Category Id
     *
     * @var integer
     */
    private $memberCategoryId;

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
            'children' => [],
        ];
        foreach($this->children as $child){
            $categoryArray['children'][] = $child->toArray();
        }
        return $categoryArray;
    }


}