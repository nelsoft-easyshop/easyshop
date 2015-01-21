

(function($) {

    var validateRedTextBox = function(idclass)
    {
        $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                    "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                    "box-shadow": "0px 0px 2px 2px #FF0000"});
        $(idclass).focus();
    } 

    var validateWhiteTextBox = function(idclass)
    {
        $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
                    "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
                    "box-shadow": "0px 0px 2px 2px #FFFFFF"});
    }

    var removeParam = function(key, sourceURL)
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
            return rtn;
        }
        return sourceURL;
    }

    var getCookie = function(name)
    {
        var regexp = new RegExp("(?:^" + name + "|;\s*"+ name + ")=(.*?)(?:;|$)", "g");
        var result = regexp.exec(document.cookie);

        return (result === null) ? null : result[1];
    }

    var createCookie = function(name, value, expires, path, domain)
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

    var checkIfUrlParamExist = function(field,url)
    { 
        if(url.indexOf('?' + field + '=') != -1)
            return true;
        else if(url.indexOf('&' + field + '=') != -1)
            return true;
        return false
    }

    var getParameterByName = function(name)
    {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

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

    $( ".priceField" ).keypress(function(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57)){
            return false;
        }
        validateWhiteTextBox(this);

        return true;
    });

    $(document).on('change',".priceField",function () {
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
        var price1 = parseFloat($('#price1').val());
        var price2 = parseFloat($('#price2').val()); 

        currentUrl = removeParam("startprice", currentUrl);
        currentUrl = removeParam("endprice", currentUrl);

        if(!isNaN(price1) && !isNaN(price2)){ 
            currentUrl = currentUrl +'&startprice='+ price1 +'&endprice='+price2;
        }

        if(isNaN(price1) && !isNaN(price2)){ 
            validateRedTextBox("#price1");
        }
        else if(!isNaN(price1) && isNaN(price2)){
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

    $('#btn_srch').click(function() {
        var price1 = parseFloat($('#price1').val());
        var price2 = parseFloat($('#price2').val()); 
 

        if(isNaN(price1) && !isNaN(price2)){ 
            validateRedTextBox("#price1");
        }
        else if(!isNaN(price1) && isNaN(price2)){
            validateRedTextBox("#price2"); 
        }
        else if(price1 > price2){
            validateRedTextBox("#price2,#price1");  
        }
        else{
            validateWhiteTextBox("#price2,#price1"); 
            $("#advsrch").submit();
        }
    });


    $('#mbtn_srch').click(function() {
        var price1 = parseFloat($('#mprice1').val());
        var price2 = parseFloat($('#mprice2').val()); 
 

        if(isNaN(price1) && !isNaN(price2)){ 
            validateRedTextBox("#mprice1");
        }
        else if(!isNaN(price1) && isNaN(price2)){
            validateRedTextBox("#mprice2"); 
        }
        else if(price1 > price2){
            validateRedTextBox("#mprice2,#mprice1");  
        }
        else{
            validateWhiteTextBox("#mprice2,#mprice1"); 
            $("#madvsrch").submit();
        }
    });

    $('.rprice').click(function() {
        var price1 = parseFloat($('#rprice1').val());
        var price2 = parseFloat($('#rprice2').val());

        currentUrl = removeParam("startprice", currentUrl);
        currentUrl = removeParam("endprice", currentUrl);

        if(!isNaN(price1) && !isNaN(price2)){
            currentUrl = currentUrl +'&startprice='+ price1 +'&endprice='+price2;
        }

        if(isNaN(price1) && !isNaN(price2)){ 
            validateRedTextBox("#price1");
        }
        else if(!isNaN(price1) && isNaN(price2)){
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
                if(head == "category"){
                    currentUrl = currentUrl +'&'+head+'='+ value;
                }
            }
            else{
                if(head == "category"){
                    currentUrl = currentUrl +'&'+head+'='+ value;
                }
                else{
                    currentUrl = currentUrl +'&'+head+'='+ paramValue+','+value; 
                }
                
            }
        }
        else{
            currentUrl = currentUrl +'&'+head+'='+ value;
        }

        document.location.href=currentUrl;
    });

    $('#list').click(function(){
        typeView = 'product-list';
        createCookie("view ", "product-list", 30); 
        $('.product').animate({opacity:0},function(){
            $('.grid').removeClass('grid-active');
            $('.list').addClass('list-active');
            $('.product').attr('class', 'product-list');
            $('.product-list').stop().animate({opacity:1},"fast");
        });
    });

    $('#grid').click(function(){
        typeView = 'product';
        createCookie("view ", "product", 30);  
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


     // START OF INFINITE SCROLLING FUNCTION 
    
    var currentUrl = $('#hidden-currentUrl').val();
    var typeView = $('#hidden-typeView').val(); 
    var emptySearch = $('#hidden-emptySearch').val();
    var loadUrl = $('#hidden-loadUrl').val();

    var offset = 1; 
    var canRequestAjax = true;
    var isEmptySearch = emptySearch != "" ? false : true;
    var objHeight=$(window).height()-50;
    var lastScroll = 0;
 
    var type = 1;
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    $(window).scroll(function(event) {
        var st = $(this).scrollTop();
        if(st > lastScroll){
            if ($(window).scrollTop() + 400 > $(document).height() - $(window).height()) {
                if (canRequestAjax === true && isEmptySearch === false) {
                    isEmptySearch = true;
                    $.ajax({
                        url: loadUrl+'&typeview='+typeView+'&page='+offset,
                        type: 'get', 
                        dataType: 'json',
                        onLoading:$(".loading_products").html('<img src="/assets/images/orange_loader.gif" />').show(),
                        success: function(response) {
                            if(response.count > 0){
                                $('#product_content').append(response.view);
                                $('#move-product').detach().appendTo('#paste-product');
                                offset++;
                                isEmptySearch = false;
                            }

                           $(".loading_products").fadeOut();
                        }
                    });
                }
            }
        }
        lastScroll = st;
    });
    // END OF INFINITE SCROLLING FUNCTION

    $("#accordion").on('click','.a-accordion-header',function() {
        var attr = $("i.adv").attr("class");
        if(attr == "adv glyphicon glyphicon-chevron-down pull-right"){
            $('.adv ').removeClass("adv glyphicon glyphicon-chevron-down pull-right").addClass("adv glyphicon glyphicon-chevron-up pull-right");
        }else if(attr == "adv glyphicon glyphicon-chevron-up pull-right"){
            $('.adv ').removeClass("adv glyphicon glyphicon-chevron-up pull-right").addClass("adv glyphicon glyphicon-chevron-down pull-right");
        }
    });



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

})( jQuery );

(function ($) {
    var p = $('.cc2_prod_name span');
    var divh = $('.cc2_prod_name').height();
    while ($(p).outerHeight()>divh) {
        $(p).text(function (index, text) {
            return text.replace(/\W*\s(\S)*$/, '...');
        });
    } 

    $(function() {
        $( ".prod_cat_drop2" ).click(function() {
          $( "#cat_nav" ).toggleClass("category_nav_plus");
          $( "#cat" ).toggleClass("active_prod_cat_drop_arrow");
        });
    });
       

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

