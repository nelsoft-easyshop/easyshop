<div class="row-featured-category">
   
    <?php $sectionCount  = 1; ?>
    <?php foreach($homeContent['categorySection'] as $categorySection): ?>
        <div class="row row-category" id="category-1">        
            <div class="col-md-12">
            <div class="purchased-items-container carousel-wrapper">
                    <div class="category-title-container">

                        <p>
                            <?php echo html_escape(strtoupper($categorySection['category']->getName())); ?>
                            <span class="pull-right">
                                <span>
                                    <a id="purchased-items-slider-prev-cat-<?php echo $sectionCount ?>"><i class="icon-control-left fa-category-item-prev"></i></a>
                                    <a id="purchased-items-slider-next-cat-<?php echo $sectionCount ?>"><i class="icon-control-right fa-category-item-next"></i></a>
                                </span>
                            </span>
                            <span class="pull-right">
                                <a class="a-see-all" href="#"><span class="span-see-all">see all</span></a>
                            </span>
                        </p>
                    </div>
                    <div class="tag-categories">
                        <?php $count = 0; ?>
                        <?php foreach($categorySection['subHeaders'] as $subheader): ?>
                            <a href="<?php echo $subheader['target']; ?>">
                                <button class="btn-tag <?php echo $count === 0 ? 'btn-tag-active' : '' ; ?>" id="new-arrival">
                                    <?php echo html_escape($subheader['text']); ?>
                                </button>
                            </a>
                            <?php $count++; ?>
                        <?php endforeach; ?>

                    </div>
                    
                    
                    <div id="cat-items" class="purchased-items-slider-cat-<?php echo $sectionCount ?> owl-carousel"> 
                    
                        <?php foreach($categorySection['products'] as $productSection): ?>
                            <?php $product = $productSection['product']; ?>
                            <?php $sellerimage = $productSection['userimage']; ?>
                            <div class="item">
                                <?php $defaultImage = $product->getDefaultImage(); ?>
                                <div class="item-image-container" style="background: url(<?php echo $defaultImage->getDirectory().'small/'.$defaultImage->getFilename() ?>) center no-repeat; background-size: cover">
                                  
                                    <?php if (floatval($product->getDiscountPercentage()) > 0): ?>
                                        <span class="discount-circle">-<?php echo $product->getDiscountPercentage() ?>%</span>
                                    <?php endif; ?>
                                    
                                    
                                </div><!-- End .item-image -->
                                                                
                                <div class="item-meta-container" align="left">
                                    <h3 class="item-name">
                                        <a href="/item/<?php echo $product->getSlug() ?>">
                                            <?php echo html_escape((strlen($product->getName())> 15)?substr_replace($product->getName(), "...", 15):$product->getName());?>
                                        </a>
                                    </h3>
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
                                                <a class="btn btn-default-1 btn-add-cart" target="_blank" href="/item/<?php echo $product->getSlug() ?>">
                                                    <span class="icon-cart"></span> ADD TO CART
                                                </a>
                                            </td>
                                            <td class="td-logo-store">
                                                <span class="pull-right">
                                                    <div class="store-logo-container ">
                                                        <div class="span-store-logo">
                                                        <img src="<?php echo $sellerimage ?>"/>
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