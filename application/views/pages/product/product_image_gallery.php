
<!-- Load CSS -->
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" type="text/css">
<?php else: ?>
    <link rel="stylesheet" href="/assets/css/min-easyshop.product-image-gallery.css?ver=<?=ES_FILE_VERSION?>" type="text/css">
<?php endif; ?>

<!-- Image File Container -->
<div class="display-when-desktop" style="position: relative; z-index: 2;">

    <div class="col-md-3 thumbnails_container">
        <div class="thumbnails-img-container">
            <div class="jcarousel">
                <ul id="thumblist">
                    <?php foreach($images as $image): ?>
                        <li>
                            <a href="javascript:void(0);" id="image<?=$image->getIdProductImage();?>" data-imageid="<?=$image->getIdProductImage();?>" rel="{gallery: 'gal1', smallimage: '<?php echo getAssetsDomain().$image->getDirectory(); ?>small/<?=$image->getFilename(); ?>',largeimage: '<?php echo getAssetsDomain().$image->getDirectory(); ?><?=$image->getFilename(); ?>'}">
                                <img src='<?php echo getAssetsDomain().$image->getDirectory(); ?>categoryview/<?=$image->getFilename(); ?>'> 
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <!-- Controls -->
            <div class="carousel-nav-btn-wrapper">
                <a href="javascript:void(0)" class="jcarousel-control-prev inactive">&lsaquo;</a>
                <a href="javascript:void(0)" class="jcarousel-control-next inactive">&rsaquo;</a>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="prod-gallery-container">
            <div class="prod_con_gal"> 
                <a href="<?php echo getAssetsDomain().$images[0]->getDirectory(); ?><?=$images[0]->getFilename(); ?>" class="jqzoom" rel='gal1'  title="Easyshop.ph" > 
                    <img src="<?php echo getAssetsDomain().$images[0]->getDirectory(); ?>small/<?=$images[0]->getFilename(); ?>"  title="product">
                </a> 
            </div>
        </div>
    </div>
    <br/>
</div>

<div class="mobile-product-gallery">
    <div id="mobile-product-gallery" class="owl-carousel">
        <?php foreach($images as $image): ?>
            <div class="mobile-image-list owl-image<?=$image->getIdProductImage();?>"> 
                 <img src='<?php echo getAssetsDomain().$image->getDirectory(); ?>small/<?=$image->getFilename(); ?>'>
            </div>
        <?php endforeach;?>
    </div>
</div>


<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/bower_components/jquery.jcarousel.js"></script>
    <script type="text/javascript" src="/assets/js/src/product-page-image-gallery.js "></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.product_image_gallery.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

