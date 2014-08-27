<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProduct;

class EsProductRepository extends EntityRepository
{

    /**
     * Find all records in database related to string given in parameters
     * @param  array  $stringCollection 
     * @return object
     */
    public function findByKeyword($stringCollection = array())
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsProduct', 'u');
        $rsm->addFieldResult('u', 'idProduct', 'idProduct');
        $rsm->addFieldResult('u', 'name', 'name');
        $rsm->addFieldResult('u', 'price', 'price'); 
        $rsm->addFieldResult('u', 'brief', 'brief');
        $rsm->addFieldResult('u', 'slug', 'slug');
        $rsm->addFieldResult('u', 'condition', 'condition');
        $query = $this->em->createNativeQuery("
        SELECT `id_product` as idProduct,`name`,price,slug,brief,`condition`,(ftscore_name + ftscore2_name + ftscore3_name + ftscore + ftscore2 + ftscore3 ) AS weight FROM (
            SELECT 
                MATCH (`name`) AGAINST (:param0 IN BOOLEAN MODE) * 3 AS ftscore_name,
                MATCH (`search_keyword`) AGAINST (:param0 IN BOOLEAN MODE) * 1.5 AS ftscore,
                MATCH (`name`) AGAINST (:param1 IN BOOLEAN MODE) * 2 AS ftscore2_name,
                MATCH (`search_keyword`) AGAINST (:param1 IN BOOLEAN MODE) * 1 AS ftscore2, 
                MATCH (`name`) AGAINST (:param2 IN BOOLEAN MODE) * 5 AS ftscore3_name,
                MATCH (`search_keyword`) AGAINST (:param2 IN BOOLEAN MODE) * 2.5 AS ftscore3, 
              id_product,`name`,price,brief,slug,`condition`
            FROM es_product  ) AS score_tbl
        HAVING weight > 0
        ORDER BY weight DESC, `name` DESC 
        ", $rsm);
        $query->setParameter('param0', $stringCollection[0]);
        $query->setParameter('param1', $stringCollection[1]); 
        $query->setParameter('param2', $stringCollection[2]); 
        $results = $query->execute();  

        return $results;
    }

    public function findByPrice($startPrice = 0 ,$endPrice = 0,$ids = array())
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
        
        if(count($ids) > 0){
            $qb = $this->em->createQueryBuilder()
                            ->select('u')
                            ->from('EasyShop\Entities\EsProduct','u')
                            ->where('u.price >= :startPrice')
                            ->andWhere('u.price <= :endPrice')
                            ->andWhere('u.idProduct IN (:ids)')
                            ->setParameter('ids', $ids)
                            ->setParameter('startPrice', $startPrice)
                            ->setParameter('endPrice', $endPrice)
                            ->getQuery();
        }
        else{
            $qb = $this->em->createQueryBuilder()
                            ->select('u')
                            ->from('EasyShop\Entities\EsProduct','u')
                            ->where('u.price >= :startPrice')
                            ->andWhere('u.price <= :endPrice')
                            ->andWhere('u.idProduct IN (:ids)')
                            ->setParameter('ids', $ids)
                            ->setParameter('startPrice', $startPrice)
                            ->setParameter('endPrice', $endPrice)
                            ->getQuery();
        }
        $result = $qb->getResult();

        return $result;
    }

    /**
     * Get all product details with given product id
     * @param  array  $productId [description]
     * @return [type]            [description]
     */
    public function getDetails($productId = array())
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findBy(['idProduct' => $productId]); 

        return $products;
    }
}
