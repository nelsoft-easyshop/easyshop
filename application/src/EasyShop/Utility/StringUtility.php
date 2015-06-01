<?php

namespace EasyShop\Utility;

use HTMLPurifier as htmlPurifier;

/**
 * String utility class
 *
 * @author czarpino
 */
class StringUtility
{
    /**
     * Ezyang Html Purifier Instance
     *
     * @var \HTMLPurifier
     */
    private $htmlPurifier;

    /**
     * Config Loader Instance
     * @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;

    /**
     * Constructor.
     *
     */
    public function __construct($htmlPurifier, $configLoader)
    {
        $this->htmlPurifier = $htmlPurifier;
        $this->configLoader = $configLoader;
    }

    /**
     * Transforms serialized request parameters into an associative
     * array
     *
     * @param string $params
     *
     * @return array
     */
    public function paramsToArray($params)
    {
        $arr = [];
        parse_str($params, $arr);
        return $arr;
    }

    /**
    * Strips unwanted characters for a string encoding
    *
    * @access public
    * @param mixed $string
    * @link http://stackoverflow.com/questions/11330480/strip-php-variable-replace-white-spaces-with-dashes
    * answer from stackoverflow for SEO friendly url
    * @return mixed
    */
    public function cleanString($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespace
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespace and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        //Remove excess dash from beginning of the string
        $string = rtrim($string, '-');
        //Remove excess dash from end of the string
        $string = ltrim($string, '-');

        return $string;
    }

    /**
     * Purify HTML
     * @param  string $string
     * @return string
     */
    public function purifyHTML($string)
    {
        return  $this->htmlPurifier->purify($string);
    }

    /**
     * Remove non utf character in string
     * @param  string $string
     * @return string
     */
    public function removeNonUTF($string)
    {
        $foreignChars = $this->configLoader->getItem('foreign_chars');
        $string = preg_replace(array_keys($foreignChars), array_values($foreignChars), $string);
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        // remove non UTF-8 character convert into none
        $string = preg_replace('/[^(\x20-\x7F)]*/', '', $string);
        // remove all multiple spaces convert into 1 space
        $string = preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }
    
    
    /**
     * Remove all special characters except white space
     *
     * @param string $string
     * @return string
     */
    public function removeSpecialCharsExceptSpace($string)
    {
        //Make alphanumeric (removes all other characters)
        $string = preg_replace('/[^A-Za-z0-9\s]/', '', $string);
        // remove all multiple spaces convert into 1 space
        $string = preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }
}
