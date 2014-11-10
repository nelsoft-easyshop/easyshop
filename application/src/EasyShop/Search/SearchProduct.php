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
     * Promo Manager Instance
     *
     * @var Easyshop\PromoManager
     */
    private $promoManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$collectionHelper,$productManager,$categoryManager,$httpRequest,$promoManager)
    {
        $this->em = $em;
        $this->collectionHelper = $collectionHelper;
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager;
        $this->httpRequest = $httpRequest;
        $this->promoManager = $promoManager;
    }

    /**
     * Search all product id using string given in parameter
     * @param  string $string
     * @return array
     */
    public function filterBySearchString($productIds,$queryString = "",$storeKeyword = TRUE)
    {
        if($storeKeyword){
            // Insert into search keyword temp 
            $keywordTemp = new EsKeywordsTemp();
            $keywordTemp->setKeywords($queryString);
            $keywordTemp->setIpAddress($this->httpRequest->getClientIp());
            $keywordTemp->setTimestamp(date_create(date("Y-m-d H:i:s")));
            $this->em->persist($keywordTemp);
            $this->em->flush();
        }

        $clearString = str_replace('"', '', preg_replace('!\s+!', ' ',$queryString));
        $stringCollection = array();
        $ids = $productIds; 

        if(trim($clearString) != ""){
            $explodedString = explode(' ', trim($clearString)); 
            $stringCollection[0] = '+"'.implode('" +"', $explodedString) .'"';
            $explodedString = explode(' ', trim(preg_replace('/[^A-Za-z0-9\ ]/', '', $clearString))); 
            $stringCollection[1] = (implode('* +', $explodedString)  == "") ? "" : '+'.implode('* +', $explodedString) .'*'; 
            $stringCollection[2] = '"'.trim($clearString).'"'; 
            $boolean = (strlen($clearString) > 1) ? FALSE : TRUE;
            $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->findByKeyword($stringCollection,$productIds,$boolean);

            $ids = [];
            foreach ($products as $key => $value) {
                $ids[] = $value['idProduct']; 
            }
        }
        
        return $ids;
    }

    /**
     * Search all product id within price given in parameter
     * @param  integer $minPrice 
     * @param  integer $maxPrice
     * @param  array   $productIds
     * @return array
     */
    public function filterProductByPrice($minPrice = 0,$maxPrice = 0,$productIds = array())
    {
        $promoManager = $this->promoManager;
        $minPrice = (is_numeric($minPrice)) ? $minPrice : 0;
        $maxPrice = (is_numeric($maxPrice)) ? $maxPrice : PHP_INT_MAX;
        $productIdsReturn = []; 
        foreach ($productIds as $productId) {
            $promoPrice = $promoManager->hydratePromoDataExpress($productId);
            $price = round(floatval($promoPrice),2);
            if($price >= $minPrice && $price <= $maxPrice){ 
                $productIdsReturn[] = $productId;
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

        // Prepare variables
        $queryString = isset($parameters['q_str']) && $parameters['q_str']?trim($parameters['q_str']):FALSE;
        $parameterCategory = isset($parameters['category']) && $parameters['category']?trim($parameters['category']):FALSE;
        $startPrice = isset($parameters['startprice']) && $parameters['startprice']?str_replace( ',', '', trim($parameters['startprice'])):FALSE;
        $endPrice = isset($parameters['endprice']) && $parameters['endprice']?str_replace( ',', '', trim($parameters['endprice'])):FALSE; 
        $pageNumber = isset($parameters['page']) && $parameters['page']?trim($parameters['page']):FALSE;
        $sortBy = isset($parameters['sortby']) && $parameters['sortby'] ?trim($parameters['sortby']):FALSE;
        $perPage = isset($parameters['limit']) ? $parameters['limit'] : self::PER_PAGE;
        $storeKeyword = $pageNumber ? FALSE:TRUE;

        // Search Filter 
        $productIds = $searchProductService->filterProductByDefaultParameter($parameters);
        $productIds = $searchProductService->filterProductByAttributesParameter($parameters,$productIds);

        // Search for Product Query String
        $productIds = $originalOrder = $queryString?$searchProductService->filterBySearchString($productIds,$queryString,$storeKeyword):$productIds;
        $productIds = $queryString && empty($productIds) ? [] : $productIds;
        $originalOrder = $sortBy ? $productIds : $originalOrder;

        $finalizedProductIds = $startPrice ? $searchProductService->filterProductByPrice($startPrice, $endPrice, $productIds) : $productIds;

        $finalizedProductIds = !empty($originalOrder) ? array_intersect($originalOrder, $finalizedProductIds) : $finalizedProductIds;

        // total product count
        $totalCount = count($finalizedProductIds);

        $offset = intval($pageNumber) * intval($perPage);

        $paginatedProductIds = array_slice($finalizedProductIds, $offset, $perPage);

        $products = [];
        foreach ($paginatedProductIds as $productId) {
            $product = $productManager->getProductDetails($productId);
            $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                      ->getDefaultImage($productId);
            $product->directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
            $product->imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

            if($productImage != NULL){
                $product->directory = $productImage->getDirectory();
                $product->imageFileName = $productImage->getFilename();
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
                else{ 
                    return ($a->getFinalPrice() < $b->getFinalPrice()) ? -1 : 1; 
                }
            });
            $products = new ArrayCollection(iterator_to_array($iterator));
        }

        $returnArray = array(
                    'collection' => $products,
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

        foreach ($subCategory as $value) {
            $subCategoryIds = $EsCatRepository->getChildCategoryRecursive($value->getIdCat());
            $popularProducts = $EsProductRepository->getPopularItemByCategory($subCategoryIds);
            if(!empty($popularProducts)){ 
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
                $popularProducts[0] = array();
            }
            $subCategoryList[$value->getName()]['item'] = $popularProducts[0];
            $subCategoryList[$value->getName()]['slug'] = $value->getSlug();
        }

        return $subCategoryList;
    }

}
