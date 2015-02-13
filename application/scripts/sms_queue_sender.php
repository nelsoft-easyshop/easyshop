<?php

echo "\nLoading Instance...";

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();



use EasyShop\Entities\EsQueueStatus as EsQueueStatus;
use EasyShop\Entities\EsQueueType as EsQueueType;


$configDatabase = require dirname(__FILE__). '/../config/param/database.php';
$CI->load->config('sms', true);
$configSms = $CI->config->item('sms');

echo "\t\033[0;32m[OK]\033[0m\n";

$dbh = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'],
                $configDatabase['user'],
                $configDatabase['password']);

$queueType = EsQueueType::TYPE_MOBILE;
$queueStatus = EsQueueStatus::QUEUED;

echo "\nFetching Queued...";
$sql = "SELECT id_queue, data, type, date_created, date_executed, status
        FROM es_queue
        WHERE type = :queue 
            AND status = :status";
 
$queueDbh = $dbh->prepare($sql);
$queueDbh->bindParam("queue", $queueType, PDO::PARAM_INT);
$queueDbh->bindParam("status", $queueStatus, PDO::PARAM_INT);
$queueDbh->execute();
$rawData = $queueDbh->fetchAll(PDO::FETCH_ASSOC);
$smsCount = count($rawData);
$numSuccess = 0;
$numFailed = 0;

echo "\t\033[0;32m[OK]\033[0m\n";

if($smsCount <= 0){
    echo "No SMS queued. \n\n";
}
else{
    echo "Fetched " . $smsCount . " queued sms!\n\n";
    foreach($rawData as $data){
        $smsData = json_decode($data['data'], true);

        if( preg_match('/^(8|9)[0-9]{9}$/', $smsData['number']) ){

            echo "Sending SMS - Queue id: " . $data['id_queue'] . "...";

            $outbound_endpoint = $configSms['outbound_endpoint'];
            $smsParam_string = http_build_query($smsData);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $outbound_endpoint);
            curl_setopt($ch,CURLOPT_POST, count($smsData));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $smsParam_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if(strtolower($output['status']) === "success"){ 
                $numSuccess++;
                echo "\t\033[0;32m[SENT]\033[0m\n";
                $status = EsQueueStatus::SENT;
            }
            else{
                $numFailed++;
                echo "\t\033[0;31m[FAILED]\033[0m\n";
                $status = EsQueueStatus::FAILED;
            }

            $exec_date = date("Y-m-d H:i:s");
            $updateSql = "UPDATE es_queue SET `status` = :status, `date_executed` = :exec_date WHERE `id_queue` = :queue_id";
            $dbhUpdate = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'],
                                 $configDatabase['user'], 
                                 $configDatabase['password']);
            $queueUpdate = $dbhUpdate->prepare($updateSql);
            $queueUpdate->bindParam("status", $status, PDO::PARAM_INT);
            $queueUpdate->bindParam("exec_date", $exec_date);
            $queueUpdate->bindParam("queue_id", $data['id_queue'], PDO::PARAM_INT);
            $queueUpdate->execute();
        }
    }

    echo "\n\033[0;32mSending Done.\033[0m\n";
    echo "\nSuccess: " . $numSuccess . "\n";
    echo "Failed: " . $numFailed . "\n";
    echo "Total: " . $smsCount . " SMS\n\n";
}

