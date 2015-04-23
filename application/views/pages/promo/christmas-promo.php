<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>12 days of Christmas</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>

        
        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <link rel='stylesheet' type='text/css' href='/assets/css/promo-css.css?ver=<?=ES_FILE_VERSION?>'  media='screen'/>
        <?php else: ?>
            <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.christmas-promo.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
        <?php endif; ?>

        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-33801742-8']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
    </head>
    <body class="animated fadeIn">
        <header class="navbar navbar-static-top">
            <div class="navbar-inner">
                <div class="container">
                    <div id="navigation">
                        <ul class="nav">
                            <li class="logo"><a href="/"><img src="<?php echo getAssetsDomain(); ?>assets/images/promo-images/logo-xmas.png"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <section class="slideshow">
            <div class="container single-image-before"></div>
            <div class="single-image">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/promo-images/header-banner.jpg?ver=<?=ES_FILE_VERSION?>" alt="">
            </div>
            <div class="container single-image-after" style="height=20;padding:10px 0;">
            </div>
        </section>
        <section>
            <div class="container load-animate">
                <h3 class="align-center padding-top-30">Hurry! Check out these selected items from our exclusive sellers!</h3>
                <div class="row-fluid padding-top-30">
                    <div class="span6 box seller-list text-center div-box-con">
                        <?PHP if (isset($featuredVendor['member'])) : ?>
                            <a href="/<?=html_escape($featuredVendor['member']->getSlug())?>" target="_blank">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/promo-images/<?=$featuredVendor['vendorImageUrl']?>?ver=1.0">
                                <p class="box-seller-name"><?=html_escape($featuredVendor['member']->getStoreName())?></p>
                            </a>
                        <?PHP else : ?>
                            <div>
                                VENDOR NOT AVAILABLE
                            </div>
                        <?PHP endif; ?>
                    </div>
                    <div class="span6 box seller-list text-center div-box-con">
                        <?PHP if (isset($product) && $product->getStartPromo()) : ?>
                            <?PHP if ($product->getIsDelete() || $product->getIsSoldOut()) : ?>
                                <div class="dc-tag sold-out"> SOLD </div>
                            <?PHP else : ?>
                                <div class="dc-tag"><?=number_format( $product->getDiscountPercentage(), 0, '.', ',')?>%</div>
                            <?PHP endif; ?>
                            <a href="/item/<?=html_escape($product->getSlug())?>" target="_blank">
                                <img alt ="<?=html_escape($product->getName())?> Image" src="<?php echo isset($featuredVendor['productImageUrl']) ? '/assets/images/promo-images/'. $featuredVendor['productImageUrl'] :  ''?>">
                            </a>
                            <div>
                                <?PHP if ($product->getIsDelete() || $product->getIsSoldOut()) : ?>
                                    <div class="price"> &nbsp; </div>
                                <?PHP else : ?>
                                     <div class="price">Php <?=number_format( $product->getFinalPrice(), 2, '.', ',')?></div>
                                     <div class="timer">
                                        <table id="table-countdown" align="center">
                                            <tr>
                                                <td class="td-time-num">
                                                    <span class="span-time-num">00</span>
                                                    <span class="span-time-label">DAYS</td>
                                                </td>
                                                <td class="td-time-num">
                                                    <span class="span-time-num">00</span>
                                                    <span class="span-time-label">HOURS</td>
                                                </td>
                                                <td class="td-time-num">
                                                    <span class="span-time-num">00</span>
                                                    <span class="span-time-label">MINUTES</td>
                                                </td>
                                                <td class="td-time-num">
                                                    <span class="span-time-num">00</span>
                                                    <span class="span-time-label">SECONDS</td>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                   
                                <?PHP endif; ?>
                            </div>
                        <?PHP else : ?>
                            <div>
                                ITEM NOT AVAILABLE
                            </div>
                        <?PHP endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container load-animate">
                <div class="row-fluid">
                    <div class="span12 box">
                        <div class="row-fluid">
                            <div class="span12 padding-top-70 padding-bottom-70 padding-left-30 padding-right-30">
                                <h1 class="align-center">PROMO MECHANICS</h1>
                                <p >1. One item will be assigned per day, starting from Dec. 14 until Dec. 25. One stock only so <strong>FIRST COME, FIRST SERVE</strong> policy will be observed.</p><br>
                                <p >2. Items will be on a countdown sale. A percentage of the original item price will be off per hour passed until the item is sold. Countdown will start at 12:00 AM each day. So it is possible for anyone to buy the item for up to 99% off!</p><br>
                                <p>Percentage breakdown:</p>
                                <ul>
                                    <li>12AM-1AM – 1% off per hour</li>
                                    <li>1AM-5AM – 2% off per hour</li>
                                    <li>5AM-10AM – 3% off per hour</li>
                                    <li>10AM-2PM- 4% off per hour</li>
                                    <li>2PM-6PM- 5% off per hour</li>
                                    <li>6PM-9PM – 6% off per hour</li>
                                    <li>9PM-12MN – 7% off per hour</li>
                                </ul><br>
                                <p>3. Once the item is bought, it will no longer be available to other buyers.</p><br>
                                <p><strong>PAYMENT METHOD: PayPal, DragonPay or COD</strong></p><br>
                                <p>Also, Watch out for these sellers for the scheduled dates of their sale.</p>
                                <ul>
                                    <li>Barbieforever – Dec. 14, 15, 16</li>
                                    <li>Airborne Technologies – Dec. 17, 23, 24</li>
                                    <li>Michaela – Dec. 19, 20, 21, 22</li>
                                    <li>Sanson Cellshop – Dec. 18 &amp; 25</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <section class="newsletter">
            <div class="container load-animate">
                <div class="row-fluid">
                    <div class="span12 padding-top-30">
                        <h3>Make sure you don't miss interesting events, sale, <br>and more by joining our newsletter program.</h3>
                        <br>
                        <form method="post" id="register" action="/subscribe" class="newsletter-form">
                            <div class="row-fluid">
                                <fieldset>
                                    <?php echo form_open('/subscribe');?>
                                    <input type="text" id="useremail" class="span6" name="email" placeholder="Your e-mail here">
                                    <input type="submit" value="subscribe" class="btn btn-primary" name="subscribe_btn">
                                    <?php echo form_close();?>
                                </fieldset>
                            </div>
                            <div>
                                <div class="newsletter-info-blank">Please enter your email address.</div>
                                <div class="newsletter-info">Thank you for subscribing.</div>
                                <div class="newsletter-validate">Please enter a valid e-mail address</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <a href="#" id="top">&#59235;</a>
            <section class="footer_links">
                <ul>
                    <li><a href="/">Visit Site</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/terms">Terms &amp; Conditions</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/policy">Privacy Policy</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </section>
            <section class="copyright">
                <p>Copyright &copy; 2014 Easyshop.ph<br>All rights reserved.</p>
            </section>
        </footer>
     
        <div id="fb-root"></div>
        
        <div>
            <?php $targetDate = $product->getStartPromo() ? $product->getEnddate() : $product->getStartdate(); ?>
            <?php $remainingTime = $targetDate->getTimestamp() - time(); ?>
            <input id="remainingTime" type="hidden" value='<?php echo $remainingTime?>'/>s
            <input type="hidden" id="dateOfAnnouncement" data-date="<?php echo isset($externalLink[\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK]) ? $externalLink[\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK]->getDateOfAnnouncement()->format("F d, Y") : ''?>">
        </div>

        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <script src="/assets/js/src/vendor/bower_components/jquery.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
            <script src="/assets/js/src/plugins.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
            <script src="/assets/js/src/vendor/modernizr-2.6.2.min.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
            <script src="/assets/js/src/vendor/jquery.plugin.min.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
            <script src="/assets/js/src/vendor/bower_components/jquery.countdown.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
            <script src="/assets/js/src/promo/christmas-promo.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
        <?php else: ?>
            <script src="/assets/js/min/easyshop.christmas-promo.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
        <?php endif; ?>
    </body>
</html>
