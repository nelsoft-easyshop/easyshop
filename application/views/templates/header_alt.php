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
    
    
    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <link type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <link type="text/css" href='/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <link type="text/css" href='/assets/css/bootstrap-mods.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <?php else: ?>
        <link type="text/css" href='/assets/css/min-easyshop.header-alt.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <?php endif; ?>
    
    <link type="text/css" href='/assets/css/font-awesome/css/font-awesome.min.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href="/assets/css/easy-icons/easy-icons.css?<?=ES_FILE_VERSION?>" rel="stylesheet">
    
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


<?php if(ES_ENABLE_CHRISTMAS_MODS): ?>
    <header class="vendor-christmas-theme">
<?php else: ?>
    <header class="new-header-con">
<?php endif; ?>

 
    <div class="main-container container vendor-mobile-wrapper">
        <div class="vendor-logo-wrapper">
            <a href="/">
                <?php if(ES_ENABLE_CHRISTMAS_MODS): ?>
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img_logo_christmas_theme.png" alt="Easyshop.ph Logo" class="vendor-christmas-theme-logo">
                <?php else: ?>
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img_logo.png" alt="Easyshop.ph Logo">
                <?php endif; ?>
            </a>
        </div>
        <div class="vendor-header-left">
            <div class="search-container">
                <span class="mobile-search"><span class="span_bg"></span></span>
                <form id="search-form1" class="search-form">
                    <select class="ui-form-control search-type">
                        <option value="1">On Seller's Page</option>
                        <option value="2">Main Page</option> 
                    </select>
                    <input type="text" id="main_search_alt" autocomplete="off" class="search-bar-input ui-form-control" name="q_str" value="<?=($this->input->get('q_str'))?trim(html_escape($this->input->get('q_str'))):""?>" class="ui-form-control">
                    <input type="submit"  value="" class="submitSearch span_bg">
                </form>
            </div>
            <div class="mobile-vendor-cart-con">
                <div class="header-cart-container">
                    <div class="mobile-vendor-cart">
                        <span class="vendor-cart-counter"><?=$cartSize?></span>
                        <span class="cart-icon span_bg"></span>
                    </div>
                    <a href="/cart" class="header-cart-wrapper">
                        <span class="header-cart-items-con ui-form-control">
                            <span class="header-cart-item"><?=$cartSize?> item(s) </span>in your cart
                        </span>
                        <span class="header-cart-icon-con span_bg cart-icon"></span>
                    </a>
            <?PHP if ((int)sizeof($cartItems) !== 0 ) : ?>
            <div class="header-cart-item-list">
                    <p>Recently added item(s)</p>
                    <?php $cartItemsReversed = array_reverse($cartItems); ?>
                    <?php for($i = 0 ; $i < 2; $i++): ?>
                            <?php if(!isset($cartItemsReversed[$i])) break; ?>
                            <div class="mrgn-bttm-15">
                                <div class="header-cart-item-img">
                                    <a href="/item/<?=$cartItemsReversed[$i]['slug']?>">
                                        <span><img src="<?php echo getAssetsDomain(); ?><?=$cartItemsReversed[$i]['imagePath']; ?>thumbnail/<?=$cartItemsReversed[$i]['imageFile']; ?>" alt="<?=html_escape($cartItemsReversed[$i]['name'])?>"></span>
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
                    <?PHP endif;?>
                </div>
            </div>

    
            <?php if(isset($logged_in) && $logged_in): ?>
            <div class="vendor-log-in-wrapper">
                <div class="vendor-login-con user-login">
                    <?php if((int)$unreadMessageCount !== 0) : ?>
                        <span id="unread-messages-count" class="msg_countr message-count-con">
                    <?php echo $unreadMessageCount; ?>
                    </span>
                    <?php endif;?>
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-default-icon-user.jpg"> 
                    <a href="/<?php echo html_escape($user->getSlug())?>" class="vendor-login-name">
                        <span>
                            <strong><?php echo html_escape($user->getUsername()); ?></strong>
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
                    <?php else: ?>
                    <div class="vendor-log-in-wrapper">
                        <div class="vendor-login-con vendor-out-con">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-default-icon-user.jpg"> 
                            <a href="/login"><strong>login</strong></a>  or 
                            <a href="/register"><strong>Create an account</strong></a>
                        </div>
                        <div class="vendor-out-con2">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-default-icon-user.jpg">
                        </div>
                        <div class="mobile-user-login">
                            <a href="/login" class="btn btn-default-3"><strong>login</strong></a>  or 
                            <a href="/register" class="btn btn-default-1"><strong>Create an account</strong></a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</header>

