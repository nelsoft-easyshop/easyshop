
<!-- Load CSS -->
<link rel="stylesheet" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" type="text/css">

<!-- Image File Container -->
<div class="display-when-desktop" style="position: relative; z-index: 2;">

    <div class="col-md-3 thumbnails_container">
        <div class="thumbnails-img-container">
            <div class="jcarousel">
                <ul id="thumblist">
                    <?php foreach($images as $image): ?>
                        <li>
                            <a href="javascript:void(0);" id="image<?=$image->getIdProductImage();?>" data-imageid="<?=$image->getIdProductImage();?>" rel="{gallery: 'gal1', smallimage: '/<?=$image->getDirectory(); ?>small/<?=$image->getFilename(); ?>',largeimage: '/<?=$image->getDirectory(); ?><?=$image->getFilename(); ?>'}">
                                <img src='/<?=$image->getDirectory(); ?>categoryview/<?=$image->getFilename(); ?>'> 
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
                <a href="/<?=$images[0]->getDirectory(); ?><?=$images[0]->getFilename(); ?>" class="jqzoom" rel='gal1'  title="Easyshop.ph" > 
                    <img src="/<?=$images[0]->getDirectory(); ?>small/<?=$images[0]->getFilename(); ?>"  title="product">
                </a> 
            </div>
        </div>
    </div>
    <br/>
</div>

<div class="mobile-product-gallery">
    <div id="mobile-product-gallery" class="owl-carousel">
        <?php foreach($images as $image): ?>
            <div> 
                 <img src='/<?=$image->getDirectory(); ?>small/<?=$image->getFilename(); ?>'>
            </div>
        <?php endforeach;?>
    </div>
</div>

<!-- Load JS -->

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.jcarousel.min.js "></script>
    <script type="text/javascript" src="/assets/js/src/product-page-image-gallery.js "></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.product_image_gallery.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

