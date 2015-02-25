
<div class="express-edit-content">
    <div class="row">
        <div class="col-xs-12 col-sm-2">
            <div class="express-edit-product-image" style="background: url(<?=getAssetsDomain().$product->directory.'categoryview/'.$product->imageFileName; ?>) center no-repeat; background-cover: cover; background-size: 90%;">
            </div>
        </div>
        <div class="col-xs-12 col-sm-7">
            <div>
                <p><strong>Product Name:</strong></p>
                <input type="text" value="<?=html_escape($product->getName());?>" class="ui-form-control width-100p product-name">
            </div>
            <div class="row edit-sub-content">
                <div class="col-xs-4 col-sm-4">
                    <p><strong>Base Price</strong></p>
                    <span><strong>P</strong></span>
                    <input type="text" value="<?=html_escape(number_format($product->getPrice(), 2, '.', ','));?>" class="ui-form-control base-price" onkeypress="return isNumberKey(event);">
                </div>
                <div class="col-xs-4 col-sm-4">
                    <p><strong>Discounted Price</strong></p>
                    <span><strong>P</strong></span>
                    <input type="text" class="ui-form-control discount-price" value="" onkeypress="return isNumberKey(event);">
                </div>
                <div class="col-xs-4 col-sm-4">
                    <p><strong>Discount Rate</strong></p>
                    <input type="text" value="<?=html_escape(number_format($product->getDiscount(), 2));?>" class="ui-form-control discount-rate" onkeypress="return isNumberKey(event);">
                    <span><strong>%</strong></span>
                </div>
            </div>
            <div class="row edit-sub-content2">
                <div class="col-xs-4 col-sm-4">
                    <span><strong>Available Stock(s):</strong></span>
                    <input type="text" class="ui-form-control txt-total-stock" readonly value="<?=$availableStock;?>">
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3 express-edit-btn">
            <p class="text-center mrgn-bttm-10">Last Modified: <br /><?=$product->getLastmodifieddate()->format('M d, Y'); ?></p>
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
                    <?php if($hasCombination): ?>
                        <?php foreach ($productCombination as $itemId => $combination): ?>
                            <tr class="combination-row">
                                <td width="20%">
                                    <input type="text" value="<?=$combination['quantity'];?>" class="ui-form-control txt-quantity quantity-control" onkeypress="return isNumberKey(event);">
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
                    <?php else: ?>
                        <tr>
                            <td width="20%"><input type="text" value="<?=$soloQuantity; ?>" class="ui-form-control txt-quantity solo-quantity"  onkeypress="return isNumberKey(event);"></td>
                        </tr> 
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 


