<?php
    include_once  __DIR__.'/bootstrap.php';
    $CI =& get_instance();
    $file = __DIR__ . "/../bin/js/config.js";
    $jsConfig = require APPPATH . '/config/param/js_config.php';
    require_once __DIR__ . '/../src/EasyShop/Core/Configuration/Configuration.php';
    $configService = new EasyShop\Core\Configuration\Configuration();
    $url = $jsConfig['HOST'];

    if($configService->isConfigFileExists()){
        $configBaseUrl = $configService->getConfigValue('base_url');
        if(strlen($configBaseUrl) > 0){
            $url = $configBaseUrl;
        }
    }

    if (strpos($url, 'https://') !== false) {
        $url = str_replace('https:', '', preg_replace('{/}', '', $url));
    }

    $configString = "    var fs = require('fs');

    exports.configureExpress = function(app) {
        app.set('PORT', " . $jsConfig['PORT'] . ");
        app.set('HOST', '" . $url . "');
        app.set('PROTOCOL', 'https');
        app.set('KEY', fs.readFileSync('key/easyshop.key'));
        app.set('CERT', fs.readFileSync('key/easyshop.crt'));
    };
    ";

    file_put_contents($file, $configString);
