<?php

namespace EasyShop\Promo;
use Doctrine\ORM\Mapping\Entity;
use EasyShop\Entities\EsProduct;
use DateTime;

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
    private static $percentagePerHour = 5.00;

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
            $this->product->getDiscount(),
            $this->option
        );
        $this->promoPrice = $promoData['promoPrice'];
        $this->isStartPromo = $promoData['isStartPromo'];
        $this->isEndPromo = $promoData['isEndPromo'];
        $this->deleteExpired($this->product, $promoData['augmentedDiscount'], $this->isStartPromo);
        $this->persist();
        return $this->product;
    }

    /**
     * Calculates Promo Price and Checks if promo has started and if promo promo has ended.
     * NOTE : Commented lines are formula use for the normal countdown sale
     * @param $price
     * @param $startDate
     * @param $endDate
     * @param $discount
     * @param $option
     * @return array
     */
    public static function getPromoData($price, $startDate, $endDate, $discount, $option)
    {
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $startDateTime = $startDate->getTimestamp();
        $endDateTime = $endDate->getTimestamp();
        $promoDetails = [
            'isStartPromo' => false,
            'isEndPromo' => false,
            'promoPrice' => $price
        ];
        $timeNow = strtotime(date('H:i:s', $dateToday));
        $totalDiscountPercentage = 0;

        if ($dateToday >= $startDateTime && $dateToday <= $endDateTime) {
            $diffHours = floor(($dateToday - $startDateTime) / 3600.0);
            $promoDetails['isStartPromo'] = true;
            if ( isset($option) ) {
                foreach ($option as $promoPeriod) {
                    if ( strtotime($promoPeriod['start']) <= $timeNow ) {
                        $startTime = new DateTime($promoPeriod['start']);
                        if ($timeNow <= strtotime($promoPeriod['end'])) {
                            $sinceStart = $startTime->diff(new DateTime(date('H:i:s', $dateToday)));
                        }
                        else {
                            $sinceStart = $startTime->diff(new DateTime($promoPeriod['end']));
                        }
                        $totalDiscountPercentage = $totalDiscountPercentage + (($sinceStart->h + 1) * $promoPeriod['discountPerHour']);
                    }
                }
            }
            else {
                $totalDiscountPercentage = $diffHours * self::$percentagePerHour;
            }
        }

        $promoPrice = $price - (($totalDiscountPercentage / 100.0) * $price);
        $promoPrice = ($promoPrice <= 0) ? 0.01 : $promoPrice;
        $promoDetails['promoPrice'] = $promoPrice;
        $promoDetails['isEndPromo'] = ($dateToday > $endDateTime);
        $promoDetails['augmentedDiscount'] = $totalDiscountPercentage;
        return $promoDetails;
    }

    /**
     * Soft delete product if reached the max allowable discount
     * @param $product
     * @param $augmentedDiscount
     * @param $isStartPromo
     */
    private function deleteExpired($product, $augmentedDiscount, $isStartPromo)
    {
        if ( (int) $augmentedDiscount >= (int) $product->getDiscount() && (int) $product->getDiscount() !== 0 && $isStartPromo ) {
            $product->isExpired = true;
        }
    }
}
