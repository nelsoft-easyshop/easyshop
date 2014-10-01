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
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$promoManager,$collectionHelper,$configLoader)
    {
        $this->em = $em; 
        $this->promoManager = $promoManager;
        $this->collectionHelper = $collectionHelper;
        $this->configLoader = $configLoader;
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
     * This has been refactored with hydrate promo data
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
                    ->setProduct($product);
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
                    'cat_type' => EsCat::CUSTOM_TYPE
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
                    'cat_type' => EsCat::CUSTOM_TYPE
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
     *  Fetch products under parent category, based on child cat ids ($arrCatId)
     *
     *  @param integer $memberId
     *  @param array $arrCatId 
     *
     *  @return array - filter count of products and array of product objects
     */
    public function getVendorDefaultCategoryAndProducts($memberId, $arrCatId, $productLimit = 12, $page = 0, $orderBy = array("clickcount"=>"DESC"), $condition = "", $lprice = "", $uprice ="")
    {
        // Container for products fetched
        $categoryProducts = array();

        // Condition parameters passed
        $page = intval($page) <= 0 ? 0 : (intval($page)-1) * $productLimit;
        $condition = strval($condition);

        $lprice = str_replace(",", "", (string)$lprice);
        $uprice = str_replace(",", "", (string)$uprice);

        $categoryProductIds = $this->em->getRepository("EasyShop\Entities\EsProduct")
                                        ->getNotCustomCategorizedProducts($memberId, $arrCatId);
        
        // Fetch product object and append image
        foreach($categoryProductIds as $productId){
            $product = $this->getProductDetails($productId);
            $objImage = $this->em->getRepository("EasyShop\Entities\EsProductImage")
                                ->getDefaultImage($productId);
            $product->directory = $objImage->getDirectory();
            $product->imageFileName = $objImage->getFilename();

            $categoryProducts[] = $product;
        }

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
        $filteredProductsCount = count($arrCollectionProducts->matching($criteria));

        // Filter number of results (pagination)
        $criteria->setFirstResult($page)
                ->setMaxResults($productLimit);

        // Push products to be displayed
        $displayProducts = $arrCollectionProducts->matching($criteria);

        // Generate result array
        $result = array(
            'products' => $displayProducts,
            'filtered_product_count' => $filteredProductsCount
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
        $CI =& get_instance();
        $CI->load->library('image_lib');               

        $config['image_library'] = 'GD2';
        $config['source_image'] = $imageDirectory;
        $config['maintain_ratio'] = true;
        $config['quality'] = '85%';
        $config['new_image'] = $newDirectory;
        $config['width'] = $dimension[0];
        $config['height'] = $dimension[1]; 

        $CI->image_lib->initialize($config); 
        $CI->image_lib->resize();
        $CI->image_lib->clear();        
    } 

    /**
     * Generates slugs for csv products upload
     * @param string $title
     * @return STRING
     */ 
    public function generateSlugForCSVProducts($title)   
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
}

