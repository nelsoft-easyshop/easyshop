<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentGateway;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;

/**
 * Cash On Delivery Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class CashOnDeliveryGateway extends AbstractGateway
{

    private $paymentType;

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        parent::__construct($em, $request, $pointTracker, $paymentService, $params);
    }

    /**
     * Pay method for Cash On Delivery Gateway Class
     * 
     */
    public function pay()
    {
        $response = [];
        $lastDigit = $this->getParameter('lastDigit');
        if(intval($lastDigit) === 2){
            $response['paymentType'] = EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT;
            $response['textType'] = 'directbankdeposit';
            $response['message'] = 'Your payment has been completed through Direct Bank Deposit.';
        }
        else{
            $response['paymentType'] = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
            $response['textType'] = 'cashondelivery';
            $response['message'] = 'Your payment has been completed through Cash on Delivery.';
        }
        $this->setParameter('paymentType', $response['paymentType']);
        return $response;
    }

    public function getExternalCharge()
    {
        return 0;
    }

    public function generateReferenceNumber($memberId)
    {
        return 'COD-'.date('ymdhs').'-'.$memberId;
    }

    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }

    public function getOrderProductStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }

}

/*
    Params needed
        method:"CashOnDelivery", 
        lastDigit:$('input[name=paymentToken]').val().slice(-1)
*/

