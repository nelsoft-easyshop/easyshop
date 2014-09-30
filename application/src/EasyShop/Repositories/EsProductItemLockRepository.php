<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProductItemLock;
use EasyShop\Entities\EsOrder;

class EsProductItemLockRepository extends EntityRepository
{
    
    /**
     * Returns the items locks for a given product_id
     *
     * @param integer $productId
     * @return EsProductItemLock
     *
     */
    public function getProductItemLockByProductId($productId)
    {
        $query =  $this->_em->createQueryBuilder()
                        ->select(['lck.idItemLock, pi.idProductItem, lck.qty as lock_qty, pi.quantity', 'lck.timestamp'])
                        ->from('EasyShop\Entities\EsProductItemLock','lck')
                        ->innerJoin('EasyShop\Entities\EsProductItem', 'pi','WITH','lck.productItem = pi.idProductItem')
                        ->where('pi.product = :product_id')
                        ->setParameter('product_id', $productId)
                        ->getQuery(); 
        return $result = $query->getResult();

             
    }

    // public function releaseAllLock($memberId)
    // {
        
    // }
}

// $query = " DELETE FROM `es_product_item_lock` WHERE id_item_lock IN (
//                 SELECT * FROM (
//                     SELECT b.`id_item_lock` FROM es_order a, `es_product_item_lock` b WHERE a.buyer_id = :member_id AND a.`order_status` = 99 AND a.`id_order` = b.`order_id`) 
//                 AS tbl)
//         ";