<?php 

include_once  __DIR__.'/../src/EasyShop/CLI/CLIHelper.php';
include_once __DIR__ . '/../../vendor/autoload.php';

ob_start();
include_once __DIR__ . '/../../web/index.php';
ob_end_clean(); 
