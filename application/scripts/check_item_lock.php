<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$paymentService = $CI->kernel->serviceContainer['payment_service'];

use EasyShop\PaymentGateways\PayPalGateway as PayPalGateway;
use EasyShop\PaymentGateways\DragonPayGateway as DragonPayGateway;
use EasyShop\PaymentGateways\PesoPayGateway as PesoPayGateway;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;

class CheckItemLock
{
    private $connection;
    private $paymentService;

    public function __construct(
        $hostName,
        $dbUsername,
        $dbPassword,
        $paymentService
    ) {
        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );
        $this->paymentService = $paymentService;
    }

    public function execute()
    {
        $lockCollection = self::getAllLock();
        $orderIdArray = [];

        echo "\nDELETING LOCK SECTION\n\n";
        foreach ($lockCollection as $itemLock) {
            $maxMinute = null;
            switch ($itemLock['payment_method_id']) {
                case EsPaymentMethod::PAYMENT_PAYPAL:
                    $maxMinute = PayPalGateway::MAX_LOCK_LIFESPAN;
                    break;
                case EsPaymentMethod::PAYMENT_DRAGONPAY:
                    $maxMinute = DragonPayGateway::MAX_LOCK_LIFESPAN;
                    break;
                case EsPaymentMethod::PAYMENT_PESOPAYCC:
                    $maxMinute = PesoPayGateway::MAX_LOCK_LIFESPAN;
                    break;
                default:
                    continue;
            }
            $isExpired = self::isTimeExpired($itemLock['timestamp'], $maxMinute);
            if ($isExpired) {
                $orderIdArray[] = $itemLock['order_id'];
                self::deleteLock($itemLock['id_item_lock']);
                echo "Lock ID: " . $itemLock['id_item_lock'] . " - EXPIRED\n";
            }
        }

        echo "\nREVERTING POINTS SECTION\n\n";

        $uniqueOrderId = array_unique($orderIdArray);
        foreach ($uniqueOrderId as $orderId) {
            self::getPaymentService()->revertTransactionPoint($orderId);
            echo "Order ID: $orderId POINTS REVERTED \n";
        }

        echo "\nSCAN COMPLETED\n\n";
    }

    private function isTimeExpired($date, $maxMinute)
    {
        $elapsedMinutes = round((time() - strtotime(date($date))) / 60);
        if ($elapsedMinutes > $maxMinute) {
            return true;
        }

        return false;
    }

    private function getConnection()
    {
        return $this->connection;
    }

    private function getPaymentService()
    {
        return $this->paymentService;
    }

    private function getAllLock()
    {
        $selectLockQuery = "
            SELECT a.*,b.payment_method_id 
            FROM es_product_item_lock a, es_order b 
            WHERE a.order_id = b.id_order;
        ";
        $selectLock = self::getConnection()->prepare($selectLockQuery);
        $selectLock->execute();
        $allLock = $selectLock->fetchAll(PDO::FETCH_ASSOC);

        return $allLock;
    }

    private function deleteLock($lockId)
    {
        $lockId = (int) $lockId;
        $lockData = self::getLockData($lockId);
        if ((bool)$lockData) {
            $deleteLockQuery = "
                DELETE FROM `es_product_item_lock` WHERE `id_item_lock` = :lock_id
            ";
            // $deleteLock = self::getConnection()->prepare($deleteLockQuery);
            // $deleteLock->bindValue("lock_id", $lockId);
            // $deleteLock->execute();

            return true;
        }
        return false;
    }

    private function getLockData($lockId)
    {
        $lockId = (int) $lockId;
        $selectLockQuery = "
            SELECT * FROM `es_product_item_lock` WHERE `id_item_lock` = :lock_id
        ";
        $selectLock = self::getConnection()->prepare($selectLockQuery);
        $selectLock->bindValue("lock_id", $lockId);
        $selectLock->execute();
        $lockData = $selectLock->fetch(PDO::FETCH_ASSOC);

        return empty($lockData) === false ? $lockData : false;
    }
}

$checkItemLock  = new CheckItemLock(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $paymentService
);

$checkItemLock->execute();
