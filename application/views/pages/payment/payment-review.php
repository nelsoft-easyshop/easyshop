
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
                            <i class="fa icon-cart fa-lg done-icon"></i>
                            <i class="fa fa-check fa-lg new-icon"></i>
                        </div>
                        <div class="breadcrumb-title"> Shopping Cart</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                    <div class="active-right-wing-cart-1"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb">
                    <div class="breadcrumb-left-wing"></div>
                    <div class=" active-left-wing-cart-2"></div>
                    <center>
                        <div class="circle-breadcrumb active-breadcrumb-icon">
                            <i class="fa icon-payment fa-lg"></i>
                        </div>
                        <div class="breadcrumb-title active-breadcrumb-title">Checkout Details</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb">
                    <div class="breadcrumb-left-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa fa-cube fa-lg"></i>
                        </div>
                         <div class="breadcrumb-title">Order Complete</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                </div>
            </div>
        </div>
        <!--End of transaction breadcrumb-->

        <div class="row">
            <!--Start of shipping details-->
            <?=form_open('', ['class' => 'addressForm','id' => 'addressForm','name' => 'addressForm']); ?>
            <div class="col-md-7">
                <input type="hidden" id="currentLat" value="<?=isset($address['lat'])?html_escape($address['lat']):''; ?>" />
                <input type="hidden" id="currentLang" value="<?=isset($address['lng'])?html_escape($address['lng']):''; ?>" />

                <div class="transaction-container bg-white">
                    <p class="transaction-container-title">Shipping Details</p>
                     <p class="transaction-container-text">
                        Please carefully review your shipping details to avoid delays in the delivery of your item(s). Including a landmark also helps the courier in locating your address quickly.
                    </p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-es-danger alert-dismissible" style="display:none" role="alert" id="delivery-address-error">
                                Please fix the errors in the delivery address you have provided.
                            </div>
                            
                            <div class="alert alert-es-success" style="display:none" role="alert" id="delivery-address-success">
                                Delivery address updated successfully.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fname">Consignee Name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" value="<?=isset($address['consignee']) ? html_escape($address['consignee']) : ''; ?>" id="fname" class="form-es-control form-es-control-block" readonly/>
                                <span class="error-span error-consignee error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="mobile">Mobile Number <abbr class="required" title="required">*</abbr></label>
                                <input type="text" value="<?=isset($address['mobile']) ? '0'.html_escape($address['mobile']) : ''; ?>" id="mobile" class="form-es-control form-es-control-block" maxlength="11" onkeypress="return isNumberKey(event , false)" readonly />
                                <span class="error-span error-mobile_number error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="telephone">Telephone Number </label>
                                <input type="text" value="<?=isset($address['telephone']) ? html_escape($address['telephone']) : ''; ?>" id="telephone" class="form-es-control form-es-control-block" readonly onkeypress="return isNumberKey(event, false)" />
                                <span class="error-span error-telephone_number error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fullAddress">Full Address <abbr class="required" title="required">*</abbr></label>
                                <input type="text" value="<?=isset($address['address']) ? html_escape($address['address']) : ''; ?>" id="fullAddress" class="form-es-control form-es-control-block" readonly/>
                                <span class="error-span error-street_address error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="shipping-state">State/Region <abbr class="required" title="required">*</abbr></label>
                                <select id="shipping-state" class="stateregionselect form-es-control form-es-control-block" disabled>
                                    <option value="0">--- Select State ---</option> 
                                    <?php foreach($locations['stateRegionLookup'] as $srkey => $stateregion):?>
                                        <option class="echo" value="<?=$srkey?>" <?=(int)$stateRegion === (int)$srkey ? 'selected' : '';?> >
                                            <?=$stateregion?> 
                                        </option>
                                    <?php endforeach;?>
                                </select>
                                <span class="error-span error-region error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="shipping-city">City <abbr class="required" title="required">*</abbr></label>
                                <select id="shipping-city" class="cityselect form-es-control form-es-control-block" data-usercity="<?=$city; ?>" disabled>
                                    <option value="0">--- Select City ---</option> 
                                </select>
                                <span class="error-span error-city error"></span>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group div-change-shipping-btn">
                               <button type="button" class="btn btn-es-green btn-sm btn-change-shipping">
                                    Change Shipping Address
                                </button>
                            </div>
                            <div class="form-group div-save-shipping-btn" style="display: none;">
                                <button type="button" class="btn btn-es-green btn-sm  btn-save-changes">
                                    Save Changes
                                </button>
                                <button type="button" class="btn btn-es-white btn-sm  btn-change-shipping-cancel">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <?=form_close();?>
            <!--End of shipping details-->

            <!--Start of order summary-->
            <div class="col-md-5">
                <div class="transaction-container bg-gray">
                    <p class="transaction-container-title">Your Order</p>
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
                            <?php foreach ($cartData as $item): ?>
                                <tr class="checkout-item <?php if($item['isAvailableInLocation'] === false): ?>border-bottom-0<?php endif;?>">
                                    <td>
                                        <?=html_escape($item['name']);?>
                                    </td>
                                    <td><?=$item['qty'];?></td>
                                    <td><?=$item['isAvailableInLocation'] === false ? "N/A" : "&#8369; ".$item['shippingFee'] ;  ?></td>
                                    <td>&#8369; <?=number_format($item['subtotal'], 2, '.', ',');?></td>
                                </tr>
                                <?php if($item['isAvailableInLocation'] === false): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="alert alert-es-danger" align="left" style="margin-bottom: 0px;">
                                                This item is not available in your location. See the item location availability <a href="javascript:void(0)" data-itemid="<?=$item['product_itemID']; ?>" class="alert-link available-location-trigger">here</a> or <a href="javascript:void(0);" data-rowid="<?=$item['rowid']; ?>" class="alert-link remove-item">remove</a> this item from your cart checkout to proceed.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif;?>
                                <?php if($item['canPurchaseWithOther'] === false): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="alert alert-es-danger" align="left" style="margin-bottom: 0px;">
                                                This items can only be purchased individually.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif;?>
                                <?php if($item['hasNoPuchaseLimitRestriction'] === false): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="alert alert-es-danger" align="left" style="margin-bottom: 0px;">
                                                You have exceeded your purchase limit for a promo of this item.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif;?>
                                <?php if($item['isQuantityAvailable'] === false): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="alert alert-es-danger" align="left" style="margin-bottom: 0px;">
                                                The availability of this items is less than your desired quantity. Someone may have purchased the item before you can complete your payment. Check the availability of item and try again.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach; ?> 
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    Subtotal
                                </td>
                                <td colspan="3">&#8369; <?=number_format($cartAmount, 2, '.', ','); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    Total Shipping Fee
                                </td>
                                <td colspan="3"><?=$canCheckout ? "&#8369; ". number_format($shippingFee, 2, '.', ',') : "N/A" ; ?></td>
                            </tr>
                            <?php if(EasyShop\PaymentGateways\PointGateway::POINT_ENABLED): ?>
                            <tr class="border-bottom-1">
                                <td>
                                    Easy Points
                                </td>
                                <td colspan="3">&mdash; &#8369; <?=number_format($usedPoints, 2, '.', ','); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td >
                                    Order Total
                                </td>
                                <td  colspan="3" class="checkout-order-total">&#8369; <?=number_format($grandTotal, 2, '.', ',')?></td>
                            </tr>
                        </foot>
                    </table>
                    <br/>
                    <?php if($canCheckout): ?>
                        <?php if($payAllViaPoints === false): ?>
                            <?php if(isset($paymentType['cdb'])): ?>
                                <div class="payment-method-container">
                                    <div class="radio">
                                        <input type="radio" name="payment-method" id="credit" class="payment-label" checked="" value="paypalcdb">
                                        <label class="payment-label payment-name" for="credit">
                                            Credit Card or Debit Card <img src="<?=getAssetsDomain();?>assets/images/payment-methods/img-payment-credit.png" class="payment-method-img"/>
                                        </label>
                                    </div>
                                    <div class="payment-method-desc">
                                        Pay using Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.
                                        
                                        <?php if(in_array(EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL, $checkoutError['paymentTypeError'])):?>
                                            <br/>
                                            <br/> 
                                            <b>NOTE:</b> one or more of your chosen items are not available for cash on delivery.
                                            <table  class="transaction-summary-table transaction-checkout-order" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">
                                                            Product
                                                        </th>
                                                        <th width="20%">
                                                            Quantity
                                                        </th>
                                                        <th width="20%">
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cartData as $item): ?> 
                                                        <?php if($item['paypal'] === false): ?>
                                                            <tr class="checkout-item">
                                                                <td>
                                                                    <?=html_escape($item['name']);?>
                                                                    <br/>
                                                                    <small>Go to your <a href="/cart">Cart</a> and Remove this Item</small>
                                                                </td>
                                                                <td>
                                                                    <?=html_escape($item['qty']);?>
                                                                </td>
                                                                <td>
                                                                    &#8369; <?=number_format($item['subtotal'], 2, '.', ',');?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($paymentType['paypal'])): ?>
                                <div class="payment-method-container">
                                    <div class="radio">
                                        <input type="radio" name="payment-method" id="paypal" class="payment-label" value="paypal">
                                        <label class="payment-label payment-name" for="paypal">
                                            PayPal Account <img src="<?=getAssetsDomain();?>assets/images/payment-methods/img-payment-paypal.png" class="payment-method-img"/>
                                        </label>
                                    </div>
                                    <div class="payment-method-desc" style="display: none;">
                                        Pay using your PayPal account. You will be redirected to the PayPal system to complete the payment.
                                        
                                        <?php if(in_array(EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL, $checkoutError['paymentTypeError'])):?>
                                            <br/>
                                            <br/> 
                                            <b>NOTE:</b> one or more of your chosen items are not available for cash on delivery.
                                            <table  class="transaction-summary-table transaction-checkout-order" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">
                                                            Product
                                                        </th>
                                                        <th width="20%">
                                                            Quantity
                                                        </th>
                                                        <th width="20%">
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cartData as $item): ?> 
                                                        <?php if($item['paypal'] === false): ?>
                                                            <tr class="checkout-item">
                                                                <td>
                                                                    <?=html_escape($item['name']);?>
                                                                    <br/>
                                                                    <small>Go to your <a href="/cart">Cart</a> and Remove this Item</small>
                                                                </td>
                                                                <td>
                                                                    <?=html_escape($item['qty']);?>
                                                                </td>
                                                                <td>
                                                                    &#8369; <?=number_format($item['subtotal'], 2, '.', ',');?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(isset($paymentType['dragonpay'])): ?>
                                <div class="payment-method-container">
                                    <div class="radio">
                                        <input type="radio" id="dragonpay" name="payment-method" class="payment-label" value="dragonpay">
                                        <label class="payment-label payment-name" for="dragonpay">
                                            Dragonpay <img src="<?=getAssetsDomain();?>assets/images/payment-methods/img-payment-dragonpay.png" class="payment-method-img"/>
                                        </label>
                                    </div>
                                    <div class="payment-method-desc" style="display:none">
                                        Dragonpay is a Philippines-based alternative payments solution company that allows buyers to pay for good or services through direct bank debit or over-the-counter (OTC). Note that BDO mall branches are open on weekends. You may also choose SM or LBC as most branches are open on weekends and holidays.
                                    
                                        <?php if(in_array(EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY, $checkoutError['paymentTypeError'])):?>
                                            <br/>
                                            <br/> 
                                            <b>NOTE:</b> one or more of your chosen items are not available for cash on delivery.
                                            <table  class="transaction-summary-table transaction-checkout-order" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">
                                                            Product
                                                        </th>
                                                        <th width="20%">
                                                            Quantity
                                                        </th>
                                                        <th width="20%">
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cartData as $item): ?> 
                                                        <?php if($item['dragonpay'] === false): ?>
                                                            <tr class="checkout-item">
                                                                <td>
                                                                    <?=html_escape($item['name']);?>
                                                                    <br/>
                                                                    <small>Go to your <a href="/cart">Cart</a> and Remove this Item</small>
                                                                </td>
                                                                <td>
                                                                    <?=html_escape($item['qty']);?>
                                                                </td>
                                                                <td>
                                                                    &#8369; <?=number_format($item['subtotal'], 2, '.', ',');?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($paymentType['pesopaycdb'])): ?>
                                <div class="payment-method-container">
                                    <div class="radio">
                                        <input type="radio" id="pesopay" name="payment-method" class="payment-label" value="pesopay">
                                        <label class="payment-label payment-name" for="pesopay">
                                            PesoPay <img src="<?=getAssetsDomain();?>assets/images/payment-methods/img-payment-pesopay.png" class="payment-method-img"/>
                                        </label>
                                    </div>
                                    <div class="payment-method-desc" style="display:none">
                                        Pay using Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.
                                    
                                        <?php if(in_array(EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC, $checkoutError['paymentTypeError'])):?>
                                            <br/>
                                            <br/> 
                                            <b>NOTE:</b> one or more of your chosen items are not available for cash on delivery.
                                            <table  class="transaction-summary-table transaction-checkout-order" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">
                                                            Product
                                                        </th>
                                                        <th width="20%">
                                                            Quantity
                                                        </th>
                                                        <th width="20%">
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cartData as $item): ?> 
                                                        <?php if($item['pesopaycdb'] === false): ?>
                                                            <tr class="checkout-item">
                                                                <td>
                                                                    <?=html_escape($item['name']);?>
                                                                    <br/>
                                                                    <small>Go to your <a href="/cart">Cart</a> and Remove this Item</small>
                                                                </td>
                                                                <td>
                                                                    <?=html_escape($item['qty']);?>
                                                                </td>
                                                                <td>
                                                                    &#8369; <?=number_format($item['subtotal'], 2, '.', ',');?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($paymentType['cod'])): ?>
                                <div class="payment-method-container">
                                    <div class="radio">
                                        <input type="radio" id="cod" name="payment-method" class="payment-label" value="cod">
                                        <label class="payment-label payment-name" for="cod">
                                            Cash on Delivery
                                        </label>
                                    </div>
                                    <div class="payment-method-desc" style="display:none">
                                        You can pay in cash to our courier when you receive the goods at your doorstep. 
                                        <?php if(in_array(EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY, $checkoutError['paymentTypeError'])):?>
                                            <br/>
                                            <br/> 
                                            <b>NOTE:</b> one or more of your chosen items are not available for cash on delivery.
                                            <table  class="transaction-summary-table transaction-checkout-order" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">
                                                            Product
                                                        </th>
                                                        <th width="20%">
                                                            Quantity
                                                        </th>
                                                        <th width="20%">
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cartData as $item): ?> 
                                                        <?php if($item['cash_delivery'] === false): ?>
                                                            <tr class="checkout-item">
                                                                <td>
                                                                    <?=html_escape($item['name']);?>
                                                                    <br/>
                                                                    <small>Go to your <a href="/cart">Cart</a> and Remove this Item</small>
                                                                </td>
                                                                <td>
                                                                    <?=html_escape($item['qty']);?>
                                                                </td>
                                                                <td>
                                                                    &#8369; <?=number_format($item['subtotal'], 2, '.', ',');?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div> 
                            <?php endif; ?>
                            <br/>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="privacy-check" checked="">
                                    I acknowledge I have read and understood <a href="/policy" target="_blank">Easyshop.ph's  Privacy Policy</a>.
                                </label>
                            </div>
                            <br/>
                            <button class="btn btn-es-green btn-lg btn-block btn-payment-button" type="button" data-points="<?=$usedPoints?>">
                                Pay Via Credit Card or Debit Card
                            </button>
                        <?php else: ?>
                            <button class="btn btn-es-green btn-lg btn-block pay-via-easypoints" type="button" data-points="<?=$usedPoints?>">
                                Pay Via Easy Points
                            </button>
                        <?php endif; ?>
                    <?php else: ?> 
                        <?php foreach ($checkoutError['errorMessage'] as $error): ?>
                        <div class="alert alert-es-danger alert-checkout" align="left" style="margin-bottom: 0px;">
                            <b>NOTE :</b> <?=html_escape($error); ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <center><a href="/cart" class="link-blue">Go back to cart</a></center>
                </div>
            </div>
            <!--End of order summary-->
        </div>
    </div>
</div>

<div class="my-modal-content available-location-modal" style="display: none;">
    <h3 class="my-modal-title">
        Available Location
    </h3>
    <p>
        The following items are currently unavailable for your location. Please select a new shipping address to proceed with your checkout.
    </p>
    <div class="form-group">
        <label for="shipping-city">These item are available in the following locations:</label> 
        <!-- append select -->
        <select class="form-es-control form-es-control-block location-container"></select>
    </div>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-es-white simplemodal-close">Close</button>
        </center>
    </div>
</div>

<div id="pesopaycdb"></div>

<script type='text/javascript'>
    var jsonCity = <?=json_encode($locations['cityLookup']);?>;
</script>  

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src="/assets/js/src/new-payment.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.payment-review.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

