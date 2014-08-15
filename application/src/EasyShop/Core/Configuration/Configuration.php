<?php

namespace EasyShop\Core\Configuration;

class Configuration
{

    /**
     * Configuration file path
     *
     * @var string
     */
    private $configFilePath;


    
    /**
     * Constructor. Sets the configurationFilePath
     * 
     * @param string $configurationPath
     */
    public function __construct($configurationPath = null)
    {
        $configurationPath = $configurationPath ? $configurationPath : dirname(__FILE__).'/../../../../../config.php';
        $this->configFilePath = $configurationPath;        
    }
    
    /**
     * Returns the local configuration path
     *
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configFilePath;
    }
    
    /**
     * Check for the existence of the configuration file
     *
     * @return boolean
     */
    public function isConfigFileExists()
    {   
        return file_exists ($this->configFilePath);
    }

    
    /**
     * Returns the configuration parameter value
     *
     * @param string $configName
     * @return
     */
    public function getConfigValue($configName = null)
    {
        $localConfig = require $this->configFilePath;
        if($configName){
            return isset($localConfig[$configName]) ? $localConfig[$configName] : '';
        }
        else{
            return $localConfig;
        }
    }
    

}
