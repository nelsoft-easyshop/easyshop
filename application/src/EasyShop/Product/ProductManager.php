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
     * User manager instance
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$promoManager,$collectionHelper,$configLoader, $imageLibrary,$userManager)
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
     * @param integer $productId
     * @return Product
     */
    public function getProductDetails($productId)
    {
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->find($productId);
        $soldPrice = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                              ->getSoldPrice($productId, $product->getStartDate(), $product->getEndDate());
        $totalShippingFee = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                                            ->getShippingTotalPrice($productId);
        $product->setSoldPrice($soldPrice);
        $product->setIsFreeShipping($totalShippingFee === 0);        
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
     * Apply discounted price to product
     *
     * @param  array  $products [description]
     * @return mixed
     */
    public function discountProducts($products)
    { 
        foreach ($products as $key => $value) {  
            $resultObject = $this->getProductDetails($value->getIdProduct());
        } 

        return $products;
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
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->find($productId);

        $queryBuilder = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->createQueryBuilder("p")
                                ->select("p")
                                ->where('p.cat = :category')
                                ->andWhere("p.idProduct != :productId")
                                ->andWhere("p.isDraft = :isDraft")
                                ->andWhere("p.isDelete = :isDelete")
                                ->setParameter('productId',$product->getIdProduct())
                                ->setParameter('category',$product->getCat())
                                ->setParameter('isDraft',0)
                                ->setParameter('isDelete',0)
                                ->orderBy('p.clickcount', 'DESC')
                                ->getQuery();
        if($limit){
            $queryBuilder->setMaxResults($limit);
        }

        $products = $queryBuilder->getResult();
        
        foreach($products as $key => $product){
            $products[$key] = $this->getProductDetails($product->getIdProduct());
        }
        
        return $products;
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
}

