<?php

namespace EasyShop\Promo;

class ListingPromo extends AbstractPromo
{


    /**
     * Applies the guessThePricePromo calulcation
     *
     * @return EasyShop\Entities\Product
     */
    public function apply()
    {
        if(!isset($this->product)){
            return null;
        }

        $this->promoPrice = $this->product->getPrice();
        if(!($this->dateToday < $this->startDateTime) ||
            ($this->endDateTime < $this->startDateTime) ||
            ($this->dateToday > $this->endDateTime))
        ){
            $this->isStartPromo = true;
        }
          
        $this->isEndPromo = ($this->dateToday > $this->endDateTime) ? true : false;
        $this->persist();        
        
        return $this->product;
    }


}

