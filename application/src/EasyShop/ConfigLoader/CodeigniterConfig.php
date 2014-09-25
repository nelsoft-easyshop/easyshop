<?php

namespace EasyShop\ConfigLoader;

/**
 * Codeigniter implementation of the config loader
 *
 */
class CodeigniterConfig implements ConfigInterface
{

    /**
     * The CI Instance
     *
     */
    private $CI;

    /**
     * The constructor
     *
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Returns the config itemss
     * @param string $configFile
     * @param string $configItem
     * 
     */ 
    public function getConfigItem($configFile, $configItem = null)
    {
        $this->CI->config->load($configFile, true);
        $configItem = ($configItem === null) ? $configFile : $configItem;
        $result = $this->CI->config->item($configItem);
        $result = $result ? $result : $this->CI->config->item($configFile)[$configItem];
 
        return $result;
    }
}

