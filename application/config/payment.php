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
                        'merchant_password' => 'UT78W5VQ',
                        'ip_address' => [
                            '166.78.8.218',
                            '180.232.69.52',
                        ]
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://staging.easydeal.ph/payment/dragonPayReturn',
                        'postback_url' => 'http://staging.easydeal.ph/payment/dragonPayPostBack'
                    ]
                ],
                'paypal' => [
                    'Easyshop' => [
                        'api_mode' => 'sandbox',
                        'api_username' => 'easyseller_api1.yahoo.com',
                        'api_password' => '1396000698',
                        'api_signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31Au1bGvwwVcv0garAliLq12YWfivG',
                    ]
                ],
                'pesopay' => [
                    'Easyshop' => [
                        'redirect_url' => 'https://test.pesopay.com/b2cDemo/eng/payment/payForm.jsp',
                        'merchant_id' => '18061489',
                        'ip_address' => [
                            '58.64.198.68',
                            '58.64.198.94',
                            '180.232.69.52',
                        ]
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
                        'merchant_password' => 'UT78W5VQ',
                        'ip_address' => [
                            '119.81.11.114',
                            '119.81.11.115',
                            '180.232.69.52',
                        ]
                    ],
                    'Easydeal' => [
                        'return_url' => 'https://www.easydeal.ph/payment/dragonPayReturn',
                        'postback_url' => 'https://www.easydeal.ph/payment/dragonPayPostBack'
                    ]
                ],
                'paypal' => [
                    'Easyshop' => [
                        'api_mode' => '',
                        'api_username' => 'admin_api1.easyshop.ph',
                        'api_password' => 'GDWFS6D9ACFG45E7',
                        'api_signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31Adro7yAfl2NInYAAVfFFipJ-QQhT',
                    ]
                ],
                'pesopay' => [
                    'Easyshop' => [
                        'redirect_url' => 'https://www.pesopay.com/b2c2/eng/payment/payForm.jsp',
                        'merchant_id' => '18139485',
                        'ip_address' => [
                            '203.105.16.160',
                            '203.105.16.191',
                            '180.232.69.52',
                        ]

                    ]
                ]
            ]
        ]
    ];


