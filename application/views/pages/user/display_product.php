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
                <div class="col-lg-3 col-md-3 col-xs-3 thumb">
                    <div class="panel-item">
                        <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>">
                            <div class="div-item">
                            
                            
                                <?php if($secondaryImagePath !== null): ?>
                                <span class="span-img-wrapper" style="background: url(<?=$secondaryImagePath;?>) center no-repeat; background-cover: cover;">
                                    <center>
                                        <div class="span-img-container">
                                        </div>
                                    </center>
                                
                                </span>
                                <?php endif; ?>
                                
                
                                
                                <div class="<?php echo $secondaryImagePath ? 'image-hover-none' : ''; ?> main-image" style="background: url(<?php echo $productImagePath; ?>) no-repeat center; background-size: cover;">
                                    <center>
                                        <div class="span-img-container">
                                        </div>
                                    </center>
                                </div>
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
                                <div style="position: relative; height: 100%; width: 100%;">
                                
                                    <?php if($secondaryImagePath !== null): ?>
                                        <div style="background: url(<?=$secondaryImagePath;?>) center no-repeat; background-size: cover; width: 100%; height:100%;">
                                            <a target="_blank" href="<?php echo '/item/' . $productSlug?>">
                                                <div class="span-space">
                                                    
                                                </div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
              
                                    <div class="<?php echo $secondaryImagePath ? 'main-image-list image-hover-none ' : ''; ?>" style="background: url(<?php echo $productImagePath; ?>) center no-repeat; background-size: cover;">
                                        <a target="_blank" href="<?php echo '/item/' . $productSlug?>">
                                            <div class="span-space">
                                            
                                            </div>
                                        </a>
                                    </div>
                                     <?php if($percentage && $percentage > 0):?>
                                        <span class="span-discount-pin-list" style="z-index: 999"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
                                    <?php endif;?>
                                </div>
                            </td>
                            <td width="55%" class="td-list-item-info">
                                <p class="p-list-item-name">
                                    <?php if(strlen($escapeName)>35): ?>
                                        <a class="color-default" rel="tooltiplist" target="_blank" href="<?php echo '/item/' . $productSlug?>" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $escapeName;?>">
                                            <?php echo substr_replace( $escapeName, "...", 35);?>
                                        </a>
                                    <?php else: ?>
                                        <a class="color-default" target="_blank" href="<?php echo '/item/' . $productSlug?>">
                                            <?php echo $escapeName;?>
                                        </a>
                                    <?php endif;?>
                                </p>
                                <p class="p-list-item-category">
                                    <?php echo $immediateCat?>
                                </p>
                                <div class="div-list-desc-container">
                                    <?php echo $briefDesc;?>
                                </div>
                            </td>
                            <td width="25%" class="td-list-price">
                                <p class="p-list-price"> P <?php echo $productPrice?> </p>
                                <div class="clear"></div>
                                <p class="p-list-discount">
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
        <!-- <?php echo $arrCat['pagination']?> -->
            <div class="clear"></div>
             
            <center>
                <?php echo $arrCat['pagination']?> 
            </center>
    </div>

   
