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
     * Category ID placeholder for non existent EsCat or EsMemberCat
     *
     * @var integer
     */
    const NON_EXISTENT_CATEGORYID_PLACEHOLDER = 0;

    /**
     * Order by product sort order
     *
     * @var integer
     */
    const ORDER_PRODUCTS_BY_SORTORDER = 0;
    
    /**
     * Order by popularity
     *
     * @var integer
     */
    const ORDER_PRODUCTS_BY_CLICKCOUNT = 1;
    
    /**
     * Order by last modified date of product
     *
     * @var integer
     */
    const ORDER_PRODUCTS_BY_LASTCHANGE = 2;
    
    /**
     * Order by isHot and clickcount
     *
     * @var integer
     */
    const ORDER_PRODUCTS_BY_HOTNESS = 3;
    
    
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
     * Form factory
     *
     */
    private $formFactory;
    
    /**
     * Form validation
     *
     */
    private $formValidation;
    
    /**
     * Form error helper
     *
     */
    private $formErrorHelper;
    
    /**
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($configLoader,
                                $em, 
                                $productManager, 
                                $promoManager, 
                                $sortUtility, 
                                $stringUtility,
                                $formFactory, 
                                $formValidation,
                                $formErrorHelper)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->sortUtility = $sortUtility;
        $this->stringUtility = $stringUtility;
        $this->formFactory = $formFactory;
        $this->formValidation = $formValidation;
        $this->formErrorHelper = $formErrorHelper;
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
    public function getProductsWithinCategory($memberId, $categoryIdFilters, $isCustom = false , $productLimit = 12, $page = 0, $orderBy = [ self::ORDER_PRODUCTS_BY_SORTORDER => 'DESC' ] , $condition = "", $lprice = "", $uprice ="")
    {
        $getAllNonCategorized = false;
        if(empty($categoryIdFilters)){
            $getAllNonCategorized = true;
        }

        $categoryProducts = [];
        $currentPage = (int) $page <= 0 ? 0 : $page-1;
        $offset = (int) $page <= 0 ? 0 : ($page-1) * $productLimit;
        $condition = strval($condition);

        $lprice = str_replace(",", "", (string)$lprice);
        $uprice = str_replace(",", "", (string)$uprice);
        
        if(!$isCustom && key($orderBy) === self::ORDER_PRODUCTS_BY_SORTORDER){
            $orderBy = [ self::ORDER_PRODUCTS_BY_CLICKCOUNT => 'DESC' ];
        }

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
                                                   ->getPagedCustomCategoryProducts($memberId, $categoryIdFilters, $productLimit, $offset, $orderBy);
                    $productCount = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                             ->countCustomCategoryProducts($memberId, $categoryIdFilters);                            
                }
                else{
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                                   ->getDefaultCategorizedProducts($memberId, $categoryIdFilters, $productLimit, $offset, $orderBy);
                    $productCount = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                             ->countDefaultCategorizedProducts($memberId, $categoryIdFilters);    
                }
            }
            $isFiltered = false;    
        }
        else{
            if($getAllNonCategorized){
                $categoryProductIds = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                               ->getNonCategorizedProductIds($memberId, PHP_INT_MAX, 0, $orderBy);
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
        
        $numberOfAllCustomCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                            ->getNumberOfCustomCategories($memberId, true);  
        
        if($numberOfAllCustomCategories === 0){
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
                    $vendorCategories[$index]['memberCategoryId'] = self::NON_EXISTENT_CATEGORYID_PLACEHOLDER;       
                    $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;
                }              
                if(!in_array($rawVendorCategory['cat_id'], $vendorCategories[$index]['child_cat'])){
                    $vendorCategories[$index]['child_cat'][] = $rawVendorCategory['cat_id'];
                }
            }   
        }
        else{
            $memberCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                     ->getTopLevelCustomCategories($memberId);    
            
            foreach( $memberCategories as $memberCategory ){
                $index = 'custom-'.$memberCategory['id_memcat'];
                $vendorCategories[$index]['name'] = $memberCategory['cat_name'];
                $vendorCategories[$index]['child_cat'] = [ $memberCategory['id_memcat'] ];
                $vendorCategories[$index]['products'] = [];
                $vendorCategories[$index]['categoryId'] = self::NON_EXISTENT_CATEGORYID_PLACEHOLDER;
                $vendorCategories[$index]['memberCategoryId'] = $memberCategory['id_memcat']; 
                $vendorCategories[$index]['sortOrder'] = $memberCategory['sort_order'];
                $vendorCategories[$index]['cat_type'] = self::CATEGORY_NONSEARCH_TYPE;

                /**
                 * change child_cat above later on
                 * Also comment this section cause it's pretty smelly in here
                 */
                $childrenData = explode('|', $memberCategory['childList']);
                $vendorCategories[$index]['children'] = [];
                foreach($childrenData as $childData){
                    if(empty($childData)){
                        continue;
                    }
                    $parsedData = explode("~", $childData, 3);
                    $vendorCategories[$index]['children'][] = [
                        'id' => $parsedData[0], 
                        'name' => $parsedData[1],
                        'sortOrder' => $parsedData[2],
                    ];
                    $vendorCategories[$index]['child_cat'][] = $parsedData[0];
                }
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
     *  @param integer[] $productIds
     *  @param integer $parentCategoryId
     *
     *  @return array
     */
    public function createCustomCategory($categoryName, $memberId, $productIds, $parentCategoryId = 0)
    {
        $errorMessage = "";
        $actionResult = false;
        $newCategoryId = 0;

        $rules = $this->formValidation->getRules('custom_category');
        $form = $this->formFactory->createBuilder('form', null, array('csrf_protection' => false))
                     ->setMethod('POST')
                     ->add('name', 'text', array('constraints' => $rules['name']))
                     ->getForm();
        
        $form->submit([ 
            'name' => $categoryName,
        ]);
        
        if ($form->isValid()) {
            $isCategoryNameAvailable = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                            ->isCustomCategoryNameAvailable($categoryName,$memberId);
            
            $esMemberCategoryRepository =  $this->em->getRepository("EasyShop\Entities\EsMemberCat"); 
            
            $parentMemberCategory = true;
            if($parentCategoryId !== 0){
                $parentMemberCategory = $esMemberCategoryRepository->findOneBy([
                                            'member' => $memberId,
                                            'idMemcat' => $parentCategoryId,
                                            'parentId' => EsMemberCat::PARENT,
                                        ]);
            }

            if(!$isCategoryNameAvailable) {
                $errorMessage = "Category name already exists";
            }
            else if($parentMemberCategory === null){
                $errorMessage = "Category cannot be added under selected parent category";
            }
            else{
                try {
                    $esProductRepo = $this->em->getRepository("EasyShop\Entities\EsProduct");
                    $datetimeToday = date_create();
                    $member = $this->em->find('EasyShop\Entities\EsMember', $memberId);
                    $higestSortOrder = $esMemberCategoryRepository->getHighestSortOrder($memberId, $parentCategoryId);
                    $higestSortOrder++;
                    $memberCategory = new EsMemberCat();
                    $memberCategory->setCatName($categoryName)
                                   ->setMember($member)
                                   ->setCreatedDate($datetimeToday)
                                   ->setSortOrder($higestSortOrder)
                                   ->setlastModifiedDate($datetimeToday)
                                   ->setParentId($parentCategoryId);
                    $this->em->persist($memberCategory);
                    $productSortOrder = 0;
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
                            $memberProductCategory->setSortOrder($productSortOrder);
                            $this->em->persist($memberProductCategory);
                            $productSortOrder++;
                        }
                    }
                    $this->em->flush();
                    $newCategoryId = $memberCategory->getIdMemcat();
                    $actionResult = true;
                }
                catch(\Exception $e) {
                    $errorMessage = "Database Error";
                }
            }
        }
        else{
            $errorMessage = reset($this->formErrorHelper->getFormErrors($form));
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
    public function editUserCustomCategoryProducts($memberCategoryId, $categoryName, $productIds, $memberId, $parentCategoryId = 0)
    {
        $memberCategoryId = (int)$memberCategoryId;
        $parentCategoyId = (int)$parentCategoryId;
        $esMemberProdcatRepo = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat");
        $esMemberCatRepo = $this->em->getRepository('EasyShop\Entities\EsMemberCat');
        $esProductRepo = $this->em->getRepository("EasyShop\Entities\EsProduct");

        $actionResult = false;
        $errorMessage = "";
        $categoryName = trim($categoryName);

        $rules = $this->formValidation->getRules('custom_category');
        $form = $this->formFactory->createBuilder('form', null, array('csrf_protection' => false))
                     ->setMethod('POST')
                     ->add('name', 'text', array('constraints' => $rules['name']))
                     ->getForm();
        
        $form->submit([ 
            'name' => $categoryName,
        ]);
        
        if ($form->isValid()) {
            $formData = $form->getData();
            $categoryName = $formData['name'];
            $esMemberCategoryRepository = $this->em->getRepository('EasyShop\Entities\EsMemberCat');
            try{
                $isCategoryNameAvailable = $esMemberCategoryRepository->isCustomCategoryNameAvailable($categoryName, $memberId, $memberCategoryId);
                $parentMemberCategory = true;
                if($parentCategoryId !== 0){
                    $parentMemberCategory = null;
                    if($parentCategoryId !== $memberCategoryId){
                        $parentMemberCategory = $esMemberCategoryRepository->findOneBy([
                                                                                'member' => $memberId,
                                                                                'idMemcat' => $parentCategoryId,
                                                                                'parentId' => EsMemberCat::PARENT,
                                                                            ]);
                    }
                }
     
                if(!$isCategoryNameAvailable){
                    $errorMessage = "Category name already exists";
                }
                else if($parentMemberCategory === null){
                    $errorMessage = "Category cannot be added under selected parent category";
                }
                else{
                    $memberCategory = $esMemberCatRepo->findOneBy([
                                        "idMemcat" => $memberCategoryId,
                                        "member" => $memberId
                                    ]);
                                    
                    if($memberCategory){
                        $isParentModified = $memberCategory->getParentId() !== $parentCategoryId;
                        $isUpdateAllowed = true;
                        if($isParentModified){
                            $numberOfChildren = $esMemberCatRepo->getNumberOfChildren($memberCategoryId);
                            $isUpdateAllowed = $numberOfChildren === 0;
                        }
                        
                        if(!$isUpdateAllowed){
                            $errorMessage = "Only a maximum of two levels are permitted in the category structure.";
                        }
                        else{          
                            $memberCategoryProducts = $esMemberProdcatRepo->findBy(["memcat" => $memberCategoryId]);                
                            foreach ($memberCategoryProducts as $memberCategoryProduct) {
                                $this->em->remove($memberCategoryProduct);
                            }
                            $datetimeToday = date_create();
                            $memberCategory->setCatName($categoryName);
                            $memberCategory->setlastModifiedDate($datetimeToday);                    
                            if($isParentModified){
                                $higestSortOrder = $esMemberCategoryRepository->getHighestSortOrder($memberId, $parentCategoryId);
                                $higestSortOrder++;
                                $memberCategory->setParentId($parentCategoryId);
                                $memberCategory->setSortOrder($higestSortOrder);
                            }
                            $productSortOrder = 0;
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
                                    $memberProductCategory->setSortOrder($productSortOrder);
                                    $productSortOrder++;
                                    $this->em->persist($memberProductCategory);
                                }
                            }
                            $this->em->flush();
                            $actionResult = true;
                        }
                    }
                    else{
                        $errorMessage = "Category not found";
                    }               
                }
            }
            catch(Exception $e) {
                $errorMessage = "Database Error";
            }            
        }
        else{
            $errorMessage = reset($this->formErrorHelper->getFormErrors($form));
        }
        
        return [
            "errorMessage" => $errorMessage,
            "result" => $actionResult
        ];
    }

    /**
     * Update the category tree structure
     *
     * @param EasyShop\Category\CategoryWrapper[] $categoryWrappers
     * @return mixed
     */
    public function updateCategoryTree($memberId, array $categoryWrappers)
    {   
        $indexedCategoryData = [];
        foreach($categoryWrappers as $categoryWrapper){
            $indexedCategoryData[$categoryWrapper->getMemberCategoryId()] = $categoryWrapper;
        }

        $memberCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                     ->getCustomCategoriesObject($memberId, array_keys($indexedCategoryData));
        $datetimeToday = date_create(date("Y-m-d H:i:s"));
        foreach($memberCategories as $memberCategory){
            $memberCategoryId = $memberCategory->getIdMemcat(); 
            if( isset($indexedCategoryData[$memberCategoryId]) ){ 
                $inputCategoryData = $indexedCategoryData[$memberCategoryId];
                $memberCategory->setSortOrder($inputCategoryData->getSortOrder());
                $memberCategory->setlastModifiedDate($datetimeToday);
                $memberCategory->setParentId(\EasyShop\Entities\EsMemberCat::PARENT);
                $children = $inputCategoryData->getChildren();
                if(empty($children) === false){
                    $indexedChildrenData = [];
                    foreach($children as $childWrapper){
                        $indexedChildrenData[$childWrapper->getMemberCategoryId()] = $childWrapper;
                    }        
                    $childrenCategoryObject = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                                       ->getCustomCategoriesObject($memberId, array_keys($indexedChildrenData));
                    foreach($childrenCategoryObject as $childObject){
                        $childMemberCategoryId = $childObject->getIdMemcat();
                        if(isset($indexedChildrenData[$childMemberCategoryId])){
                            $isNotSameAsParent = (int)$childMemberCategoryId !== (int)$memberCategoryId;
                            $numberOfChildren = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                                              ->getNumberOfChildren($childMemberCategoryId);
                            if($isNotSameAsParent && $numberOfChildren === 0){
                                $childInputCategoryData = $indexedChildrenData[$childMemberCategoryId];
                                $childObject->setSortOrder($childInputCategoryData->getSortOrder());
                                $childObject->setlastModifiedDate($datetimeToday);
                                $childObject->setParentId($memberCategoryId);
                            }
                        }
                    }
                }  
            }
        }
        
        $isSuccess = true;
        try{
            $this->em->flush();
        }
        catch(\Exception $e){
            $isSuccess = true;
        }
        
        return $isSuccess;
    }
    
    
    /**
     * Get User custom categories wrapped in CategoryWrapper class
     *
     * @param integer $memberId
     * @return EasyShop\Category\CategoryWrapper[]
     */
    public function getWrappedUserCategories($memberId)
    {
        $memberCategories = $this->getUserCategories($memberId);           
        $resultCategories = [];
        foreach($memberCategories as $memberCategory){
            $categoryWrapper = new CategoryWrapper();
            $categoryWrapper->setCategoryName($memberCategory['name']);
            $categoryWrapper->setSortOrder($memberCategory['sortOrder']);
            $categoryWrapper->setMemberCategoryId($memberCategory['memberCategoryId']);
            foreach($memberCategory['children'] as $child){
                $childCategory = new CategoryWrapper();
                $childCategory->setCategoryName($child['name']);
                $childCategory->setSortOrder($child['sortOrder']);
                $childCategory->setMemberCategoryId($child['id']);
                $categoryWrapper->addChild($childCategory);
            }
            $resultCategories[] = $categoryWrapper->toArray();
        }            
        
        return $resultCategories;
    }
    
    
    /**
     * Updates is_delete field to '1' of a custom category
     * Returns the IDs of the affected categories
     *
     * @param integer[] $customCategoryIds
     * @param integer $memberId
     * 
     * @return integer[]
     */
    public function deleteUserCustomCategory($customCategoryIds, $memberId)
    {
        /**
         * Delete child categories first to prevent categories from being orphaned
         */
        $deletedCategoryIds = [];
        $childCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                ->getCustomCategoriesObject($memberId, $customCategoryIds, true);
        
        foreach($childCategories as $childCategory){
            $numberOfChildren = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                     ->getNumberOfChildren($childCategory->getIdMemcat());
            if($numberOfChildren === 0){
                $childCategory->setIsDelete(EsMemberCat::IS_DELETE);
                $childCategory->setlastModifiedDate(date_create());
                $key = array_search($childCategory->getIdMemcat(), $customCategoryIds); 
                if($key !== false){
                    unset($customCategoryIds[$key]);
                }
                $deletedCategoryIds[] = $childCategory->getIdMemcat();
            }
        }
        $nonChildCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                  ->getCustomCategoriesObject($memberId, $customCategoryIds);
    
        foreach($nonChildCategories as $category){
            $numberOfChildren = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                     ->getNumberOfChildren($category->getIdMemcat());
            if($numberOfChildren === 0){
                $category->setIsDelete(EsMemberCat::IS_DELETE);
                $category->setlastModifiedDate(date_create());
            }
            $deletedCategoryIds[] = $category->getIdMemcat();
        }
        
        try{    
            $this->em->flush();
            return $deletedCategoryIds;
        }
        catch(Exception $e) {
            return false;
        }
    }
    
    /**
     * Retrieves the top most parent category
     *
     * @param integer $categoryId
     * @return EasyShop\Entities\EsCat
     */
    public function getTopParentCategory($categoryId)
    {
        $categoryNestedSetCount = $this->em->getRepository('EasyShop\Entities\EsCategoryNestedSet')
                                       ->getNestedSetCategoryCount();
        $parentCategoryId = 0;
        if((int)$categoryNestedSetCount === 0){
            $ancestor =  $this->em->getRepository('EasyShop\Entities\EsCat')
                                    ->getParentCategoryRecursive($categoryId);
            $parentCategoryId = reset($ancestor)['idCat'];
        }
        else{
            $ancestorIds = $this->em->getRepository('EasyShop\Entities\EsCat')
                                    ->getAncestorsWithNestedSet($categoryId);
            $parentCategoryId = reset($ancestorIds);
        }    
        $topLevelParent = $this->em->find('EasyShop\Entities\EsCat', $parentCategoryId);
        if($topLevelParent === null){
            $topLevelParent = $this->em->find('EasyShop\Entities\EsCat', $categoryId);
        }

        return $topLevelParent;
    }
    
    /**
     * Migrate the default categories to the EsMemberCat table
     * 
     * @param integer $memberId
     * @return boolean
     */
    public function migrateUserCategories($memberId)
    {
        $allUserCategories = $this->getUserCategories($memberId);
        $member = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        foreach($allUserCategories as $category){
            $datetimeToday = date_create(date("Y-m-d H:i:s"));
            $categoryName = $this->stringUtility->removeSpecialCharsExceptSpace($category['name']);
            $newMemberCategory = new \EasyShop\Entities\EsMemberCat();
            $newMemberCategory->setMember($member);
            $newMemberCategory->setCatName($categoryName);
            $newMemberCategory->setSortOrder($category['sortOrder']);
            $newMemberCategory->setCreatedDate($datetimeToday);
            $newMemberCategory->setlastModifiedDate($datetimeToday);
            $this->em->persist($newMemberCategory); 

            $childCategories = $category['child_cat'];
            $productIds = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->getDefaultCategorizedProducts($memberId, $childCategories, PHP_INT_MAX);
            $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                 ->findByIdProduct($productIds);
            $productSortOrder = 0;
            foreach($products as $product){
                $memberCategoryProduct = new \EasyShop\Entities\EsMemberProdcat();
                $memberCategoryProduct->setMemcat($newMemberCategory);
                $memberCategoryProduct->setProduct($product);
                $memberCategoryProduct->setCreatedDate($datetimeToday);
                $memberCategoryProduct->setSortOrder($productSortOrder);
                $productSortOrder++;
                $this->em->persist($memberCategoryProduct);
            } 
        } 
        $isSuccessful = true;
        try{
            $this->em->flush();
        }
        catch(\Exception $e){
            $isSuccessful = false;
        }
        return $isSuccessful;
    }
    
    
    /**
     * Migrate a single product to its default category (it's top most parent
     * category) in EsMemberCat
     * 
     * @param EasyShop\Entities\EsProduct $product
     * @param boolean $isSuccessful
     *
     */
    public function migrateProductToDefaultCustomCategory($product)
    {
        $isSuccessful = false;
        if($product){
            $productId = $product->getIdProduct();
            $member = $product->getMember();
            $memberProducts = $this->em->getRepository('EasyShop\Entities\EsMemberProdcat')
                                        ->findBy(['product' => $productId]);
            if(empty($memberProducts)){
                $stringUtility = $this->stringUtility;
                $categoryId = $product->getCat()->getIdCat();
                $topParentCategory = $this->getTopParentCategory($categoryId); 
                $topParentCategoryName = $topParentCategory->getName();
                if((int)$categoryId === \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID){
                    $topParentCategoryName = $product->getCatOtherName();
                }
                $cleanedCategoryName = $stringUtility->cleanString($topParentCategoryName);
                $cleanedCategoryName = strtolower($cleanedCategoryName);
                $memberCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                             ->getCustomCategoriesObject($member->getIdMember());     
                $isCustomCategoryFound = false;
                $datetimeToday = date_create(date("Y-m-d H:i:s"));
                $highestSortOrder = 0;
                foreach($memberCategories as $memberCategory){
                    $cleanedName = $this->stringUtility->removeSpecialCharsExceptSpace($memberCategory->getCatName());
                    $cleanedName = strtolower($stringUtility->cleanString($cleanedName));
                    if($memberCategory->getSortOrder() > $highestSortOrder){
                        $highestSortOrder = $memberCategory->getSortOrder();
                    }
                    if($cleanedName === $cleanedCategoryName){
                        $memberCategoryId = $memberCategory->getIdMemcat();
                        $highestProductSortOrder = $this->em->getRepository('EasyShop\Entities\EsMemberProdcat')
                                                        ->getHighestProductSortOrderWithinCategory($memberCategoryId);
                        $newMemberProduct = new \EasyShop\Entities\EsMemberProdcat();
                        $newMemberProduct->setMemcat($memberCategory);
                        $newMemberProduct->setCreatedDate($datetimeToday);
                        $newMemberProduct->setProduct($product);
                        $newMemberProduct->setSortOrder($highestProductSortOrder);
                        $this->em->persist($newMemberProduct);
                        $isCustomCategoryFound = true;
                        break;
                    }
                }
                if(!$isCustomCategoryFound){
                    $highestSortOrder++;
                    $validCategoryName = $this->stringUtility->removeSpecialCharsExceptSpace($topParentCategoryName);
                    $newMemberCategory = new \EasyShop\Entities\EsMemberCat();
                    $newMemberCategory->setMember($member);
                    $newMemberCategory->setCatName($validCategoryName);
                    $newMemberCategory->setSortOrder($highestSortOrder);
                    $newMemberCategory->setCreatedDate($datetimeToday);
                    $newMemberCategory->setlastModifiedDate($datetimeToday);
                    $this->em->persist($newMemberCategory);
                    $newMemberProduct = new \EasyShop\Entities\EsMemberProdcat();
                    $newMemberProduct->setMemcat($newMemberCategory);
                    $newMemberProduct->setCreatedDate($datetimeToday);
                    $newMemberProduct->setProduct($product);
                    $newMemberProduct->setSortOrder(0);
                    $this->em->persist($newMemberProduct);
                }
                try{
                    $isSuccessful = true;
                    $this->em->flush();
                }
                catch(\Exception $e){
                    $isSuccessful = false;
                }
            }
        }
        
        return $isSuccessful;
    }

} 
