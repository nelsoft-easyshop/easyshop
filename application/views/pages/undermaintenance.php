<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8" />
      <title>Easyshop.ph | Under Maintenance</title>
      <meta name="description" content="" />
      <meta name="keywords" content=""/>
      <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>

      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-48811886-1', 'easyshop.ph');
      ga('send', 'pageview');
    </script>

    <style>
        .bg_mid{
            background-color: #fff;
            background-image: url('<?php echo getAssetsDomain(); ?>assets/images/landingpage/bg_mid.png'), url('<?php echo getAssetsDomain(); ?>assets/images/landingpage/bg_mid_back.png');
            background-repeat: no-repeat, repeat;
            background-position: top center, top left;
            min-height: 45.063em;
            padding-bottom: 1px;
            color: #fff;
            text-shadow: 0px 1px 0px rgba(17, 17, 17, 1);
        }
        
        section{
            margin: 0;
            padding: 0;
            border: 0;
        }
        
        .temporarily_down{
            margin-top: 80px;
            position: relative;
        }
        
        body{
            margin: 0px;
            font: normal 100%/1.4 Arial, sans-serif;
        }
        
        .span_bg{
            background-image: url('<?php echo getAssetsDomain(); ?>assets/images/global_sprite.png');
            background-repeat: no-repeat;
            display: inline-block;
            vertical-align: middle;
        }
        
        .logo{
            background-position: -4px -6px;
            height: 3.9em;
            width: 200px;
            position: relative;
            z-index: 9999;
        }

        
        .header {
            width: auto;
            max-width: 85%;
            margin: 0 auto;
        }
        
        div.content_container{
            position:absolute; 
            text-align: center; 
            width: 100%;
            font-size: 16px;
        }
        
        div.content_container .highlight{
            font-size: 30px;
            color: #fcff00;
        }


        
        
    </style>
    
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
         
    
    
        <header>
           <div class='header'>
               <div class="logo_con">
                    <span class="span_bg logo"></span>
                </div>
           </div>
        </header>

        <section class='bg_mid'>
            <div class='content_container'>
                <img class='temporarily_down' src = '<?php echo getAssetsDomain() ?>assets/images/under_maintenance.png'/>
                <br/> <br/>
                We are performing scheduled maintenance.<br/><br/>
                <span class='highlight'>We'll be back very soon. </span><br/><br/>
                We apologize for the inconvenience and appreciate your patience. <br/> 
                Thank you for using Easyshop.ph. <br/> <br/>
                For updates, please check our facebook and twitter pages. <br/><br/>
                <p class="img_social_media">
                    <a style='margin-right:50px;' href="<?php echo $facebook; ?>" target="_blank">
                        <img src="<?php echo getAssetsDomain() ?>assets/images/img_social_media_facebook.png" alt="facebook">
                    </a>
                    <a class="" href='<?php echo $twitter; ?>' target="_blank">
                        <img src="<?php echo getAssetsDomain() ?>assets/images/img_social_media_twitter.png" alt="Twitter">
                    </a>
                </p>
                <br>
                <p><span style='font-size: 12px;'>Copryright &#169; 2014 easyshop.ph. All rights reserved<br><span><p>
            </div>
        </section>
        

    </body>

</html>
