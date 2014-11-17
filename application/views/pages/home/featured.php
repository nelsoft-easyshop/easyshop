
<div class="row mo">
    <div class="row" style="background: url('<?=$homeContent['seller']['banner']?>') no-repeat center; background-size: cover; padding: 10px 0px;">
        <div class="col-lg-9 col-md-12 col-xs-12 col-featured-items">
            
            <?php if(count($homeContent['seller']['product']) > 0): ?>
                <a class="prev"><i class="fa fa-angle-left fa-prev-slide"></i></a>
                <a class="next"><i class="fa fa-angle-right fa-next-slide"></i></a>
            <?php endif; ?>
            
            <div id="featured-seller" class="owl-carousel owl-theme">
                <?php $featuredSellerSlug = $homeContent['seller']['memberEntity']->getSlug(); ?>
                <?PHP foreach ($homeContent['seller']['product'] as $product) : ?>
                    <?php $productSlug = $product['product']->getSlug(); ?>
                    <?php $defaultImage = $product['product']->getDefaultImage(); ?>
                    <?php $secondaryImage = $product['secondaryProductImage']; ?>

                    <div class="item">
                        <center>
                            <a href="/item/<?php echo $productSlug ?>">
                            
                                <!--hover image-->
                                <?PHP if ($secondaryImage) : ?>
                                    <div class="div-rec-product-image hover-prod-image" style="background: #fff url(<?php echo $secondaryImage->getDirectory().'small/'.$secondaryImage->getFilename() ?>) center no-repeat; background-size: cover; ">
                                        
                                    </div>
                                    <div class="div-rec-product-image main-prod-image" style="background: #fff url(<?php echo $defaultImage->getDirectory().'small/'.$defaultImage->getFilename() ?>) center no-repeat; background-size: cover;">
                                <?PHP else : ?>
                                    <div class="div-rec-product-image" style="background: #fff  url(<?php echo $defaultImage->getDirectory().'small/'.$defaultImage->getFilename() ?>) center no-repeat; background-size: cover;">
                                <?PHP endif; ?>
                                <!--main image-->
                                        
                                    </div>
                                </a>
                        </center>
                        <?php if ($product['product']->getIsNew()): ?>
                            <span class="new-circle">NEW</span>
                        <?php endif; ?>

                        <?PHP if ($product['product']->getDiscount() > 0) : ?>
                            <span class="discount-circle"><?=number_format($product['product']->getDiscount(), 0, '.', ',')?>%</span>
                        <?PHP endif; ?>
                        <!-- End .item-image -->
                          
                        <div class="item-meta-container" align="left">
                            <h3 class="item-name">
                                <a href="/item/<?=$productSlug?>">
                                    <?php echo html_escape((strlen($product['product']->getName())>20) ? substr_replace($product['product']->getName(), "...", 20): $product['product']->getName()) ;?>
                                </a>
                            </h3>
                            <div class="item-meta-inner-container clearfix">
                                <div class="item-price-container">
                                    <?PHP if ($product['product']->getDiscount() > 0) : ?>
                                    <span class="old-price">P<?=number_format($product['product']->getPrice(), 2, '.', ',') ?></span><span class="item-price">P<?=number_format($product['product']->getFinalPrice(), 2, '.', ',') ?></span>
                                    <?PHP else : ?>
                                    <span class="item-price">P<?=number_format($product['product']->getFinalPrice(), 2, '.', ',') ?></span>
                                    <?PHP endif; ?>
                                </div>
                            </div>
                            <div class="item-action">
                                <table width="100%">
                                <tr>
                                    <td>
                                        <a class="btn btn-default-1 btn-add-cart" href="javascript:void(0);" data-slug="<?php echo $product['product']->getSlug(); ?>" data-productid="<?php echo $product['product']->getIdProduct(); ?>">
                                            <span class="icon-cart"></span> ADD TO CART
                                        </a>     
                                    </td>
                                    <td class="td-logo-store">
                                        <span class="pull-right">
                                            <div class="store-logo-container ">
                                                <div class="span-store-logo">
                                                
                                                    <a href="/<?php echo $featuredSellerSlug ?>">
                                                        <?PHP if($homeContent['seller']['logo']) : ?>
                                                            <img src="<?php echo getAssetsDomain().'.'.$homeContent['seller']['logo']?>" class="img-store-logo"/>
                                                        <?PHP else : ?>
                                                            <img src="<?php echo getAssetsDomain().'.'.$homeContent['seller']['vendor_image']?>" class="img-store-logo"/>
                                                        <?PHP endif; ?>
                                                    </a>

                                                </div>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                                </table>
                            </div>
                        </div>
                    </div>
            <?PHP endforeach; ?>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="col-store-logo">
                <div class="store-big-logo-container ">
                    <div class="span-store-big-logo">
                        <a href="/<?php echo $featuredSellerSlug ?>">
                            <?PHP if($homeContent['seller']['logo']) : ?>
                                <img src="<?php echo getAssetsDomain().'.'.$homeContent['seller']['logo']?>" class="img-featured-seller"/>
                            <?PHP else : ?>
                                <img src="<?php echo getAssetsDomain().'.'.$homeContent['seller']['vendor_image']?>" class="img-featured-seller"/>
                            <?PHP endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>