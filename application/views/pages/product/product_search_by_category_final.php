<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 


<?php


session_start();
$_SESSION['start'] = "0";
?>

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
                    <?php foreach ($main_categories as $category): ?>
                    <li class = <?php echo (($category['id_cat'] === $breadcrumbs[0]['id_cat']) ? "active" : ""); ?>>
                        <a href="<?= base_url() ?>category/<?php echo $category['slug']?>">
                        <?php echo html_escape($category['name']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <span class="span_bg prod_cat_drop"></span>
            </div>
        </div>
        <div class="clear"></div>
        <div class="bread_crumbs">
            <ul>
                <li class=""><a href="<?= base_url() ?>home">Home</a></li>
                <?php foreach ($breadcrumbs as $crumbs): ?>
                <li>
                    <a href="<?= base_url() ?>category/<?php echo $crumbs['slug'] ?>">
                        <?php echo html_escape($crumbs['name']); ?>
                    </a>
                </li> 
                <?php endforeach; ?>
            </ul> 
        </div>
    </div>
</section>    

<div class="wrapper" id="main_search_container">

    <div class="left_attribute">
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
        
            <?php if(count($subcategories) !== 0):?>
            <div class="filters">           
              <h2>Categories:</h2>    
              <div class="jcarousel category_carousel cc2_wrapper">
                <div class="cc2">
                    <?PHP foreach ($subcategories as $rows): ?>
                    <div class="">
                        <a class="cc2_title" href="<?=base_url()?>category/<?php echo $rows['slug'];?>">
                            <span><?php echo html_escape($rows['name']);?></span></a>
                            <?PHP if(count($rows['popular'])>0): ?>
                            <span class="cat_carousel_img_con"><span class="cat_carousel_img"><img src="<?= base_url().$rows['popular'][0]['path'].'categoryview/'.$rows['popular'][0]['file']; ?>"></span></span><br />
                            <div class="cc2_prod_name"><a href="<?PHP echo base_url()."item/".$rows['popular'][0]['slug']; ?>" title="<?PHP echo $rows['popular'][0]['product']; ?>"><span><?PHP echo html_escape($rows['popular'][0]['product']); ?></span></a></div>
                            <span class="recommended_product_price">PHP <?php echo number_format($rows['popular'][0]['price'],2,'.',',');?></span>
                            <?PHP endif; ?>
                        </div>
                        <?PHP endforeach;?>
                    </div>  
                </div>
                <a href="#" class="jcarousel-control-prev inactive category_carousel2_prev">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next inactive category_carousel2_next">&rsaquo;</a>            
            </div>
        <?php endif; ?>        
        <div class="clear"></div>
        <p class="search_result"> </p>

        Sort by: 
        <select data-url="<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']).'&sop='; ?>" id="sort_order"> 
            <?php
            $sortarray = array('bestmatch' => 'best match', 'hot' => 'hot','new' => 'new','popular' => 'popular');
            $sop = isset($_GET['sop']) ? $this->input->get('sop') : 'best match';
            foreach ($sortarray as $key => $value) {
                $selected = ($sop == $value) ? 'selected' : '';
                echo '<option value="'.$key.'" '.$selected.'>'.ucfirst($value).'</option>';
            } ?>
        </select>

        <?php
        $typeOfViewActive = '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
        if(isset($_COOKIE['view']))
        {
            $cookieView = $_COOKIE['view'];
            $typeOfViewActive = ($cookieView == "list") ? '<div id="list" class="list list-active"></div><div id="grid" class="grid"></div>' : '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
        }
        echo $typeOfViewActive;
        ?> 
        <div class="clear"></div>
        <div id="product_content">     
            <?php
            if (isset($items)) {
                for ($i = 0; $i < sizeof($items); $i++) {

                    $typeOfView = "product";
                    if(isset($_COOKIE['view']))
                    {
                        $cookieView = $_COOKIE['view'];
                        $typeOfView = ($cookieView == "list") ? "product-list" : "product";
                    }
                 ?>
                <div class="<?php echo $typeOfView; ?>">
                    <a href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>">
                        <span class="prod_img_wrapper">
			    <?php if((intval($items[$i]['is_promote']) === 1) && isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
                            <span class="cd_slide_discount">
				    <span><?php echo number_format($items[$i]['percentage'],0,'.',',');?>%<br>OFF</span>
			    </span>
			    <?php endif; ?>


                            <span class="prod_img_container">
                                <img alt="<?php echo html_escape($items[$i]['name']); ?>" src="<?php echo base_url() .$items[$i]['path'].'categoryview/'.$items[$i]['file']; ?>">
                            </span>
                        </span>
                    </a>
                    <h3>
                        <a href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>">
                            <?php echo html_escape($items[$i]['name']); ?>
                        </a>
                    </h3>
                    <div class="price-cnt">
                        <div class="price">
                            PHP <?php echo number_format($items[$i]['price'], 2,'.',','); ?>
                        </div>
                        
                        
                         <?php if(isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
			    
			    <div>
			      <span class="original_price">
				      PHP <?PHP echo number_format($items[$i]['original_price'],2,'.',','); ?>
			      </span>	
			      <span style="height: 20px;">
				|&nbsp; <strong> <?PHP echo number_format($items[$i]['percentage'],0,'.',',');?>%OFF</strong>
			      </span>
			    </div>
			<?php endif; ?>
                        
                    </div>
                    <div class="product_info_bottom">
                        <div>Condition: <strong><?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($items[$i]['condition']),15) : html_escape($items[$i]['condition']);?></strong></div>
			<?PHP if($items[$i]['is_free_shipping']): ?>
			  <span style="float:right;"><span class="span_bg img_free_shipping"></span>
			<?PHP endif; ?>	
                    </div>
                    <p>
                        <?php echo html_escape($items[$i]['brief']); ?>
                    </p>
                </div>
                <?php
            }
        }
        ?>

        </div> 
        <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
    </div>

</div>



<script src="<?= base_url() ?>assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $.scrollUp({
                scrollName: 'scrollUp', // Element ID
                scrollDistance: 300, // Distance from top/bottom before showing element (px)
                scrollFrom: 'top', // 'top' or 'bottom'
                scrollSpeed: 300, // Speed back to top (ms)
                easingType: 'linear', // Scroll to top easing (see http://easings.net/)
                animation: 'fade', // Fade, slide, none
                animationInSpeed: 200, // Animation in speed (ms)
                animationOutSpeed: 200, // Animation out speed (ms)
                scrollText: 'Scroll to top', // Text for element, can contain HTML
                scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
                scrollImg: false, // Set true to use image
                activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
                zIndex: 2147483647 // Z-Index for the overlay
            });
});

