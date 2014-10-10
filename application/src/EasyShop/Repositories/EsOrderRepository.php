<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsOrderStatus as orderStatus;
use EasyShop\Entities\EsProduct;

class EsOrderRepository extends EntityRepository
{
    /**
     * Returns product attributes in each order product
     * @param int $orderid
     * @return object
     */    
    public function getOrderProductAttributes($orderid)
    {
        $qb = $this->_em->createQueryBuilder();
        $queryBuilder = $qb->select("orderAttr.attrName as attrName,                                                         
                                     orderAttr.attrValue as attrValue,                                                         
                                     orderAttr.attrPrice as attrPrice ")

                            ->from('EasyShop\Entities\EsOrderProductAttr','orderAttr')
                            ->leftJoin('EasyShop\Entities\EsOrderProduct', 'o', 'with', "o.idOrderProduct = orderAttr.orderProduct")    
                            ->where("o.idOrderProduct = :orderid")
                            ->setParameter('orderid', $orderid)
                            ->getQuery(); 

        return $queryBuilder->getResult();                                                 
       
    }

    /**
     * Returns sold transactions of users
     * @param int $userid
     * @return object
     */   
    public function getUserSoldTransactions($uid)
    {

        $qb = $this->_em->createQueryBuilder();
        $queryBuilder = $qb->select("IDENTITY(o.orderStatus) as orderStatus,
                                    o.isFlag as isFlag, 
                                    op.total as totalOrderProduct, 
                                    p.name as productname, 
                                    m.fullname as fullname, 
                                    op.orderQuantity as orderQuantity, 
                                    IDENTITY(o.buyer) as buyerId, 
                                    o.dateadded as dateadded, 
                                    o.idOrder, 
                                    o.invoiceNo as invoiceNo, 
                                    badd.consignee, 
                                    badd.mobile, 
                                    badd.telephone, 
                                    l0.location, 
                                    l1.location as city, 
                                    badd.address as fulladd, 
                                    o.isFlag, 
                                    m.slug as buyerslug, 
                                    pm.name as paymentMethod")
                        ->from('EasyShop\Entities\EsOrder','o')
                        ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op', 'with',  
                                                                $qb->expr()->andX(
                                        $qb->expr()->eq('o.idOrder', 'op.order')
                                        ,$qb->expr()->eq('op.seller', ':sellerId')
                                    )
                            )
                        ->leftJoin('EasyShop\Entities\EsMemberFeedback', 'feedback', 'with',
                                        $qb->expr()->andX(
                                        $qb->expr()->eq('o.idOrder', 'feedback.order')
                                        ,$qb->expr()->eq('feedback.member', 'o.buyer')
                                        ,$qb->expr()->eq('feedback.member', ':sellerId')
                                    )
                            )
                        ->leftJoin('EasyShop\Entities\EsOrderShippingAddress', 'badd', 'with', "o.shippingAddressId = badd.idOrderShippingAddress")
                        ->leftJoin('EasyShop\Entities\EsLocationLookUp', 'l0', 'with', 'badd.stateregion = l0.idLocation')
                        ->leftJoin('EasyShop\Entities\EsLocationLookUp', 'l1', 'with', 'badd.city = l1.idLocation')
                        ->leftJoin('EasyShop\Entities\EsMember', 'm', 'with', 'o.buyer = m.idMember')
                        ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
                        ->innerJoin('EasyShop\Entities\EsPaymentMethod', 'pm', 'with', 'o.paymentMethod = pm.idPaymentMethod')
                        ->where(
                                $qb->expr()->not(
                                    $qb->expr()->andX(
                                        $qb->expr()->eq('o.orderStatus', '99')
                                        ,$qb->expr()->eq('o.paymentMethod', '1')
                                    )
                                )
                            )    
                        ->andWhere('o.orderStatus != 2')    
                        ->andWhere('o.paymentMethod IN(1,2,3,4,5)')                                  
                        ->orderBy('o.idOrder', "desc")    
                        ->groupBy('o.idOrder', 'o.dateadded', 'o.orderStatus', 'o.buyer')    
                        ->setParameter('sellerId', $uid)
                        ->getQuery();
                        return $queryBuilder->getResult();

    }

    /**
     * Returns bought transactions of users
     * @param int $userid
     * @return object
     */   
    public function getUserBoughtTransactions($uid)
    {
        $qb = $this->_em->createQueryBuilder();
        $queryBuilder = $qb->select("IDENTITY(o.orderStatus) as orderStatus,
                                                            o.isFlag as isFlag, 
                                                            op.total as total, 
                                                            p.name as productname, 
                                                            m.fullname as fullname, 
                                                            op.orderQuantity as orderQuantity, 
                                                            IDENTITY(o.buyer) as buyerId, 
                                                            o.dateadded as dateadded, 
                                                            o.idOrder, 
                                                            o.invoiceNo, 
                                                            pm.name as paymentMethod")
                        ->from('EasyShop\Entities\EsOrder','o')
                        ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op', 'with', 'o.idOrder = op.order')
                        ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
                        ->innerJoin('EasyShop\Entities\EsMember', 'm', 'with', 'o.buyer = m.idMember')
                        ->innerJoin('EasyShop\Entities\EsPaymentMethod', 'pm', 'with', 'o.paymentMethod = pm.idPaymentMethod')
                        ->where(
                                $qb->expr()->not(
                                    $qb->expr()->andX(
                                        $qb->expr()->eq('o.orderStatus', '99')
                                        ,$qb->expr()->eq('o.paymentMethod', '1')
                                    )
                                )
                            )
                        ->andWhere('o.orderStatus IN(0,99)')    
                        ->andWhere('o.buyer = :buyer_id')    
                        ->andWhere('o.paymentMethod IN(1,2,3,4,5)')      
                        ->orderBy('o.idOrder', "desc")    
                        ->setParameter('buyer_id', $uid) 
                        ->getQuery();
                return $queryBuilder->getResult();         

    }

    /**
     * Get the number of bought products by a seller for a given promo type  
     * @param  integer $buyerId
     * @param  integer $promoType
     * @return object
     */
    public function getUserPurchaseCountByPromo($buyerId = 0,$promoType)
    {
        $qb = $this->_em->createQueryBuilder();
        $qbResult = $qb->select('COALESCE(SUM(op.orderQuantity),0) AS cnt')
            ->from('EasyShop\Entities\EsOrder','o')
            ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op','WITH','o.idOrder = op.order')
            ->innerJoin('EasyShop\Entities\EsProduct', 'p','WITH','p.idProduct = op.product AND p.promoType = :promoType')
            ->where(
                    $qb->expr()->not(
                        $qb->expr()->andX(
                            $qb->expr()->eq('o.orderStatus', '99')
                            ,$qb->expr()->eq('o.paymentMethod', '1')
                        )
                    )
                )
            ->andWhere('o.buyer = :buyer_id') 
            ->setParameter('buyer_id', $buyerId)
            ->setParameter('promoType', $promoType) 
            ->getQuery();
        $result = $qbResult->getResult();

        return $result;
    }

    /**
     * Get the orders where two members are involved
     *
     * @param integer $oneMemberId
     * @param integer $anotherMemberId
     * @param bool $excludeReviewed
     * @return mixed
     */
    public function getOrderRelations($oneMemberId, $anotherMemberId, $excludeReviewed = false)
    {
        $qb = $this->_em->createQueryBuilder();
        
        
        $queryBuilder = $qb->select('o.idOrder, o.invoiceNo, o.transactionId, o.dateadded, stat.name as orderStatusName',
                                'stat.orderStatus', 'p.name as productname', 'COALESCE(COUNT(feedback.idFeedback), 0) as reviewCount')
                ->from('EasyShop\Entities\EsOrder','o')
                ->leftJoin('EasyShop\Entities\EsOrderProduct', 'op', 'with', 'o.idOrder = op.order')
                ->innerJoin('EasyShop\Entities\EsMember', 'buyer', 'with', 'o.buyer = buyer.idMember')
                ->innerJoin('EasyShop\Entities\EsMember', 'seller', 'with', 'op.seller = seller.idMember')
                ->innerJoin('EasyShop\Entities\EsOrderStatus', 'stat', 'with', 'o.orderStatus = stat.orderStatus' )
                ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
                ->leftJoin('EasyShop\Entities\EsMemberFeedback', 'feedback', 'with', 'o.idOrder = feedback.order');
                
        $queryBuilder = $queryBuilder->where(
                            $qb->expr()->andX(
                                $qb->expr()->neq('o.orderStatus', \EasyShop\Entities\EsOrderStatus::STATUS_DRAFT),
                                $qb->expr()->orX(
                                    $qb->expr()->andX(
                                        $qb->expr()->eq('buyer.idMember',':memberOne'),
                                        $qb->expr()->eq('seller.idMember',':memberTwo')
                                    ),
                                    $qb->expr()->andX(
                                        $qb->expr()->eq('buyer.idMember',':memberTwo'),
                                        $qb->expr()->eq('seller.idMember',':memberOne')
                                    )
                                )
                            )
                        );
        
        if($excludeReviewed){
            $queryBuilder = $queryBuilder->having('reviewCount = 0');
        }
        

        $qbResult = $queryBuilder->setParameter('memberOne', $oneMemberId)
                                ->setParameter('memberTwo', $anotherMemberId) 
                                ->groupBy('o.idOrder')
                                ->getQuery()
                                ->getResult();

        

        return $qbResult;   
    }
    
    public function updatePaymentIfComplete($id, $data, $tid, $paymentType, $orderStatus = 99, $flag = 0)
    {
        $order = $this->_em->getRepository('EasyShop\Entities\EsOrder')
                        ->find(intval($id));

        $orderStatusObj = $this->_em->getRepository('EasyShop\Entities\EsOrderStatus')
                        ->find(intval($orderStatus));

        $paymentMethod = $this->_em->getRepository('EasyShop\Entities\EsPaymentMethod')
                        ->find(intval($paymentType));

        $order->setOrderStatus($orderStatusObj);
        $order->setDataResponse($data);
        $order->setTransactionId($tid);
        $order->setPaymentMethod($paymentMethod);
        $order->setPostbackcount($order->getPostbackcount() + 1);
        $order->setIsFlag($flag);

        $this->_em->flush();

        return $order;
    }


}



