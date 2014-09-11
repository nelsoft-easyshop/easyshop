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
        
        //User Manager
        $container['user_manager'] = function ($c) use ($container) {
            return new \EasyShop\User\UserManager($container['entity_manager']);
        };

        // Paths
        $vendorDir = __DIR__ . '/../../vendor';
        $viewsDir = __DIR__ . '/../views';
        $vendorFormDir = $vendorDir . '/symfony/form/Symfony/Component/Form';
        $vendorValidatorDir = $vendorDir . '/symfony/validator/Symfony/Component/Validator';
        $vendorTwigBridgeDir = $vendorDir . '/symfony/twig-bridge/Symfony/Bridge/Twig';

        // CSRF Setup
        $csrfSecret = 'TempOraRy_KeY_12272013_bY_Sam*?!';
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $csrfProvider = new \Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider($session, $csrfSecret);

        // Twig setup
        $translator = new \Symfony\Component\Translation\Translator('en');
        $translator->addLoader('xlf', new \Symfony\Component\Translation\Loader\XliffFileLoader());
        $translator->addResource('xlf', $vendorFormDir . '/Resources/translations/validators.en.xlf', 'en', 'validators');
        $translator->addResource('xlf', $vendorValidatorDir . '/Resources/translations/validators.en.xlf', 'en', 'validators');

        //Twig Service
        $container['twig'] = function ($c) use ($translator, $viewsDir, $vendorTwigBridgeDir, $csrfProvider) {
            // Create twig
            $twig = new Twig_Environment(new Twig_Loader_Filesystem(array(
                $viewsDir,
                $vendorTwigBridgeDir . '/Resources/views/Form',
            )));

            $formEngine = new Symfony\Bridge\Twig\Form\TwigRendererEngine(array('form_div_layout.html.twig'));
            $formEngine->setEnvironment($twig);
            $twig->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));
            $twig->addExtension(new \Symfony\Bridge\Twig\Extension\FormExtension(new \Symfony\Bridge\Twig\Form\TwigRenderer($formEngine, $csrfProvider)));
            return $twig;
        };

        // Validator Setup
        $validator = \Symfony\Component\Validator\Validation::createValidator();

        //Form Factory Service
        $container['form_factory'] = function ($c) use ($csrfProvider, $validator) {
            // Create factory
            $formFactory = \Symfony\Component\Form\Forms::createFormFactoryBuilder()
                ->addExtension(new \Symfony\Component\Form\Extension\Csrf\CsrfExtension($csrfProvider))
                ->addExtension(new \Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension())
                ->addExtension(new \Symfony\Component\Form\Extension\Validator\ValidatorExtension($validator))
                ->getFormFactory();

            return $formFactory;
        };

        //Validation Rules Service
        $container['form_validation'] = function ($c) {
            return new \EasyShop\FormValidation\ValidationRules();
        };

        //Request Service
        $container['http_request'] = function ($c) {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        };

        //Bug Reporter Service
        $container['bug_reporter'] = function ($c) use($container) {
            return new \EasyShop\BugReporter\BugReporter($container['entity_manager']);
        };
        
        // Point Tracker
        $container['point_tracker'] = function ($c) {
            return new \EasyShop\PointTracker\PointTracker();
        };

        // Payment Service
        $container['payment_service'] = function ($c) {
            return new \EasyShop\PaymentService\PaymentService();
        };

        // Search product
        $container['search_product'] = function ($c) use($container) {
            $em = $container['entity_manager'];
            $collectionHelper = $container['collection_helper'];
            return new \EasyShop\Search\SearchProduct($em,$collectionHelper);
        };

        // Promo
        $container['promo_manager'] = function ($c) {
            return new \EasyShop\Promo\PromoManager();
        };

        // Product Manager
        $container['product_manager'] = function ($c) use($container) {
            $em = $container['entity_manager'];
            $promoManager = $container['promo_manager'];
            $collectionHelper = $container['collection_helper'];

            return new \EasyShop\Product\ProductManager($em,$promoManager,$collectionHelper);
        };

        // Collection Helper
        $container['collection_helper'] = function ($c) {
            return new \EasyShop\CollectionHelper\CollectionHelper();
        };
        
        // Http foundation
        $container['http_foundation'] = function ($c) {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        };

        // Category Manager
        $container['category_manager'] = function ($c) {
            return new \EasyShop\Category\CategoryManager();
        };

        // String Utility
        $container['string_utility'] = function($c) {
            return new EasyShop\Utility\StringUtility();
        };

        /* Register services END */
        $this->serviceContainer = $container;
    }

}
