    <div class="product-paging" data-page="<?php echo $arrCat['page']?>">
        <?php foreach($arrCat['products'] as $objProduct):?>
        <?php 
            $escapeName = html_escape($objProduct->getName());
            $productName = (strlen($escapeName)>17) ? substr_replace($escapeName, "...", 17) : $escapeName;
            $productSlug = $objProduct->getSlug();
            $productPrice = number_format($objProduct->getFinalPrice(), 2,'.',','); 
            $originalPrice = number_format($objProduct->getOriginalPrice(),2,'.',',');
            $percentage = $objProduct->getDiscountPercentage();
            $isPromote = intval($objProduct->getIsPromote());
            $isFreeShipping = $objProduct->getIsFreeShipping();
            $productImagePath = $objProduct->directory .'categoryview/'. $objProduct->imageFileName;
        ?>
            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                <div class="panel-item">
                    <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>">
                        <div class="div-item">
                            <span class="span-img-wrapper" style="background: url(<?=$productImagePath;?>) center no-repeat; background-cover: cover;">
                                <?php if($percentage && $percentage > 0):?>
                                <span class="grid-span-discount-pin"><?PHP echo number_format($percentage,0,'.',',');?>%OFF</span>
                                <?php endif; ?>
                                <center>
                                    <div class="span-img-container">
                                    </div>
                                </center>
                            </span>
                        </div>
                    </a>
                    <div class="div-item-info">
                        <p class="p-item-name">
                            <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>">
                                <?=$productName;?>
                            </a>
                        </p>
                        <p class="p-category">
                            Clothes and Accessories
                        </p>
                        <div class="div-amount">
                            <p class="p-price">
                                <span><s>  </s></span> P <?=$productPrice;?>
                            </p>
                            <?php if($percentage && $percentage > 0):?>
                            <p class="">
                                <span><s> P <?=$originalPrice?> </s></span>
                            </p>
                            <?php endif;?>
                            <center>
                                <button class="btn btn-default-cart">
                                    <span class="fa fa-shopping-cart"></span> BUY NOW
                                </button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>