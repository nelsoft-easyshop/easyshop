
<div class="div-tab">
<div class="dashboard-breadcrumb">
    <ul>
        <li>Dashboard</li>
        <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Store</li>
        <li class="bc-arrow"><i class="fa fa-angle-right"></i>Transactions</li>
    </ul>
</div>
<div class="div-tab-inner">
<div class="transaction-tabs">
    <ul class="idTabs">
        <li><a href="#on-going-transaction">On going</a></li>
        <li><a href="#completed-transaction">Completed</a></li>
    </ul>
</div>
<!---------------------------------------------------------------ongoing bought starts here-------------------------------------------------------------->
<div class="col-md-12" id="on-going-transaction">
<div class="row">
<div class="transaction-title-bought">
    <span class="trans-title">Bought</span>
    <span class="count"><?=count($transactionInfo['transaction']['ongoing']['bought'])?></span>
</div>
<div class="on-going-transaction-list-bought">
<?PHP if ( (int) count($transactionInfo['transaction']['ongoing']['bought']) >= 1) : ?>
    <div class="mrgn-top-20 mrgn-bttm-25 row">
        <div class="col-md-9">
            <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
            <button class="btn btn-default-3">
                <i class="icon-fax"></i> <span>Print</span>
            </button>
            <button class="btn btn-default-3">
                <i class="icon-category"></i> <span>Export CSV</span>
            </button>
        </div>
        <div class="col-md-3 text-right">
            <span>Sort By:</span>
            <select class="select-filter-item">
                <option selected=selected>Last Modified</option>
                <option>2</option>
                <option>3</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="transaction-item">
    <?PHP foreach($transactionInfo['transaction']['ongoing']['bought'] as $key => $boughtTransactionDetails) : ?>
        <div class="item-list-panel">
            <div class="transac-title">
                <?php if (intval($boughtTransactionDetails['idPaymentMethod']) === 1 && intval($boughtTransactionDetails['isFlag']) === 1) : ?>
                <div><span class="strong-label">ON HOLD - PAYPAL PAYMENT UNDER REVIEW</span></div>
                <?php else:?>
                <div><span class="strong-label">Transaction No. : </span> <?=$boughtTransactionDetails['invoiceNo'] ?></div>
                <div><span class="strong-label">Date : </span> <?=date_format($boughtTransactionDetails['dateadded'], 'jS \of F Y')?></div>
                <?PHP endif; ?>
            </div>
        <?PHP foreach($boughtTransactionDetails['product'] as $productKey => $product) : ?>
            <div class="pd-top-15">
                <div class="col-xs-12 col-sm-9 padding-reset trans-left-panel pd-top-10">
                    <div class="pd-bottom-20">
                        <div class="col-xs-3 col-sm-4 padding-reset">
                            <div class="div-product-image" style="background: url(<?=$product['productImagePath']?>) center center no-repeat; background-cover: cover; background-size: 150%;">
                            </div>
                        </div>
                        <div class="col-xs-9 col-sm-8 padding-reset">
                            <p class="item-list-name">
                                <a class="color-default" target="_blank" href="/item/<?=html_escape($product['slug'])?>">
                                    <?=html_escape($product['name'])?>
                                </a>
                            </p>
                            <p class="item-amount">
                                <span class="item-current-amount">P<?=number_format($product['price'], 2, '.', ',') ?></span>
                            </p>
                            <div class="div-meta-description">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="strong-label">Total : </span> Php <?=number_format(($product['price']*$product['orderQuantity']), 2, '.', ',') ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="strong-label">Status : </span>
                                        <?PHP if (intval($boughtTransactionDetails['isFlag']) === 0 && intval($boughtTransactionDetails['orderStatus']) === 0) : ?>
                                            <?PHP if ($product['isReject']) : ?>
                                                <span class="trans-status-pending">ITEM REJECTED</span>
                                            <?php else:?>
                                                <?PHP if( (int) $product['idOrderProductStatus'] === 0):?>
                                                    <?PHP if( (int) $boughtTransactionDetails['idPaymentMethod'] === 3 ) : ?>
                                                        <span class="trans-status-cod">CASH ON DELIVERY</span>
                                                    <?PHP else : ?>
                                                        <?PHP if( (int) $product['has_shipping_summary'] === 0 ):?>
                                                            <span class="trans-status-pending">PENDING SHIPPING INFO</span>
                                                        <?PHP elseif( (int) $product['has_shipping_summary'] === 1 ):?>
                                                            <span class="trans-status-cod">ITEM ON ROUTE</span>
                                                        <?PHP endif;?>
                                                    <?PHP endif;?>
                                                <?PHP elseif ( (int) $product['idOrderProductStatus'] === 1) : ?>
                                                    Item Received
                                                <?PHP elseif ( (int) $product['idOrderProductStatus'] === 2) : ?>
                                                    <span class="trans-status-pending">Seller canceled order</span>
                                                <?PHP elseif ( (int) $product['idOrderProductStatus'] === 3) : ?>
                                                    <span class="trans-status-cod">CASH ON DELIVERY</span>
                                                <?PHP elseif ( (int) $product['idOrderProductStatus'] === 4) : ?>
                                                    <span class="trans-status-cod">Paid</span>
                                                <?PHP elseif ( (int) $product['idOrderProductStatus'] === 5) : ?>
                                                    <span class="trans-status-pending">Payment Refunded</span>
                                                <?PHP endif;?>
                                            <?PHP endif; ?>
                                        <?PHP else : ?>
                                            <?PHP if ( (int) $boughtTransactionDetails['idPaymentMethod'] === 2) : ?>
                                                <span class="trans-status-pending">CONFIRM DRAGONPAY PAYMENT</span>
                                            <?PHP elseif (intval($boughtTransactionDetails['idPaymentMethod']) === 1 && intval($boughtTransactionDetails['isFlag']) === 1) : ?>
                                                <span class="trans-status-pending">ON HOLD</span>
                                            <?PHP endif; ?>
                                        <?PHP endif; ?>
                                    </div>
                                    <?php if( $product['has_shipping_summary'] == 1 ):?>
                                    <div class="col-xs-6">
                                        <span class="strong-label shipment-detail-button">View shipment detail</span>
                                        <div class="shipping-details">
                                            <div class="shipping-details-wrapper">
                                                <h1>Shipping details</h1>
                                            </div>
                                            <div class="col-xs-12 text-right">
                                                <?=date_format($product['datemodified'], 'jS \of F Y')?>
                                            </div>
                                            <div class="col-xs-12 pd-bttm-10"></div>
                                            <div class="col-xs-12 shipping-details-con">
                                                <div class="col-md-4">
                                                    Shipped by:
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=$product['courier'];?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12 pd-bttm-10"></div>
                                                <div class="col-md-4">
                                                    Tracking Number:
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=$product['trackingNum']?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12 pd-bttm-10"></div>
                                                <div class="col-md-4">
                                                    Delivery Date:
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=date_format($product['deliveryDate'], 'jS \of F Y')?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12 pd-bttm-10"></div>
                                                <div class="col-md-4">
                                                    Expected Date of Arrival:
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=date_format($product['expectedDate'], 'jS \of F Y')?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12">
                                                    <textarea placeholder="Write your comment..." disabled="disabled"><?=html_escape($product['shipping_comment'])?></textarea>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="shipping-border"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-9 col-xs-offset-3 col-sm-8 col-sm-offset-4 padding-reset">
                            <?PHP if (isset($product['attr'])) : ?>
                            <div class="info-main-cont">
                                <div class="toggle-info trans-item-info">
                                    <i class="fa fa-plus-circle"></i>more info
                                </div>
                                <div class="info-attributes">
                                    <div class="row">
                                        <?PHP foreach ($product['attr'] as $attr => $attrValue ) : ?>
                                            <div class="col-xs-5">
                                                <span class="strong-label"><?=$attr?> : </span><?=$attrValue?>
                                            </div>
                                        <?PHP endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?PHP endif; ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 trans-right-panel">
                    <div class="transaction-right-content">
                        <?PHP if ( (int) $productKey === (int) array_shift(array_keys($boughtTransactionDetails['product']))) : ?>
                        <div class="transaction-profile-wrapper">
                            <h4>Bought From:</h4>
                            <div>
                                <span class="transac-item-profile-con">
                                    <img src="/assets/images/products/samsung-p.jpg">
                                </span>
                                <span class="transac-item-consignee-name">
                                    <?=html_escape($product['seller'])?>
                                </span>
                            </div>
                        </div>
                        <div class="trans-btn-wrapper">
                            <?PHP if ( (int) $product['has_shipping_summary'] === 1 && (int) $boughtTransactionDetails['orderStatus'] === 0 && (int) $product['idOrderProductStatus'] === 0 && (int) $boughtTransactionDetails['idPaymentMethod'] !== 3 && (int) $boughtTransactionDetails['isFlag'] === 0 ) : ?>
                                <button class="btn btn-default-1">Item recieved</button>
                                <?php if( (int) $product['isReject'] === 0):?>
                                    <button class="btn btn-default-1">Reject Item</button>
                                    <input type="hidden" name="method" value="reject">
                                <?php else:?>
                                    <button class="btn btn-default-1">Unreject Item</button>
                                    <input type="hidden" name="method" value="unreject">
                                <?php endif;?>
                            <?PHP endif; ?>
                            <?PHP if ( (int) $product['forMemberId'] === 0) : ?>
                            <button class="btn btn-default-1 give-feedback-button">
                                <span class="img-give-feedback"></span>give feedback
                            </button>
                            <div class="give-feedback-modal">
                                <?php
                                $attr = ['class'=>'transac-feedback-form'];
                                echo form_open('',$attr);
                                ?>
                                <input type="hidden" name="feedb_kind" value="0">
                                <input type="hidden" name="order_id" value="<?=$boughtTransactionDetails['idOrder']?>">
                                <input type="hidden" name="for_memberid" value="<?=$boughtTransactionDetails['sellerId']?>">
                                <div class="feedback-content">
                                    <h1>LEAVE A FEEDBACK</h1>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[0].':'?></span>
                                        <div class="feedb-star rating1"></div>
                                    </div>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[1].':'?></span>
                                        <div class="feedb-star rating2"></div>
                                    </div>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[2].':'?></span>
                                        <div class="feedb-star rating3"></div>
                                    </div>
                                    <span class="raty-error"></span>
                                    <div>
                                        <textarea rows="4" cols="50" name="feedback-field" placeholder="Write your message..."></textarea>
                                        <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                    </div>
                                </div>
                                <div class="feedback-btns">
                                    <span class="simplemodal-close btn btn-default-1">Cancel</span>
                                    <span class="btn btn-default-3 feedback-submit">Submit</span>
                                </div>
                                <?php echo form_close();?>
                            </div>
                            <?PHP endif; ?>
                        </div>
                        <?PHP endif; ?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?PHP endforeach; ?>
        </div>
    <?PHP endforeach; ?>
        <div class="text-center">
            <span class="btn btn-loadmore">Load More</span>
        </div>
    </div>
