(function ($) {

    var checkIfUrlParamExist = function(field,url)
    { 
        if(url.indexOf('?' + field + '=') != -1){
            return true;
        }
        else if(url.indexOf('&' + field + '=') != -1){
            return true;
        }

        return false
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

    var getParameterByName = function(name)
    {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    var getClosestNumber = function(arr, closestTo){
        var closest = Math.max.apply(null, arr);
        for(var i = 0; i < arr.length; i++){
            if(arr[i] >= closestTo && arr[i] < closest){
                closest = arr[i];
            }
        }

        return closest;
    }

    var scrollToElement = function(ele) {
        var body = $("html, body");
        body.animate({scrollTop:ele.offset().top}, '500', 'swing', function() { 
            canRequestAjax = true;
        });
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

    $(".btn-add-to-cart").on("click", function(){
        var $button = $(this);
        var productId = $button.data('productid'); 
        var slug = $button.data('slug');        
        addToCart(productId, null, null, true, slug);
    });
    
    $( ".price-field" ).keypress(function(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57)){
            return false;
        }
        validateWhiteTextBox(this);

        return true;
    });

    $(document).on('change',".price-field",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        }
        else {
            tempval = Math.abs(v);
            this.value = replaceNumberWithCommas(tempval.toFixed(2));
        }
    });

    $('.btn-filter-price').click(function() { 
        var tableParent = $(this).closest('table');
        var price1 = parseFloat(tableParent.find('#filter-from-price').val().replace(/,/g , ""));
        var price2 = parseFloat(tableParent.find('#filter-to-price').val().replace(/,/g , "")); 

        currentUrl = removeParam("startprice", currentUrl);
        currentUrl = removeParam("endprice", currentUrl); 
        if(!isNaN(price1) && !isNaN(price2)){ 
            currentUrl = currentUrl +'&startprice='+ price1 +'&endprice='+price2;
        }

        if(isNaN(price1) && !isNaN(price2)){ 
            validateRedTextBox('#filter-from-price');
        }
        else if(!isNaN(price1) && isNaN(price2)){
            validateRedTextBox('#filter-to-price'); 
        }
        else if(price1 > price2){ 
            validateRedTextBox("#filter-from-price,#filter-to-price");  
        }
        else{
            validateWhiteTextBox("#filter-from-price,#filter-to-price"); 
            document.location.href = currentUrl;
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

        document.location.href = currentUrl;
    });

    $(document).on('change',"#filter-condition",function () {
        var $this = $(this);
        var value = $this.val();
        var head = "condition"; 
        currentUrl = removeParam(head,currentUrl);
        if(value != ""){
            currentUrl = currentUrl +'&'+head+'='+ value; 
        } 
        document.location.href = currentUrl;
    });

    $(document).on('change',"#filter-sort",function () {
        var $this = $(this);
        var value = $this.val();
        var head = "sortby";  
        currentUrl = removeParam(head,currentUrl);
        if(value != ""){
            currentUrl = currentUrl +'&'+head+'='+ value; 
        }  
        document.location.href = currentUrl;
    });
 
    var currentUrl = $('#hidden-currentUrl').val();
    var typeView = $('#hidden-typeView').val();
    var loadUrl = $('#hidden-loadUrl').val();
    var allQueryString = $('#hidden-queryString').val();
    var currentSegment = $("#hidden-segment").val();
 
    var lastPage = $("#hidden-totalPage").val(); 
    var canRequestAjax = true;
    var lastScroll = 0;
    var isScrollUp = false; 

    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');  

    var $body = $('body');
    var heightLimit = 360;
    if($('.search-parallax-container').length > 0){
        heightLimit += $('.search-parallax-container').height();
    }

    var sticky_offset;
    var searchParallaxSlide_height = $(".search-parallax-container").outerHeight();
    var offsetPagination = 1200;
    if(searchParallaxSlide_height == 523){
        offsetPagination = 1500;
    }

    $(document).ready(function() {
        var original_position_offset = ($('#sticky-pagination').length <=0 ) ? 0 : $('#sticky-pagination').offset() ;
        sticky_offset = original_position_offset.top;
        $('#sticky-pagination').css('position', 'fixed').css('width', '66%').css('bottom', '-400px');
    });

    $(window).scroll(function(event) {
        var scroll = $(this).scrollTop(); 
        var currentPage = parseInt($(".nav li.active > a").text().trim());
        if(scroll < lastScroll){
            isScrollUp = true;
            var requestPage = currentPage - 2;
            var presentPage = currentPage - 1;
            var element = $('.search-results-container'); 
            var appendString = '<div class="group-container row loading-row" data-id="'+presentPage+'" id="page-'+presentPage+'">\
                <div class="loading-bar-container">\
                    <span class="loading-label">\
                        Loading page '+presentPage+' of '+lastPage+'\
                    </span> \
                    <div class="outer-progress-box" style="">\
                        <div id="loading-box"></div>\
                    </div>\
                </div>\
            </div>';
            var hiddenElement = $('#div-holder > #page-'+presentPage);

            if (scroll <=  heightLimit) { 
                if(canRequestAjax && presentPage >= 1
                   && $('.search-results-container > #page-'+presentPage).length <= 0){ 
                    canRequestAjax = false; 
                    element.prepend(appendString);
                    $('[data-spy="scroll"]').each(function () {
                        var $spy = $(this).scrollspy('refresh')
                    });

                    if(hiddenElement.length <= 0){
                        $.ajax({
                            url: loadUrl+'&typeview='+typeView+'&page='+requestPage,
                            type: 'get', 
                            dataType: 'json', 
                            success: function(response) {
                                if (response.count > 0){
                                    $('.search-results-container > #page-'+presentPage).replaceWith(response.view);
                                    $('#div-holder').append(response.view);
                                    window.scrollTo(0, scroll + $('.search-results-container > #page-'+ currentPage).height() - 100)
                                    canRequestAjax = true;
                                    $('[data-spy="scroll"]').each(function () {
                                        var $spy = $(this).scrollspy('refresh')
                                    });
                                }
                            }
                        });
                    }
                    else{
                        $('.search-results-container > #page-'+presentPage)
                            .removeClass('loading-row')
                            .html(hiddenElement.html()); 
                        window.scrollTo(0, scroll + $('.search-results-container > #page-'+ currentPage).height() - 100)
                        $('[data-spy="scroll"]').each(function () {
                            var $spy = $(this).scrollspy('refresh')
                        });
                        canRequestAjax = true;
                    }
                }
            }
        }
        else{
            isScrollUp = false;
        }
        lastScroll = scroll;

        var sticky_height = $('#sticky-pagination').outerHeight(); 
        var window_height = $(window).height();

        if(scroll <= offsetPagination && currentPage === 1)  {
            $('#sticky-pagination').css('bottom', '-400px'); 
        }
        else{ 
            $('#sticky-pagination').css('bottom', '0px');
        }

    });
    
    $body.attr('data-spy', 'scroll').attr('data-target', '#myScrollspy').attr('data-offset','700'); 
    $body.scrollspy({
        target: "#myScrollspy", 
        offset: 700
    }); 

    $("#simplePagination").pagination({
        pages: lastPage, 
        displayedPages: 9,
    });

    if(lastPage <= 1){
        $('#sticky-pagination').hide();
    }

    $('#myScrollspy').on('activate.bs.scrollspy', function () { 
        var currentPageNumber = parseInt($(".nav li.active > a").text().trim()); 
        var afterPage = currentPageNumber + 1; 
        var requestPage = currentPageNumber - 1; 
        var appendString = '<div class="group-container row loading-row" data-id="'+currentPageNumber+'" id="page-'+currentPageNumber+'">\
            <div class="loading-bar-container">\
                <span class="loading-label">\
                    Loading page '+currentPageNumber+' of '+lastPage+'\
                </span> \
                <div class="outer-progress-box" style="">\
                    <div id="loading-box"></div>\
                </div>\
            </div>\
        </div>';
        var appendAfterString = '<div class="group-container row loading-row" data-id="'+afterPage+'" id="page-'+afterPage+'"></div>';
        var hiddenElement = $('#div-holder > #page-'+currentPageNumber);

        if(isScrollUp == false && canRequestAjax
           && $('.search-results-container > #page-'+currentPageNumber).hasClass('loading-row')){
            $('.search-results-container > #page-'+currentPageNumber).replaceWith(appendString);
            $('[data-spy="scroll"]').each(function () {
                var $spy = $(this).scrollspy('refresh')
            });
            canRequestAjax = false;
            if(hiddenElement.length <= 0){
                $.ajax({
                    url: loadUrl+'&typeview='+typeView+'&page='+requestPage,
                    type: 'get', 
                    dataType: 'json', 
                    success: function(response) { 
                        if(response.count > 0){  
                            $('.search-results-container > #page-'+currentPageNumber).replaceWith(response.view);
                            $('#div-holder').append(response.view);
                            $('.search-results-container').append(appendAfterString);
                            $('[data-spy="scroll"]').each(function () {
                                var $spy = $(this).scrollspy('refresh');
                            });
                            resizeWidth();
                        }
                        canRequestAjax = true;
                    }
                });
            }
            else{
                $('.search-results-container > #page-'+currentPageNumber)
                    .removeClass('loading-row')
                    .html(hiddenElement.html());
                $('.search-results-container').append(appendAfterString);
                $('[data-spy="scroll"]').each(function () {
                    var $spy = $(this).scrollspy('refresh')
                });
                canRequestAjax = true;
            }
        } 
 
        $('#simplePagination').pagination('selectPage', currentPageNumber);
        window.history.replaceState(null, null, currentSegment+'?'+allQueryString+'#page-'+currentPageNumber); 
    });

    $(document).on('click',".page-link",function () {
        var currentPageNumber;

        if($(this).hasClass('next')){
            currentPageNumber = parseInt($(".nav li.active > a").text().trim()) + 1;
        }
        else if($(this).hasClass('prev')){
            currentPageNumber = parseInt($(".nav li.active > a").text().trim()) - 1;
        }
        else{
            currentPageNumber = parseInt($(this).html().trim());
        }

        var afterPage = currentPageNumber + 1; 
        var requestPage = currentPageNumber - 1; 
        var mainElement = $('.search-results-container > #page-'+ currentPageNumber);
        var hiddenElement = $('#div-holder > #page-'+currentPageNumber);
        $('.individual').removeClass('active');
        $('.individual[data-page="'+currentPageNumber+'"]').addClass('active');
        $('#simplePagination').pagination('selectPage', currentPageNumber);
        if(isNaN(currentPageNumber) === false){ 
            appendString = '<div class="row loading-row" id="page-'+currentPageNumber+'">\
                <div class="loading-bar-container">\
                    <span class="loading-label">\
                        Loading page '+currentPageNumber+' of '+lastPage+'\
                    </span> \
                    <div class="outer-progress-box" style="">\
                        <div id="loading-box"></div>\
                    </div>\
                </div>\
            </div>';
            var appendAfterString = '<div class="group-container row loading-row" data-id="'+afterPage+'" id="page-'+afterPage+'"></div>';

            $('.search-results-container').html(appendString);
            $('[data-spy="scroll"]').each(function () {
                var $spy = $(this).scrollspy('refresh')
            });
            canRequestAjax = false;
            if(hiddenElement.length <= 0){
                $.ajax({
                    url: loadUrl+'&typeview='+typeView+'&page='+requestPage,
                    type: 'get', 
                    dataType: 'json', 
                    success: function(response) { 
                        if(response.count > 0){ 
                            $('.search-results-container > #page-'+currentPageNumber).replaceWith(response.view);
                            $('#div-holder').append(response.view);
                            $('.search-results-container').append(appendAfterString);
                            $('[data-spy="scroll"]').each(function () {
                                var $spy = $(this).scrollspy('refresh')
                            });
                        }
                        canRequestAjax = true;
                    }
                }); 
            }
            else{ 
                $('.search-results-container > #page-'+currentPageNumber)
                    .removeClass('loading-row')
                    .html(hiddenElement.html());
                $('.search-results-container').append(appendAfterString);
                $('[data-spy="scroll"]').each(function () {
                    var $spy = $(this).scrollspy('refresh')
                });
                canRequestAjax = true;
            }
        } 
    }); 

    $(document).ready(function(){ 
        if(window.location.hash || location.href.indexOf("#") != -1) {
            var hash = window.location.hash.substring(1);
            $('.page-link[href="#'+hash+'"]').trigger('click'); 
        } 
    });

    $( ".icon-list" ).click(function() { 
        typeView = "list"; 
        createCookie("view ", typeView, 30); 
        $(this).addClass("active-view");
        $(".icon-grid").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).addClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-6").removeClass("col-sm-3");
            $( ".col-search-item" ).addClass("col-xs-12");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });
    
    $( ".icon-grid" ).click(function() {
        typeView = "grid"; 
        createCookie("view ", typeView, 30); 
        $(this).addClass("active-view");
        $(".icon-list").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).removeClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-12");
            $( ".col-search-item" ).addClass("col-sm-3").addClass("col-xs-6");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });

    //Loading bar animation
    var i = 0;
    var max_value = 310;
    var interval = 0;

    function render() {
        $("#loading-box").css("width", i + "px");
        i++;
        if(i > max_value) {
            i = 0;
        }
        if(i == max_value) {
            clearInterval(interval);
            $("#loading-box").css("backgroundColor", "#ff893a");
            setTimeout(function() {
              startSetInterval();
            }, 1000);
        }
    }

    function startSetInterval() {
      interval = setInterval(render, 10);
    }

    startSetInterval();

    $('.loading-label').bind('fade-cycle', function() {
        $(this).fadeOut('fast', function() {
            $(this).fadeIn('fast', function() {
                $(this).trigger('fade-cycle');
            });
        });
    });

    $('.loading-label').each(function(index, elem) {
        setTimeout(function() {
            $(elem).trigger('fade-cycle');
        }, index * 100);
    });
    $window = $(window);
    $(window).on('load resize scroll', function() {
        resizeWidth();
    }); 

    function resizeWidth()
    {
        var table = $(".search-item-list-table");
        var windowSearch = $(window).width();
        if(windowSearch <= 1025){
            var mo = windowSearch - 724;
            $(".search-meta-hand").css("width", mo);
        }

        if(windowSearch <= 769){
            var mo = windowSearch - 161;
            $(".search-meta-hand").css("width", mo);
            $(".search-results-container").css("margin-bottom", "100px");
        }

        if(windowSearch <= 598){
            var mo = windowSearch - 141;
            $(".search-meta-hand").css("width", mo);
        }

        if(windowSearch > 1025){
            $(".search-meta-hand").css("width", "441px");
        }

        if(windowSearch > 769){
            $(".simplemodal-close").trigger("click");
        } 
    }

    $( ".list-filter-search" ).clone(true).appendTo( ".filter-modal");

    $('.col-filter').click(function (e) {
        $('.filter-modal').modal();
        $('.simplemodal-container').addClass("filter-modal-container");
        return false;
    });

    $('.col-categories').click(function (e) {
        $('.category-modal').modal();
        $('.simplemodal-container').addClass("filter-modal-container");
        return false;
    });

    var getHeightSearch = $(window).height();
    var getHeightFilter = $(".container-filter").height();
    var offsetTopData = 0;

    if(getHeightSearch < getHeightFilter){
         offsetTopData = getHeightSearch - getHeightFilter -50;
    }
         
    $window.on('load resize', function() {
        var widthOff = $(window).width();
        var heightHead = 200;
        var heightBanners = $(".search-parallax-container").outerHeight();
        var heightCategory = $("#category").outerHeight();

        var totalTopMargin = heightHead + heightBanners + heightCategory + 40 -800;

        if(widthOff <= 720){
            $window.scrollTop(100);
            $(".panel-filter-search-cont").removeClass("container-filter");
        }

        if(widthOff > 720){
            $window.scrollTop(100);
            $("#scrollUp").trigger("click");
            $(".panel-filter-search-cont").addClass("container-filter").removeAttr("style");
            $.stickysidebarscroll(".container-filter",{offset: {top: offsetTopData, bottom: 100}});
        }

        var tabSrc= $( document ).find(".tab-head-container a.active").attr('src');
        $("#"+tabSrc).show();
        $(".tab-head-container a").click(function(){
            var $this = $(this);
            var $segment = $this.data('segment');
            document.location.href = '/search/'+$segment+'?'+allQueryString;
        });
    });

    $(document).find('[rel=tooltiplist]').tooltip({
        placement : 'top'
    });

}(jQuery));


