
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href='/assets/css/base.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/new-cart.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.payment.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<div class="transaction-wrapper">
    <div class="container">
        <!--Start of transaction breadcrumb-->
        <div class="transaction-breadcrumb-container">
            <div class="row">
                <div class="col-xs-4 col-trans-breadcrumb active">
                    <div class="breadcrumb-left-wing active-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa fa-check fa-lg"></i>
                        </div>
                        <div class="breadcrumb-title"> Shopping Cart</div>
                    </center>
                    <div class="breadcrumb-right-wing active-wing"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb active">
                    <div class="breadcrumb-left-wing active-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa icon-payment fa-lg done-icon"></i>
                            <i class="fa fa-check fa-lg new-icon"></i>
                        </div>
                        <div class="breadcrumb-title">Checkout Details</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                    <div class="active-right-wing-cart-1"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb">
                    <div class="breadcrumb-left-wing"></div>
                    <div class=" active-left-wing-cart-2"></div>
                    <center>
                        <div class="circle-breadcrumb active-breadcrumb-icon">
                            <i class="fa fa-cube fa-lg"></i>
                        </div>
                         <div class="breadcrumb-title active-breadcrumb-title">Order Complete</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                </div>
            </div>
        </div>
        <!--End of transaction breadcrumb-->

        
        <div class="row">
            <!--Start of shipping details-->
            <div class="col-md-7">
                <div class="transaction-container bg-white">
                    <?php if($isPaymentSuccess): ?>
                        <p class="lead"><i><?=html_escape($responseMessage); ?></i></p>
                    <?php else: ?>
                        <div class="alert alert-es-danger alert-dismissible" role="alert">
                            <?=html_escape($responseMessage); ?>
                        </div>
                    <?php endif; ?>
                    <p class="transaction-container-title title-border-bottom-2">Transaction Details</p>
                    <table class="transaction-bill-table" width="100%">
                        <tbody>
                            <tr>
                                <td>Reference Number : </td>
                                <td><?=$order->getTransactionId(); ?></td>
                            </tr>
                            <tr>
                                <td>Invoice Number : </td>
                                <td><?=$isPaymentSuccess ? $order->getInvoiceNo() : "Not Available" ?></td>
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
                    <br/><br/>
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
            <div class="col-md-5">
                <div class="transaction-container bg-gray">
                    <p class="transaction-container-title">Your Order Details</p>
                    <table class="transaction-summary-table transaction-checkout-order" width="100%">
                        <thead>
                            <tr>
                                <th width="30%">Product</th>
                                <th width="20%">Quantity</th>
                                <th width="20%">Shipping Fee</th>
                                <th width="30%">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderProducts as $product): ?>
                                <tr class="checkout-item">
                                    <td>
                                        <?=html_escape($product->getProduct()->getName());?>
                                        <?php if(empty($product->productOptions) === false): ?>
                                            <div class="checkout-item-attribute-container">
                                                <div class="checkout-item-attribute-container-header">
                                                    <i class="fa fa-caret-down"></i> <span class="checkout-item-attribute-container-action">show</span> product attributes
                                                </div>
                                                <div class="checkout-item-attribute-container-body">
                                                    <?php foreach ($product->productOptions as $option): ?>
                                                        <div class="checkout-item-attribute-name">
                                                            <b><?=html_escape(strtoupper($option['name']));?> : </b> <?=html_escape(strtoupper($option['value']));?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?=$product->getOrderQuantity();?></td>
                                    <td>&#8369; <?=number_format(bcdiv($product->getHandlingFee(), $product->getOrderQuantity(), 4), 2, '.', ',')?></td>
                                    <td>&#8369; <?=number_format($product->getPrice(), 2, '.', ',')?></td>
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
                    <?php if($isPaymentSuccess): ?>
                        <p class="transaction-container-text-small">
                            You have made a successful purchase on Easyshop.ph. An e-mail has been sent to you and the people from whom you purchased regarding the status of your transaction.You may view your pending transactions by clicking <a href="/me?tab=ongoing">here</a>
                        </p>
                        <p class="transaction-container-text-small">
                            If you want to print this page as an additional reference, click <a href="/payment/generateReceipt?txnId=<?=$order->getTransactionId(); ?>" target="_blank">here</a>.
                        </p>
                    <?php endif; ?>
                    <br/>
                    <a href="/" class="btn btn-es-green btn-lg btn-block">
                        Continue Shopping
                    </a>
                </div>
            </div>
            <!--End of order summary-->
        </div>
    </div>
</div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/payment-response.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.payment-response.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>




