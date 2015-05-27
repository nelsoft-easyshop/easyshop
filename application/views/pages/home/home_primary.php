<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider2.css" media='screen'>
    <link rel="stylesheet" type="text/css" href="/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
    <link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.home-primary.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "url": "<?=base_url();?>",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "<?=base_url();?>search/product?q_str={search_term}",
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
    <?php
        include("sell-an-item-call-to-action.php");
    ?>
</section>


<?php echo $messageboxHtml; ?>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/jquery.bxslider1.min.js" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/owl.carousel.min.js" type="text/javascript"></script>
    <script src="/assets/js/src/newhome.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.home_primary.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
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
