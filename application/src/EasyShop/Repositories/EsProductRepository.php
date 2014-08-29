<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProduct; 
use EasyShop\Entities\EsProductImage;

class EsProductRepository extends EntityRepository
{
    /**
     * Find all records in database related to string given in parameters
     * @param  array  $stringCollection 
     * @return object
     */
    public function findByKeyword($stringCollection = array())
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsProduct', 'u');
        $rsm->addFieldResult('u', 'idProduct', 'idProduct');
        $rsm->addFieldResult('u', 'name', 'name');
        $rsm->addFieldResult('u', 'price', 'price'); 
        $rsm->addFieldResult('u', 'brief', 'brief');
        $rsm->addFieldResult('u', 'slug', 'slug');
        $rsm->addFieldResult('u', 'condition', 'condition');
        $rsm->addFieldResult('u', 'startdate', 'startdate');
        $rsm->addFieldResult('u', 'enddate', 'enddate');
        $rsm->addFieldResult('u', 'isPromote', 'isPromote');
        $rsm->addFieldResult('u', 'promoType', 'promoType');
        $rsm->addFieldResult('u', 'discount', 'discount');
        $rsm->addFieldResult('u', 'isSoldOut', 'isSoldOut');
        $query = $this->em->createNativeQuery("
        SELECT `id_product` as idProduct
            , `name`
            , `price`
            , `slug`
            , `brief`
            , `condition`
            , `startdate`
            , `enddate`
            , `is_promote` as isPromote
            , `promo_type` as promoType
            , `discount`
            , `is_sold_out` as isSoldOut
            , (ftscore_name + ftscore2_name + ftscore3_name + ftscore + ftscore2 + ftscore3 ) AS weight 
            FROM (
                SELECT 
                    MATCH (`name`) AGAINST (:param0 IN BOOLEAN MODE) * 3 AS ftscore_name,
                    MATCH (`search_keyword`) AGAINST (:param0 IN BOOLEAN MODE) * 1.5 AS ftscore,
                    MATCH (`name`) AGAINST (:param1 IN BOOLEAN MODE) * 2 AS ftscore2_name,
                    MATCH (`search_keyword`) AGAINST (:param1 IN BOOLEAN MODE) * 1 AS ftscore2, 
                    MATCH (`name`) AGAINST (:param2 IN BOOLEAN MODE) * 5 AS ftscore3_name,
                    MATCH (`search_keyword`) AGAINST (:param2 IN BOOLEAN MODE) * 2.5 AS ftscore3, 
                    id_product,`name`,price,brief,slug,`condition`,startdate, enddate,is_promote,promo_type,discount
                    ,`is_sold_out`
                FROM es_product
                WHERE is_delete = 0 AND is_draft = 0 ) AS score_tbl
        HAVING weight > 0
        ORDER BY weight   DESC 
        ", $rsm);
        $query->setParameter('param0', $stringCollection[0]);
        $query->setParameter('param1', $stringCollection[1]); 
        $query->setParameter('param2', $stringCollection[2]); 
        $results = $query->execute();  

        return $results;
    }

    public function findByPrice($startPrice = 0 ,$endPrice = 0,$ids = array())
    {
        $this->em =  $this->_em;
        
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
     * @param  array  $productId
     * @return array
     */
    public function getDetails($productId = array())
    {
        $this->em =  $this->_em;                       
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select(array('p.idProduct'
                                        ,'p.name'
                                        ,'p.price'  
                                        ,'p.brief' 
                                        ,'p.slug'
                                        ,'p.condition' 
                                        ,'p.startdate'
                                        ,'p.enddate'
                                        ,'p.isPromote'
                                        ,'p.promoType'
                                        ,'p.discount'
                                        ,'p.isSoldOut'
                                        ,'i.productImagePath'))
                                ->from('EasyShop\Entities\EsProduct','p')
                                ->leftJoin('EasyShop\Entities\EsProductImage','i','WITH','p.idProduct = i.product AND i.isPrimary = 1')
                                ->where(
                                        $qb->expr()->in('p.idProduct', $productId)
                                    )
                                ->getQuery();
        $result = $qbResult->getResult();

        return $result;
    }

    /**
     * Get All attributes of given product
     * @param  array  $productId
     * @return array
     */
    public function getAttributes($productId = array()){
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('product_id', 'product_id'); 
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('attr_value', 'attr_value');

        $query = $this->em->createNativeQuery("
            SELECT product_id,name,attr_value FROM (
                SELECT 
                    a.product_id as product_id
                    , b.name AS name
                    , a.attr_value 
                  FROM
                    `es_product_attr` a
                    , `es_attr` b 
                  WHERE a.`attr_id` = b.`id_attr` 
                  AND b.datatype_id NOT IN (2)
                  GROUP BY a.product_id
                    , name
                    , attr_value
                UNION ALL 
                SELECT 
                     a.product_id as product_id
                    , a.`field_name`
                    , b.value_name 
                FROM
                    es_optional_attrhead a
                    , es_optional_attrdetail b 
                WHERE a.`id_optional_attrhead` = b.`head_id` 
                GROUP BY a.product_id
                    , field_name
                    , value_name
            ) a
            WHERE product_id IN (:ids)
        ", $rsm);

        $query->setParameter('ids', $productId); 
        $results = $query->getResult();

        return $results;
    }
}

                // 