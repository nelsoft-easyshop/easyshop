<?php

include_once  __DIR__.'/bootstrap.php';

echo "\nLoading Instance...";

$CI =& get_instance(); 
$pointTracker = $CI->kernel->serviceContainer['point_tracker'];

use EasyShop\Entities\EsPointType as EsPointType;

echo "\t\033[0;32m[OK]\033[0m\n\n";
$dbh = new PDO($CI->db->hostname, $CI->db->username , $CI->db->password); 

$sqlQuery = "
    SELECT 
        `member_id`, `point`
    FROM
        `es_point`
    WHERE
        '".date('Y-m-d H:i:s')."' >= `expiration_date`
    AND `point` > 0
";

$pointDbh = $dbh->prepare($sqlQuery); 
$pointDbh->execute();
$recordCollection = $pointDbh->fetchAll(PDO::FETCH_ASSOC);

$updatedSuccess = 0;
$updatedFail = 0;
foreach ($recordCollection as $record) {
    $memberId = (int) $record['member_id'];
    $currentPoint = $record['point'];

    echo "Updating Points - Member id: " . $memberId . " --->";
    $isUpdated = $pointTracker->spendUserPoint($memberId, EsPointType::TYPE_EXPIRED, $currentPoint);
    echo $isUpdated ? "\t\033[0;32m[UPDATED]\033[0m\n" : "\t\033[0;31m[FAILED]\033[0m\n";
    $isUpdated ? $updatedSuccess++ : $updatedFail++ ;
}

echo "\n\033[0;32mUpdating Done.\033[0m\n";
echo "\nSuccess: " . $updatedSuccess . "\n";
echo "Failed: " . $updatedFail . "\n";
echo "Total: " . count($recordCollection) . " Users\n\n";

