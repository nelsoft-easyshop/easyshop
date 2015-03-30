
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href='/assets/css/boostrap-modal.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/base.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/new-cart.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.cart.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
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
                            <i class="fa icon-cart fa-lg"></i>
                        </div>
                        <div class="breadcrumb-title">Shopping Cart</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb">
                    <div class="breadcrumb-left-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa icon-payment fa-lg"></i>
                        </div>
                        <div class="breadcrumb-title">Checkout Details</div>
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

        <?php if($isCartEmpty): ?>
            <!--Start of empty cart display-->
            <div class="cart-empty">
                <span>Your cart is currently empty.</span>
                <br/>
                <a href="/" class="btn btn-es-white btn-lg">Return to Shop</a>
            </div>
            <!--End of empty cart display-->
        <?php else: ?>
            <!--Start of transaction cart items-->
            <div class="row">
                <div class="col-md-12">
                    <div class="transaction-container bg-white">
                        <table class="cart-table" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="3" width="50%">
                                        Item List
                                    </th>
                                    <th width="15%">
                                        Price
                                    </th>
                                    <th width="15%" align="center">
                                        Quantity
                                    </th>
                                    <th width="20%">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr class="row-<?=html_escape($item['rowid']);?>">
                                        <td valign="middle">
                                            <i class="fa fa-times fa-lg cart-item-remove"
                                                data-name="<?=html_escape($item['name']);?>"
                                                data-rowid="<?=html_escape($item['rowid']);?>"></i>
                                        </td>
                                        <td>
                                            <div class="cart-item-thumbnail" style="background: url(<?=getAssetsDomain(); ?><?=$item['imagePath']; ?>categoryview/<?=$item['imageFile']; ?>) center no-repeat; background-size: cover;"></div>
                                        </td>
                                        <td>
                                            <a href="/item/<?=$item['slug'];?>" class="cart-item-name">
                                                <?=html_escape($item['name']);?>
                                            </a>
                                            <div class="cart-item-attribute-container">
                                                <?php foreach ($item['options'] as $optionKey => $value):?>
                                                    <?php $optionValue = explode('~', $value)[0]; ?>
                                                    <div class="cart-item-attribute">
                                                        <b><?=html_escape($optionKey);?> : </b><?=html_escape($optionValue);?>
                                                    </div>
                                                <?php endforeach; ?> 
                                            </div> 
                                            <div class="mobile-price-quantity">
                                                <div class="cart-item-attribute">
                                                    <b>Price : </b>
                                                    <span class="cart-price-mobile">
                                                        &#8369;
                                                        <span class="cart-item-price">
                                                            <?=number_format($item['price'], 2, '.', ',');?>
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="cart-item-attribute">
                                                    <b>Quantity : </b>
                                                    <select class="form-es-control input-sm item-quantity" data-rowid="<?=html_escape($item['rowid']);?>">
                                                        <?php for ($i = 1; $i <= $item['maxqty']; $i++): ?>
                                                            <option value="<?=$i?>" <?= (int) $item['qty'] !== (int) $i ?:'selected' ?>><?=$i?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div class="cart-item-attribute">
                                                    <span class="cart-total-item-price">
                                                        &#8369;
                                                        <span class="cart-item-subtotal">
                                                            <?=number_format($item['subtotal'], 2, '.', ',');?>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            &#8369; 
                                            <span class="cart-item-price">
                                                <?=number_format($item['price'], 2, '.', ',');?>
                                            </span>
                                        </td>
                                        <td>
                                            <select class="form-es-control input-sm item-quantity" data-rowid="<?=html_escape($item['rowid']);?>">
                                                <?php for ($i = 1; $i <= $item['maxqty']; $i++): ?>
                                                    <option value="<?=$i?>" <?= (int) $item['qty'] !== (int) $i ?:'selected' ?>><?=$i?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        <td>
                                            &#8369; 
                                            <span class="cart-item-subtotal">
                                                <?=number_format($item['subtotal'], 2, '.', ',');?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--End of cart items-->
        <?php endif; ?>
        <!--Start of trio bottom boxes-->
        <div class="row">
            <!--Start of points-->
            <div class="col-md-7">
                <div class="transaction-container bg-gray min-height-459">
                    <p class="transaction-container-title">Use Your EasyPoints</p>
                    <p class="transaction-container-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Patientiamque totam fatemur, labores, ennius debet suapte aristippi neglexerit maiora benivolentiam credere iustitia, urbane.
                        <br/>
                        <b>How to Use</b>
                        <ol class="how-to-list">
                            <li>Conversam albam porro corporis porro definitiones dixisset monet vivendi.</li>
                            <li>Pulcherrimum concertationesque utens vitam nonne miseram tenent versuum innumerabiles. </li>
                            <li>Iudicio nivem reperietur plurimum. Huius mollitia intercapedo beata optime graecos numquidnam. Declinationem fortunae quiete.</li>
                            <li>10points = &#8369; 1.00</li>
                        </ol>
                    </p>
                    <div class="form-group">

                        <label for="points-total">Your Current EasyPoints : <?=$userPoints;?></label>
                        <input type="text" id="points-total" data-totalpoints="<?=$userPoints;?>" class="form-es-control form-es-control-block" placeholder="Enter the amount of points you want to use"/>
 
                    </div>
                    <div class="form-group">
                        <button class="btn btn-es-green btn-sm btn-deduct-points" <?=$isCartEmpty ? 'disabled' : ''; ?>>Use Points</button>
                        <button class="btn btn-es-white btn-sm btn-reset-points" <?=$isCartEmpty ? 'disabled' : ''; ?>>Reset</button>
                    </div>
                </div>
            </div>
            <!--End of points-->

            <!--Start of summary-->
            <div class="col-md-5">
                <div class="transaction-container bg-gray min-height-435 summary-container">
                    <p class="transaction-container-title">Summary</p>
                    <table class="transaction-summary-table" width="100%">
                        <thead>
                            <tr>
                                <th colspan="2">Cart Totals</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cart Subtotal</td>
                                <td>&#8369; 
                                    <span id="summary-cart-subtotal" data-cartprice="<?=number_format($totalAmount, 2, '.', ''); ?>">
                                        <?=number_format($totalAmount, 2, '.', ','); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Shipping Fee</td>
                                <td>
                                    <?php if($userAddress): ?>
                                        &#8369; 
                                        <span id="summary-shipping" data-totalshipping="<?=number_format($totalShippingFee, 2, '.', ''); ?>">
                                            <?=number_format($totalShippingFee, 2, '.', ','); ?>
                                        </span>
                                        <small class="calculate-shipping-label">
                                            <i class="fa fa-plus"></i> Calculate Shipping
                                        </small>
                                    <?php else: ?>
                                        <small class="calculate-shipping-label">
                                            No shipping location set.
                                        </small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="border-bottom-1">
                                <td>Points to Deduct</td>
                                <td>&mdash; &#8369; <span id="summary-points">0</span></td>
                            </tr>
                            <tr>
                                <td>Total Price</td>
                                <td>
                                    &#8369;
                                    <span id="summary-cart-total" data-cartprice="<?=number_format($totalAmount, 2, '.', ''); ?>" >
                                        <?=number_format(bcadd($totalAmount, $totalShippingFee, 4), 2, '.', ','); ?>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table> 
                    <table class="transaction-summary-table payment-method" width="100%">
                        <thead>
                            <tr>
                                <th>Payment Methods</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="payment-method-tr">
                                <td class="payment-method-td">
                                    <img src="<?=getAssetsDomain()?>assets/images/img-visa.png" class="img-payment-method" alt="VISA"/>
                                    <img src="<?=getAssetsDomain()?>assets/images/img-paypal.png" class="img-payment-method" alt="PayPal"/>
                                    <img src="<?=getAssetsDomain()?>assets/images/img-mastercard.png" class="img-payment-method" alt="MasterCard"/>
                                    <img src="<?=getAssetsDomain()?>assets/images/img-dragonpay.png" class="img-payment-method" alt="Dragonpay"/>
                                    <img src="<?=getAssetsDomain()?>assets/images/img-cod.png" class="img-payment-method" alt="COD"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <?=form_open('payment/review', ['class' => 'reviewForm','id' => 'reviewForm','name' => 'reviewForm']); ?>
                        <input type="hidden" id="used-points" name="used_points"  value="0" />
                        <button class="btn btn-es-green btn-lg btn-block" <?=$isCartEmpty ? 'disabled' : ''; ?>>Proceed to checkout</button>
                    <?=form_close();?> 
                    <center><a href="/" class="link-blue">Continue shopping</a></center>
                </div>
            </div>
            <!--End of summary-->
        </div>
        <!--End of trio bottom boxes-->
        
    </div>    
