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
    
}



