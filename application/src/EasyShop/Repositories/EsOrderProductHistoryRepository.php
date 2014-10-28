<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use EasyShop\Entities\EsOrderProductHistory;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrderProductStatus;

class EsOrderProductHistoryRepository extends EntityRepository
{
    public function updateOrderProductHistory($orderProductId, $comment, $orderProductStatus)
    {
        $this->_em->createQueryBuilder()
                    ->update('EasyShop\Entities\EsOrderProductHistory','hs')
                    ->set('hs.comment', ':comment')
                    ->set('hs.dateAdded', ':date')
                    ->set('hs.orderProductStatus', ':status')
                    ->where('hs.orderProduct = :ordId')
                    ->setParameter('comment', $comment)
                    ->setParameter('date', date_create(date("Y-m-d H:i:s")))
                    ->setParameter('status', $orderProductStatus)
                    ->setParameter('ordId', $orderProductId)
                    ->getQuery()
                    ->execute();
    }
}


