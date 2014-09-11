<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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
}
