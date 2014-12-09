
<?php if(count($recommended) > 0): ?>
<div class="container" id="rec">
    <div class="prod-detail-main">
        <div class="div-rec-title">
            <p class="p-rec-title">Recommended</p>
            <span class="span-rec-nav">
                <span class="span-see-all">
                    <a href="/category/<?=$productCategorySlug; ?>">see all</a>
                </span>
                <a class="prev">
                    <i class="fa fa-angle-left span-nav-prev"></i>
                </a>
                <a class="next">
                    <i class="fa fa-angle-right span-nav-next"></i>
                </a>
            </span>
        </div>
         
        <div id="recommended" class="owl-carousel owl-theme" style="overflow: hidden;">
            <?php foreach ($recommended as $value): ?>
            <div class="item">
                    <a href="/item/<?=$value->getSlug(); ?>">
                     
                        <?php if(isset($value->secondaryImage)): ?>
                            <div class="div-rec-product-image hover-prod-image" style="background: #fff url(<?php echo getAssetsDomain().$value->directory.'small/'.$value->secondaryImage; ?>) center no-repeat; background-size: cover; "></div>

                            <div class="div-rec-product-image main-prod-image">
                        <?php else: ?>
                            <div class="div-rec-product-image">
                        <?php endif; ?>
                                <div class="image-handler" style="background: #fff url(<?php echo getAssetsDomain().$value->directory.'small/'.$value->imageFileName; ?>) center no-repeat; background-size: cover;">
                                
                                </div>
                            </div>
                    </a>
                
                <?php if($value->getIsNew()): ?>
                <span class="span-circle-new">NEW</span>
                <?php endif; ?>

                <?php if(floatval($value->getDiscountPercentage()) > 0):?>
                <span class="span-circle-discount"><?=number_format($value->getDiscountPercentage(),0,'.',',');?>%</span>
                <?php endif;?>

                <div class="clear"></div>
                <div class="item-name2" style="position: relative; width: 100%;">
                    <?php
                        $item_name = htmlspecialchars(iconv("cp1252", "utf-8", $value->getName()),ENT_IGNORE,'UTF-8',true);
                        if(strlen($item_name)>20):
                    ?>
                        <div class="tooltip-home">
                           <?php echo htmlspecialchars(iconv("cp1252", "utf-8", $value->getName()),ENT_IGNORE,'UTF-8',true);?>
                         </div>
                    <?php endif; ?>
                        <p class="p-rec-product-name">
                            <a href="/item/<?=$value->getSlug(); ?>"><?=htmlspecialchars(iconv("cp1252", "utf-8", $value->getName()),ENT_IGNORE,'UTF-8',true);?></a>
                        </p>
                </div>
                <p class="p-rec-product-price">
                    
                    <?php if(floatval($value->getDiscountPercentage()) > 0):?>
                        <s>P <?=number_format($value->getOriginalPrice(),2,'.',','); ?> </s>
                        <span>P<?=number_format($value->getFinalPrice(),2,'.',',');?> </span>
                    <?php else: ?>
                       
                        <span>P<?=number_format($value->getFinalPrice(),2,'.',',');?> </span>
                    <?php endif; ?>
                </p>
                <table width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <a class="btn btn-default-1 btn-add-cart" href="javascript:void(0);" data-slug="<?php echo $value->getSlug(); ?>" data-productid="<?php echo $value->getIdProduct(); ?>">
                                    <span class="icon-cart"></span> ADD TO CART
                                </a>
                            </td>
                            <td class="td-logo-store" align="right">
                                <a href="/<?php echo $value->getMember()->getslug() ?>">
                                    <span>
                                        <div class="store-logo-container ">
                                            <div class="span-store-logo">
                                                <img src="<?php echo getAssetsDomain().'.'.$value->ownerAvatar; ?>" class="store-logo">
                                            </div>
                                        </div>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

