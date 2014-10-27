<link rel="stylesheet" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" type="text/css">


<div class="display-when-desktop" style="position: relative; z-index: 2;">
    <div class="col-md-3 thumbnails_container">
        <div class="thumbnails-img-container">
            <div class="slideshow vertical" data-cycle-fx=carousel data-cycle-timeout=0 data-cycle-next="#next" data-cycle-prev="#prev" data-cycle-carousel-visible=2 data-cycle-carousel-vertical=true>
                <?php foreach($product_images as $image): ?>
                    <a href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: '/<?php echo $image['path']; ?>small/<?php echo $image['file']; ?>',largeimage: '/<?php echo $image['path']; ?><?php echo $image['file']; ?>'}"> <img src='/<?php echo $image['path']; ?>thumbnail/<?php echo $image['file']; ?>'> </a> 
                <?php endforeach;?>

            </div>

            <div class="center">
                <a href="#" id="prev">&lt;&lt; Prev </a>
                <a href="#" id="next"> Next &gt;&gt; </a>
            </div>
        </div>

    </div>
    <div class="col-md-9">
        <div class="prod-gallery-container">
        <div class="prod_con_gal"> 
            <a href="/<?php echo $product_images[0]['path']; ?><?php echo $product_images[0]['file']; ?>" class="jqzoom" rel='gal1'  title="Easyshop.ph" > 
                <img src="/<?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product">
            </a> 
        </div>
        </div>
    </div>
    <br/>
    
</div>

<div class="mobile-product-gallery">
    <div id="mobile-product-gallery" class="owl-carousel">
        <?php foreach($product_images as $image): ?>
            <div> 
                 <img src='/<?php echo $image['path']; ?><?php echo $image['file']; ?>'>
            </div>
        <?php endforeach;?>
    </div>
</div>

<script type="text/javascript" src="/assets/js/src/vendor/jquery.cycle2.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.cycle2.carousel.js "></script>

<script>
     $.fn.cycle.defaults.autoSelector = '.slideshow';

</script>

<script>
    
    var $window = $(window);
    $window.on('load resize', function() {
        $('.owl-item div').each(function () {
            var parentWidth = $(this).width();
            if ($(this).find('img').length) {
                $(this).find('img').css( 'maxWidth', parentWidth)
            }
        });
        
    });

$(document).ready(function() {
 
    $("#mobile-product-gallery").owlCarousel({
        itemsTablet: [768,2],
        itemsMobile : [479,1],
        responsive: true,
        responsiveRefreshRate : 200,
        responsiveBaseWidth: window,
        pagination : true,
        navigation : true,
        navigationText : ["prev","next"],
        scrollPerPage : false,
        dragBeforeAnimFinish : true,
        mouseDrag : true,
        touchDrag : true,
        navigation : true,
    });

});

$(document).ready(function() {
    $('.footer-primary').addClass('footer-secondary');
});
</script>