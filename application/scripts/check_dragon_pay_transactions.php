<?php

require_once(dirname(__FILE__).'/../libraries/dragonpay.php');

$currentDate = date('Y-m-d');
$holiday_arr = array();
$t = new Dragonpay;
$con = mysqli_connect("mysql:host=ip-172-31-3-69.ap-southeast-1.compute.internalt","easyshop","SECRETmy5ql","easyshop");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "
SELECT 
    transaction_id AS txnid
    ,transaction_id AS tid
    , dateadded  
FROM 
    es_order 
WHERE 
    `payment_method_id` = 2 
    AND order_status = 99
";

$sqlHolidays = "
SELECT * FROM `es_holidaydetails`
";



$sthHoliday = mysqli_query($con,$sqlHolidays);
while($r = mysqli_fetch_array($sthHoliday)) {
    $holiday_arr[] = $r['date_d'];  
}
asort($holiday_arr); 
array_values($holiday_arr);
$sth = mysqli_query($con,$sql);
$counter = 0;
echo PHP_EOL .'Scanning of data started ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL;

while($r = mysqli_fetch_array($sth)) {
    $counter++;
    $txnid = $r['txnid'];
    $tid = mysqli_real_escape_string($con,$r['tid']);
    $addDate = date('Y-m-d',strtotime($r['dateadded']));

    $additionalExpiration = 0;
    for ($i=1; $i <= 5; $i++) { 

        $inDate = date('Y-m-d',strtotime($r['dateadded'].' + '.$i.' days'));
        $weekend = date('w',strtotime($inDate));
        
        if($weekend == '0' || $weekend == '6')
        {
            $additionalExpiration += 1; 
        }elseif(in_array($inDate, $holiday_arr)){
            $additionalExpiration += 1; 
        }
    }
    $additionalExpiration += 5;
    $expiredDate = date('Y-m-d',strtotime($r['dateadded'].' + '.$additionalExpiration.' days'));
    
    $pass = "0";     
    while($pass == "0"){
        
        $ret = moveExpiredDate($expiredDate,$holiday_arr);
        $pass = $ret['pass'];
        $expiredDate = $ret['expdate'];

    }

    $status = $t->getStatus($txnid);
   
    if(strtolower($status) == 'p' || strtolower($status) == 'u'){

        if($currentDate >= $expiredDate){
               
              $message =  'VOIDED!';
              $voidResult = $t->voidTransaction($txnid);
              $sqlVoid = "
              CALL `es_sp_expiredDragonpayTransaction`('".$tid."')
              ";
              $sthReturn = mysqli_query($con,$sqlVoid) or die(mysqli_error($con));
              mysqli_next_result($con);
              $newstatus = $t->getStatus($txnid); 
        }else{
              $newstatus = $status;
              $message = 'NOTHING TO DO';
        }

    }elseif (strtolower($status) == 'v' || strtolower($status) == 'f') {

       $message = 'ALREADY VOIDED!';
       $voidResult = $t->voidTransaction($txnid);
       $sqlVoid = "
       CALL `es_sp_expiredDragonpayTransaction`('".$tid."')
       ";
       $sthReturn = mysqli_query($con,$sqlVoid) or die(mysqli_error($con));
       mysqli_next_result($con);
       $newstatus = $t->getStatus($txnid); 


    }elseif (strtolower($status) == 's') {
      $newstatus = $t->getStatus($txnid);
      $message = 'UPDATE TRANSACTION!';
        
        $sqlUpdate = "
              UPDATE es_order set order_status = 0 where transaction_id = '".$tid."'
              ";
        $sthReturn = mysqli_query($con,$sqlUpdate) or die(mysqli_error($con));
        
    }else{
      $newstatus = $status;
      $message = 'NOTHING TO DO';
    }
    echo $counter.') '.$tid.' : '.$status.' -> '. $newstatus  .' : ' .$message .  PHP_EOL;   
    
}
echo PHP_EOL .'Scanning of data ended ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL.$counter.' ROWS AFFECTED!'.PHP_EOL;

function moveExpiredDate($expDate,$holiday_arr){
    $weekend = date('w',strtotime($expDate));
    $pass = '0';
    if($weekend == '0')
    {
        
        $expDate = date('Y-m-d',strtotime($expDate.' + 1 day'));
    }elseif($weekend == '6'){
      
        $expDate = date('Y-m-d',strtotime($expDate.' + 2 days'));
    }

    if (in_array($expDate, $holiday_arr)){
        $expDate = date('Y-m-d',strtotime($expDate.' + 1 day'));
     
    }else{
        $pass = '1';
    }

    return array('expdate' => $expDate, 'pass' => $pass);
      
}

mysqli_close($con);
?>