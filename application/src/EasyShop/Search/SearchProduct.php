<?php

namespace EasyShop\Search;

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsProductShippingHead;
use EasyShop\Entities\EsKeywordsTemp;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Search Product Class
 *
 * @author Ryan Vasquez
 */
class SearchProduct
{
    /**
     * Number of product to display per request
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

        if($clearString == ""){
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
            $price = round(floatval($value->getPrice()),2); 
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
                            ,'limit'
                            ,'sortby'
                            ,'sorttype'
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
                                'location',
                                'sortby',
                                'sorttype',
                            ); 
        $notExplodableFilter = array(
                                'seller'
                                ,'category'
                                ,'q_str'
                                ,'sortby'
                                ,'sorttype'
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
    public function getProductAttributesByProductIds($products = array())
    {   
        $finalizedProductIds = array();
        $availableCondition = array();
        $organizedAttribute = array();
        if(!empty($products)){
            $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
            foreach ($products as $key => $value) {
                array_push($finalizedProductIds, $value->getIdProduct());
                array_push($availableCondition, $value->getCondition());
            }

            if(!empty($finalizedProductIds)){
                $attributes = $EsProductRepository->getAttributesByProductIds($finalizedProductIds); 
                $organizedAttribute = $this->collectionHelper->organizeArray($attributes);
                $organizedAttribute['Brand'] = $EsProductRepository->getProductBrandsByProductIds($finalizedProductIds); 
                $organizedAttribute['Condition'] =  array_unique($availableCondition);
                ksort($organizedAttribute);
            }
        }
    
        return $organizedAttribute;
    }

    /**
     * Return all product processed by all filters
     * @param  mixed $parameters
     * @param  integer $memberId
     * @return mixed
     */
    public function getProductBySearch($parameters)
    {       
        // Prepare services
        $searchProductService = $this;
        $productManager = $this->productManager;
        $categoryManager = $this->categoryManager;

        // Prepare Repository
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat'); 

        // Prepare variables
        $queryString = (isset($parameters['q_str']) && $parameters['q_str'])?trim($parameters['q_str']):FALSE;
        $parameterCategory = (isset($parameters['category']) && $parameters['category'])?trim($parameters['category']):FALSE;
        $startPrice = (isset($parameters['startprice']) && $parameters['startprice'])?trim($parameters['startprice']):FALSE;
        $endPrice = (isset($parameters['endprice']) && $parameters['endprice'])?trim($parameters['endprice']):FALSE; 
        $pageNumber = (isset($parameters['page']) && $parameters['page'])?trim($parameters['page']):FALSE;
        $sortBy = (isset($parameters['sortby']) && $parameters['sortby'])?trim($parameters['sortby']):FALSE;
        $perPage = (isset($parameters['limit'])) ? $parameters['limit'] : self::PER_PAGE;
        $storeKeyword = ($pageNumber) ? FALSE:TRUE;

        // Search for Product
        $productIds = $originalOrder = ($queryString)?$searchProductService->filterBySearchString($queryString,$storeKeyword):array();
        $productIds = ($queryString && empty($productIds)) ? array('0') : $productIds;
        $productIds = $searchProductService->filterProductByDefaultParameter($parameters,$productIds); 
        $originalOrder = ($sortBy) ? $productIds : $originalOrder;
        $productIds = $searchProductService->filterProductByAttributesParameter($parameters,$productIds);

        // Get product details
        $filteredProducts = $EsProductRepository->getProductDetailsByIds($productIds,$pageNumber,$perPage,FALSE);

        // apply actual price on each product with or without discount
        $discountedProduct = (!empty($filteredProducts)) ? $productManager->discountProducts($filteredProducts) : array(); 
        // filter object remove product without in the range of the price
        $productsResult = ($startPrice) ? $searchProductService->filterProductByPrice($startPrice,$endPrice,$discountedProduct) : $discountedProduct;

        $finalizedProductIds = [];
        foreach ($productsResult as $key => $value) {
            array_push($finalizedProductIds, $value->getIdProduct());
        }

        $finalizedProductIds = (!empty($originalOrder)) ? array_intersect($originalOrder, $finalizedProductIds) : $finalizedProductIds; 

        // Get product details
        $filteredProducts = $EsProductRepository->getProductDetailsByIds($finalizedProductIds,$pageNumber,$perPage);
        
        // Sort object by original order of product id to retain weight order
        $data = new ArrayCollection($filteredProducts);
        $iterator = $data->getIterator();
        $iterator->uasort(function ($a, $b) use($finalizedProductIds) {
            $position1 = array_search($a->getIdProduct(), $finalizedProductIds);
            $position2 = array_search($b->getIdProduct(), $finalizedProductIds);
            return $position1 - $position2;
        });
        $collection = new ArrayCollection(iterator_to_array($iterator));

        // assign each image and image path of the product
        foreach ($collection as $key => $value) {
            $productId = $value->getIdProduct();
            $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                        ->getDefaultImage($productId);
            $value->directory = $productImage->getDirectory();
            $value->imageFileName = $productImage->getFilename();
        }
        
        return $collection;
    }

    /**
     * Get popular product of the given category
     * @param  array $subCategory
     * @return array $subCategoryList
     */
    public function getPopularProductOfCategory($subCategory)
    {
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat'); 
        $subCategoryList = array();
           
        foreach ($subCategory as $key => $value) { 
            $subCategoryIds = $EsCatRepository->getChildCategoryRecursive($value->getIdCat());
            $popularProductId = $EsProductRepository->getPopularItem(count($subCategoryIds),0,0,$subCategoryIds);
            $popularProduct = $EsProductRepository->getProductDetailsByIds($popularProductId,0,1);
            $subCategoryList[$value->getName()]['item'] = ($popularProduct)?$popularProduct:array(); 
            $subCategoryList[$value->getName()]['slug'] = $value->getSlug(); 
        }

        return $subCategoryList;
    }

}