<?PHP else : ?>
    You have not bought any items yet.
<?PHP endif; ?>
</div>
<!---------------------------------------------------------------ongoing sold starts here---------------------------------------------------------------->
<div class="transaction-title-sold mrgn-top-12">
    <span class="trans-title">Sold</span>
    <span class="count"><?=count($transactionInfo['transaction']['ongoing']['sold'])?></span>
</div>
<div class="on-going-transaction-list-sold">
<?PHP if ( (int) count($transactionInfo['transaction']['ongoing']['sold']) >= 1) : ?>
    <div class="mrgn-top-20 mrgn-bttm-25 row">
        <div class="col-md-9">
            <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
            <button class="btn btn-setting-edit-btn">
                <i class="icon-fax"></i> Print
            </button>
            <button class="btn btn-setting-edit-btn">
                <i class="icon-category"></i> Export CSV
            </button>
        </div>
        <div class="col-md-3 text-right">
            <span>Sort By:</span>
            <select class="select-filter-item">
                <option selected=selected>Last Modified</option>
                <option>2</option>
                <option>3</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="transaction-item">
    <?PHP foreach($transactionInfo['transaction']['ongoing']['sold'] as $key => $soldTransactionDetails) : ?>
        <div class="item-list-panel">
            <div class="transac-title">
                <?PHP if (intval($soldTransactionDetails['orderStatus']) != 99 && intval($soldTransactionDetails['isFlag']) === 0 ) : ?>
                    <div><span class="strong-label">Transaction No. : </span> <?=$soldTransactionDetails['invoiceNo'] ?></div>
                    <div><span class="strong-label">Date : </span> <?=date_format($soldTransactionDetails['dateadded'], 'jS \of F Y')?></div>
                <?PHP else : ?>
                    <?php if(intval($soldTransactionDetails['idPaymentMethod']) === 2):?>
                        <div><span class="strong-label">ON HOLD - PENDING DRAGONPAY PAYMENT FROM <?=$soldTransactionDetails['buyer']?></span></div>
                    <?php elseif(intval($soldTransactionDetails['idPaymentMethod']) === 5):?>
                        <div><span class="strong-label">ON HOLD - PENDING BANK DEPOSIT DETAILS FROM <?=$soldTransactionDetails['buyer']?></span></div>
                    <?php elseif(intval($soldTransactionDetails['idPaymentMethod']) === 1 && intval($soldTransactionDetails['isFlag']) === 1) : ?>
                        <div><span class="strong-label">ON HOLD - PAYPAL PAYMENT UNDER REVIEW FROM <?=$soldTransactionDetails['buyer']?></span></div>
                    <?php endif;?>
                <?PHP endif; ?>
            </div>
            <?PHP foreach($soldTransactionDetails['product'] as $productKey => $product) : ?>
            <div class="pd-top-15">
                <div class="col-xs-12 col-sm-9 padding-reset trans-left-panel pd-top-10">
                    <div class="pd-bottom-20">
                        <div class="col-xs-3 col-sm-4 padding-reset">
                            <div class="div-product-image" style="background: url(<?=$product['productImagePath']?>) center center no-repeat; background-cover: cover; background-size: 150%;">
                            </div>
                        </div>
                        <div class="col-xs-9 col-sm-8 padding-reset">
                            <p class="item-list-name">
                                <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                    <?=html_escape($product['name'])?>
                                </a>
                                <span class="trans-circle trans-circle-inc">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </span>
                            </p>
                            <p class="item-amount">
                                <span class="item-current-amount">P<?=number_format($product['price'], 2, '.', ',') ?></span>
                            </p>
                            <div class="div-meta-description">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="strong-label">Total : </span> Php <?=number_format(($product['price']*$product['orderQuantity']), 2, '.', ',') ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="strong-label">Status : </span>
                                        <?PHP if (intval($soldTransactionDetails['orderStatus']) === 0 && intval($soldTransactionDetails['isFlag']) === 0 ) : ?>
                                            <?PHP if (intval($product['isReject']) === 1) : ?>
                                                <span class="trans-status-pending status-class">ITEM REJECTED</span>
                                            <?PHP else : ?>
                                                <?PHP if (intval($product['idOrderProductStatus']) === 0) : ?>
                                                    <?PHP if( intval($soldTransactionDetails['idPaymentMethod']) === 3 ) : ?>
                                                        <span class="trans-status-cod status-class">Cash on Delivery</span>
                                                    <?PHP else:?>
                                                        <?PHP if ( (bool) $product['courier'] > 0 &&  (bool) $product['datemodified'] > 0) : ?>
                                                            <span class="trans-status-cod status-class">Item shipped</span>
                                                        <?PHP elseif (!( (bool) $product['courier'] > 0 &&  (bool) $product['datemodified'] > 0) ) : ?>
                                                            <span class="trans-status-pending status-class">Easyshop received payment.</span>
                                                        <?PHP endif;?>
                                                    <?PHP endif;?>
                                                <?PHP else : ?>
                                                    <span class="trans-status-pending status-class"><?=$soldTransactionDetails['paymentMethod']?></span>
                                                <?PHP endif; ?>
                                            <?PHP endif; ?>
                                        <?PHP else : ?>
                                            <span class="trans-status-pending status-class">ON HOLD</span>
                                        <?PHP endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-9 col-xs-offset-3 col-sm-8 col-sm-offset-4 padding-reset">
                        <?PHP if (isset($product['attr'])) : ?>
                            <div class="info-main-cont">
                                <div class="toggle-info trans-item-info">
                                    <i class="fa fa-plus-circle"></i>more info
                                </div>
                                <div class="info-attributes">
                                    <div class="row">
                            <?PHP foreach ($product['attr'] as $attr => $attrValue ) : ?>
                                        <div class="col-xs-5">
                                            <span class="strong-label"><?=$attr?> : </span><?=$attrValue?>
                                        </div>
                            <?PHP endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?PHP endif; ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 trans-right-panel">
                    <div class="transaction-right-content">
                    <?PHP if ( (int) $productKey === (int) array_shift(array_keys($soldTransactionDetails['product']))) : ?>
                        <?PHP if (intval($soldTransactionDetails['orderStatus']) != 99 && intval($soldTransactionDetails['isFlag']) === 0 ) : ?>
                        <div class="transaction-profile-wrapper">
                            <h4>Sold To:</h4>
                            <div>
                                <span class="transac-item-profile-con">
                                    <img src="/assets/images/products/samsung-p.jpg">
                                </span>
                                <span class="transac-item-consignee-name">
                                    <?=html_escape($soldTransactionDetails['buyer'])?>
                                </span>
                            </div>
                            <div class="pos-rel">
                                <span class="view-delivery-lnk">view delivery details</span>
                                <div class="view-delivery-details">
                                    <div class="col-md-12 pd-tb-8">
                                        <strong>Consignee:</strong>
                                        <span><?=html_escape($soldTransactionDetails['consignee'])?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pd-tb-8">
                                            <strong>Mobile:</strong>
                                            <span><?=$soldTransactionDetails['mobile']?></span>
                                        </div>
                                        <div class="pd-tb-8">
                                            <strong>State/Region:</strong>
                                            <span><?=$soldTransactionDetails['location']?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pd-tb-8">
                                            <strong>Telephone:</strong>
                                            <span><?=$soldTransactionDetails['telephone']?></span>
                                        </div>
                                        <div class="pd-tb-8">
                                            <strong>State/Region:</strong>
                                            <span><?=$soldTransactionDetails['city']?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pd-tb-8">
                                        <strong>Address:</strong>
                                        <span><?=html_escape($soldTransactionDetails['fulladd'])?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?PHP endif; ?>
                        <div class="trans-btn-wrapper">
                        <?PHP if ( (int) $soldTransactionDetails['orderStatus'] === 0 && (int) $product['idOrderProductStatus'] === 0 && (int) $soldTransactionDetails['idPaymentMethod'] != 3  && (int) $soldTransactionDetails['isFlag'] === 0) : ?>
                            <button class="btn btn-default-1 isform shipment-detail-button txt_buttons">Ship Item</button>
                            <div class="shipping-details">
                                <?php
                                $disable = (bool) trim($product['shipping_comment']);
                                $attr = ['class'=>'shipping_details_form'];
                                echo form_open('',$attr);
                                ?>
                                <div class="shipping-details-wrapper">
                                    <h1>Shipping details</h1>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <?=$product['datemodified'] ? date_format($product['datemodified'], 'jS \of F Y') : ''?>
                                </div>
                                <div class="col-xs-12 pd-bttm-10"></div>
                                <div class="col-xs-12 shipping-details-con">
                                    <div class="col-md-4">
                                        Shipped by:
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="ui-form-control" name="courier" value="<?=html_escape($product['courier'])?>" >
                                    </div>
                                    <div class="col-xs-12 pd-bttm-10"></div>
                                    <div class="col-md-4">
                                        Tracking Number:
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="ui-form-control" name="tracking_num" value="<?=html_escape($product['trackingNum']);?>" >
                                    </div>
                                    <div class="col-xs-12 pd-bttm-10"></div>
                                    <div class="col-md-4">
                                        Delivery Date:
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="ui-form-control modal_date" name="delivery_date" value="<?=$product['deliveryDate'] ? date_format($product['deliveryDate'], 'Y-M-d') : '' ?>" >
                                    </div>
                                    <div class="col-xs-12 pd-bttm-10"></div>
                                    <div class="col-md-4">
                                        Expected Date of Arrival:
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="ui-form-control modal_date" name="expected_date" value="<?=$product['expectedDate'] ? date_format($product['expectedDate'], 'Y-M-d') : '' ?>" >
                                    </div>
                                    <div class="col-xs-12">
                                        <textarea name="comment" placeholder="Write your comment..." data-value="<?=html_escape($product['shipping_comment']); ?>" ><?=html_escape($product['shipping_comment'])?></textarea>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="shipping-border"></div>
                                    <div class="shipping-btns">
                                        <div class="col-xs-12 padding-reset">
                                            <input name="order_product" type="hidden" value="<?=$product['idOrderProduct']?>">
                                            <input name="transact_num" type="hidden" value="<?=$soldTransactionDetails['idOrder']?>">
                                            <input class="shipping_comment_submit btn btn-default-3" type="submit" value="<?= $disable ? 'Update':'Save'?>">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <?php echo form_close();?>
                            </div>
                            <div class="shipping-details-container">
                                <input type="hidden" name="courier" value="<?=html_escape($product['courier'])?>">
                                <input type="hidden" name="tracking_num" value="<?=html_escape($product['trackingNum']);?>">
                                <input type="hidden" name="delivery_date" value="<?=$product['deliveryDate'] ? date_format($product['deliveryDate'], 'Y-M-d') : '' ?>" >
                                <input type="hidden" name="expected_date" value="<?=$product['expectedDate'] ? date_format($product['expectedDate'], 'Y-M-d') : '' ?>">
                                <input type="hidden" name="comment" value="<?=html_escape($product['shipping_comment'])?>">
                                <input type="hidden" name="is_new" value="<?= $disable ?>">
                            </div>
                            <?php
                            $attr = ['class' => 'transac_response'];
                            echo form_open('', $attr);
                            ?>
                            <input type="button" value="Cancel Order" class="btn btn-default-1 transac_response_btn tx_return enabled txt_buttons">
                            <input type="hidden" name="seller_response" value="<?=$product['idOrderProduct']?>">
                            <input type="hidden" name="transaction_num" value="<?=$soldTransactionDetails['idOrder']?>">
                            <input type="hidden" name="invoice_num" value="<?=$soldTransactionDetails['invoiceNo']?>">
                            <?php echo form_close();?>
                        <?PHP elseif (intval($soldTransactionDetails['orderStatus']) === 0 && intval($product['idOrderProductStatus']) === 0 && intval($soldTransactionDetails['idPaymentMethod']) === 3) : ?>
                            <?php
                            $attr = ['class' => 'transac_response'];
                            echo form_open('',$attr);
                            ?>
                            <input type="button" value="Completed" class="btn btn-default-3 txt_buttons transac_response_btn tx_cod enabled">
                            <input type="hidden" name="cash_on_delivery" value="<?=$product['idOrderProduct']?>">
                            <input type="hidden" name="transaction_num" value="<?=$soldTransactionDetails['idOrder']?>">
                            <input type="hidden" name="invoice_num" value="<?=$soldTransactionDetails['invoiceNo']?>">
                            <?php echo form_close();?>
                        <?PHP endif; ?>
                            <?PHP if ( (int) $soldTransactionDetails['forMemberId'] === 0 ) : ?>
                            <button class="btn btn-default-1 give-feedback-button">
                                <span class="img-give-feedback"></span>give feedback
                            </button>
                            <div class="give-feedback-modal">
                                <?php
                                $attr = ['class'=>'transac-feedback-form'];
                                echo form_open('',$attr);
                                ?>
                                <input type="hidden" name="feedb_kind" value="1">
                                <input type="hidden" name="order_id" value="<?=$soldTransactionDetails['idOrder']?>">
                                <input type="hidden" name="for_memberid" value="<?=$soldTransactionDetails['buyerId']?>">
                                <div class="feedback-content">
                                    <h1>LEAVE A FEEDBACK</h1>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[0].':'?></span>
                                        <div class="feedb-star rating1"></div>
                                    </div>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[1].':'?></span>
                                        <div class="feedb-star rating2"></div>
                                    </div>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[2].':'?></span>
                                        <div class="feedb-star rating3"></div>
                                    </div>
                                    <span class="raty-error"></span>
                                    <div>
                                        <textarea rows="4" cols="50" name="feedback-field" placeholder="Write your message..."></textarea>
                                        <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                    </div>
                                </div>
                                <div class="feedback-btns">
                                    <span class="simplemodal-close btn btn-default-1">Cancel</span>
                                    <span class="btn btn-default-3 feedback-submit">Submit</span>
                                </div>
                                <?php echo form_close();?>
                            </div>
                            <?PHP endif; ?>
                        </div>
                    <?PHP endif; ?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <?PHP endforeach; ?>
        </div>
    <?PHP endforeach; ?>
        <div class="text-center">
            <span class="btn btn-loadmore">Load More</span>
        </div>
    </div>
