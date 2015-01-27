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
    <div class="res_wrapper wrapper pos-rel">
        <div class="top_links_left pd-right-20">
            <div class="top_nav">
                <ul class="ul-li-fl-left">
                    <li class="top_nav_main pd-right-20">
                        <a class="prevent" href="/sell/step1">
                            <span class="span_bg icon_sell"></span>
                            <span class="txt_hide">Sell an Item</span>
                        </a>
                    </li>
                    <li class="top_nav_main">
                        <a class="prevent" href="/cart">
                            <?PHP if(!($cartSize) &&  !($logged_in)): ?>
                                <span class="span_bg big_cart cart cart_zero"></span>
                            <?PHP else: ?>
                                <span class="span_bg big_cart cart <?PHP echo (intval($cartSize) === 0) ? 'cart_zero' : ''; ?>"></span>
                                <span class="cart_no <?PHP echo (intval($cartSize) === 0) ? 'cart_icon_hide' : ''; ?>"><?PHP echo $cartSize; ?></span>
                            <?PHP endif;?>
                            <span class="txt_hide">View my Cart</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pos-1 pos-rtl z-index-dflt">
            <div class="nav-collapse top_links_right top_links_right_con">
                <ul class="ul-li-fl-left">       
                    <?php if(!$logged_in): ?>
                        <li><a href="/login" class="top_border prevent">Login</a></li> 
                        <li><a href="/register" class="prevent">Register</a></li> 
                        <li class="txt_res_hide">
                            <a class="prevent" href="/guide/buy">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img_icon_shop.png">
                                <span>How to Shop</span>
                            </a>
                        </li>
                        <li class="txt_res_hide">
                            <a class="prevent" href="/guide/sell">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img_icon_sell.png">
                                <span>How to Sell</span>
                            </a>
                        </li>

                    <?php else: ?>
                        <li>
                            <a href="/messages" class="msgs_link prevent">
                                <span class="span_bg img_msgs_cntr <?PHP echo $unreadMessageCount === 0 ? 'msg_icon_zero' : '';?>"></span>
                                <span id="unread-messages-count" class="msg_countr <?PHP echo $unreadMessageCount === 0 ? 'unread-messages-count-hide' : '';?>">
                                    <?PHP echo $unreadMessageCount ;?>
                                </span>
                            </a>
                            <a href="/<?php echo html_escape($user->getSlug()); ?>" class="top_link_name prevent">
                                <?php echo html_escape($user->getUsername()); ?>
                            </a>
                        </li>
                        <li class="txt_res_hide">
                            <a class="prevent" href="/guide/buy">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img_icon_shop.png">
                                <span>How to Shop</span>
                            </a>
                        </li>
                        <li class="txt_res_hide nav-menu-border">
                            <a class="prevent" href="/guide/sell">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img_icon_sell.png">
                                <span>How to Sell</span>
                            </a>
                        </li>
                        <li>
                            <div><span class="user-nav-dropdown">Account Settings</span></div>
                            <ul class="nav-dropdown">
                                <li>
                                    <a class="prevent" href="/me">Dashboard</a>
                                </li>
                                <li>
                                    <a class="prevent" href="/me?tab=ongoing">On-going Transactions</a>
                                </li>
                                <li>
                                    <a class="prevent" href="/?view=basic">Go to homepage</a>
                                </li>
                                <li class="nav-dropdown-border">
                                    <a class="prevent" href="/me?tab=settings">Settings</a>
                                </li>
                                <li class="nav-dropdown-border">
                                    <a class="prevent" href="/login/logout">Logout</a>
                                </li>
                            </ul>
                            
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="alertUser">
            <p>You have a message!</p>
            <span><?PHP echo isset($msgs['msgs']['name'])?$msgs['msgs']['name']:'';?> :</span>
            <span><?PHP echo isset($msgs['msgs']['message'])?html_escape($msgs['msgs']['message']):'';?> </span>
        </div>
        
        <div class="need_help_con">
            <div class="need_help_icons_con">
                <p>
                    <a class="prevent" href="/guide/buy">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img_icon_shop.png"><br />
                        <span>How to Shop</span>
                    </a>
                </p>
                <p>
                    <a class="prevent" href="/guide/sell">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img_icon_sell.png"><br />
                        <span>How to Sell</span>
                    </a>
                </p>
            </div>
            <div class="txt_need_help_con">
                <span class="span_bg up_arrow"></span>
                <span class="txt_need_help">
                    <span class="span_bg icon_help"></span>
                    <span class="txt_help">Need Help?</span>
                </span>
            </div>
        </div>
    </div>
