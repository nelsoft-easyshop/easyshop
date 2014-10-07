<?php

namespace EasyShop\Promo;

class FixedDiscountPromo extends AbstractPromo
{

    /**
     * Applies the fixed discount calculation
     *
     * @return EasyShop\Entities\Product
     */
    public function apply()
    {
        if(!isset($this->product)){
            return null;
        }
        
        if(($this->dateToday < $this->startDateTime) || 
          ($this->endDateTime < $this->startDateTime) ||
          ($this->dateToday < $this->endDateTime))
        {
            $this->promoPrice = $this->product->getPrice();
        }
        else{
            $this->promoPrice = $this->product->getPrice() - ($this->product->getPrice()*$this->product->getDiscount()/100.0) ;
            $this->isStartPromo = true;
        }

        $this->isEndPromo = ($this->dateToday > $this->endDateTime) ? true : false;
        $this->persist();        
        
        return $this->product;
    }

    
}