<?PHP else : ?>
    You have not sold any items yet.
<?PHP endif; ?>
</div>
</div>
</div>
<!---------------------------------------------------------------completed bought starts here------------------------------------------------------------>
<div class="col-md-12" id="completed-transaction">
<div class="row">
<div class="transaction-title-bought-completed">
    <span class="trans-title">Bought</span>
    <span class="count"><?=count($transactionInfo['transaction']['complete']['bought'])?></span>
</div>
<div class="on-going-transaction-list-bought-completed">
<?PHP if ( (int) count($transactionInfo['transaction']['complete']['bought']) >= 1) : ?>
<div class="mrgn-top-20 mrgn-bttm-25 row">
    <div class="col-md-9">
        <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
        <button class="btn btn-default-3">
            <i class="icon-fax"></i> <span>Print</span>
        </button>
        <button class="btn btn-default-3">
            <i class="icon-category"></i> <span>Export CSV</span>
        </button>
    </div>
    <div class="col-md-3 text-right">
        <span>Sort By:</span>
        <select class="select-filter-item">
            <option selected=selected>Last Modified</option>
            <option>2</option>
            <option>3</option>
        </select>
    </div>
    <div class="clear"></div>
</div>
<div class="transaction-item">
    <?PHP foreach($transactionInfo['transaction']['complete']['bought'] as $key => $boughtTransactionDetails) : ?>
    <div class="item-list-panel">
        <div class="transac-title">
            <div><span class="strong-label">Transaction No. : </span> <?=$boughtTransactionDetails['invoiceNo'] ?></div>
            <div><span class="strong-label">Date : </span> <?=date_format($boughtTransactionDetails['dateadded'], 'jS \of F Y')?></div>
        </div>
        <?PHP foreach($boughtTransactionDetails['product'] as $productKey => $product) : ?>
        <div class="pd-top-15">
            <div class="col-xs-12 col-sm-9 padding-reset trans-left-panel pd-top-10">
                <div class="pd-bottom-20">
                    <div class="col-xs-3 col-sm-4 padding-reset">
                        <div class="div-product-image" style="background: url(<?=$product['productImagePath']?>) center center no-repeat; background-cover: cover; background-size: 150%;">
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-8 padding-reset">
                        <p class="item-list-name">
                            <a class="color-default" target="_blank" href="/item/<?=html_escape($product['slug'])?>">
                                <?=html_escape($product['name'])?>
                            </a>
                        </p>
                        <p class="item-amount">
                            <span class="item-current-amount">P<?=number_format($product['price'], 2, '.', ',') ?></span>
                        </p>
                        <div class="div-meta-description">
                            <div class="row">
                                <div class="col-xs-6">
                                    <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                </div>
                                <div class="col-xs-6">
                                    <span class="strong-label">Total : </span> Php <?=number_format(($product['price']*$product['orderQuantity']), 2, '.', ',') ?>
                                </div>
                                <div class="col-xs-6">
                                    <span class="strong-label">Status : </span>
                                    <?PHP if ( (int) $product['idOrderProductStatus'] === 1) : ?>
                                        <span class="trans-status-cod status-class">Item Delivered</span>
                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === 2):?>
                                        <span class="trans-status-pending status-class">Order Canceled</span>
                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === 3):?>
                                        <span class="trans-status-cod status-class">Cash on delivery</span>
                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === 4):?>
                                        <span class="trans-status-cod status-class">Payment Received</span>
                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === 5):?>
                                        <span class="trans-status-pending status-class">Payment Returned</span>
                                    <?PHP endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-9 col-xs-offset-3 col-sm-8 col-sm-offset-4 padding-reset">
                        <?PHP if (isset($product['attr'])) : ?>
                        <div class="info-main-cont">
                            <div class="toggle-info trans-item-info">
                                <i class="fa fa-plus-circle"></i>more info
                            </div>
                            <div class="info-attributes">
                                <div class="row">
                                    <?PHP foreach ($product['attr'] as $attr => $attrValue ) : ?>
                                        <div class="col-xs-5">
                                            <span class="strong-label"><?=$attr?> : </span><?=$attrValue?>
                                        </div>
                                    <?PHP endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?PHP endif; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 trans-right-panel">
                <div class="transaction-right-content">
                    <?PHP if ( (int) $productKey === (int) array_shift(array_keys($boughtTransactionDetails['product']))) : ?>
                    <div class="transaction-profile-wrapper">
                        <h4>Bought From:</h4>
                        <div>
                            <span class="transac-item-profile-con">
                                <img src="/assets/images/products/samsung-p.jpg">
                            </span>
                            <span class="transac-item-consignee-name">
                                    <?=html_escape($product['seller'])?>
                            </span>
                        </div>
                    </div>
                    <div class="trans-btn-wrapper">
                        <?PHP if ( (int) $product['forMemberId'] === 0) : ?>
                            <button class="btn btn-default-1 give-feedback-button">
                                <span class="img-give-feedback"></span>give feedback
                            </button>
                            <div class="give-feedback-modal">
                                <?php
                                $attr = ['class'=>'transac-feedback-form'];
                                echo form_open('',$attr);
                                ?>
                                <input type="hidden" name="feedb_kind" value="0">
                                <input type="hidden" name="order_id" value="<?=$boughtTransactionDetails['idOrder']?>">
                                <input type="hidden" name="for_memberid" value="<?=$boughtTransactionDetails['sellerId']?>">
                                <div class="feedback-content">
                                    <h1>LEAVE A FEEDBACK</h1>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[0].':'?></span>
                                        <div class="feedb-star rating1"></div>
                                    </div>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[1].':'?></span>
                                        <div class="feedb-star rating2"></div>
                                    </div>
                                    <div class="star-rating-wrapper">
                                        <span class="star-label"><?=$this->lang->line('rating')[2].':'?></span>
                                        <div class="feedb-star rating3"></div>
                                    </div>
                                    <span class="raty-error"></span>
                                    <div>
                                        <textarea rows="4" cols="50" name="feedback-field" placeholder="Write your message..."></textarea>
                                        <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                    </div>
                                </div>
                                <div class="feedback-btns">
                                    <span class="simplemodal-close btn btn-default-1">Cancel</span>
                                    <span class="btn btn-default-3 feedback-submit">Submit</span>
                                </div>
                                <?php echo form_close();?>
                            </div>
                        <?PHP endif; ?>
                    </div>
                    <?PHP endif; ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <?PHP endforeach; ?>
    </div>
    <?PHP endforeach; ?>
