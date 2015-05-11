
<div class="express-edit-content">
    <div class="express-edit-confirm"></div>
    <div class="row">
        <div class="col-xs-12 col-md-3">
            <div class="express-edit-product-image" style="background: url(<?=getAssetsDomain().$product->directory.'categoryview/'.$product->imageFileName; ?>) center no-repeat; background-size: 62%;">
            </div>
        </div>
        <div class="col-xs-12 col-md-6 padding-reset">
            <div>
                <p><strong>Product Name:</strong></p>
                <input type="text" value="<?=html_escape($product->getName());?>" class="ui-form-control width-100p product-name">
            </div>
            <div class="edit-sub-content">
                <div class="edit-sub-content-column1">
                    <p><strong>Base Price</strong></p>
                    <span><strong>P</strong></span>
                    <input type="text" maxlength="10" value="<?=html_escape(number_format($product->getPrice(), 2, '.', ','));?>" class="ui-form-control base-price" onkeypress="return isNumberKey(event);">
                </div>
                <div class="edit-sub-content-column2">
                    <p><strong>Discounted Price</strong></p>
                    <span><strong>P</strong></span>
                    <input type="text" maxlength="10" class="ui-form-control discount-price" value="" onkeypress="return isNumberKey(event);">
                </div>
                <div class="edit-sub-content-column3">
                    <p><strong>Discount Rate</strong></p>
                    <input type="text" maxlength="10" value="<?=html_escape(number_format($product->getDiscount(), 4));?>" class="ui-form-control discount-rate" onkeypress="return isNumberKey(event);">
                    <span><strong>%</strong></span>
                </div>
                <div class="clear"></div>
            </div>
            <?php if(!$hasCombination): ?>
            <div class="edit-sub-content2">
                <div class="edit-sub-content-column1">
                    <p><strong>Available Stock(s):</strong></p>
                    <input type="text" maxlength="4" class="ui-form-control txt-total-stock txt-quantity solo-quantity" value="<?=$availableStock;?>" onkeypress="return isNumberKey(event, false);">
                </div>
                <div class="clear"></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-xs-12 col-md-3 express-edit-btn text-center">
            <p class="text-center mrgn-bttm-10"><strong><span>Last Modified:</span> <span><?=$product->getLastmodifieddate()->format('M d, Y'); ?></span></strong></p>
            <button type="button" class="btn btn-default-1 btn-advance-edit"
                data-productid="<?=$product->getIdProduct(); ?>"
                data-categoryid="<?=$product->getCat()->getIdCat(); ?>"
                data-othercategoryname="<?=html_escape($product->getCatOtherName()); ?>">
                advanced
            </button>
            <button type="button" class="btn btn-set-default cancel-btn">cancel</button> 
            <button type="button" class="btn btn-default-3 save-btn">save</button>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-sm-offset-2">
            <div class="error-message bg-warning">
                Error message
            </div>
        </div>
    </div> 

    <?php if($hasCombination): ?>
        <div class="row">
            <div class="col-xs-12">
                <table class="table prod-att-table" width="100%">
                    <thead>
                        <tr>
                            <th width="20%">quantity</th>
                            <th width="60%">item property</th>
                            <th width="20%">actions</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach ($productCombination as $itemId => $combination): ?>
                            <tr class="combination-row">
                                <td width="20%">
                                    <input type="text" value="<?=$combination['quantity'];?>" class="ui-form-control txt-quantity quantity-control" maxlength="4" onkeypress="return isNumberKey(event, false);">
                                    <input type="hidden" value="<?=$itemId;?>" class="item-control">
                                </td>
                                <td width="60%" class="prod-item-att-content"> 
                                    <?php foreach($combination['product_attribute_ids'] as $id): ?>
                                        <input readonly class="ui-form-control" type="text" value="<?=html_escape(ucfirst(strtolower($productAttributes[$id])));?>"> 
                                    <?php endforeach; ?>
                                </td>
                                <td width="20%">
                                    <?php if(count($productCombination) > 1): ?>
                                    <button type="button" class="btn btn-default-1 remove-row">remove</button>
                                    <?php endif; ?>
                                </td>
                            </tr> 
                        <?php endforeach; ?> 
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div> 


