<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$dragonPaySoapClient = $CI->kernel->serviceContainer['dragonpay_soap_client'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsOrderProductStatus as EsOrderProductStatus;
use EasyShop\PaymentService\PaymentService as PaymentService;

class CheckDragonPayTransaction
{
    const EXPIRATION_DAYS = 5;

    private $connection;
    private $dragonPaySoapClient;
    private $merchantId;
    private $merchantPwd;
    private $holidays;

    /**
     * Constructor
     * @param string         $hostName
     * @param string         $dbUsername
     * @param string         $dbPassword
     * @param \nusoap_client $dragonPaySoapClient
     * @param array          $dragpayConfig
     */
    public function __construct(
        $hostName,
        $dbUsername,
        $dbPassword,
        $dragonPaySoapClient,
        $dragpayConfig
    )
    {
        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );

        $this->soapClient = $dragonPaySoapClient;
        $this->merchantId = $dragpayConfig['merchant_id'];
        $this->merchantPwd = $dragpayConfig['merchant_password'];
        $this->holidays = $this->getHolidays();
    }

    /**
     * Main function to execute checking of dragonpay transactions
     */
    public function execute()
    {
        echo "\nScanning of data started (".date('M-d-Y h:i:s A').") \n \n";
        $orders = $this->getOrders();
        $currentDate = date('Y-m-d');
        $counter = 0;
        foreach ($orders as $order) {
            $counter++;
            $transactionId = $order['transaction_id'];
            $dataAdded = $order['dateadded'];
            $expiredDate = date('Y-m-d', strtotime($dataAdded.' + '.self::EXPIRATION_DAYS.' days'));
            $isPassed = false;
            while (!$isPassed) {
                $return = $this->moveExpiredDate($expiredDate, $this->holidays);
                $isPassed = $return['isPassed'];
                $expiredDate = $return['expdate'];
            }

            $status = $this->checkDragonpayOrderStatus($transactionId);
            if (strtolower($status) === PaymentService::STATUS_PENDING
                || strtolower($status) === PaymentService::STATUS_UNPAID) {
                if ($currentDate >= $expiredDate) {
                    $message =  'VOIDED!';
                    $this->voidDragonpayOrder($transactionId);
                    $this->voidTransaction($transactionId);
                    $newStatus = $this->checkDragonpayOrderStatus($transactionId);
                }
                else {
                    $newStatus = $status;
                    $message = 'NOTHING TO DO';
                }
            }
            elseif (strtolower($status) === PaymentService::STATUS_VOID
                || strtolower($status) === PaymentService::STATUS_FAIL) {
                $message = 'ALREADY VOIDED!';
                $this->voidDragonpayOrder($transactionId);
                $this->voidTransaction($transactionId);
                $newStatus = $this->checkDragonpayOrderStatus($transactionId);
            }
            elseif (strtolower($status) === PaymentService::STATUS_SUCCESS) {
                $message = 'UPDATE TRANSACTION!';
                $newStatus = $status;
                $this->acceptTransaction($transactionId);
            }
            else {
                $newStatus = $status;
                $message = 'NOTHING TO DO';
            }

            echo $counter.') '.$transactionId.' : '.$status.' -> '. $newStatus  .' : ' .$message .  PHP_EOL;
        }

        
        echo "\nScanning of data ended (".date('M-d-Y h:i:s A').") \n \n";
        echo count($orders)." ROWS SCANNED! \n \n";
    }

    /**
     * Get all holidays
     */
    private function getHolidays()
    {
        $sqlHolidays = "SELECT * FROM `es_holidaydetails`";

        foreach ($this->connection->query($sqlHolidays) as $row) {
            $holidays[] = $row['date_d'];
        }

        asort($holidays);
        array_values($holidays);

        return $holidays;
    }

    /**
     * Void dragonpay order in dashboard
     * @param  string $transactionId
     * @return integer
     */
    private function voidDragonpayOrder($transactionId)
    {
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $transactionId
        ];
        $result = $this->soapClient->call('CancelTransaction', $param);

        return $result['CancelTransactionResult'];
    }

    /**
     * Check the status of the order in dashboard
     * @param  string $transactionId [description]
     * @return string
     */
    private function checkDragonpayOrderStatus($transactionId)
    {
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'txnId' => $transactionId
        ];
        $result = $this->soapClient->call('GetTxnStatus', $param);

        return $result['GetTxnStatusResult'];
    }

    /**
     * Adjust expiration date of the transaction
     * @param  string $expDate
     * @param  array  $holidays
     * @return mixed
     */
    private function moveExpiredDate($expDate, $holidays)
    {
        $dayOfTheWeek = (int) date('w', strtotime($expDate));
        $isPassed = false;
        if ($dayOfTheWeek === 0) {
            $expDate = date('Y-m-d', strtotime($expDate.' + 1 day'));
        }
        elseif ($dayOfTheWeek === 6) {
            $expDate = date('Y-m-d', strtotime($expDate.' + 2 days'));
        }

        if (in_array($expDate, $holidays)) {
            $expDate = date('Y-m-d', strtotime($expDate.' + 1 day'));
        }
        else {
            $isPassed = true;
        }

        return [
            'expdate' => $expDate,
            'isPassed' => $isPassed
        ];
    }

    /**
     * Void Order Transaction by given transaction id
     * @param  string $transactionId
     */
    private function voidTransaction($transactionId)
    {
        $order = $this->getOrderByTransactionId($transactionId);
        if ($order) {
            $this->updateOrderStatus($transactionId, EsOrderStatus::STATUS_VOID);
            $orderProducts = $this->getAllOrderProduct($order['id_order']);
            foreach ($orderProducts as $orderProduct) {
                $orderProductId = $orderProduct['id_order_product'];
                $itemId = $orderProduct['product_item_id'];
                $revertQuantity = $orderProduct['order_quantity'];
                $this->revertProductQuantity($itemId, $revertQuantity);
                $this->updateOrderProductStatus($orderProductId, EsOrderProductStatus::CANCEL);
            }
        }
    }

    /**
     * Update order into paid status
     * @param  string $transactionId
     */
    private function acceptTransaction($transactionId)
    {
        $this->updateOrderStatus($transactionId, EsOrderStatus::STATUS_PAID);
    }

    /**
     * Get all order of dragonpay
     * @return array
     */
    private function getOrders()
    {
        $selectOrdersQuery = "
        SELECT 
            transaction_id
            , dateadded
        FROM 
            es_order 
        WHERE 
            `payment_method_id` = :payment_method_id
            AND `order_status` = :order_status
        ";

        $selectOrder = $this->connection->prepare($selectOrdersQuery);
        $selectOrder->bindValue("payment_method_id", EsPaymentMethod::PAYMENT_DRAGONPAY);
        $selectOrder->bindValue("order_status", EsOrderStatus::STATUS_DRAFT);
        $selectOrder->execute();
        $orders = $selectOrder->fetchAll(PDO::FETCH_ASSOC);

        return isset($orders[0]) ? $orders : [];
    }

    /**
     * Get order using transaction id
     * @param  string $transactionId
     * @return array
     */
    private function getOrderByTransactionId($transactionId)
    {
        $selectOrderQuery = "
            SELECT  *
            FROM `es_order` where `transaction_id` = :transaction_id
            LIMIT 1
        ";

        $selectOrder = $this->connection->prepare($selectOrderQuery);
        $selectOrder->bindValue("transaction_id", $transactionId);
        $selectOrder->execute();
        $order = $selectOrder->fetchAll(PDO::FETCH_ASSOC);

        return isset($order[0]) ? $order[0] : false;
    }

    /**
     * Get all order product in given order
     * @param  integer $orderId
     * @return array
     */
    private function getAllOrderProduct($orderId)
    {
        $selectOrderProductQuery = "
            SELECT  id_order_product, order_quantity, product_item_id
            FROM `es_order_product` where `order_id` = :order_id
        ";

        $selectOrderProducts = $this->connection->prepare($selectOrderProductQuery);
        $selectOrderProducts->bindValue("order_id", $orderId);
        $selectOrderProducts->execute();
        $orderProducts = $selectOrderProducts->fetchAll(PDO::FETCH_ASSOC);

        return $orderProducts;
    }

    /**
     * Update order status of a given order
     * @param  string  $transactionId
     * @param  integer $status
     */
    private function updateOrderStatus($transactionId, $status)
    {
        $lastDataModified = date('Y-m-d h:i:s');
        $updateOrderQuery = "
            UPDATE es_order 
            SET 
                order_status = :status,
                datemodified = :date
            WHERE
                transaction_id = :transaction_id
                AND payment_method_id = :payment_method_id
        ";

        $updateOrder = $this->connection->prepare($updateOrderQuery);
        $updateOrder->bindValue("status", $status);
        $updateOrder->bindValue("date", $lastDataModified);
        $updateOrder->bindValue("transaction_id", $transactionId);
        $updateOrder->bindValue("payment_method_id", EsPaymentMethod::PAYMENT_DRAGONPAY);
        $updateOrder->execute();
    }

    /**
     * Update order product status of a given product
     * @param  integer $orderProductId
     * @param  integer $status
     */
    private function updateOrderProductStatus($orderProductId, $status)
    {
        $updateOrderProductStatus = "
            UPDATE es_order_product
            SET status = :status
            WHERE id_order_product = :order_product_id;
        ";

        $updateStatus = $this->connection->prepare($updateOrderProductStatus);
        $updateStatus->bindValue("status", $status);
        $updateStatus->bindValue("order_product_id", $orderProductId);
        $updateStatus->execute();
    }

    /**
     * Revert quantity of the item
     * @param  integer $itemId
     * @param  integer $quantity
     */
    private function revertProductQuantity($itemId, $quantity)
    {
        $updateQuantityQuery = "
            UPDATE es_product_item
            SET quantity = quantity + :order_quantity
            WHERE id_product_item = :item_id;
        ";

        $updateQuantity = $this->connection->prepare($updateQuantityQuery);
        $updateQuantity->bindValue("item_id", $itemId);
        $updateQuantity->bindValue("order_quantity", $quantity);
        $updateQuantity->execute();
    }
}

if (!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production') {
    $configLoad = $configLoader->getItem('payment', 'production');
}
else {
    $configLoad = $configLoader->getItem('payment', 'testing');
}
$config = $configLoad['payment_type']['dragonpay']['Easyshop'];

$dragonpayCheck  = new checkDragonPayTransaction(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $dragonPaySoapClient,
    $config
);

$dragonpayCheck->execute();