<?php if(ES_ENABLE_CHRISTMAS_MODS): ?>
    <div class="persistent-nav-container persistent-christmas-theme">
<?php else: ?>
    <div class="persistent-nav-container">
<?php endif; ?>

    <div class="main-container container">
        <ul class="sticky-nav">
            <li>
                <div class="vendor-profile-img-con">
                    <img src="<?php echo getAssetsDomain().'.'.$avatarImage?>" alt="Profile Photo">
                </div>
                <h4 class="storeName"><?=html_escape($arrVendorDetails['store_name']);?></h4>
            </li>
            <li>
                <a href="/<?=$arrVendorDetails['userslug']?>"><img src="<?php echo getAssetsDomain(); ?>assets/images/img-vendor-icon-promo.png" alt="Promo"></a>
                <a href="/<?=$arrVendorDetails['userslug']; ?>/about"><img src="<?php echo getAssetsDomain(); ?>assets/images/img-vendor-icon-info.png" alt="Seller Information"></a>
                <a href="/<?=$arrVendorDetails['userslug']; ?>/contact"><img src="<?php echo getAssetsDomain(); ?>assets/images/img-vendor-icon-contact.png" alt="Contact"></a>
            </li>
            <li> 
                <form id="search-form2" class="search-form">
                    <select class="ui-form-control search-type">
                        <option value="1">On Seller's Page</option>
                        <option value="2">Main Page</option> 
                    </select>
                    <input type="text" id="main_search_alt2" autocomplete="off" class="ui-form-control search-bar-input" name="q_str" value="<?=($this->input->get('q_str'))?trim(html_escape($this->input->get('q_str'))):""?>">
                    <input type="submit"  value="" class="submitSearch span_bg">
                </form>
            </li>
            <li class="pos-rel">
                <div class="header-cart-container">
                    <a href="/cart" class="header-cart-wrapper">
                        <span class="header-cart-items-con sticky-cart ui-form-control">
                            <span class="header-cart-item"><?=$cartSize?> item(s)</span> in your cart
                        </span>
                        <span class="header-cart-icon-con span_bg cart-icon"></span>
                    </a>
                    <div class="sticky-header-cart-item-list">                        
                        <?PHP if ((intval(sizeof($cartItems))) === 0 ) : ?>
                        <?PHP else : ?>
                            <p>Recently added item(s)</p>
                            <?php $cartItemsReversed = array_reverse($cartItems); ?>
                            <?php for($i = 0 ; $i < 2; $i++): ?>
                                    <?php if(!isset($cartItemsReversed[$i])) break; ?>
                                    <div class="mrgn-bttm-15">
                                        <div class="header-cart-item-img">
                                            <a href="/item/<?=$cartItemsReversed[$i]['slug']?>">
                                                <span><img src="<?php echo getAssetsDomain(); ?><?=$cartItemsReversed[$i]['imagePath']; ?>thumbnail/<?=$cartItemsReversed[$i]['imageFile']; ?>" alt="<?=html_escape($cartItemsReversed[$i]['name'])?>"></span>
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
                        <?PHP endif;?>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

<input type='hidden' class='es-data' name='is-logged-in' value="<?php echo (isset($logged_in)&&$logged_in) ? 'true' : 'false'?>"/>
<input type="hidden" id="chatServerConfig" data-host="<?=$chatServerHost?>" data-port="<?=$chatServerPort?>" data-jwttoken="<?php echo html_escape($jwtToken); ?>" >
<input type="hidden" id="listOfFeatureWithRestriction" data-real-time-chat="<?=$listOfFeatureWithRestriction && $listOfFeatureWithRestriction[\EasyShop\Entities\EsFeatureRestrict::REAL_TIME_CHAT] ? 'true' : 'false' ?>">

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/bower_components/bootstrap.js?ver=<?=ES_FILE_VERSION?>" ></script>
    <script src="/assets/js/src/vendor/bower_components/jquery.auto-complete.js" type="text/javascript"></script>
    <script src="/assets/js/src/header_alt.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.header_alt.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

