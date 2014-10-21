<?php

// get database configuration
$configDatabase = require dirname(__FILE__). '/../config/param/database.php';
$con = mysqli_connect($configDatabase['host'],$configDatabase['user'],$configDatabase['password'],$configDatabase['dbname']);

// query all the possible user
$query = "SELECT * FROM es_member WHERE `is_hide_banner` = 0";
$users = mysqli_query($con,$query);
$totalCount = 0;
$writeString = "";
while($rows = mysqli_fetch_array($users)) {

    // check if users folder exist
    $path = dirname(__FILE__).'/../../web/assets/user/';
    $folderName = $rows['id_member'].'_'.$rows['username'];
    $bannerImageName = '/banner.png';
    if (file_exists($path.$folderName)) { 
        if (file_exists($path.$folderName.$bannerImageName)) { 
            $writeString .= $rows['id_member'] .' '. $rows['username'] ."\n";
            $totalCount++;
        } 
    }
}

// write file
$fp=fopen("user_list_banner_changed.txt","w+"); 
fwrite($fp,$writeString); 
fclose($fp);
