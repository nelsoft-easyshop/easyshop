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
            $briefDesc = html_escape($objProduct->getBrief());
            $catObj = $objProduct->getCat();
            $immediateCat = $catObj->getIdCat() === 1 ? html_escape($objProduct->getCatOtherName()) : html_escape($catObj->getName());
        ?>
            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                <div class="panel-item">
                    <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>">
                        <div class="div-item">
                            <span class="span-img-wrapper" style="background: url(<?=$productImagePath;?>) center no-repeat; background-cover: cover;">
                                <?php if($percentage && $percentage > 0):?>
                                <span class="grid-span-discount-pin"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
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
                            <a class="color-default" target="_blank" href="/item/<?=$productSlug; ?>" rel="tooltiplist" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $escapeName;?>">
                                <?=$productName;?>
                            </a>
                        </p>
                        <p class="p-category">
                            Clothes and Accessories
                        </p>
                        <div class="div-amount">
                            <p class="p-price">
                                <span><s>  <?php if($percentage && $percentage > 0):?> P <?=$originalPrice?>   <?php endif;?> </s></span> P <?=$productPrice;?>
                            </p>
                            <center>
                                <a class="btn btn-default-cart" target="_blank" href="/item/<?=$productSlug; ?>">
                                    <span class="fa fa-shopping-cart"></span> BUY NOW
                                </a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-default panel-list-item">
                <table width="100%">
                    <tr>
                        <td width="20%" class="td-list-image" style="background: url(<?=$productImagePath;?>) center no-repeat; background-cover: cover;">
                            <a target="_blank" href="<?php echo base_url() . 'item/' . $productSlug?>">
                                <div class="span-space">
                                    <?php if($percentage && $percentage > 0):?>
                                    <span class="span-discount-pin-list"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
                                    <?php endif;?>
                                </div>
                            </a>
                        </td>
                        <td width="55%" class="td-list-item-info">
                            <p class="p-list-item-name">
                                <?php if(strlen($escapeName)>35): ?>
                                    <a class="color-default" rel="tooltiplist" target="_blank" href="<?php echo base_url() . 'item/' . $productSlug?>" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $escapeName;?>">
                                        <?php echo substr_replace( $escapeName, "...", 35);?>
                                    </a>
                                <?php else: ?>
                                    <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $productSlug?>">
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
                                <span class="fa fa-shopping-cart"></span> BUY NOW
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            
            
        <?php endforeach;?>
        <!-- <?php echo $arrCat['pagination']?> -->
            <div class="clear"></div>
             
            <center>
                <?php echo $arrCat['pagination']?> 
            </center>
           
    </div>

   
