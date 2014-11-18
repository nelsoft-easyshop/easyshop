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
        $promoData = $this->getPromoData(
            $this->product->getPrice(),
            $this->startDateTime,
            $this->endDateTime,
            $this->product->getDiscount(),
            $this->option
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
            'isEndPromo' => ($dateToday > $endDateTime) ? true : false
        );

        $Ymd = strtotime(date('Y-m-d', $dateToday));
        $His = strtotime(date('H:i:s', $dateToday));
        
        if($Ymd >= strtotime(date('Y-m-d',$startDateTime))) {
            foreach ($option as $promoPeriod) {
                if ((strtotime($promoPeriod['start']) <= $His) && (strtotime($promoPeriod['end']) > $His)) {
                    $promoDetails['isStartPromo'] = true;
                    $promoDetails['promoPrice'] = $price - ($price * $discount/100.0);
                    break;
                }
            }
        }

        return $promoDetails;
    }

}
