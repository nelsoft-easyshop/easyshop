<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();

$productManager = $CI->kernel->serviceContainer['product_manager'];

$configDatabase = require dirname(__FILE__). '/../config/param/database.php';

$dbh = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'],
                $configDatabase['user'],
                $configDatabase['password']);

$sql = "SELECT id_product FROM es_product";
$counter = 0;
echo PHP_EOL .'Scanning of data started ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL;

foreach ($dbh->query($sql) as $row) {
    $productManager->generateSearchKeywords($row['id_product']);
    echo $row['id_product'] . ' DONE'.PHP_EOL;
    $counter++;
}

echo PHP_EOL .'Scanning of data ended ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
echo PHP_EOL.$counter.' ROWS AFFECTED!'.PHP_EOL;

$dbh = null;
?>
