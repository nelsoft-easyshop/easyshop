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
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor
     *
     * @param EasyShop\ConfigLoader\ConfigLoader $configLoader
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct(ConfigLoader $configLoader, \Doctrine\ORM\EntityManager $em)
    {
        $this->promoConfig = $configLoader->getItem('promo', 'Promo');
        $this->em = $em;
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
        $product->setFinalPrice($product->getPrice());
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
                $product->setFinalPrice( (floatval($regularDiscountPrice)>0) ? $regularDiscountPrice : 0.01 );
            }
        }
 
        $percentage = 100.00 * ($product->getOriginalPrice() - $product->getFinalPrice())/$product->getOriginalPrice();
        $product->setDiscountPercentage($percentage); 
    }

    /**
     * Function to calculate the promo price quickly. This is designed for bulk operations within large loops
     *
     * @param integer $productId
     * @return float
     */
    public function hydratePromoDataExpress($productId)
    {
        
        $productDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')->getRawProductPromoDetails($productId);
        if(!$productDetails){
            return 0;
        }
        
        $price = $productDetails['price'];
        $discount = $productDetails['discount'];
        $isPromote = $productDetails['is_promote'];
        $startDate = $productDetails['startdate'];
        $endDate = $productDetails['enddate'];
        $promoType = $productDetails['promo_type'];

        $startDate = date_create($startDate);
        $endDate = date_create($endDate); 

        $promoPrice = $price;
        if (intval($isPromote) === 1) {
            if (isset($this->promoConfig[$promoType])) {
                if (isset($this->promoConfig[$promoType]['implementation']) &&
                    trim($this->promoConfig[$promoType]['implementation']) !== ''
                ) {
                    $promoImplementation = $this->promoConfig[$promoType]['implementation'];
                    $promo = $promoImplementation::getPromoData($price, $startDate, $endDate, $discount,$this->promoConfig[$promoType]['option']);
                    $promoPrice = $promo['promoPrice'];
                }
            }
        }
        else {
            if (intval($discount) > 0) {
                $regularDiscountPrice = $price * (1.0-($discount/100.0));
                $promoPrice = (floatval($regularDiscountPrice)>0) ? $regularDiscountPrice : 0.01;
            }
        }
        return $promoPrice;
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
                $promoConfig = $this->promoConfig[$product->getPromoType()];
                $promoOptions = $promoConfig['option'];
                $timeNow = strtotime(date('H:i:s'));
                $startDatetime = $product->getStartdate()->getTimestamp();
                $endDatetime = $product->getEnddate()->getTimestamp();
                
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

