<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EsMemberProdcatRepository extends EntityRepository
{
    /**
     *  Count number of products under custom category
     *
     *  @return integer
     */
    public function countCustomCategoryProducts($memberId, $memcatId)
    {
        $em = $this->_em;
        $dql = "
            SELECT COUNT(pc.idMemprod)
            FROM EasyShop\Entities\EsMemberProdcat pc
            JOIN pc.memcat mc
            JOIN mc.member m
            JOIN pc.product p
            WHERE mc.idMemcat = :memcat_id
                AND m.idMember = :member_id
                AND p.isDelete = 0
                AND p.isDraft = 0
        ";

        $query = $em->createQuery($dql)
                    ->setParameter("member_id", $memberId)
                    ->setParameter("memcat_id", $memcatId);

        return $query->getSingleScalarResult();
    }


    /**
     *  Fetch Custom categorized products
     *
     *  @return array - array of product ids
     */
    public function getPagedCustomCategoryProducts($memberId, $memcatId, $prodLimit, $page = 0, $orderBy = array("idProduct" => "DESC") )
    {
        $productIds = array();

        // Generate Order by condition
        $orderCondition = "";
        foreach($orderBy as $column=>$order){
            $orderCondition .= "p." . $column . " " . $order . ", ";
        }
        $orderCondition = rtrim($orderCondition, ", ");

        $em = $this->_em;
        $dql = "SELECT pc,p
                FROM EasyShop\Entities\EsMemberProdcat pc
                JOIN pc.memcat mc
                JOIN mc.member m
                JOIN pc.product p
                WHERE mc.idMemcat = :cat_id
                    AND m.idMember = :member_id
                    AND p.isDelete = 0
                    AND p.isDraft = 0
                ORDER BY " . $orderCondition;

        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('cat_id', $memcatId)
                    ->setFirstResult($page)
                    ->setMaxResults($prodLimit);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        foreach($paginator as $prod){
            $productIds[] = $prod->getProduct()->getIdProduct();
        }

        return $productIds;
    }

    /**
     *  Get all custom categorized product ids
     *
     *  @param integer $memberId
     *  @param array $memcatId
     *  @param string $condition
     *
     *  @return array - array of product ids
     */
    public function getAllCustomCategoryProducts($memberId, $memcatId, $condition, $orderBy = array("idProduct" => "DESC"))
    {
        $productIds = array();

        // Generate Order by condition
        $orderCondition = "";
        foreach($orderBy as $column=>$order){
            $orderCondition .= "p." . $column . " " . $order . ", ";
        }
        $orderCondition = rtrim($orderCondition, ", ");

        $em = $this->_em;
        $dql = "SELECT pc,p
                FROM EasyShop\Entities\EsMemberProdcat pc
                JOIN pc.memcat mc
                JOIN mc.member m
                JOIN pc.product p
                WHERE mc.idMemcat = :cat_id
                    AND m.idMember = :member_id
                    AND p.isDelete = 0
                    AND p.isDraft = 0";
        if($condition !== "") {
            $dql .= "AND p.condition = :condition ";
        }
        $dql .= "ORDER BY ".$orderCondition;
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('cat_id', $memcatId);
        if($condition !== "") {
            $query->setParameter("condition", $condition);
        }                    

        $result = $query->getResult();

        foreach($result as $prod){
            $productIds[] = $prod->getProduct()->getIdProduct();
        }

        return $productIds;
    }
}
