<?php

namespace EasyShop\Promo;

class ScratchAndWinPromo extends AbstractPromo
{


    /**
     * Applies the ScratchAndWinPromo calulcation
     *
     * @return EasyShop\Entities\Product
     */
    public function apply()
    {
        if(!isset($this->product)){
            return null;
        }
        
        $this->dateToday = $this->dateToday->getTimestamp();
        $this->startDateTime = $this->startDateTime->getTimestamp();
        $this->endDateTime = $this->endDateTime->getTimestamp();
        
        $this->promoPrice = $this->product->getPrice();
        if(!($this->dateToday < $this->startDateTime) ||
            ($this->endDateTime < $this->startDateTime) ||
            ($this->dateToday > $this->endDateTime))
        {   
            $this->promoPrice = 0;
            $this->isStartPromo = true;
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
        return $price;
    }
}
