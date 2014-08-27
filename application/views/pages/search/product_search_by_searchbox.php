
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>"   media="screen"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" media="screen"/>
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
                </ul>
            </div>
        </div>
        <div class="clear"></div>
        <div class="bread_crumbs"></div>
    </div>
</section>
<div class="wrapper" id="main_search_container">
    <div class="left_attribute">
        <h3>Price</h3>
        <input type="text" id="price1" maxlength=10 size=6>to<input type="text" id="price2" maxlength=10 size=6> 
        <input class="price" type="button" value=">>"/>
        <p class="more_attr">More Filters</p>
        <p class="less_attr">Less Filters</p>
    </div>
 
    <div class="right_product">
        <?php if(count($products) <= 0): ?>
            <div style='margin-bottom: 100px;'>
             <span style='font-size:15px;'> Your search for <span style='font-weight:bold'><?php echo urldecode($string); ?></span> did not return any results. </span>
            </div>
        <?php else: ?>
            <div class="adv_ctr">
                <strong style="font-size:14px"><?php echo number_format(count($products));?></strong> result found for <strong><?php echo html_escape($string);?></strong>
            </div>
            <div id="list" class="list "></div>
            <div id="grid" class="grid grid-active"></div>
            <div class="clear"></div>

            <div id="product_content">
            <?php
 
            foreach ($products as $key => $value):
                  $typeOfView = "product";
                  if(isset($_COOKIE['view'])){ 
                      $typeOfView = ($_COOKIE['view'] == "list") ? "product-list" : "product";
                  }
            ?> 
                <div class="<?php echo $typeOfView; ?>"> 
                    <a href="<?php echo base_url() . "item/" . $value->getSlug(); ?>">
