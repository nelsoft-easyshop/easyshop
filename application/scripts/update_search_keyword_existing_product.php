<?php

require(dirname(__FILE__).'/../src/EasyShop/CLI/CLIHelper.php');
$_SERVER['QUERY_STRING'] = '';
ob_start();
require(__DIR__ . '/../../web/index.php');
ob_end_clean(); 
require_once(dirname(__FILE__).'/../src/EasyShop/Product/ProductManager.php');
include_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../src/EasyShop/Core/Configuration/Configuration.php';
$CI =& get_instance();

$productManager = $CI->kernel->serviceContainer['product_manager'];

$configDatabase = require dirname(__FILE__). '/../config/param/database.php';

$con = mysqli_connect($configDatabase['host'],$configDatabase['user'],$configDatabase['password'],$configDatabase['dbname']);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "SELECT id_product FROM es_product";
$sth = mysqli_query($con,$sql); 
$counter = 0;
echo PHP_EOL .'Scanning of data started ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL;

while($r = mysqli_fetch_array($sth)) { 
    $productManager->generateSearchKeywords($r['id_product']);
    echo $r['id_product'] . ' DONE'.PHP_EOL;
    $counter++;
}

echo PHP_EOL .'Scanning of data ended ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL.$counter.' ROWS AFFECTED!'.PHP_EOL;

mysqli_close($con);
?>
