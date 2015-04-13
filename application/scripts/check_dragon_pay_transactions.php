<?php

include_once  __DIR__.'/bootstrap.php';
require_once(dirname(__FILE__).'/../libraries/dragonpay.php');

$currentDate = date('Y-m-d');
$holidays = [];
$counter = 0;
$dragonPay = new Dragonpay;

$configDatabase = require dirname(__FILE__). '/../config/param/database.php';

$con = mysqli_connect($configDatabase['host'],$configDatabase['user'],$configDatabase['password'],$configDatabase['dbname']);
$dbh = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);
$dbhUpdate = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);

$sql = "
SELECT 
    transaction_id AS txnid 
    , dateadded  
FROM 
    es_order 
WHERE 
    `payment_method_id` = 2 
    AND order_status = 99
";

$sqlHolidays = "SELECT * FROM `es_holidaydetails`";

foreach ($dbh->query($sqlHolidays) as $row) {
    $holidays[] = $row['date_d'];
}

asort($holidays); 
array_values($holidays);
echo PHP_EOL .'Scanning of data started ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL;

$sqlVoid = "CALL `es_sp_expiredDragonpayTransaction`(:transaction_id)"; 
$statementStoredProc = $dbh->prepare($sqlVoid);

$sqlSuccessOrder = "UPDATE es_order set order_status = 0 where transaction_id = :transaction_id"; 
$statementSuccess = $dbh->prepare($sqlSuccessOrder);

foreach ($dbh->query($sql) as $row) {
    $counter++;
    $txnid = $row['txnid'];
    $dataAdded = $row['dateadded']; 

    $additionalExpiration = 0;
    for ($i = 1; $i <= 5; $i++) { 
        $inDate = (int) date('Y-m-d',strtotime($dataAdded.' + '.$i.' days'));
        $weekend = (int) date('w',strtotime($inDate));
        
        if($weekend === 0 || $weekend === 6){
            $additionalExpiration += 1; 
        }
        elseif(in_array($inDate, $holidays)){
            $additionalExpiration += 1; 
        }
    }
    $additionalExpiration += 5;
    $expiredDate = date('Y-m-d',strtotime($dataAdded.' + '.$additionalExpiration.' days'));
    
    $isPassed = false;
    while(!$isPassed){
        $return = moveExpiredDate($expiredDate,$holidays);
        $isPassed = $return['isPassed'];
        $expiredDate = $return['expdate'];
    }

    $status = $dragonPay->getStatus($txnid);

    if(strtolower($status) == 'p' || strtolower($status) == 'u'){
        if($currentDate >= $expiredDate){
            $message =  'VOIDED!';
            $voidResult = $dragonPay->voidTransaction($txnid);
            $statementStoredProc->bindParam(":transaction_id", $txnid);
            $statementStoredProc->execute(); 
            $newStatus = $dragonPay->getStatus($txnid); 
        }
        else{
            $newStatus = $status;
            $message = 'NOTHING TO DO';
        }
    }
    elseif (strtolower($status) == 'v' || strtolower($status) == 'f') {
        $message = 'ALREADY VOIDED!';
        $voidResult = $dragonPay->voidTransaction($txnid);
        $statementStoredProc->bindParam(":transaction_id", $txnid);
        $statementStoredProc->execute(); 
        $newStatus = $dragonPay->getStatus($txnid); 
    }
    elseif (strtolower($status) == 's') {
        $message = 'UPDATE TRANSACTION!';
        $newStatus = $dragonPay->getStatus($txnid);
        $statementSuccess->bindParam(":transaction_id", $txnid);
        $statementSuccess->execute(); 
    }
    else{
      $newStatus = $status;
      $message = 'NOTHING TO DO';
    }
    echo $counter.') '.$txnid.' : '.$status.' -> '. $newStatus  .' : ' .$message .  PHP_EOL;   
}

echo PHP_EOL .'Scanning of data ended ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL.$counter.' ROWS SCANNED!'.PHP_EOL;


function moveExpiredDate($expDate, $holidays){
    $weekend = (int) date('w',strtotime($expDate));
    $isPassed = false;
    if($weekend === 0){
        $expDate = date('Y-m-d',strtotime($expDate.' + 1 day'));
    }
    elseif($weekend === 6){
        $expDate = date('Y-m-d',strtotime($expDate.' + 2 days'));
    }

    if (in_array($expDate, $holidays)){
        $expDate = date('Y-m-d',strtotime($expDate.' + 1 day'));
    }
    else{
        $isPassed = true;
    }

    return array('expdate' => $expDate, 'isPassed' => $isPassed);
}

mysqli_close($con);
?>
