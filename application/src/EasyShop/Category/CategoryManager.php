<?php

namespace EasyShop\Category;

class CategoryManager
{
    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($em)
    {
        $this->em = $em;
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
        $memberOb = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $category = new EsMemberCat();
        $category->setCatName($catName)
                 ->setMember($memberObj);
        $this->em->persist($category);
        $this->em->flush();

        return $category->getIdMemcat();
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
}
