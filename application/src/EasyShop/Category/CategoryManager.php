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
     * [__construct description]
     */
    public function __construct() {}

    /**
     * Applies if protectedCategory will show or not
     * @param  object $categoryList
     * @param  boolean $isAdmin
     * @return object
     */
    public function applyProtectedCategory($categoryList,$isAdmin = FALSE)
    {
        $CI = get_instance();  
        $CI->config->load('protected_category', TRUE);
        $protectedCategories = $CI->config->item('protected_category');  
        if(!$isAdmin){
            foreach($categoryList as $key => $value){ 
                if((in_array($value->getIdCat(),$protectedCategories) || $value->getIdCat() == 1) && !$isAdmin){
                    unset($categoryList[$key]);
                    continue;
                } 
            }
        } 

        return $categoryList;
    }
}