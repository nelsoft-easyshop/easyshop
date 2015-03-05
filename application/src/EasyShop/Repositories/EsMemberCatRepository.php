<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsProduct;


class EsMemberCatRepository extends EntityRepository
{

    /**
     *  Checks if a category name is available
     *
     *  @param string $categoryName
     *  @param integer $idMember
     *  @param integer $excludeCategoryId
     *  @return bool
     */
    public function isCustomCategoryNameAvailable($categoryName, $idMember, $excludeCategoryId = 0)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count','count');
        $sql = 
            'SELECT
                COUNT(*) as count
            FROM
                es_member_cat
            WHERE 
                cat_name = :catName 
                AND member_id = :memberId
            ';
            
        if($excludeCategoryId !== 0){
            $sql .= " AND id_memcat != :excludeCategoryId";
        }
            
        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter("catName", $categoryName)
                    ->setParameter("memberId", $idMember);

        if($excludeCategoryId !== 0){
            $query->setParameter("excludeCategoryId", $excludeCategoryId);
        }
        
        $numberOfCategories = $query->getSingleScalarResult();

        return !($numberOfCategories > 0);
    }

    /**
     *  Fetch custom categories of memberId in array form
     *
     *  @param integer $memberId
     *  @return array $customCategories
     */
    public function getCustomCategoriesArray($memberId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_memcat','id_memcat');
        $rsm->addScalarResult('cat_name','cat_name');
        $rsm->addScalarResult('is_featured','is_featured');
        $rsm->addScalarResult('sort_order','sort_order');
        $rsm->addScalarResult('is_delete','is_delete');
        $rsm->addScalarResult('product_count','product_count');
        $sql = 'SELECT es_member_cat.id_memcat,
                    es_member_cat.cat_name,
                    es_member_cat.is_featured,
                    es_member_cat.sort_order,
                    es_member_cat.is_delete,
                    COUNT(es_member_prodcat.id_memprod) as product_count
                FROM es_member_cat
                LEFT JOIN es_member_prodcat 
                    ON es_member_cat.id_memcat = es_member_prodcat.memcat_id
                LEFT JOIN es_product
                    ON es_product.is_draft = :nonDraft AND es_product.is_delete = :active
                    AND es_product.id_product = es_member_prodcat.product_id
                WHERE es_member_cat.member_id = :member_id
                GROUP BY es_member_cat.id_memcat
                ORDER BY es_member_cat.id_memcat DESC
                ';

        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('nonDraft', EsProduct::ACTIVE )
                    ->setParameter('active', EsProduct::ACTIVE );

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
        if(!empty($categoryIdFilters)){
            $queryBuilder->andWhere('mc.idMemcat IN (:categoryIds)')
                         ->setParameter('categoryIds', $categoryIdFilters);
        }

        $customCategories = $queryBuilder->getQuery()
                                         ->getResult();                      
        return $customCategories;
    }
  

}
