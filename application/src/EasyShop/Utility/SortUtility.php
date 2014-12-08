<?php

namespace EasyShop\Utility;

class SortUtility
{

    /**
     * Stable UASORT. Regular uasort returns inconsistent
     * results for elements of equal value 
     * 
     * @param mixed $array
     * @param function $cmp_function
     * @source http://php.net/manual/en/function.uasort.php#114535
     */
    public function stableUasort(&$array, $cmp_function) 
    {
        if(count($array) < 2) {
            return;
        }
        $halfway = count($array) / 2;
        $array1 = array_slice($array, 0, $halfway, true);
        $array2 = array_slice($array, $halfway, null, true);

        $this->stableUasort($array1, $cmp_function);
        $this->stableUasort($array2, $cmp_function);
        if(call_user_func($cmp_function, end($array1), reset($array2)) < 1) {
            $array = $array1 + $array2;
            return;
        }
        $array = [];
        reset($array1);
        reset($array2);
        while(current($array1) && current($array2)) {
            if(call_user_func($cmp_function, current($array1), current($array2)) < 1) {
                $array[key($array1)] = current($array1);
                next($array1);
            } 
            else {
                $array[key($array2)] = current($array2);
                next($array2);
            }
        }
        while(current($array1)) {
            $array[key($array1)] = current($array1);
            next($array1);
        }
        while(current($array2)) {
            $array[key($array2)] = current($array2);
            next($array2);
        }
        return;
    }

}

