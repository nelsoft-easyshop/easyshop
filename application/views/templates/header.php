<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("assets/includes/css.php"); ?>
    <?php require_once("assets/includes/js.php"); ?>
<meta charset="utf-8" />
<meta name="description" content="" />
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

<!--[if IE]><![endif]-->
<!--[if lt IE 7 ]> <html lang="en" class="ie6">    <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7">    <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8">    <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9">    <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<title>
	<?php 
        if(isset($_GET['q_str']))
        {
          echo urldecode($_GET['q_str']).' | ';
        }
    ?>
    <?php echo $title?>
</title>
 
</head>
<body>
<header>
  <div class="wrapper">
    <div class="top_links_left">
      <div class="top_nav">
        <ul>
          <li class="top_nav_main">I want to visit
            <ul>
              <!-- <li><a href="<?=base_url()?>home/under_construction">Visiting a new Shop</a></li> -->
              <!-- <li><a href="<?=base_url()?>home/under_construction">Discounts</a></li> -->
              <li><a href="<?=base_url()?>category/all">Shopping Categories</a></li>
			  <li><a href="<?=base_url()?>advsearch">Advance Search</a></li>
			  <!-- Removed: not part of branch release. Keep edits like this in the trunk -->
              <!-- <li><a href="<?=base_url()?>product_search/advance">Advance Search</a></li> -->
			  
            </ul>
          </li>
          <li class="top_nav_main">Seller Center
            <ul>
              <li><a href="<?=base_url()?>sell/step1">Sell an Item</a></li>
              <!-- <li><a href="<?=base_url()?>home/under_construction">Orders being processed</a></li> -->
              <!-- <li><a href="<?=base_url()?>home/under_construction">seller services</a></li> -->
              <!-- <li><a href="<?=base_url()?>home/under_construction">market sellers</a></li> -->
              <!-- <li><a href="<?=base_url()?>home/under_construction">Training Center</a></li> -->
            </ul>
          </li>
          <li class="top_nav_main"><span class="span_bg cart"></span>Shopping Cart <span class="cart_no"><?PHP echo isset($total_items)?$total_items:0; ?></span> items
            <ul>
              <li><a href="<?php echo base_url()."cart/"; ?>">View my Cart</a></li>
            </ul>
          </li>
          
          <li class="top_nav_main">Favorites
            <ul>    
               <!-- 
              <li><a href="<?=base_url()?>home/under_construction">Favorite</a></li> 
              <li><a href="<?=base_url()?>home/under_construction">Products</a></li> 
              <li><a href="<?=base_url()?>home/under_construction">My Favorite Shops</a></li> 
              <li><a href="<?=base_url()?>home/under_construction">market sellers</a></li> 
              <li><a href="<?=base_url()?>home/under_construction">Training Center</a></li> 
              -->
            </ul>
          </li>
          <li class="top_nav_main">Southeast Asia
            <ul>
                <!--
              <li><a href="<?=base_url()?>home/under_construction">Hong Kong</a></li>
              <li><a href="<?=base_url()?>home/under_construction">Taiwan</a></li>
              <li><a href="<?=base_url()?>home/under_construction">Southeast Asia</a></li>
              <li><a href="<?=base_url()?>home/under_construction">Chinese mainland</a></li>
              <li><a href="<?=base_url()?>home/under_construction">Other regions</a></li>
               -->
            </ul>
          </li>
         
        </ul>
      </div>
    </div>
    <?php #echo uri_string();?>
    <?php if(!$logged_in): ?>
    <div  class="top_links_right"> <a href="<?=base_url()?>login" class="top_border">Login</a> <a href="<?=base_url()?>register">Register</a> </div>
    <?php else: ?>
    <div  class="top_links_right"> <a href="<?=base_url()?>me" class="top_border"><?php echo $uname; ?></a> <a href="<?=base_url()?>login/logout">Logout</a> </div>
    <?php endif; ?>
  </div>
</header>
<form action="<?php echo base_url(); ?>search/search.html" name="search_form" method="get">
  <section>
    <div class="wrapper search_wrapper">
      <div class="logo"> <a href="<?=base_url()?>home"><span class="span_bg"></span></a> </div>
      <div class="search_box prob_search_box">
        <div>
          <span class="main_srch_img_con"></span>
          <input name="q_str" type="text" id="main_search" value="<?php if(isset($_GET['q_str'])) echo str_replace('-', ' ', $_GET['q_str']); ?>" autocomplete="off">
          
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
          <button onclick="search_form.submit();" class="search_btn">SEARCH</button>
		  <input type="hidden" id="header_search" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
        </div>
        <div id="main_search_drop_content"></div>
      </div>
    </div>
  </section>
</form>

<script type="text/javascript">

$(document).ready(function() {
  var currentRequest = null;

 
$('#main_search').on('input propertychange', function() {
        
          var searchQuery = $.trim( $(this).val());
          searchQuery = searchQuery.replace(/ +(?= )/g,'');
          var fulltext = searchQuery; 

          if(searchQuery != ""){
            if($('#main_search').val().length > 2){
              currentRequest = $.ajax({
                type: "GET",
                url: '<?php echo base_url();?>search/suggest', 
                onLoading:jQuery(".main_srch_img_con").html('<img src="<?= base_url() ?>assets/images/orange_loader_small.gif" />').show(),
                cache: false,
                data: "q="+fulltext, 
                beforeSend: function(jqxhr, settings) { 
                  $("#main_search_drop_content").empty();
                  if(currentRequest != null) {
                    currentRequest.abort();
                  }
                },
                success: function(html) {
                  $("#main_search_drop_content").append(html);
                  $("#main_search_drop_content").show();
                  $(".main_srch_img_con").hide();
                }
              });
            }else{
              $("#main_search_drop_content").empty();
            }
          }else{
             $("#main_search_drop_content").hide();
          }
      });
});

</script>

<script>
         $(document).ready(function() { 

            $('#main_search').focus(function() {
            $('#main_search_drop_content').show();
            $(document).bind('focusin.main_search_drop_content click.main_search_drop_content',function(e) {
                if ($(e.target).closest('#main_search_drop_content, #main_search').length) return;
                $('#main_search_drop_content').hide();
                });
             });
 
            $('#main_search_drop_content').hide();
        });

</script>
