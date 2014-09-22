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
     * Codeigniter Config Loader
     *
     * @var EasyShop\CollectionHelper\CollectionHelper
     */
    private $configLoader;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;


    /**
     * Constructor.
     */
    public function __construct($configLoader,$em)
    {
        $this->configLoader = $configLoader;
        $this->em = $em;
    }

    /**
     * Applies if protectedCategory will show or not
     * @param  object $categoryList
     * @param  boolean $isAdmin
     * @return object
     */
    public function applyProtectedCategory($categoryList,$isAdmin = FALSE)
    {
        $protectedCategories =  $this->configLoader->getItem('protected_category');
        if(!$isAdmin){
            foreach($categoryList as $key => $value){  
                if((in_array($value->getIdCat(),$protectedCategories) || intval($value->getIdCat()) === 1) && !$isAdmin){
                    unset($categoryList[$key]);
                } 
            }
        } 

        return $categoryList;
    }

    public function setCategoryImage($categoryList)
    {
        foreach($categoryList as $key => $value){ 
                $categoryImage = $this->em->getRepository('EasyShop\Entities\EsCatImg')
                            ->findOneBy(['idCat' => $value->getIdCat()]);
                $imagePath = ($categoryImage) ? $categoryImage->getPath() : "";
                $value->setImage($imagePath); 
        } 
        
        return $categoryList;
    }
}