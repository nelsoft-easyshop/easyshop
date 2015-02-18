<?php
include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$messageManager = $CI->kernel->serviceContainer['message_manager'];
$file =  __DIR__.'/../bin/js/config.js';
$configString = "    var fs = require('fs');

    exports.configureExpress = function(app) {
        app.set('NODE_PORT', " . $messageManager->getChatPort() . ");
        app.set('HOST', '" . $messageManager->getChatHost() . "');
        app.set('PROTOCOL', 'https');
        app.set('KEY', fs.readFileSync('" . __DIR__ . "/../bin/js/key/easyshop.key'));
        app.set('CERT', fs.readFileSync('" . __DIR__ . "/../bin/js/key/easyshop.crt'));
        app.set('JWT_SECRET', '".$messageManager->getWebTokenSecret()."');
        app.set('REDIS_PORT', ".$messageManager->getRedisPort().");
        app.set('REDIS_CHANNEL_NAME', '".$messageManager->getRedisChannelName()."');
    };
    ";

file_put_contents($file, $configString);
echo "Config file is created in application/bin/js";
