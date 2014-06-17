<?php

/**
 * Web Socket Server control script
 */

/* show errors */
error_reporting(E_ALL);
ini_set('display_errors', 1);



function start_web_socket_server($rootDir, $isDev = true)
{
    // register easyshop autoloader
    require_once $rootDir . 'application/src/EasyShop/Core/ClassAutoloader/PSR0Autoloader.php';
    $psr0Autoloader = new PSR0Autoloader($rootDir . "application/src/");
    $psr0Autoloader->register();

    // register 3rd party autoloader
    require_once $rootDir . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    $isDevMode  = false;
    $dbConfig   = require $rootDir . 'application/config/param/database.php';
    $doctrineConfig     = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([$rootDir . 'application/src/EasyShop/Entities'], $isDevMode);
    
    $wsServerConfig     = require $rootDir . 'application/config/param/websocket.php';
    
    $eventLoop          = \React\EventLoop\Factory::create();
    $socketHandler      = new \EasyShop\WebSocket\Handler\Zada(new EasyShop\Utility\StringUtility(), \Doctrine\ORM\EntityManager::create($dbConfig, $doctrineConfig));
    $webSocketServer    = new \EasyShop\WebSocket\WebSocketServer($eventLoop, $socketHandler);
    $webSocketServer->listenToPusher($socketHandler->getHandlerMethod(), $wsServerConfig['pushUrl']);
    $webSocketServer->listenToClient($wsServerConfig['listenPort'], $wsServerConfig['listenIp']);
    $webSocketServer->start();
}


if (__DIR__ !== getcwd()) {
    if (!chdir(__DIR__)) {
        echo "ERROR: Script cannot be run from here\n";
        exit;
    }
}
start_web_socket_server(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' .DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
