<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsMember;

class EsMemberCatRepository extends EntityRepository
{
    /**
     *  Fetch custom categories of memberId in array form
     *
     *  @param integer $memberId
     *  @return array $customCategories
     */
    public function getCustomCategoriesArray($memberId, $categoryNameFilter = [])
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_memcat','id_memcat');
        $rsm->addScalarResult('cat_name','cat_name');
        $rsm->addScalarResult('is_featured','is_featured');

        $sql = 'SELECT id_memcat
                    , cat_name
                    , is_featured
                FROM es_member_cat
                WHERE member_id = :member_id
                ORDER BY id_memcat DESC
                ';

        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter('member_id', $memberId);

        return $query->getResult();
    }
    
    /**
     *  Fetch custom categories of memberId in object form
     *
     *  @param integer $memberId
     *  @param integer[] $categoryIdFilters
     *  @return EasyShop\Entities\EsMemberCat[]
     */
    public function getCustomCategoriesObject($memberId, $categoryIdFilters = [])
    {
    
        $em = $this->_em;
        $queryBuilder = $em->createQueryBuilder()
                           ->select('mc')
                           ->from('EasyShop\Entities\EsMemberCat', 'mc')
                           ->where('mc.member = :memberId');
        $queryBuilder->setParameter('memberId', $memberId); 
        if(!empty($categoryNameFilter)){
            $queryBuilder->andWhere('mc.catName IN (:categoryIds)')
                         ->setParameter('categoryIds', $categoryIdFilters);
        }
        $customCategories = $queryBuilder->getQuery()
                                         ->getResult();
                                         
        return $customCategories;
    }
  

}
