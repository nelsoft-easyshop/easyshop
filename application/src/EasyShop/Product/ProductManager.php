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
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
        $this->promoManager = get_instance()->kernel->serviceContainer['promo_manager']; 
    }

    /**
     * Applies discount to a product
     * @param  array  $products [description]
     * @return [type]           [description]
     */
    public function getDiscountedPrice($product = array(),$memberId)
    { 
        $CI = get_instance(); 

        foreach ($product as $key => $value) {
            $buyerId = $memberId;
            $productId =$value['idProduct'];
            $isPromote =  $value['isPromote'];
            $price =  $value['price'];  
            $startDate = $value['startdate']->format('Y-m-d H:i:s'); 
            $endDate = $value['enddate']->format('Y-m-d H:i:s');
            $promoType = $value['promoType'];
            $discount = $value['discount'];
            $isSoldOut = $value['isSoldOut'];
            $startPromo = false;
            $endPromo = false;

            if(intval($isPromote) === 1){
                $promo = $this->promoManager->applyDiscount($price, $startDate,$endDate,$isPromote,$promoType, $discount);
                $startPromo = $promo['startPromo'];
                $endPromo = $promo['endPromo'];
                $product[$key]['originalPrice'] = $originalPrice = $price;
                $userPurchaseCount = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                            ->getUserPurchaseCountByPromo($buyerId,$promoType);
                
                $promoArray = $CI->config->item('Promo')[$promoType]; 
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

}