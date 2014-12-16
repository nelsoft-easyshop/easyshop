<?php

namespace EasyShop\LanguageLoader;

use EasyShop\LanguageLoader\LanguageInterface as LanguageInterface;

class LanguageLoader
{

    /**
     * The languageLoader languageLoaderImplementation
     *
     * @var EasyShop\LanguageLoader\LanguageInterface
     */
    private $languageLoaderImplementation;

    /**
     * Constructor. 
     * 
     * @param EasyShop\LanguageLoader\LanguageInterface  $languageLoaderImplementation
     */
    public function __construct(LanguageInterface $languageLoaderImplementation)
    {
        $this->languageLoaderImplementation = $languageLoaderImplementation;
    }
    
    /**
     * Returns a language line
     *
     */
    public function getLine($line)
    {
        return $this->languageLoaderImplementation->getLanguageLine($line);
    }
}

