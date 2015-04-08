<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsOrderProductStatus as orderProductStatus;
use EasyShop\Entities\EsOrderStatus as orderStatus;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsPaymentMethod;
use DateTime;

class EsOrderRepository extends EntityRepository
{

    /**
     * Returns sold transactions of users
     * @param $userId
     * @param bool $isOngoing
     * @param int $offset
     * @param int $perPage
     * @param $transactionNumber
     * @param $paymentMethod
     * @return array
     */
    public function getUserSoldTransactions($userId, $isOngoing = true, $offset = 0, $perPage = 10, $transactionNumber = '', $paymentMethod = '')
    {
        $orderProductStatuses = $isOngoing ? [ orderProductStatus::ON_GOING ] : 
                                    [orderProductStatus::FORWARD_SELLER ,
                                    orderProductStatus::RETURNED_BUYER ,
                                    orderProductStatus::CASH_ON_DELIVERY,
                                    orderProductStatus::PAID_FORWARDED,
                                    orderProductStatus::PAID_RETURNED];
        $EsPaymentMethodRepository = $this->_em->getRepository('EasyShop\Entities\EsPaymentMethod');
        if (!$paymentMethod || trim($paymentMethod) === 'all') {
            $paymentMethod = $EsPaymentMethodRepository->getPaymentMethods();
        }

        $qb = $this->_em->createQueryBuilder();
        $queryBuilder =
            $qb->select("IDENTITY(o.orderStatus) as orderStatus,
                                o.isFlag as isFlag,
                                op.total as totalOrderProduct,
                                o.total as transactionTotal,
                                p.name as productname,
                                m.fullname as fullname,
                                op.orderQuantity as orderQuantity,
                                IDENTITY(o.buyer) as buyerId,
                                o.dateadded as dateadded,
                                o.idOrder,
                                o.invoiceNo as invoiceNo,
                                shippingAdd.consignee,
                                shippingAdd.mobile,
                                shippingAdd.telephone,
                                stateRegion.location,
                                cityLocation.location as city,
                                shippingAdd.address as fulladd,
                                o.isFlag,
                                m.username as buyer,
                                COALESCE(NULLIF(m.storeName, ''), m.username) as buyerStoreName,
                                m.slug as buyerslug,
                                pm.idPaymentMethod,
                                pm.name as paymentMethod,
                                COALESCE(memberFeedback.idMember,0) as forMemberId")
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
                        ,$qb->expr()->eq('feedback.forMemberid', 'o.buyer')
                        ,$qb->expr()->eq('feedback.member', ':sellerId')
                    )
                )
                ->leftJoin('EasyShop\Entities\EsOrderShippingAddress', 'shippingAdd', 'with', "o.shippingAddressId = shippingAdd.idOrderShippingAddress")
                ->leftJoin('EasyShop\Entities\EsLocationLookup', 'stateRegion', 'with', 'shippingAdd.stateregion = stateRegion.idLocation')
                ->leftJoin('EasyShop\Entities\EsLocationLookup', 'cityLocation', 'with', 'shippingAdd.city = cityLocation.idLocation')
                ->leftJoin('EasyShop\Entities\EsMember', 'm', 'with', 'o.buyer = m.idMember')
                ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
                ->innerJoin('EasyShop\Entities\EsPaymentMethod', 'pm', 'with', 'o.paymentMethod = pm.idPaymentMethod')
                ->leftJoin('EasyShop\Entities\EsMember', 'memberFeedback', 'with', 'memberFeedback.idMember = feedback.member')
                ->where(
                    $qb->expr()->andX(
                        $qb->expr()->not(
                            $qb->expr()->andX(
                                $qb->expr()->eq('o.orderStatus', ':STATUS_DRAFT')
                                ,$qb->expr()->orX(
                                    $qb->expr()->eq('o.paymentMethod', ':paypalPayMentMethod')
                                    ,$qb->expr()->eq('o.paymentMethod', ':pesopayPayMentMethod')
                                )
                            )
                        ),
                        $qb->expr()->in('op.status', $orderProductStatuses)
                    )
                )
                ->andWhere('o.orderStatus != :statusVoid')
                ->andWhere('o.paymentMethod IN(:paymentMethodLists)')
                ->andWhere('o.invoiceNo LIKE :transNum ')
                ->orderBy('o.idOrder', "desc")
                ->setParameter('sellerId', $userId)
                ->setParameter('STATUS_DRAFT', orderStatus::STATUS_DRAFT)
                ->setParameter('statusVoid', orderStatus::STATUS_VOID)
                ->setParameter('paypalPayMentMethod', EsPaymentMethod::PAYMENT_PAYPAL)
                ->setParameter('pesopayPayMentMethod', EsPaymentMethod::PAYMENT_PESOPAYCC)
                ->setParameter('transNum', '%' . $transactionNumber . '%')
                ->setParameter('paymentMethodLists', $paymentMethod)
                ->setFirstResult($offset)
                ->setMaxResults($perPage)
                ->getQuery();

