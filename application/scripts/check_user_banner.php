<?php

$configDatabase = require dirname(__FILE__). '/../config/param/database.php';

$dbh = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);

$sql = "SELECT * FROM es_member WHERE `is_hide_banner` = 0"; 

$totalCount = 0;
$writeString = "";
foreach ($dbh->query($sql) as $row) {
    $path = dirname(__FILE__).'/../../web/assets/user/';
    $folderName = $row['id_member'].'_'.$row['username'];
    $bannerImageName = '/banner.png';
    if (file_exists($path.$folderName)) { 
        if (file_exists($path.$folderName.$bannerImageName)) { 
            $writeString .= $row['id_member'] .' '. $row['username'] ."\n";
            $totalCount++;
        } 
    }
}

$countString = "TOTAL COUNT:" . $totalCount ."\n \n";
echo $countString;
$fp=fopen("user_list_banner_changed.txt","w+"); 
fwrite($fp,$countString.$writeString); 
fclose($fp);

$dbh = null;