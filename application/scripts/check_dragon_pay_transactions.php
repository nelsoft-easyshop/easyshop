<?php
require_once('../libraries/dragonpay.php');

$t = new Dragonpay;
$con = mysqli_connect("localhost","root","121586","easyshop");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "
SELECT SPLIT_STRING(transaction_id, '-',1) AS txnid,transaction_id AS tid, dateadded  FROM es_order WHERE `payment_method_id` = 2 AND order_status = 99
";

$sth = mysqli_query($con,$sql);
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
    $txnid = $r['txnid'];
    $tid = mysqli_real_escape_string($con,$r['tid']);
    $addDate = date('Y-m-d',strtotime($r['dateadded']));
    $expiredDate = date('Y-m-d',strtotime($r['dateadded'].' + 3 days'));
    $currentDate = date('Y-m-d');
    $status = $t->getStatus($txnid);
    if(strtolower($status) == 'p'){
        if($currentDate >= $expiredDate){
            $sql = "
                CALL `es_sp_expiredDragonpayTransaction`('".$tid."')
            ";
        }
    }
    
}
 


mysqli_close($con);
?>