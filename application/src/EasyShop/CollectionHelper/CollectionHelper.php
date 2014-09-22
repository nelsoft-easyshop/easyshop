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
            $organizeArray[$head][] = $value[$arrayKey[1]]; 
            $organizeArray[$head] = array_unique($organizeArray[$head]);
        }

        return $organizeArray;
    }

    /**
     * Remove selected array from the given origin array
     * reverse condition if the third parameter exist
     * @param  mixed  $originArray
     * @param  mixed  $arrayToRemove
     * @param  boolean $reverseCondition
     * @return mixed
     */
    public function removeArrayToArray($originArray,$arrayToRemove,$reverseCondition=TRUE)
    {
        foreach ($originArray as $key => $value) {
            if($reverseCondition){
                if(in_array($key, $arrayToRemove)){
                    unset($originArray[$key]);
                }
            }
            else{
                if(!in_array($key, $arrayToRemove)){
                    unset($originArray[$key]);
                }
            }
        }

        return $originArray;
    }

    /**
     * Explode url paramter convert to multi-dimensional array
     * @param  mixed $urlParameters
     * @return mixed
     */
    public function explodeUrlValueConvertToArray($urlParameters,$ignoreParam = array())
    {
        $newFormedArray = [];
        $paramterDelimiter = ",";
        foreach ($urlParameters as $key => $value) {
            $trimValue = trim($value);
            if(!in_array($key, $ignoreParam)){
                $convertedValue = explode($paramterDelimiter, $value);
                $newFormedArray[$key] = $convertedValue;
            }
            else{
                $newFormedArray[$key] = $value;
            }
        }

        return $newFormedArray;
    }
}