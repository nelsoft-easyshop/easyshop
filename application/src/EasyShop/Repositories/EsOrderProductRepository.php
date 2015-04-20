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
     * Returns the Product transaction details
     * @param $orderId
     * @return array
     */
    public function getOrderProductTransactionDetails($orderId)
    {
        $this->em =  $this->_em;
        $queryBuilder = $this->em->createQueryBuilder()
                                    ->select("
                                        tbl_op.idOrderProduct, tbl_p.idProduct, tbl_p.name, tbl_op.orderQuantity, tbl_op.total as price,tbl_op.price as item_price,tbl_ops.idOrderProductStatus, tbl_p.slug,
                                        tbl_op.handlingFee as handling_fee,tbl_psc.comment as shipping_comment, tbl_psc.courier, tbl_psc.trackingNum, tbl_psc.deliveryDate,
                                        tbl_psc.expectedDate, tbl_psc.datemodified, tbl_op.isReject, tbl_pi.productImagePath, tbl_opa.attrName,
                                        tbl_opa.attrValue, tbl_m.idMember as seller_id, tbl_m.username as seller, COALESCE(tbl_m_recipient.idMember, 0) as forMemberId,
                                        tbl_m.slug as sellerSlug, COALESCE(NULLIF(tbl_m.storeName, ''), tbl_m.username) as sellerStoreName,
                                        COALESCE(tbl_orderPoint.points, 0) as easyPoint
                                    ")
                                    ->from('EasyShop\Entities\EsOrderProduct', 'tbl_op')
                                    ->innerJoin('EasyShop\Entities\EsProduct', 'tbl_p', 'WITH', 'tbl_p.idProduct = tbl_op.product')
                                    ->leftJoin('EasyShop\Entities\EsOrderProductAttr', 'tbl_opa', 'WITH', 'tbl_opa.orderProduct = tbl_op.idOrderProduct')
                                    ->leftJoin('EasyShop\Entities\EsProductImage', 'tbl_pi', 'WITH', 'tbl_pi.product = tbl_op.product AND tbl_pi.isPrimary = 1')
                                    ->leftJoin('EasyShop\Entities\EsProductShippingComment', 'tbl_psc', 'WITH', 'tbl_psc.orderProduct = tbl_op.idOrderProduct')
                                    ->leftJoin('EasyShop\Entities\EsMember', 'tbl_m', 'WITH', 'tbl_m.idMember = tbl_op.seller')
                                    ->leftJoin('EasyShop\Entities\EsMemberFeedback', 'tbl_mf', 'WITH', 'tbl_mf.order = tbl_op.order AND tbl_mf.forMemberid = tbl_op.seller')
                                    ->leftJoin('EasyShop\Entities\EsOrderProductStatus', 'tbl_ops', 'WITH', 'tbl_ops.idOrderProductStatus = tbl_op.status')
                                    ->leftJoin('EasyShop\Entities\EsMember', 'tbl_m_recipient', 'WITH', 'tbl_mf.forMemberid = tbl_m_recipient.idMember')
                                    ->leftJoin('EasyShop\Entities\EsOrderPoints', 'tbl_orderPoint', 'WITH', 'tbl_orderPoint.orderProduct = tbl_op.idOrderProduct')
                                    ->where('tbl_op.order = :orderId')
                                    ->setParameter('orderId', $orderId)
                                    ->orderBy('tbl_op.idOrderProduct', 'ASC')
                                    ->getQuery();
        $result = $queryBuilder->getResult();

        return $result;
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
     * Update order product status
     * @param $esOrderProductStatus
     * @param $esOrderProduct
     * @return esOrderProduct
     */
    public function updateOrderProductStatus($esOrderProductStatus, $esOrderProduct)
    {
        $esOrderProduct->setStatus($esOrderProductStatus);
        $esOrderProduct->setIsReject(0);
        $this->_em->flush();

        return $esOrderProduct;
    }

    /**
     * Update IsReject
     * @param $isReject
     * @param $esOrderProduct
     * @return EsOrderProduct
     */
    public function updateIsReject($isReject, $esOrderProduct)
    {
        $esOrderProduct->setIsReject($isReject);
        $this->_em->flush();

        return $esOrderProduct;
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
        if($dateFrom !== null && $dateTo !== null){
            $queryBuilder->andWhere('o.dateadded BETWEEN :dateFrom AND :dateTo')
                         ->setParameter('dateFrom', $dateFrom)
                         ->setParameter('dateTo', $dateTo);
        }
        elseif($dateFrom !== null && $dateTo === null){
            $queryBuilder->andWhere('o.dateadded BETWEEN :dateFrom AND :dateTo')
                         ->setParameter('dateFrom', $dateFrom)
                         ->setParameter('dateTo', date('Y-m-d 23:59:59'));
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
