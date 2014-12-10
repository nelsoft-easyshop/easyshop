<?php

namespace EasyShop\Activity;

class ActivityManager
{
    /**
     * Language Loader Class
     *
     * @var EasyShop\EasyShop\LanguageLoader
     */
    private $languageLoader;
    
    /**
     * Constructor.
     * @param Doctrine\ORM\EntityManager $em
     * @param EasyShop\EasyShop\LanguageLoader $languageLoader
     */
    public function __construct($languageLoader)
    {
        $this->languageLoader = $languageLoader;
    }

    /**
     * Construct phrase for activity log
     * @param  array  $modifiedArray
     * @param  string $languangeLine
     * @param  string $entityLine
     * @return string
     */
    public function constructActivityPhrase($modifiedArray, $languangeLine, $entityLine)
    {   
        $returnString = "";
        $buildString = "";
        $buildStringArray = [];
        $unParsePhrase = $this->languageLoader->getLine($languangeLine); 
        $entityLanguange = $this->languageLoader->getLine($entityLine);
        foreach ($modifiedArray as $key => $value) {
            $fieldLanguage = $entityLanguange[$key];
            $buildStringArray[] = ucfirst(strtolower($fieldLanguage))." : ".$value;
        }
        $returnString = str_replace(":phrase", implode(', ', $buildStringArray), $unParsePhrase);

        return $returnString;
    }
}

