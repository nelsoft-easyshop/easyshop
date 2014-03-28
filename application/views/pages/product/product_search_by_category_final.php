<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css" type="text/css" media="screen"/> 

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
                        <a href="<?= base_url() ?>category/<?php echo $category['id_cat']; ?>/<?php echo es_url_clean($category['name']); ?>.html">
                            <?php echo $category['name']; ?>
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
                <a href="<?= base_url() ?>category/<?php echo $crumbs['id_cat'] ?>/<?php echo es_url_clean($crumbs['name']) ?>.html">
                    <?php echo $crumbs['name'] ?>
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
                    $parameter = str_replace(' ', '_', $decodeparam);
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

                    echo '<a href="'.$link.'"><input type="checkbox" '.$checked.' class="cbx" > 
                            <label for="cbx">'.ucfirst(strtolower($attr_value)).'</label><br>
                        </a>';
                }
            }
        }
        ?> 
        <p class="more_attr">More</p>
        <p class="less_attr">Less</p>
    </div>

    <div class="right_product">
        <?php if(count($subcategories) !== 0):?>
        <div class="filters">           
          <h2>Categories:</h2>    
          <div class="jcarousel category_carousel cc2_wrapper">
            <div class="cc2">
                <?PHP foreach ($subcategories as $rows): ?>
                <div class="">
                    <a class="cc2_title" href="<?=base_url()?>category/<?php echo $rows['id_cat'];?>/<?php echo es_url_clean($rows['name']);?>.html">
                        <span><?php echo $rows['name'];?></span></a>
                        <?PHP if(count($rows['popular'])>0): ?>
                        <span class="cat_carousel_img_con"><span class="cat_carousel_img"><img src="<?= base_url().$rows['popular'][0]['path'].'categoryview/'.$rows['popular'][0]['file']; ?>"></span></span><br />
                        <div class="cc2_prod_name"><a href="<?PHP echo base_url()."item/".$rows['popular'][0]['id_product']."/".es_url_clean($rows['popular'][0]['product']); ?>.html" title="<?PHP echo $rows['popular'][0]['product']; ?>"><span><?PHP echo $rows['popular'][0]['product']; ?></span></a></div>
                        <span class="recommended_product_price">PHP <?php echo number_format($rows['popular'][0]['price'],2,'.',',');?></span>
                        <?PHP endif; ?>
                    </div>
                    <?PHP endforeach;?>
                </div>  
            </div>
            <!-- Controls -->
            <a href="#" class="jcarousel-control-prev inactive category_carousel2_prev">&lsaquo;</a>
            <a href="#" class="jcarousel-control-next inactive category_carousel2_next">&rsaquo;</a>            
        </div>
    <?php endif; ?>        
    <div class="clear"></div>
    <p class="search_result"><!-- Showing 1 - 48 of 13,152 Results --></p>

    Sort by: 
    <select data-url="<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']).'&sop=';  ?>" id="sort_order"> 
        <?php 
        if(isset($_GET['sop'])){
            if($_GET['sop'] == "hot"){
                ?>    
                <option value="hot">Hot</option>
                <option value="bestmatch">Best Match</option>
                <option value="new">New</option>
                <option value="popular">Popular</option>
                <?php
            }
            elseif($_GET['sop'] == "new"){
                ?>
                <option value="new">New</option>   
                <option value="bestmatch">Best Match</option>
                <option value="hot">Hot</option>
                <option value="popular">Popular</option>
                <?php
            }
            elseif ($_GET['sop'] == "popular") { ?>    
            <option value="popular">Popular</option>
            <option value="bestmatch">Best Match</option>
            <option value="hot">Hot</option>
            <option value="new">New</option>
            <?php
        }
        else{ ?>
        <option value="bestmatch">Best Match</option>
        <option value="hot">Hot</option>
        <option value="new">New</option>
        <option value="popular">Popular</option>
        <?php
    }
}else{
    ?>
    <option value="bestmatch">Best Match</option>
    <option value="hot">Hot</option>
    <option value="new">New</option>
    <option value="popular">Popular</option>
    <?php   } ?>
</select>

