<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
    <link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.product-promo-category.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>
<section class="bg-cl-fff">
<div class="container" id="main_search_container">
    <center>
        <?php echo $deals_banner; ?>
    </center>

    <div class="clear"></div>
    
    <div id="easytreats" class="deals-pages-easytreats">
        <?php foreach($products as $product): ?>

            <div class="cd_product">

                <?php if($product->getIsSoldOut()): ?>
                    <a href="javascript:void(0)" style='cursor: default;' class="cd_link_con">
                <?php else: ?>
                    <a href="<?= '/item/'.$product->getSlug();?>" class="cd_link_con">
                <?php endif; ?>

                    <?php if(intval($product->getIsPromote()) === 1): ?>
                        
                        <div class="product_buy_con">
                            <span><span class="orange_btn3 <?php echo $product->getIsSoldOut() || !$product->getStartPromo() ? 'disabled' : 'enabled' ;?>">BUY NOW</span></span>
                        </div>

                    <?php else: ?>
                        <div class="product_buy_con">
                            <span><span class="orange_btn3 <?php echo $product->getIsSoldOut() ?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>
                    <?php endif; ?>

                    <?php if($product->getIsSoldOut()): ?>
                        <div class="cd_soldout">
                            <p>SOLDOUT</p>
                        </div>
                    <?php endif; ?>

                    <div>
                        <?php if($product->getDiscountPercentage()  > 0):?>
                            <span class="cd_slide_discount">
                                <span><?php echo number_format($product->getDiscountPercentage(),0,'.',',');?>%<br>OFF</span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <span class="cd_prod_img_con">
                        <img src="<?php echo getAssetsDomain().$product->directory .'categoryview/'. $product->imageFileName; ?>">
                    </span>

                    <h3>
                        <?php echo  es_string_limit(html_escape($product->getName()), 54, '...');?>
                    </h3>
                            
                    <div class="price-cnt">
                        <div class="price">PHP <?php echo number_format($product->getFinalPrice(),2,'.',',');?></div>
                        <?php if($product->getDiscountPercentage() > 0): ?>
                            <div class="discount_price">PHP <?php echo number_format($product->getOriginalPrice(),2,'.',',');?></div>
                        <?php endif; ?>
                    </div>
                                
                                
                    <div class="cd_condition">
                        <b>Condition: </b> <span style='font-weight: 10px;'>
                        <?php echo $product->getIsFreeShipping() ? es_string_limit(html_escape($product->getCondition()),15) : html_escape($product->getCondition());?></span>
                        <?php if($product->getIsFreeShipping()): ?>
                            <span style="float:right;"><span class="span_bg img_free_shipping"></span>
                        <?php endif; ?>	
                    </div>
                        
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>
</section>
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<?php else: ?> 
    <script src="/assets/js/min/easyshop.product_promo_category.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

