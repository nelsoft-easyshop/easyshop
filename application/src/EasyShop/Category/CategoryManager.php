<?php

namespace EasyShop\Category;

use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsCat;

/**
 *  CategoryManager class
 *
 *  @author Ryan Vasquez
 *  @author stephenjanz
 *  @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class CategoryManager
{
    
    /**
     * Search type
     *
     * @var integer
     */
    const CATEGORY_SEARCH_TYPE = 0;
    
    /**
     * Non search category type 
     *
     * @var integer
     */
    const CATEGORY_NONSEARCH_TYPE = 1;

    
    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Codeigniter Config Loader
     *
     * @var EasyShop\CollectionHelper\CollectionHelper
     */
    private $configLoader;
    
    /**
     * Product Managaer
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    
    /**
     * Promo Manager
     *
     * @var EasyShop\Product\PromoManager
     */
    private $promoManager;
    
    /**
     * Sort Utlity
     *
     * @var EasyShop\Utility\SortUtility
     */
    private $sortUtility;
    
    
    /**
     * String utility
     *
     * @var EasyShop\Utility\StringUtility
     */
    private $stringUtility;
    
    /**
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($configLoader, $em, $productManager, $promoManager, $sortUtility, $stringUtility)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->sortUtility = $sortUtility;
        $this->stringUtility = $stringUtility;
    }

    /**
     * Applies if protectedCategory will show or not
     * @param  object $categoryList
     * @param  boolean $isAdmin
     * @return object
     */
    public function applyProtectedCategory($categoryList,$isAdmin = FALSE)
    {
        $protectedCategories =  $this->configLoader->getItem('protected_category');
        if(!$isAdmin){
            foreach($categoryList as $key => $value){  
                if((in_array($value->getIdCat(),$protectedCategories) || intval($value->getIdCat()) === 1) && !$isAdmin){
                    unset($categoryList[$key]);
                } 
            }
        } 

        return $categoryList;
    }

    /**
     *  Set category as featured @table es_member_cat. is_featured = 1
     *  Pass an array of categoryIDs for batch updating.
     *
     *  @param array $catId - category ID
     */
    public function setCustomCategoryAsFeatured($catId, $memberId)
    {
        $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);

        if( !is_array($catId) ){
            $catId = array($catId);
        }

        foreach($catId as $categoryId){
            $category = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                ->findOneBy(array(
                                                'idMemcat' => $categoryId, 
                                                'member' => $memberObj
                                            ));
            $category->setIsFeatured(1);
            $this->em->persist($category);
        }

        $this->em->flush();
    }

    /**
     * Set the image/icon of the category
     * @param mixed $categoryList
     * @return mixed
     */
    public function setCategoryImage($categoryList)
    {
        foreach($categoryList as $key => $value){ 
                $categoryImage = $this->em->getRepository('EasyShop\Entities\EsCatImg')
                            ->findOneBy(['idCat' => $value->getIdCat()]);
                $imagePath = ($categoryImage) ? $categoryImage->getPath() : "";
                $value->setImage($imagePath); 
        } 
        
        return $categoryList;
    }
    
    
    /**
     *  Fetch products under category. If array of category Ids is empty all 
     *  non categorized products are returned.
     *
     *  @param integer $memberId
     *  @param integer[] $arrCatId 
     *  @param boolean $isCustom
     *  @param integer $productLimit
     *  @param integer $page
     *  @param string[] $orderBy
     *  @param string $condition
     *  @param string $lprice
     *  @param string $uprice
     *
     *  @return array - filter count of products and array of product objects
     */
    public function getProductsWithinCategory($memberId, $arrCatId, $isCustom = false , $productLimit = 12, $page = 0, $orderBy = [ "clickcount" => "DESC" ], $condition = "", $lprice = "", $uprice ="")
    {
        $getAllNonCategorized = false;
        if(empty($arrCatId)){
            $getAllNonCategorized = true;
        }

        $categoryProducts = [];
        $currentPage = (int) $page <= 0 ? 0 : $page-1;
        $offset = (int) $page <= 0 ? 0 : ($page-1) * $productLimit;
        $condition = strval($condition);

        $lprice = str_replace(",", "", (string)$lprice);
        $uprice = str_replace(",", "", (string)$uprice);

        if($condition === "" && $lprice === "" && $uprice === ""){
            if($getAllNonCategorized){
                $categoryProductIds = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                           ->getNonCategorizedProductIds($memberId, $productLimit, $offset, $orderBy);
                $productCount = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                         ->getCountNonCategorizedProducts($memberId);
            }
            else{
                if($isCustom){
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                                ->getPagedCustomCategoryProducts($memberId, $arrCatId, $productLimit, $offset, $orderBy);
                    $productCount = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                            ->countCustomCategoryProducts($memberId, $arrCatId);                            
                }
                else{
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                                ->getDefaultCategorizedProducts($memberId, $arrCatId, $productLimit, $offset, $orderBy);
                    $productCount = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                            ->countDefaultCategorizedProducts($memberId, $arrCatId);    
                }
            }
            $isFiltered = false;    
        }
        else{
            if($getAllNonCategorized){
                $categoryProductIds = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                           ->getNonCategorizedProductIds($memberId, PHP_INT_MAX);
            }
            else{
                if($isCustom){
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                                ->getAllCustomCategoryProducts($memberId, $arrCatId, $condition, $orderBy);
                }
                else{
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                                ->getAllDefaultCategorizedProducts($memberId, $arrCatId, $condition, $orderBy);
                }
            }

            if($lprice !== "" || $uprice !== "") {
                foreach ($categoryProductIds as $key => $prodId) {
                    $discountedPrice = floatval($this->promoManager->hydratePromoDataExpress($prodId));
                    if( ( $lprice !== "" && bccomp($discountedPrice, $lprice, 4) === -1) || ( $uprice !== "" && bccomp($discountedPrice, $uprice, 4) === 1)) {
                        unset($categoryProductIds[$key]);
                    }
                }   
            }             
            $isFiltered = true;  
        }

        if($isFiltered) {
            $productCount = count($categoryProductIds);
            if(!empty($categoryProductIds)) {
                $filteredCategoryProducts = array_chunk($categoryProductIds, $productLimit);            
                $categoryProductIds = $filteredCategoryProducts[$currentPage];
            }
        }

        foreach($categoryProductIds as $productId){
            $product = $this->productManager->getProductDetails($productId);
            $objImage = $this->em->getRepository("EasyShop\Entities\EsProductImage")
                                ->getDefaultImage($productId);       
            $secondaryProductImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                              ->getSecondaryImage($productId);
            $product->directory = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
            $product->imageFileName = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_FILE;
            $product->secondaryImageDirectory = null;
            $product->secondaryImageFileName = null;
            if($objImage !== null){
                $product->directory = $objImage->getDirectory();
                $product->imageFileName = $objImage->getFilename();
            }
            if($secondaryProductImage !== null){
                $product->secondaryImageDirectory = $secondaryProductImage->getDirectory();
                $product->secondaryImageFileName = $secondaryProductImage->getFilename();
            }

            $categoryProducts[] = $product;
        }

        $result = [
            'products' => $categoryProducts,
            'filtered_product_count' => $productCount,
        ];

        return $result;
    }

    
    /**
     * Get User Categories. Return custom categories if available otherwise 
     * this will return the default categories
     *
     * @param integer $memberId
     * @return mixed
     */
    public function getUserCategories($memberId)
    {
        $vendorCategories = [];
        
        $memberCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                     ->getCustomCategoriesArray($memberId);    
        
        if(empty($memberCategories)){
            $categoryNestedSetCount = $this->em->getRepository('EasyShop\Entities\EsCategoryNestedSet')
                                            ->getNestedSetCategoryCount();
            if((int)$categoryNestedSetCount === 0){
                $rawVendorCategories = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getUserCategoriesUsingAdjacencyList($memberId);
            }
            else{
                $rawVendorCategories = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getUserCategoriesUsingNestedSet($memberId);
            }    
            
            foreach( $rawVendorCategories as $rawVendorCategory ){
                $parentId = (int)$rawVendorCategory['parent_cat'];
                $categoryName = $rawVendorCategory['p_cat_name'];
                $index = 'default-'.$parentId;
                $isParentArrayNotAvailable = !isset($vendorCategories[$index]);
                $isParentRoot = $parentId === EsCat::ROOT_CATEGORY_ID;
                if($isParentArrayNotAvailable){
                    $sortOrder = 0;
                    if($isParentRoot){
                        $categoryName = 'Others';
                        $sortOrder = 1;
                    }
                    $vendorCategories[$index]['sortOrder'] = $sortOrder;
                    $vendorCategories[$index]['name'] = $categoryName;
                    $vendorCategories[$index]['child_cat'] = [ $parentId ];
                    $vendorCategories[$index]['products'] = [];
                    $vendorCategories[$index]['categoryId'] = $parentId;
                    $vendorCategories[$index]['memberCategoryId'] = 0;       
                    $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                }              
                if(!in_array($rawVendorCategory['cat_id'], $vendorCategories[$index]['child_cat'])){
                    $vendorCategories[$index]['child_cat'][] = $rawVendorCategory['cat_id'];
                }
            }   
        }
        else{
            $highestSortOrder = 0;
            foreach( $memberCategories as $memberCategory ){
                $index = 'custom-'.$memberCategory['id_memcat'];
                $vendorCategories[$index]['name'] = $memberCategory['cat_name'];
                $vendorCategories[$index]['child_cat'] = [ $memberCategory['id_memcat'] ];
                $vendorCategories[$index]['products'] = [];
                $vendorCategories[$index]['categoryId'] = 0;
                $vendorCategories[$index]['memberCategoryId'] = $memberCategory['id_memcat']; 
                $vendorCategories[$index]['sortOrder'] = $memberCategory['sort_order'];
                $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                if($vendorCategories[$index]['sortOrder']  > $highestSortOrder){
                    $highestSortOrder = $vendorCategories[$index]['sortOrder'];
                }
            }            
            $totalCountNonCategorizedProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                                     ->getCountNonCategorizedProducts($memberId);
            if($totalCountNonCategorizedProducts > 0){
                $index = 'custom-noncategorized';
                $vendorCategories[$index]['name'] = 'Others';
                $vendorCategories[$index]['child_cat'] = [];
                $vendorCategories[$index]['products'] = [];
                $vendorCategories[$index]['product_count'] = $totalCountNonCategorizedProducts;
                $vendorCategories[$index]['categoryId'] = 0;
                $vendorCategories[$index]['memberCategoryId'] = 0;;
                $vendorCategories[$index]['sortOrder'] = $highestSortOrder;
                $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                $vendorCategories[$index]['total_product_count'] = $totalCountNonCategorizedProducts;
            }
        }

        $this->sortUtility->stableUasort($vendorCategories, function($sortArgumentA, $sortArgumentB) {
            return $sortArgumentA['sortOrder'] - $sortArgumentB['sortOrder'];
        });
        
        return $vendorCategories;
    }


    /**
     *  Create custom category for memberId @table es_member_cat
     *
     *  @param string $categoryName
     *  @param integer $memberId
     *  @param inetger[] $productIds
     *
     *  @return array
     */
    public function createCustomCategory($categoryName, $memberId, $productIds)
    {
        $errorMessage = "";
        $actionResult = false;
        $newCategoryId = 0;
   
        if(empty($categoryName)){
            $errorMessage = "Category name cannot be empty";
        }
        else{
            $isCategoryNameAvailable = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                            ->isCustomCategoryNameAvailable($categoryName,$memberId);
            if(!$isCategoryNameAvailable) {
                $errorMessage = "Category name already exists";
            }
            else{
                try {
                    $esProductRepo = $this->em->getRepository("EasyShop\Entities\EsProduct");
                    $esMemberCategoryRepository =  $this->em->getRepository("EasyShop\Entities\EsMemberCat");
                    $datetimeToday = date_create();
                    $member = $this->em->find('EasyShop\Entities\EsMember', $memberId);
                    $higestSortOrder = $esMemberCategoryRepository->getHighestSortOrder($memberId);
                    $higestSortOrder++;
                    $memberCategory = new EsMemberCat();
                    $memberCategory->setCatName($categoryName)
                                   ->setMember($member)
                                   ->setCreatedDate($datetimeToday)
                                   ->setSortOrder($higestSortOrder)
                                   ->setlastModifiedDate($datetimeToday);
                    $this->em->persist($memberCategory);
                    foreach ($productIds as $productId) {
                        $product =  $esProductRepo->findOneBy([
                                        "member" => $memberId,
                                        "idProduct" => $productId,
                                        "isDelete" => EsProduct::ACTIVE,
                                        "isDraft" => EsProduct::ACTIVE
                                    ]);
                        if($product) {
                            $memberProductCategory = new EsMemberProdcat();
                            $memberProductCategory->setMemcat($memberCategory);
                            $memberProductCategory->setProduct($product);
                            $memberProductCategory->setCreatedDate($datetimeToday);
                            $this->em->persist($memberProductCategory);
                        }
                    }
                    $this->em->flush();
                    $newCategoryId = $memberCategory->getIdMemcat();
                    $actionResult = true;
                }
                catch(Exception $e) {
                    $errorMessage = "Database Error";
                }
            }
        }

        return [
            "errorMessage" => $errorMessage,
            "result" => $actionResult,
            "newCategoryId" => $newCategoryId,
        ];
       
    }

    /**
     * Performs the update actions of User Custom Category Products
     * 
     * @param integer $memberCategoryId
     * @param string $categoryName
     * @param integer[] $productIds
     * @param integer $memberId
     * 
     * @return mixed
     */
    public function editUserCustomCategoryProducts($memberCategoryId, $categoryName, $productIds, $memberId)
    {
        $esMemberProdcatRepo = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat");
        $esMemberCatRepo = $this->em->getRepository('EasyShop\Entities\EsMemberCat');
        $esProductRepo = $this->em->getRepository("EasyShop\Entities\EsProduct");

        $actionResult = false;
        $errorMessage = "";
        $categoryName = trim($categoryName);

        try{
        
            if(empty($categoryName)){
                 $errorMessage = "Category name cannot be empty";
            }
            else{
                $isCategoryNameAvailable = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                                ->isCustomCategoryNameAvailable($categoryName,$memberId, $memberCategoryId);
                if($isCategoryNameAvailable) {
                    $memberCategory = $esMemberCatRepo->findBy(["idMemcat" => $memberCategoryId, "member" => $memberId]);
                    if($memberCategory) {
                        $memberCategoryProducts = $esMemberProdcatRepo->findBy(["memcat" => $memberCategoryId]);                
                        foreach ($memberCategoryProducts as $memberCategoryProduct) {
                            $this->em->remove($memberCategoryProduct);
                        }
                    }
                    $datetimeToday = date_create();
                    $memberCategory = $esMemberCatRepo->find($memberCategoryId);
                    $memberCategory->setCatName($categoryName);
                    $memberCategory->setlastModifiedDate($datetimeToday);
                    foreach ($productIds as $productId) {
                        $product =  $esProductRepo->findOneBy([
                                        "member" => $memberId,
                                        "idProduct" => $productId,
                                        "isDelete" => EsProduct::ACTIVE,
                                        "isDraft" => EsProduct::ACTIVE
                                    ]);

                        if($product) {
                            $memberProductCategory = new EsMemberProdcat();
                            $memberProductCategory->setMemcat($memberCategory);
                            $memberProductCategory->setProduct($product);
                            $memberProductCategory->setCreatedDate($datetimeToday);
                            $this->em->persist($memberProductCategory);
                        }
                    }
                    $this->em->flush();
                    $actionResult = true;
                }
                else {
                    $errorMessage = "Category name already exists";
                }
            }
        }
        catch(Exception $e) {
            $errorMessage = "Database Error";
        }            

        return [
            "errorMessage" => $errorMessage,
            "result" => $actionResult
        ];
    }

    /**
     * Updates is_delete field to '1' of a custom category
     *
     * @param integer[[ $customCategoryIds
     * @param integer $memberId
     * 
     * @return boolean
     */
    public function deleteUserCustomCategory($customCategoryIds, $memberId)
    {
        try{
            foreach ($customCategoryIds as $categoryId) {
                if($categoryId !== 0){
                    $memberCat = $this->em
                                      ->getRepository("EasyShop\Entities\EsMemberCat")
                                      ->findOneBy([
                                            "idMemcat" => $categoryId,
                                            "member" => $memberId,
                                            "isDelete" => EsMemberCat::ACTIVE,
                                        ]); 
                    if($memberCat) {
                        $memberCat->setIsDelete(EsMemberCat::IS_DELETE);
                        $memberCat->setlastModifiedDate(date_create());
                    }
                }
            }                   
            $this->em->flush();
            return true;
        }
        catch(Exception $e) {
            return false;
        }
    }

} 
