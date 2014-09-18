<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsCat;
use Doctrine\ORM\Query\ResultSetMapping;

class EsCatRepository extends EntityRepository
{
    /**
     * Get all children category recursively up to last category of the selected category
     * @param  integer $categoryId
     * @param  boolean $returnAsString
     * @return mixed
     */
    public function getChildCategoryRecursive($categoryId = 1,$returnAsString = false)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('categoryList', 'categoryList');
             $query = $this->em->createNativeQuery("
                     SELECT 
                     CASE
                        WHEN `GetFamilyTree` (id_cat) = '' 
                     THEN :categoryId
                        ELSE CONCAT(:categoryId,',',`GetFamilyTree` (id_cat))
                     END as categoryList
                     FROM
                     `es_cat` 
                     WHERE id_cat != 1 
                     AND id_cat = :categoryId ;
             ", $rsm);

        $query->setParameter('categoryId', $categoryId); 
        $results = $query->getOneOrNullResult();
        
        if($returnAsString){
            return $results['categoryList'];
        }

        return explode(',', $results['categoryList']);
    }

    /**
     * Get all parent of parent of the selected category
     * @param  integer $categoryId
     * @return array
     */
    public function getParentCategoryRecursive($categoryId = 1)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('idCat', 'idCat');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('keywords', 'keywords');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('parent', 'parent');
        $rsm->addScalarResult('sortOrder', 'sortOrder');
        $rsm->addScalarResult('isMain', 'isMain');
             $query = $this->em->createNativeQuery("
                SELECT 
                    T2.id_cat as idCat,
                    T2.name,
                    T2.slug,
                    T2.keywords,
                    T2.description,
                    T2.parent_id as parent,
                    T2.sort_order,
                    T2.is_main
                FROM (
                    SELECT
                        @r AS _id,
                        (SELECT @r := parent_id FROM es_cat WHERE id_cat = _id) AS parent_id,
                        @l := @l + 1 AS lvl
                    FROM
                        (SELECT @r := :categoryId, @l := 0) vars,
                        es_cat h
                    WHERE @r != 1
                ) T1
                JOIN es_cat T2
                ON T1._id = T2.id_cat
                ORDER BY T1.lvl DESC
             ", $rsm);

        $query->setParameter('categoryId', $categoryId); 
        $results = $query->getResult();
        
        return $results;
    }
}

