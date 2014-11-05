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

        $Ymd = strtotime(date('Y-m-d', $this->dateToday->getTimeStamp()));
        $His = strtotime(date('H:i:s', $this->dateToday->getTimeStamp()));
        $this->promoPrice = $this->product->getPrice();
        
        if($Ymd === strtotime(date('Y-m-d',$this->startDate->getTimeStamp()))){
            foreach ($this->option as $promoPeriod) {
                if ((strtotime($promoPeriod['start']) <= $His) && (strtotime($promoPeriod['end']) > $His)) {
                    $this->isStartPromo = true;
                    $this->promoPrice = $this->product->getPrice() - ($this->product->getPrice() * $this->product->getDiscount()/100.0);
                    break;
                }
            }
        }

        $this->isEndPromo = ($this->dateToday > $this->endDateTime) ? true : false;
        $this->persist();
        
        return $this->product;
    }

    /**
     * Calculates Promo Price
     *
     * @param $price
     * @param $startDate
     * @param $endDate
     * @param $discount
     * @return float
     */
    public static function getPrice($price, $startDate, $endDate, $discount)
    {
        return $price - ($price * $discount/100.0);
    }
}
