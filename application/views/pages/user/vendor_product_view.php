<div class="product-paging" data-page="<?php echo $arrCat['page']?>">
    <?php foreach($arrCat['products'] as $objProduct):?>
        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
            <div class="panel-item">
                <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $objProduct->getSlug()?>">
                    <div class="div-item">
                        <span class="span-img-wrapper" style="background: url(<?=base_url()?><?php echo $arrCat['product_images'][$objProduct->getIdProduct()]?>) center no-repeat; background-cover: cover;">
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
