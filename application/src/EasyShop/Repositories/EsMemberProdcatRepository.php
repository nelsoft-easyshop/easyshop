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
     *  Fetch Custom categorized products
     *
     *  @return array - array of objects
     */
    public function getCustomCategoryProduct($memberId, $memcatId, $prodLimit, $page = 0, $orderBy = "p.idProduct DESC")
    {
        $product = array();
        $page = intval($page) <= 0 ? 0 : (intval($page)-1) * $prodLimit;

        $em = $this->_em;
        $dql = "SELECT pc, p
                FROM EasyShop\Entities\EsMemberProdcat pc
                JOIN pc.memcat mc
                JOIN mc.member m
                JOIN pc.product p
                WHERE mc.idMemcat = :cat_id
                    AND m.idMember = :member_id
                    AND p.isDelete = 0
                    AND p.isDraft = 0
                ORDER BY " . $orderBy;
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('cat_id', $memcatId)
                    ->setFirstResult($page)
                    ->setMaxResults($prodLimit);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        foreach($paginator as $prod){
            $product[] = $prod->getProduct();
        }

        return $product;
    }

    /**
     *  Count number of products under custom category
     *
     *  @return integer
     */
    public function countCustomCategoryProduct($memberId, $memcatId)
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
}
