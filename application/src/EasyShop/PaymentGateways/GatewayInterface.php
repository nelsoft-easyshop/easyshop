<?php

namespace EasyShop\PaymentGateways;

interface GatewayInterface
{
    public function pay();

    public function getPaymentMethodName();

    public function getAmountAllocated();

    public function setAmountAllocated($newAmount);


    /*
        Breakdown = [$param_arr1, $param_arr2 ...]
            $param_arr1 -> parameter array for the first payment method
        
        $param_arrx = [
                    'name' => unique string for referencing this gateway,
                    'method' => COD/POINT .. , 
                    'amount' => amount allocated, 
                    'param1' => param,
                    'param2' => param
                    ]
    */

    public function setParameters($param = []);
    
    public function getParameter($key);
}