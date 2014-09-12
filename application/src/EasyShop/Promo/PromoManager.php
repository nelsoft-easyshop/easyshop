<?php

namespace EasyShop\Promo;

use EasyShop\ConfigLoader\ConfigLoader as ConfigLoader;

/**
 * PromoManager Class
 *
 */
class PromoManager 
{
    /**
     * Codeigniter Config Loader
     *
     * @var EasyShop\CollectionHelper\CollectionHelper
     */
    private $configLoader;

    /**
     * Promo config
     *
     * @var mixed
     */
    private $promoConfig = array();

    /**
     * Constructor
     *
     * @param EasyShop\ConfigLoader\ConfigLoader ConfigLoader
     */
    public function __construct(ConfigLoader $configLoader)
    {
        $this->promoConfig = $configLoader->getItem('promo', 'Promo');
    }
    
    /**
     * Returns the promo configuration array
     *
     * @param integer $type 
     * @return mixed
     */
    public function getPromoConfig($type = null)
    {
        if($type !== null){
            return isset($this->promoConfig[$type]) ? $this->promoConfig[$type] : array();
        }

        return $this->promoConfig;
    }

    /**
     * Hydrates the product entity with promo-related data
     * Product object is modified by reference.
     *
     * @param Product $product
     *
     */   
    public function hydratePromoData(&$product)
    {
        $product->setOriginalPrice($product->getPrice());
        if(intval($product->getIsPromote()) === 1){
            $promoType = $product->getPromoType();
            if(isset($this->promoConfig[$promoType])){
                if(isset($this->promoConfig[$promoType]['implementation']) &&
                   trim($this->promoConfig[$promoType]['implementation']) !== ''
                ){
                    $promoImplementation = $this->promoConfig[$promoType]['implementation'];
                    $promoOptions = $this->promoConfig[$promoType]['option'];
                    $promoObject = new $promoImplementation($product);
                    $promoObject->setOptions($promoOptions);
                    $product = $promoObject->apply();
                }
              
            }
        }
        else{
            if(intval($product->getDiscount('discount')) > 0){
                $regularDiscountPrice = $product->getPrice() * (1.0-($product->getDiscount()/100.0));
                $product->setPrice( (floatval($regularDiscountPrice)>0) ? $regularDiscountPrice : 0.01 );
            }  
        }
 
        $percentage = 100.00 * ($product->getOriginalPrice() - $product->getPrice())/$product->getOriginalPrice();
        $product->setDiscountPercentage($percentage); 
    }
    
    
    /**
     * Returns the product checkout limit based on a promo.
     * This method does not take into consideration which user will buy the
     * the product. Checking if an item can be bought by a particular user is 
     * the responsibility of a separate service. Also note that the option
     * quantity limit will take precedence over any other quantity limits.
     *
     * @param Product $product
     * @return integer
     * 
     */
    public function getPromoQuantityLimit($product)
    {
        $promoQuantityLimit = PHP_INT_MAX;
        $isPromoActive = $product->getStartPromo();
        
        if($product->getStartPromo() && $product->getIsPromote()){  
                $promoConfig = $this->promoConfig[$product->getPromo_type()];
                $promoOptions = $promoConfig['option'];
                $timeNow = strtotime(date('H:i:s'));
                $startDateTime = $product->getStartdate()->getTimestamp();
                $endDateTime = $product->getEnddate()->getTimestamp();
                
                foreach($promoOptions as $option ){
                    if((strtotime($option['start']) <= $timeNow) && (strtotime($option['end']) > $timeNow)){
                        $promoQuantityLimit = $option['purchase_limit'];
                        $startDatetime = date('Y-m-d',strtotime($startDatetime)).' '.$option['start'];
                        $endDatetime = date('Y-m-d',strtotime($endDatetime)).' '.$option['end'];
                        break;
                    }
                }

                if(isset($opt['puchase_limit'])){
                    $soldCount = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                        ->getSoldCount($product->product_id, $startDatetime, $endDatetime);
                    $promoQuantityLimit = $option['purchase_limit'] - $soldCount;
                    $promoQuantityLimit = ($promoQuantityLimit >= 0) ? $promoQuantityLimit : 0;
                }
                else{
                    $promoQuantityLimit = $promoConfig['purchase_limit'];
                }

        }

        return $promoQuantityLimit;
    }
    
    /**
     * Applies discount to a product 
     * This has been refactored with hydrate promo data
     *
     * @param  float   $basePrice       [description]
     * @param  [type]  $startDate       [description]
     * @param  [type]  $endDate         [description]
     * @param  integer $isPromo         [description]
     * @param  integer $promoType       [description]
     * @param  integer $discountPercent [description]
     * @return [type]                   [description]
     */
    public function applyDiscount($basePrice = 0.00, $startDate, $endDate, $isPromo = 0, $promoType = 0, $discountPercent = 0)
    {

        $promoArray = $this->promoConfig[$promoType]; 
        $today = strtotime( date("Y-m-d H:i:s"));
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        $discountMultiplier = $discountPercent / 100;
        $booleanStartPromo = false;
        $return['endPromo'] = ($today > $endDate) ? true : false;
        $return['price'] = $basePrice; 
        if(intval($isPromo) === 1){
            $option = isset($promoArray['option'])?$promoArray['option']:array();
            $calculationId = $promoArray['calculation_id'];
            switch ($calculationId) {
                case 1:
                    if(($today < $startDate) 
                        || ($endDate < $startDate)){
                        $diffHours = 0;
                    }
                    else if($today >= $endDate){
                        $diffHours = 49.5;
                        $booleanStartPromo = true;
                    }
                    else{
                        $diffHours = floor(($today - $startDate) / 3600.0);
                        $booleanStartPromo = true;
                    }

                    $PromoPrice = $basePrice - (($diffHours * 0.02) * $basePrice);
                    break;
                
                case 2:
                    if(($today < $startDate) 
                        || ($endDate < $startDate) 
                        || ($today > $endDate)){
                        $PromoPrice = $basePrice;
                    }
                    else{
                        $PromoPrice = $basePrice - ($basePrice*$discountMultiplier) ;
                        $booleanStartPromo = true;
                    }
                    break;

                case 3:
                    $Ymd = strtotime(date('Y-m-d', $today));
                    $His = strtotime(date('H:i:s', $today));
                    if($Ymd === strtotime(date('Y-m-d',$startDate)) ){
                        foreach($option as $opt){
                            if((strtotime($opt['start']) <= $His)
                                 && (strtotime($opt['end']) > $His)){
                                $booleanStartPromo = true;
                                break;
                            }
                        }
                    }
                    $PromoPrice = $basePrice - ($basePrice * $discountMultiplier) ;
                    break;
                case 4 :
                    $PromoPrice = $basePrice;
                    if(!( ($today < $startDate) 
                        || ($endDate < $startDate) 
                        || ($today > $endDate))){
                        $booleanStartPromo = true;
                    }
                    break;

                case 0:
                default:
                    $PromoPrice = $basePrice;
                    break;
            }

            $return['price'] = $PromoPrice;
        }

        $return['price'] = (floatval($return['price'])>0) ? $return['price'] : 0.01;
        $return['startPromo'] = $booleanStartPromo; 

        return $return;
    }
    
}

