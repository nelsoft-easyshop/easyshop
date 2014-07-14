<?php

/* 
 * Database param values
 */

$server_config_path = dirname(__FILE__).'/../../../../config.php';

if(file_exists ($server_config_path)){
	$serverConfig = require  $server_config_path;
	$dbConfig = [
	    'host'      => $serverConfig['DB_host'],
	    'driver'    => $serverConfig['DB_driver'],
	    'user'      => $serverConfig['DB_user'],
	    'password'  => $serverConfig['DB_password'],
	    'dbname'    => $serverConfig['DB_dbname'],
	];
	
}else{
	$dbConfig = [
	    'host'      => '127.0.0.1',
	    'driver'    => 'pdo_mysql',
	    'user'      => 'root',
	    'password'  => '121586',
	    'dbname'    => 'easyshop',
	];
}

return $dbConfig;

