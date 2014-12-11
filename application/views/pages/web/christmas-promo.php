<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Early Christmas Sale</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>
        <link href='https://fonts.googleapis.com/css_family=Montserrat:400,700|Open+Sans:400,700,700italic,400italic,300,300italic,600,600italic,800,800italic.html' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css_family=Abril+Fatface.html' rel='stylesheet' type='text/css'>
        <link href='/assets/css/promo-css.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media='screen' type='text/css'/>
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
                            <li class="logo"><a href="/"><img src="/assets/images/promo-images/logo-xmas.png"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <section class="slideshow">

            <div class="container single-image-before"></div>

            <div class="single-image">
                <img src="/assets/images/promo-images/header-xmas.jpg?ver=<?=ES_FILE_VERSION?>" alt="">

            </div>

            <div class="container single-image-after" style="height=20;padding:10px 0;">
                
            </div>

        </section>


        <section>
            <div class="container load-animate">

                <h3 class="align-center padding-top-30">Hurry! Check out these selected items from our exclusive sellers!</h3>

                <div class="row-fluid padding-top-30"> 
                    <div class="span6 box seller-list text-center div-box-con">
                        <a href="/frluxxeproducts" target="_blank">
                            <img src="/assets/images/promo-images/item1.jpg">
                            <p class="box-seller-name">FRLUXXEPRODUCTS</p>
                        </a>
                    </div>
                    <div class="span6 box seller-list text-center div-box-con">
                        <div class="dc-tag"><?=number_format( $product->getDiscountPercentage(), 0, '.', ',')?>%</div>
                        <a href="/<?=html_escape($product->getSlug())?>" target="_blank">
                            <img src="/<?=$image->getProductImagePath()?>">
                        </a>
                        <div>
                            <div class="price">Php <?=number_format( $product->getFinalPrice(), 0, '.', ',')?></div>
                            <div class="timer">
                                <div class="timer-time">
                                    <span>24</span>
                                    <span>60</span>
                                    <span>60</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="timer-txt">
                                    <span>hours</span>
                                    <span>minutes</span>
                                    <span>seconds</span>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
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
                                    <input id="useremail" class="span6" type="email" placeholder="Your e-mail here" name="email" required><br>
                                    <button class="btn btn-primary" type="submit">SUBSRIBE</button>
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
                    <li><a href="https://www.easyshop.ph/">Visit Site</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="https://www.easyshop.ph/terms">Terms &amp; Conditions</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="https://www.easyshop.ph/policy">Privacy Policy</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="https://www.easyshop.ph/contact">Contact</a></li>
                </ul>
            </section>

            <section class="copyright">
                <p>Copyright © 2014 Easyshop.ph<br>All rights reserved.</p>
            </section>
        </footer>

        <div id="fb-root"></div>
        <script src="/assets/js/src/vendor/jquery-1.9.1.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
        <script src="/assets/js/src/plugins.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
        <script src="/assets/js/src/christmas-promo.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
        <script src="/assets/js/src/vendor/modernizr-2.6.2.min.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    </body>
</html>
