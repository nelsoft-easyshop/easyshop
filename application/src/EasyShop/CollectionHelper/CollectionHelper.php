<?php

namespace EasyShop\CollectionHelper;

/**
 * Product Manager Class
 *
 * @author Ryan Vasquez
 */
class CollectionHelper
{

    /**
     * Group the array by its head
     * @param  array  $array [description]
     * @return array
     */
    public function organizeArray($array = array())
    {
        $organizeArray = array();

        foreach ($array as $key => $value) {
            $arrayKey = array_keys($value); 
            $head = $value[$arrayKey[0]];
            if(!array_key_exists($head,$organizeArray)){
                $organizeArray[$head] = array();
            }
            array_push($organizeArray[$head],  $value[$arrayKey[1]]);
        }

        return $organizeArray;
    }
}