<!--                         <span class="prod_img_wrapper">
                            <?php if((intval($items[$i]['is_promote']) == 1) && isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
                                <span class="cd_slide_discount">
                                    <span><?php echo number_format($items[$i]['percentage'],0,'.',',');?>%<br>OFF</span>
                                </span>
                            <?php endif; ?>
                        
                            <span class="prod_img_container">
                              <img alt="<?php echo html_escape($items[$i]['name']); ?>" src="<?php echo base_url() . $items[$i]['path'] . "categoryview/" . $items[$i]['file']; ?>">
                            </span>
                        </span>  -->
                    </a>
                    <h3>
                        <a href="<?php echo base_url() . "item/" . $value->getSlug(); ?>">
                            <?php echo html_escape($value->getName()); ?>
                        </a>
                    </h3>
                    <div class="price-cnt">
                        <div class="price"> 
                            <span>&#8369;</span> <?php echo number_format($value->getPrice(), 2);?>
                        </div>
                      
                        <?php if(isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
                        <div>
                            <span class="original_price">
                                &#8369; <?php echo number_format($items[$i]['original_price'],2,'.',','); ?>
                            </span>
                            <span style="height: 20px;">
                                |&nbsp; <strong><?PHP echo number_format($items[$i]['percentage'],0,'.',',');?>%OFF</strong>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                   <div class="product_info_bottom">
                        <div>
                            Condition:
                            <strong>
                               <?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($value->getCondition()),15) : html_escape($value->getCondition());?>
                            </strong>
                        </div>
                        <?php if($items[$i]['is_free_shipping']): ?>
                            <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
                        <?php endif; ?>
                    </div>
                    <p><?php echo html_escape($value->getBrief()); ?></p>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

 
<script type="text/javascript">

$(document).ready(function () {
     if ($('.left_attribute').length === $('.left_attribute:contains("a")').length) {
        $('.left_attribute').children('h3:gt(2)').nextAll().hide();
        $('.left_attribute').children('h3:gt(2)').hide();
        $('.left_attribute').children('.more_attr').show();
    }
    else {
       $('.more_attr').hide();
    }

     $(".more_attr").click(function() {
        $(this).parent().children().show();
        $(this).hide();
        $(this).siblings('.less_attr').show;
    });

    $(".less_attr").click(function() {
        $('.left_attribute').children('h3:gt(2)').nextAll().hide();
        $('.left_attribute').children('h3:gt(2)').hide();
        $(this).siblings('.more_attr').show();
        $(this).hide();
    });
});

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
            function removeParam(key, sourceURL) {
        var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = rtn + "?" + params_arr.join("&");
        }
        return rtn;
    }


  // START OF INFINITE SCROLLING FUNCTION

  var base_url = config.base_url;
  var offset = 1;
  var request_ajax = true;
  var ajax_is_on = false; 
  var objHeight=$(window).height()-50;  
  var last_scroll_top = 0;
  <?php 
   $type = 0;
   if(isset($_COOKIE['view']))
    {
        $cookieView = $_COOKIE['view'];
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
            url: base_url + 'search_more',
            data:{page_number:offset,id_cat:<?php echo $id_cat ?>,type:type,parameters:'<?php echo json_encode($get_params) ?>', csrfname : csrftoken},
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
 


    $('#price1').val(<?php echo $price1 ?>);
    $('#price2').val(<?php echo $price2 ?>);

    $(document).on('change',"#price2,#price1",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        } else {
            tempval = Math.abs(v);
            this.value = tempval.toFixed(2);
        }
    });

    $('.price').click(function() {

        var price1 = $('#price1').val();

        var price2 = $('#price2').val();
        var url = $(this).data("url");

        if(price1 == "" && price2 == "")
        {
            url = removeParam("price", url);
        }else{
            url = removeParam("price", url);
            url = url +'&price='+ price1 +'to'+price2;
        }
        if(price1 == "" && price2 != ""){
            $("#price1").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
            $( "#price1" ).focus();
        }else if(price1 != "" && price2 == ""){
            $("#price2").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
            $( "#price2" ).focus();
        }else if(price1 > price2){
           $("#price1,#price2").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
            $( "#price1" ).focus();
        }else{
            document.location.href=url;
        }     
    });
 
    $( "#price1 , #price2" ).keypress(function(evt) {
 
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
               $(this).css({"-webkit-box-shadow": "0px 0px 0px 0px #FFFFFF",
            "-moz-box-shadow": "0px 0px 0px 0px #FFFFFF",
            "box-shadow": "0px 0px 0px 0px #FFFFFF"});
         
   
   
    });

    $(".more_attr").click(function() {
        $(this).parent().children().show();
        $(this).hide();
        $(this).siblings('.less_attr').show;
    });

    $(".less_attr").click(function() {
        $('.left_attribute').children('h3:gt(2)').nextAll().hide();
        $('.left_attribute').children('h3:gt(2)').hide();
        $(this).siblings('.more_attr').show();
        $(this).hide();
    });

    $(document).on('change','#sort_order',function() {
        var url = $(this).data("url");
        var type = $(this).val();
        url = removeParam("sop", url);
        document.location.href=url+"&sop="+type;

    });

    $(document).on('click','.prod_cat_drop',function() {
        $(".category_nav").toggleClass("category_nav_plus");
        $(".prod_cat_drop").toggleClass("active_prod_cat_drop_arrow");
        $(document).bind('focusin.prod_cat_drop click.prod_cat_drop',function(e) {
            if ($(e.target).closest('.prod_cat_drop, .category_nav').length) return;
            $('.category_nav').removeClass('category_nav_plus');
            $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');
        });
    });

    $('.category_nav').removeClass('category_nav_plus');
    $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');

  });
</script>


<script type="text/javascript">
 
$(document).ready(function () {
     if ($('.left_attribute').length === $('.left_attribute:contains("a")').length) {
        $('.left_attribute').children('h3:gt(2)').nextAll().hide();
        $('.left_attribute').children('h3:gt(2)').hide();
        $('.left_attribute').children('.more_attr').show();
    }
    else {
       $('.more_attr').hide();
    }
});

</script>
  