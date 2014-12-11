<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'Promo' => array(
        /* Default, non-promo */ 
        '0' =>array(
            'implementation' => '',
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
            'viewable_button_product_page' =>true,
        ),
        /* Countdown Sale */ 
        '1' => array(
            'implementation' => 'EasyShop\Promo\CountDownSalePromo',
            'calculation_id' => '1',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => array(
                            'cdb'=>'Credit or Debit Card',
                            'paypal'=>'Paypal'
            ),
            'banner' => 'countdown',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => false,
            'option' => array(),
            'viewable_button_product_page' =>true,
        ),
        /* Fix discount sale */ 
        '2' => array(
            'implementation' => 'EasyShop\Promo\FixedDiscountPromo',
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
            'viewable_button_product_page' =>true,
        ),
        /* Peak time sale */ 
        '3' => array(
            'implementation' => 'EasyShop\Promo\PeakHourSalePromo',
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
            'viewable_button_product_page' =>true,
        ),
        /* ListingPromo */
        '4' => array(
            'implementation' => 'EasyShop\Promo\ListingPromo',
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
             ),
            'viewable_button_product_page' =>false,
        ),
        /* Scratch and win promo */
        '5' =>array(
            'implementation' => 'EasyShop\Promo\ScratchAndWinPromo',
            'calculation_id' => '5',
            'purchase_limit' => 1,
            'payment_method' => array(),
            'banner' => 'generic',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => array(
                0 => array(
                    'start' => '00:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => PHP_INT_MAX
                ),
            ),
            'viewable_button_product_page' =>false,
        ),
        /* Buy at zero promo */
        '6' =>array(
            'implementation' => 'EasyShop\Promo\BuyAtZeroPromo',
            'calculation_id' => '6',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => array(),
            'banner' => 'genericWithCountdown',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => false,
            'option' => array(
                0 => array(
                    'start' => '00:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => 1
                ),
             ),
            'viewable_button_product_page' =>true,
        ),
    )
);

/* End of file old_promo.php */
/* Location: ./application/config/old_promo.php */

