<?php

namespace EasyShop\Category;

use EasyShop\Entities\EsMemberCat;
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
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($configLoader, $em, $productManager, $promoManager, $sortUtility)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->sortUtility = $sortUtility;
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

        return $result = [
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
                                               ->getPagedNotCustomCategorizedProducts($memberId, $arrCatId, $productLimit, $page, $orderBy);
                $productCount = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                         ->countNotCustomCategorizedProducts($memberId, $arrCatId);    
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
                                               ->getAllNotCustomCategorizedProducts($memberId, $arrCatId, $condition, $orderBy);
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
     * Get User Categories (Combines custom and main categories)
     *
     * @param integer $memberId
     *
     * @return array
     */
    public function getUserCategories($memberId)
    {
        $defaultCategoryImage = "assets/images/default_icon_small.png";
        $vendorCategories = [];
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
        
        $memberCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                     ->getCustomCategoriesArray($memberId);      
        $indexedMemberCategoriesByName = [];
        foreach($memberCategories as $category){
            $cleanedCategoryName = strtolower(str_replace(' ', '', $category['cat_name']));
            $indexedMemberCategoriesByName[$cleanedCategoryName] = $category;
        }

        foreach( $rawVendorCategories as $vendorCategory ){
            $parentId = (int)$vendorCategory['parent_cat'];
            $categoryName = $vendorCategory['p_cat_name'];
            $cleanedCategoryName = strtolower(str_replace(' ', '', $categoryName));
            $hasNoParent = !isset($vendorCategories[$parentId]);
            $isCategoryMainParent = $parentId === EsCat::ROOT_CATEGORY_ID;
            $isMemberCategorySet = isset($indexedMemberCategoriesByName[$cleanedCategoryName]);
            if($hasNoParent){
                $vendorCategories[$parentId] = [];
                if( !$isCategoryMainParent ){
                    $categoryImage = "assets/" . substr($vendorCategory['p_cat_img'],0,strrpos($vendorCategory['p_cat_img'],'.')) . "_small.png";
                    if($vendorCategory['p_cat_img'] !== "" && file_exists($categoryImage)){
                        $categoryImage = $defaultCategoryImage;
                    }
                    $vendorCategories[$parentId]['slug'] = $vendorCategory['p_cat_slug'];
                    $vendorCategories[$parentId]['cat_link'] = '/category/' . $vendorCategory['p_cat_slug'];
                    $vendorCategories[$parentId]['cat_img'] = $categoryImage;
                    $sortOrder = $isMemberCategorySet ? $indexedMemberCategoriesByName[$cleanedCategoryName]['sort_order'] : 0;
                    $vendorCategories[$parentId]['sortOrder'] = $sortOrder;
                }
                else if( $hasNoParent && $isCategoryMainParent){
                    $categoryName = 'Others';
                    $cleanedCategoryName = strtolower(str_replace(' ', '', $categoryName));
                    $isMemberCategorySet = isset($indexedMemberCategoriesByName[$cleanedCategoryName]);
                    $vendorCategories[$parentId]['slug'] = "";
                    $vendorCategories[$parentId]['cat_link'] = "";
                    $vendorCategories[$parentId]['cat_img'] = $defaultCategoryImage;
                    $sortOrder = $isMemberCategorySet ? $indexedMemberCategoriesByName[$cleanedCategoryName]['sort_order'] : PHP_INT_MAX;
                    $vendorCategories[$parentId]['sortOrder'] = $sortOrder;
                }
                $vendorCategories[$parentId]['name'] = $categoryName;
                $vendorCategories[$parentId]['child_cat'] = [ $parentId ];
                $vendorCategories[$parentId]['products'] = [];
                $vendorCategories[$parentId]['product_count'] = 0;
                $vendorCategories[$parentId]['isActive'] = false;
                $vendorCategories[$parentId]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                $vendorCategories[$parentId]['categoryId'] = $parentId;
                $memberCategoryId = $isMemberCategorySet ? $indexedMemberCategoriesByName[$cleanedCategoryName]['id_memcat'] : 0;
                $vendorCategories[$parentId]['memberCategoryId'] = $memberCategoryId;           
            }
            $vendorCategories[$vendorCategory['parent_cat']]['child_cat'][] = $vendorCategory['cat_id'];
            $vendorCategories[$vendorCategory['parent_cat']]['product_count'] += $vendorCategory['prd_count'];
        }

        $this->sortUtility->stableUasort($vendorCategories, function($sortArgumentA, $sortArgumentB) {
            return $sortArgumentA['sortOrder'] - $sortArgumentB['sortOrder'];
        });
        
  
        return $vendorCategories;
    }


    /**
     * Updates is_delete field to '1' of a custom category
     * Inserts new record with a is_delete value of '1' if category name is not found
     * @param array $categoryArray
     * @param int $memberId
     * 
     * @return bool
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
