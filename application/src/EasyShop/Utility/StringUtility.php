<?php

namespace EasyShop\Utility;

use HTMLPurifier as HTMLPurifier;

/**
 * String utility class
 *
 * @author czarpino
 */
class StringUtility
{
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

    public function purifyString($string)
    {

        $HTMLPurifier = new HTMLPurifier();

        return  $HTMLPurifier->purify($string);
    }
}
