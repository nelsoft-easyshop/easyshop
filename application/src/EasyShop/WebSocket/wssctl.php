<?php

/**
 * Web Socket Server control script
 */

/* show errors */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$rootDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' .DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

// register easyshop autoloader
require_once $rootDir . 'application/src/EasyShop/Core/ClassAutoloader/PSR0Autoloader.php';
$psr0Autoloader = new PSR0Autoloader($rootDir . "application/src/");
$psr0Autoloader->register();

// register 3rd party autoloader
require_once $rootDir . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$paths      = array('src/EasyShop/Entities');
$isDevMode  = true;
$dbConfig   = require $rootDir . 'application/config/param/database.php';
$config     = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([$rootDir . 'application/src/EasyShop/Entities'], $isDevMode);

$eventLoop          = \React\EventLoop\Factory::create();
$socketHandler      = new \EasyShop\WebSocket\Handler\Zada(new EasyShop\Utility\StringUtility(), \Doctrine\ORM\EntityManager::create($dbConfig, $config));
$webSocketServer    = new \EasyShop\WebSocket\WebSocketServer($eventLoop, $socketHandler);
$webSocketServer->listenToPusher($socketHandler->getHandlerMethod(), $socketHandler->getPushURL());
$webSocketServer->listenToClient(8080, '0.0.0.0');
$webSocketServer->start();
