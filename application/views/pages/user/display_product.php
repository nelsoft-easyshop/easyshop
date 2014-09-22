
 <!-- PRODUCT DISPLAY -->
    <?php $prodLimit = 12; ?>
    <?php $divCounter = 0; foreach($defaultCatProd as $catId => $arrCat):?>
    <div class="view row row-items grid category-products" id="def-<?php echo $catId?>" data-catId='<?php echo $arrCat['json_subcat']?>' data-catType="2" style="display:<?php echo $divCounter>0 ? 'none' : ''?>">
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
                                            if(strlen($prod_name)>17){
                                                
                                                echo substr_replace( $prod_name, "...", 17);
                                            
                                            }else{
                                                echo $prod_name;
                                            }
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
                                            <span class="fa fa-shopping-cart"></span> ADD TO CART
                                        </button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="clear"></div>
            <div>
                <center>
                    <ul class="pagination pagination-items">
                        <li class="pagination-maxleft"><a href="#bm"><span>&laquo;</span></a></li>
                        <?php for($i=1; $i<=ceil($arrCat['non_categorized_count']/$prodLimit); $i++):?>
                            <li class="pagination-indiv <?php echo $i===1 ? "active" : "" ?>" data-page="<?php echo $i;?>"><a href="#bm"><span><?php echo $i?></span></a></li>
                        <?php endfor;?>
                        <li class="pagination-maxright"><a href="#bm"><span>&raquo;</span></a></li>
                    </ul>
                </center>
            </div>
        <?php endif;?>
    </div>
    <?php $divCounter++; endforeach;?>