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
     * @param  array    $array
     * @param  boolean  $all
     * @param  boolean  $noQuote
     * @return array
     */
    public function organizeArray($array = array(),$all = false,$noQuote = false)
    {
        $organizeArray = array();

        if(!$all){
            foreach ($array as $value) {
                $arrayKey = array_keys($value); 
                $head = strtolower($value[$arrayKey[0]]);
                if(!array_key_exists($head,$organizeArray)){
                    $organizeArray[$head] = array();
                }
                $organizeArray[$head][] = strtoupper($value[$arrayKey[1]]);
                $organizeArray[$head] = array_unique($organizeArray[$head]);
            }
        }
        else{ 
            foreach($array as $row){ 
                $arrayKey = array_keys($row);
                $head = strtolower($row[$arrayKey[0]]);
                $head = $noQuote ? $head : "'$head'";
                if(!array_key_exists($head, $organizeArray)){
                    $organizeArray[$head] = array();
                } 
                $organizeArray[$head][] = $row; 
            }
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

    /**
     * Generate all the possible combinations among a set of nested arrays.
     *
     * @param array $data  The entrypoint array container.
     * @param array $all   The final container (used internally).
     * @param array $group The sub container (used internally).
     * @param mixed $val   The value to append (used internally).
     * @param int   $index     The key index (used internally).
     */
    public function generateCombinations(array $data, array &$all = [], array $group = [], $value = null, $index = 0)
    {
        $keys = array_keys($data);
        if (isset($value) === true) {
            $group[] = $value;
        }

        if ($index >= count($data)) {
            $all[] = $group;
        } 
        else {
            $currentKey     = $keys[$index];
            $currentElement = $data[$currentKey];
            foreach ($currentElement as $val) {
                $this->generateCombinations($data, $all, $group, $val, $index + 1);
            }
        }

        return $all;
    }
}

