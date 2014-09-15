<?php

namespace EasyShop\Category;

/**
 * Promo Class
 *
 * @author Ryan Vasquez
 */
class CategoryManager
{

    /**
     * Constructor.
     */
    public function __construct()
    {          
        $this->ci = get_instance();  
        $this->ci->config->load('protected_category', TRUE);
        $this->protectedCategories = $this->ci->config->item('protected_category');  
    }

    /**
     * Applies if protectedCategory will show or not
     * @param  object $categoryList
     * @param  boolean $isAdmin
     * @return object
     */
    public function applyProtectedCategory($categoryList,$isAdmin = FALSE)
    {
        $protectedCategories =  $this->protectedCategories; 
        if(!$isAdmin){
            foreach($categoryList as $key => $value){  
                if((in_array($value->getIdCat(),$protectedCategories) || $value->getIdCat() == 1) && !$isAdmin){
                    unset($categoryList[$key]);
                } 
            }
        } 

        return $categoryList;
    }
}