<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->

<!--[if (gt IE 9)|!(IE)]><!--><!--<![endif]-->

<head>
    <?php require_once("assets/includes/css.php"); ?>
    <?php require_once("assets/includes/js.php"); ?>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo isset($metadescription)?$metadescription:''?>"  />
    <meta name="keywords" content=""/>
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>
    <?php if(isset($relCanonical)): ?>
        <link rel="canonical" href="<?php echo $relCanonical ?>"/>
    <?php endif; ?>
    <!--[if lt IE 9]>
    <script>
    var e = ("abbr,article,aside,audio,canvas,datalist,details," +
        "figure,footer,header,hgroup,mark,menu,meter,nav,output," +
        "progress,section,time,video").split(',');
    for (var i = 0; i < e.length; i++) {
        document.createElement(e[i]);
    }
    </script>
    <![endif]-->

    <title>
        <?php echo $title?>
    </title>


    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-48811886-1', 'easyshop.ph');
        ga('send', 'pageview');
    </script>
    <!-- End of Google Analytics -->

    <a href="https://plus.google.com/108994197867506780841" rel="publisher"></a>

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <link rel="stylesheet" type="text/css" href='/assets/css/normalize.min.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
        <link rel="stylesheet" type="text/css" href="/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/simple-header-css.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/footer-css.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.header-alt2.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <?php endif; ?>
    
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome/css/font-awesome.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
    <link rel="stylesheet" type="text/css" href="/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>" media='screen'>

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

<header class="simple-header-container">
    <div class="container">
        <div class="row">
            <div class="col-xs-5 header-top-logo">
                <a href="/">
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img_logo.png" alt="Online Shopping">
                </a>
            </div>
            <div class="col-xs-7 login-container">
                <?php if(isset($logged_in) && $logged_in): ?>
                    <div class="login-content">
                        <div class="header-user-msge cart_no" style="display: <?php echo (int)$unreadMessageCount !== 0 ? 'inline-block' : 'none'; ?>">
                            <?php echo $unreadMessageCount; ?>
                        </div>
                        <div class="header-user-profile-photo" style="background: url(<?php echo getAssetsDomain(); ?><?=$user->profileImage;?>) no-repeat center center; background-size:cover;">
                        </div>
                        <a href="/<?php echo html_escape($user->getSlug()); ?>" class="header-username">
                            <?php echo html_escape($user->getUsername()); ?>
                        </a>
                        <div class="header-user-arrow-down">
                        </div>
                        <ul class="header-nav-dropdown">
                            <li>
                                <a class="prevent" href="/me">Dashboard</a>
                            </li>
                            <li>
                                <a class="prevent" href="/me?tab=ongoing">On-going Transactions</a>
                            </li>
                            <li>
                                <a class="prevent" href="/">Go to homepage</a>
                            </li>
                            <li class="nav-dropdown-border">
                                <a class="prevent" href="/me?tab=settings">Settings</a>
                            </li>
                            <li class="nav-dropdown-border pos-rel">
                                <a href="/messages">Messages</a>
                                <div id="unread-messages-count" class="msg_countr message-count-con" style="display: <?php echo (int)$unreadMessageCount !== 0 ? 'inline-block' : 'none'; ?>">
                                    <?php echo $unreadMessageCount; ?>
                                </div>
                            </li>
                            <li class="nav-dropdown-border">
                                <a class="prevent logoutClient" href="/login/logout">Logout</a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="login-content">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img-default-icon-user.jpg" alt="login">
                        <span>
                            <a href="/login">login</a>&nbsp;or&nbsp;
                            <a href="/register">create an account</a>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <input type='hidden' class='es-data' name='is-logged-in' value="<?php echo (isset($logged_in)&&$logged_in) ? 'true' : 'false'?>"/>
    <input type="hidden" id="chatServerConfig" data-host="<?=$chatServerHost?>" data-port="<?=$chatServerPort?>" data-jwttoken="<?php echo html_escape($jwtToken); ?>">
    <input type="hidden" id="listOfFeatureWithRestriction" data-real-time-chat="<?=$listOfFeatureWithRestriction && $listOfFeatureWithRestriction[\EasyShop\Entities\EsFeatureRestrict::REAL_TIME_CHAT] ? 'true' : 'false' ?>">
    
</header>

