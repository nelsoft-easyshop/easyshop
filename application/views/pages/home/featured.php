<div class="row mo">
    <div class="row" style="background: url('<?=$homeContent['seller']['banner']?>') no-repeat center; background-size: cover; padding: 10px 0px;">
        <div class="col-lg-9 col-md-12 col-xs-12 col-featured-items">
            <a class="prev"><i class="fa fa-angle-left fa-prev-slide"></i></a>
            <a class="next"><i class="fa fa-angle-right fa-next-slide"></i></a>
            <div id="featured-seller" class="owl-carousel owl-theme">
            
                <?php $featuredSellerSlug = reset($homeContent['seller']['product'])['product']->getMember()->getSlug(); ?>
            
                <?PHP foreach ($homeContent['seller']['product'] as $product) : ?>
                    <?php $productSlug = $product['product']->getSlug(); ?>
                    <div class="item">
                            <a href="/item/<?php echo $productSlug ?>">
                                <div class="div-rec-product-image">
                                    <center>
                                        <span class="span-me">
                                            <img src="<?=$product['image']['directory'] . 'categoryview/' . $product['image']['imageFileName']?>" class="img-rec-product">
                                        </span>
                                    </center>
                                </div>
                            </a>
                            <?php if ($product['product']->getIsNew()): ?>
                                <span class="new-circle">NEW</span>
                            <?php endif; ?>

                            <?PHP if ($product['product']->getDiscount() > 0) : ?>
                                <span class="discount-circle">-<?=number_format($product['product']->getDiscount(), 0, '.', ',')?>%</span>
                            <?PHP endif; ?>
                            <!-- End .item-image -->
                          
                        <div class="item-meta-container" align="left">
                            <h3 class="item-name">
                                <a href="/item/<?=$productSlug?>">
                                    <?=(strlen($product['product']->getName())>15)?substr_replace($product['product']->getName(), "...", 15):$product['product']->getName();?>
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
                                        <a class="btn btn-default-1 btn-add-cart" href="javascript:void(0);" data-slug="<?php echo $product['product']->getSlug(); ?>" data-productid="<?php echo $product['product']->getIdProduct(); ?>>
                                            <span class="icon-cart"></span> ADD TO CART
                                        </a>     
                                    </td>
                                    <td class="td-logo-store">
                                        <span class="pull-right">
                                            <div class="store-logo-container ">
                                                <div class="span-store-logo">
                                                    <a href="/<?php echo $featuredSellerSlug ?>">
                                                        <?PHP if($homeContent['seller']['logo']) : ?>
                                                            <img src="<?=$homeContent['seller']['logo']?>" class="img-store-logo"/>
                                                        <?PHP else : ?>
                                                            <img src="<?=$homeContent['seller']['vendor_image']?>" class="img-store-logo"/>
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
                <center>
                <div class="store-big-logo-container ">
                    <div class="span-store-big-logo">
                        <a href="/<?php echo $featuredSellerSlug ?>">
                            <?PHP if($homeContent['seller']['logo']) : ?>
                                <img src="<?=$homeContent['seller']['logo']?>" class="img-featured-seller"/>
                            <?PHP else : ?>
                                <img src="<?=$homeContent['seller']['vendor_image']?>" class="img-featured-seller"/>
                            <?PHP endif; ?>
                        </a>
                    </div>
                </div>
                </center>
            </div>
        </div>
        
    </div>
    
</div>