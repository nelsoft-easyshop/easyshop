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

    
}

