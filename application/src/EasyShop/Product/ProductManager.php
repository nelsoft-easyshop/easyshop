<?php

namespace EasyShop\Product;

use Easyshop\Promo\PromoManager as PromoManager;
use EasyShop\ConfigLoader\ConfigLoader as ConfigLoader;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder; 
use EasyShop\Entities\EsProduct; 
use EasyShop\Entities\EsProductShippingHead; 

use Easyshop\Entities\EsProductItem;

use EasyShop\Entities\EsMemberProdcat;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Easyshop\Entities\EsProducItemLock;
use EasyShop\Entities\EsCat;

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
     * Default limit of meta desciption of the product
     */
    const PRODUCT_IS_PROMOTE = 1;

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
     * @var EasyShop\CollectionHelper\CollectionHelper
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
     * @var CI_Image_lib
     */
    private $userManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$promoManager,$collectionHelper,$configLoader, $imageLibrary, $userManager)
    {
        $this->em = $em; 
        $this->promoManager = $promoManager;
        $this->collectionHelper = $collectionHelper;
        $this->configLoader = $configLoader;
        $this->imageLibrary = $imageLibrary;
        $this->userManager = $userManager;
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
        
        $soldPrice = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                              ->getSoldPrice($productId, $product->getStartDate(), $product->getEndDate());
        $totalShippingFee = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                                            ->getShippingTotalPrice($productId);

        $product->setSoldPrice($soldPrice);
        $product->setIsFreeShipping(0 === bccomp(floatval($totalShippingFee),0));
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
    public function getProductInventory($product, $isVerbose = false, $doLockDeduction = false)
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
        
        $locks = $this->validateProductItemLock($product->getIdProduct());
        if($doLockDeduction){
            foreach($locks as $lock){
                if(isset($data[$lock['id_product_item']])){
                    $data[$lock['id_product_item']]['quantity'] -=  $lock['lock_qty'];
                    $data[$lock['id_product_item']]['quantity'] = ($data[$lock['id_product_item']]['quantity'] >= 0) ? $data[$lock['id_product_item']]['quantity'] : 0;
                }
            }
        }

        return $data;
    }
    
    
    /**
     * Checks the productItemLocks that exists for a given product
     * If lock exceeds its life time, delete it.
     *
     * @param integer $productId
     * @return mixed
     */
    public function validateProductItemLock($productId)
    {
        $productItemLocks = $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                                        ->getProductItemLockByProductId($productId);
        foreach($productItemLocks as $idx => $lock){
            $elapsedMinutes = round((time() - $lock['timestamp']->getTimestamp())/60);
            if($elapsedMinutes > $this->lockLifeSpan){
                $lockEntity =  $this->em->getRepository('EasyShop\Entities\EsProductItemLock')
                                        ->find($lock['idItemLock']);
                $this->em->remove($lockEntity);
                $this->em->flush();
                unset($lock[$idx]);
            }
        }
        
        return $productItemLocks;
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
        $username = $products->getMember()->getUsername();

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
     *  Get parent category of products uploaded by user
     *
     *  @param integer $memberId
     *
     *  @return array
     */
    public function getAllUserProductParentCategory($memberId)
    {
        $defaultCatImg = "assets/images/default_icon_small.png";
        $vendorCategories = array();

        $rawVendorCategories = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->getUserProductParentCategories($memberId);

        foreach( $rawVendorCategories as $vendorCategory ){
            if( !isset($vendorCategories[$vendorCategory['parent_cat']]) && intval($vendorCategory['parent_cat']) !== 1 ){
                $catImg = "assets/" . substr($vendorCategory['p_cat_img'],0,strrpos($vendorCategory['p_cat_img'],'.')) . "_small.png";
                if( $vendorCategory['p_cat_img'] !== "" && file_exists($catImg)){
                    $categoryImage = $catImg;
                }
                else{
                    $categoryImage = $defaultCatImg;
                }
                
                $vendorCategories[$vendorCategory['parent_cat']] = array(
                    'name' => $vendorCategory['p_cat_name'],
                    'slug' => $vendorCategory['p_cat_slug'],
                    'child_cat' => array($vendorCategory['parent_cat']),
                    'products' => array(),
                    'product_count' => 0,
                    'cat_link' => '/category/' . $vendorCategory['p_cat_slug'],
                    'cat_img' => $categoryImage,
                    'cat_type' => EsCat::CUSTOM_TYPE,
                    'isActive' => FALSE
                );
            }
            // For products whose parent is 'PARENT'
            else if( !isset($vendorCategories[$vendorCategory['parent_cat']]) && intval($vendorCategory['parent_cat']) === 1 ) {
                $vendorCategories[$vendorCategory['parent_cat']] = array(
                    'name' => 'Others',
                    'slug' => '',
                    'child_cat' => array($vendorCategory['parent_cat']),
                    'products' => array(),
                    'product_count' => 0,
                    'cat_link' => '',
                    'cat_img' => $defaultCatImg,
                    'cat_type' => EsCat::CUSTOM_TYPE,
                    'isActive' => FALSE
                );
            }
            $vendorCategories[$vendorCategory['parent_cat']]['child_cat'][] = $vendorCategory['cat_id'];
            $vendorCategories[$vendorCategory['parent_cat']]['product_count'] += $vendorCategory['prd_count'];
        }

        // Move OTHERS at the end of array - unset and reset only to push at end of array
        if(isset($vendorCategories[1])){
            $temp = $vendorCategories[1];
            unset($vendorCategories[1]);
            $vendorCategories[1] = $temp;
        }

        return $vendorCategories;
    }

    /**
     *  Fetch custom categories of $memberId
     *
     *  @param integer $memberId
     *
     *  @return array
     */
    public function getAllUserProductCustomCategory($memberId)
    {
        $customCategories = array();
        $arrCustomCategories = $this->em->getRepository("EasyShop\Entities\EsMemberCat")
                                    ->getCustomCategoriesArray($memberId);

        foreach( $arrCustomCategories as $customCat ){
            $customCategories[$customCat['id_memcat']] = array(
                'name' => $customCat['cat_name'],
                'is_featured' => $customCat['is_featured'],
                'child_cat' => array($customCat['id_memcat']),
                'products' => array(),
                'cat_type' => 1,
                'isActive' => FALSE
            );
        }

        return $customCategories;
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

        $products = $productRepo->getRecommendedProducts($productId, $product->getCat(), $limit);
        
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
     *  Fetch products under parent category, based on child cat ids ($arrCatId)
     *
     *  @param integer $memberId
     *  @param array $arrCatId 
     *
     *  @return array - filter count of products and array of product objects
     */
    public function getVendorDefaultCategoryAndProducts($memberId, $arrCatId, $catType="default", $productLimit = 12, $page = 0, $orderBy = array("clickcount"=>"DESC"), $condition = "", $lprice = "", $uprice ="")
    {
        // Container for products fetched
        $categoryProducts = array();

        // Condition parameters passed
        $page = intval($page) <= 0 ? 0 : (intval($page)-1) * $productLimit;
        $condition = strval($condition);

        $lprice = str_replace(",", "", (string)$lprice);
        $uprice = str_replace(",", "", (string)$uprice);

        // Identify which query to use in fetching product Ids and product count
        if($condition === "" && $lprice === "" && $uprice === ""){
            switch( $catType ){
                case "custom":
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                                   ->getPagedCustomCategoryProducts($memberId, $arrCatId, $productLimit, $page, $orderBy);
                    $productCount = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                             ->countCustomCategoryProducts($memberId, $arrCatId);
                    break;
                default:
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                                   ->getPagedNotCustomCategorizedProducts($memberId, $arrCatId, $productLimit, $page, $orderBy);
                    $productCount = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                             ->countNotCustomCategorizedProducts($memberId, $arrCatId);
                    break;
            }            
            $isAllProductIds = FALSE;
        }
        else{
            switch( $catType ){
                case "custom":
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                                   ->getAllCustomCategoryProducts($memberId, $arrCatId);
                    break;
                default:
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                                   ->getAllNotCustomCategorizedProducts($memberId, $arrCatId);
                    break;
            }
            $isAllProductIds = TRUE;
        }
        
        // Fetch product object and append image
        foreach($categoryProductIds as $productId){
            $product = $this->getProductDetails($productId);
            $objImage = $this->em->getRepository("EasyShop\Entities\EsProductImage")
                                ->getDefaultImage($productId);
            if(!$objImage){
                $product->directory = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                $product->imageFileName = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_FILE;
            }
            else{
                $product->directory = $objImage->getDirectory();
                $product->imageFileName = $objImage->getFilename();
            }
            $categoryProducts[] = $product;
        }

        // IF FILTER CONDITIONS ARE PROVIDED
        if($isAllProductIds){
            // Flag for triggering andWhere in criteria
            $hasWhere = FALSE;

            $arrCollectionProducts = new ArrayCollection($categoryProducts);
            $criteria = new Criteria();

            // Start appending filter conditions
            if($condition !== ""){
                $criteria->where(Criteria::expr()->eq("condition", $condition));
                $hasWhere = TRUE;
            }

            if($lprice !== ""){
                if(!$hasWhere){
                    $criteria->where(Criteria::expr()->gte("finalPrice", $lprice));
                    $hasWhere = TRUE;
                }
                else{
                    $criteria->andWhere(Criteria::expr()->gte("finalPrice", $lprice));
                }
            }

            if($uprice !== ""){
                if(!$hasWhere){
                    $criteria->where(Criteria::expr()->lte("finalPrice", $uprice));
                    $hasWhere = TRUE;
                }
                else{
                    $criteria->andWhere(Criteria::expr()->lte("finalPrice", $uprice));
                }   
            }

            // Generate orderby criteria - Implemented to handle multiple conditions
            $criteriaOrderBy = array();
            foreach($orderBy as $sortBy=>$sort){
                if($sort === "ASC"){
                    $criteriaOrderBy[$sortBy] = Criteria::ASC;
                }
                else{
                    $criteriaOrderBy[$sortBy] = Criteria::DESC;
                }
            }
            $criteria->orderBy($criteriaOrderBy);

            // Count product result after filtering
            $productCount = count($arrCollectionProducts->matching($criteria));

            // Filter number of results (pagination)
            $criteria->setFirstResult($page)
                    ->setMaxResults($productLimit);

            // Push products to be displayed
            $categoryProducts = $arrCollectionProducts->matching($criteria);
        }
        
        // Generate result array
        $result = array(
            'products' => $categoryProducts,
            'filtered_product_count' => $productCount
        );

        return $result;
    }

    /**
     * Creates directories, checks if the passed image name exists in the admin folder
     * @param int $imagesId
     * @return JSONP
     */ 
    public function imageresize($imageDirectory, $newDirectory, $dimension)
    {
        
        $config['image_library'] = 'GD2';
        $config['source_image'] = $imageDirectory;
        $config['maintain_ratio'] = true;
        $config['quality'] = '85%';
        $config['new_image'] = $newDirectory;
        $config['width'] = $dimension[0];
        $config['height'] = $dimension[1]; 

        $this->imageLibrary->initialize($config); 
        $this->imageLibrary->resize();
        $this->imageLibrary->clear();        
    } 

    /**
     * Generates slugs 
     * @param string $title
     * @return string
     */ 
    public function generateSlug($title)   
    {
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                ->findBy(['slug' => $title]);

        $cnt = count($product);
        if($cnt > 0) {
            $slugGenerate = $title."-".$cnt++;
        }
        else {
            $slugGenerate = $title;
        }
        $checkIfSlugExist = $this->em->getRepository('EasyShop\Entities\EsProduct')
                ->findBy(['slug' => $slugGenerate]);

        if(count($checkIfSlugExist) > 0 ){
            foreach($checkIfSlugExist as $newSlugs){
                $slugGenerate = $slugGenerate."-".$newSlugs->getIdProduct();
            }
        }
        return $slugGenerate;
    }
    
    /**
     * Gets a default attribute for a particular product
     * The first available attribute combination will be used
     *
     * @param int $productId
     * @return mixed
     */
    public function getProductDefaultAttribute($productId)
    {
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->find($productId);
        $defaultAttributes = array();                    
        if($product){
            $inventoryDetails = $this->getProductInventory($product);
            $defaultInventory = array();
            foreach($inventoryDetails as $inventory){
                if($inventory['quantity'] > 0){
                    $defaultInventory = $inventory;
                    break;
                }
            }

            $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->getProductAttributeDetailByName($productId);
            /**
             *  If the default quantity has been set
             */
            if( intval($defaultInventory['product_attribute_ids'][0]['id']) === 0 &&
                intval($defaultInventory['product_attribute_ids'][0]['is_other']) === 0)
            {
                foreach($attributes as $attributeIndex => $attribute){
                    if(!array_key_exists($attribute['attr_name'],$defaultAttributes)){
                        $defaultAttributes[$attribute['attr_name']] = $attribute;
                    }
                }
            }
            else{
                foreach($defaultInventory['product_attribute_ids'] as $productAttributeId){
                    foreach($attributes as $attributeIndex => $attribute){
                        if(intval($productAttributeId['id']) === intval($attribute['attr_id']) &&
                        intval($productAttributeId['is_other']) === intval($attribute['is_other'])){
                            array_push($defaultAttributes, $attribute);
                            unset($attributes[$attributeIndex]);
                        } 
                    }
                }
            }
           
        }
        return $defaultAttributes;
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

        // check if totally free shipping 
        $isFreeShippingNationwide = TRUE;
        foreach ($shippingDetails as $value) {
            if( intval($value['location_id']) !== \EasyShop\Entities\EsLocationLookup::PHILIPPINES_LOCATION_ID
                || bccomp(floatval($value['price']),0) !== 0){

                $isFreeShippingNationwide = FALSE;
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
        // get combination quantity
        $productInventory = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                     ->getProductInventoryDetail($productId);

         // get product shipping location
        $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                    ->getShippingDetailsByProductId($productId);

        $productCombinationAvailable = [];
        foreach ($productInventory as $value) {
            if(!array_key_exists($value['id_product_item'],$productCombinationAvailable)){

                $locationArray = [];
                foreach ($shippingDetails as $shipKey => $shipValue) {
                    if((int)$shipValue['product_item_id'] === (int)$value['id_product_item']){
                        $locationArray[] = [
                                'location_id' => $shipValue['location_id'],
                                'price' => $shipValue['price'],
                            ];
                    }
                }

                $productCombinationAvailable[$value['id_product_item']] = [
                    'quantity' => $value['quantity'],
                    'product_attribute_ids' => [$value['product_attr_id']],
                    'location' => $locationArray,
                ];
            }
            else{
                $productCombinationAvailable[$value['id_product_item']]['product_attribute_ids'][] = $value['product_attr_id'];
            }
        }

        // check if combination available
        $noMoreSelection = "";
        if((count($productInventory) === 1 && (int)$productInventory[0]['product_attr_id'] === 0) 
            || count($productCombinationAvailable) === 1 ){
            $noMoreSelection = $productInventory[0]['id_product_item'];
        }

        return [
                'noMoreSelection' => $noMoreSelection,
                'productCombinationAvailable' => $productCombinationAvailable
            ];
    }
}

