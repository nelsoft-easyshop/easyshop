<!DOCTYPE html>
    <head>
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>
        <meta property="og:title" content="EasyShop.ph" />
        <meta property="og:description" content="Our vision is to be the leading Online Shopping website in South East Asia. The mission that EasyShop has is to provide its customer with a Fast and Easy Online shopping of different products available in the market. The up-to-date information provided by the Online Sellers gives a clear picture of the products and the key features, helping Online Buyers make the right purchasing decision. EasyShops’ goal is to be the first website you think about when buying online." />
        <meta property="og:image" content="http://easyshop.ph/assets/images/img_logo.png" />

        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <link href="/assets/css/landingpage/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet">
            <link href="/assets/css/landingpage/bootstrap-responsive.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet">
            <link href="/assets/css/landingpage/mystyle.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet">
            <link href="/assets/css/vendor/bower_components/jquery-ui.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet">
            
        <?php else: ?>
            <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.register-success.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
        <?php endif; ?>
        
        
        <title><?php echo $title;?></title>
        
        <!-- Google Analytics -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-48811886-1', 'easyshop.ph');
          ga('send', 'pageview');
        </script>
        <!-- End of Google Analytics -->
    </head>
    <body>
          <!-- Google Tag Manager -->
         <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KP5F8R"
         height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
         <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
         new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
         j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
         '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
         })(window,document,'script','dataLayer','GTM-KP5F8R');</script>
         <!-- End Google Tag Manager -->
    
        <div class = "container header_bg">
            <div class="row-fluid">
                <div id="himg" class="text-center"> 
                  <a href="/"><img src="<?php echo getAssetsDomain()?>assets/images/landingpage/img_logo.png"></a>
                  <div class="login_btn">
                    <a href="/login" class="">Log In</a>
                  </div>
                </div>
               
                 
                    <p class="text-center p_success txt_success3 c_ylw">
                    
                       <?php echo $content; ?>
                    </p>
                    <p class="p_success txt_success2">
                        <?php echo $sub_content;?>
                    </p>
                    <p class="text-center">
                      <span class="btn btn-warning btn-large"><a href="/sell/step1" style="color:#fff;">Sell Now</a></span>
                    </p>   
                    <p class="text-center txt_success">
                        Like us on and share
                    </p>
                    <p class="text-center img_social_media">
                        <a class="img_sc_1" href="https://www.facebook.com/sharer/sharer.php?s=100&amp;p[url]=http://easyshop.ph&amp;p[images][0]=http://easyshop.ph/assets/images/img_logo.png&amp;p[title]=EasyShop.ph" target="_blank">
                            <img src="<?php echo getAssetsDomain()?>assets/images/img_social_media_facebook.png" alt="facebook">
                        </a>
                        <a class="img_sc_2" href='<?php echo $twitter; ?>' target="_blank">
                            <img src="<?php echo getAssetsDomain()?>assets/images/img_social_media_twitter.png" alt="Twitter">
                        </a>
                    </p>
                    <br/>
               
            </div>
        </div>

        <div class="footer2">
            <div class="container">
                <span class="help-block spnmsg"><br>Copryright &#169; 2014 easyshop.ph. All rights reserved<br></span>
            </div>
        </div>
    </body>

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.js" ></script>
        <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery-ui.js"></script>
        <script type='text/javascript' src='/assets/js/src/landingpage-bootstrap.min.js'></script>
    <?php else: ?>
        <script src="/assets/js/min/easyshop.user_register_subscribe_success.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <?php endif; ?>
    <!-- Form Plugins -->


