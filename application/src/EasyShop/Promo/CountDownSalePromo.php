<?php

namespace EasyShop\Promo;

class CountDownSalePromo extends AbstractPromo
{

    /**
     * Maximum hour differential for the promo
     * @var float
     *
     */
    private $maxHourDifferential = 49.5;

    /**
     * The percentage discount per hour
     * @var float
     *
     */
    private $percentagePerHour = 2.00;
    
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

    
}

