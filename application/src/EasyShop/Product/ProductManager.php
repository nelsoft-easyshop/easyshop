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