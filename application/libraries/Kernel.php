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
        $config->addCustomStringFunction('BINARY', 'EasyShop\Doctrine\Query\MySql\Binary');
        $config->addCustomStringFunction('FIELD', 'EasyShop\Doctrine\Query\MySql\Field');
        
        $container['entity_manager'] = function ($c) use ($dbConfig, $config, $container){
            $em = Doctrine\ORM\EntityManager::create($dbConfig, $config);
            $em->getConnection()->getConfiguration()->setSQLLogger(null);
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsProductSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsMemberSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsAddressSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsProductReviewSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsMemberFeedbackSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsOrderSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsProductShippingCommentSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );
            $em->getEventManager()->addEventSubscriber(
                new \EasyShop\Doctrine\Subscribers\EsOrderProductSubscriber(
                    $container['activity_manager'],
                    $container['language_loader']
                )
            );

            $em->getEventManager()->addEventListener(
                [\Doctrine\ORM\Events::postLoad], new \EasyShop\Doctrine\Listeners\ProductImageExistenceListener(ENVIRONMENT)
            );
            
            return $em;
        };


        //Configuration Setter
        $container['local_configuration'] = function ($c) {
            return new \EasyShop\Core\Configuration\Configuration();
        };
        
        //CMS Service
        $container['xml_cms'] = function ($c) use ($container) {
            return new \EasyShop\XML\CMS($container['xml_resource'],
                                         $container['entity_manager'],
                                         $container['product_manager'],
                                         $container['user_manager'],
                                         $container['url_utility']);
        };
        
        //XML Resource Service
        $container['xml_resource'] = function ($c) use ($container) {
            return new \EasyShop\XML\Resource($container['local_configuration']);
        };
        
        
               
        //User Manager
        $container['user_manager'] = function ($c) use ($container) {
        
            $controllerConfigPath = APPPATH . 'config/param/controllers.php';
            $controllerList = array();
            if(file_exists($controllerConfigPath)){
                $controllerList = require($controllerConfigPath);
            }
            return new \EasyShop\User\UserManager($container['entity_manager']
                                                ,$container['config_loader']
                                                ,$container['form_validation']
                                                ,$container['form_factory']
                                                ,$container['form_error_helper']
                                                ,$container['string_utility']
                                                ,$controllerList);
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
            $httpRequest = $container['http_request'];
            $emailNotification  = $container['email_notification'];   
            $parser = new \CI_Parser();
            $encrypter = new \CI_Encrypt();
            $configLoader = $container['config_loader'];
            $languageLoader = $container['language_loader'];
            $hashUtitility = $container['hash_utility'];
            $socialMediaManager = $container['social_media_manager'];
            return new \EasyShop\Account\AccountManager($em, $brcyptEncoder, 
                                                        $userManager, 
                                                        $formFactory, 
                                                        $formValidation, 
                                                        $formErrorHelper,
                                                        $stringHelper,
                                                        $httpRequest,
                                                        $emailNotification,
                                                        $parser,$encrypter,
                                                        $configLoader,
                                                        $languageLoader,
                                                        $hashUtitility,
                                                        $socialMediaManager
                                                        );        
        };

        $container['message_manager'] = function ($c) use ($container) {
            $em = $container['entity_manager'];
            $configLoader = $container['config_loader'];
            $languageLoader = $container['language_loader'];
            $socialMediaManager = $container['social_media_manager'];
            $emailService = $container['email_notification'];
            $parser = new \CI_Parser();
            $redisClient = $container['redis_client'];
            $localConfiguration = $container['local_configuration'];
            return new \EasyShop\Message\MessageManager($em, 
                                                        $configLoader,
                                                        $languageLoader,
                                                        $socialMediaManager,
                                                        $emailService, 
                                                        $parser,
                                                        $redisClient,
                                                        $localConfiguration);
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
            $trustedProxies = require APPPATH . '/config/param/proxies.php';
            $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
            $request->setTrustedProxies($trustedProxies);
            return $request;
        };

        //Bug Reporter Service
        $container['bug_reporter'] = function ($c) use($container) {
            return new \EasyShop\BugReporter\BugReporter($container['entity_manager']);
        };
        
        // Point Tracker
        $container['point_tracker'] = function ($c) use($container) {
            return new \EasyShop\PointTracker\PointTracker($container['entity_manager']);
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
            $httpRequest = $container['http_request'];
            $promoManager = $container['promo_manager'];
            $configLoader = $container['config_loader'];
            $sphinxClient = $container['sphinx_client'];
            $userManager = $container['user_manager'];

            return new \EasyShop\Search\SearchProduct(
                                                        $em,
                                                        $collectionHelper,
                                                        $productManager,
                                                        $categoryManager,
                                                        $httpRequest,
                                                        $promoManager,
                                                        $configLoader,
                                                        $sphinxClient,
                                                        $userManager
                                                    );
        };

        //Promo Manager
        $container['promo_manager'] = function ($c) use ($container){
            return new \EasyShop\Promo\PromoManager($container['config_loader'], $container['entity_manager']);
        };

        // Product Manager
        $container['product_manager'] = function ($c) use ($container) {
            $em = $container['entity_manager'];
            $promoManager = $container['promo_manager'];
            $configLoader = $container['config_loader'];
            $collectionHelper = $container['collection_helper'];
            $userManager = $container['user_manager'];
            $imageLibrary = new \CI_Image_lib();
            $stringHelper = $container['string_utility'];            
            return new \EasyShop\Product\ProductManager($em, 
                                                        $promoManager, 
                                                        $collectionHelper, 
                                                        $configLoader,
                                                        $imageLibrary,
                                                        $userManager,
                                                        $stringHelper);
        };

        $container['transaction_manager'] = function ($c) use ($container) {
            $em = $container['entity_manager'];
            $userManager = $container['user_manager'];
            $productManager = $container['product_manager'];

            return new \EasyShop\Transaction\TransactionManager($em, $userManager, $productManager);
        };
        
        $container['image_utility'] = function ($c) use ($container){
            $imageLibrary = new \CI_Image_lib();            
            return new \EasyShop\Image\ImageUtility($imageLibrary);
        };  

        $container['webservice_manager'] = function ($c) use ($container){
            $em = $container['entity_manager'];   
            return new \EasyShop\Webservice\AuthenticateRequest($em);                     
        };                      

        // Collection Helper
        $container['collection_helper'] = function ($c) {
            return new \EasyShop\CollectionHelper\CollectionHelper();
        };
        
        $container['string_utility'] = function ($c) use ($container) {
            $htmlPurifier = new \HTMLPurifier();
            $configLoader = $container['config_loader'];
            return new \EasyShop\Utility\StringUtility(
                $htmlPurifier,
                $configLoader
            );
        };
        
        $container['hash_utility'] = function($c) use ($container) {
            $encrypt = new CI_Encrypt();
            return new \EasyShop\Utility\HashUtility($encrypt, $container['entity_manager']);
        };
        
        $container['url_utility'] = function ($c) {
            return new \EasyShop\Utility\UrlUtility();
        };
        
        $container['sort_utility'] = function ($c) use ($container){         
            return new \EasyShop\Utility\SortUtility();
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
            $userManager = $container['user_manager'];
            $configLoader = $container['config_loader'];
            $stringUtility = $container['string_utility'];
            $formValidation = $container['form_validation'];
            $formFactory = $container['form_factory'];
            return new \EasyShop\SocialMedia\SocialMediaManager(
                $fbRedirectLoginHelper,
                $googleClient,
                $em,
                $userManager,
                $configLoader,
                $stringUtility,
                $formValidation,
                $formFactory
            );
        };
        // Category Manager
        $container['category_manager'] = function ($c) use($container) {
            $em = $container['entity_manager'];
            $configLoader = $container['config_loader'];
            $productManager = $container['product_manager'];
            $promoManager = $container['promo_manager'];
            $sortUtility = $container['sort_utility'];
            $stringUtility = $container['string_utility'];
            $formFactory = $container['form_factory'];
            $formValidation = $container['form_validation'];
            $formErrorHelper = $container['form_error_helper'];
            return new \EasyShop\Category\CategoryManager(
                            $configLoader,
                            $em, 
                            $productManager, 
                            $promoManager, 
                            $sortUtility,
                            $stringUtility,
                            $formFactory,
                            $formValidation,
                            $formErrorHelper
                        );
        };
        
        $container['config_loader'] = function ($c) {
            $configImplementation = new \EasyShop\ConfigLoader\CodeigniterConfig();
            return new \EasyShop\ConfigLoader\ConfigLoader($configImplementation);
        };
         

        // Payment Service
        $container['payment_service'] = function ($c) use ($container) {
            return new \EasyShop\PaymentService\PaymentService(
                $container['entity_manager'],
                $container['http_request'],
                $container['point_tracker'],
                $container['promo_manager'],
                $container['product_manager'],
                $container['email_notification'],
                $container['mobile_notification'],
                new \CI_Parser(),
                $container['config_loader'],
                $container['xml_resource'],
                $container['social_media_manager'],
                $container['language_loader'],
                $container['message_manager'],
                $container['dragonpay_soap_client'],
                $container['product_shipping_location_manager']
            );
        };


        //Login Throttler Service
        $container['login_throttler'] = function ($c) use($container) {
            return new \EasyShop\LoginThrottler\LoginThrottler(
                $container['entity_manager'],
                $container['http_request']
                );
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

        // NUSoap Client
        $container['dragonpay_soap_client'] = function ($c) use ($container) {
            $url = '';
            if(!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production'){
                // LIVE
                $configLoad = $container['config_loader']->getItem('payment','production');  
            }
            else{
                // SANDBOX
                $configLoad = $container['config_loader']->getItem('payment','testing');  
            }

            $url = $configLoad['payment_type']['dragonpay']['Easyshop']['webservice_url'];

            return new \nusoap_client($url,true);
        };

        // QR Code Generator
        $container['qr_code_manager'] = function ($c) {
            $qrCode = new \PHPQRCode\QRcode();
            return  new \EasyShop\QrCode\QrCodeManager($qrCode);
        };

        // API formatter 
        $container['api_formatter'] = function ($c) use($container) {
            $em = $container['entity_manager']; 
            $collectionHelper = $container['collection_helper'];
            $productManager = $container['product_manager'];
            $cartManager = $container['cart_manager'];
            $reviewProductService = $container['review_product_service'];
            $stringUtility = $container['string_utility'];
            return new \EasyShop\Api\ApiFormatter($em,
                                                  $collectionHelper,
                                                  $productManager,
                                                  $cartManager,
                                                  $reviewProductService,
                                                  $stringUtility);
        }; 

        // Notification Services
        $emailConfig = require(APPPATH . "config/email_swiftmailer.php");
        $smsConfig = require(APPPATH . "config/sms.php");
        $container['email_notification'] = function($c) use ($container ,$emailConfig){
            return new \EasyShop\Notifications\EmailNotification(
                $container['entity_manager'],
                $emailConfig
            );
        };
        $container['mobile_notification'] = function($c) use ($smsConfig, $container){
            $em = $container['entity_manager']; 

            return new \EasyShop\Notifications\MobileNotification($em,$smsConfig);
        };

        $awsConfig = require_once(APPPATH . "config/param/aws.php");
        $container["aws_uploader"] = function($c) use ($awsConfig, $container){
            $awsClient =  \Aws\S3\S3Client::factory([ 
                'key' => $awsConfig['s3']['key'],
                'secret' => $awsConfig['s3']['secret']
            ]);
            return new \EasyShop\Upload\AwsUpload($awsClient, $container["config_loader"]);
        };

        $container['assets_uploader'] = function($c) use ($container){
            $uploadLibrary = new MY_Upload();
            $imageLibrary = new MY_Image_lib();
            return new \EasyShop\Upload\AssetsUploader( $container["entity_manager"], 
                                                        $container["aws_uploader"],
                                                        $container["config_loader"],
                                                        $uploadLibrary,
                                                        $imageLibrary,
                                                        ENVIRONMENT, 
                                                        $container['image_utility']);
        };
        
        $container["image_utility"] = function($c) use ($container){
            $imageLibrary = new CI_Image_lib();
            return new \EasyShop\Image\ImageUtility($imageLibrary);
        };
        
        // Review product
        $container['review_product_service'] = function ($c) use ($container) {
            return new \EasyShop\Review\ReviewProductService(
                            $container['entity_manager'],
                            $container['user_manager']
                            );
        };

        // Product Shipping Manager
        $container['product_shipping_location_manager'] = function ($c) use ($container) {
            return new \EasyShop\Product\ProductShippingLocationManager(
                $container['entity_manager']
            );
        };

        // Member Feature Restrict Manager
        $container['member_feature_restrict_manager'] = function ($c) use ($container) {
            return new \EasyShop\MemberFeatureRestrict\MemberFeatureRestrictManager(
                $container['entity_manager']
            );
        };

        $container['language_loader'] = function ($c) {
            $languageImplementation = new \EasyShop\LanguageLoader\CodeigniterLanguage();
            return new \EasyShop\LanguageLoader\LanguageLoader($languageImplementation);
        };

        $container['activity_manager'] = function ($c) use ($container) { 
            return new \EasyShop\Activity\ActivityManager(
                            $container['language_loader']
                        );
        };

        // Checkout Service
        $container['checkout_service'] = function ($c) use ($container) {
            return new \EasyShop\Checkout\CheckoutService(
                            $container['entity_manager'],
                            $container['product_manager'],
                            $container['promo_manager'],
                            $container['cart_manager'],
                            $container['payment_service'],
                            $container['product_shipping_location_manager']
                        );
        };
        
        $container['sphinx_client'] = function ($c) use ($container) {
            $sphinxClient = new \SphinxClient();
            $sphinxClient->SetMaxQueryTime(5000);
            return $sphinxClient;
        };

        // Product Upload Manager
        $container['product_upload_manager'] = function ($c) use ($container) {
            return new \EasyShop\Product\ProductUploadManager(
                            $container['entity_manager'],
                            $container['product_manager'],
                            $container['string_utility'],
                            $container['language_loader']
                        );
        };

        $container['json_web_token'] = function ($c) {
            return new \JWT();
        }; 

        $container['mcrypt'] = function ($c) {
            return new \MCrypt\MCrypt();
        }; 
        
        $nodejsConfig = require_once(APPPATH . "config/param/nodejs.php");
        $container['redis_client'] = function ($c) use ($nodejsConfig) {
            return new \Predis\Client([
                'scheme' => 'tcp',
                'host' =>  $nodejsConfig['HOST'],
                'port' => $nodejsConfig['REDIS_PORT'],
            ]);
        }; 
       
        $container['captcha_builder'] = function ($c) {
            return new \Gregwar\Captcha\CaptchaBuilder();
        }; 

        /* Register services END */
        $this->serviceContainer = $container;
    }

}
