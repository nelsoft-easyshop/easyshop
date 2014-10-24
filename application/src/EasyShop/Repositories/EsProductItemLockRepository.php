<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProductItemLock as EsProductItemLock;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsProductItem;

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

    /**
     * Returns the amount of lock given an Order id
     *
     * @param integer $orderId
     * @return int
     *
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
     * @param integer $memberId
     * @return void
     *
     */
    public function releaseAllLock($memberId)
    {
        $query = $this->_em->createQueryBuilder()
                        ->select("lck.idItemLock")
                        ->from('EasyShop\Entities\EsProductItemLock','lck')
                        ->innerJoin('EasyShop\Entities\EsOrder', 'ord', 'with', "ord.idOrder = lck.order")
                        ->where("ord.orderStatus = 99")
                        ->andWhere("ord.buyer = :memberId")
                        ->setParameter('memberId', $memberId)
                        ->getQuery()
                        ->getScalarResult();
        
        $productIds = [];
        foreach ($query as $arr => $value) {
            $productIds[] = intval($value['idItemLock']);
        }
        
        if(count($productIds) > 0){
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
     * @return void
     *
     */
    public function deleteLockItem($ids = array(), $orderId)
    {
        foreach ($ids as $itemId => $qty) {
            $item = $this->_em->getRepository('EasyShop\Entities\EsProductItemLock')
                        ->findOneBy([
                            'productItem' => $itemId,
                            'qty' => $qty,
                            'order' => $orderId
                            ]);
            $this->_em->remove($item);
            $this->_em->flush();
        }
    }

    /**
     * Locks objects
     *
     * @param mixed $ids
     * @param int $orderId
     * @return void
     *
     */
    public function insertLockItem($ids = array(), $orderId)
    {
        $esOrderObj = $this->_em->getRepository('EasyShop\Entities\EsOrder')
                            ->find(intval($orderId));

        foreach ($ids as $itemId => $qty) {
            $esProductItemObj = $this->_em->getRepository('EasyShop\Entities\EsProductItem')
                                ->find(intval($itemId));

            $lockItem = new EsProductItemLock();
            $lockItem->setQty($qty);
            $lockItem->setOrder($esOrderObj);
            $lockItem->setProductItem($esProductItemObj);
            $lockItem->setTimestamp(date_create(date("Y-m-d H:i:s")));

            $this->_em->persist($lockItem);
            $this->_em->flush();
        }        
    }
}


