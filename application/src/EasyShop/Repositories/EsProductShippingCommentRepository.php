<?php

namespace EasyShop\Repositories;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProductShippingComment;

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

    /**
     * Add Shipping Comment
     * @param $orderProduct Object
     * @param $comment
     * @param $member Object
     * @param $courier
     * @param $trackingNumber
     * @param $expectedDate
     * @param $deliveryDate
     * @return EsProductShippingComment
     */
    public function addShippingComment($orderProduct, $comment, $member, $courier, $trackingNumber, $expectedDate, $deliveryDate)
    {
        $em = $this->_em;
        $esProductShippingComment = new EsProductShippingComment();
        $esProductShippingComment->setOrderProduct($orderProduct);
        $esProductShippingComment->setComment($comment);
        $esProductShippingComment->setMember($member);
        $esProductShippingComment->setCourier($courier);
        $esProductShippingComment->setTrackingNum($trackingNumber);
        if ($expectedDate) {
            $esProductShippingComment->setExpectedDate(new DateTime($expectedDate));
        }
        $esProductShippingComment->setDeliveryDate(new DateTime($deliveryDate));
        $esProductShippingComment->setDatemodified(new DateTime('now'));

        $em->persist($esProductShippingComment);
        $em->flush();

        return $esProductShippingComment;
    }

    /**
     * Update Shipping Comment
     * @param $esProductShippingComment Object
     * @param $orderProduct Object
     * @param $comment
     * @param $member Object
     * @param $courier
     * @param $trackingNumber
     * @param $expectedDate
     * @param $deliveryDate
     * @return EsProductShippingComment
     */
    public function updateShippingComment($esProductShippingComment, $orderProduct, $comment, $member, $courier, $trackingNumber, $expectedDate, $deliveryDate)
    {
        $em = $this->_em;
        $esProductShippingComment->setOrderProduct($orderProduct);
        $esProductShippingComment->setComment($comment);
        $esProductShippingComment->setMember($member);
        $esProductShippingComment->setCourier($courier);
        $esProductShippingComment->setTrackingNum($trackingNumber);
        if ($expectedDate) {
            $esProductShippingComment->setExpectedDate(new DateTime($expectedDate));
        }
        $esProductShippingComment->setDeliveryDate(new DateTime($deliveryDate));
        $esProductShippingComment->setDatemodified(new DateTime('now'));

        $em->flush();

        return $esProductShippingComment;
    }
}
