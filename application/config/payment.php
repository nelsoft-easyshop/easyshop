<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = [
        'testing' => [
            'payment_type' => [
                'dragonpay' => [
                    'Easyshop' => [
                        'return_url' => 'http://staging.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'http://staging.easyshop.ph/payment/dragonPayPostBack',
                        'mode' => '7',
                        'webservice_url' => 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl',
                        'redirect_url' => 'http://test.dragonpay.ph/Pay.aspx',
                        'merchant_id' => 'EASYSHOP',
                        'merchant_password' => 'UT78W5VQ'
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://staging.easydeal.ph/payment/dragonPayReturn',
                        'postback_url' => 'http://staging.easydeal.ph/payment/dragonPayPostBack'
                    ]
                ]
            ]
        ],
        'production' => [
            'payment_type' => [
                'dragonpay' => [
                    'Easyshop' => [
                        'return_url' => 'https://www.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'https://www.easyshop.ph/payment/dragonPayPostBack',
                        'mode' => '7',
                        'webservice_url' => 'https://secure.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl',
                        'redirect_url' => 'https://gw.dragonpay.ph/Pay.aspx',
                        'merchant_id' => 'EASYSHOP',
                        'merchant_password' => 'UT78W5VQ'
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://www.easydeal.ph/payment/dragonPayReturn',
                        'postback_url' => 'https://www.easydeal.ph/payment/dragonPayPostBack'
                    ]
                ]
            ]
        ]
    ];


