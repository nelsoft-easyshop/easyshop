<div class="transaction-item">
<?PHP if (count($transaction) !== 0) : ?>
    <?PHP foreach($transaction as $key => $soldTransactionDetails) : ?>
    <div class="item-list-panel <?='invoiceno-' . $soldTransactionDetails['invoiceNo'] ?>">
        <div class="transac-title">
            <?PHP if (intval($soldTransactionDetails['orderStatus']) != (int) \EasyShop\Entities\EsOrderStatus::STATUS_DRAFT && !$soldTransactionDetails['isFlag'] ) : ?>
                <div><span class="strong-label">Transaction No. : </span> <?=$soldTransactionDetails['invoiceNo'] ?></div>
                <div><span class="strong-label">Date : </span> <?=date_format($soldTransactionDetails['dateadded'], 'jS \of F Y')?></div>
                <div><span class="strong-label">Total : Php </span> <?=number_format($soldTransactionDetails['transactionTotal'], 2, '.', ',') ?></div>
            <?PHP else : ?>               
                <?php if(intval($soldTransactionDetails['idPaymentMethod']) === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY):?>
                    <div><strong>ON HOLD -</strong> PENDING DRAGONPAY PAYMENT FROM <?=html_escape($soldTransactionDetails['buyerStoreName'])?></div>
                <?php elseif(intval($soldTransactionDetails['idPaymentMethod']) === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT):?>
                    <div><strong>ON HOLD -</strong> PENDING BANK DEPOSIT DETAILS FROM <?=html_escape($soldTransactionDetails['buyerStoreName'])?></div>
                <?php elseif(intval($soldTransactionDetails['idPaymentMethod']) === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL && intval($soldTransactionDetails['isFlag']) === 1) : ?>
                    <div><strong>ON HOLD -</strong> PAYPAL PAYMENT UNDER REVIEW FROM <?=html_escape($soldTransactionDetails['buyerStoreName'])?></div>
                <?php elseif(intval($soldTransactionDetails['idPaymentMethod']) === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC) : ?>
                    <div><strong>ON HOLD -</strong> PESOPAY PAYMENT UNDER REVIEW FROM <?=html_escape($soldTransactionDetails['buyerStoreName'])?></div>
                <?php endif;?>
            <?PHP endif; ?>
        </div>
        <?PHP foreach($soldTransactionDetails['product'] as $productKey => $product) : ?>
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
                                <?PHP if (count($soldTransactionDetails['product']) > 1) : ?>
                                    <?PHP if ( (int) $soldTransactionDetails['orderStatus'] === (int) \EasyShop\Entities\EsOrderStatus::STATUS_PAID && (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::ON_GOING && (int) $soldTransactionDetails['idPaymentMethod'] === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY) : ?>
                                <input type="checkbox" id="orderProduct_<?=$product['idOrderProduct']?>" class="css-checkbox order-checkbox" value="<?=$product['idOrderProduct']?>">
                                <label for="orderProduct_<?=$product['idOrderProduct']?>" class="css-label"></label>
                                    <?PHP endif; ?>
                                <?PHP endif; ?>
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
                                        <span class="strong-label">Total : </span> Php <?=number_format($product['price'], 2, '.', ',') ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <span class="strong-label">Status : </span>
                                        <?PHP if (intval($soldTransactionDetails['orderStatus']) === (int) \EasyShop\Entities\EsOrderStatus::STATUS_PAID && intval($soldTransactionDetails['isFlag']) === 0 ) : ?>
                                            <?PHP if (intval($product['isReject']) === 1) : ?>
                                                <span class="trans-status-pending status-class">ITEM REJECTED</span>
                                            <?PHP else : ?>
                                                <?PHP $orderProductStatus = intval($product['idOrderProductStatus']); ?>
                                                <?PHP if ($orderProductStatus === (int) \EasyShop\Entities\EsOrderProductStatus::ON_GOING) : ?>
                                                    <?PHP if( intval($soldTransactionDetails['idPaymentMethod']) === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY ) : ?>
                                                        <span class="trans-status-cod status-class">Cash on Delivery</span>
                                                    <?PHP else:?>
                                                        <?PHP if ( (bool) $product['courier'] > 0 &&  (bool) $product['datemodified'] > 0) : ?>
                                                            <span class="trans-status-cod status-class">Item shipped</span>
                                                        <?PHP elseif (!( (bool) $product['courier'] > 0 &&  (bool) $product['datemodified'] > 0) ) : ?>
                                                            <span class="trans-status-pending status-class">Easyshop received payment.</span>
                                                        <?PHP endif;?>
                                                    <?PHP endif;?>
                                                <?PHP elseif ($orderProductStatus === (int)\EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER): ?>
                                                    <span class="trans-status-pending status-class">Item Received</span>
                                                <?PHP elseif ($orderProductStatus === (int)\EasyShop\Entities\EsOrderProductStatus::RETURNED_BUYER): ?>
                                                    <span class="trans-status-pending status-class">Order Canceled</span>
                                                <?PHP elseif ($orderProductStatus === (int)\EasyShop\Entities\EsOrderProductStatus::CASH_ON_DELIVERY): ?>
                                                    <span class="trans-status-pending status-class">Cash on Delivery</span>
                                                <?PHP elseif ($orderProductStatus === (int)\EasyShop\Entities\EsOrderProductStatus::PAID_FORWARDED): ?>
                                                    <span class="trans-status-pending status-class">Paid</span>
                                                <?PHP elseif ($orderProductStatus === (int)\EasyShop\Entities\EsOrderProductStatus::PAID_RETURNED): ?>
                                                    <span class="trans-status-pending status-class">Payment Refunded</span>
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
                        <?PHP if ( (int) $soldTransactionDetails['orderStatus'] !== (int) \EasyShop\Entities\EsOrderStatus::STATUS_DRAFT && (int) !$soldTransactionDetails['isFlag'] ) : ?>
                        <div class="transaction-profile-wrapper">
                            <h4>Sold To:</h4>
                            <div>
                                <span class="transac-item-profile-con">
                                    <img src="<?php echo getAssetsDomain().'.'.$soldTransactionDetails['userImage'];?>">
                                </span>
                                <span class="transac-item-consignee-name">
                                    <?=html_escape($soldTransactionDetails['buyerStoreName'])?>
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
                                            <span><?=html_escape($soldTransactionDetails['location'])?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pd-tb-8">
                                            <strong>Telephone:</strong>
                                            <span><?=html_escape($soldTransactionDetails['telephone'])?></span>
                                        </div>
                                        <div class="pd-tb-8">
                                            <strong>State/Region:</strong>
                                            <span><?=html_escape($soldTransactionDetails['city'])?></span>
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
                        <?PHP if ( (int) $soldTransactionDetails['orderStatus'] === 0 && (int) $product['idOrderProductStatus'] === 0 && (int) $soldTransactionDetails['idPaymentMethod'] != 3  && !$soldTransactionDetails['isFlag']) : ?>
                        <div class="trans-btn-wrapper trans-btn-con1">
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
                                        <div class="col-sm-4 col-md-4">
                                            Shipped by:
                                        </div>
                                        <div class="col-sm-8 col-md-8">
                                            <input type="text" class="ui-form-control" name="courier" value="<?=html_escape($product['courier'])?>" >
                                        </div>
                                        <div class="col-xs-12 pd-bttm-10"></div>
                                        <div class="col-sm-4 col-md-4">
                                            Tracking Number:
                                        </div>
                                        <div class="col-sm-8 col-md-8">
                                            <input type="text" class="ui-form-control" name="tracking_num" value="<?=html_escape($product['trackingNum']);?>" >
                                        </div>
                                        <div class="col-xs-12 pd-bttm-10"></div>
                                        <div class="col-sm-4 col-md-4">
                                            Delivery Date:
                                        </div>
                                        <div class="col-sm-8 col-md-8">
                                            <input type="text" class="ui-form-control modal_date dp-delivery-date" name="delivery_date" value="<?=$product['deliveryDate'] ? date_format($product['deliveryDate'], 'Y-M-d') : '' ?>" >
                                        </div>
                                        <div class="col-xs-12 pd-bttm-10"></div>
                                        <div class="col-sm-4 col-md-4">
                                            Expected Date of Arrival:
                                        </div>
                                        <div class="col-sm-8 col-md-8">
                                            <input type="text" class="ui-form-control modal_date dp-expected-date" name="expected_date" value="<?=$product['expectedDate'] ? date_format($product['expectedDate'], 'Y-M-d') : '' ?>" >
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
                        </div>
                        <?PHP elseif ( (int) $soldTransactionDetails['orderStatus'] === (int) \EasyShop\Entities\EsOrderStatus::STATUS_PAID && (int) $soldTransactionDetails['idPaymentMethod'] === (int) \EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY) : ?>
                        <div class="trans-btn-wrapper trans-btn-con2">
                                <?php
                                $attr = ['class' => 'transac_response'];
                                echo form_open('',$attr);
                                ?>
                                <input type="button" value="Completed" class="btn btn-default-3 txt_buttons transac_response_btn tx_cod enabled" <?=count($soldTransactionDetails['product']) > 1 ? 'disabled="disabled"' : '' ?>>
                                <input type="hidden" name="cash_on_delivery" value="<?=$product['idOrderProduct']?>">
                                <input type="hidden" name="transaction_num" value="<?=$soldTransactionDetails['idOrder']?>">
                                <input type="hidden" name="invoice_num" value="<?=$soldTransactionDetails['invoiceNo']?>">
                                <?php echo form_close();?>
                         </div>
                        <?PHP endif; ?>
                        <?PHP if ( (int) $soldTransactionDetails['forMemberId'] === 0 ) : ?>
                            <div class="trans-btn-wrapper">
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
                            </div>
                        <?PHP endif; ?>
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
