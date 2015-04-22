<?php

namespace EasyShop\Product;

use Easyshop\Promo\PromoManager as PromoManager;
use EasyShop\ConfigLoader\ConfigLoader as ConfigLoader;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsProductShippingHead; 
use EasyShop\Entities\EsProductImage as EsProductImage; 
use EasyShop\Entities\EsOrder; 
use EasyShop\Entities\EsProduct as EsProduct;
use Easyshop\Entities\EsProductItem;
use EasyShop\Entities\EsMemberProdcat;
use Easyshop\Entities\EsProducItemLock;
use EasyShop\Entities\EsCat;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Product Manager Class
 *
 * @author Ryan Vasquez
 * @author stephenjanz
 */
class ProductManager
{

    /**
     * Newness Limit in days 
     *
     */
    const NEWNESS_LIMIT = 14;

    /**
     * Default Number of recommended products
     *
     */
    const RECOMMENDED_PRODUCT_COUNT = 15;

    /**
     * Default limit of meta desciption of the product
     */
    const PRODUCT_META_DESCRIPTION_LIMIT = 155;

    /**
     * Default result product count in dashboard
     */
    const PRODUCT_COUNT_DASHBOARD = 10;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Product Item Lock life time in minutes
     *
     * @var integer
     */
    private $lockLifeSpan = 10;

    /**
     * Promo Manager instance
     *
     * @var \EasyShop\Promo\PromoManager
     */
    private $promoManager;

    /**
     * Collection instance
     *
     * @var EasyShop\CollectionHelper\CollectionHelper
     */
    private $collectionHelper;
    

    /**
     * Codeigniter Config Loader
     *
     * @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;


    /**
     * Image Library Dipendency Injection
     *
     * @var CI_Image_lib
     */
    private $imageLibrary;

    /**
     * User Manager Instance
     *
     * @var Easyshop\User\UserManager
     */
    private $userManager;

