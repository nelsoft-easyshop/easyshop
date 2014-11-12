<?php

namespace EasyShop\Search;

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsProductShippingHead;
use EasyShop\Entities\EsKeywordsTemp;
use EasyShop\Entities\EsProductImage as EsProductImage;
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
     * Symfony Http Request instance
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $httpRequest;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$collectionHelper,$productManager,$categoryManager,$httpRequest)
    {
        $this->em = $em;
        $this->collectionHelper = $collectionHelper;
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager;
        $this->httpRequest = $httpRequest;
    }

    /**
     * Search all product id using string given in parameter
     * @param  string $string
     * @return array
     */
    public function filterBySearchString($productIds,$queryString = "",$storeKeyword = true)
    {
        if($storeKeyword){
            $keywordTemp = new EsKeywordsTemp();
            $keywordTemp->setKeywords($queryString);
            $keywordTemp->setIpAddress($this->httpRequest->getClientIp());
            $keywordTemp->setTimestamp(date_create(date("Y-m-d H:i:s")));
            $this->em->persist($keywordTemp);
            $this->em->flush();
        }

        $clearString = str_replace('"', '', preg_replace('!\s+!', ' ',$queryString));
        $stringCollection = [];
        $ids = $productIds;

        if(trim($clearString)){
            $explodedString = explode(' ', trim($clearString));
            $explodedStringWithRegEx = explode(' ', trim(preg_replace('/[^A-Za-z0-9\ ]/', '', $clearString))); 

            $stringCollection[] = '+"'.implode('" +"', $explodedString) .'"';
            $wildCardString = !implode('* +', $explodedStringWithRegEx)
                              ? "" 
                              : '+'.implode('* +', $explodedStringWithRegEx) .'*';
            $stringCollection[] = str_replace("+*", "", $wildCardString);
            $stringCollection[] = '"'.trim($clearString).'"'; 

            $isLimit = strlen($clearString) > 1;
            $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                 ->findByKeyword($stringCollection,$productIds,$isLimit);

            $ids = [];
            foreach ($products as $product) {
                $ids[] = $product['idProduct']; 
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
        $productManager = $this->productManager;
        $minPrice = (is_numeric($minPrice)) ? $minPrice : 0;
        $maxPrice = (is_numeric($maxPrice)) ? $maxPrice : PHP_INT_MAX;

        $productIdsReturn = []; 
        foreach ($arrayItems as $key => $value) {
            $value = $productManager->getProductDetails($value);
            $price = round(floatval($value->getFinalPrice()),2);
            if($price >= $minPrice && $price <= $maxPrice){ 
                $productIdsReturn[] = $value->getIdProduct();
            } 
        }

        return $productIdsReturn; 
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
                $condition =  array_unique($availableCondition);

                foreach ($condition as $key => $value) {
                    $organizedAttribute['Condition'][] = $value;
                }
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
        $esProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct'); 

        // Prepare variables
        $queryString = (isset($parameters['q_str']) && $parameters['q_str'])?trim($parameters['q_str']):FALSE;
        $parameterCategory = (isset($parameters['category']) && $parameters['category'])?trim($parameters['category']):FALSE;
        $startPrice = (isset($parameters['startprice']) && $parameters['startprice'])?str_replace( ',', '', trim($parameters['startprice'])):FALSE;
        $endPrice = (isset($parameters['endprice']) && $parameters['endprice'])?str_replace( ',', '', trim($parameters['endprice'])):FALSE; 
        $pageNumber = (isset($parameters['page']) && $parameters['page'])?trim($parameters['page']):FALSE;
        $sortBy = (isset($parameters['sortby']) && $parameters['sortby'])?trim($parameters['sortby']):FALSE;
        $perPage = (isset($parameters['limit'])) ? $parameters['limit'] : self::PER_PAGE;
        $storeKeyword = ($pageNumber) ? FALSE:TRUE;

        // Search Filter 
        $productIds = $searchProductService->filterProductByDefaultParameter($parameters);
        $productIds = $searchProductService->filterProductByAttributesParameter($parameters,$productIds);

        // Search for Product Query String
        $productIds = $originalOrder = ($queryString)?$searchProductService->filterBySearchString($productIds,$queryString,$storeKeyword):$productIds;
        $productIds = ($queryString && empty($productIds)) ? array() : $productIds;
        $originalOrder = ($sortBy) ? $productIds : $originalOrder;

        if($startPrice){
            // Get product object
            $filteredProducts = $esProductRepository->findBy(['idProduct' => $productIds]);

            // filter object remove product without in the range of the price
            $finalizedProductIds = $startPrice ? $searchProductService->filterProductByPrice($startPrice, $endPrice, $filteredProducts) : $discountedProduct;
        }
        else{
            $finalizedProductIds = $productIds;
        }

        $finalizedProductIds = !empty($originalOrder) ? array_intersect($originalOrder, $finalizedProductIds) : $finalizedProductIds;

        // total product count
        $totalCount = count($finalizedProductIds);

        // Get product details
        $filteredProducts = $esProductRepository->getProductDetailsByIds($finalizedProductIds,$pageNumber,$perPage);
        
        // Sort object by original order of product id to retain weight order
        $data = new ArrayCollection($filteredProducts);
        $iterator = $data->getIterator();
        $iterator->uasort(function ($a, $b) use($finalizedProductIds) {
            $position1 = array_search($a->getIdProduct(), $finalizedProductIds);
            $position2 = array_search($b->getIdProduct(), $finalizedProductIds);
            return $position1 - $position2;
        });
        $collection = new ArrayCollection(iterator_to_array($iterator));

        if($sortBy && strtolower($sortBy) == "price"){
            $data = new ArrayCollection($filteredProducts);
            $iterator = $data->getIterator();
            $sortString = "ASC";
            if(isset($parameters['sorttype']) && strtoupper(trim($parameters['sorttype'])) == "DESC"){ 
                $sortString = "DESC"; 
            }
            $iterator->uasort(function ($a, $b) use($sortString) {
                if($a->getFinalPrice() === $b->getFinalPrice()) {
                    return 0;
                } 
                if($sortString === "DESC"){
                    return ($a->getFinalPrice() < $b->getFinalPrice()) ? -1 : 1; 
                }
                else{ 
                    return ($a->getFinalPrice() > $b->getFinalPrice()) ? -1 : 1; 
                }
            });
            $collection = new ArrayCollection(iterator_to_array($iterator));
        }

        // assign each image and image path of the product
        foreach ($collection as $key => $value) {
            $productId = $value->getIdProduct();
            $value = $productManager->getProductDetails($value); 
            $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                      ->getDefaultImage($productId);
            $value->directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
            $value->imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

            if($productImage != NULL){
                $value->directory = $productImage->getDirectory();
                $value->imageFileName = $productImage->getFilename();
            }
        }

        $returnArray = array(
                    'collection' => $collection,
                    'count' => $totalCount,
                );

        return $returnArray;
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
            foreach ($popularProduct as $keyProduct => $valueProduct) {
                $productId = $valueProduct->getIdProduct();
                $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                ->getDefaultImage($productId);
                $valueProduct->directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                $valueProduct->imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

                if($productImage != NULL){
                    $valueProduct->directory = $productImage->getDirectory();
                    $valueProduct->imageFileName = $productImage->getFilename();
                }

            }
            $subCategoryList[$value->getName()]['item'] = ($popularProduct)?$popularProduct:array(); 
            $subCategoryList[$value->getName()]['slug'] = $value->getSlug(); 
        }

        return $subCategoryList;
    }

}
