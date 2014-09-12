<?php

namespace EasyShop\Search;

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsProductShippingHead;
use EasyShop\Entities\EsKeywordsTemp;

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
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Search all product id using string given in parameter
     * @param  string $string
     * @return array;
     */
    public function filterBySearchString($queryString = "",$storeKeyword = TRUE)
    {
        if($storeKeyword){
            // Insert into search keyword temp 
            $keywordTemp = new EsKeywordsTemp();
            $keywordTemp->setKeywords($queryString); 
            $this->em->persist($keywordTemp);
            $this->em->flush();
        }

        $clearString = preg_replace('/[^A-Za-z0-9]+/', ' ', $queryString);  
        $stringCollection = array();
        $ids = array(); 
        $explodedString = explode(' ', trim($clearString)); 
        $stringCollection[0] = '+'.implode('* +', $explodedString) .'*';
        $stringCollection[1] = trim($clearString);
        $stringCollection[2] = '"'.trim($clearString).'"';
        $stringCollection[3] = str_replace(' ', '', trim($clearString));
        
        if($queryString == ""){
            $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->findBy(['isDraft' => 0,'isDelete' => 0]);
        }
        else{
            $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->findByKeyword($stringCollection);
        }

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
    public function filterByCategory($catId = array(),$pids = array(),$filter=false)
    {
        $ids = array();
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct');
        if($filter){ 
            if(count($catId) == 1 && $catId[0] == 1){ 
                return $pids;
            }
            else{
                $object = $products->findBy(['cat' => $catId,'idProduct' => $pids,'isDraft' => 0,'isDelete' => 0]); 
            }
        } 
        else{
            if(count($catId) == 1 && $catId[0] == 1){ 
                $object = $products->findBy(['isDraft' => 0,'isDelete' => 0]); 
            }
            else{
                $object = $products->findBy(['cat' => $catId,'isDraft' => 0,'isDelete' => 0]); 
            }
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
    public function filterByBrand($brands = "",$pids = array(),$filter=false)
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

        if($filter){  
            $object = $products->findBy(['brand' => $brandIds,'idProduct' => $pids,'isDraft' => 0,'isDelete' => 0]); 
        } 
        else{
            $object = $products->findBy(['brand' => $brandIds,'isDraft' => 0,'isDelete' => 0]); 
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
    public function filterByCondition($condition = "",$pids = array(),$filter=false)
    {
        $conditions = explode(',', $condition);
        $ids = array();
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct');

        if($filter){  
            $object = $products->findBy(['condition' => $conditions,'idProduct' => $pids,'isDraft' => 0,'isDelete' => 0]); 
        } 
        else{
            $object = $products->findBy(['condition' => $conditions,'isDraft' => 0,'isDelete' => 0]); 
        }

        foreach ($object as $key => $value) {
            array_push($ids, $value->getIdProduct());
        }

        return $ids; 
    }

    /**
     * Search all product id within price given in parameter
     * @param  integer $minPrice 
     * @param  integer $maxPrice
     * @param  array   $arrayItems
     * @return array
     */
    public function filterByPrice($minPrice = 0,$maxPrice = 0,$arrayItems = array())
    {
        $minPrice = (is_numeric($minPrice)) ? $minPrice : 0;
        $maxPrice = (is_numeric($maxPrice)) ? $maxPrice : PHP_INT_MAX;
   
        foreach ($arrayItems as $key => $value) {
            $price = round(floatval($value['price']),2); 
            if($price < $minPrice || $price > $maxPrice){
                unset($arrayItems[$key]);
            }
        }
    
        return $arrayItems; 
    }

    /**
     * Filter product by parameters attributes
     * @param  array  $productIds
     * @return array
     */
    public function filterByOtherParameter($parameter = array(),$productIds = array())
    {
        // array of parameters that will disregard on filtering
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
                            ,'typeview'
                            ,'page'
                        );

        $finalizedParamter = array();
        $addtionString = "";
        $counter = 0;
        $havingCounter = 0;

        if(!empty($parameter)){
            foreach ($parameter as $key => $value) {
                if(!in_array(strtolower($key), $unsetParam)){
                    $finalizedParamter[$key] = explode(',', $value);
                    $valueString = "";
                    foreach ($finalizedParamter[$key] as $paramKey => $paramValue) {
                        $valueString .= ":headValue{$counter}{$paramKey},";
                        $havingCounter++;
                    }
                    $addtionString .= " OR (name = :head".$counter." AND attr_value IN (".substr($valueString, 0,-1)."))";
                    $counter++;
                }
                else{
                    unset($parameter[$key]);
                }
            }

            if(!empty($parameter)){
                $addtionString = ' AND ('.substr_replace($addtionString," ",1,3).') GROUP BY product_id HAVING COUNT(*) = '. $havingCounter; 
                $result = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->getAttributes($productIds,TRUE,$addtionString,$finalizedParamter);
                $resultNeeded = array_map(function($value) { return $value['product_id']; }, $result);

                return $resultNeeded;
            }
        }

        return $productIds;
    }

    /**
     * Filter array by seller if exist
     * @param  string $seller    [description]
     * @param  array  $arrayItems [description]
     * @return array
     */
    public function filterBySeller($seller = "", $arrayItems = array())
    {
        $seller = trim($seller); 
   
        foreach ($arrayItems as $key => $value) {
            $username = $value['username']; 
            if (strpos($username, $seller) === false) { 
                unset($arrayItems[$key]);
            }
        }
    
        return $arrayItems; 
    }

    /**
     * Filter product by given location
     * @param  array   $productIds [description]
     * @param  integer $location   [description]
     * @param  boolean $filter     [description]
     * @return array
     */
    public function filterByLocation($location = 0, $pids = array(), $filter = FALSE)
    {
        $ids = array();
        $products = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead');
        $object = $products->findBy(['location' => $location]); 

        if($filter){  
            $object = $products->findBy(['location' => $location,'product' => $pids]); 
        } 
        else{
            $object = $products->findBy(['location' => $location]);
        }

        foreach ($object as $key => $value) {  
            array_push($ids, $value->getProduct()->getIdProduct());
        }

        return $ids; 
    }
}
