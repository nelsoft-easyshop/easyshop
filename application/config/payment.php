<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = [
        'testing' => [
            'payment_type' => [
                'dragonpay' => [
                    'Easyshop' => [
                        'points' => true,
                        'return_url' => 'http://staging.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'http://staging.easyshop.ph/payment/dragonPayPostBack',
                        'mode' => '7',
                        'webservice_url' => 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl',
                        'redirect_url' => 'http://test.dragonpay.ph/Pay.aspx',
                        'merchant_id' => 'EASYSHOP',
                        'merchant_password' => 'UT78W5VQ', 
                        'ip_address' => [
                            '166.78.8.218',
                            '180.232.69.52',
                        ],
                        'lowest_amount' => 50.00
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://staging.easydeal.ph/payment/return/dragonpay',
                        'postback_url' => 'http://staging.easydeal.ph/payment/postback/dragonpay'
                    ]
                ],
                'paypal' => [
                    'Easyshop' => [
                        'points' => true,
                        'api_mode' => 'sandbox',
                        'api_username' => 'easyseller_api1.yahoo.com',
                        'api_password' => '1396000698',
                        'api_signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31Au1bGvwwVcv0garAliLq12YWfivG',
                        'lowest_amount' => 50.00
                    ]
                ],
                'pesopay' => [
                    'Easyshop' => [
                        'points' => true,
                        'api_url' => 'https://test.pesopay.com/b2cDemo/eng/merchant/api/orderApi.jsp',
                        'currency_code' => '608',
                        'payment_type' => 'N',
                        'secure_hash' => '1JQ6vd9qatkqoA9gRJpyKeMpl2Nu6cmm',
                        'redirect_url' => 'https://test.pesopay.com/b2cDemo/eng/payment/payForm.jsp',
                        'merchant_id' => '18061489',
                        'merchant_login_api' => 'apiuser',
                        'merchant_password_api' => 'apipassword',
                        'ip_address' => [ 
                            '180.232.69.52',
                        ],
                        'range_ip' => [
                            [
                                '58.64.198.68',
                                '58.64.198.94'
                            ],
                        ],
                        'lowest_amount' => 50.00
                    ],
                    'Easydeal' => [
                        'postback_url' => 'http://staging.easydeal.ph/payment/postback/pesopay'
                    ]
                ]
            ]
        ],
        'production' => [
            'payment_type' => [
                'dragonpay' => [
                    'Easyshop' => [
                        'points' => true,
                        'return_url' => 'https://www.easyshop.ph/payment/dragonPayReturn',
                        'postback_url' => 'https://www.easyshop.ph/payment/dragonPayPostBack',
                        'mode' => '7',
                        'webservice_url' => 'https://secure.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl',
                        'redirect_url' => 'https://gw.dragonpay.ph/Pay.aspx',
                        'merchant_id' => 'EASYSHOP',
                        'merchant_password' => 'UT78W5VQ',
                        'ip_address' => [
                            '119.81.11.114',
                            '119.81.11.115',
                            '180.232.69.52',
                        ],
                        'lowest_amount' => 50.00
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://www.easydeal.ph/payment/return/dragonpay',
                        'postback_url' => 'https://www.easydeal.ph/payment/postback/dragonpay'
                    ]
                ],
                'paypal' => [
                    'Easyshop' => [
                        'points' => true,
                        'api_mode' => '',
                        'api_username' => 'admin_api1.easyshop.ph',
                        'api_password' => 'GDWFS6D9ACFG45E7',
                        'api_signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31Adro7yAfl2NInYAAVfFFipJ-QQhT',
                        'lowest_amount' => 50.00
                    ]
                ],
                'pesopay' => [
                    'Easyshop' => [
                        'points' => true,
                        'api_url' => 'https://www.pesopay.com/b2c2/eng/merchant/api/orderApi.jsp',
                        'currency_code' => '608',
                        'payment_type' => 'N',
                        'secure_hash' => 'TvNiOW6xWOghmbqMeWQ3PAyoHY3lhIbb',
                        'redirect_url' => 'https://www.pesopay.com/b2c2/eng/payment/payForm.jsp',
                        'merchant_id' => '18139485',
                        'merchant_login_api' => 'EasyshopPH1',
                        'merchant_password_api' => 'pheasyshop4321',
                        'ip_address' => [
                            '180.232.69.52',
                        ],
                        'range_ip' => [
                            [
                                '203.105.16.160',
                                '203.105.16.191'
                            ],
                        ],
                        'lowest_amount' => 50.00
                    ],
                    'Easydeal' => [
                        'postback_url' => 'https://www.easydeal.ph/payment/postback/pesopay'
                    ]
                ]
            ]
        ]
    ];


