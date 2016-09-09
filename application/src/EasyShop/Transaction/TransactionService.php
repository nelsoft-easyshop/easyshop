<?php

namespace EasyShop\Transaction;

use EasyShop\Entities\EsOrderStatus;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrderProductStatus;

class TransactionService
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Product Manager instance
     *
     * @var EasyShop\Product\ProductManager
     */
    public $productManager;

    /**
     * Constructor
     * 
     */
    public function __construct($em, $productManager)
    {
        $this->em = $em;
        $this->productManager = $productManager;
    }

    public function cancelTransaction($txnId = null, $quantity = true)
    {
        if($txnId === null){
            return;
        }

        $result = $this->em->createQueryBuilder()
                            ->update('EasyShop\Entities\EsOrder', 'ord')
                            ->set('ord.orderStatus', ':status')
                            ->where('ord.transactionId = :txnId')
                            ->setParameter('status', EsOrderStatus::STATUS_VOID)
                            ->setParameter('txnId', $txnId)
                            ->getQuery()
                            ->execute();

        $idOrder = $this->em->getRepository('EasyShop\Entities\EsOrder')
                            ->findOneBy(['transactionId' => $txnId])
                            ->getIdOrder();

        $orderProducts = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                    ->findBy(['order' => $idOrder]);

        if($quantity){
            $qb = $this->em->createQueryBuilder();
            foreach ($orderProducts as $orderProduct) {
                $result = $this->em->createQueryBuilder()
                                    ->update('EasyShop\Entities\EsProductItem','itm')
                                    ->set('itm.quantity','itm.quantity + :qty')
                                    ->where('itm.idProductItem = :prdId')
                                    ->setParameter('qty', $orderProduct->getOrderQuantity())
                                    ->setParameter('prdId', $orderProduct->getProductItemId())
                                    ->getQuery()
                                    ->execute();

                $this->productManager->updateSoldoutStatus($orderProduct->getProduct()->getIdProduct());
                $this->em->getRepository('EasyShop\Entities\EsOrderProductHistory')
                            ->updateOrderProductHistory(
                                $orderProduct->getIdOrderProduct(), 
                                'REJECTED', 
                                EsOrderProductStatus::STATUS_CANCEL
                                );
            }
        }

        $result = $this->em->createQueryBuilder()
                            ->update('EasyShop\Entities\EsOrderProduct', 'prd')
                            ->set('prd.status', ':status')
                            ->where('prd.order = :orderId')
                            ->setParameter('status', EsOrderProductStatus::STATUS_CANCEL)
                            ->setParameter('orderId', $idOrder)
                            ->getQuery()
                            ->execute();

        return $idOrder;
    }
}

