<?php

namespace EasyShop\Activity;

class ActivityManager
{
    /**
     * Language Loader Class
     *
     * @var EasyShop\LanguageLoader\LanguageLoader
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
     * @param  string $entityLine
     * @return string
     */
    public function constructActivityPhrase($modifiedArray, $unParsePhrase, $entityLine)
    {   
        $returnString = "";
        $buildString = "";
        $buildStringArray = [];
        $entityLanguange = $this->languageLoader->getLine($entityLine);
        foreach ($modifiedArray as $key => $value) {
            $fieldLanguage = $entityLanguange[$key];
            $buildStringArray[] = ucfirst(strtolower($fieldLanguage))." : ".$value;
        }
        $returnString = str_replace(":phrase", implode(', ', $buildStringArray), $unParsePhrase);

        return $returnString;
    }
}

