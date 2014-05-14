<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/product_search_category.css?ver=1.0"   media="screen"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style_new.css?ver=1.0" media="screen"/>
<div class="clear"></div>
<section class="top_margin">
	<div class="wrapper">
		<div class="prod_categories">
			<div class="nav_title">Categories <img src="<?=base_url()?>assets/images/img_arrow_down.png"></div>
			<?php echo $category_navigation; ?> 
		</div>
		<div class="prob_cat_nav">
			<div class="category_nav product_content">
				<ul>
					<!--
				  	<li class = "active">
						<a href="#">Related: Not yet Available</a>
				  	</li>
				  	-->
				</ul>
			</div>
		</div>
		<div class="clear"></div>
		<div class="bread_crumbs"></div>
	</div>
</section>
<div class="wrapper" id="main_search_container">
	<div class="left_attribute">
		<div class="src_ul_li">

			<?php
        if(!count($items) <= 0){
       echo $category_cnt; 
     }
     ?>
		</div>
	</div>
	<div class="right_product">
	   <?php 
      if(count($items) <= 0){
        ?>
        <div>
         <h2> No Results Found.
          </h2>
        </div>
        <?php
      }else{
        ?>
  <?php 
      $rec = 0;
      if(!empty($cntr)):
        $rec = $cntr;
        if($rec > 0):
          $s = "";
          if($rec > 1){
            $s = "s";
          }
    ?>
          <div class="adv_ctr"><strong style="font-size:14px"><?php echo number_format($rec);?></strong> result<?php echo $s;?></div>
    <?php   endif; 
      endif;
    ?>
    <?php
      $typeOfViewActive = '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
            if(isset($_COOKIE['view'])){

              $typeOfViewActive = '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
                $cookieView = $_COOKIE['view'];
                if($cookieView == "list"){
                    $typeOfViewActive = '<div id="list" class="list list-active"></div><div id="grid" class="grid"></div>';
                }else{
                   $typeOfViewActive = '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
                }
            }
            echo $typeOfViewActive;
    ?>
    <div class="clear"></div>
    <div id="product_content">
    <?php
          if(isset($items)):
            for ($i=0; $i < sizeof($items); $i++): 
              $pic = explode('/', $items[$i]['product_image_path']);
                $typeOfView = "product";
                if(isset($_COOKIE['view'])){
                    $cookieView = $_COOKIE['view'];
            if($cookieView == "list"){
              $typeOfView = "product-list";
            }else{
               $typeOfView = "product";
            }
                }
         ?>
          <div class="<?php echo $typeOfView; ?>"> 
            <a href="<?php echo base_url() . "item/" . $items[$i]['product_slug']; ?>">
              <span class="prod_img_wrapper">
                <span class="prod_img_container">
                  <img alt="<?php echo html_escape($items[$i]['product_name']); ?>" src="<?php echo base_url() . $pic[0] . "/" . $pic[1] . "/" . $pic[2] . "/" . $pic[3] . "/" . "categoryview" . "/" . $pic[4]; ?>">
                </span>
              </span> 
            </a>
            <h3>
              <a href="<?php echo base_url() . "item/" . $items[$i]['product_slug']; ?>">
                <?php echo html_escape($items[$i]['product_name']); ?>
              </a>
            </h3>
            <div class="price-cnt">
              <div class="price"> 
                <span>&#8369;</span> <?php echo number_format($items[$i]['product_price'], 2);?>
              </div>
            </div>
            <div class="product_info_bottom">
              <div>Condition: <strong><?php echo $items[$i]['product_condition']; ?></strong></div>
            </div>
            <p><?php echo html_escape($items[$i]['product_brief']); ?></p>
          </div>
    <?php
        endfor;
      endif;
    ?>
    </div>

      <?php }
     ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){

    var today = new Date();
        var expiry = new Date(today.getTime() + 30 * 24 * 3600 * 1000); // plus 30 days

        function createCookie(name, value, expires, path, domain) {
            var cookie = name + "=" + escape(value) + ";";

            if (expires) { 
                if(expires instanceof Date) { 
                    if (isNaN(expires.getTime()))
                        expires = new Date();
                }
                else
                    expires = new Date(new Date().getTime() + parseInt(expires) * 1000 * 60 * 60 * 24);
                cookie += "expires=" + expires.toGMTString() + ";";
            }
            if (path)
                cookie += "path=" + path + ";";
            if (domain)
                cookie += "domain=" + domain + ";";
            document.cookie = cookie;
        }

        function getCookie(name) {
            var regexp = new RegExp("(?:^" + name + "|;\s*"+ name + ")=(.*?)(?:;|$)", "g");
            var result = regexp.exec(document.cookie);
            return (result === null) ? null : result[1];
        }

  // START OF INFINITE SCROLLING FUNCTION

  var base_url = '<?php echo base_url(); ?>';
  var offset = 1;
  var request_ajax = true;
  var ajax_is_on = false; 
  var objHeight=$(window).height()-50;  
  var last_scroll_top = 0;
  <?php 
   $type = 0;
   if(isset($_COOKIE['view']))
    {
        $type = 0;
        if($cookieView == "list"){
            $type = "1";
        }else{
           $type = "0";
        }
    }
 ?>
 var type = '<?php echo $type ?>';
  var csrftoken = $("meta[name='csrf-token']").attr('content');
  var csrfname = $("meta[name='csrf-name']").attr('content');
  $(window).scroll(function(event) {
    var st = $(this).scrollTop();

    if(st > last_scroll_top){
      if ($(window).scrollTop() + 400 > $(document).height() - $(window).height()) {
        if (request_ajax === true && ajax_is_on === false) {
          ajax_is_on = true;
          $.ajax({
            url: base_url + 'search/load_search_other_product',
            data:{page_number:offset,id_cat:<?php echo $id_cat ?>,type:type,parameters:<?php echo json_encode($_GET); ?>, csrfname : csrftoken},
            type: 'post',
            async: false,
            dataType: 'json',
            success: function(d) {
               if(d == "0"){
                 ajax_is_on = true;
               }else{
                $($.parseHTML(d.trim())).appendTo($('#product_content'));
                ajax_is_on = false;
                offset += 1;
            }
          }
        });
        }
      }
    }
    last_scroll_top = st;
  });

  // END OF INFINITE SCROLLING FUNCTION

  $(".cbs").click(function(){
    window.location = "<?php echo site_url(uri_string().'?'.$_SERVER['QUERY_STRING']);?>" + this.value;
  });

  $('#list').click(function(){    
    type = 1;
	createCookie("view ", "list", 30); 
    $('.product').animate({opacity:0},function(){
      $('.grid').removeClass('grid-active');
      $('.list').addClass('list-active');
      $('.product').attr('class', 'product-list');
      $('.product-list').stop().animate({opacity:1},"fast");
    });
  });

  $('#grid').click(function(){
    type = 0;
 	createCookie("view ", "grid", 30);  
    $('.product-list').animate({opacity:0},function(){
      $('.list').removeClass('list-active');
      $('.grid').addClass('grid-active');
      $('.product-list').attr('class', 'product');
      $('.product').stop().animate({opacity:1},"fast");
    });
  });

  $('.nav_title').mouseover(function(e) {
   $("nav").show();
 });
  $('.nav_title').mouseout(function(e) {
   $("nav").hide();
 });
  $("nav").mouseenter(function() {
    $(this).show();
  }).mouseleave(function() {
    $(this).hide();
  });
});
</script> 
