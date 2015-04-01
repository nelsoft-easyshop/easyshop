<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/my_cart_css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/payment_review.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/bootstrap-mods.css" type="text/css" media="screen"/>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.payment.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<div class="container font-roboto">  
    <h2 class="checkout_title">Payment <span class="pull-right"><a href="/cart">Go back to cart</a></span></h2>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default no-border" style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">
                <?php if($shippingDetails == true): ?>
                    <h2 class="bg-gray">
                        Ship To:
                    </h2>
                    <table width="100%"  class="tbl-ship-to">
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                Name: 
                            </td>
                            <td style="padding: 15px 0px 5px 10px" width="70%">
                                <?php echo ucwords(strtolower(html_escape($consignee)));?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                Full Address:
                            </td>
                            <td style="padding: 5px 0px 5px 10px">
                                <?php echo html_escape($c_address);?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                State/Region:
                            </td>
                            <td style="padding: 5px 0px 5px 10px">
                                <?php echo ucwords(strtolower($c_stateregion));?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                City:
                            </td>
                            <td style="padding: 5px 0px 5px 10px">
                                <?php echo ucwords(strtolower($c_city));?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                Country:
                            </td>
                            <td style="padding: 5px 0px 5px 10px">
                                <?php echo ucwords(strtolower($country_name));?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                Mobile:
                            </td>
                            <td style="padding: 5px 0px 5px 10px">
                                <?php echo (strlen(trim($c_mobile)) !== 0)?ucwords(strtolower($c_mobile)):'';?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px 5px 0px">
                                Telephone:
                            </td>
                            <td style="padding: 5px 0px 5px 10px">
                                <?php echo ucwords(strtolower($c_telephone));?>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>
                <a class="btn btn-default show-form-address btn-gray" style="text-decoration: none; margin-top:10px;" data-toggle="modal" data-target="#change_ship">Change Shipping Details</a> 
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default no-border" style="background:#f4f4f4; border:0px; padding:10px; ">
                <h2 class="h2-title">Order Summary</h2>
                <p>You have <?php echo count($cat_item);?> item/s to checkout.</p>
                <table width="100%" class="table font-12">
                    <tr class="tr-header-summary">
                        <th style="padding-top: 10px; padding-bottom: 10px;" width="40%">Product</th>
                        <th align="center">Quantity</th>
                        <th style="text-align: right !important;">Shipping Fee</th>
                        <th style="text-align: right !important;">Price</th>
                    </tr>
                    <?php 
                    $total = 0;
                    $shipping_fee = 0;
                    foreach ($cat_item as $key => $value):
                        $total += $value['subtotal'] ;
                        $shipping_fee = isset($value['shipping_fee'])
                                        ? $shipping_fee += $value['shipping_fee'] * $value['qty'] 
                                        : $shipping_fee; 
                    ?>
                        <tr>
                            <td WIDTH="40%">
                                <?php echo html_escape($value['name']); ?>
                            </td>
                            <td align="center">
                                <?php echo $value['qty'] ?>
                            </td>
                            <td  align="right">
                                <?php echo (isset($value['shipping_fee'])) ? number_format($value['shipping_fee'], 2, '.',',') : '<span style="color:red">Not available.</span>' ?>
                            </td>
                            <td  align="right">
                                <?php echo number_format($value['price'], 2, '.',',') ?>
                            </td>
                        </tr>
                        <?php if(!$value['availability']): ?>
                            <tr>
                                <td colspan="4" style="border-top: 0px;">
                                    <div class="error_shipping_address">
                                        <span>
                                            This item is not available in your location. 
                                            <a style="color:#0654BA" href="javascript:{}" data-slug="<?= $value['id'] ?>" data-name="<?= html_escape($value['name']); ?>" data-iid="<?= $value['product_itemID']; ?>" class="view_location_item" data-toggle="modal" data-target="#avail_loc" >See the item location availability here.</a>
                                                or <a href="javascript:void(0);" class="removeitem" data-cart-id="<?php echo $value["rowid"] ?>" data-slug="<?= $value['slug'] ?>" style="color:red">Remove</a> this item from your cart checkout to proceed.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2">
                            Subtotal:
                        </td>
                        <td colspan="2" align="right">
                            <?php echo number_format($total, 2, '.',','); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 0px;">
                            Shipping fee:
                        </td>
                        <td colspan="2" style="border: 0px;" align="right">
                            Php <?php echo number_format($shipping_fee, 2, '.',','); ?>
                        </td>
                    </tr>
                    <tr class="tr-header-summary">
                        <td colspan="2">
                            <b>Total:</b>
                        </td>
                        <td colspan="2" align="right">
                            <b>Php <?php echo number_format($total + $shipping_fee, 2, '.',','); ?></b>
                        </td>
                    </tr> 
                </table>
            </div>
        </div>
    </div>
    <div class="row mrgn-bttm-45">
        <div class="col-md-12">
            <div class="display-when-desktop-payment">
                <?php if($success 
                         && $qtysuccess 
                         && $promoteSuccess['purchase_limit'] 
                         && $promoteSuccess['solo_restriction']
                         && $paymentMethodSuccess): ?>
                    <div>
                        <p class="fl_pay"><strong>How would you like to pay?</strong></p> 
                        <ul class="idTabs payment_options_tabs">
                            <?php foreach($paymentType as $key => $value):?>
                                <li><a href="#<?=$key;?>"><?=$value;?></a></li> 
                            <?php endforeach; ?>
                        </ul>
                        <?php foreach($paymentType as $key => $value):?> 

                            <!-- CREDIT CARD / DEBIT CARD PAYPAL DESKTOP SECTION -->
                            <?php if($key == 'cdb'): ?>
                                <div id="cdb" class="payment_inner_content">
                                    <p class="cod_desc">
                                        <strong>
                                            Pay using  Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.
                                        </strong>
                                    </p><br />
                                    <a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=350'); return false;">
                                        <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" style="vertical-align:middle;text-decoration: underline;"> 
                                        What is PayPal?
                                    </a><br/>
                                    <?php if(count($cat_item) <= 0): ?>
                                        <br /> <br />
                                        No Items in Cart Can't Proceed.
                                    <?php else: ?> 
                                        <?php if($paypalsuccess): ?>
                                            <p class="chck_privacy">
                                                <input type="checkbox" checked class="chk_paypal">
                                                <label for='chk_paypal2'> 
                                                    I acknowledge I have read and understood Easyshop.ph's
                                                </label>
                                                <a href="/policy" target='_blank'>
                                                    <span style='border-bottom:1px dotted'> 
                                                        Privacy Policy 
                                                    </span>
                                                </a>.
                                            </p>
                                            <div class="paypal_button">
                                                <a style="cursor:pointer" data-type="2"  class="paypal">
                                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/paypal_checkout_button.png" alt="Paypal Credit/Debit Card Checkout" align="left" style="margin-right:7px;">
                                                    <span></span>
                                                </a>
                                            </div>
                                            <div class="paypal_loader">
                                                <img src="<?php echo getAssetsDomain(); ?>assets/images/paypal_load.gif">
                                            </div>
                                        <?php else: ?>
                                            <span>
                                                <strong>
                                                    NOTE: one or more of your chosen items are not available for paypal.
                                                </strong>
                                            </span>
                                            <table width="100%" class="table font-12">
                                                <tr class="tr-header-summary">
                                                    <th>Seller</th>
                                                    <th>Product</th>
                                                    <th style="text-align: center;">Quantity</th>
                                                    <th style="text-align: right;">Price</th>
                                                </tr>
                                                <?php foreach ($cat_item as $key => $value): ?>
                                                    <tr>
                                                        <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                            <?php echo html_escape($value['store_name']); ?>
                                                        </td>
                                                        <td width="40%">
                                                            <?php echo html_escape($value['name']); ?>
                                                        </td>
                                                        <td  width="15%" align="center">
                                                            <?php echo $value['qty'] ?>
                                                        </td>
                                                        <td align="right"  width="15%">
                                                            <?php echo number_format($value['price'], 2, '.',',') ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="border-top: 0px;">
                                                            <?=$value['paypal'] 
                                                               ? "<span style='color:green'>Available for Paypal</span>" 
                                                               : "<span style='color:red; font-weight:bold;'>Not available for Paypal</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)";?>
                                                        </td>
                                                    </tr> 
                                                <?php endforeach; ?>
                                            </table>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div style="clear:both"></div>
                                    <p class="notify">
                                        You will be notified regarding your order status via email or sms.
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- PAYPAL DESKTOP SECTION -->
                            <?php if($key == 'paypal'): ?>
                                <div id="paypal" class="payment_inner_content">
                                    <p class="cod_desc">
                                        <strong>
                                            Pay using your PayPal account. You will be redirected to the PayPal system to complete the payment.
                                        </strong>
                                    </p><br />
                                    <a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=350'); return false;">
                                        <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" style="vertical-align:middle;text-decoration: underline;"> 
                                        What is PayPal?
                                    </a><br />
                                    <?php if(count($cat_item) <= 0): ?>
                                        There are no items in the cart.
                                    <?php else: ?> 
                                        <?php if($paypalsuccess): ?>
                                            <p class="chck_privacy">
                                                <input type="checkbox" checked class="chk_paypal">
                                                <label for='chk_paypal1'> 
                                                    I acknowledge I have read and understood Easyshop.ph's 
                                                </label>
                                                <a href="/policy" target='_blank'>
                                                    <span style='border-bottom:1px dotted'> 
                                                        Privacy Policy 
                                                    </span>
                                                </a>.
                                            </p>
                                            <div class="paypal_button">
                                                <a style="cursor:pointer" data-type="1"  class="paypal">
                                                    <img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Paypal Checkout" align="left" style="margin-right:7px;">
                                                    <span></span>
                                                </a>
                                            </div>
                                            <div class="paypal_loader"><img src="<?php echo getAssetsDomain(); ?>assets/images/paypal_load.gif"></div> 
                                        <?php else: ?>
                                            <span><strong>NOTE: one or more of your chosen items are not available for paypal.</strong></span>
                                            <table width="100%" class="table font-12">
                                                <tr class="tr-header-summary">
                                                    <th>Seller</th>
                                                    <th>Product</th>
                                                    <th style="text-align: center;">Quantity</th>
                                                    <th style="text-align: right;">Price</th>
                                                </tr>
                                                <?php foreach ($cat_item as $key => $value): ?>
                                                    <tr>
                                                        <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                            <?php echo html_escape($value['store_name']); ?>
                                                        </td>
                                                        <td width="40%">
                                                            <?php echo html_escape($value['name']); ?>
                                                        </td>
                                                        <td  width="15%" align="center">
                                                            <?php echo $value['qty'] ?>
                                                        </td>
                                                        <td align="right"  width="15%">
                                                            <?php echo number_format($value['price'], 2, '.',',') ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="border-top: 0px;">
                                                            <?=$value['paypal'] 
                                                               ? "<span style='color:green'>Available for Paypal</span>" 
                                                               : "<span style='color:red; font-weight:bold;'>Not available for Paypal</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)";?>
                                                        </td>
                                                    </tr> 
                                                <?php endforeach; ?>
                                            </table>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div style="clear:both"></div>
                                    <p class="notify">
                                        You will be notified regarding your order status via email or sms.
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- DRAGON PAY DESKTOP SECTION -->
                            <?php if($key == 'dragonpay'): ?>
                                <div id="dragonpay" class="payment_inner_content">
                                    <?php if($dragonpaysuccess): ?>
                                           <img src="<?php echo getAssetsDomain(); ?>assets/images/dp-icons.png" alt="Dragon Pay Icons" align="left" style="float:none; width:100%;">
                                           
                                            <p class="chck_privacy">
                                                <input type="checkbox" checked class="chk_dp" name='chk_dp'>
                                                <label for='chk_dp'>I acknowledge I have read and understood Easyshop.ph's </label>
                                                <a href="/policy" target='_blank'>
                                                    <span style='border-bottom:1px dotted;'> Privacy Policy </span>
                                                </a>.
                                            </p>
                                            <input type="button" class="btnDp orange_btn3" value="Pay via DRAGON PAY">
                                            
                                            <p class="notify" style="font-style: italic;">
                                                <b>Note:</b>
                                                Dragonpay is a Philippines-based alternative payments solution company that allows buyers to pay for good or services through direct bank debit or over-the-counter (OTC). Note that BDO mall branches are open on weekends. You may also choose SM or LBC as most branches are open on weekends and holidays.
                                            </p>
                                    <?php else: ?>
                                        <span>
                                            <strong>
                                                NOTE: one or more of your chosen items are not available for dragonpay payment.
                                            </strong>
                                        </span>
                                        <table width="100%" class="table font-12">
                                            <tr class="tr-header-summary">
                                                <th>Seller</th>
                                                <th>Product</th>
                                                <th style="text-align: center;">Quantity</th>
                                                <th style="text-align: right;">Price</th>
                                            </tr>
                                            <?php foreach ($cat_item as $key => $value): ?>
                                                <tr>
                                                    <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                        <?php echo html_escape($value['store_name']) ?>
                                                    </td>
                                                    <td width="40%">
                                                        <?php echo html_escape($value['name']) ?>
                                                    </td>
                                                    <td  width="15%" align="center">
                                                        <?php echo $value['qty'] ?>
                                                    </td>
                                                    <td align="right"  width="15%">
                                                        <?php echo number_format($value['price'], 2, '.',',') ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="border-top: 0px;">
                                                        <?php echo ($value['dragonpay'] ? "<span style='color:green'>Available for Dragonpay</span>" : "<span style='color:red; font-weight:bold;'>Not available for Dragonpay</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)") ;?>
                                                    </td>
                                                </tr> 
                                            <?php endforeach; ?>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>  

                            <!-- CASH ON DELIVERY DESKTOP SECTION -->
                            <?php if($key == 'cod'): ?>
                                <div id="cod" class="payment_inner_content">
                                    <?php if($codsuccess): ?>
                                        <p class="cod_desc">
                                            <strong>
                                                You can pay in cash to our courier when you receive the goods at your doorstep.
                                            </strong>
                                        </p><br>
                                        <?=form_open('pay/cashondelivery/', ['class' => 'codFrm','id' => 'codFrm','name' => 'codFrm']); ?>
                                            <p class="chck_privacy">
                                                <input type="checkbox" checked class="chk_cod" name='chk_cod'>
                                                <label for='chk_cod'> I acknowledge I have read and understood Easyshop.ph's</label>
                                                <a href="/policy" target='_blank'>
                                                    <span style='border-bottom:1px dotted'> Privacy Policy </span>
                                                </a>.
                                            </p>
                                            <input type="button" class="payment_cod orange_btn3" value="Pay via Cash On Delivery">  
                                        <?=form_close();?>
                                        <p class="notify">You will be notified regarding your order status via email or sms.</p>
                                    <?php else: ?>
                                        <span>
                                            <strong>
                                                NOTE: one or more of your chosen items are not available for cash on delivery.
                                            </strong>
                                        </span>
                                        <table width="100%" class="table font-12">
                                            <tr class="tr-header-summary">
                                                <th>Seller</th>
                                                <th>Product</th>
                                                <th style="text-align: center;">Quantity</th>
                                                <th style="text-align: right;">Price</th>
                                            </tr>
                                            <?php foreach ($cat_item as $key => $value): ?>
                                                <tr>
                                                    <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                        <?php echo html_escape($value['store_name']); ?>
                                                    </td>
                                                    <td width="40%">
                                                        <?php echo html_escape($value['name']); ?>
                                                    </td>
                                                    <td  width="15%" align="center">
                                                        <?php echo $value['qty'] ?>
                                                    </td>
                                                    <td align="right"  width="15%">
                                                        <?php echo number_format($value['price'], 2, '.',',') ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="border-top: 0px;">
                                                        <?php echo ($value['cash_delivery'] ? "<span style='color:green'>Available for Cash on Delivery</span>" : "<span style='color:red; font-weight:bold;'>Not available for Cash on Delivery</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)") ;?>
                                                    </td>
                                                </tr>
                                                <?php if(!$value['availability']): ?>
                                                <tr>
                                                    <td style="color:red"> 
                                                          Please <a style="color:#0654BA" href="javascript:{}" class="link_address">change your shipping address</a> or remove this from your <a href="/cart" style="color:#0654BA">Cart</a>.
                                                    </td>
                                                </tr>
                                                <?php endif; ?> 
                                            <?php endforeach; ?>
                                         </table> 
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- PESOPAY DESKTOP SECTION -->
                            <?php if($key == 'pesopaycdb'): ?>
                                <div id="pesopaycdb" class="payment_inner_content">
                                    <?php if($pesopaysuccess): ?>
                                        <p class="chck_privacy">
                                            <input type="checkbox" checked id="chk_ppcdb" name='chk_ppcdb'> 
                                            <label for='chk_ppcdb'>
                                                I acknowledge I have read and understood Easyshop.ph's
                                            </label>
                                            <a href="/policy" target='_blank'>
                                                <span style='border-bottom:1px dotted'>
                                                    Privacy Policy
                                                </span>
                                            </a>.
                                        </p>
                                        <input type="button" class="pesopaycdb pesopaycdb_btn orange_btn3" value="Pay via Credit or Debit Card">
                                        <p class="notify">You will be notified regarding your order status via email or sms.</p>
                                    <?php else: ?>
                                        <span><strong>NOTE: one or more of your chosen items are not available for Credit Card.</strong></span>
                                        <table width="100%" class="table font-12">
                                            <tr class="tr-header-summary">
                                                <th>Seller</th>
                                                <th>Product</th>
                                                <th style="text-align: center;">Quantity</th>
                                                <th style="text-align: right;">Price</th>
                                            </tr>
                                            <?php foreach ($cat_item as $key => $value): ?>
                                            <tr>
                                                <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                    <?php echo html_escape($value['store_name']); ?>
                                                </td>
                                                <td width="40%">
                                                    <?php echo html_escape($value['name']); ?>
                                                </td>
                                                <td  width="15%" align="center">
                                                    <?php echo $value['qty'] ?>
                                                </td>
                                                <td align="right"  width="15%">
                                                    <?php echo number_format($value['price'], 2, '.',',') ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: 0px;">
                                                    <?php echo ($value['pesopaycdb']) 
                                                            ? "<span style='color:green'>Available for Credit Card</span>" 
                                                            : "<span style='color:red; font-weight:bold;'>Not available for Credit Card</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)" ;?>
                                                </td>
                                            </tr>
                                             
                                            <?php endforeach; ?>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div> 
                <?php else: ?>
                    <?php if(!$success):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red;'>NOTE: One or more of your item(s) is unavailable in your location. </span>
                    <?php elseif(!$qtysuccess):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red; '>NOTE: The availability of one of your items is less than your desired quantity. Someone may have purchased the item before you can complete your payment. Check the availability of your item and try again.</span>
                    <?php elseif(!$promoteSuccess['solo_restriction']):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: One of your items can only be purchased  individually.</span>
                    <?php elseif(!$promoteSuccess['purchase_limit']):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: You have exceeded your purchase limit for a promo of an item in your cart.</span>
                     <?php elseif (!$paymentMethodSuccess): ?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: Cannot proceed payment. One of your item is not available for checkout.</span>
                    <?php else:?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: One or more of your item(s) is unavailable in your location. </span>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>Also, the availability of one of your items is less than your desired quantity. Someone may have purchased the item before you can complete your payment. Check the availability of your item and try again. </span>
                    <?php endif;?>
                <?php endif; ?>
            </div>
            <div class="display-when-mobile-542">
                <?php if($success 
                         && $qtysuccess 
                         && $promoteSuccess['purchase_limit'] 
                         && $promoteSuccess['solo_restriction']
                         && $paymentMethodSuccess): ?>
                    <p class="fl_pay"><strong>How would you like to pay?</strong></p>
                    <div class="panel-group" id="accordion">
                        <?php foreach($paymentType as $key => $value):?>
                            <div class="panel panel-default no-border">
                                <div class="panel-heading no-border">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#id-<?=$key;?>">
                                            <?=$value;?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="id-<?=$key;?>" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <!-- PAYPAL MOBILE SECTION DIRECT TO CREDIT CARD / DEBIT CARD -->
                                        <?php if($key == 'cdb'): ?>
                                            <div id="cdb_mobile">
                                                <p class="cod_desc" style="font-size: 12px;">
                                                    Pay using  Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.
                                                </p>
                                                <a style="font-size: 13px;" href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=350'); return false;">
                                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" style="vertical-align:middle;text-decoration: underline; "> 
                                                    What is PayPal?
                                                </a><br/>
                                                <?php if(count($cat_item) <= 0): ?>
                                                    <br /> <br />
                                                    No Items in Cart Can't Proceed.
                                                <?php else: ?> 
                                                    <?php if($paypalsuccess): ?>
                                                        <br/> 
                                                        <p class="chck_privacy" style="font-size: 12px;">
                                                            <input type="checkbox" checked class="chk_paypal" > 
                                                            I acknowledge I have read and understood Easyshop.ph's
                                                            <a href="/policy" target='_blank'>
                                                                <span style='border-bottom:1px dotted'> 
                                                                    Privacy Policy 
                                                                </span>
                                                            </a>.
                                                        </p>
                                                        <div class="paypal_button">
                                                            <a style="cursor:pointer" data-type="2"  class="paypal">
                                                                <img class="img-responsive" src="<?php echo getAssetsDomain(); ?>assets/images/paypal_checkout_button.png" alt="Paypal Credit/Debit Card Checkout" align="left">
                                                                <span></span>
                                                            </a>
                                                        </div>
                                                        <div class="paypal_loader">
                                                            <img src="<?php echo getAssetsDomain(); ?>assets/images/paypal_load.gif">
                                                        </div> 
                                                    <?php else: ?>
                                                        <span style="font-size: 10px;">
                                                            <strong>
                                                                NOTE: one or more of your chosen items are not available for cash on delivery.
                                                            </strong>
                                                        </span>
                                                        <table width="100%" class="table font-12">
                                                            <tr class="tr-header-summary">
                                                                <th>Seller</th>
                                                                <th>Product</th>
                                                                <th style="text-align: center;">Quantity</th>
                                                                <th style="text-align: right;">Price</th>
                                                            </tr>
                                                            <?php foreach ($cat_item as $key => $value): ?>
                                                                <tr>
                                                                    <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                                        <?=html_escape($value['store_name']); ?>
                                                                    </td>
                                                                    <td width="40%">
                                                                        <?=html_escape($value['name']); ?>
                                                                    </td>
                                                                    <td  width="15%" align="center">
                                                                        <?=$value['qty'] ?>
                                                                    </td>
                                                                    <td align="right"  width="15%">
                                                                        <?=number_format($value['price'], 2, '.',',') ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" style="border-top: 0px;">
                                                                        <?=$value['paypal'] === true 
                                                                           ? "<span style='color:green'>Available for Cash on Delivery</span>" 
                                                                           : "<span style='color:red; font-weight:bold;'>Not available for Cash on Delivery</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)";?>
                                                                    </td>
                                                                </tr> 
                                                            <?php endforeach; ?>
                                                        </table>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <div style="clear:both"></div>
                                                <p class="notify"  style="font-size: 13px !important;">You will be notified regarding your order status via email or sms.</p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- PAYPAL MOBILE SECTION DIRECT TO LOGIN -->
                                        <?php if($key == 'paypal'): ?>
                                            <div id="paypal_mobile">
                                                <p class="cod_desc" style="font-size: 12px;">Pay using your PayPal account. You will be redirected to the PayPal system to complete the payment.</p> 
                                                <a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=350'); return false;">
                                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" style="vertical-align:middle;text-decoration: underline;"> 
                                                    What is PayPal?
                                                </a><br />
                                                <?php if(count($cat_item) <= 0): ?>
                                                    <br /> <br />
                                                    There are no items in the cart.
                                                <?php else: ?>
                                                    <?php if($paypalsuccess): ?>
                                                        <br />
                                                        <p class="chck_privacy" style="font-size:12px;">
                                                            <input type="checkbox" checked class="chk_paypal"> 
                                                            I acknowledge I have read and understood Easyshop.ph's
                                                            <a href="/policy" target='_blank'>
                                                                <span style='border-bottom:1px dotted'> 
                                                                    Privacy Policy 
                                                                </span>
                                                            </a>.
                                                        </p>
                                                        <div class="paypal_button">
                                                            <a style="cursor:pointer" data-type="1" class="paypal">
                                                            <img class="img-responsive" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Paypal Checkout" align="left" style="margin-right:7px;">
                                                            <span></span>
                                                            </a>
                                                        </div>
                                                        <div class="paypal_loader">
                                                            <img src="<?php echo getAssetsDomain(); ?>assets/images/paypal_load.gif">
                                                        </div> 
                                                    <?php else: ?>
                                                        <span style="font-size: 10px;">
                                                            <strong>
                                                                NOTE: one or more of your chosen items are not available for cash on delivery.
                                                            </strong>
                                                        </span>
                                                        <table width="100%" class="table font-12">
                                                            <tr class="tr-header-summary">
                                                                <th>Seller</th>
                                                                <th>Product</th>
                                                                <th style="text-align: center;">Quantity</th>
                                                                <th style="text-align: right;">Price</th>
                                                            </tr>
                                                            <?php foreach ($cat_item as $value): ?>
                                                                <tr>
                                                                    <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                                        <?=$value['store_name'] ?>
                                                                    </td>
                                                                    <td width="40%">
                                                                        <?=html_escape($value['name']); ?>
                                                                    </td>
                                                                    <td  width="15%" align="center">
                                                                        <?=html_escape($value['qty']); ?>
                                                                    </td>
                                                                    <td align="right"  width="15%">
                                                                        <?=number_format($value['price'], 2, '.',',') ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" style="border-top: 0px;">
                                                                        <?=$value['paypal'] === true
                                                                           ? "<span style='color:green'>Available for Cash on Delivery</span>" 
                                                                           : "<span style='color:red; font-weight:bold;'>Not available for Cash on Delivery</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)" ;?>
                                                                    </td>
                                                                </tr> 
                                                            <?php endforeach; ?>
                                                        </table>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <div style="clear:both"></div>
                                                <p class="notify" style="font-size: 13px !important;">You will be notified regarding your order status via email or sms.</p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- DRAGON PAY MOBILE SECTION -->
                                        <?php if($key == 'dragonpay'): ?>
                                            <div id="dragonpay_mobile">
                                                <?php if($dragonpaysuccess): ?>
                                                    <img class="img-responsive" src="<?php echo getAssetsDomain(); ?>assets/images/dp-icons.png" alt="Dragon Pay Icons" align="left" style="margin-right:7px;float:none;">
                                                    
                                                    <p class="chck_privacy" style="font-size: 12px;">
                                                        <input type="checkbox" checked class="chk_dp" name='chk_dp'>
                                                        I acknowledge I have read and understood Easyshop.ph's 
                                                        <a href="/policy" target='_blank'>
                                                            <span style='border-bottom:1px dotted'> 
                                                                Privacy Policy 
                                                            </span>
                                                        </a>.
                                                    </p>
                                                    <input type="button" style='width: 153px;' class="btnDp orange_btn3" value="Pay via DRAGON PAY">
                                                    
                                                    <p class="notify" style="font-style: italic;font-size:10px;"><b>Note:</b> Dragonpay is a Philippines-based alternative payments solution company that allows buyers to pay for good or services through direct bank debit or over-the-counter (OTC). Note that BDO mall branches are open on weekends. You may also choose SM or LBC as most branches are open on weekends and holidays.</p>
                                                <?php else: ?>
                                                    <span style="font-size: 10px;"><strong>NOTE: one or more of your chosen items are not available for Dragonpay payment.</strong></span>
                                                    <table width="100%" class="table font-12">
                                                        <tr class="tr-header-summary">
                                                            <th>Seller</th>
                                                            <th>Product</th>
                                                            <th style="text-align: center;">Quantity</th>
                                                            <th style="text-align: right;">Price</th>
                                                        </tr>
                                                        <?php foreach ($cat_item as $key => $value): ?>
                                                            <tr>
                                                                <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                                    <?=$value['store_name'] ?>
                                                                </td>
                                                                <td width="40%">
                                                                    <?=html_escape($value['name']); ?>
                                                                </td>
                                                                <td  width="15%" align="center">
                                                                    <?=html_escape($value['qty']); ?>
                                                                </td>
                                                                <td align="right"  width="15%">
                                                                    <?=number_format($value['price'], 2, '.',',') ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4" style="border-top: 0px;">
                                                                    <?=$value['dragonpay'] === true
                                                                       ? "<span style='color:green'>Available for Dragonpay payment.</span>" 
                                                                       : "<span style='color:red; font-weight:bold;'>Not available for Dragonpay payment.</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)" ;?>
                                                                </td>
                                                            </tr> 
                                                        <?php endforeach; ?>
                                                    </table>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- CASH ON DELIVERY MOBILE SECTION -->
                                        <?php if($key == 'cod'): ?>
                                            <div id="cod_mobile">
                                                <?php if($codsuccess): ?>
                                                    <p class="cod_desc" style="font-size: 10px;">
                                                        <strong>You can pay in cash to our courier when you receive the goods at your doorstep.</strong>
                                                    </p>
                                                    <br>
                                                    <?=form_open('pay/cashondelivery/', ['class' => 'codFrm','id' => 'codFrm_mobile','name' => 'codFrm']); ?>
                                                        <p class="chck_privacy" style="font-size: 12px;">
                                                            <input type="checkbox" checked class="chk_cod" name='chk_cod'> 
                                                            I acknowledge I have read and understood Easyshop.ph's 
                                                            <a href="/policy" target='_blank'> 
                                                                <span style='border-bottom:1px dotted'> 
                                                                    Privacy Policy 
                                                                </span>
                                                            </a>.
                                                        </p>
                                                        <input type="button" class="payment_cod orange_btn3" value="Pay via Cash On Delivery">     
                                                    <?=form_close();?>
                                                    <p class="notify" style="font-size: 10px;">You will be notified regarding your order status via email or sms.</p>
                                                <?php else: ?>
                                                     <span style="font-size: 10px;"><strong>NOTE: one or more of your chosen items are not available for cash on delivery.</strong></span>
                                                     <table width="100%" class="table font-12">
                                                        <tr class="tr-header-summary">
                                                            <th>Seller</th>
                                                            <th>Product</th>
                                                            <th style="text-align: center;">Quantity</th>
                                                            <th style="text-align: right;">Price</th>
                                                        </tr>
                                                        <?php foreach ($cat_item as $key => $value): ?>
                                                            <tr>
                                                                <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                                    <?=html_escape($value['store_name']); ?>
                                                                </td>
                                                                <td width="40%">
                                                                    <?=html_escape($value['name']); ?>
                                                                </td>
                                                                <td  width="15%" align="center">
                                                                    <?=$value['qty'] ?>
                                                                </td>
                                                                <td align="right"  width="15%">
                                                                    <?=number_format($value['price'], 2, '.',',') ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4" style="border-top: 0px;">
                                                                    <?=$value['cash_delivery'] === true 
                                                                       ? "<span style='color:green'>Available for Cash on Delivery</span>" 
                                                                       : "<span style='color:red; font-weight:bold;'>Not available for Cash on Delivery</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)";?>
                                                                </td>
                                                            </tr>
                                                            <?php if(!$value['availability']): ?>
                                                                <tr>
                                                                    <td style="color:red">
                                                                          Please <a style="color:#0654BA" href="javascript:{}" class="link_address">change your shipping address</a> or remove this from your <a href="/cart" style="color:#0654BA">Cart</a>.
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?> 
                                                        <?php endforeach; ?>
                                                     </table> 
                                                <?php endif; ?>  
                                            </div>
                                        <?php endif; ?>

                                        <!-- PESOPAY MOBILE VERSION -->
                                        <?php if($key == 'pesopaycdb'): ?>
                                            <div id="pesopaycdb_mobile">
                                                <?php if($pesopaysuccess): ?>
                                                    <p class="chck_privacy" style="font-size: 12px;">
                                                        <input type="checkbox" checked class="checkprivacy pesopay_chk_mobile">
                                                        I acknowledge I have read and understood Easyshop.ph's 
                                                        <a href="/policy" target='_blank'> 
                                                            <span style='border-bottom:1px dotted'> Privacy Policy </span>
                                                        </a>.
                                                    </p>  
                                                    <input type="button" class="pesopaycdb_mobile pesopaycdb_btn orange_btn3" value="Pay via Credit or Debit Card">
                                                    <p class="notify">
                                                        You will be notified regarding your order status via email or sms.
                                                    </p>
                                                <?php else: ?>
                                                    <span style="font-size: 10px;">
                                                        <strong>
                                                            NOTE: one or more of your chosen items are not available for cash on delivery.
                                                        </strong>
                                                    </span>
                                                    <table width="100%" class="table font-12">
                                                        <tr class="tr-header-summary">
                                                            <th>Seller</th>
                                                            <th>Product</th>
                                                            <th style="text-align: center;">Quantity</th>
                                                            <th style="text-align: right;">Price</th>
                                                        </tr>
                                                        <?php foreach ($cat_item as $key => $value): ?>
                                                        <tr>
                                                            <td style="border: 0px 0px 0px 0px;"  width="30%">
                                                                <?=html_escape($value['store_name']); ?>
                                                            </td>
                                                            <td width="40%">
                                                                <?=html_escape($value['name']); ?>
                                                            </td>
                                                            <td  width="15%" align="center">
                                                                <?=$value['qty'] ?>
                                                            </td>
                                                            <td align="right"  width="15%">
                                                                <?=number_format($value['price'], 2, '.',',') ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" style="border-top: 0px;">
                                                                <?=$value['pesopaycdb'] === true
                                                                   ? "<span style='color:green'>Available for Credit Card</span>" 
                                                                   : "<span style='color:red; font-weight:bold;'>Not available for Credit Card</span> (Go to your <a href='/cart' style='color:#0654BA'>Cart</a> and Remove this Item)";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </table> 
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <?php if(!$success):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red;'>NOTE: One or more of your item(s) is unavailable in your location. </span>
                    <?php elseif(!$qtysuccess):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red; '>NOTE: The availability of one of your items is less than your desired quantity. Someone may have purchased the item before you can complete your payment. Check the availability of your item and try again.</span>
                    <?php elseif(!$promoteSuccess['solo_restriction']):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: One of your items can only be purchased  individually.</span>
                    <?php elseif(!$promoteSuccess['purchase_limit']):?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: You have exceeded your purchase limit for a promo of an item in your cart.</span>
                    <?php elseif (!$paymentMethodSuccess): ?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: Can't proceed to payment. One of your items is not available for checkout.</span>
                    <?php else:?>
                        <br/>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>NOTE: One or more of your item(s) is unavailable in your location. </span>
                        <span style='padding-top:8px; font-size: 12px; font-weight:bold;color:red'>Also, the availability of one of your items is less than your desired quantity. Someone may have purchased the item before you can complete your payment. Check the availability of your item and try again. </span>
                    <?php endif;?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Modal Change Shipping Address-->
