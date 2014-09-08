<?php



/**
 * Workaround for managing non-CI packages
 *
 * @author czarpino
 */
class Kernel
{
    /**
     * Service container
     * 
     * @var Pimple\Container
     */
    public $serviceContainer;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_bootstrap();
        $this->_configure();
    }
    
    /**
     * Perform boostrap operations.
     */
    private function _bootstrap()
    {
        /* We register the application class autoloader */
        require_once APPPATH . 'src/EasyShop/Core/ClassAutoloader/PSR0Autoloader.php';
        $psr0Autoloader = new PSR0Autoloader(APPPATH . "src/");
        $psr0Autoloader->register();

        /* We register 3rd party autoloader */
        require_once '../vendor/autoload.php';
    }
    
    /**
     * Configure Kernel
     */
    private function _configure()
    {
        $container = new Pimple\Container();
        
        /* Register services BEGIN */
        
        // JavascriptIncluder
        $container['javacsript_includer'] = function ($c) {
            return new \EasyShop\View\Helpers\JavascriptIncluder(
                    ENVIRONMENT === 'production', 'assets/js/src', 'assets/js/min');
        };
        
        // Doctrine ORM
        $paths = array('src/EasyShop/Entities');
        $isDevMode = (ENVIRONMENT === 'development') && false;    // set to false to avoid concurrency problems in staging

        $dbConfig = require APPPATH . '/config/param/database.php';

        $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
        $config->setProxyDir(APPPATH . '/src/EasyShop/Doctrine/Proxies');
        $config->setProxyNamespace('EasyShop\Doctrine\Proxies');
        
        $container['entity_manager'] = function ($c) use ($dbConfig, $config){
            return Doctrine\ORM\EntityManager::create($dbConfig, $config);
        };

        // ZeroMQ pusher
        $container['user_pusher'] = function ($c) {
            $wsConfig = require APPPATH . '/config/param/websocket.php';
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect($wsConfig['pushUrl']);
            
            // keeps from blocking when unable to send
            $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 50);
            
            return new EasyShop\WebSocket\Pusher\UserPusher($socket, $c['entity_manager']);
        };
        
        //Configuration Setter
        $container['local_configuration'] = function ($c) {
            return new \EasyShop\Core\Configuration\Configuration();
        };
        
        //CMS Service
        $container['xml_cms'] = function ($c) {
            return new \EasyShop\XML\CMS();
        };
        
        //XML Resource Service
        $container['xml_resource'] = function ($c) use ($container) {
            return new \EasyShop\XML\Resource($container['local_configuration']);
        };
        
        //Authentication Manager
        $container['account_manager'] = function ($c) use ($container) {
            $brcyptEncoder = new \Elnur\BlowfishPasswordEncoderBundle\Security\Encoder\BlowfishPasswordEncoder(5);
            $em = $container['entity_manager'];
            $userManager = $container['user_manager'];
            return new \EasyShop\Account\AccountManager(new BlowfishPasswordEncoder($brcyptEncoder, $em, $userManager);        
        }

        // Point Tracker
        $container['point_tracker'] = function ($c) {
            return new \EasyShop\PointTracker\PointTracker();
        };

        // Payment Service
        $container['payment_service'] = function ($c) {
            return new \EasyShop\PaymentService\PaymentService();
        };

        // Http foundation
        $container['http_foundation'] = function ($c) {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        };

          // Product Manager
        $container['product_manager'] = function ($c) {
            return new \EasyShop\Product\ProductManager();
        };

        // Collection Helper
        $container['collection_helper'] = function ($c) {
            return new \EasyShop\CollectionHelper\CollectionHelper();
        };


        /* Register services END */
        $this->serviceContainer = $container;
    }

}
