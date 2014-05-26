<?php 

$path = '../../assets/temp_product';

foreach (new DirectoryIterator($path) as $fileInfo) {
    if(!$fileInfo->isDot()) {
        $timestamp = $fileInfo->getCTime();
        $timestamp = gmdate("Y-m-d H:i:s", $timestamp);
        $now = date("Y-m-d H:i:s");
        $interval = date_diff(new DateTime($now),new DateTime($timestamp));
        //DELETE ALL CONTENT THAT IS AT LEAST 3 HOUR OLD
        if(intval($interval->format('H'),10) > 3){
            //This may fail if the application does not have access to the folders
            unlink($fileInfo->getPathname());
        }
    }
}