<?php

namespace EasyShop\Promo;

class BuyAtZeroPromo extends AbstractPromo
{

    /**
     * Applies the buy at zero promo calculation
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
        
        if(($this->dateToday < $this->startDateTime) || 
          ($this->endDateTime < $this->startDateTime) ||
          ($this->dateToday > $this->endDateTime))
        {
            $this->promoPrice = $this->product->getPrice();
        }
        else{
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

        if($dateToday >= $startDateTime && $dateToday <= $startDateTime) {
            $isAvailable['isStartPromo'] = true;
        }

        $isAvailable['isEndPromo'] = ($startDateTime > $endDateTime) ? true : false;

        return $isAvailable;
    }
}
