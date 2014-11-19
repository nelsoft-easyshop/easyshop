
<div class="div-tab">
    <div class="transaction-tabs">
       <ul class="idTabs">
            <li><a href="#on-going-transaction">On going</a></li>
            <li><a href="#completed-transaction">Completed</a></li>
        </ul>
    </div>
    <div class="col-md-12" id="on-going-transaction">
        <div class="row">
            <div class="transaction-title-bought">
                <span class="trans-title">Bought</span> 
                <span class="count">12</span>
            </div>
            <div class="on-going-transaction-list-bought">
                <div class="mrgn-top-20 mrgn-bttm-25 row">
                    <div class="col-md-6">
                        <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
                    </div>
                    <div class="col-md-6 text-right">
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
                    <?PHP foreach($user_details2['transaction']['ongoing']['bought'] as $key => $boughtTransactionDetails) : ?>
                        <div class="item-list-panel">
                        <?PHP foreach($boughtTransactionDetails['product'] as $productKey => $product) : ?>
                                <table width="100%">
                                    <tbody>
                                    <tr>
                                        <td class="td-image-cont" width="20%" >
                                            <div class="div-product-image" style="background: url(<?=$product['productImagePath']?>) center no-repeat; background-cover: cover; background-size: 90%;">

                                            </div>
                                        </td>
                                        <td class="td-meta-info">
                                            <p class="item-list-name">
                                                <a class="color-default" target="_blank" href="/item/<?=$product['slug'] ?>">
                                                    <?=$product['name'] ?>
                                                </a>
                                            </p>
                                            <p class="item-amount">

                                                <span class="item-current-amount">P<?=number_format($product['price'], 2, '.', ',') ?></span>
                                            </p>
                                            <div class="div-meta-description">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <?php if (intval($boughtTransactionDetails['idPaymentMethod']) === 1 && intval($boughtTransactionDetails['isFlag']) === 1) : ?>
                                                            <span class="strong-label">ON HOLD - PAYPAL PAYMENT UNDER REVIEW</span>
                                                        <?php else:?>
                                                        <span class="strong-label">Transaction No. : </span> <?=$boughtTransactionDetails['invoiceNo'] ?>
                                                        <?PHP endif; ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <span class="strong-label">Date : </span> <?=date_format($boughtTransactionDetails['dateadded'], 'jS \of F Y')?>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <span class="strong-label">Status : </span>
                                                        <?PHP if (intval($boughtTransactionDetails['isFlag']) === 0 && intval($boughtTransactionDetails['orderStatus']) === 0) : ?>
                                                            <?PHP if ($product['isReject']) : ?>
                                                            ITEM REJECTED
                                                            <?php else:?>
                                                                <?=$boughtTransactionDetails['paymentMethod']?>
                                                            <?PHP endif; ?>
                                                        <?PHP else : ?>
                                                            <?PHP if (intval($boughtTransactionDetails['idPaymentMethod']) === 2) : ?>
                                                            CONFIRM DRAGONPAY PAYMENT
                                                            <?PHP elseif (intval($boughtTransactionDetails['idPaymentMethod']) === 5) : ?>
