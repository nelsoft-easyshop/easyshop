<?php
include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$messageManager = $CI->kernel->serviceContainer['message_manager'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class GenerateJsConfig extends ScriptBaseClass
{
    private $emailService;
    private $configLoader;
    private $viewParser;
    private $messageManager;

    /**
     * Constructor
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     * @param EasyShop\Message\MessageManager          $messageManager
     */
    public function __construct(
        $emailService,
        $configLoader,
        $viewParser,
        $messageManager
    )
    {
        parent::__construct($emailService, $configLoader, $viewParser);
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $file =  __DIR__.'/../bin/js/config.js';
        $configString = "
            var fs = require('fs');
            exports.configureExpress = function(app) {
                app.set('NODE_PORT', " . $this->messageManager->getChatPort() . ");
                app.set('NODE_HOST', '" . $this->messageManager->getChatHost() . "');
                app.set('PROTOCOL', 'https');
                app.set('KEY', fs.readFileSync('" . __DIR__ . "/../bin/js/key/easyshop.key'));
                app.set('CERT', fs.readFileSync('" . __DIR__ . "/../bin/js/key/easyshop.crt'));
                app.set('JWT_SECRET', '".$this->messageManager->getWebTokenSecret()."');
                app.set('REDIS_PORT', ".$this->messageManager->getRedisPort().");
                app.set('REDIS_HOST', '".$this->messageManager->getRedisHost()."');
                app.set('REDIS_CHANNEL_NAME', '".$this->messageManager->getRedisChannelName()."');
            };
        ";

        file_put_contents($file, $configString);
        echo "Config file is created in application/bin/js";
    }
}

$generateJsConfig  = new GenerateJsConfig(
    $emailService,
    $configLoader,
    $viewParser,
    $messageManager
);

$generateJsConfig->execute();
