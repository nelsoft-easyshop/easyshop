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
        $this->persist();
        $this->deleteExpired($this->product, $promoData['augmentedDiscount'], $this->isStartPromo);
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
    public static function getPromoData($price, $startDate, $endDate, $discount, $option = array())
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
        $His = strtotime(date('H:i:s', $dateToday));
        $discountPerHour = 0;

        foreach ($option as $promoPeriod) {
            if ( strtotime($promoPeriod['start']) <= $His ) {
                $start_date = new DateTime($promoPeriod['start']);
                if ($His <=strtotime($promoPeriod['end'])) {
                    $since_start = $start_date->diff(new DateTime($His));
                }
                else {
                    $since_start = $start_date->diff(new DateTime($promoPeriod['end']));
                }
                $discountPerHour = $discountPerHour + (($since_start->h + 1) * $promoPeriod['discount']);
            }
        }

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
//        $promoPrice = $price - (($diffHours * self::$percentagePerHour / 100.0) * $price);
        $promoPrice = $price - (($discountPerHour / 100.0) * $price);
        $promoPrice = ($promoPrice <= 0) ? 0.01 : $promoPrice;
        $promoDetails['promoPrice'] = $promoPrice;
        $promoDetails['isEndPromo'] = ($dateToday > $endDateTime);
//        $promoDetails['augmentedDiscount'] = $diffHours * self::$percentagePerHour;
        $promoDetails['augmentedDiscount'] = $discountPerHour;

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
            $product->setIsDelete(EsProduct::DELETE);
            $this->persist();
        }
    }
}