</header>

<form action="/search/search.html" name="search_form" method="get">

    <section class="<?php echo ES_ENABLE_CHRISTMAS_MODS ? 'header-theme-bg' : ''?>">

        <div class="container old-page-container">
        
        <?php if(!(isset($render_logo) && ($render_logo === false))): ?>
            <div class="logo"> 
                <a href="/" class="prevent">
                    <?php if(ES_ENABLE_CHRISTMAS_MODS): ?>
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img_logo_christmas_theme.png" alt="Easyshop.ph" class="header-old-christmas-logo">
                    <?php else: ?>
                        <span class="span_bg"></span>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif; ?>
       
        <?php if(!(isset($renderSearchbar) && ($renderSearchbar === false))): ?>
            <div class="search_box prob_search_box">
                <div id="search-container" class="pos-rel">
                <span class="main_srch_img_con"></span>
                <input name="q_str" type="text" id="main_search" placeholder="Search..." value="<?= $this->input->get('q_str') ? html_escape(trim($this->input->get('q_str'))) : "" ; ?>" autocomplete="off">
                
                <select name="category" id="category">
                    <option value="1">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <?php $isSelected = $this->input->get('category') && (int)$this->input->get('category') === (int)$category->getIdCat(); ?>
                        <option <?php $isSelected ? 'selected' : '' ?> value="<?php  echo $category->getIdCat() ?>">
                            <?php echo html_escape($category->getName()); ?>
                        </option>
                    <?php endforeach;?>
                </select>
                <button onclick="search_form.submit();" class="search_btn">SEARCH</button><a href="/advsrch" class="adv_srch_lnk">Advance Search</a>
                </div> 
            </div>
        <?php endif; ?>
        </div>
    </section>
    <div class="clear"></div>
</form>
<input type="hidden" id="chatClientInfo" data-host="<?=$chatServerHost?>" data-port="<?=$chatServerPort?>" data-store-name="<?=html_escape($user ? $user->getStoreName() : false)?>">
<input type='hidden' class='es-data' name='is-logged-in' value="<?php echo (isset($logged_in)&&$logged_in) ? 'true' : 'false'?>"/>
<script src="/assets/js/src/vendor/jquery.auto-complete.js" type="text/javascript"></script>
<script>
    (function ($) {  

        var $minChars = 3;

        $('#main_search')
            .autoComplete({
                minChars: $minChars,
                cache: false,
                menuClass: 'autocomplete-suggestions auto-complete-header',
                source: function(term, response){ 
                    try { 
                        xhr.abort(); 
                    } catch(e){}
                    var xhr = $.ajax({ 
                        type: "get",
                        url: '/search/suggest',
                        data: "query=" + term,
                        dataType: "json", 
                        success: function(data){
                            response(data); 
                        }
                    });
                },
                onSelect: function(term){
                    $('#main_search').addClass('selectedClass');
                }
            })
            .focus(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.autocomplete-suggestions').html() ).length ) {
                            $('.autocomplete-suggestions').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .click(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.autocomplete-suggestions').html() ).length ) {
                            $('.autocomplete-suggestions').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .change(function() {
                if($(this).val().length <= 0){
                    $('.autocomplete-suggestions').empty();
                }
            });

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

            $(".txt_need_help_con").click(function(){
                $('.need_help_icons_con').slideToggle();
                $(this).toggleClass("arrow-switch");
            });

            $('.need_help_icons_con').hide();
            var navigation = responsiveNav(".nav-collapse"); 
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
