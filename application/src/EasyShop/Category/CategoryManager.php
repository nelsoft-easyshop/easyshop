<?php

namespace EasyShop\Category;

use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsCat;

/**
 *  Promo class
 *
 *  @author Ryan Vasquez
 *  @author stephenjanz
 */
class CategoryManager
{
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
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($configLoader, $em, $productManager)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->productManager = $productManager;
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
     *  @param string $catName - category name
     *
     *  @return integer $lastId
     */
    public function createCustomCategory($catName, $memberId)
    {
        $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $category = new EsMemberCat();
        $category->setCatName($catName)
                 ->setMember($memberObj)
                 ->setCreatedDate(date_create());
        $this->em->persist($category);
        $this->em->flush();

        return $category;
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
        $currentPage = (int) $page <= 0 ? 0 : $page-1;
        $page = (int) $page <= 0 ? 0 : ($page-1) * $productLimit;
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
            $isFiltered = false;    
        }
        else{
              
            switch( $catType ){
                case "custom":
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsMemberProdcat")
                                                   ->getAllCustomCategoryProducts($memberId, $arrCatId, $condition, $orderBy);
                    break;
                default:
                    $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                                   ->getAllNotCustomCategorizedProducts($memberId, $arrCatId, $condition, $orderBy);
                    break;
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
        // Fetch product object and append image
        foreach($categoryProductIds as $productId){
            $product = $this->productManager->getProductDetails($productId);
            $objImage = $this->em->getRepository("EasyShop\Entities\EsProductImage")
                                ->getDefaultImage($productId);       
            $secondaryProductImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                              ->getSecondaryImage($productId);
            if(!$objImage){
                $product->directory = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                $product->imageFileName = \EasyShop\Entities\EsProductImage::IMAGE_UNAVAILABLE_FILE;
            }
            else{
                $product->directory = $objImage->getDirectory();
                $product->imageFileName = $objImage->getFilename();
            }
            $product->secondaryImageDirectory = null;
            $product->secondaryImageFileName = null;
            if($secondaryProductImage){
                $product->secondaryImageDirectory = $secondaryProductImage->getDirectory();
                $product->secondaryImageFileName = $secondaryProductImage->getFilename();
            }
            $categoryProducts[] = $product;
        }

        // Generate result array
        $result = array(
            'products' => $categoryProducts,
            'filtered_product_count' => $productCount
        );

        return $result;
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

        
        $categoryNestedSetCount = $this->em->getRepository('EasyShop\Entities\EsCategoryNestedSet')
                                            ->getNestedSetCategoryCount();
        if($categoryNestedSetCount === 0){
                $rawVendorCategories = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getUserCategoriesUsingAdjacencyList($memberId);
        }
        else{
                $rawVendorCategories = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getUserCategoriesUsingNestedSet($memberId);
        }    

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

    
    
    
    
    
} 
