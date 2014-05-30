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
        require_once APPPATH . '/src/EasyShop/Core/ClassAutoloader/PSR0Autoloader.php';
        $psr0Autoloader = new PSR0Autoloader(APPPATH . "/src/");
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
        
        //Doctrine ORM
        $paths = array('src/EasyShop/Entities');
        $isDevMode = (ENVIRONMENT === 'development');
        
        $dbConfig = require APPPATH . '/config/param/database.php';
        $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $container['entity_manager'] = function ($c) use ($dbConfig, $config){
            return Doctrine\ORM\EntityManager::create($dbConfig, $config);
        };

        /* Register services END */
        $this->serviceContainer = $container;
    }
}
