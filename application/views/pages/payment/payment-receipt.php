<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->

<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Payment Receipt</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>

    <link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='all'/>
    <link rel="stylesheet" href="/assets/css/new-cart.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="all">
    <link rel="stylesheet" href="/assets/css/payment-receipt.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="print">
</head>

<body onload="window.print();window.location.replace('/me')">
<section class="wrapper">
    <div class="container text-center">
        <div class="row">
            <!--Start of shipping details-->
            <div class="col-xs-6">
                <div class="transaction-container bg-white">
                    <p class="lead"><i>Your order has been received. Thank you for using EasyShop.ph</i></p>

                    <p class="transaction-container-title title-border-bottom-2">Transaction Details</p>
                    <table class="transaction-bill-table" width="100%">
                        <tbody>
                            <tr>
                                <td>Reference Number : </td>
                                <td>PPY-1412170857-2</td>
                            </tr>
                            <tr>
                                <td>Invoice Number : </td>
                                <td>2-2-1412170857</td>
                            </tr>
                            <tr>
                                <td>Payment Method : </td>
                                <td>PayPal</td>
                            </tr>
                            <tr>
                                <td>Total Amount : </td>
                                <td>&#8369; 50,760.00</td>
                            </tr>
                            <tr>
                                <td>Transaction Date : </td>
                                <td>2014-12-17 15:16:58</td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <p class="transaction-container-title title-border-bottom-2">Shipping Details</p>
                    <table class="transaction-bill-table" width="100%">
                        <tbody>
                            <tr>
                                <td>Consignee Name : </td>
                                <td>Juan Dela Cruz</td>
                            </tr>
                            <tr>
                                <td>Contact Number : </td>
                                <td>PayPal</td>
                            </tr>
                            <tr>
                                <td>Full Address : </td>
                                <td>123 Taft Ave., Ermita, Manila</td>
                            </tr>
                            <tr>
                                <td>City and State : </td>
                                <td>Manila, NCR</td>
                            </tr>
                            <tr>
                                <td>Near Landmark : </td>
                                <td>Near LRT 1 Quirino Station Southbound</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--End of shipping details-->

            <!--Start of order summary-->
            <div class="col-xs-6">
                <div class="transaction-container bg-gray">
                    <p class="transaction-container-title">Your Order Details</p>
                    <table class="transaction-summary-table transaction-checkout-order" width="100%">
                        <thead>
                            <tr>
                                <th width="40%">Product</th>
                                <th width="20%">Quantity</th>
                                <th width="20%">Shipping Fee</th>
                                <th width="20%">Price</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr class="checkout-item">
                                <td>
                                    IPHONE 6 BLACK 64GB WITH 2 YEARS WARRANTY FROM MAC CENTER
                                </td>
                                <td>1</td>
                                <td>&#8369; 42,000.00</td>
                                <td>&#8369; 42,000.00</td>
                            </tr>
                             <tr class="checkout-item">
                                <td>
                                    Tailored Short
                                </td>
                                <td>1</td>
                                <td>&#8369; 200.00</td>
                                <td>&#8369; 200.00</td>
                            </tr>
                            <tr class="checkout-item">
                                <td>
                                    Long Sleeves Shirt
                                </td>
                                <td>1</td>
                                <td>&#8369; 400.00</td>
                                <td>&#8369; 400.00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    Subtotal
                                </td>
                                <td colspan="3">&#8369; 45,000.00</td>
                            </tr>
                            <tr>
                                <td>
                                    Total Shipping Fee
                                </td>
                                <td colspan="3">&#8369; 6,000.00</td>
                            </tr>
                            <tr class="border-bottom-1">
                                <td>
                                    Points Deduction
                                </td>
                                <td colspan="3">&mdash; &#8369; 240.00</td>
                            </tr>
                            <tr class="border-bottom-0">
                                <td>
                                    Order Total
                                </td>
                                <td  colspan="3" class="checkout-order-total">&#8369; 50,760.00</td>
                            </tr>
                        </foot>
                    </table>
                    <p class="transaction-container-text-small">
                        You have made a successful purchase on Easyshop.ph. An e-mail has been sent to you and the people from whom you purchased regarding the status of your transaction.
                    </p>
                </div>
            </div>
            <!--End of order summary-->
        </div>
        


        <div class="row">
            <div class="col-xs-12 mrgn-tb-100 qr-es-logo">
                <img src="/assets/images/qrcode-images/easyshop-logo.jpg" alt="Easyshop.ph">
            </div>
        </div>
    </div>
    <div class="hide-border-bottom">
        <div class="border-1"></div>
        <div class="border-2"></div>
    </div>
</section>
</body>
</html>
