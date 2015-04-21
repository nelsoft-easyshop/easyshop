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

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <link rel="stylesheet" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>'type="text/css" media='all'/>
        <link rel="stylesheet" href="/assets/css/new-cart.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="all"/>
        <link rel="stylesheet" href="/assets/css/payment-receipt.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="all"/>
    <?php else: ?>
        <link type="text/css" href='/assets/css/min-easyshop.payment-receipt.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='all'/>
    <?php endif; ?>

</head>

<body>
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
                                <td><?=$order->getTransactionId(); ?></td>
                            </tr>
                            <tr>
                                <td>Invoice Number : </td>
                                <td><?=$order->getInvoiceNo(); ?></td>
                            </tr>
                            <tr>
                                <td>Payment Method : </td>
                                <td><?=$order->getPaymentMethod()->getName(); ?></td>
                            </tr>
                            <tr>
                                <td>Total Amount : </td>
                                <td>&#8369; <?=number_format(bcsub($order->getTotal(), $transactionPoints, 4), 2, '.', ',')?></td>
                            </tr>
                            <tr>
                                <td>Transaction Date : </td>
                                <td><?=$order->getDateadded()->format('Y-m-d h:i a'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <p class="transaction-container-title title-border-bottom-2">Shipping Details</p>
                    <table class="transaction-bill-table" width="100%">
                        <tbody>
                            <tr>
                                <td>Consignee Name : </td>
                                <td><?=html_escape($shippingAddress->getConsignee());?></td>
                            </tr>
                            <tr>
                                <td>Mobile Number : </td>
                                <td>0<?=html_escape($shippingAddress->getMobile());?></td>
                            </tr>
                            <tr>
                                <td>Telephone Number : </td>
                                <td><?=html_escape($shippingAddress->getTelephone());?></td>
                            </tr>
                            <tr>
                                <td>Full Address : </td>
                                <td><?=html_escape($shippingAddress->getAddress());?></td>
                            </tr>
                            <tr>
                                <td>City and State : </td>
                                <td><?=html_escape($shippingAddress->getStateregion()->getLocation());?>, <?=html_escape($shippingAddress->getCity()->getLocation());?></td>
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
                            <?php foreach ($orderProducts as $product): ?>
                                <tr class="checkout-item">
                                    <td>
                                        <?=html_escape($product->getProduct()->getName());?>
                                    </td>
                                    <td><?=$product->getOrderQuantity();?></td>
                                    <td>&#8369; <?=number_format($product->getHandlingFee(), 2, '.', ',')?></td>
                                    <td>&#8369; <?=number_format($product->getTotal(), 2, '.', ',')?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    Subtotal
                                </td>
                                <td colspan="3">&#8369; <?=number_format(bcsub($order->getTotal(), $transactionShippingFee, 4), 2, '.', ',')?></td>
                            </tr>
                            <tr>
                                <td>
                                    Total Shipping Fee
                                </td>
                                <td colspan="3">&#8369; <?=number_format($transactionShippingFee, 2, '.', ',')?></td>
                            </tr>
                            <?php if(EasyShop\PaymentGateways\PointGateway::POINT_ENABLED): ?>
                            <tr class="border-bottom-1">
                                <td>
                                    Easy Points
                                </td>
                                <td colspan="3">&mdash; &#8369; <?=number_format($transactionPoints, 2, '.', ',')?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>
                                    Order Total
                                </td>
                                <td  colspan="3" class="checkout-order-total">&#8369; <?=number_format(bcsub($order->getTotal(), $transactionPoints, 4), 2, '.', ',')?></td>
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
                <img src="<?=getAssetsDomain(); ?>assets/images/qrcode-images/easyshop-logo.jpg" alt="Easyshop.ph" width="200">
            </div>
        </div>
    </div>
    <div class="hide-border-bottom">
        <div class="border-1"></div>
        <div class="border-2"></div>
    </div>
</section>
<script type="text/javascript">
    window.onload = function () {
        window.print();
        setTimeout(function(){window.close();}, 1);
    }
</script>
</body>
</html>
