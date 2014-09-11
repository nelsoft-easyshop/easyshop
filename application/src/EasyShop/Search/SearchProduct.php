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
     * Number of product display per page
     */
    const PER_PAGE = 15;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Collection Helper instance
     *
     * @var EasyShop\CollectionHelper
     */
    private $collectionHelper;

    /**
     * Product Manager instance
     *
     * @var EasyShop\ProductManager
     */
    private $productManager;

    /**
     * Category Manager instance
     *
     * @var EasyShop\CategoryManager
     */
    private $categoryManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$collectionHelper,$productManager,$categoryManager)
    {
        $this->em = $em;
        $this->collectionHelper = $collectionHelper;
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager;
    }

    /**
     * Search all product id using string given in parameter
     * @param  string $string
     * @return array
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
     * Search all product id within price given in parameter
     * @param  integer $minPrice 
     * @param  integer $maxPrice
     * @param  array   $arrayItems
     * @return array
     */
    public function filterProductByPrice($minPrice = 0,$maxPrice = 0,$arrayItems = array())
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
     * Filter product by attributes parameter
     * @param  array  $productIds
     * @return array
     */
    public function filterProductByAttributesParameter($parameter = array()
                                                        ,$productIds = array())
    {
        // array of parameters that will disregard on filtering
        $unsetParam = array(
                            'q_str'
                            ,'category'
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
                                            ->getAttributesByProductIds($productIds,TRUE,$addtionString,$finalizedParamter);
                $resultNeeded = array_map(function($value) { return $value['product_id']; }, $result);

                return $resultNeeded;
            }
        }

        return $productIds;
    }
 
    /**
     * filter product by default filter parameter
     * @param  array $filterParameter 
     * @param  array  $productIds 
     * @return integer[]
     */
    public function filterProductByDefaultParameter($filterParameter,$productIds = array())
    { 
        $acceptableFilter = array(
                                'seller',
                                'category',
                                'brand',
                                'condition',
                                'location' 
                            ); 
        $notExplodableFilter = array(
                                'seller'
                                ,'category'
                            );

        $filteredArray = $this->collectionHelper->removeArrayToArray($filterParameter,$acceptableFilter,FALSE);
        $filteredArray = $this->collectionHelper->explodeUrlValueConvertToArray($filterParameter,$notExplodableFilter);
        $productIds = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                        ->getProductByParameterFiltering($filteredArray,$productIds); 

        return $productIds;
    }

    /**
     * Get the available attribute of the product by the given product IDs
     * @param  integer[] $productIds
     * @return mixed
     */
    public function getProductAttributesByProductIds($productIds = array())
    {   
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $organizedAttribute = array();
        if(count($productIds)>0){
            $attributes = $EsProductRepository->getAttributesByProductIds($productIds); 
            $organizedAttribute = $this->collectionHelper->organizeArray($attributes);
            $organizedAttribute['Brand'] = $EsProductRepository->getProductBrandsByProductIds($productIds); 
        }

        return $organizedAttribute;
    }

    /**
     * Return all product processed by all filters
     * @param  mixed $parameters
     * @param  integer $memberId
     * @return mixed
     */
    public function getProductBySearch($parameters,$memberId = 0)
    {    
        $searchProductService = $this;
        $productManager = $this->productManager;
        $categoryManager = $this->categoryManager;

        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat'); 


        $queryString = (isset($parameters['q_str']) && $parameters['q_str'])?trim($parameters['q_str']):FALSE;
        $parameterCategory = (isset($parameters['category']) && $parameters['category'])?trim($parameters['category']):FALSE;
        $startPrice = (isset($parameters['startprice']) && $parameters['startprice'])?trim($parameters['startprice']):FALSE;
        $endPrice = (isset($parameters['endprice']) && $parameters['endprice'])?trim($parameters['endprice']):FALSE; 
        $pageNumber = (isset($parameters['page']) && $parameters['page'])?trim($parameters['page']):FALSE;  
        $storeKeyword = ($pageNumber) ? FALSE:TRUE;

        $productIds = $originalOrder = ($queryString)?$searchProductService->filterBySearchString($queryString,$storeKeyword):array();
        $productIds = $searchProductService->filterProductByDefaultParameter($parameters,$productIds); 
        $productIds = $searchProductService->filterProductByAttributesParameter($parameters,$productIds);

        $productIds = (count($originalOrder)>0) ? array_intersect($originalOrder, $productIds) : $productIds; 
        $filteredProducts = $EsProductRepository->getProductDetailsByIds($productIds,$pageNumber,self::PER_PAGE);
        $discountedProduct = ($filteredProducts > 0) ? $productManager->getDiscountedPrice($memberId,$filteredProducts) : array(); 
        $productsResult = ($startPrice) ? $searchProductService->filterProductByPrice($startPrice,$endPrice,$discountedProduct) : $discountedProduct;

        return $productsResult;
    }

}
