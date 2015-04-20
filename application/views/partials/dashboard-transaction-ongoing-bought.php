<div class="transaction-item">
<?PHP if (count($transaction) !== 0) : ?>
    <?PHP foreach($transaction as $key => $boughtTransactionDetails) : ?>
    <div class="item-list-panel <?='invoiceno-' . $boughtTransactionDetails['invoiceNo'] ?>">
        <div class="transac-title">
        <?php if ( (int) $boughtTransactionDetails['idPaymentMethod'] === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL && (int) $boughtTransactionDetails['isFlag'] === 1) : ?>
            <div><span class="strong-label">ON HOLD - PAYPAL PAYMENT UNDER REVIEW</span></div>
        <?php else:?>
            <div><span class="strong-label">Transaction No. : </span> <?=$boughtTransactionDetails['invoiceNo'] ?></div>
            <div><span class="strong-label">Date : </span> <?=date_format($boughtTransactionDetails['dateadded'], 'jS \of F Y')?></div>
            <div><span class="strong-label">Total : Php </span> <?=number_format($boughtTransactionDetails['transactionTotal'], 2, '.', ',') ?></div>
        <?PHP endif; ?>
    </div>
        <?PHP foreach($boughtTransactionDetails['product'] as $productKey => $product) : ?>
        <div class="pd-top-15 item-list-products">
            <div class="col-xs-12 col-sm-9 padding-reset trans-left-panel pd-top-10">
                <div class="pd-bottom-20">
                    <div class="col-xs-3 col-sm-4 padding-reset">
                        <div class="div-product-image" >
                            <img src="<?php echo getAssetsDomain().$product['productImagePath']?>" class="image-primary">
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-8 padding-reset">
                        <p class="item-list-name">
                            <a class="color-default" target="_blank" href="/item/<?=html_escape($product['slug'])?>">
                                <?=html_escape($product['name'])?>
                            </a>
                        </p>
                        <p class="item-amount">
                            <span class="item-current-amount">P<?=number_format($product['item_price'], 2, '.', ',') ?></span>
                        </p>
                        <div class="div-meta-description">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <span class="strong-label">Shipping fee : </span> Php <?=number_format($product['handling_fee'], 2, '.', ',') ?>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <span class="strong-label">Easypoint : </span> 1,299.00
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <span class="strong-label">Total : </span> Php <?=number_format($product['price'], 2, '.', ',') ?>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <span class="strong-label">Status : </span>
                                    <?PHP if (intval($boughtTransactionDetails['isFlag']) === 0 && intval($boughtTransactionDetails['orderStatus']) === 0) : ?>
                                        <?PHP if ($product['isReject']) : ?>
                                            <span class="trans-status-pending status-class">ITEM REJECTED</span>
                                        <?php else:?>
                                            <?PHP if( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::ON_GOING):?>
                                                <?PHP if( (int) $boughtTransactionDetails['idPaymentMethod'] === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY ) : ?>
                                                    <span class="trans-status-cod status-class">CASH ON DELIVERY</span>
                                                <?PHP else : ?>
                                                    <?PHP if( (int) $product['has_shipping_summary'] === 0 ):?>
                                                        <span class="trans-status-pending status-class">PENDING SHIPPING INFO</span>
                                                    <?PHP elseif( (int) $product['has_shipping_summary'] === 1 ):?>
                                                        <span class="trans-status-cod status-class">ITEM ON ROUTE</span>
                                                    <?PHP endif;?>
                                                <?PHP endif;?>
                                            <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER) : ?>
                                                Item Received
                                            <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::RETURNED_BUYER) : ?>
                                                <span class="trans-status-pending status-class">Seller canceled order</span>
                                            <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::CASH_ON_DELIVERY) : ?>
                                                <span class="trans-status-cod status-class">CASH ON DELIVERY</span>
                                            <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::PAID_FORWARDED) : ?>
                                                <span class="trans-status-cod status-class">Paid</span>
                                            <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::PAID_RETURNED) : ?>
                                                <span class="trans-status-pending status-class">Payment Refunded</span>
                                            <?PHP endif;?>
                                        <?PHP endif; ?>
                                    <?PHP else : ?>
                                        <?PHP if ( (int) $boughtTransactionDetails['idPaymentMethod'] === \EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY) : ?>
                                            <span class="trans-status-pending status-class">CONFIRM DRAGONPAY PAYMENT</span>
                                        <?PHP elseif (intval($boughtTransactionDetails['idPaymentMethod']) === \EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC): ?>
                                            <span class="trans-status-pending status-class">PESOPAY - PENDING</span>
                                        <?PHP elseif (intval($boughtTransactionDetails['idPaymentMethod']) === \EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL && intval($boughtTransactionDetails['isFlag']) === 1) : ?>
                                            <span class="trans-status-pending status-class">ON HOLD</span>
                                        <?PHP endif; ?>
                                    <?PHP endif; ?>
                                </div>
                                <?php if( $product['has_shipping_summary'] == 1 ):?>
                                    <div class="col-xs-12">
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
                                                <div class="col-sm-4 col-md-4">
                                                    Shipped by:
                                                </div>
                                                <div class="col-sm-8 col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=$product['courier'];?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12 pd-bttm-10"></div>
                                                <div class="col-sm-4 col-md-4">
                                                    Tracking Number:
                                                </div>
                                                <div class="col-sm-8 col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=$product['trackingNum']?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12 pd-bttm-10"></div>
                                                <div class="col-sm-4 col-md-4">
                                                    Delivery Date:
                                                </div>
                                                <div class="col-sm-8 col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=date_format($product['deliveryDate'], 'Y - m - d')?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12 pd-bttm-10"></div>
                                                <div class="col-sm-4 col-md-4">
                                                    Expected Date of Arrival:
                                                </div>
                                                <div class="col-sm-8 col-md-8">
                                                    <input type="text" class="ui-form-control" value="<?=$product['expectedDate'] ? date_format($product['expectedDate'], 'Y - m - d') : ''?>" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12">
                                                    <textarea disabled="disabled"><?=html_escape($product['shipping_comment'])?></textarea>
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
                                    <img src="<?php echo getAssetsDomain().'.'.$boughtTransactionDetails['userImage']?>">
                                </span>
                                <span class="transac-item-consignee-name">
                                    <?=html_escape($product['sellerStoreName'])?>
                                </span>
                            </div>
                        </div>
                        <div class="trans-btn-wrapper trans-1btn">
                            <?PHP if ( (int) $product['has_shipping_summary'] === 1 && (int) $boughtTransactionDetails['orderStatus'] === 0 && (int) $product['idOrderProductStatus'] === 0 && (int) $boughtTransactionDetails['idPaymentMethod'] !== 3 && (int) $boughtTransactionDetails['isFlag'] === 0 ) : ?>
                                <?php
                                $attr = ['class'=>'transac_response'];
                                echo form_open('',$attr);
                                ?>
                                <input type="button" value="Item received" class="btn btn-default-1 transac_response_btn tx_forward transac_orange_btn enabled">
                                <input type="hidden" name="buyer_response" value="<?=$product['idOrderProduct']?>">
                                <input type="hidden" name="transaction_num" value="<?=$boughtTransactionDetails['idOrder']?>">
                                <input type="hidden" name="invoice_num" value="<?=$boughtTransactionDetails['invoiceNo']?>">
                                <?php echo form_close();?>

                                <?php
                                $attr = ['class'=>'rejectForm'];
                                echo form_open('', $attr);?>
                                <?php if( (int) $product['isReject'] === 0):?>
                                    <input type="button" value="Reject Item" class="btn btn-default-1 reject_btn reject_item reject">
                                    <input type="hidden" name="method" value="reject">
                                <?php else:?>
                                    <input type="button" value="Unreject Item" class="btn btn-default-1 reject_btn reject_item unreject">
                                    <input type="hidden" name="method" value="unreject">
                                <?php endif;?>
                                    <input type="hidden" name="order_product" value="<?=$product['idOrderProduct']?>">
                                    <input type="hidden" name="transact_num" value="<?=$boughtTransactionDetails['idOrder']?>">
                                    <input type="hidden" name="seller_id" value="<?=$boughtTransactionDetails['sellerId']?>">
                                <?php echo form_close();?>
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
                                            <textarea rows="4" cols="50" maxlength="1024" name="feedback-field" placeholder="Write your message..."></textarea>
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
            <input class="order-product-ids" type="hidden" value="<?=$product['idOrderProduct']?>">
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
<?PHP else : ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>
        There are no transactions for this category.
    </div>
<?PHP endif; ?>
</div>
