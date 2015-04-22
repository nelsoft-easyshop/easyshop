<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProductItemLock;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsProductItem;

class EsProductItemLockRepository extends EntityRepository
{
    
    /**
     * Returns the items locks for a given product_id
     *
     * @param int $productId
     * @return EsProductItemLock
     */
    public function getProductItemLockByProductId($productId)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select(['lck.idItemLock, pi.idProductItem, lck.qty as lock_qty, pi.quantity', 'lck.timestamp'])
                    ->from('EasyShop\Entities\EsProductItemLock','lck')
                    ->innerJoin('EasyShop\Entities\EsProductItem', 'pi','WITH','lck.productItem = pi.idProductItem')
                    ->leftJoin('EasyShop\Entities\EsOrder', 'o', 'WITH', 'lck.order = o.idOrder')
                    ->where('pi.product = :product_id');
        $query = $query->setParameter('product_id', $productId)
                       ->getQuery(); 
        return $result = $query->getResult();
    }

    /**
     * Returns the amount of lock given an Order id
     *
     * @param int $orderId
     * @return int
     */
    public function getLockCount($orderId)
    {
        $query = $this->_em->createQueryBuilder()
                            ->select('count(lck.idItemLock) AS cnt')
                            ->from('EasyShop\Entities\EsProductItemLock','lck')
                            ->where('lck.order = :orderId')
                            ->setParameter('orderId', $orderId) 
                            ->getQuery()
                            ->getSingleScalarResult();
        return intval($query);
    }

    /**
     * Releases all locks held by member
     *
     * @param int $memberId
     */
    public function releaseAllLock($memberId)
    {
        $itemLockIds = $this->_em->createQueryBuilder()
                            ->select("lck.idItemLock")
                            ->from('EasyShop\Entities\EsProductItemLock','lck')
                            ->innerJoin('EasyShop\Entities\EsOrder', 'ord', 'with', "ord.idOrder = lck.order")
                            ->where("ord.orderStatus = 99")
                            ->andWhere("ord.buyer = :memberId")
                            ->setParameter('memberId', $memberId)
                            ->getQuery()
                            ->getScalarResult();
        
        if(count($itemLockIds) > 0){
            $productIds = [];
            foreach ($itemLockIds as $value) {
                $productIds[] = intval($value['idItemLock']);
            }
            
            $qb = $this->_em->createQueryBuilder();
            $query = $this->_em->createQueryBuilder()
                                ->delete('EasyShop\Entities\EsProductItemLock','lck')
                                ->where($qb->expr()->in("lck.idItemLock", $productIds))
                                ->getQuery()
                                ->execute();
        }
    }

    /**
     * Deletes all lock given an array
     *
     * @param mixed $ids
     * @param int $orderId
     */
    public function deleteLockItem($orderId, $ids)
    {
        if(count($ids) > 0){
            $itemIds = array_keys($ids);
            $qb = $this->_em->createQueryBuilder();
            $this->_em->createQueryBuilder()
                        ->delete('EasyShop\Entities\EsProductItemLock', 'lck')
                        ->where($qb->expr()->in("lck.productItem", $itemIds))
                        ->andWhere('lck.order = :orderId')
                        ->setParameter('orderId', $orderId)
                        ->getQuery()
                        ->execute();
        }
    }

    /**
     * Locks objects
     *
     * @param mixed $ids
     * @param int $orderId
     */
    public function insertLockItem($orderId, $ids = array())
    {
        if(count($ids) > 0){
            $esOrderObj = $this->_em->getRepository('EasyShop\Entities\EsOrder')->find(intval($orderId));
            $esProductItemRepo = $this->_em->getRepository('EasyShop\Entities\EsProductItem');

            foreach ($ids as $itemId => $qty) {
                $esProductItemObj = $esProductItemRepo->find(intval($itemId));
                $lockItem = new EsProductItemLock();
                $lockItem->setQty($qty);
                $lockItem->setOrder($esOrderObj);
                $lockItem->setProductItem($esProductItemObj);
                $lockItem->setTimestamp(date_create(date("Y-m-d H:i:s")));
                $this->_em->persist($lockItem);
            }        
            $this->_em->flush();
        }
    }
}

