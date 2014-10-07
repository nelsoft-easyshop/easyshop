<?php

namespace EasyShop\ConfigLoader;

use EasyShop\ConfigLoader\ConfigInterface as ConfigInterface;

class ConfigLoader
{

    /**
     * The configLoader configImplementation
     *
     * @var EasyShop\ConfigLoader\ConfigInterface 
     */
    private $configLoaderImplementation;

    /**
     * Constructor. 
     * 
     * @param EasyShop\ConfigLoader\ConfigInterface  $configImplementation
     */
    public function __construct(ConfigInterface $configImplementation)
    {
        $this->configLoaderImplementation = $configImplementation;
    }
    
    /**
     * Returns a config item
     *
     */
    public function getItem($file, $item = null)
    {
        return $this->configLoaderImplementation->getConfigItem($file, $item);
    }
    
}
