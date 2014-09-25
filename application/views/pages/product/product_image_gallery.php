
<div class="display-when-desktop" style="position: relative; z-index: 2;">
    <div class="cd_promo_badge_con">
        <?php if($product['is_sold_out']): ?>
            <span class="cd_soldout_product_page">
                <img src="/assets/images/img_cd_soldout.png" alt="Sold Out">
            </span>
        <?php endif; ?>

        <?php if(isset($product['percentage']) && $product['percentage'] > 0): ?>
            <span class="cd_slide_discount">
                <span><?php echo  number_format( $product['percentage'],0,'.',',');?>%<br>OFF</span>
            </span>
        <?php endif; ?>
    </div>
    <div class="prod_con_gal"> 
        <a href="/<?php echo $product_images[0]['path']; ?><?php echo $product_images[0]['file']; ?>" class="jqzoom" rel='gal1'  title="Easyshop.ph" > 
            <img src="/<?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product">
        </a> 
    </div>
    <br/>
    <div class="thumbnails_container">
        <div class="jcarousel">
            <ul id="thumblist">
            <?php foreach($product_images as $image): ?>
                <li> <a href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: '/<?php echo $image['path']; ?>small/<?php echo $image['file']; ?>',largeimage: '/<?php echo $image['path']; ?><?php echo $image['file']; ?>'}"> <img src='/<?php echo $image['path']; ?>thumbnail/<?php echo $image['file']; ?>'> </a> </li>
            <?php endforeach;?>
            </ul>
        </div>
        <!-- Controls -->
        <a href="javascript:void(0)" class="jcarousel-control-prev inactive">&lsaquo;</a>
        <a href="javascript:void(0)" class="jcarousel-control-next inactive">&rsaquo;</a>
    </div>
</div>


<div class="display-when-mobile-833">
    <?php if($product['is_sold_out']): ?>
        <span class="cd_soldout_product_page_m pull-right">
            <img class="img-responsive_soldout" src="/assets/images/img_cd_soldout.png" alt="Sold Out" />
        </span>
    <?php endif; ?>
    <?php if(isset($product['percentage']) && $product['percentage'] > 0): ?>
        <span class="cd_slide_discount_m">
            <span><?php echo  number_format( $product['percentage'],0,'.',',');?>%<br>OFF</span>
        </span>
    <?php endif; ?>
    <!----SLIDER--->
    <div id='myCarousel' class='carousel slide'>
        <div class='carousel-inner'>
            <?php foreach($product_images as $image): ?>
            <!--SLIDE NON-ACTIVE CLASS-->
            <div class='item <?php echo (intval($image['is_primary']) === 1) ? 'active' : ''; ?>' >
                <span class="span-container"><center><span class="span-container-img"><img src='/<?php echo $image['path']; ?>/<?php echo $image['file']; ?>' alt='Beach' class='img-responsive img-slider-2' /></span></center></span>
            </div>
            <?php endforeach;?>
        </div>    
        <a class='carousel-control left' href='#myCarousel' data-slide='prev'>
            <span class='glyphicon glyphicon-chevron-left'></span>
        </a>
        <a class='carousel-control right' href='#myCarousel' data-slide='next'>
            <span class='glyphicon glyphicon-chevron-right'></span>
        </a>
    </div>
    <!----END OF SLIDER--->
    
    
    
</div>

<br/>
