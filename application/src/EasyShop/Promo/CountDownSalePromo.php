<?php

namespace EasyShop\Promo;

use Symfony\Component\Validator\Constraints\DateTime;

class CountDownSalePromo extends AbstractPromo
{

    /**
     * Maximum hour differential for the promo
     * @var float
     *
     */
    private static $maxHourDifferential = 49.5;

    /**
     * The percentage discount per hour
     * @var float
     *
     */
    private static $percentagePerHour = 2.00;
    
    /**
     * Applies the count down sale calculations
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
        
        if(($this->dateToday < $this->startDateTime) || ($this->endDateTime < $this->dateToday)) {
            $diffHours = 0;
        }
        else if($this->dateToday > $this->endDateTime){
            $diffHours = $this->maxHourDifferential;
            $this->isStartPromo = true;
        }
        else{
            $diffHours = floor(($this->dateToday - $this->startDateTime) / 3600.0);
            $this->isStartPromo = true;
        }
        
        $this->promoPrice = $this->product->getPrice() - (($diffHours * $this->percentagePerHour / 100.0) * $this->product->getPrice());
        $this->promoPrice = ($this->promoPrice <= 0) ? 0.01 : $this->promoPrice;
        $this->isEndPromo = ($this->dateToday > $this->endDateTime) ? true : false;
        
        $this->persist();        
        
        return $this->product;
    }

    /**
     * Calculates Promo Price
     * @param $price
     * @param $startDate
     * @param $endDate
     * @param $discount
     * @return float
     */
    public static function getPrice($price, $startDate, $endDate, $discount)
    {
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $startDateTime = $startDate->getTimestamp();
        $endDateTime = $endDate->getTimestamp();

        if (($dateToday < $startDateTime) || ($endDateTime < $dateToday)) {
            $diffHours = 0;
        }
        else if ($dateToday > $endDateTime) {
            $diffHours = self::$maxHourDifferential;
        }
        else {
            $diffHours = floor(($dateToday - $startDateTime) / 3600.0);
        }

        $promoPrice = $price - (($diffHours * self::$percentagePerHour / 100.0) * $price);
        $promoPrice = ($promoPrice <= 0) ? 0.01 : $promoPrice;

        return $promoPrice;
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