<div class="text-center">
    <span class="btn btn-loadmore">Load More</span>
</div>
</div>
<?PHP else : ?>
    There are no transactions for this category.
<?PHP endif; ?>
</div>
<!---------------------------------------------------------------completed sold starts here---------------------------------------------------------------->
<div class="transaction-title-sold-completed mrgn-top-12">
    <span class="trans-title">Sold</span>
    <span class="count"><?=count($transactionInfo['transaction']['complete']['sold'])?></span>
</div>
<div class="on-going-transaction-list-sold-completed">
<?PHP if ( (int) count($transactionInfo['transaction']['complete']['sold']) >= 1) : ?>
    <div class="mrgn-top-20 mrgn-bttm-25 row">
        <div class="col-md-9">
            <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
            <button class="btn btn-default-3">
                <i class="icon-fax"></i> <span>Print</span>
            </button>
            <button class="btn btn-default-3">
                <i class="icon-category"></i> <span>Export CSV</span>
            </button>
        </div>
        <div class="col-md-3 text-right">
            <span>Sort By:</span>
            <select class="select-filter-item">
                <option selected=selected>Last Modified</option>
                <option>2</option>
                <option>3</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="transaction-item">
    <?PHP foreach($transactionInfo['transaction']['complete']['sold'] as $key => $soldTransactionDetails) : ?>
        <div class="item-list-panel">
            <div class="transac-title">
                <div><span class="strong-label">Transaction No. : </span> <?=$soldTransactionDetails['invoiceNo'] ?></div>
                <div><span class="strong-label">Date : </span> <?=date_format($soldTransactionDetails['dateadded'], 'jS \of F Y')?></div>
            </div>
        <?PHP foreach($soldTransactionDetails['product'] as $productKey => $product) : ?>
            <div class="pd-top-15">
                <div class="col-xs-12 col-sm-9 padding-reset trans-left-panel pd-top-10">
                    <div class="pd-bottom-20">
                        <div class="col-xs-3 col-sm-4 padding-reset">
                            <div class="div-product-image" style="background: url(<?=$product['productImagePath']?>) center center no-repeat; background-cover: cover; background-size: 150%;">
                            </div>
                        </div>
                        <div class="col-xs-9 col-sm-8 padding-reset">
                            <p class="item-list-name">
                                <a class="color-default" target="_blank" href="/item/<?=html_escape($product['slug'])?>">
                                    <?=html_escape($product['name'])?>
                                </a>
                            </p>
                            <p class="item-amount">
                                <span class="item-current-amount">P<?=number_format($product['price'], 2, '.', ',') ?></span>
                            </p>
                            <div class="div-meta-description">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="strong-label">Total : </span> Php <?=number_format(($product['price']*$product['orderQuantity']), 2, '.', ',') ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="strong-label">Status : </span>
                                        <?PHP if ( (int) $product['idOrderProductStatus'] === 1) : ?>
                                            <span class="trans-status-cod status-class">Item Delivered</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === 2):?>
                                            <span class="trans-status-pending status-class">Order Canceled</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === 3):?>
                                            <span class="trans-status-cod status-class">Cash on delivery</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === 4):?>
                                            <span class="trans-status-cod status-class">Payment Received</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === 5):?>
                                            <span class="trans-status-pending status-class">Payment Returned</span>
                                        <?PHP endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-9 col-xs-offset-3 col-sm-8 col-sm-offset-4 padding-reset">
                            <div class="info-main-cont">
                                <div class="toggle-info trans-item-info">
                                    <i class="fa fa-plus-circle"></i>more info
                                </div>
                                <div class="info-attributes">
                                    <div class="row">
                                        <?PHP foreach ($product['attr'] as $attr => $attrValue ) : ?>
                                            <div class="col-xs-5">
                                                <span class="strong-label"><?=$attr?> : </span><?=$attrValue?>
                                            </div>
                                        <?PHP endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 trans-right-panel">
                    <div class="transaction-right-content">
                        <div class="transaction-profile-wrapper">
                            <h4>Sold To:</h4>
                            <div>
                                <span class="transac-item-profile-con">
                                    <img src="/assets/images/products/samsung-p.jpg">
                                </span>
                                <span class="transac-item-consignee-name">
                                    <?=html_escape($soldTransactionDetails['buyer'])?>
                                </span>
                            </div>
                            <div class="pos-rel">
                                <span class="view-delivery-lnk">view delivery details</span>
                                <div class="view-delivery-details">
                                    <div class="col-md-12 pd-tb-8">
                                        <strong>Consignee:</strong>
                                        <span><?=html_escape($soldTransactionDetails['consignee'])?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pd-tb-8">
                                            <strong>Mobile:</strong>
                                            <span><?=$soldTransactionDetails['mobile']?></span>
                                        </div>
                                        <div class="pd-tb-8">
                                            <strong>State/Region:</strong>
                                            <span><?=$soldTransactionDetails['location']?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pd-tb-8">
                                            <strong>Telephone:</strong>
                                            <span><?=$soldTransactionDetails['telephone']?></span>
                                        </div>
                                        <div class="pd-tb-8">
                                            <strong>State/Region:</strong>
                                            <span><?=$soldTransactionDetails['city']?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pd-tb-8">
                                        <strong>Address:</strong>
                                        <span><?=html_escape($soldTransactionDetails['fulladd'])?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="trans-btn-wrapper">
                            <?PHP if ( (int) $soldTransactionDetails['forMemberId'] === 0 ) : ?>
                                <button class="btn btn-default-1 give-feedback-button">
                                    <span class="img-give-feedback"></span>give feedback
                                </button>
                                <div class="give-feedback-modal">
                                    <?php
                                    $attr = ['class'=>'transac-feedback-form'];
                                    echo form_open('',$attr);
                                    ?>
                                    <input type="hidden" name="feedb_kind" value="1">
                                    <input type="hidden" name="order_id" value="<?=$soldTransactionDetails['idOrder']?>">
                                    <input type="hidden" name="for_memberid" value="<?=$soldTransactionDetails['buyerId']?>">
                                    <div class="feedback-content">
                                        <h1>LEAVE A FEEDBACK</h1>
                                        <div class="star-rating-wrapper">
                                            <span class="star-label"><?=$this->lang->line('rating')[0].':'?></span>
                                            <div class="feedb-star rating1"></div>
                                        </div>
                                        <div class="star-rating-wrapper">
                                            <span class="star-label"><?=$this->lang->line('rating')[1].':'?></span>
                                            <div class="feedb-star rating2"></div>
                                        </div>
                                        <div class="star-rating-wrapper">
                                            <span class="star-label"><?=$this->lang->line('rating')[2].':'?></span>
                                            <div class="feedb-star rating3"></div>
                                        </div>
                                        <span class="raty-error"></span>
                                        <div>
                                            <textarea rows="4" cols="50" name="feedback-field" placeholder="Write your message..."></textarea>
                                            <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                        </div>
                                    </div>
                                    <div class="feedback-btns">
                                        <span class="simplemodal-close btn btn-default-1">Cancel</span>
                                        <span class="btn btn-default-3 feedback-submit">Submit</span>
                                    </div>
                                    <?php echo form_close();?>
                                </div>
                            <?PHP endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?PHP endforeach; ?>
        </div>
    <?PHP endforeach; ?>
        <div class="text-center">
            <span class="btn btn-loadmore">Load More</span>
        </div>
    </div>
