<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("PAYPAL",  1);
define("DRAGONPAY_OFFLINE",2);
define("CASH_ON_DELIVERY",3);
define("DRAGONPAY_ONLINE",4);
define("DIRECT_BANK",5);

$config = array(
    'Promo' => array(
        '0' =>array(
            'calculation_id' => '0',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => array(PAYPAL,DRAGONPAY_OFFLINE,CASH_ON_DELIVERY,DRAGONPAY_ONLINE,DIRECT_BANK),
            'banner' => '',
        ),
        '1' => array(
            'calculation_id' => '1',
            'purchase_limit' => '1',
            'payment_method' => array(PAYPAL),
            'banner' => 'countdown',
        ),
        '2' => array(
            'calculation_id' => '2',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => array(PAYPAL,DRAGONPAY_OFFLINE,CASH_ON_DELIVERY,DRAGONPAY_ONLINE,DIRECT_BANK),
            'banner' => 'fixeddiscount',
        ),
    )
);


/* End of file promo.php */
/* Location: ./application/config/promo.php */

