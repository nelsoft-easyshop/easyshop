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
        $promoData = $this->getPromoData(
            $this->product->getPrice(),
            $this->dateToday,
            $this->endDateTime,
            $this->getDiscount()
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
            'promoPrice' => $price,
            'isStartPromo' => false,
            'isEndPromo' => ($startDateTime > $endDateTime) ? true : false
        );

        if($dateToday >= $startDateTime && $dateToday <= $endDateTime) {
            $promoDetails['promoPrice'] = 0;
            $promoDetails['isStartPromo'] = true;
        }

        return $promoDetails;
    }

}
