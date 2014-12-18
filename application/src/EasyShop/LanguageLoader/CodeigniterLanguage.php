<?php

namespace EasyShop\LanguageLoader;

/**
 * Codeigniter implementation of the config loader
 *
 */
class CodeigniterLanguage implements LanguageInterface
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
    public function getLanguageLine($languageLine)
    {
        return $this->CI->lang->line($languageLine);
    }
}

