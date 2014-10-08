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
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/responsive_css.css" media='screen'>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/header-css.css" media='screen'>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/bootstrap.css" media='screen'>
    <link type="text/css" href='<?=base_url()?>assets/css/new-homepage.css' rel="stylesheet" media='screen'/>
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
                            <div class="header-top-left">
                                <ul id="top-links" class="clearfix">
                                    <li><a href="#" title="My Wishlist"><span class="top-icon top-icon-pencil"></span><span class="hide-for-xs">sell an item</span></a></li>
                                    <li><a href="#" title="My Account"><span class="top-icon top-icon-user"></span><span class="hide-for-xs">how to sell</span></a></li>
                                    <li><a href="#" title="My Cart"><span class="top-icon top-icon-cart"></span><span class="hide-for-xs">how to shop</span></a></li>
                                </ul>
                            </div><!-- End .header-top-left -->
                            <div class="header-top-right">
                                <div class="header-text-container pull-right">
                                    <div class="header-link">
                                        <span class="login-icon user-acct-icon"></span>
                                        <a href="#">login</a>&nbsp;or&nbsp;
                                        <a href="#">create an account</a>
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
                        <div class="col-md-4 col-sm-4 col-xs-12 logo-container">
                            <h1 class="logo clearfix">
                                <span>EasyShop.ph</span>
                                <a href="#" title="EasyShop.ph Website">
                                    <img src="<?php echo base_url() ?>assets/images/img_logo.png" alt="Online Shopping">
                                </a>
                            </h1>
                        </div><!-- End .col-md-5 -->
                        <div class="col-md-8 col-sm-8 col-xs-12 header-inner-right">
                                
                                <div class="header-inner-right-wrapper clearfix">
                                    <div class="dropdown-cart-menu-container pull-right">
                                        <div class="pos-rel mrgn-rght-8">
                                            <div class="header-cart-container">
                                                <a href="" class="header-cart-wrapper">
                                                    <span class="header-cart-items-con">
                                                        <span class="header-cart-item">2 item(s)</span> in your cart
                                                    </span>
                                                    <span class="header-cart-icon-con span_bg cart-icon"></span>
                                                </a>
                                                <div class="header-cart-item-list">
                                                    <p>Recently add item(s)</p>
                                                    <div class="mrgn-bttm-15">
                                                        <div class="header-cart-item-img">
                                                            <a href="">
                                                                <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                                            </a>
                                                        </div>
                                                        <div class="header-cart-item-con">
                                                            <a href=""><span>Doraemon - blue</span></a>
                                                            <span>x 1</span>
                                                            <span class="header-cart-item-price">&#8369; 450.00</span>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="mrgn-bttm-15">
                                                        <div class="header-cart-item-img">
                                                            <a href="">
                                                                <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                                            </a>
                                                        </div>
                                                        <div class="header-cart-item-con">
                                                            <a href=""><span>Doraemon - blue</span></a>
                                                            <span>x 1</span>
                                                            <span class="header-cart-item-price">&#8369; 450.00</span>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="header-cart-lower-content">
                                                        <div class="header-cart-shipping-total">
                                                            <p>Shipping: <span>&#8369; 50.00</span></p>
                                                            <p>Total: <span>&#8369; 100,500.00</span></p>
                                                        </div>
                                                        <div class="header-cart-buttons">
                                                            <a href="" class="header-cart-lnk-cart">go to cart</a>
                                                            <a href="" class="header-cart-lnk-checkout">checkout</a>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div><!-- End .dropdown-cart-menu-container -->

                                <div class="header-top-dropdowns">
                                    <form class="nav-searchbar-inner" ,="" accept-charset="utf-8" role="search" name="site-search" method="get" action="/s/ref=nb_sb_noss" id="nav-searchbar">

                                    <div class="nav-submit-button nav-sprite">
                                        <button id="quick-search" class="btn btn-custom" type="submit"></button>
                                    </div>

                                <div class="nav-searchfield-width">
                                  <div id="nav-iss-attach">
                                    <input class="ui-form-control" type="text" autocomplete="off" name="field-keywords" value="" title="Search For" id="twotabsearchtextbox">
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
                                        <img src="<?=base_url()?>assets/images/img-sticky-logo.png" alt="Easyshop Logo">
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 clearfix">
                                    
                                    <div id="main-nav">
                                        <div id="responsive-nav">
                                            <div id="responsive-nav-button">
                                                Menu <span id="responsive-nav-button-icon"></span>
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
                                                            <div class="col-md-3">
                                                                <a href="" class="cat-sub-title">Clothes &amp; Accessories</a>
                                                                <ul class="cat-sub-list">
                                                                    <li><a href="">Women Clothing</a></li>
                                                                    <li><a href="">Men Clothing</a></li>
                                                                    <li><a href="">Babies Clothing</a></li>
                                                                    <li><a href="">Unisex Accessories</a></li>
                                                                </ul>
                                                            </div><!-- End .col-5 -->
                                                            <div class="col-md-3">
                                                                <a href="" class="cat-sub-title">Electronics &amp; Gadget</a>
                                                                <ul class="cat-sub-list">
                                                                    <li><a href="">Cameras &amp; Imaging</a></li>
                                                                    <li><a href="">Computer &amp; Networking</a></li>
                                                                    <li><a href="">Tablets</a></li>
                                                                    <li><a href="">Mobile Phones</a></li>
                                                                </ul>
                                                            </div><!-- End .col-5 -->
                                                            <div class="col-md-3">
                                                                <a href="" class="cat-sub-title">Jewelry &amp; Watches</a>
                                                                <ul class="cat-sub-list">
                                                                    <li><a href="">Watches</a></li>
                                                                    <li><a href="">Fasion Jewelry</a></li>
                                                                </ul>
                                                            </div><!-- End .col-5 -->
                                                            <div class="col-md-3">
                                                                <a href="" class="cat-sub-title">Health &amp; Beauty</a>
                                                                <ul class="cat-sub-list">
                                                                    <li><a href="">Makeup</a></li>
                                                                    <li><a href="">Fragrances</a></li>
                                                                    <li><a href="">Skin Care</a></li>
                                                                </ul>
                                                            </div><!-- End .col-5 -->
                                                        </div>                                                    
                                                    </div>
                                                    <div class="col-md-20p">
                                                        <h2>other categories</h2>
                                                        <ul class="other-cat-con">
                                                            <li><a href="">Food &amp; Beverages</a></li>
                                                            <li><a href="">Toys, Hobbies &amp; Collections</a></li>
                                                            <li><a href="">Books, Music &amp; Movies</a></li>
                                                            <li><a href="">Home, Furniture &amp; Garden</a></li>
                                                            <li><a href="">Business &amp; Industrial</a></li>
                                                        </ul>
                                                    </div>    
                                                </div><!-- End .mega-menu -->
                                            </li>
                                            
                                            <li class="mobile-menu-nav-hide">
                                                <a href="#">NEW ARRIVALS</a>
                                                <ul class="nav-2nd-level">
                                                    <li><a href="#">Male</a></li>
                                                    <li><a href="#">Female</a></li>
                                                    <li><a href="c#">Gadgets</a>
                                                        <ul class="nav-3rd-level">
                                                            <li><a href="#">Camera</a></li>
                                                            <li><a href="#">Phone</a></li>
                                                            <li><a href="#">Computer</a></li>
                                                            <li><a href="#">Accessories</a></li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="#">Children</a></li>
                                                    <li><a href="#">Toys</a></li>
                                                    <li><a href="#">Auto Supplies</a></li>
                                                    <li><a href="#">Food &amp; Beverages</a></li>
                                                    <li><a href="#">Beers</a></li>
                                                </ul>
                                            </li>
                                            <li class="mobile-menu-nav-hide"><a href="#">TOP PRODUCTS</a>
                                            </li>
                                            <li class="mobile-menu-nav-hide"><a href="#">TOP SELLERS</a></li>
                                            <li class="mobile-menu-nav-hide"><a href="#">EASY TREATS</a>
                                                <ul class="nav-2nd-level">
                                                    <li><a href="#">Hot Deals</a></li>
                                                </ul>
                                            </li>
                                            <li class="mobile-menu-nav-hide"><a href="#">EASY DEALS</a></li>
                                        </ul>
                                        
                                        <div class="sticky-search-cart-wrapper">
                                            <div class="sticky-search-wrapper">
                                                <input type="text" class="ui-form-control">
                                                <button id="quick-search" class="" type="submit"></button>
                                            </div>
                                            <div class="header-cart-container">
                                                <span class="header-cart-items-con sticky-cart">
                                                    <span class="header-cart-item">2 item(s)</span> in your cart
                                                </span>
                                                <span class="header-cart-icon-con span_bg cart-icon"></span>
                                                <div class="sticky-header-cart-item-list">
                                                    <p>Recently add item(s)</p>
                                                    <div class="mrgn-bttm-15">
                                                        <div class="header-cart-item-img">
                                                            <a href="">
                                                                <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                                            </a>
                                                        </div>
                                                        <div class="header-cart-item-con">
                                                            <a href=""><span>Doraemon - blue</span></a>
                                                            <span>x 1</span>
                                                            <span class="header-cart-item-price">&#8369; 450.00</span>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="mrgn-bttm-15">
                                                        <div class="header-cart-item-img">
                                                            <a href="">
                                                                <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                                            </a>
                                                        </div>
                                                        <div class="header-cart-item-con">
                                                            <a href=""><span>Doraemon - blue</span></a>
                                                            <span>x 1</span>
                                                            <span class="header-cart-item-price">&#8369; 450.00</span>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="header-cart-lower-content">
                                                        <div class="header-cart-shipping-total">
                                                            <p>Shipping: <span>&#8369; 50.00</span></p>
                                                            <p>Total: <span>&#8369; 100,500.00</span></p>
                                                        </div>
                                                        <div class="header-cart-buttons">
                                                            <a href="" class="header-cart-lnk-cart">go to cart</a>
                                                            <a href="" class="header-cart-lnk-checkout">checkout</a>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="header-text-container pull-right">                                               
                                                <div class="header-link">
                                                    <span class="login-icon user-acct-icon"></span>
                                                    <a href="#">login</a>&nbsp;or&nbsp;
                                                    <a href="#">create an account</a>
                                                </div>
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

