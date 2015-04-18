<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Estudyantrepreneur</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="/assets/css/promo-css.css">
        <script src="/assets/js/src/vendor/modernizr-2.6.2.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-33801742-8']);
            _gaq.push(['_trackPageview']);

            (function ($) {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                
                $(document).ready(function() {
                    $('html, body').animate({
                        scrollTop: $(".resultContainer").offset().top
                    }, 300);
                });
            }(jQuery));

        </script>
    </head>
    <body class="animated fadeIn">
        <header>
            <div class="container">
                <div class="logo">
                    <a href="/">
                        <img src="<?=getAssetsDomain()?>assets/images/promo-images/easyshop_logo.png">
                    </a>
                </div>
            </div>
        </header>

        <section class="slideshow">

            <div class="container single-image-before"></div>

            <div class="single-image">
                <img src="<?=getAssetsDomain()?>assets/images/promo-images/ESbanner.jpg" alt="">

            </div>

            <div class="container single-image-after" style="height=20;padding:10px 0;">
                
            </div>

        </section>


        <section class="ty-comment-section">
            <div class="container load-animate">
                <div class="box resultContainer">
                    <div id="<?php echo  $result['isSuccessful'] === true ? 'success' : 'failed' ?>">
                        <div class="padding-top-70 padding-bottom-70 padding-left-30 padding-right-30">
                            <?php if($result['isSuccessful'] === true): ?>
                                <h3>
                                    <b>THANK YOU FOR VOTING!</b>
                                </h3>
                                <p class="text-align-justify">
                                    We have already counted your vote.
                                </p>
                            <?php else: ?>  
                                <h3>
                                    <b><?php echo $result['errorMsg']; ?></b>
                                </h3>
                            <?php endif; ?>
                            <br/>
                            <br/>
                            <p class="text-align-justify">
                                To know your current standing, you may email or call:
                            </p>
                            <br>
                            <p class="text-align-justify">
                                Kevin Dela Cruz - kevin.delacruz@easyshop.ph / 09152718002
                            </p>
                            <p class="text-align-justify">
                                Anthony Romero - anthony.romero@easyshop.ph / 09434937320
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="current-stats-section">
            <div class="container load-animate">
                <!-- standings container -->
            </div>
        </section>

        <section class="newsletter">
            <div class="container load-animate">
                <div class="row-fluid">
                    <div class="span12 padding-top-30">
                        <h3>Make sure you don't miss interesting events, sale, 
                            <br>and more by joining our newsletter program.
                        </h3>
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
                            <div class="newsletter-info-blank">Please enter your email address.</div>
                            <div class="newsletter-info">Thank you for subscribing.</div>
                            <div class="newsletter-validate">Please enter a valid e-mail address</div>
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
                <p>Copyright © 2015 Easyshop.ph<br>All rights reserved.</p>
            </section>
        </footer>

        <script src="/assets/js/src/plugins.js"></script>
        <script src="/assets/js/src/promo/christmas-promo.js"></script>

    </body>
</html>
