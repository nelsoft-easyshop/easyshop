<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'Promo' => array(
	/* Default, non-promo */ 
        '0' =>array(
            'calculation_id' => '0',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => array(
                            'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal',
                            'dragonpay'=>'Dragon Pay',
                            // 'dbd'=>'Direct Bank Deposit',
                            'cod'=>'Cash on Delivery',
                            //'pesopaycdb'=>'Credit or Debit Card'
            ),
            'banner' => '',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => array(),
        ),
        /* Countdown Sale */ 
        '1' => array(
            'calculation_id' => '1',
            'purchase_limit' => 1,
            'payment_method' => array(
                            'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal'
            ),
            'banner' => 'countdown',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => true,
            'option' => array(),
        ),
        /* Fix discount sale */ 
        '2' => array(
            'calculation_id' => '2',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' =>  array(
                            'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal',
                            'dragonpay'=>'Dragon Pay',
                            'cod'=>'Cash on Delivery'
            ),
            'banner' => 'fixeddiscount',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => array(),
        ),
        /* Peak time sale */ 
        '3' => array(
            'calculation_id' => '3',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' =>  array(
                'cdb'=>'Credit or Debit Card',
                'paypal'=>'Paypal',
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery'
            ),
            'banner' => '',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => false,
            'option' => array(
                0 => array(
                    'start' => '00:00:00',
                    'end' => '06:59:59',
                    'purchase_limit' => 2),
                1 => array(
                    'start' => '07:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => 15)
             ),
        ),
        '4' => array(
            'calculation_id' => '4',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' =>  array(
                'cdb'=>'Credit or Debit Card',
                'paypal'=>'Paypal',
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery'
            ),
            'banner' => 'generic',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => array(
                0 => array(
                    'start' => '00:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => 0
                ),
             )
        
        ),
    )
);


/* End of file promo.php */
/* Location: ./application/config/promo.php */

