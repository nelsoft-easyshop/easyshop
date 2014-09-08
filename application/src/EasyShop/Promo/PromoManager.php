<?php

namespace EasyShop\Promo;

/**
 * Promo Class
 *
 * @author Ryan Vasquez
 */
class PromoManager
{

    /**
     * Constructor.
     */
    public function __construct()
    {          
        $this->ci = get_instance();  
        $this->promoArray = $this->ci->config->item('Promo');
    }

    /**
     * Applies discount to a product
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
        $promoArray = $this->promoArray[$promoType];
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