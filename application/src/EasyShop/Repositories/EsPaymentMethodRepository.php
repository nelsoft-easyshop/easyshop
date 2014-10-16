<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use EasyShop\Entities\EsPaymentMethod;

class EsPaymentMethodRepository extends EntityRepository
{
    /**
     * Returns declared paymentMethods constants
     * @return ARRAY
     */        
    public function getPaymentMethods()
    {
        return array(
                 EsPaymentMethod::PAYMENT_PAYPAL, 
                 EsPaymentMethod::PAYMENT_DRAGONPAY, 
                 EsPaymentMethod::PAYMENT_CASHONDELIVERY, 
                 EsPaymentMethod::PAYMENT_PESOPAYCC, 
                 EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT, 
                 EsPaymentMethod::PAYMENT_POINTS
                );   
    }
}