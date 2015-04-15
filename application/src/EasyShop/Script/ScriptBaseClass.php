<?php

namespace EasyShop\Script;

class ScriptBaseClass
{
    /**
     * Email notification instance
     */
    private $emailService;

    /**
     * Config loader instance
     */
    private $configLoader;

    private $scriptConfig;

    /**
     * Constructor
     * @param $emailService
     */
    public function __construct($emailService, $configLoader)
    {
        $this->emailService = $emailService;
        $this->configLoader = $configLoader;
        $this->scriptConfig = $this->configLoader->getItem('script', 'emailParameter');
        $this->registerErrorHandler();
    }

    /**
     * Register error handlers
     */
    private function registerErrorHandler()
    {
        error_reporting(E_ALL);
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorShutdownHandler']);
    }

    /**
     * Error Handler
     * @param  string $code
     * @param  string $message
     * @param  string $file
     * @param  string $line
     */
    public function errorHandler($code, $message, $file, $line)
    {
        $emailBody = $this->constructMessage($code, $message, $file, $line);

        $this->emailService->setRecipient($this->scriptConfig['recipient'])
                           ->setSubject($this->scriptConfig['subject'])
                           ->setMessage($emailBody)
                           ->sendMail();
    }

    /**
     * Shutdown hanlder
     */
    public function fatalErrorShutdownHandler()
    {
        $lastError = error_get_last();
        if ($lastError['type'] === E_ERROR) {
            $this->errorHandler(E_ERROR, $lastError['message'], $lastError['file'], $lastError['line']);
        }
    }

    /**
     * Message Constructor for email notification
     * @param  string $code
     * @param  string $message
     * @param  string $file
     * @param  string $line
     */
    private function constructMessage($code, $message, $file, $line)
    {
        $message = "
            <table>
                <tr>
                    <td>
                        Error Code
                    </td>
                    <td>
                        $code
                    </td>
                </tr>
                <tr>
                    <td>
                        Error Message
                    </td>
                    <td>
                        $message
                    </td>
                </tr>
                <tr>
                    <td>
                        Line Number
                    </td>
                    <td>
                        $line
                    </td>
                </tr>
                <tr>
                    <td>
                        File Name
                    </td>
                    <td>
                        $file
                    </td>
                </tr>
            </table>
        ";

        return $message;
    }
}
