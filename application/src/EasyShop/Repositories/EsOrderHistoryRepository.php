<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsOrderHistory as EsOrderHistory;
use \DateTime;

class EsOrderHistoryRepository extends EntityRepository
{
    public function addOrderHistory($data)
    {
        $orderHistory = new EsOrderHistory();

        $order = $this->_em->getRepository('EasyShop\Entities\EsOrder')
                        ->find(intval($data['order_id']));

        $orderStatus = $this->_em->getRepository('EasyShop\Entities\EsOrderStatus')
                        ->find($data['order_status']);

        $orderHistory->setOrder($order);
        $orderHistory->setOrderStatus($orderStatus);
        $orderHistory->setComment($data['comment']);
        $orderHistory->setDateAdded(new DateTime("now"));

        $this->_em->persist($orderHistory);
        $this->_em->flush();

        return $orderHistory;
    }
}