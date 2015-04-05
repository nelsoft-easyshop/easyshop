<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use Doctrine\ORM\Tools\Pagination\Paginator;
use EasyShop\Category\CategoryManager as CategoryManager;

class EsMemberProdcatRepository extends EntityRepository
{
    /**
     *  Count number of products under custom category
     *
     *  @param integer $memberId
     *  @param integer[] $memcatIds
     *  @return integer
     */
    public function countCustomCategoryProducts($memberId, $memcatIds)
    {
        $em = $this->_em;
        $dql = "
            SELECT COUNT(pc.idMemprod)
            FROM EasyShop\Entities\EsMemberProdcat pc
            JOIN pc.memcat mc
            JOIN mc.member m
            JOIN pc.product p
            WHERE mc.idMemcat IN (:memcat_id)
                AND m.idMember = :member_id
                AND p.isDelete = 0
                AND p.isDraft = 0
        ";

        $query = $em->createQuery($dql)
                    ->setParameter("member_id", $memberId)
                    ->setParameter("memcat_id", $memcatIds);

        return $query->getSingleScalarResult();
    }


    /**
     *  Fetch Custom categorized products
     *
     *  @param integer $memberId
     *  @param integer[] $memcatIds
     *  @param integer $prodLimit
     *  @param integer $offset
     *  @param mixed $orderBy
     *  @param string $searchString
     *
     *  @return integer[]  
     */
    public function getPagedCustomCategoryProducts($memberId, $memcatIds, $prodLimit, $offset = 0, $orderBy = [ CategoryManager::ORDER_PRODUCTS_BY_SORTORDER => 'ASC' ], $searchString = "")
    {      
        $orderByDirections = [
            'ASC' => 'ASC', 
            'DESC' => 'DESC',
        ];
        $orderByFields = [
            CategoryManager::ORDER_PRODUCTS_BY_SORTORDER => [ 'pc.sortOrder' ],
            CategoryManager::ORDER_PRODUCTS_BY_CLICKCOUNT =>  [ 'p.clickcount' ], 
            CategoryManager::ORDER_PRODUCTS_BY_LASTCHANGE => [ 'p.lastmodifieddate' ],
            CategoryManager::ORDER_PRODUCTS_BY_HOTNESS => [ 'p.isHot' , 'p.clickcount' ],
        ];
        $orderByField = isset($orderByFields[key($orderBy)]) ? 
                        $orderByFields[key($orderBy)] : $orderByFields[CategoryManager::ORDER_PRODUCTS_BY_SORTORDER];
        $orderByDirection = isset($orderByDirections[reset($orderBy)]) ? 
                            $orderByDirections[reset($orderBy)] : $orderByDirections['ASC'];

        $productIds = [];

        $orderCondition = "";
        foreach($orderBy as $column=>$order){
            $orderCondition .= $column . " " . $order . ", ";
        }
        $orderCondition = rtrim($orderCondition, ", ");

        $em = $this->_em;
        $dql = "SELECT pc
                FROM EasyShop\Entities\EsMemberProdcat pc
                JOIN pc.memcat mc
                JOIN mc.member m
                JOIN pc.product p
                WHERE mc.idMemcat IN (:cat_ids)
                    AND m.idMember = :member_id
                    AND p.isDelete = 0
                    AND p.isDraft = 0";
        
        if($searchString !== ""){
            $dql .= " AND p.name LIKE :queryString ";
        }
         
        $dql .= " GROUP BY p.idProduct";
         
        $orderByString = "";
        foreach($orderByField as $field){
            $orderByString .= $field." ".$orderByDirection.",";
        }
        $orderByString = $orderByString." p.idProduct DESC";
        $dql .= " ORDER BY " . $orderByString;  
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('cat_ids', $memcatIds);

        if($searchString !== ""){
            $queryString = '%'.$searchString.'%';
            $query->setParameter('queryString', $queryString);
        }

        $query->setFirstResult($offset)
              ->setMaxResults($prodLimit);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
   
        foreach($paginator as $prod){
            $productIds[] = $prod->getProduct()->getIdProduct();
        }

        return $productIds;
    }

