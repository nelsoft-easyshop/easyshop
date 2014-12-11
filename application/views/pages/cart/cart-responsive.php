<link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/my_cart_css.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/bootstrap-mods.css" type="text/css" media="screen"/>

<div class="container container-cart-responsive">
    <h2 class="my_cart_title">My Cart</h2>
    <table width="100%" class="table table-responsive font-roboto hide-to-536 tbl_deskptop">
        <tr class="tr-header-cart">
            <td style="vertical-align: middle;" width="5%"><input type="checkbox" onclick="selectAll(this)" data_id="checkAll_desktop" class ="checkAll" checked="checked"/></td>
            <td align="left" colspan="2" width="35%">Item List</td>
            <td align="center" width="20%">Price</td>
            <td align="center" width="20%">Quantity</td>
            <td align="right" width="20%">Sub Total</td>
            <td width="5%" class="display-when-desktop">&nbsp;</td>
        </tr>
        <?php foreach ($cart_items as $row): ?>
            <tr id="row<?php echo $row['rowid']; ?>" class="tr-cart-content row_<?php echo $row['rowid']; ?>">
                <td>
                    <input type="checkbox" onclick="singleSelect(this)" class="single1_checkAll_desktop" id="rad_<?php echo $row['rowid'] ?>"
                           value="<?php echo number_format($row['price'] * $row['qty'], 2, '.', ','); ?>"
                           checked="checked" data="<?php echo $row['rowid'] ?>" name="checkbx[]">
                </td>
                <td width="7%">
                    <a href="<?= '/item/' . $row['slug']; ?>" class="has-tooltip"
                       data-image="/<?php echo $row['imagePath'] ?>categoryview/<?php echo $row['imageFile']; ?>">
                        <span class="cart-item-image-con">
                            <img  src="/<?php echo $row['imagePath']; ?>thumbnail/<?php echo $row['imageFile']; ?>" class="cart-item-image" />
                        </span>
                    </a>
                </td>
                <td style="align:left;">
                    <a class="product_title"
                       href="<?= '/item/' . $row['slug']; ?>"> <?php echo html_escape($row['name']); ?></a>
                    <br/>
                    <?php
                    if (!array_filter($row['options'])) {
                        echo '<span class="attr b-label font-12">No additional details</span>';
                    } else {
                        $key = array_keys($row['options']); //get the key of options,used in checking the product in the database
                        for ($a = 0; $a < sizeof($key); $a++) {
                            $attr = $key[$a];
                            $attr_value = $row['options'][$key[$a]];
                            $attr_value2 = explode('~', $attr_value);
                            echo '<span class="attr b-label font-12">' . html_escape($attr) . ':</span><span class="b-label font-12">' . html_escape($attr_value2[0]) . '</span><br/>';
                        }
                    }
                    ?>

                </td>
                <td style="vertical-align:top; text-align:center;">
                <span class="span-price">
                <p class="p-price">&#8369; <?php echo number_format($row['price'], 2, '.', ','); ?></p>
                    <?php if ($row['is_promote'] === "1"): ?>
                        <p>&#8369; <?php echo number_format($row['original_price'], 2, '.', ','); ?></p>
                        <p>
                            Discount <?php echo round(($row['original_price'] - $row['price']) / $row['original_price'] * 100); ?>
                            %</p>
                    <?php endif; ?>
                </span>
                </td>
                <td>
                    <center>
                        <input id="<?php echo $row['rowid']; ?>" onkeypress="return isNumberKey(event);" type="text"
                               class="inpt_qty" max-qty="<?php echo $row['maxqty']; ?>" onchange="changeQuantity(this);" maxlength="3"
                               value="<?php echo $row['qty']; ?>">
                        <br/>

                        <p class="p-availability">Availability : <?php echo $row['maxqty']; ?></p>
                    </center>
                </td>
                <td style="text-align: right;">
                    <?php
                    $totalprice = $row['price'] * $row['qty'];
                    ?>
                    <p class="subtotal">Php
                        <span class="subtotal<?php echo $row['rowid']; ?>"><?php echo " " . number_format($totalprice, 2, '.', ','); ?></span>
                    </p>

                    <p class="display-when-mobile">
                        <a class="btn btn-orange btn-remove dlt_<?php echo $row['rowid']; ?>" onclick="del(this);" val="<?php echo $row['rowid']; ?>" ><i class="glyphicon glyphicon-trash "></i> Remove</a>
                    </p>
                </td>
                <td class="display-when-desktop" align="center">
                    <p>
                        <a class="delete">
                            <input type="button"
                                   class="del span_bg cart_delete dlt_<?php echo $row['rowid']; ?>" val="<?php echo $row['rowid']; ?>" onclick="del(this);"
                                   name="delete"
                                   value="Remove" style="font-size: 13px;">
                        </a>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="display-when-mobile-536">
        <table width="100%" class="table table-responsive font-roboto">
            <tr class="tr-header-cart">
                <td style="vertical-align: middle;" width="5%"><input type="checkbox" data_id="checkAll_tablet" onclick="selectAll(this)" class ="checkAll2"  checked="checked"/>
                </td>
                <td align="left" colspan="2" width="35%">Item List</td>
            </tr>
            <?php foreach ($cart_items as $row): ?>
                <tr id="row<?php echo $row['rowid']; ?>" class="tr-cart-content row_<?php echo $row['rowid']; ?>">
                    <td>
                        <input type="checkbox" onclick="singleSelect(this)"  class="single1_checkAll_tablet"  id="rad_<?php echo $row['rowid'] ?>"
                               value="<?php echo number_format($row['price'] * $row['qty'], 2, '.', ','); ?>"
                               checked="checked" data="<?php echo $row['rowid'] ?>" name="checkbx[]">
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-1">
                                <a class="product_title"
                                   href="<?= '/item/' . $row['slug']; ?>"> <?php echo html_escape($row['name']); ?></a>

                                <table width="100%">
                                    <tr>
                                        <td width="50%">
                                            <a href="<?= '/item/' . $row['slug']; ?>" class="has-tooltip"
                                               data-image="/<?php echo $row['imagePath']; ?>categoryview/<?php echo $row['imageFile']; ?>">
                                                <span class="cart-item-image-con">
                                                    <img  src="/<?php echo $row['imagePath']; ?>thumbnail/<?php echo $row['imageFile']; ?>" class="cart-item-image" />
                                                </span>
                                            </a>
                                        </td>
                                        <td style="vertical-align: top; text-align:right;" align="right" width="50%">
                                            <p style="margin-top:10px;">
                                                <?php
                                                $totalprice = $row['price'] * $row['qty'];
                                                ?>

                                            <p class="subtotal pull-right">Php <span
                                                    class="subtotal<?php echo $row['rowid']; ?>"><?php echo " " . number_format($totalprice, 2, '.', ','); ?></span>
                                            </p>
                                            </p>

                                            <p align="right" style="margin-top: 52px;">
                                                <a class="btn btn-orange btn-remove pull-right dlt_<?php echo $row['rowid']; ?>" onclick="del(this);" val="<?php echo $row['rowid']; ?>" >
                                                    <i class="glyphicon glyphicon-trash"></i> Remove</a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="col-sm-1">

                               <table style="margin-top: -15px; margin-left: -30px;">
                                    <tr>
                                        <td>
                                            <b class="b-label">Price: </b>
                                        </td>
                                        <td style="padding: 5px 0px 3px 45px; margin-top: 5px; font-size: 12px;">
                                        <span class="span-price">
                                        <span
                                            class="p-price">&#8369; <?php echo number_format($row['price'], 2, '.', ','); ?></span>
                                            <?php if ($row['is_promote'] === "1"): ?>
                                                <p>
                                                    &#8369; <?php echo number_format($row['original_price'], 2, '.', ','); ?></p>
                                                <p>
                                                    Discount <?php echo round(($row['original_price'] - $row['price']) / $row['original_price'] * 100); ?>
                                                    %</p>
                                            <?php endif; ?>
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b class="b-label">Quantity: </b>
                                        </td>
                                        <td style="padding: 5px 0px 3px 45px; margin-top: 5px; font-size: 12px;">
                                            <input id="<?php echo $row['rowid']; ?>"
                                                   onkeypress="return isNumberKey(event);" type="text" class="inpt_qty"
                                                   mx="<?php echo $row['maxqty']; ?>" onchange="changeQuantity(this);"
                                                   maxlength="3" value="<?php echo $row['qty']; ?>"
                                                   style="height: 25px !important;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b class="b-label">Availability: </b>
                                        </td>
                                        <td style="padding: 5px 0px 3px 45px; margin-top: 5px; font-size: 12px;">
                                            <span class="p-price">
                                                <?php echo $row['maxqty']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <br/>
                                    <?php
                                    if (!array_filter($row['options'])) {
                                        echo '<tr><td colspan=\'2\' style="padding: 5px 0px 3px 0px; margin-top: 5px; font-size: 12px;"><span class="attr b-label">No additional details</span></td></tr>';
                                    } else {
                                        $key = array_keys($row['options']); //get the key of options,used in checking the product in the database
                                        for ($a = 0; $a < sizeof($key); $a++) {
                                            $attr = $key[$a];
                                            $attr_value = $row['options'][$key[$a]];
                                            $attr_value2 = explode('~', $attr_value);
                                            echo '<tr><td><span class="attr b-label"><b>' . html_escape($attr) . ':</b></span></td><td style="padding: 5px 0px 3px 45px; margin-top: 5px; font-size: 12px;"><span class="font-12 p-price">' . html_escape($attr_value2[0]) . '</span></td></tr>';
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="my_cart_total my_cart_total_div pull-right roboto">
        <p>Total: &#8369;<span id="total"><?php echo $total; ?></span></p>

        <p><span>VAT included</span></p>
    </div>
    <div class="display-when-desktop pull-right">
        <div class="may_cart_payment">
            <a href="<?=$continue_url?>"
               class="continue">Continue Shopping</a>
            <?php if (!count($cart_items) <= 0) { ?>
                <a class="btn payment" id="proceed_payment_desktop" onclick="proceedPayment(this)" child="single1_checkAll_desktop">Proceed to Payment<span></span></a>
            <?php } ?>

        </div>
    </div>

    <div class="display-when-mobile" style="margin-top: -20px !important;">
        <div class="may_cart_payment">
            <center>
                <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/' . 'product/categories_all'; ?>"
                   class="continue">Continue Shopping</a>
            </center>

            <?php if (!count($cart_items) <= 0) { ?>
                <a class="btn payment btn-lg btn-block" id="proceed_payment_tablet" onclick="proceedPayment(this)" child="single1_checkAll_tablet">Proceed to Payment<span></span></a>
            <?php } ?>

        </div>
    </div>
</div>

<div id="div_cart_modal">
    <h1>Cart - Remove item</h1>
    <div class="div_cart_modal_container">
        Are you sure you would like to remove this item from the shopping cart?
    </div>
    <button class="btn btn-default-3">Yes</button>
    <span class="modalCloseImg simplemodal-close btn btn-default-1">Cancel</span>
</div>

<div id="navigator">
</div>
<div class="clear"></div>

<script src="/assets/js/src/vendor/numeral.min.js"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script src="/assets/js/src/cart.js?ver=<?= ES_FILE_VERSION ?>" type="text/javascript"></script>