<!DOCTYPE html>
<html>
    <head>
        <title>Easyshop.ph Email</title>
        <style type="text/css">
            body {
                background: #F1F1F1;
            }
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,600,700);
            h1,h2,h3,h4,h5 {
                margin: 0;
                padding: 0;
                border: 0;
                font: inherit;
                vertical-align: baseline;
                font-family: 'Open Sans', sans-serif;
                font-weight: bold;
            }
            span, a, p {
                font-family: Helvetica, sans-serif;
                font-size: 16px;
            }
            a { 
                color: #F18200;
                text-decoration: none;
                font-weight: bold; 
            }
        </style>
    </head>
    <body>
        <div class="contentWrap" style="background: #F1F1F1; width: 100%; padding-top: 30px;">
        <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="600px">
            <tr>
                <td style="text-align: center;">
                    <a href="<?php echo base_url(); ?>" style="outline: none; border: 0;">
                        <img src="header-img.png" style="vertical-align:middle;" alt="Easyshop.ph">
                    </a>
                </td>
            </tr>
            <tr>
                <td style="background-color: #ffffff; padding: 30px 20px;">
                    <h2 class="intro" style="margin-bottom: 30px; font-size: 20px~; color: #003E91;">Dear {user},</h2>
                    <p style="font-family: Helvetica, sans-serif; font-size: 16px;">Welcome to your business’ new home!</p>
                    <p style="font-family: Helvetica, sans-serif; font-size: 16px;">EasyShop.ph targets to cater a platform, where you can facilitate easy and safe online business transactions. We are offering you a free stage for your business, and ensuring the buyers scam-free and easy-shopping experience. EasyShop.ph is where shopping made easy!</p>
                </td>
            </tr>
            <?php if(!isset($emailVerified)): ?>
            <tr>
                <td style="background-color: #ffffff; padding: 20px 20px;">
                    Please verify your email address:<br />
                    <a href="{site_url}?h={hash}" style="background-color:#0191C8; color:#ffffff; display:inline-block; font-size:14px; padding:10px 20px; margin-top:20px;">Verify email</a> <br />
                    Once verified, you may start selling products in Easyshop.ph!
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td style="background: #3C475C; padding: 20px; border-bottom: 5px solid #F18200;">
                    <div class="socialMedia" style="width: 160px; margin: 0 auto;">
                        <a href="https://www.facebook.com/pages/EasyShopph/211771799032417?ref=hl"><img src="facebook.png" style="width: 50px;" alt="Share us on Facebook"></a>
                        <!--<a href="#"><img src="googleplus.png" style="width: 50px;" alt="Connect with Google+"></a>-->
                        <a href="https://twitter.com/EasyShopPH"><img src="twitter.png" style="width: 50px;" alt="Tweet us on Twitter"></a>
                    </div>
                    <p style="color: #ffffff; text-align: center;">Copyright &copy; 2014 Easyshop.ph</p>
                </td>
            </tr>
        </table>
    </body>
</html>

