<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder;

class EsOrderProductRepository extends EntityRepository
{

    /**
     * Get the average price of all instances of a sold item between specified dates
     * @param  integer $id       [description]
     * @param  [type]  $dateFrom [description]
     * @param  [type]  $dateTo   [description]
     * @return [type]            [description]
     */
    public function getSoldPrice($productId = 0,$dateFrom,$dateTo)
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
                            
        $result = $qb->getResult();

        return $result;
    }
}