</script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>

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
$(document).ready(function() {

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
    var objHeight = $(window).height() - 50;
    var last_scroll_top = 0;
    
    <?php 
    $type = 0;
    if(isset($_COOKIE['view'])){
        $type = ($cookieView == "list") ? '1' : '0';
    }
    ?>

    var type = '<?php echo $type ?>';
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    $(window).scroll(function(event) {
        var st = $(this).scrollTop();
        if(st > last_scroll_top){
            if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {
                if (request_ajax === true && ajax_is_on === false) {
                    ajax_is_on = true; 
                    $.ajax({
                        url: base_url + 'category_more',
                        data:{page_number:offset,id_cat:'<?php echo $id_cat ?>',type:type, parameters:'<?php echo  json_encode($_GET); ?>', csrfname : csrftoken},
                        type: 'post',
                        dataType: 'JSON',
                        onLoading:jQuery(".loading_products").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                        success: function(d) {
                            if(d == "0"){
                                ajax_is_on = true;
                            }else{ 
                                if(d.substring(0,5)  == "<dob>"){
                                    $($.parseHTML(d.trim())).appendTo($('#product_content'));
                                    ajax_is_on = true;
                                }else{
                                    $($.parseHTML(d.trim())).appendTo($('#product_content'));
                                    ajax_is_on = false;
                                    offset += 1;   
                                }
                            }
                           jQuery(".loading_products").fadeOut();    
                        }
                    });
                }
            }
        }
        last_scroll_top = st;

    });
    // END OF INFINITE SCROLLING FUNCTION


    $(document).on('click','.smr_btn',function () {
        ajax_is_on = false;
        $('.phides').show();
        $(this).hide();
    });

    $(".cbx").click(function() {
        window.location = $(this).data('value');
    });

    $('#list').click(function() {
        type = 1;
        createCookie("view ", "list", 30); 
        $('.product').animate({opacity: 0}, function() {
            $('.grid').removeClass('grid-active');
            $('.list').addClass('list-active');
            $('.product').attr('class', 'product-list');
            $('.product-list').stop().animate({opacity: 1}, "fast");
        });
    });

    $('#grid').click(function() {
        type = 0;
        createCookie("view ", "grid", 30);  
        $('.product-list').animate({opacity: 0}, function() {
            $('.list').removeClass('list-active');
            $('.grid').addClass('grid-active');
            $('.product-list').attr('class', 'product');
            $('.product').stop().animate({opacity: 1}, "fast");
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
 
<script type="text/javascript">
    (function($) {
        $(function() {
            $('.jcarousel').jcarousel();

            $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

            $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

            $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .jcarouselPagination();
        });
    })(jQuery);
</script>

<script type="text/javascript">
    var p = $('.cc2_prod_name span');
    var divh = $('.cc2_prod_name').height();
    while ($(p).outerHeight()>divh) {
        $(p).text(function (index, text) {
            return text.replace(/\W*\s(\S)*$/, '...');
        });
    }
</script>