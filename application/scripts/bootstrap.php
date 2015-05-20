<?php 

include_once  __DIR__.'/../src/EasyShop/CLI/CLIHelper.php';
include_once __DIR__ . '/../../vendor/autoload.php';

$_SERVER['REMOTE_ADDR'] = 'CLI';
$_SERVER['HTTP_HOST'] = 'CLI';
$_SERVER['REQUEST_URI'] = 'CLI';
$_SERVER['REQUEST_URI'] = 'CLI';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_USER_AGENT'] = 'CLI';

ob_start();
include_once __DIR__ . '/../../web/index.php';
ob_end_clean(); 
