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
  <div>
    <div class="wrapper header_wrapper">
      <div class="top_links_left">
        <div class="top_nav">
          <ul>
            <li class="top_nav_main">I want to visit
              <ul>
                <li><a href="<?=base_url()?>cat/all">Shopping Categories</a></li>
                <li><a href="<?=base_url()?>advsrch">Advance Search</a></li>
              </ul>
            </li>
            <li class="top_nav_main">Seller Center
              <ul>
                <li><a href="<?=base_url()?>sell/step1">Sell an Item</a></li>
              </ul>
            </li>
            <li class="top_nav_main"><span class="span_bg cart"></span><span class="cart_no"><?PHP echo ($total_items) &&  ($logged_in)?$total_items:0; ?></span>Shopping Cart
              <ul>
                <li><a href="<?php echo base_url()."cart/"; ?>">View my Cart</a></li>
              </ul>
            </li>         
          </ul>
        </div>
      </div>

      <div class="top_links_right_con">
        <?php #echo uri_string();?>
        <?php if(!$logged_in): ?>
        <div  class="top_links_right"> 
            <a href="<?=base_url()?>login" class="top_border">Login</a> 
            <a href="<?=base_url()?>register">Register</a> 
        </div> 

        <?php else: ?>
        <div  class="top_links_right">
            <a href="<?=base_url()?>messages" class="msgs_link">
                <span class="span_bg img_msgs_cntr"></span>
                <span id="unread-messages-count" class="msg_countr"><?PHP echo $msgs['unread_msgs'];?></span>
            </a>
            <a href="<?=base_url()?>me" class="top_border top_link_name"><?php echo $uname; ?></a>
            <a href="<?=base_url()?>login/logout">Logout</a>
        </div>
        <?php endif; ?>
      </div>
  	<div class="alertUser">
  	    <p>You have a message!</p>
  		<span><?PHP echo isset($msgs['msgs']['name'])?$msgs['msgs']['name']:'';?> :</span>
  		<span><?PHP echo isset($msgs['msgs']['message'])?html_escape($msgs['msgs']['message']):'';?> </span>
  	</div>
    </div>
    <div class="need_help_con">
      <div class="need_help_icons_con">
        <p><a href="<?=base_url()?>guide/buy"><img src="<?=base_url()?>/assets/images/img_icon_shop.png"><br /><span>How to Shop</span></a></p>
        <p><a href="<?=base_url()?>guide/sell"><img src="<?=base_url()?>/assets/images/img_icon_sell.png"><br /><span>How to Sell</span></a></p>
      </div>
      <div class="txt_need_help_con">
        <span class="span_bg up_arrow"></span>
        <span class="txt_need_help"><span class="span_bg icon_help"></span><span class="txt_help">Need Help?</span></span>
      </div>
    </div>
  </div>
</header>

    <form action="<?php echo base_url(); ?>search/search.html" name="search_form" method="get">
      <section>
        <div class="wrapper search_wrapper">
        
          <?php if(!(isset($render_logo) && ($render_logo === false))): ?>
            <div class="logo"> <a href="<?=base_url()?>"><span class="span_bg"></span></a> </div>
          <?php endif; ?>
          
          <?php if(!(isset($render_searchbar) && ($render_searchbar === false))): ?>
              <div class="search_box prob_search_box">
                <div>
                  <span class="main_srch_img_con"></span>
                  <input name="q_str" type="text" id="main_search" placeholder="Search..." value="<?php if(isset($_GET['q_str'])) echo str_replace('-', ' ', html_escape($_GET['q_str'])); ?>" autocomplete="off">
                  
                  <select name="q_cat" id="q_cat">
                    <option value="1">All Categories</option>
                    <?php
                        foreach ($category_search as $keyrow) {
                          $selected = "";
                          if(isset($_GET['q_cat'])){
                              if($_GET['q_cat'] == $keyrow['id_cat'])
                              {
                                $selected = "selected";
                              }
                          }
                     ?>
                    <option <?php  echo $selected ?> value="<?php  echo $keyrow['id_cat'] ?>"><?php echo $keyrow['name']; ?></option>
                    <?php
                          }
                    ?>
                  </select>
                  <button onclick="search_form.submit();" class="search_btn">SEARCH</button><a href="<?=base_url()?>advsrch" class="adv_srch_lnk">Advance Search</a>
                </div>
                <div id="main_search_drop_content"></div> 
              </div>
          <?php endif; ?>
        </div>
      </section>
    </form>


<script type="text/javascript">
/*
$(document).ready(function() {
  var currentRequest = null;

 
$('#main_search').on('input propertychange', function() {
        
          var searchQuery = $.trim( $(this).val());
          searchQuery = searchQuery.replace(/ +(?= )/g,'');
          var fulltext = searchQuery; 
          if(searchQuery != ""){
            if(searchQuery.length > 0){
              currentRequest = $.ajax({
                type: "GET",
                url: '<?php echo base_url();?>search/suggest', 
                
                cache: false,
                data: "q="+fulltext, 
                processData: false,
                beforeSend: function(jqxhr, settings) { 
                  if(currentRequest != null) {
                    currentRequest.abort();
                  }
                },
                success: function(html) {
                    $("#main_search_drop_content").empty();
                    if(html==0){
                        $("#main_search_drop_content").fadeOut(150);
                        // $("#main_search_drop_content").append('No Results Found');
                        // $("#main_search_drop_content").show();
                    }
                    else{
                        $("#main_search_drop_content").append(html);
                        $("#main_search_drop_content").fadeIn(150);
                    }
                    $(".main_srch_img_con").hide();
                }
              });
            }else{
              if(currentRequest != null) {
                currentRequest.abort();
              }
              $("#main_search_drop_content").empty();
              $("#main_search_drop_content").fadeOut(150);
            }
          }else{
            if(currentRequest != null) {
              currentRequest.abort();
            }  
            $("#main_search_drop_content").empty();
            $("#main_search_drop_content").fadeOut(150);
          }
      });
});
*/
      
</script>

<script>
         $(document).ready(function() { 
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
           
        });
        
        $(".txt_need_help_con").click(function(){
            $('.need_help_icons_con').slideToggle();
        });

        $('.need_help_icons_con').hide();
        
        // Hide Need help how to sell and how to buy when click outside the container
      //   $(document).on('click','.txt_need_help',function() {
      //   $(document).bind('focusin.txt_need_help click.txt_need_help',function(e) {
      //       if ($(e.target).closest('.txt_need_help, .need_help_icons_con').length) return;
      //       $('.need_help_icons_con').slideUp();
      //   });
      // });

</script>