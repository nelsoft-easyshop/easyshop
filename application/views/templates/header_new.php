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
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />
    <link type="text/css" href='/assets/css/normalize.min.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>

    <link type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/font-awesome/css/font-awesome.min.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
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

<!-- <header class="new-header-con"> original container -->
<header class="<?php echo ES_ENABLE_CHRISTMAS_MODS ? 'vendor-christmas-theme' : 'new-header-con' ?>">
    <div class="main-container">
        <div>
            <a href="/">
            
                <?php if(ES_ENABLE_CHRISTMAS_MODS): ?>
                    <img src="/assets/images/img_logo_christmas_theme.png" alt="Easyshop.ph" class="vendor-christmas-theme-logo">
                <?php else: ?>
                    <span class="span_bg"></span>
                <?php endif; ?>

            </a>
        </div>
        <div class="search-container">
           <form class="search-form">
                <select class="ui-form-control search-type">
                    <option value="1">On Seller's Page</option>
                    <option value="2">Main Page</option> 
                </select>
                <input type="text" name="q_str" value="<?=($this->input->get('q_str'))?trim($this->input->get('q_str')):""?>" class="ui-form-control">
                <input type="submit"  value="" class="submitSearch span_bg">
            </form>
        </div>
        <div class="pos-rel mrgn-rght-8">
            <div class="header-cart-container">
                <a href="/cart" class="header-cart-wrapper">
                    <span class="header-cart-items-con ui-form-control">
                        <span class="header-cart-item"><?=$cartSize?> item(s)</span> in your cart
                    </span>
                    <span class="header-cart-icon-con span_bg cart-icon"></span>
                </a>
                <?PHP if ((intval(sizeof($cartItems))) !== 0 ) : ?>
                <div class="header-cart-item-list">
                        <p>Recently added item(s)</p>
                        <?php $cartItemsReversed = array_reverse($cartItems); ?>
                        <?php for($i = 0 ; $i < 2; $i++): ?>
                                <?php if(!isset($cartItemsReversed[$i])) break; ?>
                                <div class="mrgn-bttm-15">
                                    <div class="header-cart-item-img">
                                        <a href="/item/<?=$cartItemsReversed[$i]['slug']?>">
                                            <span><img src="/<?=$cartItemsReversed[$i]['imagePath']; ?>thumbnail/<?=$cartItemsReversed[$i]['imageFile']; ?>" alt="<?=html_escape($cartItemsReversed[$i]['name'])?>"></span>
                                        </a>
                                    </div>
                                    <div class="header-cart-item-con">
                                        <a href="/item/<?=$cartItemsReversed[$i]['slug']?>"><span><?=html_escape($cartItemsReversed[$i]['name'])?></span></a>
                                        <span>x <?=$cartItemsReversed[$i]['qty']?></span>
                                        <span class="header-cart-item-price">&#8369; <?=$cartItemsReversed[$i]['price']?></span>
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
                <?PHP endif;?>
            </div>
        </div>
        
        <?php if(isset($logged_in) && $logged_in): ?>
        <div class="vendor-log-in-wrapper">
            <div class="vendor-login-con user-login">
                <?php if(intval($msgs['unread_msgs']) !== 0) : ?>
                    <span id="unread-messages-count" class="msg_countr message-count-con">
                <?=$msgs['unread_msgs'];?>
                </span>
                <?php endif;?>
                <img src="/assets/images/img-default-icon-user.jpg"> 
                <a href="/<?php echo html_escape($user['slug'])?>" class="vendor-login-name">
                    <span>
                        <strong><?php echo html_escape($user['username']); ?></strong>
                    </span>
                </a>
                <div class="new-user-nav-dropdown">
                    <span class="user-nav-dropdown">Account Settings</span>
                </div>
                <ul class="nav-dropdown">
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
                        <a href="/messages">Message</a>
                        <?php if(intval($msgs['unread_msgs']) !== 0) : ?>
                        <div id="unread-messages-count" class="msg_countr message-count-con">
                        <?=$msgs['unread_msgs'];?>
                        </div>
                        <?php endif;?>
                    </li>
                    <li class="nav-dropdown-border">
                        <a class="prevent" href="/login/logout">Logout</a>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <?php else: ?>
        <div>
            <div class="vendor-login-con vendor-out-con">
                <img src="/assets/images/img-default-icon-user.jpg"> 
                <a href="/login"><strong>login</strong></a>  or 
                <a href="/register"><strong>Create and account</strong></a>
            </div>
        </div>
        <?php endif; ?>
        
        
        <div class="clear"></div>
    </div>
</header>

<script type="text/javascript" src="/assets/js/src/bootstrap.js?ver=<?=ES_FILE_VERSION?>" ></script>

<script type='text/javascript'>

    (function(){
    
        $(function () {
   
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
        
        });

    })(jQuery);


</script>

