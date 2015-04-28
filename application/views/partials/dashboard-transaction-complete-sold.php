<div class="transaction-item">
    <?PHP if (count($transaction) !== 0) : ?>
            <?PHP foreach($transaction as $key => $soldTransactionDetails) : ?>
                <div class="item-list-panel">
                    <div class="transac-title">
                        <div><span class="strong-label">Transaction No. : </span> <?=$soldTransactionDetails['invoiceNo'] ?></div>
                        <div><span class="strong-label">Date : </span> <?=date_format($soldTransactionDetails['dateadded'], 'jS \of F Y')?></div>
                        <div><span class="strong-label">Total : Php </span> <?=number_format($soldTransactionDetails['transactionTotal'], 2, '.', ',') ?></div>
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
                                                    <?PHP if ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER) : ?>
                                                        <span class="trans-status-cod status-class">Item Delivered</span>
                                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::RETURNED_BUYER) : ?>
                                                        <span class="trans-status-pending status-class">Order Canceled</span>
                                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::CASH_ON_DELIVERY) : ?>
                                                        <span class="trans-status-cod status-class">Cash on delivery</span>
                                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::PAID_FORWARDED) : ?>
                                                        <span class="trans-status-cod status-class">Payment Received</span>
                                                    <?PHP elseif ( (int) $product['idOrderProductStatus'] === (int) \EasyShop\Entities\EsOrderProductStatus::PAID_RETURNED) : ?>
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
                                    <?PHP if ( (int) $productKey === (int) array_shift(array_keys($soldTransactionDetails['product']))) : ?>
                                    <div class="transaction-profile-wrapper">
                                        <h4>Sold To:</h4>
                                        <div>
                                             <span class="transac-item-profile-con">
                                                 <img src="<?php echo getAssetsDomain().'.'.$soldTransactionDetails['userImage']?>">
                                             </span>
                                             <span class="transac-item-consignee-name">
                                                 <?php echo html_escape($soldTransactionDetails['buyerStoreName'])?>
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
                                    <div class="trans-btn-wrapper trans-1btn">
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
