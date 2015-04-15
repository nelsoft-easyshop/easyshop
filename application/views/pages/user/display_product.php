    <div class="product-paging" data-page="<?php echo $arrCat['page']?>">

        <div class="product-inner-container">
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
                $secondaryImagePath = null;
                if($objProduct->secondaryImageDirectory && $objProduct->secondaryImageFileName){
                    $secondaryImagePath = $objProduct->secondaryImageDirectory .'categoryview/'. $objProduct->secondaryImageFileName;
                }
                $briefDesc = html_escape($objProduct->getBrief());
                $catObj = $objProduct->getCat();
                $immediateCat = $catObj->getIdCat() === 1 ? html_escape($objProduct->getCatOtherName()) : html_escape($catObj->getName());
            ?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 thumb">
                    <div class="panel-item">
                        <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>">
                            <div class="div-item">
                            
                                <img src="<?php echo getAssetsDomain().$productImagePath; ?>" class="grid-image-primary <?php if($secondaryImagePath !== null): ?>grid-image-has-secondary <?php endif; ?>" >
                                <?php if($secondaryImagePath !== null): ?>
                                    <img src="<?php echo getAssetsDomain().$secondaryImagePath; ?>" class="grid-image-secondary" >
                                <?php endif; ?>

                                <?php if($percentage && $percentage > 0):?>
                                <span class="grid-span-discount-pin"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
                                <?php endif; ?>
                                    
                                
                            </div>
                        </a>
                        <div class="div-item-info">
                            <p class="p-item-name">
                                <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>" rel="tooltiplist" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $escapeName;?>">
                                    <?php echo $productName;?>
                                </a>
                            </p>
                            <p class="p-category">
                                Clothes and Accessories
                            </p>
                            <div class="div-amount">
                                <p class="p-price">
                                    <?php if($percentage && $percentage > 0):?>
                                        <span><s class="discount-value"> P <?=$originalPrice?>  </s> </span> <span style="font-size: 12px;"> P <?=$productPrice;?></span>
                                    <?php else:?>
                                        <span> P <?=$productPrice;?></span>
                                    <?php endif;?>
                                </p>
                                <center>
                                    <a class="btn btn-default-cart" target="_blank" href="/item/<?=$productSlug; ?>">
                                        <span class="fa icon-cart"></span> BUY NOW
                                    </a>
                                </center>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-default panel-list-item">
                    <table width="100%">
                        <tr>
                            <td width="20%" class="td-list-image" style="">
                                <div class="image-container">
                                    <div class="div-item">
                                        <img src="<?php echo getAssetsDomain().$productImagePath; ?>" class="grid-image-primary <?php if($secondaryImagePath !== null): ?>grid-image-has-secondary <?php endif; ?>" >
                                        <?php if($secondaryImagePath !== null): ?>
                                            <img src="<?php echo getAssetsDomain().$secondaryImagePath; ?>" class="grid-image-secondary" >
                                        <?php endif; ?>

                                        <?php if($percentage && $percentage > 0):?>
                                        <span class="grid-span-discount-pin"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
                                        <?php endif; ?>
                                    </div>          
                                </div>
                            </td>
                            <td width="55%" class="td-list-item-info">
                                <p class="p-list-item-name">
                                    <?php if(strlen($escapeName)>50): ?>
                                        <a class="color-default" rel="tooltiplist" target="_blank" href="<?php echo '/item/' . $productSlug?>" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $escapeName;?>">
                                            <?php echo substr_replace( $escapeName, "...", 50);?>
                                        </a>
                                    <?php else: ?>
                                        <a class="color-default" target="_blank" href="<?php echo '/item/' . $productSlug?>">
                                            <?php echo  html_escape($escapeName);?>
                                        </a>
                                    <?php endif;?>
                                </p>
                                <p class="p-list-item-category">
                                    <?php echo $immediateCat;?>
                                </p>
                                <div class="div-list-desc-container">
                                    <?php echo html_escape((strlen($briefDesc)>75) ? substr_replace($briefDesc, "...", 75): $briefDesc) ;?>
                                </div>
                                <div class="actions-list">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <p class="p-list-price p-list-price-mobile"> P <?php echo $productPrice?> </p>
                                            <div class="clear"></div>
                                            <p class="p-list-discount p-list-discount-mobile">
                                                <s><?php if($percentage && $percentage > 0):?> P <?=$originalPrice?>   <?php endif;?> </s>
                                            </p>
                                        </div>
                                        <div class="col-xs-6">
                                             <a class="btn btn-default-1 btn-list-buy-now-mobile" target="_blank" href="/item/<?= html_escape($productSlug); ?>" >
                                                <span class="fa icon-cart"></span> BUY NOW
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td width="25%" class="td-list-price">
                                <?php
                                     $priceFontStyle = strlen((string)$productPrice) > 11 ? "font-size:15px !important;" : "";
                                     $discountFontStyle = strlen((string)$originalPrice) > 11 ? "font-size:13px !important;" : "";
                                 ?>
                                <p class="p-list-price" style="<?php echo $priceFontStyle; ?>"> P <?php echo $productPrice?> </p>
                                <div class="clear"></div>
                                <p class="p-list-discount" style="<?php echo $discountFontStyle; ?>">
                                    <s><?php if($percentage && $percentage > 0):?> P <?=$originalPrice?>   <?php endif;?> </s>
                                </p>
                                <a class="btn btn-default-1" target="_blank" href="/item/<?=$productSlug; ?>" >
                                    <span class="fa icon-cart"></span> BUY NOW
                                </a>
                            </td>
                        </tr>
                    </table>
                    <!--SHIPPING TAG-->
                    <?php if($isFreeShipping): ?>
                    <span class="free-shipping-tag">
                        <i class="fa fa-truck fa-lg"></i> FREE 
                        SHIPPING
                        <span class="free-shipping-tag-tail"><span>
                    </span>
                    <?php endif; ?>
                    <!--END OF SHIPPING TAG-->
                </div>
            <?php endforeach;?>
        </div>
        <div class="clear"></div>
    </div>

