<link type="text/css" href='/assets/css/base.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-cart.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>

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

        
        <div class="row">
            <!--Start of shipping details-->
            <div class="col-md-7">
                <div class="transaction-container bg-white">
                    <p class="transaction-container-title">Shipping Details</p>
                     <p class="transaction-container-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Interrogari re pervenias videmus quando suspicor, ponit fugiat leguntur cupiditatibus usque intus careat disputatione, sint audivi affirmatis indoctis secutus,
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fname">First Name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="fname" class="form-es-control form-es-control-block" readonly/>
                             </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="lname">Last Name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="lname" class="form-es-control form-es-control-block" readonly />
                             </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact">Contact Number <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="contact" class="form-es-control form-es-control-block" readonly />
                                <span class="error">Invalid number format</span>
                             </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fullAddress">Full Address <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="fullAddress" class="form-es-control form-es-control-block" readonly/>
                             </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="state">State/Region <abbr class="required" title="required">*</abbr></label>
                                <select id="state" class="form-es-control form-es-control-block" disabled>
                                    <option>NCR</option>
                                </select>
                             </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="city">City <abbr class="required" title="required">*</abbr></label>
                                <select id="city" class="form-es-control form-es-control-block" disabled>
                                    <option>Manila</option>
                                    <option>Quezon City</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="landmark">Nearest Landmark </label>
                                <input type="text" id="landmark" class="form-es-control form-es-control-block"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">Order Notes/Comments </label>
                                <textarea id="notes" rows="10" class="form-es-control form-es-control-block"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group div-change-shipping-btn">
                               <button class="btn btn-es-green btn-sm btn-change-shipping">
                                    Change Shipping Address
                                </button>
                            </div>
                            <div class="form-group div-save-shipping-btn" style="display: none;">
                                <button class="btn btn-es-green btn-sm  btn-save-changes">
                                    Save Changes
                                </button>
                                <button class="btn btn-es-white btn-sm  btn-change-shipping-cancel">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
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
                            <!--Add border-bottom-0 class if item is not available to buyer's location-->
                            <tr class="checkout-item border-bottom-0">
                                <td>
                                    IPHONE 6 BLACK 64GB WITH 2 YEARS WARRANTY FROM MAC CENTER
                                </td>
                                <td>1</td>
                                <td>&#8369; 42,000.00</td>
                                <td>&#8369; 42,000.00</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="alert alert-es-danger" align="left" style="margin-bottom: 0px;">
                                        This item is not available in your location. See the item location availability <a href="#" class="alert-link">here</a> or <a href="#" class="alert-link">remove</a> this item from your cart checkout to proceed.
                                    </div>
                                </td>
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
                            <tr>
                                <td >
                                    Order Total
                                </td>
                                <td  colspan="3" class="checkout-order-total">&#8369; 50,760.00</td>
                            </tr>
                        </foot>
                    </table>
                    <br/>
                     <!--<table class="transaction-summary-table transaction-checkout-order" width="100%">
                        <thead>
                            <tr class="border-bottom-1">
                                <th>How would you like to pay?</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                    -->
                    <div class="alert alert-es-danger alert-checkout" align="left" style="margin-bottom: 0px;">
                        <b>NOTE :</b> One or more of your item(s) is unavailable in your location.
                    </div>
                    <div class="payment-method-container">
                        <div class="radio">
                            <input type="radio" name="payment-method" id="credit" class="payment-label" value="">
                            <label class="payment-label payment-name" for="credit">
                                Credit Card or Debit Card <img src="/assets/images/payment-methods/img-payment-credit.png" class="payment-method-img"/>
                            </label>
                        </div>
                        <div class="payment-method-desc">
                            Pay using Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.
                        </div>
                    </div>

                    <div class="payment-method-container">
                        <div class="radio">
                            <input type="radio" name="payment-method" id="paypal" class="payment-label" value="">
                            <label class="payment-label payment-name" for="paypal">
                                PayPal Account <img src="/assets/images/payment-methods/img-payment-paypal.png" class="payment-method-img"/>
                            </label>
                        </div>
                        <div class="payment-method-desc" style="display: none;">
                            Pay using your PayPal account. You will be redirected to the PayPal system to complete the payment.
                        </div>
                    </div>
                    
                    <div class="payment-method-container">
                        <div class="radio">
                            <input type="radio" id="dragonpay" name="payment-method" class="payment-label" value="">
                            <label class="payment-label payment-name" for="dragonpay">
                                Dragonpay <img src="/assets/images/payment-methods/img-payment-dragonpay.png" class="payment-method-img"/>
                            </label>
                        </div>
                        <div class="payment-method-desc" style="display:none">
                            Dragonpay is a Philippines-based alternative payments solution company that allows buyers to pay for good or services through direct bank debit or over-the-counter (OTC). Note that BDO mall branches are open on weekends. You may also choose SM or LBC as most branches are open on weekends and holidays.
                        </div>
                    </div>

                    <div class="payment-method-container">
                        <div class="radio">
                            <input type="radio" id="pesopay"name="payment-method" class="payment-label" value="">
                            <label class="payment-label payment-name" for="pesopay">
                                PesoPay <img src="/assets/images/payment-methods/img-payment-pesopay.png" class="payment-method-img"/>
                            </label>
                        </div>
                        <div class="payment-method-desc" style="display:none">
                            Pay using Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.
                        </div>
                    </div>

                    <div class="payment-method-container">
                        <div class="radio">
                            <input type="radio" id="cod" name="payment-method" class="payment-label" value="">
                            <label class="payment-label payment-name" for="cod">
                                Cash on Delivery
                            </label>
                        </div>
                        <div class="payment-method-desc" style="display:none">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Superstitione erunt vituperata iactant oderit consuevit propemodum eruditionem, tarentinis.
                            <br/>
                            <br/>
                            <!--Display this table if one or more item is not available for COD-->
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
                                    <tr class="checkout-item">
                                        <td>
                                            IPHONE 6 BLACK 64GB WITH 2 YEARS WARRANTY FROM MAC CENTER
                                            <br/>
                                            <small>Go to your <a href="#">Cart</a> and Remove this Item</small>
                                        </td>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            &#8369; 42,000.00
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="">
                            I acknowledge I have read and understood <a href="#">Easyshop.ph's  Privacy Policy</a>.
                        </label>
                    </div>
                    <br/>
                    <button class="btn btn-es-green btn-lg btn-block btn-payment-button">
                        Pay Via Credit Card or Debit Card
                    </button>
                </div>
            </div>
            <!--End of order summary-->
        </div>
        
    </div>    
</div>

<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
<script src="/assets/js/src/cart.js?ver=<?php echo ES_FILE_VERSION ?>"></script>