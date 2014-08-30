<?php

namespace EasyShop\Search;

use EasyShop\Entities\EsProduct;

/**
 * Search Product Class
 *
 * @author Ryan Vasquez
 */
class SearchProduct
{

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }

    /**
     * Search all product id using string given in parameter
     * @param  string $string
     * @return array;
     */
    public function filterBySearchString($string = "")
    {
        $stringCollection = array();
        $ids = array();
        $explodedString = explode(' ', trim($string));
        $stringCollection[0] = '+'.implode(' +', $explodedString) .'*';
        $stringCollection[1] = implode(' ', $explodedString);
        $stringCollection[2] = '"'.implode(' ', $explodedString).'"';
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findByKeyword($stringCollection);

        foreach ($products as $key => $value) {
            array_push($ids, $value->getIdProduct());
        }

        return $ids;
    }

    /**
     * Search all product id within category given in parameter
     * @param  array $catId  
     * @return array;
     */
    public function filterByCategory($catId = array(),$pids = array())
    {
        $ids = array();
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct');
        if(count($pids) > 0){ 
            if(count($catId) == 1 && $catId[0] == 1){ 
                return $pids;
            }
            else{
                $object = $products->findBy(['cat' => $catId,'idProduct' => $pids]); 
            }
        } 
        else{
            $object = $products->findBy(['cat' => $catId]); 
        }

        foreach ($object as $key => $value) {
            array_push($ids, $value->getIdProduct());
        }

        return $ids;
    }

    /**
     * Search all product id within brands given in parameter
     * @param  array  $brands  
     * @param  array  $pids  
     * @return array
     */
    public function filterByBrand($brands = "",$pids = array())
    {
        $brandNames = explode(',', $brands);
        $ids = array();
        $brandIds = array();
        $brandObject = $this->em->getRepository('EasyShop\Entities\EsBrand')
                                    ->findBy(['name' => $brandNames]);

        foreach ($brandObject as $key => $value) {
            array_push($brandIds, $value->getIdBrand());
        }

        $products = $this->em->getRepository('EasyShop\Entities\EsProduct');

        if(count($pids) > 0){  
            $object = $products->findBy(['brand' => $brandIds,'idProduct' => $pids]); 
        } 
        else{
            $object = $products->findBy(['brand' => $brandIds]); 
        }

        foreach ($object as $key => $value) {
            array_push($ids, $value->getIdProduct());
        }

        return $ids; 
    }

    /**
     * Search all product id within condition given in parameter
     * @param  string $condition 
     * @param  array  $pids 
     * @return array
     */
    public function filterByCondition($condition = "",$pids = array())
    {
        $conditions = explode(',', $condition);
        $ids = array();
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct');

        if(count($pids) > 0){  
            $object = $products->findBy(['condition' => $conditions,'idProduct' => $pids]); 
        } 
        else{
            $object = $products->findBy(['condition' => $conditions]); 
        }

        foreach ($object as $key => $value) {
            array_push($ids, $value->getIdProduct());
        }

        return $ids; 
    }

    /**
     * Search all product id within price given in parameter
     * @param  integer $start 
     * @param  integer $end
     * @param  array   $pids
     * @return array
     */
    public function filterByPrice($start = 0,$end = 0,$arrayItem = array())
    {
        $start = (is_numeric($start)) ? $start : 0;
        $end = (is_numeric($end)) ? $end : PHP_INT_MAX;
   
        foreach ($arrayItem as $key => $value) {
            $price = $value['price'];
            if(!($price >= $start && $price <= $end)){
                unset($arrayItem[$key]);
            }
        }
    
        return $arrayItem; 
    }

    /**
     * [filterByOtherParameter description]
     * @param  array  $productIds [description]
     * @return [type]             [description]
     */
    public function filterByOtherParameter($parameter = array(),$productIds = array())
    {
        $unsetParam = array(
                            'q_str'
                            ,'q_cat'
                            ,'condition'
                            ,'startprice'
                            ,'endprice'
                            ,'brand'
                            ,'seller'
                            ,'location'
                            ,'sort'
                        );

        $finalizedParamter = array();
        $addtionString = "";
        $counter = 0;
        foreach ($parameter as $key => $value) {
            if(!in_array(strtolower($key), $unsetParam)){
                $finalizedParamter[$key] = explode(',', $value);
                $valueString = "";
                foreach ($finalizedParamter[$key] as $paramKey => $paramValue) {
                    $valueString .= ":headValue{$counter}{$paramKey},";
                }
                $addtionString .= " OR (name = :head".$counter." AND attr_value IN (".substr($valueString, 0,-1)."))";
                $counter++;
            }
            else{
                unset($parameter[$key]);
            }
        }

        if(count($parameter) > 0){
            $addtionString = ' AND ('.substr_replace($addtionString," ",1,3).') GROUP BY product_id HAVING COUNT(*) = '. count($finalizedParamter); 
            $result = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                        ->getAttributes($productIds,TRUE,$addtionString,$finalizedParamter);
            $resultNeeded = array_map(function($value) { return $value['product_id']; }, $result);

            return $resultNeeded;
        }

        return $productIds;
    }
}
