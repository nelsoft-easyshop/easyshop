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

    /**
     * Sort array by given arrangement
     * @param  array  $array
     * @param  array  $arrangement
     * @param  string $field
     * @return array
     */
    public function sortArrayByArrangement($arrayList = array(),$arrangement = array(),$field = "")
    {
        $arrangeArray = array(); ;
        foreach ($arrangement as $arrangementKey) {
            foreach ($arrayList as $key => $value) {
                if($value[$field] == $arrangementKey)
                {
                    array_push($arrangeArray, $value);
                }
            }
        }

        return $arrangeArray;
    }
}