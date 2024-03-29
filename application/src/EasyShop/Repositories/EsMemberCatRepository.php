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
                AND is_delete = :deletedStatus
            ';
            
        if($excludeCategoryId !== 0){
            $sql .= " AND id_memcat != :excludeCategoryId";
        }
            
        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter("catName", $categoryName)
                    ->setParameter("memberId", $idMember)
                    ->setParameter("deletedStatus", \EasyShop\Entities\EsMemberCat::ACTIVE);

        if($excludeCategoryId !== 0){
            $query->setParameter("excludeCategoryId", $excludeCategoryId);
        }
        
        $numberOfCategories = $query->getSingleScalarResult();

        return !($numberOfCategories > 0);
    }

    /**
     *  Fetch top level custom categories of memberId in array form
     *
     *  @param integer $memberId
     *  @return array $customCategories
     */
    public function getTopLevelCustomCategories($memberId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_memcat','id_memcat');
        $rsm->addScalarResult('cat_name','cat_name');
        $rsm->addScalarResult('is_featured','is_featured');
        $rsm->addScalarResult('sort_order','sort_order');
        $rsm->addScalarResult('is_delete','is_delete');
        $rsm->addScalarResult('product_count','product_count');
        $rsm->addScalarResult('childList','childList');
        $sql = 'SELECT es_member_cat.id_memcat,
                    es_member_cat.cat_name,
                    es_member_cat.is_featured,
                    es_member_cat.sort_order,
                    es_member_cat.is_delete,
                    COUNT(es_member_prodcat.id_memprod) as product_count,
                    
                    GROUP_CONCAT(
                        DISTINCT CONCAT(CONCAT(CONCAT(CONCAT(level2.id_memcat, "~"), level2.cat_name), "~") ,level2.sort_order)
                            ORDER BY 
                        level2.sort_order ASC SEPARATOR "|"
                    ) as childList
                    
                FROM es_member_cat
                LEFT JOIN 
                    es_member_prodcat 
                    ON es_member_cat.id_memcat = es_member_prodcat.memcat_id
                LEFT JOIN 
                    es_product
                    ON es_product.is_draft = :nonDraft AND es_product.is_delete = :active
                    AND es_product.id_product = es_member_prodcat.product_id
                LEFT JOIN
                    es_member_cat as level2  
                    ON level2.parent_id = es_member_cat.id_memcat 
                    AND level2.is_delete = :categoryDeleteStatus
                WHERE
                    es_member_cat.member_id = :member_id
                    AND es_member_cat.is_delete = :categoryDeleteStatus
                    AND es_member_cat.parent_id = :parentCustomCategory
                GROUP BY es_member_cat.id_memcat
                ORDER BY es_member_cat.id_memcat DESC
                ';

        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('categoryDeleteStatus', EsMemberCat::ACTIVE)
                    ->setParameter('nonDraft', EsProduct::ACTIVE )
                    ->setParameter('active', EsProduct::ACTIVE )
                    ->setParameter('parentCustomCategory', EsMemberCat::PARENT );

        return $query->getResult();
    }
    
    /**
     *  Fetch custom categories of memberId in object form
     *
     *  @param integer $memberId
     *  @param integer[] $categoryIdFilters
     *  @param boolean $isChildOnly
     *  @return EasyShop\Entities\EsMemberCat[]
     */
    public function getCustomCategoriesObject($memberId, $categoryIdFilters = [], $isChildOnly = false)
    {
        $em = $this->_em;
        $queryBuilder = $em->createQueryBuilder()
                           ->select('mc')
                           ->from('EasyShop\Entities\EsMemberCat', 'mc')
                           ->where('mc.member = :memberId')
                           ->andWhere('mc.isDelete != :deleted');
        $queryBuilder->setParameter('memberId', $memberId); 
        $queryBuilder->setParameter('deleted', \EasyShop\Entities\EsMemberCat::DELETED); 
        if(!empty($categoryIdFilters)){
            $queryBuilder->andWhere('mc.idMemcat IN (:categoryIds)')
                         ->setParameter('categoryIds', $categoryIdFilters);
        }
        if($isChildOnly){
            $queryBuilder->andWhere('mc.parentId != :parentId')
                         ->setParameter('parentId',\EasyShop\Entities\EsMemberCat::PARENT);
        }

        $customCategories = $queryBuilder->getQuery()
                                         ->getResult();                      
        return $customCategories;
    }

    
    /**
     * Returns the highest sort order among a user's active categories
     *
     * @param integer $memberId
     * @param integer $parentCategoryId
     * @return integer
     */
    public function getHighestSortOrder($memberId, $parentCategoryId = \EasyShop\Entities\EsMemberCat::PARENT)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('maxSortOrder','maxSortOrder');
        $sql = 'SELECT 
                    MAX(sort_order) as maxSortOrder
                FROM 
                    es_member_cat
                WHERE
                    member_id = :memberId AND 
                    is_delete != :deleted AND 
                    parent_id = :parentId
                ';
        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter('memberId', $memberId)
                    ->setParameter('deleted', \EasyShop\Entities\EsMemberCat::DELETED )
                    ->setParameter('parentId', $parentCategoryId);
        $results = $query->getResult()[0];

        return (int)$results['maxSortOrder'];                  
    }

    /**
     * Gets the number of custom categories of a user
     * 
     * @param integer $memberId
     * @param boolean $isIncludeDeleted
     * @return integer
     */
    public function getNumberOfCustomCategories($memberId, $isIncludeDeleted = false)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('numberOfCategories','numberOfCategories');
        $sql = "SELECT 
                    COUNT(id_memcat) as numberOfCategories
                FROM 
                    es_member_cat
                WHERE
                    member_id = :member_id
                ";
        if(!$isIncludeDeleted){
            $sql .= " AND is_delete = :categoryDeleteStatus ";
        }
        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('categoryDeleteStatus', EsMemberCat::ACTIVE);
        $result = $query->getResult()[0];

        return (int)$result['numberOfCategories'];
    }
    
    /**
     * Gets the number of children of a memberCategory
     *
     * @param integer $memberCategoryId
     * @return integer
     */
    public function getNumberOfChildren($memberCategoryId)
    {
        $this->em = $this->_em;
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('COUNT(m.idMemcat)')
                     ->from('EasyShop\Entities\EsMemberCat','m')
                     ->where('m.parentId = :memberCategoryId')
                     ->andWhere('m.isDelete = :deleteStatus')
                     ->setParameter('memberCategoryId', $memberCategoryId)
                     ->setParameter('deleteStatus', \EasyShop\Entities\EsMemberCat::ACTIVE);

        $resultCount = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int)$resultCount;
    }
  
}
