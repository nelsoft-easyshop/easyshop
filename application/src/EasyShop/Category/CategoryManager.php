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
     *  Create custom category for memberId @table es_member_cat
     *
     *  @param string $catName
     *  @param int $memberId
     *  @param bool $isForDeleteCategory
     *
     *  @return array
     */
    public function createCustomCategory($catName, $memberId, $isForDeleteCategory = false)
    {
        $errorMessage = "";
        $actionResult = false;
        $doesCategoryExist = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                            ->findBy([
                                'catName' => $catName,
                                'member' => $memberId
                            ]);
        if($doesCategoryExist) {
            $errorMessage = "Category name already exists";
        }
        else {
            try {
                $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);
                $category = new EsMemberCat();
                $category->setCatName($catName)
                         ->setMember($memberObj)
                         ->setCreatedDate(date_create())
                         ->setlastModifiedDate(date_create());
                if($isForDeleteCategory) {
                    $category->setIsDelete(EsMemberCat::IS_DELETE);
                }
                $this->em->persist($category);
                $this->em->flush();
                $actionResult = true;
            }
            catch(Exception $e) {
                $errorMessage = "Database Error";
            }
        }

        return [
            "message" => $errorMessage,
            "result" => $actionResult ? $category : false
        ];
       
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
     *  Fetch products under category
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
    public function getProductsWithinCategory($memberId, $arrCatId, $isCustom = false , $productLimit = 12, $page = 0, $orderBy = array("clickcount"=>"DESC"), $condition = "", $lprice = "", $uprice ="")
    {
        $categoryProducts = [];
        $currentPage = (int) $page <= 0 ? 0 : $page-1;
        $page = (int) $page <= 0 ? 0 : ($page-1) * $productLimit;
        $condition = strval($condition);

        $lprice = str_replace(",", "", (string)$lprice);
        $uprice = str_replace(",", "", (string)$uprice);

        if($condition === "" && $lprice === "" && $uprice === ""){
            if($isCustom){
                /**
                 * Do custom stuff here
                 */
                $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                               ->getPagedCustomCategoryProducts($memberId, $arrCatId, $productLimit, $page, $orderBy);
                $productCount = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                         ->countCustomCategoryProducts($memberId, $arrCatId);                            
            }
            else{
                $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                               ->getDefaultCategorizedProducts($memberId, $arrCatId, $productLimit, $page, $orderBy);
                $productCount = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                         ->countDefaultCategorizedProducts($memberId, $arrCatId);    
            }
            $isFiltered = false;    
        }
        else{
              
            if($isCustom){
                /**
                 * Do other custom stuff
                 */
                $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                               ->getAllCustomCategoryProducts($memberId, $arrCatId, $condition, $orderBy);
            }
            else{
                $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                               ->getAllDefaultCategorizedProducts($memberId, $arrCatId, $condition, $orderBy);
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
                    $vendorCategories[$index]['product_count'] = 0;
                    $vendorCategories[$index]['isActive'] = false;
                    $vendorCategories[$index]['categoryId'] = $parentId;
                    $vendorCategories[$index]['memberCategoryId'] = 0;       
                    $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                    $vendorCategories[$index]['is_delete'] = 0;
                }              
                if(!in_array($rawVendorCategory['cat_id'], $vendorCategories[$index]['child_cat'])){
                    $vendorCategories[$index]['child_cat'][] = $rawVendorCategory['cat_id'];
                }
                $vendorCategories[$index]['product_count'] += $rawVendorCategory['prd_count'];
            }   
        }
        else{
            foreach( $memberCategories as $memberCategory ){
                $index = 'custom-'.$memberCategory['id_memcat'];
                $vendorCategories[$index]['name'] = $memberCategory['cat_name'];
                $vendorCategories[$index]['child_cat'] = [ $memberCategory['id_memcat'] ];
                $vendorCategories[$index]['products'] = [];
                $vendorCategories[$index]['product_count'] = $memberCategory['product_count'];
                $vendorCategories[$index]['isActive'] = false;
                $vendorCategories[$index]['categoryId'] = 0;
                $vendorCategories[$index]['memberCategoryId'] = $memberCategory['id_memcat']; 
                $vendorCategories[$index]['sortOrder'] = $memberCategory['sort_order'];
                $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                $vendorCategories[$index]['is_delete'] = $memberCategory['is_delete'];
                $cleanNameMemberCategories[] = $this->stringUtility->cleanString($memberCategory['cat_name']);
            }
        }

        $this->sortUtility->stableUasort($vendorCategories, function($sortArgumentA, $sortArgumentB) {
            return $sortArgumentA['sortOrder'] - $sortArgumentB['sortOrder'];
        });
        
        return $vendorCategories;
    }


    /**
     * Performs the update actions of User Custom Category Products
     * 
     * @param integer $memCatId
     * @param string $categoryName
     * @param integer[] $productIds
     * @param integer $memberId
     * 
     * @return mixed
     */
    public function editUserCustomCategoryProducts($memCatId, $categoryName, $productIds, $memberId)
    {
        $esMemberProdcatRepo = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat");
        $esMemberCatRepo = $this->em->getRepository('EasyShop\Entities\EsMemberCat');
        $esProductRepo = $this->em->getRepository("EasyShop\Entities\EsProduct");

        $actionResult = false;
        $errorMessage = "";

        try{
            $isCategoryNameAvailable = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                            ->isCustomCategoryNameAvailable($categoryName,$memberId, $memCatId);
            if($isCategoryNameAvailable) {
                $memberCategory = $esMemberCatRepo->findBy(["idMemcat" => $memCatId, "member" => $memberId]);
                if($memberCategory) {
                    $memberCategoryProducts = $esMemberProdcatRepo->findBy(["memcat" => $memCatId]);                
                    foreach ($memberCategoryProducts as $memberCategoryProduct) {
                        $this->em->remove($memberCategoryProduct);
                    }
                }
            
                $memberCategory = $esMemberCatRepo->find($memCatId);
                $memberCategory->setCatName($categoryName);
                $memberCategory->setlastModifiedDate(date_create());
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
                        $memberProductCategory->setCreatedDate(date_create());
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
     * Inserts new record with a is_delete value of '1' if category name is not found
     * 
     * @param mixed $categoryArray
     * @param int $memberId
     * 
     * @return boolean
     */
    public function deleteUserCustomCategory($categoryArray, $memberId)
    {
        try{
            foreach ($categoryArray as $value) {
                if(isset($value->memberCatId) && (int) $value->memberCatId !== 0)
                {
                    $memberCat = $this->em
                                      ->getRepository("EasyShop\Entities\EsMemberCat")
                                      ->findOneBy([
                                            "idMemcat" => (int)$value->memberCatId,
                                            "member" => $memberId
                                        ]); 
                    if($memberCat) {
                        $memberCat->setIsDelete(EsMemberCat::IS_DELETE);
                        $memberCat->setlastModifiedDate(date_create());
                        $this->em->flush();
                    }
                }
                else {
                    $this->createCustomCategory(trim($value->catName), $memberId, true);
                }
            }
            return true;
        }
        catch(Exception $e) {
            return false;
        }
    }

} 
