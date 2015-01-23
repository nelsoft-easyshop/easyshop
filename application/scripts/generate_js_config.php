<?php
include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$messageManager = $CI->kernel->serviceContainer['message_manager'];
$file =  __DIR__.'/../bin/js/config.js';
$configString = "    var fs = require('fs');

    exports.configureExpress = function(app) {
        app.set('PORT', " . $messageManager->getChatPort() . ");
        app.set('HOST', '" . $messageManager->getChatHost() . "');
        app.set('PROTOCOL', 'https');
        app.set('KEY', fs.readFileSync('key/easyshop.key'));
        app.set('CERT', fs.readFileSync('key/easyshop.crt'));
    };
    ";

file_put_contents($file, $configString);
echo "Config file is created in application/bin/js";
