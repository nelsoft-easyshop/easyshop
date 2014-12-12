<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class EsProductShippingCommentRepository extends EntityRepository
{
    /**
     *  Select shipping comment entry based on posted data
     *  Used to check if shipping comment entries have changed
     */
    public function getExactShippingComment($data){
        $em = $this->_em;
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id_shipping_comment', 'id_shipping_comment');

        $sql = "SELECT id_shipping_comment
                FROM es_product_shipping_comment
                WHERE order_product_id = :id_order_product
                    AND courier = :courier
                    AND tracking_num = :tracking_num
                    AND comment = :comment
                    AND expected_date = :expected_date
                    AND delivery_date = :delivery_date
                    AND member_id = :member_id";

        $query = $em->createNativeQuery($sql, $rsm)
                    ->setParameter('id_order_product', $data['order_product'])
                    ->setParameter('courier', $data['courier'])
                    ->setParameter('tracking_num', $data['tracking_num'])
                    ->setParameter('comment', $data['comment'])
                    ->setParameter('expected_date', $data['expected_date'])
                    ->setParameter('delivery_date', $data['delivery_date'])
                    ->setParameter('member_id', $data['member_id']);

        return $query->getResult();
    }
}
