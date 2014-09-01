<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsCat;
use Doctrine\ORM\Query\ResultSetMapping;

class EsCatRepository extends EntityRepository
{

    public function getChildCategoryRecursive($categoryId)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('categoryList', 'categoryList');
             $query = $this->em->createNativeQuery("
                     SELECT 
                     CASE
                        WHEN `GetFamilyTree` (id_cat) = '' 
                     THEN '0,0' 
                        ELSE CONCAT(:categoryId,',',`GetFamilyTree` (id_cat))
                     END as categoryList
                     FROM
                     `es_cat` 
                     WHERE id_cat != 1 
                     AND id_cat = :categoryId ;
             ", $rsm);

        $query->setParameter('categoryId', $categoryId); 
        $results = $query->getOneOrNullResult();
        
        return explode(',', $results['categoryList']);
    }
}