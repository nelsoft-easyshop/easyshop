<?php

namespace EasyShop\Category;

use EasyShop\Entities\EsMemberCat;

/**
 *  Promo class
 *
 *  @author Ryan Vasquez
 *  @author stephenjanz
 */
class CategoryManager
{
    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Codeigniter Config Loader
     *
     * @var EasyShop\CollectionHelper\CollectionHelper
     */
    private $configLoader;

    /**
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($configLoader, $em)
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

    /**
     *  Create custom category for memberId @table es_member_cat
     *
     *  @param string $catName - category name
     *
     *  @return integer $lastId
     */
    public function createCustomCategory($catName, $memberId)
    {
        $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $category = new EsMemberCat();
        $category->setCatName($catName)
                 ->setMember($memberObj)
                 ->setCreatedDate(date_create());
        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    /**
     *  Set category as featured @table es_member_cat. is_featured = 1
     *  Pass an array of categoryIDs for batch updating.
     *
     *  @param array $catId - category ID
     */
    public function setCustomCategoryAsFeatured($catId, $memberId)
    {
        $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);

        if( !is_array($catId) ){
            $catId = array($catId);
        }

        foreach($catId as $categoryId){
            $category = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                ->findOneBy(array(
                                                'idMemcat' => $categoryId, 
                                                'member' => $memberObj
                                            ));
            $category->setIsFeatured(1);
            $this->em->persist($category);
        }

        $this->em->flush();
    }

    /**
     * Set the image/icon of the category
     * @param mixed $categoryList
     * @return mixed
     */
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
