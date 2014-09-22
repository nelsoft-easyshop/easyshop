<?php

namespace EasyShop\Promo;

class PeakHourSalePromo extends AbstractPromo
{

  
    /**
     * Applies the peak hour calculation
     *
     * @return EasyShop\Entities\Product
     */
    public function apply()
    {
        if(!isset($this->product)){
            return null;
        }

        $Ymd = strtotime(date('Y-m-d', $today->getTimeStamp());
        $His = strtotime(date('H:i:s', $today->getTimeStamp());
        $this->promoPrice = $product->getPrice();
        
        if($Ymd === strtotime(date('Y-m-d',$this->startDate->getTimeStamp()))){
            foreach($this->option as $promoPeriod){
                if((strtotime($promoPeriod['start']) <= $His) && (strtotime($opt['end']) > $His)){
                    $this->isStartPromo = true;
                    $this->promoPrice = $product->getPrice() - ($product->getPrice() * $product->getDiscount()/100.0);
                    break;
                }
            }
        }

        $this->isEndPromo = ($this->dateToday > $this->endDateTime) ? true : false;
        $this->persist();        
        
        return $this->product;
    }



    
}

