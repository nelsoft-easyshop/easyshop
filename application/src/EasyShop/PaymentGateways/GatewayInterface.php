<?php

namespace EasyShop\PaymentGateways;


/**
 * Payment gateway interface
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
interface GatewayInterface
{
    public function pay($validatedCart, $memberId);

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
        Only the first three params are required, the rest are
        specific to each gateway
    */

    public function setParameters($param = []);
    
    public function getParameter($key);
}
