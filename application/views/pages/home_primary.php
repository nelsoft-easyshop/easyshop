<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/jquery.bxslider2.css" media='screen'>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/new-homepage.css" media='screen'>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/owl.carousel.css" media='screen'>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/font-awesome/css/font-awesome.css" media='screen'>

<section id="content">       
    <div id="slider-edge">
        <div class="left-side-shadow"></div><!-- End .left-side-shadow -->
        <div class="right-side-shadow"></div><!-- End .left-side-shadow -->
        <div id="bxslider" class="container">
            <ul class="bxslider">
                <li>
                    <div class="slider-item">
                        <img src="<?php echo base_url() ?>assets/images/homeslider/slide1_1.jpg" alt="Slider item 1">
                    </div><!-- End .slider-item -->
                    <div class="slider-item">
                        <img src="<?php echo base_url() ?>assets/images/homeslider/slide2_1.jpg" alt="Slider item 2">                        
                    </div><!-- End .slider-item -->
                </li>
                <li>
                    <div class="slider-item">
                        <img src="<?php echo base_url() ?>assets/images/homeslider/slide3_1.jpg" alt="Slider item 3">
                    </div><!-- End .slider-item -->
                    <div class="slider-item">
                        <img src="<?php echo base_url() ?>assets/images/homeslider/slide4_1.jpg" alt="Slider item 4">                        
                    </div><!-- End .slider-item -->
                </li>
            </ul>
        </div><!-- End #bxslider -->
    </div><!-- #slider-edge -->

    <div class="lg-margin"></div><!-- Space -->
    <div class="container">
        <?php
            include("featured.php");
        ?>
        <?php
            include("promo-ads.php");
        ?>
        <?php
            include("featured-category.php");
        ?>
    </div>
    
    
</section>

<script src="/assets/js/src/vendor/jquery.bxslider1.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/owl.carousel.min.js" type="text/javascript"></script>

<script>
    (function ($)  {
        // BxSlider.js Slider Plugin
        $('.bxslider').bxSlider({
            minSlides: 1,
            maxSlides: 1,
            speed: 1000,
            auto: true,
            pause: 6000,
            prevText : '',
            nextText : ''
        });
         
        $(window).on('load resize', function() {
            var windowWidth = $(window).width(),
            bxSliderWidth = $('#bxslider').width(),
            bxSliderHeight = $('#bxslider').height(),
            shadowWidth = (windowWidth - bxSliderWidth) / 2 ;

            $('.left-side-shadow, .right-side-shadow').css({'width': shadowWidth, 'height': bxSliderHeight});

        });
        
    })(jQuery);

</script>