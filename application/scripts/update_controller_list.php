<?php
/*
 |	This script is used to update config/param/controllers.php 
 |	which contains an array of all controllers existing.
 |	Array is used and checked when vendor slug is being changed.
 |	All controller names should be restricted for vendor slug names
 */

include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$messageManager = $CI->kernel->serviceContainer['message_manager'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class UpdateControllerList extends ScriptBaseClass
{
    private $controllerConfig;

    /**
     * Constructor
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     */
    public function __construct(
        $emailService,
        $configLoader,
        $viewParser
    ) {
        // parent::__construct($emailService, $configLoader, $viewParser);
    }

    public function execute()
    {
        $this->controllerConfig = [];
        echo "\nScript running! \n";

        // Path for controllers dir
        echo "Loading controllers directory... \n";
        $path = dirname(__FILE__) . '/../controllers/*';
        $myFiles = glob($path);

        // Path for controllers config file
        echo "Defining controllers.php config file and directory... \n";
        $configFile = dirname(__FILE__) . '/../config/param/controllers.php';

        // Execute extraction of controller filenames
        echo "Extracting files from controllers directory... \n";
        $this->getDirContents($myFiles);

        // Overwrite config file with new array
        echo "Creating controllers.php configuration file ... \n";
        file_put_contents($configFile, '<?php $controllerConfig = ' . var_export($this->controllerConfig, true) . '; return $controllerConfig;');

        echo "\n\nControllers.php created! Execution complete! \n";
    }

    private function getDirContents($files)
    {
        foreach ($files as $file) {
            if (is_dir($file)) {
                $newFiles = glob($file.'/*');
                $this->getDirContents($newFiles);
            }
            else {
                $path_parts = pathinfo($file);
                if ($path_parts['extension'] === "php") {
                    if (!in_array($path_parts['basename'], $this->controllerConfig)) {
                        $this->controllerConfig[] = $path_parts['filename'];
                    }
                }
            }
        }
    }
}

$updateControllerList  = new UpdateControllerList(
    $emailService,
    $configLoader,
    $viewParser
);

$updateControllerList->execute();
