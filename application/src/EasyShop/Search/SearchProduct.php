<?php

namespace EasyShop\Search;

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsProductShippingHead;
use EasyShop\Entities\EsKeywordsTemp;
use EasyShop\Entities\EsKeywords as EsKeywords;
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
    const PER_PAGE = 12;

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
     * Promo Manager Instance
     *
     * @var Easyshop\PromoManager
     */
    private $promoManager;

    /**
     * Config Loaded
     *
     * @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;
    
    
    /**
     * Sphinx Search Client
     *
     * @var sphinxapi
     */
    private $sphinxClient;


    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,
                                $collectionHelper,
                                $productManager,
                                $categoryManager,
                                $httpRequest,
                                $promoManager,
                                $configLoader,
                                $sphinxClient,
                                $userManager)
    {
        $this->em = $em;
        $this->collectionHelper = $collectionHelper;
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager;
        $this->httpRequest = $httpRequest;
        $this->promoManager = $promoManager;
        $this->configLoader = $configLoader;
        $this->sphinxClient = $sphinxClient;
        $this->userManager = $userManager;
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

        $ids = [];
        $sphinxMatchMatches = $this->configLoader->getItem('search','sphinx_match_matches');
        $this->sphinxClient->SetMatchMode('SPH_MATCH_ANY');
        $this->sphinxClient->SetFieldWeights([
            'name' => 50, 
            'store_name' => 30,
            'search_keyword' => 10,
        ]);
    
        if(empty($productIds) === false){
            $this->sphinxClient->SetFilter('productid', $productIds);
        }
        $this->sphinxClient->setLimits(0, $sphinxMatchMatches, $sphinxMatchMatches);
        $this->sphinxClient->AddQuery($queryString, 'products products_delta'); 
        
        $sphinxResult =  $this->sphinxClient->RunQueries();
        
        $products = [];
        if($sphinxResult === false){
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
        }
        else if(isset($sphinxResult[0]['matches'])){
            foreach ($sphinxResult[0]['matches'] as $productId => $product) {
                $ids[] = $productId; 
            }
        }

        return $ids;
    }

    /**
     * Search all product id within price given in parameter
     * @param  integer $minPrice 
     * @param  integer $maxPrice
     * @param  array   $productIds
     * @param  string  $sortType
     * @return array
     */
    public function filterProductByPrice($minPrice = 0,$maxPrice = 0,$productIds = [], $sortType = "")
    {
        $promoManager = $this->promoManager;
        $minPrice = (is_numeric($minPrice)) ? $minPrice : 0;
        $maxPrice = (is_numeric($maxPrice)) ? $maxPrice : PHP_INT_MAX;
        $productIdsReturn = [];
        $productIdsWithPrice = [];

        foreach ($productIds as $productId) {
            $promoPrice = $promoManager->hydratePromoDataExpress($productId);
            $price = round(floatval($promoPrice),2);
            if($price >= $minPrice && $price <= $maxPrice){ 
                $productIdsReturn[] = $productId;
                $productIdsWithPrice[$productId] = $price;
            } 
        }

        if($sortType !== ""){
            if(strtoupper($sortType) === "DESC"){
                arsort($productIdsWithPrice);
            }
            else{ 
                asort($productIdsWithPrice);
            }
            $productIdsReturn = array_keys($productIdsWithPrice);
        }

        return $productIdsReturn; 
    }

    /**
     * Filter product by attributes parameter
     * @param  array  $productIds
     * @return array
     */
    public function filterProductByAttributesParameter($parameter = []
                                                        ,$productIds = [])
    {
        // array of parameters that will disregard on filtering
        $unsetParam = [
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
        ];

        $finalizedParamter = [];
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
    public function filterProductByDefaultParameter($filterParameter,$productIds = [])
    { 
        $acceptableFilter = [
            'seller',
            'category',
            'brand',
            'condition',
            'location',
            'sortby',
            'sorttype',
        ]; 
        $notExplodableFilter = [
            'seller'
            ,'category'
            ,'q_str'
            ,'sortby'
            ,'sorttype'
        ];

        $excludePromo = $this->configLoader->getItem('search','hide_promo_type');
        $excludeProducts = $this->configLoader->getItem('search','hide_product_slug');
        $filteredArray = $this->collectionHelper->removeArrayToArray($filterParameter,$acceptableFilter,false);
        $filteredArray = $this->collectionHelper->explodeUrlValueConvertToArray($filterParameter, $notExplodableFilter);
        $productIds = $this->em->getRepository('EasyShop\Entities\EsProduct')
                               ->getProductByParameterFiltering($filteredArray,
                                                                $productIds,
                                                                $excludePromo,
                                                                $excludeProducts);

        return $productIds;
    }

    /**
     * Get the available attribute of the product by the given product IDs
     * @param  integer[] $productIds
     * @return mixed
     */
    public function getProductAttributesByProductIds($productIds = [])
    {
        $organizedAttribute = []; 
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');

        if(!empty($productIds)){
            $attributes = $EsProductRepository->getAttributesByProductIds($productIds); 
            $organizedAttribute = $this->collectionHelper->organizeArray($attributes);
            $organizedAttribute['Brand'] = $EsProductRepository->getProductBrandsByProductIds($productIds);
            $organizedAttribute['Condition'] = $EsProductRepository->getProductConditionByProductIds($productIds);
            ksort($organizedAttribute);
        } 
    
        return $organizedAttribute;
    }

    /**
     * Return all product processed by all filters
     * @param  mixed   $parameters
     * @param  integer $memberId
     * @param  integer $isHydrate
     * @return mixed
     */
    public function getProductBySearch($parameters, $isHydrate = true)
    {
        $searchProductService = $this;
        $productManager = $this->productManager;
        $categoryManager = $this->categoryManager;
        $userManager = $this->userManager;

        $queryString = isset($parameters['q_str']) && $parameters['q_str']?trim($parameters['q_str']):false;
        $parameterCategory = isset($parameters['category']) && $parameters['category']?trim($parameters['category']):false;
        $startPrice = isset($parameters['startprice']) && $parameters['startprice']?str_replace( ',', '', trim($parameters['startprice'])):false;
        $endPrice = isset($parameters['endprice']) && $parameters['endprice']?str_replace( ',', '', trim($parameters['endprice'])):false; 
        $pageNumber = isset($parameters['page']) && $parameters['page']?trim($parameters['page']):false;
        $sortBy = isset($parameters['sortby']) && $parameters['sortby'] ?trim($parameters['sortby']):false;
        $perPage = isset($parameters['limit']) ? $parameters['limit'] : self::PER_PAGE;
        $storeKeyword = $pageNumber ? false:true;

        $productIds = $searchProductService->filterProductByDefaultParameter($parameters);
        $productIds = $sortOrder = $searchProductService->filterProductByAttributesParameter($parameters,$productIds);
        $productIds = $originalOrder = $queryString?$searchProductService->filterBySearchString($productIds,$queryString,$storeKeyword):$productIds;
        $productIds = $queryString && empty($productIds) ? [] : $productIds;
        $originalOrder = $sortBy ? $sortOrder : $originalOrder;
        $finalizedProductIds = $productIds;
        if($startPrice || $endPrice || strtolower($sortBy) === "price"){
            $sortType = "";
            if(strtolower($sortBy) === "price"){
                $sortType = "ASC";
                if(isset($parameters['sorttype']) && strtoupper($parameters['sorttype']) === "DESC"){
                    $sortType = "DESC";
                }
            }
            $finalizedProductIds = $searchProductService->filterProductByPrice($startPrice, $endPrice, $productIds, $sortType);
            $originalOrder = strtolower($sortBy) === "price" ? $finalizedProductIds : $originalOrder;
        }

        $finalizedProductIds = !empty($originalOrder) ? array_intersect($originalOrder, $finalizedProductIds) : $finalizedProductIds;
        $finalizedProductIds = $this->sortResultByTopic($finalizedProductIds,$queryString);
        $totalCount = count($finalizedProductIds);
        $products = [];
        
        if ($isHydrate) {
            $offset = (int) bcmul($pageNumber, $perPage);
            $paginatedProductIds = array_slice($finalizedProductIds, $offset, $perPage);
            foreach ($paginatedProductIds as $productId) {
                $product = $productManager->getProductDetails($productId);
                $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                         ->getDefaultImage($productId);
                $secondaryProductImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                  ->getSecondaryImage($productId);

                $product->ownerAvatar = $userManager->getUserImage($product->getMember()->getIdMember());
                $product->directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                $product->imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;
                $product->secondaryImageDirectory = null;
                $product->secondaryImageFileName = null;
                $product->hasSecondaryImage = false;

                if($productImage !== null){
                    $product->directory = $productImage->getDirectory();
                    $product->imageFileName = $productImage->getFilename();
                }

                if($secondaryProductImage !== null){
                    $product->hasSecondaryImage = true;
                    $product->secondaryImageDirectory = $secondaryProductImage->getDirectory();
                    $product->secondaryImageFileName = $secondaryProductImage->getFilename();
                }

                $products[] = $product;
            }

            if($sortBy && strtolower($sortBy) == "price"){
                $data = new ArrayCollection($products);
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
                        return ($a->getFinalPrice() > $b->getFinalPrice()) ? -1 : 1; 
                    }

                    return ($a->getFinalPrice() < $b->getFinalPrice()) ? -1 : 1;
                });
                $products = new ArrayCollection(iterator_to_array($iterator));
            }
        }

        $returnArray = [
            'collection' => $products,
            'count' => $totalCount,
            'productIds' => $finalizedProductIds
        ];

        return $returnArray;
    }

    /**
     * Sort product object using search topic table
     * @param  object $products 
     * @param  string $queryString 
     * @return object
     */
    public function sortResultByTopic($productIds, $queryString) 
    {
        $wordResult = $this->em->getRepository('EasyShop\Entities\EsSearchTopic')
                               ->getTopicOrderByWord($queryString);
        if(count($wordResult) > 0){
            $sortedIds = [];
            $categoryOrder = [];
            foreach ($wordResult as $result) {
                $categoryOrder[] = $result->getCategory()->getIdCat();
            }
        
            $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                 ->getProductCategoryIdByIds($productIds);

            usort($products, function ($a, $b) use ($categoryOrder) {
                $positionA = array_search($a['cat_id'], $categoryOrder);
                $positionB = array_search($b['cat_id'], $categoryOrder);
                return $positionA - $positionB;
            }); 

            foreach ($products as $product) {
                $sortedIds[] = $product['id_product'];
            }
            return $sortedIds;
        }

        return $productIds;
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
        $productManager = $this->productManager;
        $subCategoryList = [];

        foreach ($subCategory as $value) {
            $subCategoryIds = $EsCatRepository->getChildrenWithNestedSet($value->getIdCat());
            $popularProducts = [];
            if(!empty($subCategoryIds)){
                $popularProducts = $EsProductRepository->getPopularItemByCategory($subCategoryIds);
            }
            if(!empty($popularProducts)){ 
                $popularProducts[0] = $productManager->getProductDetails($popularProducts[0]);
                $productId = $popularProducts[0]->getIdProduct();
                $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                         ->getDefaultImage($productId);
                $popularProducts[0]->directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                $popularProducts[0]->imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

                if($productImage != NULL){
                    $popularProducts[0]->directory = $productImage->getDirectory();
                    $popularProducts[0]->imageFileName = $productImage->getFilename();
                } 
            }
            else{
                $popularProducts[0] = [];
            }
            $subCategoryList[$value->getName()]['item'] = $popularProducts[0];
            $subCategoryList[$value->getName()]['slug'] = $value->getSlug();
        }

        return $subCategoryList;
    }
    
    /**
     * Retrieves suggested words for search string
     *
     * @param string $queryString
     * @return string[]
     */
    public function getKeywordSuggestions($queryString)
    {
        $suggestionLimit = EsKeywords::SUGGESTION_LIMIT;
        $suggestions = [];

        $this->sphinxClient->SetMatchMode('SPH_MATCH_ANY');
        $this->sphinxClient->SetFieldWeights([
            'keywords' => 50,
        ]);
    
        $this->sphinxClient->SetSortMode(SPH_SORT_RELEVANCE);
        $this->sphinxClient->setLimits(0, $suggestionLimit, $suggestionLimit); 
        $this->sphinxClient->AddQuery($queryString.'*', 'suggestions');
        
        $sphinxResult =  $this->sphinxClient->RunQueries();
        if($sphinxResult === false){
            $keywords = $this->em->getRepository('EasyShop\Entities\EsKeywords')
                                 ->getSimilarKeywords($queryString, $suggestionLimit);
            foreach($keywords as $word){
                 $suggestions[] = $word['keyword'];
            }
        }
        else if(isset($sphinxResult[0]['matches'])){
            foreach($sphinxResult[0]['matches'] as $match){
                $suggestions[] = $match['attrs']['keywordattr'];
            }
        }

        return $suggestions;
    }

}