    /**
     *  Get all custom categorized product ids
     *
     *  @param integer $memberId
     *  @param integer[] $memcatIds
     *  @param string $condition
     *
     *  @return array - array of product ids
     */
    public function getAllCustomCategoryProducts($memberId, $memcatIds, $condition, $orderBy = [ CategoryManager::ORDER_PRODUCTS_BY_SORTORDER => 'ASC' ])
    {
        $productIds = [];
        
        $orderByDirections = [
            'ASC' => 'ASC', 
            'DESC' => 'DESC',
        ];
        $orderByFields = [
            CategoryManager::ORDER_PRODUCTS_BY_SORTORDER => [ 'pc.sortOrder' ],
            CategoryManager::ORDER_PRODUCTS_BY_CLICKCOUNT =>  [ 'p.clickcount' ], 
            CategoryManager::ORDER_PRODUCTS_BY_LASTCHANGE => [ 'p.lastmodifieddate' ],
            CategoryManager::ORDER_PRODUCTS_BY_HOTNESS => [ 'p.isHot' , 'p.clickcount' ],
        ];
        $orderByField = isset($orderByFields[key($orderBy)]) ? 
                        $orderByFields[key($orderBy)] : $orderByFields[CategoryManager::ORDER_PRODUCTS_BY_SORTORDER];
        $orderByDirection = isset($orderByDirections[reset($orderBy)]) ? 
                            $orderByDirections[reset($orderBy)] : $orderByDirections['ASC'];

        $orderByString = "";
        foreach($orderByField as $field){
            $orderByString .= $field." ".$orderByDirection.",";
        }
        $orderByString = rtrim($orderByString, ",");

        $em = $this->_em;
        $dql = "SELECT pc,p
                FROM EasyShop\Entities\EsMemberProdcat pc
                JOIN pc.memcat mc
                JOIN mc.member m
                JOIN pc.product p
                WHERE mc.idMemcat IN (:cat_id)
                    AND m.idMember = :member_id
                    AND p.isDelete = 0
                    AND p.isDraft = 0";
        if($condition !== "") {
            $dql .= "AND p.condition = :condition ";
        }
        $dql .= "ORDER BY ".$orderByString;
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('cat_id', $memcatIds);
        if($condition !== "") {
            $query->setParameter("condition", $condition);
        }                    

        $result = $query->getResult();

        foreach($result as $prod){
            $productIds[] = $prod->getProduct()->getIdProduct();
        }

        return $productIds;
    }
    
    /**
     * Retrieves the highest product sort order within a custom category
     *
     * @param integer $memberCategoryId
     * @return integer
     */
    public function getHighestProductSortOrderWithinCategory($memberCategoryId)
    {
        $em = $this->_em;
        $dql = "
            SELECT 
                MAX(pc.sortOrder)
            FROM 
                EasyShop\Entities\EsMemberProdcat pc
            JOIN 
                pc.product p
            WHERE 
                pc.memcat = :memberCategoryId
                AND p.isDelete = :deleteStatus
                AND p.isDraft = :draftStatus
        ";

        $query = $em->createQuery($dql)
                    ->setParameter("memberCategoryId", $memberCategoryId)
                    ->setParameter("deleteStatus", \EasyShop\Entities\EsProduct::ACTIVE)
                    ->setParameter("draftStatus", \EasyShop\Entities\EsProduct::ACTIVE);

        $maxSortOrder = $query->getSingleScalarResult();
        $maxSortOrder = $maxSortOrder === null ? 0: $maxSortOrder;
        
        return (int)$maxSortOrder;
    }
    
    /**
     * Get Multiple member products by ID
     *
     * @param integer[] $productIds
     * @param integer $memberCategoryId
     * @return EasyShop\Entities\EsMemberProdcat
     */
    public function getMemberProductsByProductIds($productIds, $memberCategoryId = null)
    {
        $memberProducts = [];
        if(empty($productIds) === false){
            $em = $this->_em;
            $qb = $em->createQueryBuilder();
            $qb->select('mp')
               ->from('EasyShop\Entities\EsMemberProdcat', 'mp')
               ->where($qb->expr()->in('mp.product', $productIds));
            if($memberCategoryId !== null){
                $qb->andWhere('mp.memcat = :memberCategoryId');
                $qb->setParameter('memberCategoryId', $memberCategoryId);
            }
            $query = $qb->getQuery();
            $memberProducts = $query->getResult();
        }
        return $memberProducts;
    }
    
}