<!--                         ---------------------------        FOR BANK DEPOSIT       ------------------------------------                                         ---------------------------->
                                                                <?php if( $transact['bd_details']['bd_datemodified'] != '' ):?>
                                                                    <?php if($transact['bd_details']['is_invalid'] == 1):?>
                                                                        <span class="trans_alert trans_red">WRONG DEPOSIT DETAILS</span>
                                                                    <?php else:?>
                                                                        <span class="trans_alert trans_orange">PROCESSING DEPOSIT DETAILS</span>
                                                                    <?php endif;?>
                                                                <?php else:?>
                                                                    <span class="trans_alert trans_red">DEPOSIT DETAILS REQUIRED</span>
                                                                <?php endif;?>
                                                            <?PHP elseif (intval($boughtTransactionDetails['idPaymentMethod']) === 1 && intval($boughtTransactionDetails['isFlag']) === 1) : ?>
                                                            ON HOLD
                                                            <?PHP endif; ?>
                                                        <?PHP endif; ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="td-item-actions transaction-right-content" width="25%">
                                            <div class="transaction-profile-wrapper">
                                                <h4>Bought From:</h4>
                                                <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    <?=$product['seller']?>
                                                </span>
                                                </div>
                                            </div>
                                            <button class="btn btn-default-1">
                                                <span class="img-give-feedback"></span>give feedback
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>

                                        </td>
                                        <td colspan="2" class="td-attributes">
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
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                        <?PHP endforeach; ?>
                        </div>
                    <?PHP endforeach; ?>
                    <div class="text-center">
                        <span class="btn btn-loadmore">Load More</span>
                    </div>
                </div>
            </div>
            <div class="transaction-title-sold mrgn-top-12">
                <span class="trans-title">Sold</span> 
                <span class="count">12</span>
            </div>
            <div class="on-going-transaction-list-sold">
                <div class="mrgn-top-20 mrgn-bttm-25 row">
                    <div class="col-md-6">
                        <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
                    </div>
                    <div class="col-md-6 text-right">
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
                <?PHP foreach($user_details2['transaction']['ongoing']['sold'] as $key => $soldTransactionDetails) : ?>
                    <div class="item-list-panel">
                    <?PHP foreach($soldTransactionDetails['product'] as $productKey => $product) : ?>
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(<?=$product['productImagePath']?>) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                <?=$product['name'] ?>
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            <span class="item-current-amount">P<?=number_format($product['price'], 2, '.', ',') ?></span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> <?=$user_details2['transaction']['ongoing']['sold'][$key]['invoiceNo'] ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> <?=date_format($user_details2['transaction']['ongoing']['sold'][$key]['dateadded'], 'jS \of F Y')?>
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> <?=$product['orderQuantity']?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div class="pos-rel">
                                                <span class="view-delivery-lnk">view delivery details</span>
                                                <div class="view-delivery-details">
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Consignee:</strong>
                                                        <span>Clark Christopher Reyes</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Mobile:</strong>
                                                            <span>09123456789</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Telephone:</strong>
                                                            <span>727-1234</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Address:</strong>
                                                        <span>#1 aniston Montgomery Place E. Rodriguez Q.C.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?PHP endforeach; ?>
                    </div>
                <?PHP endforeach; ?>
                </div>
                <div class="text-center">
                    <span class="btn btn-loadmore">Load More</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="completed-transaction">
        <div class="row">
            <div class="transaction-title-bought-completed">
                <span class="trans-title">Bought</span> 
                <span class="count">12</span>
            </div>
            <div class="on-going-transaction-list-bought-completed">
                <div class="mrgn-top-20 mrgn-bttm-25 row">
                    <div class="col-md-6">
                        <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
                    </div>
                    <div class="col-md-6 text-right">
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
                    <div class="item-list-panel">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                Samsung Galaxy S5
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            
                                            <span class="item-original-amount">P34,000.00</span>
                                            <span class="item-current-amount">P24,000.00</span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> 1331-17414-1234567890
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> 28th October 2014
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span> Cash on Delivery
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> 2
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Total : </span> Php 12,400.00
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div class="pos-rel">
                                                <span class="view-delivery-lnk">view delivery details</span>
                                                <div class="view-delivery-details">
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Consignee:</strong>
                                                        <span>Clark Christopher Reyes</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Mobile:</strong>
                                                            <span>09123456789</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Telephone:</strong>
                                                            <span>727-1234</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Address:</strong>
                                                        <span>#1 aniston Montgomery Place E. Rodriguez Q.C.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="item-list-panel">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                Samsung Galaxy S5
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            
                                            <span class="item-original-amount">P34,000.00</span>
                                            <span class="item-current-amount">P24,000.00</span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> 1331-17414-1234567890
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> 28th October 2014
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span> Cash on Delivery
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> 2
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Total : </span> Php 12,400.00
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div class="pos-rel">
                                                <span class="view-delivery-lnk">view delivery details</span>
                                                <div class="view-delivery-details">
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Consignee:</strong>
                                                        <span>Clark Christopher Reyes</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Mobile:</strong>
                                                            <span>09123456789</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Telephone:</strong>
                                                            <span>727-1234</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Address:</strong>
                                                        <span>#1 aniston Montgomery Place E. Rodriguez Q.C.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="item-list-panel">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                Samsung Galaxy S5
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            
                                            <span class="item-original-amount">P34,000.00</span>
                                            <span class="item-current-amount">P24,000.00</span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> 1331-17414-1234567890
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> 28th October 2014
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span> Cash on Delivery
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> 2
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Total : </span> Php 12,400.00
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div class="pos-rel">
                                                <span class="view-delivery-lnk">view delivery details</span>
                                                <div class="view-delivery-details">
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Consignee:</strong>
                                                        <span>Clark Christopher Reyes</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Mobile:</strong>
                                                            <span>09123456789</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Telephone:</strong>
                                                            <span>727-1234</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Address:</strong>
                                                        <span>#1 aniston Montgomery Place E. Rodriguez Q.C.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <span class="btn btn-loadmore">Load More</span>
                    </div>
                </div>
            </div>
            <div class="transaction-title-sold-completed mrgn-top-12">
                <span class="trans-title">Sold</span> 
                <span class="count">12</span>
            </div>
            <div class="on-going-transaction-list-sold-completed">
                <div class="mrgn-top-20 mrgn-bttm-25 row">
                    <div class="col-md-6">
                        <input type="text" class="ui-form-control transaction-search" placeholder="Enter transaction no.">
                    </div>
                    <div class="col-md-6 text-right">
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
                    <div class="item-list-panel">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                Samsung Galaxy S5
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            
                                            <span class="item-original-amount">P34,000.00</span>
                                            <span class="item-current-amount">P24,000.00</span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> 1331-17414-1234567890
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> 28th October 2014
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span> Cash on Delivery
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> 2
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Total : </span> Php 12,400.00
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div class="pos-rel">
                                                <span class="view-delivery-lnk">view delivery details</span>
                                                <div class="view-delivery-details">
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Consignee:</strong>
                                                        <span>Clark Christopher Reyes</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Mobile:</strong>
                                                            <span>09123456789</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pd-tb-8">
                                                            <strong>Telephone:</strong>
                                                            <span>727-1234</span>
                                                        </div>
                                                        <div class="pd-tb-8">
                                                            <strong>State/Region:</strong>
                                                            <span>Quezon City</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pd-tb-8">
                                                        <strong>Address:</strong>
                                                        <span>#1 aniston Montgomery Place E. Rodriguez Q.C.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="item-list-panel">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                Samsung Galaxy S5
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            
                                            <span class="item-original-amount">P34,000.00</span>
                                            <span class="item-current-amount">P24,000.00</span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> 1331-17414-1234567890
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> 28th October 2014
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span> Cash on Delivery
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> 2
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Total : </span> Php 12,400.00
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div>
                                                <span>view delivery details</span>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="item-list-panel">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="td-image-cont" width="20%" >
                                        <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                            
                                        </div>
                                    </td>
                                    <td class="td-meta-info">
                                        <p class="item-list-name">
                                            <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                Samsung Galaxy S5
                                            </a>
                                        </p>
                                        <p class="item-amount">
                                            
                                            <span class="item-original-amount">P34,000.00</span>
                                            <span class="item-current-amount">P24,000.00</span>
                                        </p>
                                        <div class="div-meta-description">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="strong-label">Transaction No. : </span> 1331-17414-1234567890
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Date : </span> 28th October 2014
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Status : </span> Cash on Delivery
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Quantity : </span> 2
                                                </div>
                                                <div class="col-xs-6">
                                                    <span class="strong-label">Total : </span> Php 12,400.00
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-item-actions transaction-right-content" width="25%">
                                        <div class="transaction-profile-wrapper">
                                            <h4>Sold To:</h4>
                                            <div>
                                                <span class="transac-item-profile-con">
                                                    <img src="/assets/images/products/samsung-p.jpg">
                                                </span>
                                                <span class="transac-item-consignee-name">
                                                    Juan Delafo foooooo0000oooooooooz
                                                </span>
                                            </div>
                                            <div>
                                                <span>view delivery details</span>
                                            </div>
                                        </div>
                                        <button class="btn btn-default-3">
                                            <span class="img-completed"></span>completed
                                        </button>
                                        
                                        <button class="btn btn-default-1">
                                            <span class="img-give-feedback"></span>give feedback
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                    <td colspan="2" class="td-attributes">
                                        <div class="info-main-cont">
                                            <div class="toggle-info trans-item-info">
                                                <i class="fa fa-plus-circle"></i>more info
                                            </div>
                                            <div class="info-attributes">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Color : </span>blue, charcoal black, white
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center">
                    <span class="btn btn-loadmore">Load More</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
