<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = [
        'testing' => [
            'payment_type' => [
                'dragonpay' => [
                    'Easyshop' => [
                        'return_url' => 'http://staging.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'http://staging.easyshop.ph/payment/dragonPayPostBack',
                    ],
                    'Easydeal' => [
                        'return_url' => 'http://staging.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'http://staging.easyshop.ph/payment/dragonPayPostBack',                    ]
                ]
            ]
        ],
        'production' => [
            'payment_type' => [
                'dragonpay' => [
                    'Easyshop' => [
                        'return_url' => 'https://www.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'https://www.easyshop.ph/payment/dragonPayPostBack',
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://www.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'https://www.easyshop.ph/payment/dragonPayPostBack',
                    ]
                ]
            ]
        ]
    ];


