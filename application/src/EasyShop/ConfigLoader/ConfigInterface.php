<?php

namespace EasyShop\ConfigLoader;

interface ConfigInterface
{
    /**
     * Returns the config itemss
     * @param string $configFile
     * @param string $configItem
     *
     */
    public function getConfigItem($configFile, $configItem = null);
}

