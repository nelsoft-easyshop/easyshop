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
				</ul>
			</div>
		</div>
		<div class="clear"></div>
		<div class="bread_crumbs"></div>
	</div>
</section>
<div class="wrapper" id="main_search_container">

  <div class="left_attribute">
			<?php
        if(!count($items) <= 0){
       echo $category_cnt; 
     }
     ?>
		 

         <h3>Price</h3>
        <?php
        if(!isset($_GET['price']))
            $pricelink = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']) . '&price=';
        else
            $pricelink = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']) ;
        ?>
        <input type="text" id="price1" maxlength=10 size=6>to<input type="text" id="price2" maxlength=10 size=6> <input class="price" data-url="<?php echo $pricelink ?>" type="button" value=">>"/>
        <?php
        if (isset($attributes)) {
            foreach ($attributes as $keyparam => $value) {
                    
                    $decodeparam = urldecode($keyparam);
                    $parameter = strtolower(str_replace(' ', '_', $decodeparam));

                    echo '<h3 class="title">'.html_escape($decodeparam).' <br></h3>';
                    
                foreach ($value as $key2 => $attr_value) {
                    $attr_value = ucfirst(strtolower($attr_value));
                    # start if   
                    if(count($_GET) <= 0){$finalurl = $_SERVER['REQUEST_URI'].'?';}
                    else{$finalurl = $_SERVER['REQUEST_URI'].'&';}
                    # end if


                    $url = $finalurl;
                    list($file, $parameters) = explode('?', $url);
                    parse_str($parameters, $output);
                    $checked = "";
                    if(isset($_GET[$parameter])){
                       $oldvalue = $output[$parameter];
                       unset($output[$parameter]);
                        if(strpos($_GET[$parameter], '-|-') !== false) {

                            $var = explode('-|-',$_GET[$parameter]);
                            $newvalue = "";
 
                                if (in_array($attr_value, $var)) {
                                    $checked = "checked";
                                    $key = array_search($attr_value, $var);
                                    unset($var[$key]); 
                                    $newvalue = implode("-|-", $var); 
                                    $link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$newvalue;
                                }else{
                                    $link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$oldvalue.'-|-'.$attr_value;
                                }

                        } else {

                            if($_GET[$parameter] == $attr_value){
                                $checked = "checked";
                                $link = $file . '?' . http_build_query($output); 
                            }else{
                                $link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$oldvalue.'-|-'.$attr_value;
                            }
                        }
                     
                    }else{
                        $link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$attr_value; 
                    }

                    echo '<a href="'.$link.'"><input type="checkbox" '.$checked.' class="cbx" data-value="'.$link.'" > 
                            <label for="cbx">'.ucfirst(strtolower($attr_value)).'</label><br>
                        </a>';
                }
            }
        }
        ?> 
        <p class="more_attr">More Filters</p>
        <p class="less_attr">Less Filters</p>
	</div>
 
	<div class="right_product">
	   <?php 
      if(count($items) <= 0){
        ?>
        <div style='margin-bottom: 100px;'>
         <span style='font-size:15px;'> Your search for <span style='font-weight:bold'><?php echo html_escape($string); ?></span> did not return any results. </span>
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
          <div class="adv_ctr"><strong style="font-size:14px"><?php echo number_format($rec);?></strong> result<?php echo $s;?> found for <strong><?php echo html_escape($string);?></strong></div>
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
            <a href="<?php echo base_url() . "item/" . $items[$i]['slug']; ?>">
              <span class="prod_img_wrapper">
                <span class="prod_img_container">
                  <img alt="<?php echo html_escape($items[$i]['name']); ?>" src="<?php echo base_url() . $pic[0] . "/" . $pic[1] . "/" . $pic[2] . "/" . $pic[3] . "/" . "categoryview" . "/" . $pic[4]; ?>">
                </span>
              </span> 
            </a>
            <h3>
              <a href="<?php echo base_url() . "item/" . $items[$i]['slug']; ?>">
                <?php echo html_escape($items[$i]['name']); ?>
              </a>
            </h3>
            <div class="price-cnt">
              <div class="price"> 
                <span>&#8369;</span> <?php echo number_format($items[$i]['price'], 2);?>
              </div>
            </div>
            <div class="product_info_bottom">
              <div>Condition: <strong><?php echo $items[$i]['condition']; ?></strong></div>
            </div>
            <p><?php echo html_escape($items[$i]['brief']); ?></p>
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


<?php
$price1= "";
$price2 = "";

if(isset($_GET['price'])){
    if(strpos($_GET['price'], 'to') !== false)
    {
        $price = explode('to',  $_GET['price']);
        $price1 = str_replace( ',', '', $price[0]);
        $price2 = str_replace( ',', '', $price[1]);          
       
    } else {
       $price1= "";
       $price2 = "";
   }
}
?>
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
            url: base_url + 'category/loadproduct',
            data:{page_number:offset,id_cat:<?php echo $id_cat ?>,type:type,parameters:'<?php echo json_encode($_GET); ?>', csrfname : csrftoken},
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
  