    /**
     * String utility helper
     *
     */
    private $stringUtility;    


    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,
                                $promoManager,
                                $collectionHelper,
                                $configLoader,
                                $imageLibrary,
                                $userManager,
                                $stringUtility)
    {
        $this->em = $em; 
        $this->promoManager = $promoManager;
        $this->collectionHelper = $collectionHelper;
        $this->configLoader = $configLoader;
        $this->imageLibrary = $imageLibrary;
        $this->userManager = $userManager;
        $this->stringUtility = $stringUtility;
    }

    /**
     * Returns the product object with hydrated virtual fields
     *
     * @param integer or EasyShop/Entities/EsProduct $productArgument
     * @return Product
     */
    public function getProductDetails($productArgument)
    {
        if(is_numeric($productArgument)){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->find($productArgument);
            $productId = $productArgument;
        }
        else if(is_object($productArgument)){
            $product = $productArgument;
            $productId = $productArgument->getIdProduct();
        }
        else{
            return false;
        }

        if(!$product){
            return false;
        }
        
        $soldPrice = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                              ->getSoldPrice($productId, $product->getStartDate(), $product->getEndDate());
        $totalShippingFee = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                                            ->getShippingTotalPrice($productId);

        $product->setSoldPrice($soldPrice);
        $product->setIsFreeShipping(
            0 === bccomp(floatval($totalShippingFee),0) 
            && $this->isListingOnly($product) === false
        );
        $product->setIsNew($this->isProductNew($product));
        $product->setDefaultImage($this->em->getRepository('EasyShop\Entities\EsProductImage')
                                           ->getDefaultImage($product->getIdProduct()));
        
        $this->promoManager->hydratePromoData($product);

        return $product;
    }

    /**
     * Returns the inventory of a product
     *
     * @param Product $product
     * @param bool $isVerbose 
     * @param bool $doLockDeduction : If true, locked items will also be deducted from the total availability
     *
     */
    public function getProductInventory($product, $isVerbose = false)
    {
        $promoQuantityLimit = $this->promoManager->getPromoQuantityLimit($product);
        $inventoryDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->getProductInventoryDetail($product->getIdProduct(), $isVerbose);

        /**
         * Organize data result set
         */
        $data = array();
        foreach($inventoryDetails as $inventoryDetail){
            if(!array_key_exists($inventoryDetail['id_product_item'],  $data)){
                $data[$inventoryDetail['id_product_item']] = array();
                $data[$inventoryDetail['id_product_item']]['quantity'] = ($inventoryDetail['quantity'] <= $promoQuantityLimit) ? $inventoryDetail['quantity'] : $promoQuantityLimit;
                $data[$inventoryDetail['id_product_item']]['product_attribute_ids'] = array();
                $data[$inventoryDetail['id_product_item']]['attr_lookuplist_item_id'] = array();
                $data[$inventoryDetail['id_product_item']]['attr_name'] = array();
                $data[$inventoryDetail['id_product_item']]['is_default'] = true;
            }
            array_push($data[$inventoryDetail['id_product_item']]['product_attribute_ids'], array('id'=> $inventoryDetail['product_attr_id'], 'is_other'=> $inventoryDetail['is_other']));
  
            if(count($data[$inventoryDetail['id_product_item']]['product_attribute_ids']) > 1   
                || intval($inventoryDetail['product_attr_id']) !== 0
                || intval($inventoryDetail['is_other']) !== 0)
            {
                $data[$inventoryDetail['id_product_item']]['is_default'] = false;
            }
            
            if($isVerbose){
                array_push($data[$inventoryDetail['id_product_item']]['attr_lookuplist_item_id'], $inventoryDetail['attr_lookuplist_item_id']);
                array_push($data[$inventoryDetail['id_product_item']]['attr_name'], $inventoryDetail['attr_value']);
            }
        }

        /**
         * Reduce lock quantity on original quantity
         */
        $productItemLocks = $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                                     ->getProductItemLockByProductId($product->getIdProduct());

        foreach($productItemLocks as $lock){
            if(isset($data[$lock['idProductItem']])){
                $data[$lock['idProductItem']]['quantity'] -=  $lock['lock_qty'];
                $data[$lock['idProductItem']]['quantity'] = ($data[$lock['idProductItem']]['quantity'] >= 0) ? $data[$lock['idProductItem']]['quantity'] : 0;
            }
        }

        return $data;
    }

    /**
     * function that will get all possible keyword tied on selected product
     * @return boolean
     */
    public function generateSearchKeywords($productId)
    {
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->find($productId);
        
        $category = $products->getCat()->getIdCat();
        $brand = $products->getBrand()->getName();
        $username = $products->getMember()->getStoreName();

        $categoryParent = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getParentCategoryRecursive($category);

        $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->getAttributesByProductIds($productId);

        $organizedAttributes = $this->collectionHelper->organizeArray($attributes);

        $attributesString = "";
        foreach ($organizedAttributes as $key => $value) {
            $attributesString .= $key.' ';
            foreach ($value as $attrValue) {
                $attributesString .= $attrValue.' ';
            }
        }

        $categoryString = "";
        foreach ($categoryParent as $key => $value) {
            $categoryString .= $value['name'].' ';
        }

        $arrayKeyword = array(
                        $products->getName(),
                        $products->getKeywords(),
                        $products->getCatOtherName(),
                        $brand,
                        $username,
                        $attributesString,
                        $categoryString
                    );

        $finalSearchKeyword = preg_replace('/\s+/', ' ',implode(' ', $arrayKeyword));

        $products->setSearchKeyword($finalSearchKeyword); 
        $this->em->flush();

        return true;
    }

    /**
     *  Classify product under custom category
     *  Pass an array of productIDs for batch updating.
     *
     *  @param array $prodId
     *  @param integer $catId
     */
    public function setProductCustomCategory($prodId, $catId, $memberId)
    {
        $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $category = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                            ->findOneBy(array(
                                            'idMemcat' => $catId,
                                            'member' => $memberObj
                                        ));
        if( !is_array($prodId) ){
            $prodId = array($prodId);
        }

        foreach($prodId as $productId){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(array(
                                                'idProduct' => $productId,
                                                'member' => $memberObj
                                            ));
            $memProd = new EsMemberProdcat();
            $memProd->setMemcat($category)
                    ->setProduct($product)
                    ->setCreatedDate(date_create());
            $this->em->persist($memProd);
        }

        $this->em->flush();
    }

   
    /**
     * Updates quantity of a particular product
     * @return bool True on successful update
     */
    public function deductProductQuantity($productId,$itemId,$qty)
    {

        $item = $this->em->getRepository('EasyShop\Entities\EsProductItem')
                            ->findOneBy(['product' => $productId,'idProductItem' => $itemId]);

        $item->setQuantity($item->getQuantity() - $qty);
        $this->em->flush();
        return true;
    }

    /**
     * Updates soldout status of a particular product
     * @return bool True on successful update
     */
    public function updateSoldoutStatus($productId)
    {
        $item = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                 ->find($productId);

        $inventory = $this->getProductInventory($item);

        $isSoldOut = intval(reset($inventory)['quantity']) <= 0 ? true : false;
        $item->setIsSoldOut($isSoldOut);
        $this->em->flush();
        return true;
    }
    
    /**
     * Determines if a product is new
     *
     * @param EasyShop\Entities\EsProduct $product
     * @return bool
     */
    public function isProductNew($product)
    {
        if(is_string($product->getLastModifiedDate())){
            $sql = "
                SELECT 
                    p.lastmodifieddate
                FROM 
                    EasyShop\Entities\EsProduct p
                WHERE p.idProduct = :productId
            ";
            $query = $this->em->createQuery($sql)
                                ->setParameter('productId', $product->getIdProduct());
            $lastModifiedDate = $query->getResult()[0]['lastmodifieddate']->getTimestamp();
        }
        else{
            $lastModifiedDate = $product->getLastModifiedDate()
                                        ->getTimestamp();
        }
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->getTimestamp();
        $datediff = $dateNow - $lastModifiedDate;
        $daysDifferential = floor($datediff/(60*60*24));
        return $daysDifferential <= self::NEWNESS_LIMIT;
    }

    /**
     * Returns the recommended products list for a certain product
     *
     * @param integer $productId
     * @param integer $limit
     * @return \EasyShop\Entities\EsProduct
     */
    public function getRecommendedProducts($productId, $limit = null)
    {    
        $productImageRepo =  $this->em->getRepository('EasyShop\Entities\EsProductImage');
        $productRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $product = $productRepo->find($productId);
        $products = $productRepo->getRecommendedProducts($productId, [$product->getCat()->getIdCat()], $limit);
        $productCount = count($products);
        $maxRecommended = self::RECOMMENDED_PRODUCT_COUNT;

        if($productCount < $maxRecommended){
            $lackCount = $maxRecommended - $productCount;
            $productIdsNotIncluded = []; 
            $productIdsNotIncluded[] = $productId;
            $categoryParent = $this->em->getRepository('EasyShop\Entities\EsCat')
                                       ->getAncestorsWithNestedSet($product->getCat()->getIdCat());
            if(empty($categoryParent) === false){
                foreach ($products as $product) {
                    $productIdsNotIncluded[] = $product->getIdProduct();
                }
                $additionalProduct = $productRepo->getRecommendedProducts($productIdsNotIncluded, 
                                                                          $categoryParent, 
                                                                          $lackCount);

                $products = array_merge($products, $additionalProduct);
            }
        }

        $detailedProducts = [];
        foreach($products as $key => $product){ 
            $eachProduct = $this->getProductDetails($product->getIdProduct());
            $eachProduct->ownerAvatar = $this->userManager
                                             ->getUserImage($product->getMember()->getIdMember());

            $eachProduct->directory = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
            $eachProduct->imageFileName = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_FILE;

            if($eachProduct->getDefaultImage()){
                $eachProduct->directory = $eachProduct->getDefaultImage()->getDirectory();
                $eachProduct->imageFileName = $eachProduct->getDefaultImage()->getFilename();
                $secondaryImage = $productImageRepo->getSecondaryImage($product->getIdProduct());

                if($secondaryImage){
                    $eachProduct->secondaryImage = $secondaryImage->getFilename(); 
                }
            }

            $detailedProducts[$key] = $eachProduct;
        }

        return $detailedProducts;
    }

  
    /**
     * Generates slugs 
     * @param string $title
     * @return string
     */ 
    public function generateSlug($title)   
    {
        $cleanedTitle = $this->stringUtility->cleanString(strtolower($title));

        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('slug', 'slug');
        $sql = "SELECT slug FROM es_product WHERE slug LIKE :productSlug ";
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('productSlug', $cleanedTitle.'%'); 
        $existingSlugs = array_map('current', $query->getResult());

        if(count($existingSlugs) > 0) {
            $counter = 0;
            while (in_array($cleanedTitle ."-". ++$counter, $existingSlugs));
            $cleanedTitle .= "-". $counter;
        }

        return $cleanedTitle;
    }
    
    /**
     * Gets a default attribute for a particular product
     * The first available attribute combination will be used
     *
     * @param int $productId
     * @return mixed
     */
    public function getProductDefaultAttributes($productId)
    {
        $response = [
            'hasStock' => false,
            'defaultAttributes' => [],
        ];    
    
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->find($productId);                
        if($product){
            $inventoryDetails = $this->getProductInventory($product);
            $defaultInventory = [];
            foreach($inventoryDetails as $inventory){
                if((int)$inventory['quantity'] > 0){
                    $defaultInventory = $inventory;
                    $response['hasStock'] = true;
                    break;
                }
            }
            $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->getProductAttributeDetailByName($productId);

            if(empty($attributes) === false){
                if( (int)$defaultInventory['product_attribute_ids'][0]['id'] === 0 &&
                    (int)$defaultInventory['product_attribute_ids'][0]['is_other'] === 0)
                {   
                    /**
                     * Condition for products with no quantity-combination but with
                     * product attributes. This is the case for products with attributes
                     * but only has one global quantity value.
                     */
                    foreach($attributes as $attribute){
                        if(!array_key_exists($attribute['attr_name'],$response['defaultAttributes'])){
                            $response['defaultAttributes'][$attribute['attr_name']] = $attribute;
                        }
                    }
                }
                else{
                    foreach($defaultInventory['product_attribute_ids'] as $productAttributeId){
                        foreach($attributes as $attributeIndex => $attribute){
                            if((int)$productAttributeId['id'] === (int)$attribute['attr_id'] &&
                            (int)$productAttributeId['is_other'] === (int)$attribute['is_other']){ 
                                $response['defaultAttributes'][] = $attribute;
                                unset($attributes[$attributeIndex]);
                            } 
                        }
                    }
                }
            }
           
        }

        return $response;
    }
    
    /**
     * Determines if a product is posted as a listing only
     *
     * @param EasyShop\Entities\EsProduct $product
     * @return bool
     */
    public function isListingOnly($product)
    {
        $isListingOnly = false;
        if($product->getIsMeetUp()){
            $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                ->getShippingDetailsByProductId($product->getIdProduct());
            if(count($shippingDetails) === 0){
                $isListingOnly = true;
            }
        }

        return $isListingOnly;
    }

    /**
     *  Bulk Restore products in memberpage
     *
     *  @param array $arrProductId - product Ids
     *  @param integer $memberId
     *
     *  @return boolean
     */
    public function editBulkIsDelete($arrProductId, $memberId, $selector = "restore")
    {
        $arrayProductId = is_array($arrProductId) ? $arrProductId : array($arrProductId);
        $objMember = $this->em->find("EasyShop\Entities\EsMember", $memberId);

        switch( $selector ){
            case "restore":
                $isDeleteVal = EsProduct::ACTIVE;
                break;
            case "delete":
                $isDeleteVal = EsProduct::DELETE;
                break;
            case "full_delete":
                $isDeleteVal = EsProduct::FULL_DELETE;
                break;
            default:
                $isDeleteVal = EsProduct::ACTIVE;
                break;
        }

        foreach($arrayProductId as $productId){
            $objProduct = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                   ->findOneBy(array(
                                        "idProduct" => $productId,
                                        "member" => $objMember
                                    ));

            $objProduct->setIsDelete($isDeleteVal)
                       ->setLastmodifieddate(date_create());

            $this->em->persist($objProduct);
        }

        $this->em->flush();

        return true;
    }
    
    /*
     * Check if the product is free shipping nationwide
     * @param  integer  $productId
     * @return boolean
     */
    public function isFreeShippingNationwide($productId)
    {
        $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                    ->getShippingDetailsByProductId($productId);

        $isFreeShippingNationwide = true;
        foreach ($shippingDetails as $value) {
            if((int)$value['location_id'] !== \EasyShop\Entities\EsLocationLookup::PHILIPPINES_LOCATION_ID
                || bccomp((float)$value['price'],0) !== 0){

                $isFreeShippingNationwide = false;
                break;
            }
        }

        return $isFreeShippingNationwide;
    }

    /**
     * Return the possible combination of the given product
     * @param  integer $productId
     * @return mixed
     */
    public function getProductCombinationAvailable($productId)
    {
        $product = $this->getProductDetails($productId);
        $productInventoryDetail = $this->getProductInventory($product);
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                    ->getShippingDetailsByProductId($productId);

        $productCombinationAvailable = [];
        foreach ($productInventoryDetail as $itemId => $detail) {
            unset($detail['attr_lookuplist_item_id']);
            unset($detail['attr_name']);
            unset($detail['is_default']);
            $productAttributesId = [];
            foreach ($detail['product_attribute_ids'] as $attributes) {
                $productAttributesId[] = $attributes['id'];
            }
            $locationArray = [];
            foreach ($shippingDetails as $shipKey => $shipValue) {
                if((int)$shipValue['product_item_id'] === (int)$itemId){
                    $locationArray[] = [
                            'location_id' => $shipValue['location_id'],
                            'price' => $shipValue['price'],
                        ];
                }
            }
            $detail['product_attribute_ids'] = $productAttributesId;
            $detail['location'] = $locationArray;
            $productCombinationAvailable[$itemId] = $detail;
        }

        $productAttributeDetails = $esProductRepo->getProductAttributeDetailByName($productId);
        $productAttributes = $this->collectionHelper->organizeArray($productAttributeDetails,true,true);

        $attrCount = 0;
        foreach ($productAttributes as $attribute) {
            if(count($attribute) === 1){
                $attrCount ++;
            }
        }

        $noMoreSelection = "";
        if(count($productCombinationAvailable) === 1 && $attrCount === count($productAttributes)){
            $noMoreSelection = key($productCombinationAvailable);
        }

        $needToSelect = false;
        if(count($productCombinationAvailable) === 1 && $attrCount !== count($productAttributes)){
            $needToSelect = true;
        }

        return [
                'noMoreSelection' => $noMoreSelection,
                'productCombinationAvailable' => $productCombinationAvailable,
                'needToSelect' => $needToSelect,
            ];
    }

    /**
     * Get all active product of specific user
     * @param  integer  $memberId
     * @param  integer $offset
     * @return objec
     */
    public function getProductsByUser($memberId,
                                      $isDelete = [EsProduct::ACTIVE],
                                      $isDraft = [EsProduct::ACTIVE],
                                      $offset = 0,
                                      $searchString = "",
                                      $sortString = "")
    {
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        switch( $sortString ){
            case "lastmodified":
                $orderByColumn = "p.lastmodifieddate";
                break;
            case "new": 
                $orderByColumn = "p.createddate";
                break;
            default: 
                $orderByColumn = "p.idProduct";
                break;
        }

        $resultProducts = $esProductRepo->getUserProducts($memberId,
                                                          $isDelete,
                                                          $isDraft,
                                                          $offset,
                                                          self::PRODUCT_COUNT_DASHBOARD,
                                                          $searchString,
                                                          $orderByColumn);
        $products = [];
        foreach ($resultProducts as $resultProduct) {
            $product = $this->getProductDetails($resultProduct);
            $product->directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
            $product->imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

            if($product->getDefaultImage()){
                $product->directory = $product->getDefaultImage()->getDirectory();
                $product->imageFileName = $product->getDefaultImage()->getFilename();
            }

            $product->rating = $esProductRepo->getProductAverageRating($product->getIdProduct());
            $product->reviewCount = $esProductRepo->getProductReviewCount($product->getIdProduct());
            $product->availableStock = $esProductRepo->getProductAvailableStocks($product->getIdProduct());
            $product->soldCount = $esProductRepo->getSoldProductCount($product->getIdProduct());
            $productAttributes = $esProductRepo->getAttributesByProductIds([$product->getIdProduct()]);
            $product->attributes = $this->collectionHelper->organizeArray($productAttributes);

            if ( strlen(trim($product->getCatOtherName())) <= 0 ){
                $product->category = trim($product->getCat()->getName());
            }
            else{
                $product->category = trim($product->getCatOtherName());
            }
            
            $products[] = $product;
        }

        return $products;
    }

    /**
     * Update is_delete status of individual product
     * @param  integer $productId
     * @param  integer $memberId
     * @param  integer $isDeleteStatus
     * @return boolean
     */
    public function updateIsDeleteStatus($productId, $memberId, $isDeleteStatus)
    {
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')->find($productId);

        if($product && $product->getMember()->getIdMember() === (int) $memberId){
            $product->setIsDelete($isDeleteStatus); 
            $product->setLastmodifieddate(date_create());
            $this->em->flush();
            return true;
        }

        return false;
    }

    /**
     * Get prodcut additional information to display on product details based on attributes
     * @param  array $productAttributes [description]
     * @return array
     */
    public function separateAttributesOptions($productAttributes)
    {   
        $additionalInformation = [];
        foreach ($productAttributes as $headKey => $headValue) {
            if(count($headValue) === 1){
                $additionalInformation[] = ucfirst($headValue[0]['attr_name']) .' : '. ucfirst($headValue[0]['attr_value']);
                if((int)$headValue[0]['datatype_id'] !== \EasyShop\Entities\EsDatatype::CHECKBOX_DATA_TYPE && $headValue[0]['type'] === "specific"){
                    unset($productAttributes[$headKey]);
                }
            }
            else{
                foreach ($headValue as $key => $value) {
                    if((int)$value['datatype_id'] !== \EasyShop\Entities\EsDatatype::CHECKBOX_DATA_TYPE && $value['type'] === "specific" ){
                        $additionalInformation[] = ucfirst($value['attr_name']) .' : '. ucfirst($value['attr_value']);
                        unset($productAttributes[$headKey][$key]);
                    }
                    if(empty($productAttributes[$headKey])){
                        unset($productAttributes[$headKey]);
                    }
                }
            }
        }

        return [
            'additionalInformation'=> $additionalInformation,
            'productOptions' => $productAttributes
        ];
    }

    public function increaseClickCount($product, $memberId)
    {
        $numberOfViewsToday = $this->em->getRepository('EasyShop\Entities\EsProductHistoryView')
                                ->getCountProductViewsByMemberToday($product->getIdProduct(), $memberId);

        if((int)$memberId !== $product->getMember()->getIdMember()
            && $numberOfViewsToday <= 0){
            $product->setClickcount($product->getClickcount() + 1);
            $this->em->flush();
            return true;
        }

        return false;
    }

    /**
     * Determines if a product is active or not
     *
     * @param EasyShop\Entities\EsProduct
     * @return boolean
     */
    public function isProductActive($product)
    {
        if(!$product){
            return false;
        }

        $member = $product->getMember();

        $isNotDeleted = (int)$product->getIsDelete() === \EasyShop\Entities\EsProduct::ACTIVE;
        $isNotDrafted = !$product->getIsDraft();
        $isMemberNotBanned = !$member->getIsBanned();
        $isMemberActive = $member->getIsActive();
        
        return $isNotDeleted && $isNotDrafted && $isMemberNotBanned && $isMemberActive;
    }
    

    /**
     * Get random products from different users
     *
     * @param integer[] $memberIdArray
     * @param integer $limit
     * @return EasyShop\Entities\EsProduct[]
     */
    public function getRandomProductsFromUsers($memberIdArray, $limit = 10)
    {
        if(is_int($memberIdArray)){
            $memberIdArray = [ $memberIdArray ];
        }
        
        $numberOfMembers = count($memberIdArray);
        if($numberOfMembers === 0){
            return [];
        }
        
        $productFromEachSeller = $limit/$numberOfMembers;
        $productFromEachSeller = ceil($productFromEachSeller);
        $productFromEachSeller = $productFromEachSeller < 1 ? 1 : $productFromEachSeller;

        $productResults = $this->em->getRepository('EasyShop\Entities\EsProduct')
                               ->getRandomProductsFromUsers($memberIdArray, $productFromEachSeller);

        $productIds = [];
        $memberIdsWithProducts = [];
        
        foreach($productResults as $result){
            $productIds[] = $result['id_product'];
            if(!in_array($result['member_id'], $memberIdsWithProducts)){
                $memberIdsWithProducts[] = $result['member_id'];
            }
        }

        $numberOfFoundProducts = count($productResults);
        if($numberOfFoundProducts < $limit){
            $numberOfProductsToFill =  $limit - $numberOfFoundProducts;
            $numberOfMembersWithProducts =  count($memberIdsWithProducts) ;
            if($numberOfMembersWithProducts > 0){
                $productFromEachSeller = $numberOfProductsToFill/$numberOfMembersWithProducts;
                $productFromEachSeller = ceil($productFromEachSeller);
                $productFromEachSeller = $productFromEachSeller < 1 ? 1 : $productFromEachSeller;
                $fillerProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                       ->getRandomProductsFromUsers($memberIdsWithProducts, $productFromEachSeller, $productIds);
                foreach($fillerProducts as $fillerProduct){
                    $productIds[] = $fillerProduct['id_product'];
                }
            } 
        }
        
        if(count($productIds) > $limit){
            shuffle($productIds);
            $productIds = array_splice($productIds, 0, $limit);
           
        }

        $products = []; 
        if(!empty($productIds)){
            $qb = $this->em->createQueryBuilder();
            $products = $qb->select('p')
                        ->from('EasyShop\Entities\EsProduct','p') 
                        ->where($qb->expr()->in('p.idProduct', $productIds) ) 
                        ->getQuery()
                        ->getResult();
        }

        return $products;
    }
}

