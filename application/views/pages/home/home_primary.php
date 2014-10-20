<link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider2.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/new-homepage.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome/css/font-awesome.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/easy-icons/easy-icons.css" media='screen'>

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
<script src="/assets/js/src/newhome.js" type="text/javascript"></script>