<script src="<?=base_url()?>assets/js/src/vendor/main.js" type="text/javascript"></script>
<script>


    (function ($) { 
        
        $(document).ready(function(){

            var $user_nav_dropdown = $(".user-nav-dropdown");
            var $nav_dropdown = $("ul.nav-dropdown");

            $(document).mouseup(function (e) {

                if (!$nav_dropdown.is(e.target) // if the target of the click isn't the container...
                    && $nav_dropdown.has(e.target).length === 0) // ... nor a descendant of the container
                {
                   $nav_dropdown.hide(1);
                }

            });

            $user_nav_dropdown.click(function() {
                $nav_dropdown.show();
            });

            
        
            var navigation = responsiveNav(".nav-collapse");
            var srchdropcontent= $('#main_search_drop_content');
            
            $('#main_search').focus(function() {
                if(srchdropcontent.find("ul").length > 0){
                    $('#main_search_drop_content').fadeIn(150);
                }

                $(document).bind('focusin.main_search_drop_content click.main_search_drop_content',function(e) {
                    if ($(e.target).closest('#main_search_drop_content, #main_search').length) return;
                        $('#main_search_drop_content').fadeOut('fast');
                });
            });

            $('#main_search_drop_content').hide();

            $(".txt_need_help_con").click(function(){
                $('.need_help_icons_con').slideToggle();
                $(this).toggleClass("arrow-switch");
            });

            $('.need_help_icons_con').hide();
              
            var $container = $(".nav-collapse");
            
            $(document).mouseup(function (e) {

                if (!$container.is(e.target) // if the target of the click isn't the container...
                    && $container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                   navigation.close();
                }

            });
            
        });
        
    })(jQuery);

</script>
