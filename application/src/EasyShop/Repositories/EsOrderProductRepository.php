<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsProduct;

class EsOrderProductRepository extends EntityRepository
{

    /**
     * Get the average price of all instances of a sold item between specified dates
     * @param  integer $id       [description]
     * @param  date  $dateFrom [description]
     * @param  date  $dateTo   [description]
     * @return float
     */
    public function getSoldPrice($productId = 0, $dateFrom, $dateTo)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('COALESCE(AVG(op.price),0) AS soldPrice')
                            ->from('EasyShop\Entities\EsOrderProduct','op')
                            ->innerJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder AND o.dateadded BETWEEN :datefrom AND :dateto')
                            ->where('op.product = :productId')
                            ->andWhere('o.orderStatus != 99') 
                            ->setParameter('productId', $productId)
                            ->setParameter('datefrom', $dateFrom)
                            ->setParameter('dateto', $dateTo)
                            ->getQuery();

        $result = $qb->getOneOrNullResult();

        return floatval($result['soldPrice']);
    }
    
    /**
     * Returns the number of sold items within a specified date period
     *
     * @param integer $productId
     * @param string $dateFrom
     * @param string $dateTo
     * @return integer
     */
    public function getSoldCount($productId, $dateFrom, $dateTo)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                        ->select('COALESCE(SUM(op.orderQuantity),0) as sold_count')
                        ->from('EasyShop\Entities\EsOrderProduct','op')
                        ->innerJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder AND o.dateadded BETWEEN :datefrom AND :dateto')
                        ->where('op.product = :productId')
                        ->andWhere('o.orderStatus != 99') 
                        ->andWhere('o.orderStatus != 2') 
                        ->setParameter('productId', $productId)
                        ->setParameter('datefrom', $dateFrom)
                        ->setParameter('dateto', $dateTo)
                        ->getQuery();
                    
        $result = $qb->getOneOrNullResult();

        return intval($result['sold_count']);
    }
    
    /**
     * Returns the number of purchases by a user for a given promo
     *
     * @param integer $memberId
     * @param integer $promoType
     * @return integer
     */
    public function getPromoPurchaseCountForMember($memberId, $promoType)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                        ->select('COALESCE(SUM(op.orderQuantity),0) as bought_count')
                        ->from('EasyShop\Entities\EsOrder','o')
                        ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op','WITH','op.order = o.idOrder')
                        ->innerJoin('EasyShop\Entities\EsProduct', 'p','WITH','p.idProduct = op.product AND p.promoType = :type')
                        ->where('o.buyer = :memberId')
                        ->andWhere('o.orderStatus != 99') 
                        ->andWhere('o.orderStatus != 2') 
                        ->setParameter('memberId', $memberId)
                        ->setParameter('type', $promoType)
                        ->getQuery();

        $result = $qb->getOneOrNullResult();
        return intval($result['bought_count']);
    }

    /**
     * Returns the number of purchases by a user for a given product
     *
     * @param integer $memberId
     * @param integer $productId
     * @return integer
     */
    public function getProductBuyCountByUser($memberId,$productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                       ->select('COALESCE(COUNT(op.idOrderProduct),0) as total_count')
                       ->from('EasyShop\Entities\EsOrderProduct','op')
                       ->leftJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder') 
                       ->where('o.buyer = :memberId')
                       ->andWhere('op.product = :productId')  
                       ->setParameter('memberId', $memberId)
                       ->setParameter('productId', $productId)
                       ->getQuery(); 
        $result = $qb->getOneOrNullResult();

        return intval($result['total_count']);
    }

    /**
     * Get all current sales of user that is not yet payout
     * @param  integer $memberId
     * @param  integer $page
     * @param  integer $limit
     * @param  integer $orderProductStatus
     * @param  date object $dateFrom
     * @param  date object $dateTo
     * @return object
     */
    public function getOrderProductTransaction($memberId,
                                               $orderProductStatus,
                                               $limit,
                                               $page = 0,
                                               $dateFrom = null, 
                                               $dateTo = null)
    {
        $this->em =  $this->_em;
        $queryBuilder = $this->em->createQueryBuilder()->select('op')
                                                       ->from('EasyShop\Entities\EsOrderProduct','op')
                                                       ->leftJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder') 
                                                       ->where('op.seller = :memberId')
                                                       ->andWhere('op.status = :status')
                                                       ->setParameter('memberId', $memberId)
                                                       ->setParameter('status', $orderProductStatus);
        if($dateFrom != null && $dateTo != null){
            $queryBuilder->andWhere('o.dateadded BETWEEN :dateFrom AND :dateTo')
                         ->setParameter('dateFrom', $dateFrom)
                         ->setParameter('dateTo', $dateTo);
        }

        $qbResult = $queryBuilder->orderBy('op.idOrderProduct', "DESC")
                                 ->setFirstResult($page)
                                 ->setMaxResults($limit)
                                 ->getQuery();
        $result = $qbResult->getResult();

        return $result;
    }

    /**
     * Get Total Sum of order product per user by status
     * @param  integer $memberId
     * @param  integer $orderProductStatus
     * @param  date object $dateFrom
     * @param  date object $dateTo
     * @return float
     */
    public function getSumOrderProductTransaction($memberId, 
                                                  $orderProductStatus,
                                                  $dateFrom = null, 
                                                  $dateTo = null)
    {
        $this->em =  $this->_em;
        $queryBuilder = $this->em->createQueryBuilder()
                                 ->select('COALESCE(SUM(op.net),0) as net_amount')
                                 ->from('EasyShop\Entities\EsOrderProduct','op')
                                 ->leftJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder') 
                                 ->where('op.seller = :memberId')
                                 ->andWhere('op.status = :status')
                                 ->setParameter('memberId', $memberId)
                                 ->setParameter('status', $orderProductStatus);

        if($dateFrom != null && $dateTo != null){
            $queryBuilder->andWhere('o.dateadded BETWEEN :dateFrom AND :dateTo')
                         ->setParameter('dateFrom', $dateFrom)
                         ->setParameter('dateTo', $dateTo);
        }

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        return (float) $result['net_amount'];
    }

    /**
     * Get total Count of all order product per user by status
     * @param  integer $memberId
     * @param  integer $orderProductStatus
     * @param  date object $dateFrom
     * @param  date object $dateTo
     * @return integer
     */
    public function getCountOrderProductTransaction($memberId, 
                                                     $orderProductStatus,
                                                     $dateFrom = null, 
                                                     $dateTo = null)
    {
        $this->em =  $this->_em;
        $queryBuilder = $this->em->createQueryBuilder()
                                 ->select('COUNT(op.idOrderProduct) as total_count')
                                 ->from('EasyShop\Entities\EsOrderProduct','op')
                                 ->leftJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder') 
                                 ->where('op.seller = :memberId')
                                 ->andWhere('op.status = :status')
                                 ->setParameter('memberId', $memberId)
                                 ->setParameter('status', $orderProductStatus);

        if($dateFrom != null && $dateTo != null){
            $queryBuilder->andWhere('o.dateadded BETWEEN :dateFrom AND :dateTo')
                         ->setParameter('dateFrom', $dateFrom)
                         ->setParameter('dateTo', $dateTo);
        }

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        return (int) $result['total_count'];
    }


    
}
