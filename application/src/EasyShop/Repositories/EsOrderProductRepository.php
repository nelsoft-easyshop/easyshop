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

    public function getOrderProductTransactionDetails($orderId)
    {
        $this->em =  $this->_em;
        $queryBuilder = $this->em->createQueryBuilder()
                                    ->select('
                                        tbl_op.idOrderProduct, tbl_p.idProduct, tbl_p.name, tbl_op.orderQuantity, tbl_op.total as price,tbl_ops.idOrderProductStatus, tbl_p.slug,
                                        tbl_psc.comment as shipping_comment, tbl_psc.courier, tbl_psc.trackingNum, tbl_psc.deliveryDate,
                                        tbl_psc.expectedDate, tbl_psc.datemodified, tbl_op.isReject, tbl_pi.productImagePath, tbl_opa.attrName,
                                        tbl_opa.attrValue, tbl_m.idMember as seller_id, tbl_m.username as seller, COALESCE(tbl_m_recipient.idMember, 0) as for_memberid,
                                        tbl_m.slug as sellerSlug
                                    ')
                                    ->from('EasyShop\Entities\EsOrderProduct', 'tbl_op')
                                    ->innerJoin('EasyShop\Entities\EsProduct', 'tbl_p', 'WITH', 'tbl_p.idProduct = tbl_op.product')
                                    ->leftJoin('EasyShop\Entities\EsOrderProductAttr', 'tbl_opa', 'WITH', 'tbl_opa.orderProduct = tbl_op.idOrderProduct')
                                    ->leftJoin('EasyShop\Entities\EsProductImage', 'tbl_pi', 'WITH', 'tbl_pi.product = tbl_op.product AND tbl_pi.isPrimary = 1')
                                    ->leftJoin('EasyShop\Entities\EsProductShippingComment', 'tbl_psc', 'WITH', 'tbl_psc.orderProduct = tbl_op.idOrderProduct')
                                    ->leftJoin('EasyShop\Entities\EsMember', 'tbl_m', 'WITH', 'tbl_m.idMember = tbl_op.seller')
                                    ->leftJoin('EasyShop\Entities\EsMemberFeedback', 'tbl_mf', 'WITH', 'tbl_mf.order = tbl_op.order AND tbl_mf.member = tbl_op.seller')
                                    ->leftJoin('EasyShop\Entities\EsOrderProductStatus', 'tbl_ops', 'WITH', 'tbl_ops.idOrderProductStatus = tbl_op.status')
                                    ->leftJoin('EasyShop\Entities\EsMember', 'tbl_m_recipient', 'WITH', 'tbl_mf.forMemberid = tbl_m_recipient.idMember')
                                    ->where('tbl_op.order = :orderId')
                                    ->setParameter('orderId', $orderId)
                                    ->orderBy('tbl_op.idOrderProduct', 'ASC')
                                    ->getQuery();
        $result = $queryBuilder->getResult();

        return $result;
    }
}
