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
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico" type="image/x-icon"/>
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
 
    <link type="text/css" href='/assets/css/main-style.css' rel="stylesheet" media='screen'/>
    
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/header-css.css" media='screen'>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/bootstrap.css" media='screen'>
    <link type="text/css" href='<?=base_url()?>assets/css/new-homepage.css' rel="stylesheet" media='screen'/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/responsive_css.css" media='screen'>
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
                        <div class="col-md-12">
                            <div class="col-xs-6 col-sm-6 col-md-6 top-header-left">
                                <ul id="top-links" class="clearfix">
                                    <li><a href="/sell/step1" title="Sell an item"><span class="top-icon top-icon-pencil"></span><span class="hide-for-xs">sell an item</span></a></li>
                                    <li><a href="/guide/sell" title="Learn how to sell your items"><span class="top-icon top-icon-user"></span><span class="hide-for-xs">how to sell</span></a></li>
                                    <li><a href="/guide/buy" title="Learn how to make a purchase"><span class="top-icon top-icon-cart"></span><span class="hide-for-xs">how to shop</span></a></li>
                                </ul>
                            </div><!-- End .header-top-left -->
                            <div class="col-xs-6 col-sm-6 col-md-6 top-header-right">
                                <div class="header-text-container pull-right">
                                    <div class="header-link">
                                                                               
                                        <?php if(isset($logged_in) && $logged_in): ?>
                                            <div class="new-user-nav-dropdown">
                                                <div class="login-profile-con">
                                                    <img src="<?=$user_details->profileImage;?>">
                                                </div>
                                                <a href="/<?=$user_details->getSlug();?>" class="header-seller-name"><?=$user_details->getUsername();?></a>
                                                <span class="default-nav-dropdown-arrow">Account Settings</span>
                                                <ul class="default-nav-dropdown">
                                                    <li>
                                                        <a href="/me">Dashboard</a>
                                                    </li>
                                                    <li>
                                                        <a href="/me?me=pending">On-going Transactions</a>
                                                    </li>
                                                    <li class="nav-dropdown-border">
                                                        <a href="/me?me=settings">Settings</a>
                                                    </li>
                                                    <li class="nav-dropdown-border pos-rel">
                                                        <a href="/messages">Messages</a>
                                                        <?php if(intval($msgs['unread_msgs']) !== 0) : ?>
                                                        <div id="unread-messages-count" class="msg_countr message-count-con">
                                                        <?=$msgs['unread_msgs'];?>
                                                        </div>
                                                        <?php endif;?>
                                                    </li>
                                                    <li class="nav-dropdown-border">
                                                        <a class="prevent" href="/sell/step1">Sell an item</a>
                                                    </li>
                                                    <li class="nav-dropdown-border">
                                                        <a class="prevent" href="/login/logout">Logout</a>
                                                    </li>
                                                </ul>
                                                <div class="clear"></div>                                            
                                            </div>
                                        <?php else: ?>
                                            <div class="header-link-login">
                                                <img src="assets/images/img-login-icon.png" alt="login">
                                                <a href="/login">login</a>&nbsp;or&nbsp;
                                                <a href="/register">create an account</a>
                                            </div>
                                        <?php endif; ?>                                           
                                    </div>
                                </div><!-- End .pull-right -->
                            </div><!-- End .header-top-right -->
                        </div><!-- End .col-md-12 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End #header-top -->
            
            <div id="inner-header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 logo-container">
                            <h1 class="logo clearfix">
                                <span>EasyShop.ph</span>
                                <a href="/" title="EasyShop.ph Website">
                                    <img src="/assets/images/img_logo.png" alt="Online Shopping">
                                </a>
                            </h1>
                        </div><!-- End .col-md-5 -->
                        <div class="col-md-9 col-sm-9 col-xs-12 header-inner-right">
                                
                                <div class="header-inner-right-wrapper clearfix">
                                    <div class="dropdown-cart-menu-container pull-right">
                                        <div class="pos-rel mrgn-rght-8">
                                            <div class="header-cart-container">
                                                <a href="/cart" class="header-cart-wrapper">
                                                    <span class="header-cart-items-con ui-form-control">
                                                        <span class="header-cart-item"><?=$cart_size?> item(s)</span> in your cart
                                                    </span>
                                                    <span class="header-cart-icon-con span_bg cart-icon"></span>
                                                </a>
                                                <?PHP if ((intval(sizeof($cart_items))) === 0 ) : ?>
                                                <?PHP else : ?>
                                                <div class="header-cart-item-list">
                                                    <p>Recently add item(s)</p>
                                                    <?PHP for($cnt = sizeof($cart_items) - 1; $cnt > -1 ;$cnt--) : ?>
                                                        <?PHP if(sizeof($cart_items) - 1 === $cnt || sizeof($cart_items) - 1 === $cnt +1) : ?>
                                                            <div class="mrgn-bttm-15">
                                                                <div class="header-cart-item-img">
                                                                    <a href="/item/<?=$cart_items[$cnt]['slug']?>">
                                                                        <span><img src="/<?=$cart_items[$cnt]['imagePath']; ?>thumbnail/<?=$cart_items[$cnt]['imageFile']; ?>" alt="<?=$cart_items[$cnt]['name']?>"></span>
                                                                    </a>
                                                                </div>
                                                                <div class="header-cart-item-con">
                                                                    <a href="/item/<?=$cart_items[$cnt]['slug']?>"><span><?=$cart_items[$cnt]['name']?></span></a>
                                                                    <span>x <?=$cart_items[$cnt]['qty']?></span>
                                                                    <span class="header-cart-item-price">&#8369; <?=$cart_items[$cnt]['price']?></span>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                                                        <?PHP endif; ?>
                                                    <?PHP endfor; ?>
                                                    <div class="header-cart-lower-content">
                                                        <div class="header-cart-shipping-total">
                                                            <p>Items(s) in cart: <span><?=$cart_size?></span></p>
                                                            <p>Total: <span>&#8369; <?=$total?></span></p>
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
                                    <form class="nav-searchbar-inner" accept-charset="utf-8" role="search" name="site-search" method="get" action="/search/search.html" id="nav-searchbar">

                                        <div class="nav-submit-button nav-sprite">
                                            <input type="submit" value="" class="span_bg">
                                        </div>
                                        <div class="nav-searchfield-width">
                                          <div class="search-container">
                                                <!-- <select name="category" class="ui-form-control">
                                                    <option value="1">- All -</option>
                                                    <?php foreach ($parentCategory as $key => $value): ?>
                                                        <option value="<?php echo $value->getIdCat();?>" <?=($this->input->get('category')==$value->getIdCat())?'selected':'';?> ><?php echo $value->getName();?></option>
                                                    <?php endforeach; ?>
                                                </select> -->
                                                <input type="text" name="q_str" class="ui-form-control">
                                            </div>
                                        </div>
                                    </form><!-- End .Search Navigation -->

                                </div><!-- End .header-top-dropdowns -->
                                </div><!-- End .header-inner-right-wrapper -->

                        </div><!-- End .col-md-7 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
                <div class="sticky-header-nav">
                    <div id="main-nav-container">
                        <div class="container">
                            <div  class="sticky-nav-logo-con">
                                <div class="sticky-nav-logo">
                                    <a href="">
                                        <img src="/assets/images/img-sticky-logo.png" alt="Easyshop Logo">
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
                                                <span class="category-icon"></span>
                                                <a href="#" class="mega-menu-title">CATEGORIES</a>
                                                <span class="drop-icon"></span>
                                                <div class="mega-menu clearfix">
                                                    <div class="col-md-80p border-right">                                                    
                                                        <h2>popular categories</h2>
                                                        <div class="mrgn-left-neg-14">
                                                            <?PHP foreach ($homeContent['categoryNavigation']['popularCategory'] as $popularCategory) : ?>
                                                                <div class="col-md-3">
                                                                    <a href="/<?=$popularCategory['category']->getSlug()?>" class="cat-sub-title"><?=$popularCategory['category']->getName()?></a>
                                                                    <ul class="cat-sub-list">
                                                                        <?PHP foreach($popularCategory['subCategory'] as $subCategory) : ?>
                                                                            <li><a href="/<?=$subCategory->getSlug()?>"><?=$subCategory->getName()?></a></li>
                                                                        <?PHP endforeach; ?>
                                                                    </ul>
                                                                </div><!-- End .col-5 -->
                                                            <?PHP endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-20p">
                                                        <h2>other categories</h2>
                                                        <ul class="other-cat-con">
                                                            <?PHP foreach ($homeContent['categoryNavigation']['otherCategory'] as $otherCategory) : ?>
                                                            <li><a href="/<?=$otherCategory->getSlug()?>"><?=$otherCategory->getName()?></a></li>
                                                            <?PHP endforeach; ?>
                                                        </ul>
                                                    </div>    
                                                </div><!-- End .mega-menu -->
                                            </li>
                                            
                                            <li class="mobile-menu-nav-hide">
                                                <a href="javascript:void(0)">NEW ARRIVALS</a>
                                                <ul class="nav-2nd-level">
                                                    <?php foreach( $homeContent['menu']['newArrivals']['arrival']  as $newArrival): ?>

                                                        <li><a href="<?php echo $newArrival['target'] ?>"><?php echo html_escape($newArrival['text']) ?></a></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </li>
                                            <li class="mobile-menu-nav-hide">
                                                <a href="javascript:void(0)">TOP PRODUCTS</a>
                                                <ul class="nav-2nd-level">
                                                    <?php foreach( $homeContent['menu']['topProducts']as $topProduct): ?>
                                                        <?php if($topProduct): ?>
                                                            <li><a href="/item/<?php echo $topProduct->getSlug() ?>"><?php echo html_escape($topProduct->getName()) ?></a></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </li>
                                            <li class="mobile-menu-nav-hide">
                                                <a href="#">TOP SELLERS</a>
                                                <ul class="nav-2nd-level top-seller-list">
                                                    <li>
                                                        <?php foreach($homeContent['menu']['topSellers'] as $topSeller): ?>
                                                            <?php if($topSeller['details']): ?>
                                                                <a href="<?php echo $topSeller['details']->getSlug() ?>">
                                                                    <div class="top-seller-profile-photo">
                                                                        <img src="<?php echo $topSeller['image'] ?>" alt="seller profile photo">
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
                                             <form class="nav-searchbar-inner" accept-charset="utf-8" role="search" name="site-search" method="get" action="/search/search.html" id="nav-searchbar">
                                                <input type="text" name="q_str" class="ui-form-control">
                                                <input type="submit" value="" class="span_bg">
                                            </form>
                                            </div>
                                            <div class="header-cart-container">
                                                <a href="" class="header-cart-wrapper">
                                                    <span class="header-cart-items-con sticky-cart ui-form-control">
                                                        <span class="header-cart-item"><?=$cart_size?> item(s)</span> in your cart
                                                    </span>
                                                    <span class="header-cart-icon-con span_bg cart-icon">
                                                        <span class="cart-item-notif"><?=$cart_size?></span>
                                                    </span>
                                                </a>
                                            <?PHP if ((intval(sizeof($cart_items))) === 0 ) : ?>
                                            <?PHP else : ?>
                                                <div class="sticky-header-cart-item-list">
                                                    <p>Recently add item(s)</p>
                                                    <?PHP for($cnt = sizeof($cart_items) - 1; $cnt > -1 ;$cnt--) : ?>
                                                        <?PHP if(sizeof($cart_items) - 1 === $cnt || sizeof($cart_items) - 1 === $cnt +1) : ?>
                                                            <div class="mrgn-bttm-15">
                                                                <div class="header-cart-item-img">
                                                                    <a href="/item/<?=$cart_items[$cnt]['slug']?>">
                                                                        <span><img src="/<?=$cart_items[$cnt]['imagePath']; ?>thumbnail/<?=$cart_items[$cnt]['imageFile']; ?>" alt="<?=$cart_items[$cnt]['name']?>"></span>
                                                                    </a>
                                                                </div>
                                                                <div class="header-cart-item-con">
                                                                    <a href="/item/<?=$cart_items[$cnt]['slug']?>"><span><?=$cart_items[$cnt]['name']?></span></a>
                                                                    <span>x <?=$cart_items[$cnt]['qty']?></span>
                                                                    <span class="header-cart-item-price">&#8369; <?=$cart_items[$cnt]['price']?></span>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                                                        <?PHP endif; ?>
                                                    <?PHP endfor; ?>
                                                    <div class="header-cart-lower-content">
                                                        <div class="header-cart-shipping-total">
                                                            <p>Items(s) in cart: <span><?=$cart_size?></span></p>
                                                            <p>Total: <span>&#8369; <?=$total?></span></p>
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
                                            <div class="header-text-container pull-right">
                                            <?php if(isset($logged_in) && $logged_in): ?>
                                                <div class="new-user-nav-dropdown">
                                                    <div class="login-profile-con">
                                                        <img src="<?=$user_details->profileImage;?>">
                                                    </div>
                                                    <a href="/<?=$user_details->getSlug();?>" class="header-seller-name"><?=$user_details->getUsername();?></a>
                                                    <span class="default-nav-dropdown-arrow">Account Settings</span>
                                                    <ul class="default-nav-dropdown">
                                                        <li>
                                                            <a href="/me">Dashboard</a>
                                                        </li>
                                                        <li>
                                                            <a href="/me?me=pending">On-going Transactions</a>
                                                        </li>
                                                        <li class="nav-dropdown-border">
                                                            <a href="/me?me=settings">Settings</a>
                                                        </li>
                                                        <li class="nav-dropdown-border pos-rel">
                                                            <a href="/messages">Messages</a>
                                                            <?php if(intval($msgs['unread_msgs']) !== 0) : ?>
                                                            <div id="unread-messages-count" class="msg_countr message-count-con">
                                                            <?=$msgs['unread_msgs'];?>
                                                            </div>
                                                            <?php endif;?>
                                                        </li>
                                                        <li class="nav-dropdown-border">
                                                            <a class="prevent" href="/sell/step1">Sell an item</a>
                                                        </li>
                                                        <li class="nav-dropdown-border">
                                                            <a class="prevent" href="/login/logout">Logout</a>
                                                        </li>
                                                    </ul>
                                                    <div class="clear"></div>                                            
                                                </div>
                                            <?php else: ?> 
                                                <div class="header-link">
                                                    <img src="assets/images/img-login-icon.png" alt="login">
                                                    <a href="/login">login</a>&nbsp;or&nbsp;
                                                    <a href="/register">create an account</a>
                                                </div>
                                            <?php endif; ?>

                                            <!--                                                
                                                <div class="header-link">
                                                    <span class="login-icon user-acct-icon"></span>
                                                    <a href="/login">login</a>&nbsp;or&nbsp;
                                                    <a href="/register">create an account</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div><!-- End .col-md-12 -->
                        </div><!-- End .row -->
                        <div class="clear"></div> 
                    </div><!-- End .container -->
                </div>
                    <div class="clear"></div> 
                </div><!-- End #nav -->
                <div class="clear"></div> 
            </div><!-- End #inner-header -->
            <div class="clear"></div> 
</div><!-- End #header -->

<div class="clear"></div>        
<input type='hidden' class='es-data' name='is-logged-in' value="<?php echo (isset($logged_in)&&$logged_in) ? 'true' : 'false'?>"/>

<script src="/assets/js/src/header.js" type="text/javascript"></script>

