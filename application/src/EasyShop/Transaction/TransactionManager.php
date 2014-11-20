<?php

namespace EasyShop\Transaction;

class TransactionManager
{
    /**
     * Entity Manager instance
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     *  User manager instance
     * @var \EasyShop\User\UserManager
     */
    private $userManager;

    /**
     * Product manager instance
     * @var \EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * Load Dependencies
     * @param $em
     * @param $userManager
     * @param $productManager
     */
    public function __construct ($em, $userManager, $productManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->productManager = $productManager;
    }

    /**
     * Get bought transaction details
     * @param $memberId
     * @return mixed
     */
    public function getBoughtTransactionDetails ($memberId)
    {
        $boughtTransactionDetails = array();
        $getUserBoughtTransactions = $this->em->getRepository('EasyShop\Entities\EsOrder')->getUserBoughtTransactions($memberId);
        foreach ($getUserBoughtTransactions as $key => $transaction) {
            if (!isset($boughtTransactionDetails[$transaction['idOrder'] . '-' . $transaction['sellerId']])) {
                $boughtTransactionDetails[$transaction['idOrder'] . '-' . $transaction['sellerId']] = $transaction;
                $orderProducts =
                    $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                ->getOrderProductTransactionDetails($transaction['idOrder']);
                foreach ($orderProducts as $productKey => $product) {
                    if (
                        !isset($boughtTransactionDetails[$transaction['idOrder'] . '-' . $transaction['sellerId']]['product'][$orderProducts[$productKey]['idOrderProduct']]) &&
                        $transaction['sellerId'] === $product['seller_id']
                    ) {
                        $product['has_shipping_summary'] = 0;
                        if (trim(strlen($product['courier'])) > 0 && trim(strlen($product['datemodified'])) > 0) {
                            $product['has_shipping_summary'] = 1;
                        }
                        $boughtTransactionDetails[$transaction['idOrder'] . '-' . $transaction['sellerId']]['product'][$orderProducts[$productKey]['idOrderProduct']] = $product;
                    }
                    if ($product['attrName'] && $transaction['sellerId'] === $product['seller_id']) {
                        $boughtTransactionDetails[$transaction['idOrder'] . '-' . $transaction['sellerId']]['product'][$orderProducts[$productKey]['idOrderProduct']]['attr'][$product['attrName']] = $product['attrValue'];
                    }
                }
            }
        }

        return $boughtTransactionDetails;
    }

    /**
     * Get Sold transaction details
     * @param $memberId
     * @return mixed
     */
    public function getSoldTransactionDetails ($memberId)
    {
        $soldTransactionDetails = array();
        $getUserSoldTransactions = $this->em->getRepository('EasyShop\Entities\EsOrder')->getUserSoldTransactions($memberId);
        foreach ($getUserSoldTransactions as $key => $transaction) {
            if (!isset($soldTransactionDetails[$transaction['idOrder']])) {
                $soldTransactionDetails[$transaction['idOrder']] = $transaction;
                $orderProducts =
                    $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                        ->getOrderProductTransactionDetails($transaction['idOrder']);
                foreach ($orderProducts as $productKey => $product) {
                    if ( (int) $memberId !== (int) $product['seller_id']) {
                        continue;
                    }
                    if (!isset($soldTransactionDetails[$transaction['idOrder']]['product'][$orderProducts[$productKey]['idOrderProduct']])) {
                        $soldTransactionDetails[$transaction['idOrder']]['product'][$orderProducts[$productKey]['idOrderProduct']] = $product;
                    }
                    if ($product['attrName']) {
                        $soldTransactionDetails[$transaction['idOrder']]['product'][$orderProducts[$productKey]['idOrderProduct']]['attr'][$product['attrName']] = $product['attrValue'];
                    }
                }
            }
        }

        return $soldTransactionDetails;
    }
}
