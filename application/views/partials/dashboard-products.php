
<div id="express-edit-section">
    <center class="loading-image"><img src="<?php echo getAssetsDomain(); ?>assets/images/loading/preloader-whiteBG-small.gif" alt="Please wait !!!"></center>
    <div class="express-edit-section-content">
    </div>
</div>

<?php foreach ($products as $product): ?>
<div id="item-list-<?=$product->getIdProduct(); ?>" class="items-list-container item-list-<?=$product->getIdProduct(); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="item-list-panel">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td class="td-image-cont" width="20%" >
                                <div class="div-product-image" >
                                    <img src="<?php echo getAssetsDomain().$product->directory.$product->imageFileName?>" class="image-primary">
                                    <?php if((float)$product->getDiscountPercentage() > 0):?>
                                    <div class="pin-discount">
                                        <?php echo number_format($product->getDiscountPercentage(),0,'.',',');?>%
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="td-meta-info" >
                                <p class="item-list-name">
                                    <?php if((int)$product->getIsDelete() === EasyShop\Entities\EsProduct::DELETE || (int)$product->getIsDraft() === EasyShop\Entities\EsProduct::DRAFT): ?>
                                            <?php if(strlen($product->getName()) > 100): ?>
                                                <?=substr_replace( html_escape($product->getName()), "...", 100); ?>
                                            <?php else: ?>
                                                <?=html_escape($product->getName());?>
                                            <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($product->getName()): ?>
                                        <a class="color-default" target="_blank" href="/item/<?=$product->getSlug();?>">
                                            <?php if(strlen($product->getName()) > 100): ?>
                                                <?=substr_replace( html_escape($product->getName()), "...", 100); ?>
                                            <?php else: ?>
                                                <?=html_escape($product->getName());?>
                                            <?php endif; ?>
                                        </a>
                                        <?php else: ?>
                                            (NO NAME)
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                                <p class="item-amount">
                                    <?php $priceFontStyle = strlen((string)$product->getOriginalPrice()) > 12 ? "font-size:13px;" : ""; ?>
                                    <?php if((float)$product->getDiscountPercentage() > 0):?>
                                        <span class="item-original-amount" style="<?php echo $priceFontStyle; ?>">
                                            P<?=number_format($product->getOriginalPrice(),2,'.',',');?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="item-current-amount" style="<?php echo $priceFontStyle ?>">
                                        P<?=number_format($product->getFinalPrice(),2,'.',',');?>
                                    </span>
                                </p>
                                <div class="div-meta-description">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="strong-label">Sold Item(s) : </span> <?=$product->soldCount; ?>
                                        </div>
                                        <div class="col-md-8 col-stock">
                                            <span class="strong-label">Available Stock(s) : </span> <span class="stock-number"> <?=$product->availableStock; ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <span class="strong-label">Category : </span> <?=html_escape($product->category);?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table width="100%">
                                                <tr>
                                                    <td class="td-label-desc"><span class="strong-label">Description: </span></td>
                                                    <td class="td-desc-item">
                                                        <?php if(strlen($product->getBrief()) > 100): ?>
                                                            <?=substr_replace(html_escape($product->getBrief()), "...", 100); ?>
                                                        <?php else: ?>
                                                            <?=html_escape($product->getBrief());?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div> 
                                    <div class="row row-action-mobile">
                                        <div class="col-md-12">
                                            <?php if((int)$product->getIsDelete() === EasyShop\Entities\EsProduct::ACTIVE): ?>
                                                <?php if((int)$product->getIsDraft() === EasyShop\Entities\EsProduct::ACTIVE):?>
                                                    <button class="btn btn-action-edit btn-edit-product" data-slug="<?=$product->getSlug(); ?>">
                                                        <i class="icon-edit"></i>edit
                                                    </button>
                                                <?php else: ?> 
                                                    <button class="btn btn-action-edit btn-edit-draft-product" 
                                                        data-productid="<?=$product->getIdProduct(); ?>"
                                                        data-categoryid="<?=$product->getCat()->getIdCat(); ?>"
                                                        data-othercategoryname="<?=html_escape($product->getCatOtherName()); ?>">
                                                        <i class="icon-edit"></i>edit
                                                    </button>
                                                <?php endif;?> 
                                            <?php else: ?>
                                            <button data-id=<?=$product->getIdProduct(); ?> class="btn btn-action-delete btn-restore">
                                                <i class="icon-delete"></i>Restore
                                            </button>
                                            <?php endif; ?> 
                                            <button data-id=<?=$product->getIdProduct(); ?> class="<?=(int)$product->getIsDelete() === EasyShop\Entities\EsProduct::DELETE || (int)$product->getIsDraft() === EasyShop\Entities\EsProduct::DRAFT ? 'hard-delete' : 'soft-delete';?> btn btn-action-delete btn-delete">
                                                <i class="icon-delete"></i>delete
                                            </button> 
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-item-actions" width="25%">
                                <p>Last Modified: <?=$product->getLastmodifieddate()->format('M d, Y'); ?></p>
                                <div class="">
                                <?php for ($i=0; $i < 5; $i++): ?>
                                    <i class="icon-star star-stat <?=$product->rating > 0 ? 'star-active' : '' ?>"></i>
                                    <?php $product->rating--; ?>
                                <?php endfor; ?>
                                </div>
                                <p>Total Reviews : <?=$product->reviewCount; ?></p>
                                <?php if((int)$product->getIsDelete() === EasyShop\Entities\EsProduct::ACTIVE): ?>
                                    <?php if((int)$product->getIsDraft() === EasyShop\Entities\EsProduct::ACTIVE):?>
                                        <button class="btn btn-action-edit btn-edit-product" 
                                            data-slug="<?=$product->getSlug(); ?>"
                                            data-pid="<?=$product->getIdProduct();?>">
                                            <i class="icon-edit"></i>edit
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-action-edit btn-edit-draft-product" 
                                            data-productid="<?=$product->getIdProduct(); ?>"
                                            data-categoryid="<?=$product->getCat()->getIdCat(); ?>"
                                            data-othercategoryname="<?=html_escape($product->getCatOtherName()); ?>">
                                            <i class="icon-edit"></i>edit
                                        </button>
                                    <?php endif;?>
                                <?php else: ?>
                                <button data-id=<?=$product->getIdProduct(); ?> class="btn btn-action-delete btn-restore">
                                    <i class="icon-delete"></i>Restore
                                </button>
                                <?php endif; ?>
                                <button data-id=<?=$product->getIdProduct(); ?> class="<?=(int)$product->getIsDelete() === EasyShop\Entities\EsProduct::DELETE || (int)$product->getIsDraft() === EasyShop\Entities\EsProduct::DRAFT ? 'hard-delete' : 'soft-delete';?> btn btn-action-delete btn-delete">
                                    <i class="icon-delete"></i>delete
                                </button> 
                            </td>
                        </tr>
                        <tr class="tr-attributes-drop">
                            <td></td>
                            <td colspan="2" class="td-attributes">
                                <?php if(empty($product->attributes) === false): ?>
                                <div class="info-main-cont">
                                    <div class="toggle-info more-info-attribute">
                                        <i class="info-item-icon fa fa-plus-circle"></i> <span class="text-info-icon">more info</span>
                                    </div>
                                    <div class="info-attributes">
                                        <div class="row">
                                            <?php foreach($product->attributes as $attributeName => $attributeValue): ?>
                                                <div class="col-xs-5">
                                                    <span class="strong-label"><?=html_escape($attributeName);?> : </span>
                                                    <?=html_escape(implode(', ', $attributeValue)); ?> 
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php if(isset($pagination)): ?>
<center>
    <div class="pagination-section">
        <?=$pagination;?>
    </div>
</center>
<?php endif; ?>

