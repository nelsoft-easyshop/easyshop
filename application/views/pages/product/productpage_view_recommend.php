<link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
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
         
        <div id="recommended" class="owl-carousel owl-theme">
            <?php foreach ($recommended as $key => $value): ?>
            <div class="item">
                <center>
                    <a href="/item/<?=$value->getSlug(); ?>">
                        <div class="div-rec-product-image">
                            <center>
                                <span class="span-me">
                                    <img src="/<?=$value->directory.'categoryview/'.$value->imageFileName; ?>" class="img-rec-product">
                                </span>
                            </center>
                        </div>
                    </a>
                </center>
                <?php if($value->getIsNew()): ?>
                <span class="span-circle-new">NEW</span>
                <?php endif; ?>

                <?php if(floatval($value->getDiscountPercentage()) > 0):?>
                <span class="span-circle-discount"><?=$value->getDiscountPercentage();?>%</span>
                <?php endif;?>

                <div class="clear"></div>
                <a href="#">
                    <p class="p-rec-product-name">
                        <?=htmlspecialchars($value->getName(),ENT_QUOTES,'ISO-8859-1');?>
                    </p>
                </a>
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
                                <a class="btn btn-default-1 btn-add-cart" target="_blank" href="/item/<?=$value->getSlug(); ?>">
                                    <span class="icon-cart"></span> ADD TO CART
                                </a>
                            </td>
                            <td class="td-logo-store" align="right">
                                <a href="#">
                                    <span>
                                        <div class="store-logo-container ">
                                            <div class="span-store-logo">
                                                <img src="<?=$value->ownerAvatar; ?>" class="store-logo">
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

