<?php

namespace EasyShop\Product;

use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder; 
use EasyShop\Entities\EsProductShippingHead; 

/**
 * Product Manager Class
 *
 * @author Ryan Vasquez
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
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$promoManager,$collectionHelper)
    {
        $this->em = $em; 
        $this->promoManager = $promoManager;
        $this->collectionHelper = $collectionHelper;

        $this->ci = get_instance();  
        $this->promoArray = $this->ci->config->item('Promo');
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
    public function getDiscountedPrice($memberId,$product = array())
    { 
        foreach ($product as $key => $value) {
            $buyerId = $memberId;
            $productId =$value['idProduct'];
            $isPromote =  $value['isPromote'];
            $price =  $value['price'];  
            $startDate = $value['startdate']; 
            $endDate = $value['enddate'];
            $promoType = $value['promoType'];
            $discount = $value['discount'];
            $isSoldOut = $value['isSoldOut'];
            $startPromo = false;
            $endPromo = false;

            $promoArray = $this->promoArray[$promoType]; 

            if(intval($isPromote) === 1){
                $promo = $this->promoManager->applyDiscount($price, $startDate,$endDate,$isPromote,$promoType, $discount);
                $startPromo = $promo['startPromo'];
                $endPromo = $promo['endPromo'];
                $product[$key]['originalPrice'] = $originalPrice = $price;
                $userPurchaseCount = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                            ->getUserPurchaseCountByPromo($buyerId,$promoType);
                
                if(($userPurchaseCount[0]['cnt'] >= $promoArray['purchase_limit']) || 
                (!$promoArray['is_buyable_outside_promo'] && !$startPromo)){
                    $product[$key]['canPurchase'] =  false;
                }
                else{
                    $product[$key]['canPurchase']   = true;
                }
        
                $dateToParam = date('Y-m-d',strtotime($endDate));
                if($dateToParam === '0001-01-01' ){
                    $dateToParam = date('Y-m-d');
                }

                $soldPrice = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                            ->getSoldPrice($productId, date('Y-m-d',strtotime($startDate)), $dateToParam);
                                            
                $price = ($isSoldOut) ? $soldPrice : $promo['price']; 
            }
            else{
                $product[$key]['originalPrice'] = $originalPrice = $price;
                $product[$key]['canPurchase'] = true;
                if(intval($discount) > 0){
                    $price = $price * (1.0-($discount/100.0));
                }  
            }
            
            if($originalPrice <= 0){
                $product[$key]['percentage'] = 0;
            }
            else{
                $product[$key]['percentage'] = ($originalPrice - $price)/$originalPrice * 100.00;
            }

            $product[$key]['price'] = $price;
            $product[$key]['isFreeShipping'] = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                            ->getShippingTotalPrice($productId);
        }

        return $product;
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
}