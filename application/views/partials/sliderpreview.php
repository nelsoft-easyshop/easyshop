<?php require_once("assets/includes/js.php"); ?>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider2.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>" media='screen'>

    <div class="main-slider-container" style="padding: 50px !important;">    
    <div id="slider-edge">
        <div class="left-side-shadow"></div>
        <div class="right-side-shadow"></div>
        <div id="bxslider">
            <ul class="bxslider">
                <?php foreach($homeContent['slider'] as $slideView): ?>
                    <?php echo $slideView; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    </div>   
    <div class="lg-margin"></div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/jquery.bxslider1.min.js" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/owl.carousel.min.js" type="text/javascript"></script>
    <script src="/assets/js/src/newhome.js" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.partial_sliderpreview.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