<?php
$typeOfViewActive = '<div id="list" class="list "></div>
<div id="grid" class="grid grid-active"></div>';
if(isset($_COOKIE['view']))
{
    $cookieView = $_COOKIE['view'];
    if($cookieView == "list"){
        $typeOfViewActive = '<div id="list" class="list list-active"></div>
        <div id="grid" class="grid"></div>';
    }else{
     $typeOfViewActive = '<div id="list" class="list "></div>
     <div id="grid" class="grid grid-active"></div>';
 }
}
echo $typeOfViewActive;
?> 
<div class="clear"></div>
<div id="product_content">     
    <?php
    if (isset($items)) {
        for ($i = 0; $i < sizeof($items); $i++) {
            $pic = explode('/', $items[$i]['product_image_path']);

            $typeOfView = "product";
            if(isset($_COOKIE['view']))
            {
                $cookieView = $_COOKIE['view'];
                if($cookieView == "list"){
                    $typeOfView = "product-list";
                }else{
                 $typeOfView = "product";
             }
         }
         ?>
         <div class="<?php echo $typeOfView; ?>">
            <a href="<?= base_url() ?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo es_url_clean($items[$i]['name']); ?>.html"><span class="prod_img_wrapper"><span class="prod_img_container"><img alt="<?php echo $items[$i]['name']; ?>" src="<?php echo base_url() . $pic[0] . '/' . $pic[1] . '/' . $pic[2] . '/' . $pic[3] . '/' . 'categoryview' . '/' . $pic[4]; ?>"></span></span></a>
            <h3>
                <a href="<?= base_url() ?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo es_url_clean($items[$i]['name']); ?>.html"><?php echo html_escape($items[$i]['name']); ?></a>
            </h3>

            <div class="price-cnt">
                <div class="price">
                    Php <?php echo number_format($items[$i]['price'], 2); ?>
                </div>
            </div>
            <div class="product_info_bottom">
                <div>Condition: <strong><?php echo html_escape($items[$i]['condition']); ?></strong></div>
                <!-- <div>Sold: <strong>32</strong></div> -->
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
<input type="hidden" id="scroll_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
<div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
</div>

</div>
</div>   
</div>




<script src="<?= base_url() ?>assets/JavaScript/js/jquery.easing.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.scrollUp.min.js" type="text/javascript"></script>
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

<script src="<?= base_url() ?>assets/JavaScript/js/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/JavaScript/categorynavigation.js" type="text/javascript"></script>
<?php
$price1= "";
$price2 = "";

if(isset($_GET['price'])){
    if(strpos($_GET['price'], 'to') !== false)
    {
        $price = explode('to',  $_GET['price']);          
        $price1= (double)$price[0];
        $price2 = (double)$price[1];
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
    var base_url = '<?php echo base_url(); ?>';
    var offset = 1;
    var request_ajax = true;
    var ajax_is_on = false;
    var objHeight = $(window).height() - 50;
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
     var csrftoken = $('#scroll_csrf').val();
     $(window).scroll(function(event) {
        var st = $(this).scrollTop();
        if(st > last_scroll_top){
            if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {
                if (request_ajax === true && ajax_is_on === false) {
                    ajax_is_on = true; 
                    $.ajax({
                        url: base_url + 'product/loadproduct',
                        data:{page_number:offset,id_cat:'<?php echo $id_cat ?>',type:type,parameters:'<?php echo json_encode($_GET); ?>', es_csrf_token : csrftoken},
                        type: 'post',
                        async: false,
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
                            $(".loading_products").hide();
                        }
                    });
                }
            }
        }
        last_scroll_top = st;
        jQuery(".loading_products").fadeOut();   
    });
    // END OF INFINITE SCROLLING FUNCTION


    $(document).on('click','.smr_btn',function () {
        ajax_is_on = false;
        $('.phides').show();
        $(this).hide();
    });

    $(".cbs").click(function() {
        window.location = "<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>" + this.value;
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
        var v = parseFloat(this.value);
        if (isNaN(v)) {
            this.value = '';
        } else {
            this.value = v.toFixed(2)
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
        }else{
            document.location.href=url;
        }     
    });
 
    $( "#price1 , #price2" ).keypress(function() {
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