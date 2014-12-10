<?php

namespace EasyShop\LanguageLoader;

interface LanguageInterface
{
    /**
     * Returns the config itemss
     * @param string $configFile
     * @param string $configItem
     *
     */
    public function getLanguageLine($languageLine);
}

