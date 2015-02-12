<div class="row-featured-category">
   
    <?php $sectionCount  = 1; ?>
    <?php foreach($homeContent['categorySection'] as $categorySection): ?>
        <div class="row row-category" id="category-<?php echo $sectionCount; ?>">        
            <div class="col-md-12">
            <div class="purchased-items-container carousel-wrapper">
                    <div class="category-title-container">
                        <span class="pull-left">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/categories/icon-categories/<?php echo $categorySection['category']->getSlug()?>.png" class="img-category">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/categories/icon-flats/<?php echo $categorySection['category']->getSlug()?>.png" class="img-category-mobile">
                        </span>
                        <p>
                            <span>
                                <?php echo html_escape(strtoupper($categorySection['category']->getName())); ?>
                            </span>
                            <span class="pull-right sn-container">
                                <span>
                                    <a id="purchased-items-slider-prev-cat-<?php echo $sectionCount ?>"><i class="fa fa-angle-left fa-category-item-prev"></i></a>
                                    <a id="purchased-items-slider-next-cat-<?php echo $sectionCount ?>"><i class="fa fa-angle-right fa-category-item-next"></i></a>
                                </span>
                            </span>
                            <span class="pull-right">
                                <a class="a-see-all" href="/category/<?php echo $categorySection['category']->getSlug()?>"><span class="span-see-all">see all</span></a>
                            </span>
                        </p>
                    </div>
                    <div class="tag-categories" id="tag-<?php echo $sectionCount; ?>">
                        <?php $count = 0; ?>
                        <?php foreach($categorySection['subHeaders'] as $subheader): ?>
                            <a href="javascript:void(0)" class="sub-class" data-id="<?=html_escape($subheader['target'])?>" data-section-count="<?=$sectionCount?>">
                                <button class="btn-tag <?php echo $count === 0 ? 'btn-tag-active' : '' ; ?>">
                                    <?php echo html_escape($subheader['text']); ?>
                                </button>
                                <script type="text/javascript">
                                    jQuery(function () {
                                        $("[rel='tooltip']").tooltip();
                                    });
                                </script>
                            </a>
                            <?php $count++; ?>
                            <?php if (isset($subheader['productPanel'])) : ?>
                                <?php foreach ($subheader['productPanel'] as $product) : ?>
                                    <input type="hidden" class="product-<?=$subheader['target']?>" data-slug="<?=html_escape($product['slug'])?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                    
                    
                    <div id="cat-items" class="purchased-items-slider-cat-<?php echo $sectionCount ?> owl-carousel"> 
                    
                        <?php foreach($categorySection['products'] as $productSection): ?>
                            <?php $product = $productSection['product']; ?>
                            <?php $sellerimage = $productSection['userimage']; ?>
                            <?php $secondaryImage = $productSection['productSecondaryImage']; ?>
                            <?php $productSlug = $product->getSlug(); ?>
                            <div class="item">
                                <?php $defaultImage = $product->getDefaultImage(); ?>
                                    <center>
                                        <a href="/item/<?php echo $productSlug ?>">

                                            <!--hover image-->
                                            <?php if($secondaryImage): ?>
                                                <div class="div-rec-product-image hover-prod-image" style="background: #fff url(<?php echo  getAssetsDomain().$secondaryImage->getDirectory().'small/'.$secondaryImage->getFilename() ?>) center no-repeat; background-size: cover; ">
                                                    
                                                </div>
                                                <div class="div-rec-product-image main-prod-image">
                                            <?PHP else : ?>
                                                <div class="div-rec-product-image">
                                            <?PHP endif; ?>
                                            <!--main image-->
                                                    <div class="image-handler" style="background: #fff url(<?php echo  getAssetsDomain().$defaultImage->getDirectory().'small/'.$defaultImage->getFilename() ?>) center no-repeat; background-size: cover;">
                                        
                                                    </div>
                                                </div>
                                                
                                        </a>
                                    </center>
                                    <?php if ($product->getIsNew()): ?>
                                        <span class="new-circle-2">NEW</span>
                                    <?php endif; ?>
                                    <?php if (floatval($product->getDiscountPercentage()) > 0): ?>
                                        <span class="discount-circle-2"><?php echo number_format($product->getDiscountPercentage(), 0); ?>%</span>
                                    <?php endif; ?>
                                <div class="item-meta-container" align="left">
                                    
                                        <a href="/item/<?php echo $productSlug ?>">
                                            <h3 class="item-name" style="position: relative;">
                                                <?php if(strlen($product->getName())>20): ?>
                                                     <div class="tooltip-home">
                                                       <?php echo html_escape($product->getName())?>
                                                     </div>
                                                <?php endif; ?>
                                                 
                                                <?php if(strlen($product->getName())>20): ?>
                                                    <span style="width: 100%; display: inline-block; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                                                <?php else: ?>
                                                    <span>
                                                <?php endif; ?>
                                                    <?php echo html_escape((strlen($product->getName())> 20)?substr_replace($product->getName(), "...", 20):$product->getName());?>
                                                    </span>
                                            </h3>
                                        </a>
                                    
                                    <div class="item-meta-inner-container clearfix">
                                        <div class="item-price-container">
                                            <?php if (floatval($product->getDiscountPercentage()) > 0): ?>
                                                <span class="old-price">P<?php echo number_format ($product->getOriginalPrice() , 2) ?></span>
                                            <?php endif; ?>
                                            <span class="item-price">P<?php echo number_format ($product->getFinalPrice() , 2) ?></span>
                                        
                                        </div>
                                    </div>
                                    <div class="item-action">
                                        <table width="100%">
                                        <tr>
                                            <td>
                                                <a class="btn btn-default-1 btn-add-cart" target="_blank" href="javascript:void(0);" data-slug="<?php echo $product->getSlug(); ?>" data-productid="<?php echo $product->getIdProduct(); ?>">
                                                    <span class="icon-cart"></span> ADD TO CART
                                                </a>
                                            </td>
                                            <td class="td-logo-store">
                                                <span class="pull-right">
                                                    <div class="store-logo-container ">
                                                        <div class="span-store-logo">
                                                            <a href="/<?php echo $product->getMember()->getSlug() ?>">
                                                                <img src="<?php echo getAssetsDomain().'.'.$sellerimage ?>" class="img-store-logo"/>
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

                        <?php endforeach; ?>
                    </div><!--purchased-items-slider -->
                </div><!-- End .purchased-items-container -->
            </div><!-- End .col-md-12 -->
        </div><!-- End .row -->
        <?php $sectionCount++; ?>
    <?php endforeach; ?>
    

    
</div>