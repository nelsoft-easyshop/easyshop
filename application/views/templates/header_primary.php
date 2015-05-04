<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" data-ng-app="easyshopApp">
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->

<!--[if (gt IE 9)|!(IE)]><!--><!--<![endif]-->

<head data-ng-controller="HeaderController">
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

    <title data-ng-bind="pageTitle">
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
        <link rel="stylesheet" type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
        <link rel="stylesheet" type="text/css" href='/assets/css/normalize.min.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
        <link rel="stylesheet" type="text/css" href="/assets/css/header-css.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/responsive_css.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link rel="stylesheet" type="text/css" href="/assets/css/footer-css.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.header-primary.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
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
        
<div id="header">
    <div id="header-top">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 top-header-left">
                    <ul id="top-links" class="clearfix">
                        <li><a href="/sell/step1" title="Sell an item"><i class="icon-item"></i><span class="hide-for-xs">sell an item</span></a></li>
                        <li><a href="/how-to-sell" title="Learn how to sell your items"><i class="icon-how-to-sell"></i><span class="hide-for-xs">how to sell</span></a></li>
                        <li><a href="/how-to-buy" title="Learn how to make a purchase"><i class="icon-how-to-shop"></i><span class="hide-for-xs">how to shop</span></a></li>
                    </ul>
                </div><!-- End .header-top-left -->
                <div class="col-xs-6 col-sm-6 col-md-6 top-header-right">
                    <div class="header-text-container pull-right">
                        <div class="header-link">
                            <?php if(isset($logged_in) && $logged_in): ?>
                                <div class="new-user-nav-dropdown">
                                    <div id="unread-messages-count2" class="msg_countr top-msg-con message-count-con" style="display: <?php echo (int)$unreadMessageCount !== 0 ? 'inline-block' : 'none'; ?>">
                                        <?php echo $unreadMessageCount; ?>
                                    </div>
                                    <div class="login-profile-con" style="background: url(<?php echo getAssetsDomain(); ?><?=$user->profileImage;?>) no-repeat center center; background-size:cover;">
                                    </div>
                                    <a href="/<?=$user->getSlug();?>" class="header-seller-name">
                                        <?php echo html_escape($user->getUsername()); ?>
                                    </a>
                                    <span class="default-nav-dropdown-arrow">Account Settings</span>
                                    <ul class="default-nav-dropdown">
                                        <li>
                                            <a href="/me">Dashboard</a>
                                        </li>
                                        <li>
                                            <a href="/me?tab=ongoing">On-going Transactions</a>
                                        </li>
                                        <li>
                                            <a href="/">Go to homepage</a>
                                        </li>
                                        <li class="nav-dropdown-border">
                                            <a href="/me?tab=settings">Settings</a>
                                        </li>
                                        <li class="nav-dropdown-border pos-rel">
                                            <a href="/messages">Messages</a>
                                            <div id="unread-messages-count" class="msg_countr message-count-con" style="display: <?php echo (int)$unreadMessageCount !== 0 ? 'inline-block' : 'none'; ?>">
                                                <?php echo $unreadMessageCount; ?>
                                            </div>
                                        </li>
                                        <li class="nav-dropdown-border">
                                            <a class="prevent" href="/login/logout">Logout</a>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            <?php else: ?>
                                <a href="/login" class="header-link-login user-login-box">
                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-default-icon-user.jpg" alt="login">
                                    <span>
                                        login &nbsp;or&nbsp; create an account
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div><!-- End .pull-right -->
                </div><!-- End .header-top-right -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End #header-top -->
    

    <div id="inner-header" class="<?php echo ES_ENABLE_CHRISTMAS_MODS ? 'christmas-theme' : '' ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12 logo-container">
                    <h1 class="logo clearfix">
                        <span>EasyShop.ph</span>
                        <a href="/" title="EasyShop.ph Website">
                            <?php if(ES_ENABLE_CHRISTMAS_MODS): ?>
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img_logo_christmas_theme.png" alt="Online Shopping">
                            <?php else: ?>
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img_logo.png" alt="Online Shopping">
                            <?php endif; ?>
                        </a>
                    </h1>
                </div><!-- End .col-md-5 -->
                <div class="col-md-9 col-sm-9 col-xs-12 header-inner-right">
                    <div class="header-inner-right-wrapper clearfix">
                        <div class="dropdown-cart-menu-container pull-right">
                            <div class="pos-rel">
                                <div class="header-cart-container">
                                    <a href="/cart" class="header-cart-wrapper">
                                        <span class="header-cart-items-con ui-form-control">
                                            <span class="header-cart-item"><?=$cartSize?> item(s)</span> in your cart
                                        </span>
                                        <span class="header-cart-icon-con span_bg cart-icon"></span>
                                    </a>
                                    <?PHP if ((intval(sizeof($cartItems))) === 0 ) : ?>
                                    <?PHP else : ?>
                                    <div class="header-cart-item-list">
                                        <p>Recently added item(s)</p>
                                        <?php $cartItemsReversed = array_reverse($cartItems); ?>
                                        <?php for($i = 0 ; $i < 2; $i++): ?>
                                                <?php if(!isset($cartItemsReversed[$i])) break; ?>
                                                <div class="mrgn-bttm-15">
                                                    <div class="header-cart-item-img">
                                                        <a href="/item/<?=$cartItemsReversed[$i]['slug']?>">
                                                            <span><img src="<?php echo getAssetsDomain(); ?><?=$cartItemsReversed[$i]['imagePath']; ?>thumbnail/<?=$cartItemsReversed[$i]['imageFile']; ?>" alt="<?= html_escape($cartItemsReversed[$i]['name']); ?>"></span>
                                                        </a>
                                                    </div>
                                                    <div class="header-cart-item-con">
                                                        <a href="/item/<?=$cartItemsReversed[$i]['slug']?>"><span><?=html_escape($cartItemsReversed[$i]['name'])?></span></a>
                                                        <span>x <?=$cartItemsReversed[$i]['qty']?></span>
                                                        <span class="header-cart-item-price">&#8369; <?=number_format($cartItemsReversed[$i]['price'], 2, '.', ',');?></span>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                        <?php endfor; ?>

                                        <div class="header-cart-lower-content">
                                            <div class="header-cart-shipping-total">
                                                <p>Item(s) in cart: <span><?=$cartSize?></span></p>
                                                <p>Total: <span>&#8369; <?=$cartTotal?></span></p>
                                            </div>
                                            <div class="header-cart-buttons">
                                                <a href="/cart" class="header-cart-lnk-cart">go to cart</a>
                                                <a href="javascript:void(0)" onclick="proceedPayment(this)" class="header-cart-lnk-checkout">checkout</a>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <?PHP endif; ?>
                                </div>
                                
                            </div>
                        </div><!-- End .dropdown-cart-menu-container -->

                        <div class="header-top-dropdowns">
                            <form class="nav-searchbar-inner" accept-charset="utf-8" role="search" name="site-search" method="get" action="/search/search.html" id="nav-searchbar1">

                                <div class="nav-submit-button nav-sprite">
                                    <input type="submit" value="" class="span_bg">
                                </div>
                                <div class="nav-searchfield-width">
                                    <div class="search-container nav-search1">
                                        <input type="text" name="q_str" id="primary-search" autocomplete="off" placeholder="Find what you're looking for." class="ui-form-control main-search-input search-box">
                                    </div>
                                </div>
                            </form><!-- End .Search Navigation -->
                        </div><!-- End .header-top-dropdowns -->
                    </div><!-- End .header-inner-right-wrapper -->
                </div><!-- End .col-md-7 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
        <div class="persistent-header-wrapper">
            <div class="sticky-header-nav">
           <!-- <div id="main-nav-container"> -->
                <div id="main-nav-container" class="<?php echo ES_ENABLE_CHRISTMAS_MODS ? 'persistent-christmas-theme' : '' ?>">
                    <div class="container">
                        <div  class="sticky-nav-logo-con">
                            <div class="sticky-nav-logo">
                                <a href="">
                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-sticky-logo.png" alt="Easyshop Logo">
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 clearfix">
                                
                                <div id="main-nav">
                                    <div id="responsive-nav">
                                        <div id="responsive-nav-button">
                                            <span id="responsive-nav-button-icon"></span>
                                        </div><!-- responsive-nav-button -->
                                    </div>
                                    <ul class="menu clearfix">
                                        <li class="mega-menu-container drop-category">
                                            <span class="icon-category"></span>
                                            <a href="javascript:void(0)" class="mega-menu-title">CATEGORIES</a>
                                            <span class="icon-dropdown"></span>
                                            <span class="icon-dropup"></span>
                                            <div class="mega-menu clearfix">
                                                <div class="col-md-80p border-right">                                                    
                                                    <h2>popular categories</h2>
                                                    <div class="mrgn-left-neg-14">
                                                        <?PHP foreach ($menu['categoryNavigation']['popularCategory'] as $popularCategory) : ?>
                                                            <div class="col-md-3">
                                                                <a href="/category/<?=$popularCategory['category']->getSlug()?>" class="cat-sub-title"><?=$popularCategory['category']->getName()?></a>
                                                                <ul class="cat-sub-list">
                                                                    <?PHP foreach($popularCategory['subCategory'] as $subCategory) : ?>
                                                                        <li><a href="/category/<?=$subCategory->getSlug()?>"><?=$subCategory->getName()?></a></li>
                                                                    <?PHP endforeach; ?>
                                                                </ul>
                                                            </div><!-- End .col-5 -->
                                                        <?PHP endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-20p">
                                                    <h2>other categories</h2>
                                                    <ul class="other-cat-con">
                                                        <?PHP foreach ($menu['categoryNavigation']['otherCategory'] as $otherCategory) : ?>
                                                        <li><a href="/category/<?=$otherCategory->getSlug()?>"><?=$otherCategory->getName()?></a></li>
                                                        <?PHP endforeach; ?>
                                                    </ul>
                                                </div>    
                                            </div><!-- End .mega-menu -->
                                        </li>
                                        
                                        <li class="mobile-menu-nav-hide">
                        
                                            <a href="javascript:void(0)">NEW ARRIVALS</a>
                                     
                                            <ul class="nav-2nd-level">
                                                <?php foreach( $menu['menu']['newArrivals']  as $newArrival): ?>
                                                    <li><a href="<?php echo $newArrival['target'] ?>"><?php echo html_escape($newArrival['text']) ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                        <li class="mobile-menu-nav-hide">
                                            <a href="javascript:void(0)">TOP PRODUCTS</a>
                                            <ul class="nav-2nd-level">
                                                <?php foreach( $menu['menu']['topProducts']as $topProduct): ?>
                                                    <?php if($topProduct): ?>
                                                        <li><a href="/item/<?php echo $topProduct->getSlug() ?>"><?php echo html_escape($topProduct->getName()) ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                        <li class="mobile-menu-nav-hide">
                                            <a href="javascript:void(0)">TOP SELLERS</a>
                                            <ul class="nav-2nd-level top-seller-list">
                                                <li>
                                                    <?php foreach($menu['menu']['topSellers'] as $topSeller): ?>
                                                        <?php if($topSeller['details']): ?>
                                                            <a href="/<?php echo $topSeller['details']->getSlug() ?>">
                                                                <div class="top-seller-profile-photo">
                                                                    <img src="<?php echo getAssetsDomain().'.'.$topSeller['image'] ?>" alt="seller profile photo">
                                                                </div>
                                                                <div class="top-seller-name">
                                                                    <?php $storeName = $topSeller['details']->getStoreName(); ?>
                                                                    <?php echo html_escape(($storeName && strlen(trim($storeName)) > 0) ? $storeName : $topSeller['details']->getUsername()); ?>
                                                                </div>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    
                                                </li>                                                    
                                            </ul>
                                        </li>
                                        <li class="mobile-menu-nav-hide"><a href="/deals">EASY TREATS</a>
                                        </li>
                                    </ul>
                                    
                                    <div class="sticky-search-cart-wrapper">
                                        <div class="sticky-search-wrapper">
                                            <form class="nav-searchbar-inner nav-search2" accept-charset="utf-8" role="search" name="site-search" method="get" action="/search/search.html" id="nav-searchbar2">
                                                <input type="text" name="q_str" autocomplete="off" id="primary-search2" placeholder="Find what you're looking for." class="ui-form-control main-search-input search-box">
                                                <input type="submit" value="" class="span_bg">
                                            </form>
                                        </div>
                                        <div class="header-cart-container">
                                            <a href="/cart" class="header-cart-wrapper">
                                                <span class="header-cart-items-con sticky-cart ui-form-control">
                                                    <span class="header-cart-item"><?=$cartSize?> item(s)</span> in your cart
                                                </span>
                                                <span class="header-cart-icon-con span_bg cart-icon">
                                                    <span class="cart-item-notif"><?=$cartSize?></span>
                                                </span>
                                            </a>
                                        <?PHP if ((intval(sizeof($cartItems))) === 0 ) : ?>
                                        <?PHP else : ?>
                                            <div class="sticky-header-cart-item-list">
                                                <p>Recently added item(s)</p>
                                                <?php for($i = 0 ; $i < 2; $i++): ?>
                                                        <?php if(!isset($cartItemsReversed[$i])) break; ?>
                                                        <div class="mrgn-bttm-15">
                                                            <div class="header-cart-item-img">
                                                                <a href="/item/<?=$cartItemsReversed[$i]['slug']?>">
                                                                    <span><img src="<?php echo getAssetsDomain(); ?><?=$cartItemsReversed[$i]['imagePath']; ?>thumbnail/<?=$cartItemsReversed[$i]['imageFile']; ?>" alt="<?=html_escape($cartItemsReversed[$i]['name'])?>"></span>
                                                                </a>
                                                            </div>
                                                            <div class="header-cart-item-con">
                                                                <a href="/item/<?=$cartItemsReversed[$i]['slug']?>"><span><?=html_escape($cartItemsReversed[$i]['name']);?></span></a>
                                                                <span>x <?=$cartItemsReversed[$i]['qty']?></span>
                                                                <span class="header-cart-item-price">&#8369; <?=number_format($cartItemsReversed[$i]['price'], 2, '.', ',');?></span>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                <?php endfor; ?>
        
                                                <div class="header-cart-lower-content">
                                                    <div class="header-cart-shipping-total">
                                                        <p>Item(s) in cart: <span><?=$cartSize?></span></p>
                                                        <p>Total: <span>&#8369; <?=$cartTotal?></span></p>
                                                    </div>
                                                    <div class="header-cart-buttons">
                                                        <a href="/cart" class="header-cart-lnk-cart">go to cart</a>
                                                        <a href="javascript:void(0)" onclick="proceedPayment(this)" class="header-cart-lnk-checkout">checkout</a>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        <?PHP endif; ?>
                                        </div>
                                        <div class="header-text-container">
                                        <?php if(isset($logged_in) && $logged_in): ?>
                                            <div class="new-user-nav-dropdown">
                                                <div id="unread-messages-count3" class="msg_countr top-msg-con message-count-con" style="display: <?php echo (int)$unreadMessageCount !== 0 ? 'inline-block' : 'none'; ?>">
                                                    <?php echo $unreadMessageCount; ?>
                                                </div>
                                                <div class="login-profile-con" style="background: url(<?php echo getAssetsDomain(); ?><?=$user->profileImage;?>) no-repeat center center; background-size:cover;">
                                                </div>
                                                <a href="/<?=$user->getSlug();?>" class="header-seller-name">
                                                    <?php echo html_escape($user->getUsername()) ;?>
                                                </a>
                                                <span class="default-nav-dropdown-arrow">Account Settings</span>
                                                <ul class="default-nav-dropdown">
                                                    <li>
                                                        <a href="/me">Dashboard</a>
                                                    </li>
                                                    <li>
                                                        <a href="/me?tab=ongoing">On-going Transactions</a>
                                                    </li>
                                                    <li>
                                                        <a href="/?view=basic">Go to homepage</a>
                                                    </li>
                                                    <li class="nav-dropdown-border">
                                                        <a href="/me?tab=settings">Settings</a>
                                                    </li>
                                                    <li class="nav-dropdown-border pos-rel">
                                                        <a href="/messages">Messages</a>
                                                        <div id="unread-messages-count" class="msg_countr message-count-con" style="display: <?php echo (int)$unreadMessageCount !== 0 ? 'inline-block' : 'none'; ?>">
                                                            <?php echo $unreadMessageCount; ?>
                                                        </div>
                                                        
                                                    </li>
                                                    <li class="nav-dropdown-border">
                                                        <a class="prevent" href="/login/logout">Logout</a>
                                                    </li>
                                                </ul>
                                                <div class="clear"></div>
                                            </div>
                                        <?php else: ?> 
                                            <a href="/login" class="header-link">
                                                <div class="user-login-box">
                                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-default-icon-user.jpg" alt="login">
                                                    login &nbsp;or&nbsp; create an account
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div><!-- End .col-md-12 -->
                    </div><!-- End .row -->
                    <div class="clear"></div> 
                </div><!-- End .container -->
            </div>
        </div>
        <div class="clear"></div> 
        </div><!-- End #nav -->
        <div class="clear"></div> 
    </div><!-- End #inner-header -->
    <div class="clear"></div> 
</div><!-- End #header -->

<div class="clear"></div>        

<input type='hidden' class='es-data' name='is-logged-in' value="<?php echo (isset($logged_in)&&$logged_in) ? 'true' : 'false'?>"/>
<input type="hidden" id="chatServerConfig" data-host="<?=$chatServerHost?>" data-port="<?=$chatServerPort?>" data-jwttoken="<?php echo html_escape($jwtToken); ?>">
<input type="hidden" id="isRealTimeChatAllowed" data-real-time-chat="<?=$listOfFeatureWithRestriction && $listOfFeatureWithRestriction[\EasyShop\Entities\EsFeatureRestrict::REAL_TIME_CHAT] ? 'true' : 'false' ?>">

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/bower_components/jquery.scrollUp.js"></script>
    <script src="/assets/js/src/vendor/bower_components/jquery.auto-complete.js" type="text/javascript"></script>
    <script src="/assets/js/src/header.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.header_primary.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

