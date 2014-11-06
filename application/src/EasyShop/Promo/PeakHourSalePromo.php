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

    /**
     * Checks if promo has started and if promo promo has ended.
     * @param $startDate
     * @param $endDate
     * @param $option
     * @return array
     */
    public static function getAvailability($startDate, $endDate, $option = array())
    {
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $startDateTime = $startDate->getTimestamp();
        $endDateTime = $endDate->getTimestamp();
        $isAvailable = array(
            'isStartPromo' => false,
            'isEndPromo' => false,
        );

        $Ymd = strtotime(date('Y-m-d', $dateToday));
        $His = strtotime(date('H:i:s', $dateToday));

        if($Ymd === strtotime(date('Y-m-d',$startDateTime))) {
            foreach ($option as $promoPeriod) {
                if ((strtotime($promoPeriod['start']) <= $His) && (strtotime($promoPeriod['end']) > $His)) {
                    $isAvailable['isStartPromo'] = true;
                    break;
                }
            }
        }

        $isAvailable['isEndPromo'] = ($startDateTime > $endDateTime) ? true : false;

        return $isAvailable;
    }
}
