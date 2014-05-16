<?php
require_once('../libraries/dragonpay.php');

$currentDate = date('Y-m-d');
$holiday_arr = array();
$t = new Dragonpay;
$con = mysqli_connect("localhost","root","121586","easyshop");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "
SELECT 
    SPLIT_STRING(transaction_id, '-',1) AS txnid
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
    $expiredDate = date('Y-m-d',strtotime($r['dateadded'].' + 5 days'));
    
    $pass = "0";     
    while($pass == "0"){
        
        $ret = moveExpiredDate($expiredDate,$holiday_arr);
        $pass = $ret['pass'];
        $expiredDate = $ret['expdate'];

    }

    $status = $t->getStatus($txnid);
   
    if(strtolower($status) == 'p' || strtolower($status) == 'v'){

        if($currentDate >= $expiredDate){
               
              $message = (strtolower($status) == 'p' ? 'VOIDED!' : 'ALREADY VOIDED!');
              $voidResult = $t->voidTransaction($txnid);
              $sqlVoid = "
              CALL `es_sp_expiredDragonpayTransaction`('".$tid."')
              ";
              $sthReturn = mysqli_query($con,$sqlVoid) or die(mysqli_error($con));
              mysqli_next_result($con);
              $newstatus = $t->getStatus($txnid); 
        }

    }elseif (strtolower($status) == 's') {
      $newstatus = $t->getStatus($txnid);
      $message = 'UPDATE TRANSACTION!';
        
        $sqlUpdate = "
              UPDATE es_order set order_status = 0 where transaction_id = '".$tid."'
              ";
        $sthReturn = mysqli_query($con,$sqlUpdate) or die(mysqli_error($con));
        
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