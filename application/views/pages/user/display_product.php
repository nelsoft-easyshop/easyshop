
 <!-- PRODUCT DISPLAY -->
    <?php $prodLimit = 12; ?>
    <?php $divCounter = 0; foreach($defaultCatProd as $catId => $arrCat):?>
    <div class="view row row-items grid category-products <?php echo $divCounter === 0 ? 'active' : ''?>" id="def-<?php echo $catId?>" data-catId='<?php echo $arrCat['json_subcat']?>' data-catType="<?php echo $arrCat['cat_type']?>" style="display:<?php echo $divCounter>0 ? 'none' : ''?>">
        <div class="vendor-select-con">
            <select data-group="<?php echo $catId?>" class="sort_select form-select-default color-default pull-right">
                <option value="1">Default Sorting</option>
                <option value="2">Date Uploaded</option>
                <option value="3">Hot</option>
            </select>
            <div class="clear"></div>
        </div>
        <div class="loading_div" style="text-align:center;display:none;"><img src="assets/images/orange_loader.gif"></div>

        <?php if($arrCat['non_categorized_count'] === 0): ?>
            <span>No items available for this category.</span>
        <?php else:?>
            <div class="product-paging" data-page="1">
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
                                    <p class="p-discount">
                                        <span><s> P <?=$originalPrice?> </s></span>
                                    </p>
                                    
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
            <div class="clear"></div>
            <div id="paginationDiv-<?php echo $catId?>">
                <center>
                    <ul class="pagination pagination-items">
                        <li data-group="<?php echo $catId?>" class="pagination-maxleft"><a href="javascript:void(0)"><span>&laquo;</span></a></li>
                        <?php for($i=1; $i<=ceil($arrCat['non_categorized_count']/$prodLimit); $i++):?>
                            <li data-group="<?php echo $catId?>" class="pagination-indiv <?php echo $i===1 ? "active" : "" ?>" data-page="<?php echo $i;?>">
                                <a href="javascript:void(0)">
                                    <span><?php echo $i?></span>
                                </a>
                            </li>
                        <?php endfor;?>
                        <li data-group="<?php echo $catId?>" class="pagination-maxright"><a href="javascript:void(0)"><span>&raquo;</span></a></li>
                    </ul>
                </center>
            </div>
        <?php endif;?>
    </div>
    <?php $divCounter++; endforeach;?>