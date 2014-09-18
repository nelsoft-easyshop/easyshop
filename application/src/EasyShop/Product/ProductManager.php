<?php

namespace EasyShop\Product;

use Easyshop\Promo\PromoManager as PromoManager;
use EasyShop\ConfigLoader\ConfigLoader as ConfigLoader;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder; 
use EasyShop\Entities\EsProduct; 
use EasyShop\Entities\EsProductShippingHead; 

use EasyShop\Entities\EsMemberProdcat;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Easyshop\Entities\EsProducItemLock;

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
     * Applies discount to a product
     * This has been refactored with hydrate promo data
     * @param  array  $products [description]
     * @return [type]           [description]
     */
    public function getDiscountedPrice($memberId,$products = array())
    { 
        foreach ($products as $key => $value) { 
            $productObject = $value->getProduct();
            $resultObject = $this->promoManager->hydratePromoData($productObject);
        } 
        
        return $products;
    }

    /**
     * function that will get all possible keyword tied on selected product
     * @return [type] [description]
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
                                            ->getAttributes($productId);

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
                    'child_cat' => array(),
                    'products' => array(),
                    'product_count' => 0,
                    'cat_link' => base_url(). 'category/' . $vendorCategory['p_cat_slug'],
                    'cat_img' => $categoryImage
                );
            }
            // For products whose parent is 'PARENT'
            else if( !isset($vendorCategories[$vendorCategory['parent_cat']]) && intval($vendorCategory['parent_cat']) === 1 ) {
                $vendorCategories[$vendorCategory['parent_cat']] = array(
                    'name' => 'Others',
                    'slug' => '',
                    'child_cat' => array(),
                    'products' => array(),
                    'product_count' => 0,
                    'cat_link' => '',
                    'cat_img' => $defaultCatImg
                );
            }
            $vendorCategories[$vendorCategory['parent_cat']]['child_cat'][] = $vendorCategory['cat_id'];
            $vendorCategories[$vendorCategory['parent_cat']]['product_count'] += $vendorCategory['prd_count'];
        }

        // Move OTHERS at the end of array
        if(isset($vendorCategories[1])){
            $vendorCategories[1001] = $vendorCategories[1];
            unset($vendorCategories[1]);
        }

        return $vendorCategories;
    }

}