<?PHP else : ?>
    There are no transactions for this category.
<?PHP endif; ?>
</div>

</div>
</div>
</div>
</div>

<div class="clear"></div>

<!---------------------------------------------------------------feedback modal starts here---------------------------------------------------------------->
<div id="feedback-modal">
    <div class="feedback-content">
        <h1>LEAVE A FEEDBACK</h1>
        <div class="star-rating-wrapper">
            <span class="star-label">Item quality:</span>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat"></i>
        </div>
        <div class="star-rating-wrapper">
            <span class="star-label">Communication: </span>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat"></i>
        </div>
        <div class="star-rating-wrapper">
            <span class="star-label">Shipment time:  </span>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat star-active"></i>
            <i class="icon-star star-stat"></i>
        </div>
        <div>
            <textarea rows="4" cols="50" name="feedback-field" placeholder="Write your message..."></textarea>
        </div>
    </div>
    <div class="feedback-btns">
        <span class="simplemodal-close btn btn-default-1">Cancel</span>
        <button class="btn btn-default-3">Submit</button>
    </div>
</div>

<!---------------------------------------------------------------ship item modal starts here---------------------------------------------------------------->
<!--<div id="shipping-details">-->
<!--    <div class="shipping-details-wrapper">-->
<!--        <h1>Shipping details</h1>-->
<!--    </div>-->
<!--    <div class="col-xs-12 text-right">-->
<!--        20th November 2014-->
<!--    </div>-->
<!--    <div class="col-xs-12 pd-bttm-10"></div>-->
<!--    <div class="col-xs-12 shipping-details-con">-->
<!--        <div class="col-md-4">-->
<!--            Shipped by:-->
<!--        </div>-->
<!--        <div class="col-md-8">-->
<!--            <input type="text" class="ui-form-control">-->
<!--        </div>-->
<!--        <div class="col-xs-12 pd-bttm-10"></div>-->
<!--        <div class="col-md-4">-->
<!--            Tracking Number:-->
<!--        </div>-->
<!--        <div class="col-md-8">-->
<!--            <input type="text" class="ui-form-control">-->
<!--        </div>-->
<!--        <div class="col-xs-12 pd-bttm-10"></div>-->
<!--        <div class="col-md-4">-->
<!--            Delivery Date:-->
<!--        </div>-->
<!--        <div class="col-md-8">-->
<!--            <input type="text" class="ui-form-control">-->
<!--        </div>-->
<!--        <div class="col-xs-12 pd-bttm-10"></div>-->
<!--        <div class="col-md-4">-->
<!--            Expected Date of Arrival:-->
<!--        </div>-->
<!--        <div class="col-md-8">-->
<!--            <input type="text" class="ui-form-control">-->
<!--        </div>-->
<!--        <div class="col-xs-12">-->
<!--            <textarea placeholder="Write your comment..."></textarea>-->
<!--        </div>-->
<!--        <div class="clear"></div>-->
<!--        <div class="shipping-border"></div>-->
<!--        <div class="shipping-btns">-->
<!--            <div class="col-xs-12 padding-reset">-->
<!--                <span class="simplemodal-close btn btn-default-1">Edit</span>-->
<!--                <button class="btn btn-default-3">Save</button>-->
<!--            </div>-->
<!--            <div class="clear"></div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="clear"></div>-->
<!--</div>-->



