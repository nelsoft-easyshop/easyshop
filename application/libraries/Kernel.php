<?php



/**
 * Workaround for managing non-CI packages
 *
 * @author czarpino
 */
class Kernel
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_bootstrap();
    }
    
    /**
     * Perform boostrap operations.
     */
    private function _bootstrap()
    {
        /* We register the aplication class autoloader */
        require_once 'application/src/EasyShop/Core/ClassAutoloader/PSR0Autoloader.php';
        $psr0Autoloader = new PSR0Autoloader("application/src/");
        $psr0Autoloader->register();
        
        /* We register 3rd party autoloader */
        require_once 'vendor/autoload.php';
    }
}
