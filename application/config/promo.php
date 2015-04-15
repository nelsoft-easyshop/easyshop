<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = [
    'Promo' => [
        /* Default, non-promo */
        '0' => [
            'implementation' => '',
            'calculation_id' => '0',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [
                #'cdb'=>'Credit or Debit Card',
                #'paypal'=>'Paypal',
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery',
                'pesopaycdb' => 'Peso Pay'
            ],
            'banner' => '',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => [],
            'viewable_button_product_page' =>true,
        ],
        /* Countdown Sale */
        '1' => [
            'implementation' => 'EasyShop\Promo\CountDownSalePromo',
            'calculation_id' => '1',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery',
                'pesopaycdb' => 'Peso Pay'
            ],
            'banner' => 'countdown',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => false,
            'option' => [
                0 => [
                    'start' => '00:00:00',
                    'end' => '00:59:59',
                    'discountPerHour' => 1,
                    'purchase_limit' => PHP_INT_MAX
                ],
                1 => [
                    'start' => '01:00:00',
                    'end' => '04:59:59',
                    'discountPerHour' => 2,
                    'purchase_limit' => PHP_INT_MAX
                ],
                2 => [
                    'start' => '05:00:00',
                    'end' => '09:59:59',
                    'discountPerHour' => 3,
                    'purchase_limit' => PHP_INT_MAX
                ],
                3 => [
                    'start' => '10:00:00',
                    'end' => '13:59:59',
                    'discountPerHour' => 4,
                    'purchase_limit' => PHP_INT_MAX
                ],
                4 => [
                    'start' => '14:00:00',
                    'end' => '17:59:59',
                    'discountPerHour' => 5,
                    'purchase_limit' => PHP_INT_MAX
                ],
                5 => [
                    'start' => '18:00:00',
                    'end' => '20:59:59',
                    'discountPerHour' => 6,
                    'purchase_limit' => PHP_INT_MAX
                ],
                6 => [
                    'start' => '21:00:00',
                    'end' => '23:59:59',
                    'discountPerHour' => 7,
                    'purchase_limit' => PHP_INT_MAX
                ]
            ],
            'viewable_button_product_page' =>true,
        ],
        /* Fix discount sale */
        '2' => [
            'implementation' => 'EasyShop\Promo\FixedDiscountPromo',
            'calculation_id' => '2',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery',
                'pesopaycdb' => 'Peso Pay'
            ],
            'banner' => 'fixeddiscount',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => [],
            'viewable_button_product_page' =>true,
        ],
        /* Peak time sale */
        '3' => [
            'implementation' => 'EasyShop\Promo\PeakHourSalePromo',
            'calculation_id' => '3',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery',
                'pesopaycdb' => 'Peso Pay'
            ],
            'banner' => '',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => false,
            'option' => [
                0 => [
                    'start' => '00:00:00',
                    'end' => '06:59:59',
                    'purchase_limit' => 2
                ],
                1 => [
                    'start' => '12:00:00',
                    'end' => '16:59:59',
                    'purchase_limit' => 15
                ]
            ],
            'viewable_button_product_page' =>true,
        ],
        /* ListingPromo */
        '4' => [
            'implementation' => 'EasyShop\Promo\ListingPromo',
            'calculation_id' => '4',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [
                'dragonpay'=>'Dragon Pay',
                'cod'=>'Cash on Delivery',
                'pesopaycdb' => 'Peso Pay'
            ],
            'banner' => 'generic',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => [
                0 => [
                    'start' => '00:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => 0
                ],
            ],
            'viewable_button_product_page' =>false,
        ],
        /* Scratch and win promo */
        '5' => [
            'implementation' => 'EasyShop\Promo\ScratchAndWinPromo',
            'calculation_id' => '5',
            'purchase_limit' => 1,
            'payment_method' => [],
            'banner' => 'generic',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => [
                0 => [
                    'start' => '00:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => PHP_INT_MAX
                ],
            ],
            'viewable_button_product_page' =>false,
        ],
        /* Buy at zero promo */
        '6' => [
            'implementation' => 'EasyShop\Promo\BuyAtZeroPromo',
            'calculation_id' => '6',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [],
            'banner' => 'genericWithCountdown',
            'is_buyable_outside_promo' => false,
            'cart_solo_restriction' => false,
            'option' => [
                0 => [
                    'start' => '00:00:00',
                    'end' => '23:59:59',
                    'purchase_limit' => 1
                ],
            ],
            'viewable_button_product_page' =>true,
        ],
        /* Estudyantrepreneur promo */
        '7' => [
            'implementation' => 'EasyShop\Promo\Estudyantrepreneur',
            'calculation_id' => '7',
            'purchase_limit' => PHP_INT_MAX,
            'payment_method' => [],
            'banner' => 'generic',
            'is_buyable_outside_promo' => true,
            'cart_solo_restriction' => false,
            'option' => [
                'first_round' => [
                        'start' => '2015-04-15 17:00:00',
                        'end' => '2015-06-15 23:59:59',
                        'limit' => PHP_INT_MAX
                    ],
                'second_round' => [
                        'start' => '2020-12-31 00:00:00',
                        'end' => '2020-12-31 23:59:59',
                        'limit' => PHP_INT_MAX
                    ],
                'inter_school_round' => [
                        'start' => '2021-12-31 00:00:00',
                        'end' => '2022-12-31 23:59:59',
                        'limit' => 1
                    ],
            ],
            'viewable_button_product_page' =>false,
        ],
    ]
];
