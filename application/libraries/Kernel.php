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
            return new \EasyShop\User\UserManager($container['entity_manager']
                                                ,$container['config_loader']
                                                ,$container['form_validation']
                                                ,$container['form_factory']
                                                ,$container['form_error_helper']);
        };
        
        //Account Manager
        $container['account_manager'] = function ($c) use ($container) {
            $brcyptEncoder = new \Elnur\BlowfishPasswordEncoderBundle\Security\Encoder\BlowfishPasswordEncoder(5);
            $em = $container['entity_manager'];
            $userManager = $container['user_manager'];
            $formFactory = $container['form_factory'];
            $formValidation = $container['form_validation'];
            $formErrorHelper = $container['form_error_helper'];
            $stringHelper = $container['string_utility'];
            return new \EasyShop\Account\AccountManager($em, $brcyptEncoder, 
                                                        $userManager, 
                                                        $formFactory, 
                                                        $formValidation, 
                                                        $formErrorHelper,
                                                        $stringHelper);        
        };

        $container['message_manager'] = function ($c) use ($container) {
            $em = $container['entity_manager'];
            return new \EasyShop\Message\MessageManager($em);
        };

        //Authentication Manager
        $container['account_manager'] = function ($c) use ($container) {
            $brcyptEncoder = new \Elnur\BlowfishPasswordEncoderBundle\Security\Encoder\BlowfishPasswordEncoder(5);
            $em = $container['entity_manager'];
            $userManager = $container['user_manager'];
            $formFactory = $container['form_factory'];
            $formValidation = $container['form_validation'];
            $formErrorHelper = $container['form_error_helper'];
            $stringHelper = $container['string_utility'];
            return new \EasyShop\Account\AccountManager($em, $brcyptEncoder, 
                                                        $userManager, 
                                                        $formFactory, 
                                                        $formValidation, 
                                                        $formErrorHelper,
                                                        $stringHelper);        
        };


        // Paths
        $vendorDir = __DIR__ . '/../../vendor';
        $viewsDir = __DIR__ . '/../views';
        $vendorFormDir = $vendorDir . '/symfony/form/Symfony/Component/Form';
        $vendorValidatorDir = $vendorDir . '/symfony/validator/Symfony/Component/Validator';
        $vendorTwigBridgeDir = $vendorDir . '/symfony/twig-bridge/Symfony/Bridge/Twig';

        // CSRF Setup
        $container['csrf_provider'] = function ($c){
            $csrfSecret = 'TempOraRy_KeY_12272013_bY_Sam*?!';
            $session = new \Symfony\Component\HttpFoundation\Session\Session();
            $csrfProvider = new \Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider($session, $csrfSecret);
            return $csrfProvider;
        };
        
        
        // Twig setup
        $translator = new \Symfony\Component\Translation\Translator('en');
        $translator->addLoader('xlf', new \Symfony\Component\Translation\Loader\XliffFileLoader());
        $translator->addResource('xlf', $vendorFormDir . '/Resources/translations/validators.en.xlf', 'en', 'validators');
        $translator->addResource('xlf', $vendorValidatorDir . '/Resources/translations/validators.en.xlf', 'en', 'validators');

        //Twig Service
        $container['twig'] = function ($c) use ($translator, $viewsDir, $vendorTwigBridgeDir, $container) {
            // Create twig
            $twig = new Twig_Environment(new Twig_Loader_Filesystem(array(
                $viewsDir,
                $vendorTwigBridgeDir . '/Resources/views/Form',
            )));

            $formEngine = new Symfony\Bridge\Twig\Form\TwigRendererEngine(array('form_div_layout.html.twig'));
            $formEngine->setEnvironment($twig);
            $twig->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));
            $twig->addExtension(new \Symfony\Bridge\Twig\Extension\FormExtension(new \Symfony\Bridge\Twig\Form\TwigRenderer($formEngine, $container['csrf_provider'])));
            return $twig;
        };

        // Validator Setup
        $validator = \Symfony\Component\Validator\Validation::createValidator();

        //Form Factory Service
        $container['form_factory'] = function ($c) use ($container, $validator) {
            // Create factory
            $formFactory = \Symfony\Component\Form\Forms::createFormFactoryBuilder()
                ->addExtension(new \Symfony\Component\Form\Extension\Csrf\CsrfExtension($container['csrf_provider']))
                ->addExtension(new \Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension())
                ->addExtension(new \Symfony\Component\Form\Extension\Validator\ValidatorExtension($validator))
                ->getFormFactory();

            return $formFactory;
        };

        //Validation Rules Service
        $container['form_validation'] = function ($c) use ($container){
            return new \EasyShop\FormValidation\ValidationRules($container['entity_manager']);
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
        $container['point_tracker'] = function ($c) use($container) {
            return new \EasyShop\PointTracker\PointTracker($container['entity_manager']);
        };

        // Http foundation
        $container['request'] = function ($c) use($container) {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        };
        
        //Cart Manager
        $container['cart_manager'] = function ($c) use ($container) {
            $productManager = $container['product_manager'];
            $promoManager = $container['promo_manager'];
            $cart = new \EasyShop\Cart\CodeigniterCart($container['entity_manager']);
            return new \EasyShop\Cart\CartManager($container['entity_manager'], $cart, $productManager, $promoManager);
        };

        // Search product
        $container['search_product'] = function ($c) use($container) {
            $em = $container['entity_manager'];
            $collectionHelper = $container['collection_helper'];
            $productManager = $container['product_manager'];
            $categoryManager = $container['category_manager'];

            return new \EasyShop\Search\SearchProduct(
                                                        $em
                                                        ,$collectionHelper
                                                        ,$productManager
                                                        ,$categoryManager
                                                    );
        };

        //Promo Manager
        $container['promo_manager'] = function ($c) use ($container){
            return new \EasyShop\Promo\PromoManager($container['config_loader']);
        };

        // Product Manager
        $container['product_manager'] = function ($c) use ($container) {
            $em = $container['entity_manager'];
            $promoManager = $container['promo_manager'];
            $configLoader = $container['config_loader'];
            $collectionHelper = $container['collection_helper'];
            return new \EasyShop\Product\ProductManager($em, 
                                                        $promoManager, 
                                                        $collectionHelper, 
                                                        $configLoader);
        };


        // Collection Helper
        $container['collection_helper'] = function ($c) {
            return new \EasyShop\CollectionHelper\CollectionHelper();
        };
        $container['string_utility'] = function ($c) {
            return new \EasyShop\Utility\StringUtility();
         };
        $socialMediaConfig = require APPPATH . 'config/oauth.php';
        $container['social_media_manager'] = function ($c) use($socialMediaConfig, $container) {
            $fbRedirectLoginHelper = new \Facebook\FacebookRedirectLoginHelper(
                $socialMediaConfig['facebook']['redirect_url'],
                $socialMediaConfig['facebook']['key']['appId'],
                $socialMediaConfig['facebook']['key']['secret']
            );
            $googleClient = new Google_Client();
            $googleClient->setAccessType('online');
            $googleClient->setApplicationName('Easyshop');
            $googleClient->setClientId($socialMediaConfig['google']['key']['appId']);
            $googleClient->setClientSecret($socialMediaConfig['google']['key']['secret']);
            $googleClient->setRedirectUri($socialMediaConfig['google']['redirect_url']);
            $googleClient->setDeveloperKey($socialMediaConfig['google']['key']['apiKey']);
            $em = $container['entity_manager'];
            $stringUtility = $container['string_utility'];
            return new \EasyShop\SocialMedia\SocialMediaManager(
                $socialMediaConfig['facebook']['key']['appId'],
                $socialMediaConfig['facebook']['key']['secret'],
                $fbRedirectLoginHelper,
                $googleClient,
                $em,
                $stringUtility
            );
        };
        // Category Manager
        $container['category_manager'] = function ($c) use($container) {
            $em = $container['entity_manager'];
            $configLoader = $container['config_loader'];

            return new \EasyShop\Category\CategoryManager($configLoader,$em);
        };
        
        $container['config_loader'] = function ($c) {
            $configImplementation = new \EasyShop\ConfigLoader\CodeigniterConfig();
            return new \EasyShop\ConfigLoader\ConfigLoader($configImplementation);
        };
         

        // Payment Service
        $container['payment_service'] = function ($c) use ($container) {
            return new \EasyShop\PaymentService\PaymentService(
                            $container['entity_manager'],
                            $container['request'],
                            $container['point_tracker'],
                            $container['promo_manager'],
                            $container['product_manager']
                            );
        };


        //Login Throttler Service
        $container['login_throttler'] = function ($c) use($container) {
            return new \EasyShop\LoginThrottler\LoginThrottler(
                $container['entity_manager'],
                $container['http_request']
                );
        };
        
        $container['string_utility'] = function ($c) {
            return new \EasyShop\Utility\StringUtility();
        };
        
        // Form Helper
        $container['form_error_helper'] = function ($c) {
            return new \EasyShop\FormValidation\FormHelpers\FormErrorHelper();
        };

        $container['oauth2_server'] = function ($c) use ($dbConfig, $container) {
            $dsn = 'mysql:dbname='.$dbConfig['dbname'].';host='.$dbConfig['host'].';';
            $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $dbConfig['user'], 'password' => $dbConfig['password']), ['user_table' => 'es_member']);
            
            $userCredentialStorage = new EasyShop\OAuth\Storage\UserCredentials($container['account_manager']);
            $server = new OAuth2\Server($storage);
            $server->addGrantType(new OAuth2\GrantType\UserCredentials($userCredentialStorage));
            $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));
            return $server;
        };

        /* Register services END */
        $this->serviceContainer = $container;
    }

}
