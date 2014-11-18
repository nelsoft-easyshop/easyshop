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
        $promoData = $this->getPromoData(
            $this->product->getPrice(),
            $this->startDateTime,
            $this->endDateTime,
            $this->product->getDiscount()
        );

        $this->promoPrice = $promoData['promoPrice'];
        $this->isStartPromo = $promoData['isStartPromo'];
        $this->isEndPromo = $promoData['isEndPromo'];
        $this->persist();

        return $this->product;
    }

    /**
     * Calculates Promo Price and Checks if promo has started and if promo promo has ended.
     * @param $price
     * @param $startDate
     * @param $endDate
     * @param $discount
     * @param $option
     * @return array
     */
    public static function getPromoData($price, $startDate, $endDate, $discount, $option = array())
    {
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $startDateTime = $startDate->getTimestamp();
        $endDateTime = $endDate->getTimestamp();
        $promoDetails = array(
            'isStartPromo' => false,
            'isEndPromo' => false,
            'promoPrice' => $price
        );

        if (($dateToday < $startDateTime) || ($endDateTime < $dateToday)) {
            $diffHours = 0;
        }
        else if ($dateToday > $endDateTime) {
            $diffHours = self::$maxHourDifferential;
            $promoDetails['isStartPromo'] = true;
        }
        else {
            $diffHours = floor(($dateToday - $startDateTime) / 3600.0);
            $promoDetails['isStartPromo'] = true;
        }

        $promoPrice = $price - (($diffHours * self::$percentagePerHour / 100.0) * $price);
        $promoPrice = ($promoPrice <= 0) ? 0.01 : $promoPrice;
        $promoDetails['promoPrice'] = $promoPrice;
        $promoDetails['isEndPromo'] = ($startDateTime > $endDateTime) ? true : false;

        return $promoDetails;
    }

}
