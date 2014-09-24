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
 
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/header-css.css" media='screen'>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/bootstrap.css" media='screen'>
    <link type="text/css" href='<?=base_url()?>assets/css/main-style.css' rel="stylesheet" media='screen'/>
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
        
<header id="header">
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
                                    <p class="header-text"><span class="top-icon top-icon-account"></p>
                                    <p class="header-link"><a href="#">login</a>&nbsp;or&nbsp;<a href="#">create an account</a></p>
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
                                    <div class="btn-group dropdown-cart">
                                    <button type="button" class="btn-info dropdown-toggle" data-toggle="dropdown">
                                        <span class="drop-item">0 item(s) </span>
                                        <span class="drop-price">- P 0.00</span>
                                        <span class="cart-menu-icon"></span>
                                    </button>
                                    
                                        <div class="dropdown-menu dropdown-cart-menu pull-right clearfix" role="menu">
                                            <p class="dropdown-cart-description">Recently added item(s).</p>
                                            <ul class="dropdown-cart-product-list">
                                                <li class="item clearfix">
                                                <a href="#" title="Delete item" class="delete-item"><i class="fa fa-times"></i></a>
                                                    <figure>
                                                        <a href="#"><img src="images/products/thumbnails/dress1.jpg" alt="dress 1"></a>
                                                    </figure>
                                                    <div class="dropdown-cart-details">
                                                        <p class="item-name">
                                                        <a href="#">Cam Optia AF Webcam </a>
                                                        </p>
                                                        <p>
                                                            1x
                                                            <span class="item-price">P499</span>
                                                        </p>
                                                    </div><!-- End .dropdown-cart-details -->
                                                </li>
                                                <li class="item clearfix">
                                                <a href="#" title="Delete item" class="delete-item"><i class="fa fa-times"></i></a>
                                                    <figure>
                                                        <a href="#"><img src="images/products/thumbnails/dress6.jpg" alt="dress 6"></a>
                                                    </figure>
                                                    <div class="dropdown-cart-details">
                                                        <p class="item-name">
                                                            <a href="#">Iphone Case Cover Original</a>
                                                        </p>
                                                        <p>
                                                            1x
                                                            <span class="item-price">P499<span class="sub-price">.99</span></span>
                                                        </p>
                                                    </div><!-- End .dropdown-cart-details -->
                                                </li>
                                            </ul>
                                            
                                            <ul class="dropdown-cart-total">
                                                <li><span class="dropdown-cart-total-title">Shipping:</span>P7</li>
                                                <li><span class="dropdown-cart-total-title">Total:</span>P1005<span class="sub-price">.99</span></li>
                                            </ul><!-- .dropdown-cart-total -->
                                            <div class="dropdown-cart-action">
                                                <p><a href="#" class="btn btn-custom-2 btn-block">Cart</a></p>
                                                <p><a href="#" class="btn btn-custom btn-block">Checkout</a></p>
                                            </div><!-- End .dropdown-cart-action -->
                                            
                                        </div><!-- End .dropdown-cart -->
                                        </div><!-- End .btn-group -->
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
                
                <div id="main-nav-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 clearfix">
                                
                                <nav id="main-nav">
                                    <div id="responsive-nav">
                                        <div id="responsive-nav-button">
                                            Menu <span id="responsive-nav-button-icon"></span>
                                        </div><!-- responsive-nav-button -->
                                    </div>
                                    <ul class="menu clearfix">
                                        <li class="mega-menu-container drop-category">
                                            <span class="category-icon"></span>
                                            <a href="#">CATEGORIES</a>
                                            <span class="drop-icon"></span>
                                            <div class="mega-menu clearfix">
                                                <h2>popular categories</h2>
                                                    <div class="col-3">
                                                        <a href=""><h3>Clothes &amp; Accessories</h3></a>
                                                        <ul>
                                                            <li><a href="">Women Clothing</a></li>
                                                            <li><a href="">Men Clothing</a></li>
                                                            <li><a href="">Babies Clothing</a></li>
                                                            <li><a href="">Unisex Accessories</a></li>
                                                        </ul>
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href=""><h3>Electronics &amp; Gadget</h3></a>
                                                        <ul>
                                                            <li><a href="">Cameras &amp; Imaging</a></li>
                                                            <li><a href="">Computer &amp; Networking</a></li>
                                                            <li><a href="">Tablets</a></li>
                                                            <li><a href="">Mobile Phones</a></li>
                                                        </ul>
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href=""><h3>Jewelry &amp; Watches</h3></a>
                                                        <ul>
                                                            <li><a href="">Watches</a></li>
                                                            <li><a href="">Fasion Jewelry</a></li>
                                                        </ul>
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href="#" class="mega-menu-title">
                                                            <span class="category-4-icon"></span>
                                                            Electronic & Gadgets</a>
                                                            <p>FLorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque sagittis.</p><!-- End .mega-menu-title -->
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href="#" class="mega-menu-title">
                                                            <span class="category-5-icon"></span>
                                                            Toys, Hobbies & Collectibles</a>
                                                            <p>FLorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque sagittis.</p><!-- End .mega-menu-title -->
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href="#" class="mega-menu-title">
                                                            <span class="category-6-icon"></span>
                                                            Books, Music & Movies</a>
                                                            <p>FLorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque sagittis.</p><!-- End .mega-menu-title -->
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href="#" class="mega-menu-title">
                                                            <span class="category-7-icon"></span>
                                                            Furniture, Home & Garden</a>
                                                            <p>FLorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque sagittis.</p><!-- End .mega-menu-title -->
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href="#" class="mega-menu-title">
                                                            <span class="category-8-icon"></span>
                                                            Business & Industrial</a>
                                                            <p>FLorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque sagittis.</p><!-- End .mega-menu-title -->
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-3">
                                                        <a href="#" class="mega-menu-title">
                                                            <span class="category-9-icon"></span>
                                                            Food & Beverages</a>
                                                            <p>FLorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque sagittis.</p><!-- End .mega-menu-title -->
                                                    </div><!-- End .col-5 -->
                                            </div><!-- End .mega-menu -->
                                        </li>
                                        
                                        <li>
                                            <a href="#">NEW ARRIVALS</a>
                                            <ul>
                                                <li><a href="#">Male</a></li>
                                                <li><a href="#">Female</a></li>
                                                <li><a href="c#">Gadgets</a>
                                                    <ul>
                                                        <li><a href="#">Camera</a></li>
                                                        <li><a href="#">Phone</a></li>
                                                        <li><a href="#">Computer</a></li>
                                                        <li><a href="#">Accessories</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Children</a></li>
                                                <li><a href="#">Toys</a></li>
                                                <li><a href="#">Auto Supplies</a></li>
                                                <li><a href="#">Food & Beverages</a></li>
                                                <li><a href="#">Beers</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">TOP PRODUCTS</a>
                                        </li>
                                        <li><a href="#">TOP SELLERS</a></li>
                                        <li><a href="#">EASY TREATS</a>
                                            <ul>
                                                <li><a href="#">Hot Deals</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">EASY DEALS</a></li>
                                    </ul>
                                    
                                </nav>
                                
                            </div><!-- End .col-md-12 -->
                    </div><!-- End .row -->
                    <div class="clear"></div> 
                </div><!-- End .container -->
                    <div class="clear"></div> 
                </div><!-- End #nav -->
                <div class="clear"></div> 
            </div><!-- End #inner-header -->
            <div class="clear"></div> 
        </header><!-- End #header -->

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
