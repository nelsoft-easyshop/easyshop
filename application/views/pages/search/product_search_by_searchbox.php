
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
        <input type="text" id="price1" value="<?=($this->input->get('startprice')?$this->input->get('startprice'):'')?>" maxlength=9 size=6>
        to
        <input type="text" id="price2" value="<?=($this->input->get('startprice')?$this->input->get('endprice'):'')?>" maxlength=9 size=6> 
        <input class="price" type="button" value=">>"/>

        <?php foreach ($attributes as $attrName => $attrListValue):?>
        <h3><?=$attrName?></h3>
            <ul>
            <?php foreach ($attrListValue as $key => $value):?>
                <li style="border:0px">
                    <a class="cbx" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                        <input type="checkbox" <?=(strpos($this->input->get(strtolower($attrName)),strtolower($value)) !== false)?'checked':'';?> class="cbx checkBox" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                        <label for="cbx"><?=ucfirst($value);?></label>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
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
                    <a href="<?php echo base_url() . "item/" . $value['slug']; ?>">
                        <span class="prod_img_wrapper">
                            <?php if((intval($value['isPromote']) == 1) && isset($value['percentage']) && $value['percentage'] > 0):?>
                                <span class="cd_slide_discount">
                                    <span><?php echo number_format($value['percentage'],0,'.',',');?>%<br>OFF</span>
                                </span>
                            <?php endif; ?>
                        
                            <span class="prod_img_container">
                                    <img alt="<?php echo html_escape($value['name']); ?>" src="<?php echo base_url() . $value['productImagePath']; ?>">
                            </span>
                        </span>
                    </a>
                    <h3>
                        <a href="<?php echo base_url() . "item/" . $value['slug']; ?>">
                            <?php echo html_escape($value['name']); ?>
                        </a>
                    </h3>
                    <div class="price-cnt">
                        <div class="price"> 
                            <span>&#8369;</span> <?php echo number_format($value['price'], 2);?>
                        </div>
                      
                        <?php if(isset($value['percentage']) && $value['percentage'] > 0):?>
                        <div>
                            <span class="original_price">
                                &#8369; <?php echo number_format($value['originalPrice'],2,'.',','); ?>
                            </span>
                            <span style="height: 20px;">
                                |&nbsp; <strong><?PHP echo number_format($value['percentage'],0,'.',',');?>%OFF</strong>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                   <div class="product_info_bottom">
                        <div>
                            Condition:
                            <strong>
                               <?php echo ($value['isFreeShipping'])? es_string_limit(html_escape($value['condition']),15) : html_escape($value['condition']);?>
                            </strong>
                        </div>
                        <?php if($value['isFreeShipping']): ?>
                            <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

 
<script type="text/javascript">
    var currentUrl = "<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>"; 
</script>
<script type="text/javascript">

function validateRedTextBox(idclass)
{
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
    $(idclass).focus();
} 

function validateWhiteTextBox(idclass)
{
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "box-shadow": "0px 0px 2px 2px #FFFFFF"});
}

function removeParam(key, sourceURL)
{
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

function getCookie(name)
{
    var regexp = new RegExp("(?:^" + name + "|;\s*"+ name + ")=(.*?)(?:;|$)", "g");
    var result = regexp.exec(document.cookie);

    return (result === null) ? null : result[1];
}

function createCookie(name, value, expires, path, domain)
{
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
    if (path){
        cookie += "path=" + path + ";";
    }
    if (domain){
        cookie += "domain=" + domain + ";";
    }
    document.cookie = cookie;
}

function checkIfUrlParamExist(field,url)
{
    if(url.indexOf('?' + field + '=') != -1)
        return true;
    else if(url.indexOf('&' + field + '=') != -1)
        return true;
    return false
}

function getParameterByName(name)
{
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

(function($) {

    $( "#price1 , #price2" ).keypress(function(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57)){
            return false;
        }
        validateWhiteTextBox(this);

        return true;
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

    $(document).on('change',"#price2,#price1",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        }
        else {
            tempval = Math.abs(v);
            this.value = tempval.toFixed(2);
        }
    });

    $('.price').click(function() {
        var price1 = $('#price1').val();
        var price2 = $('#price2').val();

        currentUrl = removeParam("startprice", currentUrl);
        currentUrl = removeParam("endprice", currentUrl);

        if(price1 != "" && price2 != ""){
            currentUrl = removeParam("price", currentUrl);
            currentUrl = currentUrl +'&startprice='+ price1 +'&endprice='+price2;
        }

        if(price1 == "" && price2 != ""){ 
            validateRedTextBox("#price1");
        }
        else if(price1 != "" && price2 == ""){
            validateRedTextBox("#price2"); 
        }
        else if(price1 > price2){
            validateRedTextBox("#price2,#price1");  
        }
        else{
            validateWhiteTextBox("#price2,#price1"); 
            document.location.href=currentUrl;
        }
    });

    $(".checkBox").click(function(){
        var $this = $(this);
        $this.parent('a').trigger('click');
    });

    $(".cbx").click(function(){
        var $this = $(this);
        var head = $this.data('head').toLowerCase();
        var value = $this.data('value');
        var check = checkIfUrlParamExist(head,currentUrl); 
        if(check){
            currentUrl = removeParam(head, currentUrl);
            var paramValue = getParameterByName(head);
            if (paramValue.toLowerCase().indexOf(value) >= 0){ 
                var newValue = paramValue.replace(value,'').replace(/^,|,$/g,'');
                if(newValue == ""){
                    currentUrl = currentUrl;
                }
                else{
                    currentUrl = currentUrl +'&'+head+'='+ newValue;
                }
            }
            else{
                currentUrl = currentUrl +'&'+head+'='+ paramValue+','+value; 
            }
        }
        else{
            currentUrl = currentUrl +'&'+head+'='+ value;
        }

        document.location.href=currentUrl;
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

})( jQuery );


 

$(document).ready(function(){
 


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
  // $(window).scroll(function(event) {
  //   var st = $(this).scrollTop();

  //   if(st > last_scroll_top){
  //     if ($(window).scrollTop() + 400 > $(document).height() - $(window).height()) {
  //       if (request_ajax === true && ajax_is_on === false) {
  //         ajax_is_on = true;
  //         $.ajax({
  //           url: base_url + 'search_more',
  //           data:{page_number:offset,id_cat:,type:type,parameters:, csrfname : csrftoken},
  //           type: 'post',
  //           async: false,
  //           dataType: 'json',
  //           success: function(d) {
  //              if(d == "0"){
  //                ajax_is_on = true;
  //              }else{
  //               $($.parseHTML(d.trim())).appendTo($('#product_content'));
  //               ajax_is_on = false;
  //               offset += 1;
  //           }
  //         }
  //       });
  //       }
  //     }
  //   }
  //   last_scroll_top = st;
  // });

  // END OF INFINITE SCROLLING FUNCTION
 

  });
</script>
 