<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder;
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
}

