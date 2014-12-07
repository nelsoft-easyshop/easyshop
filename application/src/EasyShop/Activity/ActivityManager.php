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
     * @param  array  $modifiedArray [description]
     * @param  string $languangeLine [description]
     * @return string
     */
    public function constructActivityPhrase($modifiedArray, $languangeLine)
    {   
        $returnString = "";
        $buildString = "";
        $buildStringArray = [];
        $unParsePhrase = $this->languageLoader->getLine($languangeLine); 
        foreach ($modifiedArray as $key => $value) {
            $fieldLanguage = $this->languageLoader->getLine('EsMember')[$key];
            $buildStringArray[] = $fieldLanguage." : ".$value;
        }
        $parseDateString = str_replace(":date", date("Y-m-d H:i:s"), $unParsePhrase);
        $returnString = str_replace(":phrase", implode(', ', $buildStringArray), $parseDateString);

        return $returnString;
    }
}