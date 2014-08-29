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
        $stringCollection[0] = '+'.implode(' +', $explodedString);
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
    public function filterByPrice($start = 0,$end = 0,$pids = array())
    {
        $start = (is_numeric($start)) ? $start : 0;
        $end = (is_numeric($end)) ? $end : PHP_INT_MAX;

        $ids = array();

        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findByPrice($start,$end,$pids);

        foreach ($products as $key => $value) {
            array_push($ids, $value->getIdProduct());
        }

        return $ids; 
    }

}
