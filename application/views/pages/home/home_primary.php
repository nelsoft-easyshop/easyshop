<link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider2.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome/css/font-awesome.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/easy-icons/easy-icons.css" media='screen'>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "url": "https://www.easyshop.ph/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://www.easyshop.ph/search/search.html?q_str={search_term}",
    "query-input": "required name=search_term"
  }
}
</script>
<section id="content">
    <div class="main-slider-container">    
    <div id="slider-edge">
        <div class="left-side-shadow"></div>
        <div class="right-side-shadow"></div>
        <div id="bxslider" class="container">
            <ul class="bxslider">
                <?php foreach($homeContent['slider'] as $slideView): ?>
                    <?php echo $slideView; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    </div>   
    <div class="lg-margin"></div>
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
    <?php
        include("featured-brands.php");
    ?>
</section>

<script src="/assets/js/src/vendor/jquery.bxslider1.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/owl.carousel.min.js" type="text/javascript"></script>
<script src="/assets/js/src/newhome.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>

<!--Script for snow storm-->


<?php if( ES_ENABLE_CHRISTMAS_MODS  ): ?>
<script src="/assets/js/src/vendor/snowstorm-min.js" type="text/javascript"></script>
<script>
    snowStorm.snowColor = '#f7f7f7';   // blue-ish snow!?
    snowStorm.flakesMaxActive = 150;    // show more snow on screen at once
    snowStorm.flakesMax = 100;    // show more snow on screen at once
    snowStorm.useTwinkleEffect = true; // let the snow flicker in and out of view
    snowStorm.followMouse = false;
    snowStorm.freezeOnBlur = false;
    snowStorm.zIndex = 99999;
    snowStorm.animationInterval = 33;
</script>
<?php endif; ?>