</div>
<div class="my-modal-content remove-item-modal">
    <p>
        Are you sure you want to remove <span class="remove-item-name"></span> from your shopping cart?
    </p>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-es-green remove-item">Remove</button>
            <button class="btn btn-es-white simplemodal-close">Cancel</button>
        </center>
    </div>
</div> 

<div class="my-modal-content shipping-calculator-modal" style="display: none;">
    <h3 class="my-modal-title">
        Shipping calculator
    </h3>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Patientiamque totam fatemur, labores, ennius debet suapte aristippi neglexerit maiora benivolentiam credere iustitia, urbane. 
    </p>
    <div class="form-group">
        <label for="shipping-city">State/Region</label> 
        <select id="shipping-state" class="stateregionselect form-es-control form-es-control-block">
            <option value="0" selected="">--- Select State ---</option> 
            <?php foreach($locations['stateRegionLookup'] as $srkey => $stateregion):?>
                <option class="echo" value="<?=$srkey?>">
                    <?=$stateregion?>
                </option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="shipping-state">City</label>
        <select id="shipping-city" class="cityselect form-es-control form-es-control-block">
            <option value="0" selected="">--- Select City ---</option> 
            <?php foreach($locations['cityLookup'] as $parentkey => $arr):?>
                <?php foreach($arr as $lockey => $city):?>
                    <option class="echo" value="<?=$lockey?>" data-parent="<?=$parentkey?>">
                        <?=$city?>
                    </option>
                <?php endforeach;?>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="shipping-total">Total Shipping Fee</label>
        <input type="text" id="shipping-total" class="form-es-control form-es-control-block" readOnly />
    </div>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-es-green update-shipping" disabled="disabled" >Save Location</button>  
        </center>
    </div>
</div>
<div>
    <?php if($userAddress): ?>
        <input type="hidden" id="fname" value="<?=html_escape($userAddress['address']['consignee']); ?>" />
        <input type="hidden" id="mobile" value="0<?=html_escape($userAddress['address']['mobile']); ?>" />
        <input type="hidden" id="fullAddress" value="<?=html_escape($userAddress['address']['address']); ?>" />
        <input type="hidden" id="telephone" value="<?=html_escape($userAddress['address']['telephone']); ?>" />
        <input type="hidden" id="currentLat" value="<?=html_escape($userAddress['address']['lat']); ?>" />
        <input type="hidden" id="currentLang" value="<?=html_escape($userAddress['address']['lng']); ?>" />
    <?php endif; ?>
</div>

<script type='text/javascript'>
    var jsonCity = <?=json_encode($locations['cityLookup']);?>;
</script> 

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>' type='text/javascript' ></script>
    <script src="/assets/js/src/cart.js?ver=<?php echo ES_FILE_VERSION ?>" type='text/javascript' ></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.cart.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>


