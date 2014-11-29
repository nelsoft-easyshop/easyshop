<?php

namespace EasyShop\Transaction;
use EasyShop\Entities\EsOrderProductStatus;
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
                        $product['has_shipping_summary'] = false;
                        if ( (bool) $product['courier'] === true &&  (bool) $product['datemodified']  === true ) {
                            $product['has_shipping_summary'] = true;
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

    public function updateTransactionStatus($status, $orderProductId, $orderId, $invoiceNumber, $memberId)
    {
        $result = [
            'o_success' => false,
            'o_message' => 'Product Order entry not found!'
        ];
        $getOrderProduct = $this->getOrderProductByStatus($status, $orderProductId, $orderId, $invoiceNumber, $memberId);

        if ( (bool) $getOrderProduct['orderProductId'] ) {
            $esOrderProduct = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                        ->findOneBy([
                                            'idOrderProduct' => $orderProductId,
                                            'order' => $orderId
                                        ]);
            $esOrderProductStatus = $this->em->getRepository('EasyShop\Entities\EsOrderProductStatus')->find($status);
            $this->em->getRepository('EasyShop\Entities\EsOrderProduct')->updateOrderProductStatus($esOrderProductStatus, $esOrderProduct);
            $this->em->getRepository('EasyShop\Entities\EsOrderProductHistory')->createHistoryLog($esOrderProduct, $esOrderProductStatus, $getOrderProduct['historyLog']);

            $doesAllOrderProductResponded = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                                        ->findOneBy([
                                                            'status' => EsOrderProductStatus::STATUS_ONGOING,
                                                            'order' => $orderId
                                                        ]);
            if ( ! (bool) $doesAllOrderProductResponded ) {
                $esOrder = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                        ->findOneBy([
                                            'invoiceNo' => $invoiceNumber,
                                            'idOrder' => $orderId
                                        ]);
                $esOrderStatus = $this->em->getRepository('EasyShop\Entities\EsOrderStatus')->find(1);
                $this->em->getRepository('EasyShop\Entities\EsOrder')->updateOrderStatus($esOrder, $esOrderStatus);
                $orderHistoryData = [
                    'order_id' => $orderId,
                    'order_status' => EsOrderProductStatus::STATUS_FORWARD_TO_SELLER,
                    'comment' => 'COMPLETED',
                ];
                $this->em->getRepository('EasyShop\Entities\EsOrderHistory')->addOrderHistory($orderHistoryData);
            }

            $result = [
                'o_success' => true,
                'o_message' => 'Product Order entry updated!'
            ];
        }

        $result = [
            'o_success' => true,
            'o_message' => 'Product Order entry updated!'
        ];
        return $result;
    }

    public function getOrderProductByStatus($status, $orderProductId, $orderId, $invoiceNumber, $memberId)
    {
        $result = [
            'orderProductId' => false,
            'historyLog' => false
        ];
        if ( (int) $status === 1 ) {
            $qb = $this->em->createQueryBuilder()
                ->select('op.idOrderProduct')
                ->from('EasyShop\Entities\EsOrderProduct','op')
                ->innerJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder AND op.order = :orderId AND op.idOrderProduct = :orderProductId AND o.invoiceNo = :invoice')
                ->where('op.status = 0 AND o.buyer = :memberId')
                ->setParameter('orderId', $orderId)
                ->setParameter('orderProductId', $orderProductId)
                ->setParameter('invoice', $invoiceNumber)
                ->setParameter('memberId', $memberId)
                ->getQuery();
            $orderProduct = $qb->getOneOrNullResult();

            $result = [
                'orderProductId' => $orderProduct['idOrderProduct'],
                'historyLog' => 'FORWARDED'
            ];
        }
        else if ( (int) $status === (int) EsOrderProductStatus::STATUS_RETURNED_TO_BUYER || (int) $status === (int) EsOrderProductStatus::STATUS_COD ) {
            $qb = $this->em->createQueryBuilder()
                ->select('op.idOrderProduct')
                ->from('EasyShop\Entities\EsOrderProduct','op')
                ->innerJoin('EasyShop\Entities\EsOrder', 'o','WITH','op.order = o.idOrder AND o.invoiceNo = :invoice')
                ->where('op.status = 0 AND op.seller = :memberId AND op.idOrderProduct = :orderProductId AND op.order = :orderId')
                ->setParameter('invoice', $invoiceNumber)
                ->setParameter('orderId', $orderId)
                ->setParameter('orderProductId', $orderProductId)
                ->setParameter('memberId', $memberId)
                ->getQuery();
            $orderProduct = $qb->getOneOrNullResult();

            $result['orderProductId'] = $orderProduct['idOrderProduct'];
            if ( (int) $status === (int) EsOrderProductStatus::STATUS_RETURNED_TO_BUYER ) {
                $result['historyLog'] = 'RETURNED';
            }
            else if ( (int) $status === (int) EsOrderProductStatus::STATUS_COD ) {
                $result['historyLog'] = 'COD - COMPLETED';
            }
        }

        return $result;
    }

    /**
     * Get order product details
     * @param $orderId
     * @param $orderProductId
     * @param $memberId
     * @param $invoiceNum
     * @param $orderProductStatus
     * @return array
     */
    public function getOrderProductTransactionDetails($orderId, $orderProductId, $memberId, $invoiceNum, $orderProductStatus)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('
                tbl_o.idOrder as id_order, tbl_o.invoiceNo as invoice_no, tbl_op.idOrderProduct as id_order_product, tbl_p.name as product_name, tbl_op.price as price, tbl_op.orderQuantity as order_quantity,
                tbl_op.handlingFee as handling_fee, tbl_op.total as total, tbl_op.easyshopCharge as easyshop_charge, tbl_op.paymentMethodCharge as payment_method_charge,
                tbl_op.net as net,tbl_opa.attrName as attr_name, tbl_opa.attrValue as attr_value,
                tbl_m_seller.username as seller, tbl_m_seller.email as seller_email, tbl_m_seller.contactno as seller_contactno,
                tbl_m_buyer.username as buyer, tbl_m_buyer.email as buyer_email, tbl_m_buyer.contactno as buyer_contactno,
                tbl_pm.idPaymentMethod as payment_method_id,
                tbl_m_buyer.slug as buyer_slug, tbl_m_seller.slug as seller_slug
            ')
            ->from('EasyShop\Entities\EsOrder', 'tbl_o')
            ->innerJoin('EasyShop\Entities\EsOrderProduct', 'tbl_op', 'WITH', 'tbl_op.order = tbl_o.idOrder AND tbl_op.order = :orderId AND tbl_op.idOrderProduct = :orderProductId AND tbl_o.invoiceNo = :invoiceNum')
            ->innerJoin('EasyShop\Entities\EsProduct', 'tbl_p', 'WITH', 'tbl_op.product = tbl_p.idProduct')
            ->leftJoin('EasyShop\Entities\EsOrderProductAttr', 'tbl_opa', 'WITH', 'tbl_opa.orderProduct = tbl_op.idOrderProduct')
            ->leftJoin('EasyShop\Entities\EsMember', 'tbl_m_seller', 'WITH', 'tbl_m_seller.idMember = tbl_op.seller')
            ->leftJoin('EasyShop\Entities\EsMember', 'tbl_m_buyer', 'WITH', 'tbl_m_buyer.idMember = tbl_o.buyer')
            ->leftJoin('EasyShop\Entities\EsPaymentMethod', 'tbl_pm', 'WITH', 'tbl_pm.idPaymentMethod = tbl_o.paymentMethod')
            ->where('tbl_op.seller = :memberId OR tbl_o.buyer = :memberId')
            ->setParameter('orderId', $orderId)
            ->setParameter('orderProductId', $orderProductId)
            ->setParameter('invoiceNum', $invoiceNum)
            ->setParameter('memberId', $memberId)
            ->getQuery();
        $row = $queryBuilder->getResult();

        $parseData = array_splice($row[0], 1, 10);
        $parseData['attr'] = array();

        if ( (int) $orderProductStatus === (int) EsOrderProductStatus::STATUS_FORWARD_TO_SELLER ) {
            $parseData['user'] = $row[0]['buyer'];
            $parseData['user_slug'] = $row[0]['buyer_slug'];
            $parseData['email'] = $row[0]['seller_email'];
            $parseData['mobile'] = trim($row[0]['seller_contactno']);
            $parseData['recipient'] = $row[0]['seller'];
        }
        else if ( (int) $orderProductStatus === (int) EsOrderProductStatus::STATUS_RETURNED_TO_BUYER || (int) $orderProductStatus === (int) EsOrderProductStatus::STATUS_COD ) {
            $parseData['user'] = $row[0]['seller'];
            $parseData['user_slug'] = $row[0]['seller_slug'];
            $parseData['email'] = $row[0]['buyer_email'];
            $parseData['mobile'] = trim($row[0]['buyer_contactno']);
            $parseData['recipient'] = $row[0]['buyer'];
        }

        switch( (int) $row[0]['payment_method_id'] ){
            case 1:
                $parseData['payment_method_name'] = "PayPal";
                break;
            case 2:
                $parseData['payment_method_name'] = "DragonPay";
                break;
            case 3:
                $parseData['payment_method_name'] = "Cash on Delivery";
                break;
            case 5:
                $parseData['payment_method_name'] = "Bank Deposit";
                break;
        }

        foreach( $row as $r){
            if( (string) $r['attr_name'] !== '' && (string) $r['attr_value'] !== '' ) {
                array_push($parseData['attr'], array('field' => ucwords(strtolower($r['attr_name'])), 'value' => ucwords(strtolower($r['attr_value'])) ));
            }
            else {
                array_push($parseData['attr'], array('field' => 'Attribute', 'value' => 'N/A' ));
            }
        }

        $parseData['price'] = number_format($parseData['price'], 2, '.', ',');
        $parseData['handling_fee'] = number_format($parseData['handling_fee'], 2, '.', ',');
        $parseData['total'] = number_format($parseData['total'], 2, '.', ',');
        $parseData['easyshop_charge'] = number_format($parseData['easyshop_charge'], 2, '.', ',');
        $parseData['payment_method_charge'] = number_format($parseData['payment_method_charge'], 2, '.', ',');
        $parseData['net'] = number_format($parseData['net'], 2, '.', ',');

        return $parseData;
    }

    /**
     * Check transaction if exists
     * @param $orderId
     * @param $buyer
     * @param $seller
     * @return bool
     */
    public function doesTransactionExists($orderId, $buyer, $seller)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('o.idOrder')
            ->from('EasyShop\Entities\EsOrder','o')
            ->innerJoin('EasyShop\Entities\EsOrderProduct', 'op','WITH','op.order = o.idOrder AND o.idOrder = :orderId AND o.buyer = :buyer AND op.seller = :seller')
            ->setParameter('orderId', $orderId)
            ->setParameter('buyer', $buyer)
            ->setParameter('seller', $seller)
            ->getQuery();
        $order = $qb->getResult();

        return (bool) $order;
    }
}
