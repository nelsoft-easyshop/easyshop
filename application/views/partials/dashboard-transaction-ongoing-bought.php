<div class="transaction-item">
<?PHP foreach($transaction as $key => $boughtTransactionDetails) : ?>
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
                                        <?PHP if( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::ON_GOING):?>
                                            <?PHP if( (int) $boughtTransactionDetails['idPaymentMethod'] === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY ) : ?>
                                                <span class="trans-status-cod">CASH ON DELIVERY</span>
                                            <?PHP else : ?>
                                                <?PHP if( (int) $product['has_shipping_summary'] === 0 ):?>
                                                    <span class="trans-status-pending">PENDING SHIPPING INFO</span>
                                                <?PHP elseif( (int) $product['has_shipping_summary'] === 1 ):?>
                                                    <span class="trans-status-cod">ITEM ON ROUTE</span>
                                                <?PHP endif;?>
                                            <?PHP endif;?>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER) : ?>
                                            Item Received
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::RETURNED_BUYER) : ?>
                                            <span class="trans-status-pending">Seller canceled order</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::CASH_ON_DELIVERY) : ?>
                                            <span class="trans-status-cod">CASH ON DELIVERY</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::PAID_FORWARDED) : ?>
                                            <span class="trans-status-cod">Paid</span>
                                        <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::PAID_RETURNED) : ?>
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
                                <img src="<?=$boughtTransactionDetails['userImage']?>">
                            </span>
                            <span class="transac-item-consignee-name">
                                <?=html_escape($product['seller'])?>
                            </span>
                        </div>
                    </div>
                    <div class="trans-btn-wrapper trans-1btn">
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
    <center>
        <?=$pagination; ?>
    </center>
</div>
</div>