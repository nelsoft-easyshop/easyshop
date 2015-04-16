<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$emailService = $CI->kernel->serviceContainer['email_notification'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class ClearImageTempFolder extends ScriptBaseClass
{
    private $emailService;
    private $configLoader;
    private $viewParser;

    const HOURS_LAPSED = 3;

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
    )
    {
        parent::__construct($emailService, $configLoader, $viewParser);
    }

    public function execute()
    {
        $path = dirname(__FILE__).'/../../web/assets/temp_product/*';
        $files = glob($path);

        foreach ($files as $file) {
            $datecreated = date("Y-m-d H:i:s.", filectime($file));
            $now = date("Y-m-d H:i:s");
            if ((int)round((strtotime($now)) - strtotime($datecreated)) / 3600 >= self::HOURS_LAPSED) {
                if (is_dir($file)) {
                    $this->deleteDir($file);
                }
                else {
                    unlink($file);
                }
            }
        }
        echo "\nDone. The directory '.$path.' has been emptied. \n\n";
    }

    /**
     * Deletes a directory and all its content. Recursive implementation.
     * @param  string $dirPath
     */
    private function deleteDir($dirPath)
    {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}

$clearTempFolder  = new ClearImageTempFolder(
    $emailService,
    $configLoader,
    $viewParser
);

$clearTempFolder->execute();
