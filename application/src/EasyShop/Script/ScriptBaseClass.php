<?php

namespace EasyShop\Script;

abstract class ScriptBaseClass
{
    /**
     * Email notification instance
     */
    private $emailService;

    /**
     * Config loader instance
     */
    private $configLoader;

    /**
     * Parser Library
     */
    private $parserLibrary;

    private $scriptConfig;

    /**
     * Constructor
     * @param $emailService
     */
    protected function __construct($emailService, $configLoader, $parserLibrary)
    {
        $this->emailService = $emailService;
        $this->configLoader = $configLoader;
        $this->parserLibrary = $parserLibrary;
        $this->scriptConfig = $this->configLoader->getItem('script', 'emailParameter');
        self::registerErrorHandler();
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
    public function errorHandler($code, $message, $fileName, $lineNumber)
    {
        $emailBody = $this->constructMessage($code, $message, $fileName, $lineNumber);

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
    private function constructMessage($code, $message, $fileName, $lineNumber)
    {
        $errorData = [
            'code' => $code,
            'message' => $message,
            'fileName' => $fileName,
            'lineNumber' => $lineNumber,
        ];

        return $this->parserLibrary->parse('errors/script-error', $errorData, true);
    }

    /**
     * Execute the script
     */
    abstract public function execute();
}
