<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_seach_category.css" type="text/css"  media="screen"/> 
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
         <a href="<?=base_url()?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo es_url_clean($items[$i]['product_name']); ?>.html"><img alt="<?php echo $items[$i]['product_name']; ?>" src="<?php echo base_url().$pic[0].'/'.$pic[1].'/'.$pic[2].'/'.$pic[3].'/'.'categoryview'.'/'.$pic[4];;?>"></a>

         <h3 style="  -o-text-overflow: ellipsis;    
         text-overflow:    ellipsis;   
         overflow:hidden;             
         white-space:nowrap;  
         width: 225px; ">
         <a href="<?=base_url()?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo es_url_clean($items[$i]['product_name']); ?>.html"><?php echo html_escape($items[$i]['product_name']); ?></a>
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
