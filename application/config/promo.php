<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'Promo' => array(
        '0' =>array(
            'calculation_id' => '0',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => array(
                            // 'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal',
                            'dragonpay'=>'Dragon Pay',
                            // 'dbd'=>'Direct Bank Deposit',
                            'cod'=>'Cash on Delivery',
                            'pesopaycdb'=>'Credit or Debit Card'
            ),
            'banner' => '',
            'cart_solo_restriction' => false,
        ),
        '1' => array(
            'calculation_id' => '1',
            'purchase_limit' => 1,
            'payment_method' => array(
                            'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal'
            ),
            'banner' => 'countdown',
            'cart_solo_restriction' => true,
        ),
        '2' => array(
            'calculation_id' => '2',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' =>  array(
                            'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal',
                            'dragonpay'=>'Dragon Pay',
                            // 'dbd'=>'Direct Bank Deposit',
                            'cod'=>'Cash on Delivery'
            ),
            'banner' => 'fixeddiscount',
            'cart_solo_restriction' => false,
        ),
        '3' => array(
            'calculation_id' => '3',
            'purchase_limit' => 'peak_hour_and_limit',
            'payment_method' =>  array(
                'cdb'=>'Credit or Debit Card',
                'paypal'=>'Paypal',
                'dragonpay'=>'Dragon Pay',
                // 'dbd'=>'Direct Bank Deposit',
                'cod'=>'Cash on Delivery'
            ),
            'banner' => '',
            'cart_solo_restriction' => false,
            'peak_hour_and_limit' => array(
                0 => array(
                    'start' => '12:00:00',
                    'end' => '13:59:59',
                    'purchase_limit' => 5),
                1 => array(
                    'start' => '20:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => PHP_INT_MAX)
            )
        )
    )
);


/* End of file promo.php */
/* Location: ./application/config/promo.php */

