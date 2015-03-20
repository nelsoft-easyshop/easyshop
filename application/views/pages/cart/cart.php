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

        <!--Start of empty cart display-->
        <!--<div class="cart-empty">
            <span>Your cart is currently empty.</span>
            <br/>
            <a href="#" class="btn btn-es-white btn-lg">Return to Shop</a>
        </div>-->
        <!--End of empty cart display-->


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
                            <tr>
                                <td valign="middle">
                                    <i class="fa fa-times fa-lg cart-item-remove"></i>
                                </td>
                                <td>
                                    <div class="cart-item-thumbnail" style="background: url(assets/images/products/apple-p.jpg) center no-repeat; background-size: cover;"></div>
                                </td>
                                <td>
                                    <a href="#" class="cart-item-name">
                                        IPHONE 6 BLACK 64GB WITH 2 YEARS WARRANTY FROM MAC CENTER
                                    </a>
                                    <div class="cart-item-attribute-container">
                                        <div class="cart-item-attribute">
                                            <b>Color : </b>Black
                                        </div>
                                        <div class="cart-item-attribute">
                                            <b>Color : </b>Black
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    &#8369; 42,000.00
                                </td>
                                <td>
                                    <select class="form-es-control input-sm">
                                        <option>1</option>
                                    </select>
                                </td>
                                <td>
                                    &#8369; 42,000.00
                                </td>
                            </tr>
                            
                            <tr>
                                <td valign="middle">
                                    <i class="fa fa-times fa-lg cart-item-remove"></i>
                                </td>
                                <td>
                                    <div class="cart-item-thumbnail" style="background: url(assets/images/products/apple-p.jpg) center no-repeat; background-size: cover;"></div>
                                </td>
                                <td>
                                    <a href="#" class="cart-item-name">
                                        IPHONE 6 BLACK 64GB WITH 2 YEARS WARRANTY FROM MAC CENTER
                                    </a>
                                    <div class="cart-item-attribute-container">
                                        <div class="cart-item-attribute">
                                            <b>Color : </b>Black
                                        </div>
                                        <div class="cart-item-attribute">
                                            <b>Color : </b>Black
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    &#8369; 42,000.00
                                </td>
                                <td>
                                    <select class="form-es-control input-sm">
                                        <option>1</option>
                                    </select>
                                </td>
                                <td>
                                    &#8369; 42,000.00
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--End of cart items-->

        <!--Start of middle buttons-->
        <div class="row">
            <div class="col-md-12">
                <div class="mid-button-container">
                    <center>
                        <a href="#" class="btn btn-es-white btn-lg btn-mid btn-mid-left"><i class="fa fa-angle-left fa-lg"></i>Continue Shopping</a>
                        <a href="#" class="btn btn-es-white btn-lg btn-mid btn-mid-right">Update My Cart<i class="fa fa-refresh fa-lg"></i></a>
                    </center>
                    <div class="mid-button-divider"></div>
                </div>
            </div>
        </div>
        <!--End of middle buttons-->

        <!--Start of trio bottom boxes-->
        <div class="row">
            <!--Start of shipping calculator-->
            <div class="col-md-4">
                <div class="transaction-container bg-gray">
                    <p class="transaction-container-title">Calculate Shipping</p>
                    <p class="transaction-container-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nominis ipso dividendo tollatur stultus instituit ornamenta. 
                    </p>
                    <div class="form-group">
                        <label for="shipping-city">City</label>
                        <select id="shipping-city" class="form-es-control form-es-control-block">
                            <option>-Select City Here-</option>
                            <option>Manila</option>
                            <option>Quaezon City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shipping-state">State/Region</label>
                        <select id="shipping-state" class="form-es-control form-es-control-block">
                            <option>-Select City Here-</option>
                            <option>NCR</option>
                            <option>Region 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-es-green btn-sm">Calculate</button>
                    </div>
                    <div class="form-group">
                        <label for="shipping-total">Total Shipping Fee</label>
                        <input type="text" id="shipping-total" class="form-es-control form-es-control-block" readOnly />
                    </div>
                </div>
            </div>
            <!--End of shipping calculator-->

            <!--Start of points-->
            <div class="col-md-4">
                <div class="transaction-container bg-gray">
                    <p class="transaction-container-title">Use Your EasyShop Points</p>
                    <p class="transaction-container-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nominis ipso dividendo tollatur stultus instituit ornamenta. 
                        <br/>
                        <b>How to Use</b>
                        <ol class="how-to-list">
                            <li>Conversam albam porro corporis porro definitiones dixisset monet vivendi.</li>
                            <li>Integris corrumpit. Cernimus reiciendis.</li>
                            <li>10points = &#8369; 1.00</li>
                        </ol>
                    </p>
                    <div class="form-group">
                        <label for="shipping-total">Your Current Points</label>
                        <input type="text" id="shipping-total" class="form-es-control form-es-control-block" readOnly placeholder="234.00"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-es-green btn-sm">Use Your Points</button>
                    </div>
                </div>
            </div>
            <!--End of points-->

            <!--Start of summary-->
            <div class="col-md-4">
                <div class="transaction-container bg-gray">
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
                                <td>&#8369; 84,000.00</td>
                            </tr>
                            <tr>
                                <td>Shipping Fee</td>
                                <td>&#8369; 400.00</td>
                            </tr>
                            <tr class="border-bottom-1">
                                <td>Points to Deduct</td>
                                <td>&mdash; &#8369; 200.00</td>
                            </tr>
                            <tr>
                                <td>Total Price</td>
                                <td>&#8369; 86,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-es-green btn-lg btn-block">Proceed to checkout</button>
                    <table class="transaction-summary-table payment-method" width="100%">
                        <thead>
                            <tr>
                                <th>Payment Methods</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="payment-method-tr">
                                <td class="payment-method-td">
                                    <img src="/assets/images/img-visa.png" class="img-payment-method" alt="VISA"/>
                                    <img src="/assets/images/img-paypal.png" class="img-payment-method" alt="PayPal"/>
                                    <img src="/assets/images/img-mastercard.png" class="img-payment-method" alt="MasterCard"/>
                                    <img src="/assets/images/img-dragonpay.png" class="img-payment-method" alt="Dragonpay"/>
                                    <img src="/assets/images/img-cod.png" class="img-payment-method" alt="COD"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--End of summary-->
        </div>
        <!--End of trio bottom boxes-->
        
    </div>    
</div>
<div class="my-modal-content remove-item-modal">
    <p>
        Are you sure you want to remove <span class="remove-item-name">IPHONE 6 BLACK 64GB WITH 2 YEARS WARRANTY FROM MAC CENTER</span> from your shopping cart?
    </p>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-es-green">Remove</button>
            <button class="btn btn-es-white simplemodal-close">Cancel</button>
        </center>
    </div>
</div>

<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
<script src="/assets/js/src/cart.js?ver=<?php echo ES_FILE_VERSION ?>"></script>