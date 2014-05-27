<?php

namespace EasyShop\Utility;

/**
 * Url utility class
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
}
