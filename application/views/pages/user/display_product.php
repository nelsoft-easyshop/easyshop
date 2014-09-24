
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
                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                        <div class="panel-item">
                            <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $objProduct->getSlug()?>">
                                <div class="div-item">
                                    <span class="span-img-wrapper" style="background: url(<?php echo $objProduct->directory .'categoryview/'.$objProduct->imageFileName;?>) center no-repeat; background-cover: cover;">
                                        <center>
                                            <div class="span-img-container">
                                            </div>
                                        </center>
                                    </span>
                                </div>
                            </a>
                            <div class="div-item-info">
                                <p class="p-item-name">
                                    <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $objProduct->getSlug()?>">
                                        <?php 
                                            $prod_name = html_escape($objProduct->getName());
                                            echo (strlen($prod_name)>17) ? substr_replace($prod_name, "...", 17) : $prod_name;
                                        ?>
                                    </a>
                                </p>
                                <p class="p-category">
                                    Clothes and Accessories
                                </p>
                                <div class="div-amount">
                                    <p class="p-price">
                                        <span><s>  </s></span> P <?php echo html_escape($objProduct->getPrice())?>
                                    </p>
                                    <p class="p-discount">
                                        <span><s> P 1200.00 </s></span>
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