<div class="modal fade" id="change_ship" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog no-border font-roboto ">
        <div class="modal-content no-border">
            <div class="modal-header no-border bg-orange">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="color: #ffffff;">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Shipping Details</h4>
            </div>
            <?=form_open('', ['class' => 'delAddressFrm','id' => 'delAddressFrm','name' => 'delAddressFrm','enctype' => 'multipart/form-data']);?>
            <div class="modal-body">
                <table style="width: 100%">
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;">Consignee Name:<font color="red">*</font></td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;"><input type="text" name="consignee" id="consignee" class="form-control no-border" value="<?php echo (strlen(trim($consignee)) !== 0)?$consignee:$fullname;  ?>"></td>
                    </tr>
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;">Mobile No:<font color="red">*</font></td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;"><input maxlength="11" placeholder="eg. 09051235678" type="text" name="c_mobile" id="c_mobile"  class="form-control no-border" value="<?php echo (strlen(trim($c_mobile)) !== 0)?$c_mobile:$contactno;?>"> </td>
                    </tr>
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;">Telephone No:</td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;"><input type="text" name="c_telephone" id="c_telephone" class="form-control no-border" placeholder="eg. 354-5973" maxlength="15" value="<?php echo $c_telephone?>"></td>
                    </tr>
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;">Full Address:<font color="red">*</font></td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;"><input type="text" name="c_address" class="c_address form-control no-border"  value="<?php echo html_escape($c_address);?>"></td>
                    </tr>
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;"><span>State/Region:<font color="red">*</font></td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;">
                        <select name="c_stateregion" class="address_dropdown stateregionselect form-control no-border" data-status="<?php echo $c_stateregionID?>">
                            <option value="0">--- Select State/Region ---</option>
                            <?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
                                <option class="echo" value="<?php echo $srkey?>" <?php echo $c_stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                            <?php endforeach;?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;">City:<font color="red">*</font></td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;">
                        <select name="c_city" class="address_dropdown cityselect form-control no-border" data-status="<?php echo $c_cityID?>">
                            <option value="0">--- Select City ---</option>
                            <option class="optionclone" value="" style="display:none;" disabled></option>
                            <?php foreach($city_lookup as $parentkey=>$arr):?>
                                <?php foreach($arr as $lockey=>$city):?>
                                    <option class="echo" value="<?php echo $lockey?>" data-parent="<?php echo $parentkey?>" <?php echo $c_cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                <?php endforeach;?>
                            <?php endforeach;?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" style="padding: 5px 0px 7px 0px;">Country:<font color="red">*</font></td>
                        <td width="70%"  style="padding: 5px 0px 7px 0px;">
                        <select disabled class="form-control no-border">
                            <option selected=""><?php echo $country_name?></option>
                        </select>
                        <input type="hidden" name="c_country" value="<?php echo $country_id?>">
                        </td>
                    </tr>
                </table>
            </div>
            <?php echo form_close();?>
            <div class="modal-footer" style="border-radius:0px !important;">
                <center>
                    <input type="button" value="Change Shipping Details" class="changeAddressBtn orange_btn3">
                </center>
            </div>
        </div>
    </div>
</div>

<!-- Modal Available Location-->
<div class="modal fade" id="avail_loc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog no-border font-roboto ">
        <div class="modal-content no-border">
            <div class="modal-header no-border bg-orange">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="color: #ffffff;">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Available Location</h4>
            </div>
          
            <div class="modal-body">
                <div class="div_view_avail_location">
                    <div>
                        Loading details...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script type='text/javascript'>
    var jsonCity = <?php echo $json_city;?>;
</script>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.idTabs.min.js"></script>
    <script type='text/javascript' src='/assets/js/src/payment.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bootstrap.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.payment_review_responsive.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
