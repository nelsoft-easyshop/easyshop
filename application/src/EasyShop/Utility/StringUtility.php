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
     * Constructor.
     * 
     */
    public function __construct($htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
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
    * @return mixed
    */
    public function cleanString($string)
    {
        $string = preg_replace("/\s+/", " ", $string);
        $string = str_replace('-', ' ', trim($string));
        $string = preg_replace("/\s+/", " ", $string);
        $string = str_replace(' ', '-', trim($string));
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $string = str_replace('-', ' ', $string);
        $string = str_replace(' ', '-', $string);
        $string = str_replace('--', '-', $string);

        return preg_replace('/\s+/','-', $string);
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
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $string = preg_replace('/[^(\x20-\x7F)]*/','', $string);

        return $string;
    }
    
    
    /**
     * Remove all special characters except white space
     * 
     * @param string $string
     * @return string
     */
    public function removeSpecialCharsExceptSpace($string)
    {
        $string = preg_replace('/[^A-Za-z0-9\s]/', '', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        return $string;
    }
}
