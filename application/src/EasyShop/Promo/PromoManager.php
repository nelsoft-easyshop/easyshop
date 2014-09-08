<?php

namespace EasyShop\Promo;

/**
 * PromoManager Class
 *
 * @author Ryan Vasquez
 * @author Sam Gavinio
 */
class PromoManager
{  
    
    /**
     * The CI singleton
     *
     */
    private $CI;
    
    
    /**
     * Map of promo types to promo implementations
     *
     * @var string[]
     */
    private $promoMap = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('promo', true);
        $this->promoConfig = $this->CI->config->item('Promo');
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
                $promoImplementation = $this->promoConfig[$promoType]['implementation'];
                $promoOptions = $this->promoConfig[$promoType]['option'];
                $promoObject = new $promoImplementation($product);
                $promoObject->setOptions($promoOptions);
                $product = $promoObject->apply();
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
    
}

