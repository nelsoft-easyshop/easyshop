<?php



/**
 * Workaround for bootstrapping non-CI packages
 *
 * @author czarpino
 */
class Bootstrap
{
    public function __construct()
    {
        /* 
         * We register custom class autoloader to autoload classes in our src directory
         */
        require_once 'application/src/EasyShop/Core/ClassAutoloader/PSR0Autoloader.php';
        $psr0Autoloader = new PSR0Autoloader("application/src/");
        $psr0Autoloader->register();
    }
}
