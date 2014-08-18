<?php

/**
 * Database param values
 *
 */


require_once __DIR__ . '/../../src/EasyShop/Core/Configuration/Configuration.php';
$configService = new EasyShop\Core\Configuration\Configuration();


if($configService->isConfigFileExists()){
    $serverConfig = $configService->getConfigValue();
    $dbConfig = [
        'host'      => $serverConfig['DB_host'],
        'driver'    => $serverConfig['DB_driver'],
        'user'      => $serverConfig['DB_user'],
        'password'  => $serverConfig['DB_password'],
        'dbname'    => $serverConfig['DB_dbname'],
    ];

}
else{
    $dbConfig = [
        'host'      => '127.0.0.1',
        'driver'    => 'pdo_mysql',
        'user'      => 'root',
        'password'  => '121586',
        'dbname'    => 'easyshop',
    ];
}

return $dbConfig;