        return $queryBuilder->getResult();
    }

    /**
     * Returns bought transactions of users
     * @param $uid
     * @param bool $isOngoing
     * @param int $offset
     * @param int $perPage
     * @param $transactionNumber
     * @param $paymentMethod
     * @return array
     */
    public function getUserBoughtTransactions($uid, $isOngoing = true, $offset = 0, $perPage = 10, $transactionNumber ='', $paymentMethod = '')
    {
        $orderProductStatuses = $isOngoing ? [ orderProductStatus::ON_GOING ] : 
                                    [orderProductStatus::FORWARD_SELLER ,
                                    orderProductStatus::RETURNED_BUYER ,
                                    orderProductStatus::CASH_ON_DELIVERY,
                                    orderProductStatus::PAID_FORWARDED,
                                    orderProductStatus::PAID_RETURNED];

        $qb = $this->_em->createQueryBuilder();
        $EsPaymentMethodRepository = $this->_em->getRepository('EasyShop\Entities\EsPaymentMethod');
        if (!$paymentMethod || trim($paymentMethod) === 'all') {
            $paymentMethod = $EsPaymentMethodRepository->getPaymentMethods();
        }

        $queryBuilder = $qb->select("IDENTITY(o.orderStatus) as orderStatus,
                                                            o.isFlag as isFlag, 
                                                            op.total as total,
                                                            o.total as transactionTotal,
                                                            p.name as productname, 
                                                            m.fullname as fullname,
                                                            sm.idMember as sellerId,
                                                            op.orderQuantity as orderQuantity,
                                                            IDENTITY(o.buyer) as buyerId, 
                                                            o.dateadded as dateadded, 
                                                            o.idOrder,
                                                            o.invoiceNo,
                                                            pm.idPaymentMethod,
                                                            pm.name as paymentMethod")
                        ->from('EasyShop\Entities\EsOrder','o')
                        ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op', 'with', 'o.idOrder = op.order')
                        ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
                        ->innerJoin('EasyShop\Entities\EsMember', 'm', 'with', 'o.buyer = m.idMember')
                        ->leftJoin('EasyShop\Entities\Esmember', 'sm', 'WITH', 'op.seller = sm.idMember')
                        ->innerJoin('EasyShop\Entities\EsPaymentMethod', 'pm', 'with', 'o.paymentMethod = pm.idPaymentMethod')
                        ->where(
                                $qb->expr()->andx(
                                    $qb->expr()->not(
                                        $qb->expr()->andX( 
                                            $qb->expr()->eq('o.orderStatus', ':STATUS_DRAFT')
                                            ,$qb->expr()->orX(
                                                $qb->expr()->eq('o.paymentMethod', ':paypalPayMentMethod')
                                                ,$qb->expr()->eq('o.paymentMethod', ':pesopayPayMentMethod')
                                            )
                                        )
                                    ),
                                    $qb->expr()->in('op.status', $orderProductStatuses)
                                )
                            )
                        ->andWhere('o.buyer = :buyer_id')
                        ->andWhere('o.paymentMethod IN(:paymentMethodLists)')
                        ->andWhere('o.invoiceNo LIKE :transNum ')
                        ->orderBy('o.idOrder', "desc")
                        ->setParameter('buyer_id', $uid)
                        ->setParameter('STATUS_DRAFT', orderStatus::STATUS_DRAFT)
                        ->setParameter('paypalPayMentMethod', EsPaymentMethod::PAYMENT_PAYPAL)
                        ->setParameter('pesopayPayMentMethod', EsPaymentMethod::PAYMENT_PESOPAYCC)
                        ->setParameter('paymentMethodLists', $paymentMethod)
                        ->setParameter('transNum', '%' . $transactionNumber . '%')
                        ->setFirstResult($offset)
                        ->setMaxResults($perPage)
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

    public function updatePaymentIfComplete($id, $data, $tid, $paymentType, $orderStatus = orderStatus::STATUS_DRAFT, $flag = false)
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

    /**
     * Update Order status
     * @param $esOrder
     * @param $orderStatus
     * @return esOrder
     */
    public function updateOrderStatus($esOrder, $orderStatus)
    {
        $esOrder->setOrderStatus($orderStatus);
        $esOrder->setDatemodified(new DateTime('now'));

        $this->_em->flush();

        return $esOrder;
    }

    /**
     * Returns all bought transactions
     * @param $uid
     * @param bool $isOngoing
     * @param string $paymentMethod
     * @param string $transactionNumber
     * @return array
     */
    public function getAllUserBoughtTransactions($uid, $isOngoing = true, $paymentMethod = '', $transactionNumber ='')
    {
        $orderProductStatuses = $isOngoing ? [ orderProductStatus::ON_GOING ] : 
                                           [ orderProductStatus::FORWARD_SELLER ,
                                           orderProductStatus::RETURNED_BUYER ,
                                           orderProductStatus::CASH_ON_DELIVERY,
                                           orderProductStatus::PAID_FORWARDED,
                                           orderProductStatus::PAID_RETURNED ];

        $qb = $this->_em->createQueryBuilder();
        $EsPaymentMethodRepository = $this->_em->getRepository('EasyShop\Entities\EsPaymentMethod');
        if (!$paymentMethod || trim($paymentMethod) === 'all') {
            $paymentMethod = $EsPaymentMethodRepository->getPaymentMethods();
        }

        $queryBuilder = $qb->select("IDENTITY(o.orderStatus) as orderStatus,
                                                            o.isFlag as isFlag,
                                                            op.total as total,
                                                            o.total as transactionTotal,
                                                            p.name as productname,
                                                            m.fullname as fullname,
                                                            sm.idMember as sellerId,
                                                            op.orderQuantity as orderQuantity,
                                                            IDENTITY(o.buyer) as buyerId,
                                                            o.dateadded as dateadded,
                                                            o.idOrder,
                                                            o.invoiceNo,
                                                            pm.idPaymentMethod,
                                                            pm.name as paymentMethod")
            ->from('EasyShop\Entities\EsOrder','o')
            ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op', 'with', 'o.idOrder = op.order')
            ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
            ->innerJoin('EasyShop\Entities\EsMember', 'm', 'with', 'o.buyer = m.idMember')
            ->leftJoin('EasyShop\Entities\Esmember', 'sm', 'WITH', 'op.seller = sm.idMember')
            ->innerJoin('EasyShop\Entities\EsPaymentMethod', 'pm', 'with', 'o.paymentMethod = pm.idPaymentMethod')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->not(
                        $qb->expr()->andX(
                            $qb->expr()->eq('o.orderStatus', ':STATUS_DRAFT')
                            ,$qb->expr()->orX(
                                $qb->expr()->eq('o.paymentMethod', ':paypalPayMentMethod')
                                ,$qb->expr()->eq('o.paymentMethod', ':pesopayPayMentMethod')
                            )
                        )
                    ),
                    $qb->expr()->in('op.status', $orderProductStatuses)
                )
            )
            ->andWhere('o.buyer = :buyer_id')
            ->andWhere('o.paymentMethod IN(:paymentMethodLists)')
            ->andWhere('o.invoiceNo LIKE :transNum ')
            ->orderBy('o.idOrder', "desc")
            ->setParameter('buyer_id', $uid)
            ->setParameter('STATUS_DRAFT', orderStatus::STATUS_DRAFT)
            ->setParameter('paypalPayMentMethod', EsPaymentMethod::PAYMENT_PAYPAL)
            ->setParameter('pesopayPayMentMethod', EsPaymentMethod::PAYMENT_PESOPAYCC)
            ->setParameter('paymentMethodLists', $paymentMethod)
            ->setParameter('transNum', '%' . $transactionNumber . '%')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    /**
     * Returns all sold transactions
     * @param $userId
     * @param bool $isOngoing
     * @param string $paymentMethod
     * @param $transactionNumber
     * @return array
     */
    public function getAllUserSoldTransactions($userId, $isOngoing = true, $paymentMethod = '', $transactionNumber)
    {
        $orderProductStatuses = $isOngoing ? [ orderProductStatus::ON_GOING ] : 
                                           [ orderProductStatus::FORWARD_SELLER ,
                                           orderProductStatus::RETURNED_BUYER ,
                                           orderProductStatus::CASH_ON_DELIVERY,
                                           orderProductStatus::PAID_FORWARDED,
                                           orderProductStatus::PAID_RETURNED ];
        $EsPaymentMethodRepository = $this->_em->getRepository('EasyShop\Entities\EsPaymentMethod');
        if (!$paymentMethod || trim($paymentMethod) === 'all') {
            $paymentMethod = $EsPaymentMethodRepository->getPaymentMethods();
        }

        $qb = $this->_em->createQueryBuilder();
        $queryBuilder = $qb->select("IDENTITY(o.orderStatus) as orderStatus,
                                o.isFlag as isFlag,
                                op.total as totalOrderProduct,
                                o.total as transactionTotal,
                                p.name as productname,
                                m.fullname as fullname,
                                op.orderQuantity as orderQuantity,
                                IDENTITY(o.buyer) as buyerId,
                                o.dateadded as dateadded,
                                o.idOrder,
                                o.invoiceNo as invoiceNo,
                                shippingAdd.consignee,
                                shippingAdd.mobile,
                                shippingAdd.telephone,
                                stateRegion.location,
                                cityLocation.location as city,
                                shippingAdd.address as fulladd,
                                o.isFlag,
                                m.username as buyer,
                                m.slug as buyerslug,
                                pm.idPaymentMethod,
                                pm.name as paymentMethod,
                                COALESCE(memberFeedback.idMember,0) as forMemberId")
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
                    ,$qb->expr()->eq('feedback.forMemberid', 'o.buyer')
                    ,$qb->expr()->eq('feedback.member', ':sellerId')
                )
            )
            ->leftJoin('EasyShop\Entities\EsOrderShippingAddress', 'shippingAdd', 'with', "o.shippingAddressId = shippingAdd.idOrderShippingAddress")
            ->leftJoin('EasyShop\Entities\EsLocationLookup', 'stateRegion', 'with', 'shippingAdd.stateregion = stateRegion.idLocation')
            ->leftJoin('EasyShop\Entities\EsLocationLookup', 'cityLocation', 'with', 'shippingAdd.city = cityLocation.idLocation')
            ->leftJoin('EasyShop\Entities\EsMember', 'm', 'with', 'o.buyer = m.idMember')
            ->innerJoin('EasyShop\Entities\EsProduct', 'p', 'with', 'op.product = p.idProduct')
            ->innerJoin('EasyShop\Entities\EsPaymentMethod', 'pm', 'with', 'o.paymentMethod = pm.idPaymentMethod')
            ->leftJoin('EasyShop\Entities\EsMember', 'memberFeedback', 'with', 'memberFeedback.idMember = feedback.member')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->not(
                        $qb->expr()->andX(
                            $qb->expr()->eq('o.orderStatus', ':STATUS_DRAFT')
                            ,$qb->expr()->orX(
                                $qb->expr()->eq('o.paymentMethod', ':paypalPayMentMethod')
                                ,$qb->expr()->eq('o.paymentMethod', ':pesopayPayMentMethod')
                            )
                        )
                    ),
                    $qb->expr()->in('op.status', $orderProductStatuses)
                )
            )
            ->andWhere('o.orderStatus != :statusVoid')
            ->andWhere('o.paymentMethod IN(:paymentMethodLists)')
            ->andWhere('o.invoiceNo LIKE :transNum ')
            ->orderBy('o.idOrder', "desc")
            ->setParameter('sellerId', $userId)
            ->setParameter('statusVoid', orderStatus::STATUS_VOID)
            ->setParameter('STATUS_DRAFT', orderStatus::STATUS_DRAFT)
            ->setParameter('paypalPayMentMethod', EsPaymentMethod::PAYMENT_PAYPAL)
            ->setParameter('pesopayPayMentMethod', EsPaymentMethod::PAYMENT_PESOPAYCC)
            ->setParameter('transNum', '%' . $transactionNumber . '%')
            ->setParameter('paymentMethodLists', $paymentMethod)
            ->getQuery();

        return $queryBuilder->getResult();
    }

}
