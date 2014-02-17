<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/grid_list_style.css" media="screen"/> 
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style_new.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/jquery.bxslider.css" type="text/css" media="screen"/>   


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
          
          <li class = "active">
            <a href="#">
            Related: Not yet Available
            </a>
          </li>
        
      </ul>
    </div>
  </div>
  <div class="clear"></div>
  <div class="bread_crumbs">
  
</div>

</div>
</section>    

<div class="wrapper" id="main_search_container">

  <div class="left_attribute">

    <?php
echo $category_cnt;
     ?>
  </div>

  <div class="right_product">
    <p class="search_result"><!-- Showing 1 - 48 of 13,152 Results --></p>

    <div id="list" class="list "></div>
    <div id="grid" class="grid grid-active"></div>
    <div class="clear"></div>
    <div id="product_content">     
      <?php
      if(isset($items))
      {
       for ($i=0; $i < sizeof($items); $i++) { 
        $pic = explode('/', $items[$i]['product_image_path']);
        ?>
        <div class="product">
         <a href="<?=base_url()?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo urlencode($items[$i]['product_name']); ?>.html"><img alt="<?php echo $items[$i]['product_name']; ?>" src="<?php echo base_url().$pic[0].'/'.$pic[1].'/'.$pic[2].'/'.$pic[3].'/'.'categoryview'.'/'.$pic[4];;?>"></a>

         <h3 style="  -o-text-overflow: ellipsis;    
         text-overflow:    ellipsis;   
         overflow:hidden;             
         white-space:nowrap;  
         width: 225px; ">
         <a href="<?=base_url()?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo urlencode($items[$i]['product_name']); ?>.html"><?php echo html_escape($items[$i]['product_name']); ?></a>
       </h3>

       <div class="price-cnt">
        <div class="price">
          <span>&#8369;</span> <?php echo number_format($items[$i]['product_price'],2); ?>
        </div>
      </div>
      <div class="product_info_bottom">
        <div>Condition: <strong><?php echo html_escape($items[$i]['product_condition']); ?></strong></div>
        <!-- <div>Sold: <strong>32</strong></div> -->
      </div>
      <p>
        <?php echo html_escape($items[$i]['product_brief']); ?>
      </p>
    </div>

  

<?php
}
}
?>
</div>
</div>
</div>

<script src="<?=base_url()?>assets/JavaScript/js/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/JavaScript/categorynavigation.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){

  // START OF INFINITE SCROLLING FUNCTION

  var base_url = '<?php echo base_url(); ?>';
  var offset = 1;
  var request_ajax = true;
  var ajax_is_on = false; 
  var objHeight=$(window).height()-50;  
  var last_scroll_top = 0;
  var type = 0;
  $(window).scroll(function(event) {
    var st = $(this).scrollTop();

    if(st > last_scroll_top){
      if ($(window).scrollTop() + 400 > $(document).height() - $(window).height()) {
        if (request_ajax === true && ajax_is_on === false) {
          ajax_is_on = true;
          $.ajax({
            url: base_url + 'search/load_search_other_product',
            data:{page_number:offset,id_cat:<?php echo $id_cat ?>,type:type,parameters:<?php echo json_encode($_GET); ?>},
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

    $('.product').animate({opacity:0},function(){
      $('.grid').removeClass('grid-active');
      $('.list').addClass('list-active');
      $('.product').attr('class', 'product-list');
      $('.product-list').stop().animate({opacity:1},"fast");
    });
  });

  $('#grid').click(function(){
    type = 0;

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
<style>
#main_search_container{
  display: table;
  padding: 10px;
}

.search_left_panel {
  float: left;
}

.left_attribute{
  float: left;
  width:186px;
  margin-right: 10px;
  min-height: 1px;
  background-color: #F8F7F7;
}

.right_product{
  display: inline-block;
  width: 784px;
}

.search_result {
  float: left;
  line-height: 45px;
}

#product_content {
  border-top: 1px solid #b0b0b0;
  margin-top: 10px;
}

.bread_crumbs {
  margin-left: 195px;
}

.filters {
    margin: 0 0 10px !important;
}

.left_attribute a {
  display: block;
  margin-bottom: 8px;
  margin-right: 5px;
}

.left_attribute h3 {
  background-color: #EBE9E9;
  display: block;
  font-size: 12px;
  text-transform: uppercase;
  padding:8px 5px;
  margin: 10px 0px 5px 0px;
}

.left_attribute h3:first-child {
  margin-top: 0;
}

.left_attribute input {
  margin-right: 3px;
  vertical-align: middle;
}

a[href*="Color"] {
  display: inline-block;
  width: 80px;
}

nav {
  display: none;
  position: absolute;
  z-index: 9999;
  background-color: #fff;
}

.prod_categories {
  margin-bottom: 10px;
}

.slide_arrows {
  max-width: none !important;
}

.bx-wrapper {
  width: auto !important;
}
.side_menu_slides {
  margin: 0 auto;
}

.side_menu_nav_arrow {
  margin-left: 0px !important;
}

.product {
  width: 237px !important;
}

.product-list {
  width: 745px;
}

.product img {
  padding-left: 9px;
}

.product_info_bottom {
  margin: 0 10px 10px;
}

.product-list .price {
  float: right;
}

.product-list p {
  width: 575px;
}

.product-list div.product_info_bottom div:first-child {
  width: 420px;
}

</style>