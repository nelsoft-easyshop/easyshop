(function ($) {

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

    $('.btn-filter-price').click(function() { 
        var price1 = parseFloat($('#filter-from-price').val());
        var price2 = parseFloat($('#filter-to-price').val()); 

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

    // START OF INFINITE SCROLLING FUNCTION 
    var currentUrl = $('#hidden-currentUrl').val();
    var typeView = $('#hidden-typeView').val(); 
    var emptySearch = $('#hidden-emptySearch').val();
    var loadUrl = $('#hidden-loadUrl').val();

    var page = 1; 
    var canRequestAjax = true;
    var isEmptySearch = emptySearch != "" ? false : true; 
    var lastScroll = 0;

    var type = 1;
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    var existingArray = [page];
    var currentExistingPage = page; 
    var betweenNumbers = []; 

    $(window).scroll(function(event) {
        var st = $(this).scrollTop();
        if(st > lastScroll){
            // upscroll code
            if ($(window).scrollTop() + 700 > $(document).height() - $(window).height()) {
                if (canRequestAjax === true && isEmptySearch === false) {
                    isEmptySearch = true;  
                    $.ajax({
                        url: loadUrl+'&typeview='+typeView+'&page='+page,
                        type: 'get', 
                        dataType: 'json',
                        onLoading:$(".loading_products").html('<img src="/assets/images/orange_loader.gif" />').show(),
                        success: function(response) {
                            if(response.count > 0){ 
                                page++;
                                var closestNumber = getClosestNumber(existingArray, page);
                                if($('.search-results-container > #page-'+ page).length <= 0){
                                    if(typeof closestNumber === 'undefined'){ 
                                        $('.search-results-container').append(response.view); 
                                    }
                                    else{
                                        if(closestNumber < page){
                                            $('.search-results-container > #page-'+ closestNumber).after(response.view);
                                        }
                                        else{
                                            $('.search-results-container > #page-'+ closestNumber).before(response.view);
                                        }
                                    } 
                                }
                                existingArray.push(page);  
                                $('[data-spy="scroll"]').each(function () {
                                    var $spy = $(this).scrollspy('refresh');
                                }); 
                                isEmptySearch = false;
                            }
                           $(".loading_products").fadeOut();
                        }
                    });
                }
            }
        } 
        else {
            // upscroll code
        }
        lastScroll = st;
    });
    // END OF INFINITE SCROLLING FUNCTION

    $.stickysidebarscroll("#search-tips-container",{offset: {top:50, bottom: 600}});
    $.stickysidebarscroll("#filter-panel-container",{offset: {top: -60, bottom: 600}});
    $('body').attr('data-spy', 'scroll').attr('data-target', '#myScrollspy').attr('data-offset','0'); 
    $('body').scrollspy({target: "#myScrollspy"});
    $("#simplePagination").pagination({
        pages: $("#hidden-totalPage").val(), 
        displayedPages: 9,
    });

    $('#myScrollspy').on('activate.bs.scrollspy', function () {
        var currentPageNumber = parseInt($(".nav li.active > a").text().trim()); 
        if(currentExistingPage > currentPageNumber 
            && currentExistingPage - 1 != currentPageNumber 
            && canRequestAjax) {
            for (var i = currentPageNumber + 1; i <  currentExistingPage; i++) { 
                if ($.inArray(existingArray, i) == -1
                    && $('.search-results-container > #page-'+ i).length <= 0){
                    requestPage(i, false);
                }
            } 
        }
        else if($('.search-results-container > #page-'+ currentPageNumber).hasClass('loading-row')
                && canRequestAjax){
            requestDivBefore(currentPageNumber);
            requestPage(currentPageNumber, false);
        }
 
        currentExistingPage = currentPageNumber;
        $('#simplePagination').pagination('selectPage', currentPageNumber);
    });

    var requestPage = function(pageNumber, scrollAfter) {
        var requestPage = pageNumber - 1; 
        var appendString;
        var closestNumber;  

        closestNumber = getClosestNumber(existingArray, pageNumber);
        if($('.search-results-container > #page-'+ pageNumber).length <= 0){
            appendString = "<div class='row loading-row' id='page-"+pageNumber+"'>\
            <center><img src='/assets/images/loading/preloader-whiteBG.gif' /></center>\
            </div>";
            if(closestNumber < pageNumber){
                $('.search-results-container > #page-'+ closestNumber).after(appendString);
            }
            else{
                $('.search-results-container > #page-'+ closestNumber).before(appendString);
            }
        }
        if(scrollAfter){
            scrollToElement($('.search-results-container > #page-'+ pageNumber));
        } 
        existingArray.push(pageNumber); 

        $.ajax({
            url: loadUrl+'&typeview='+typeView+'&page='+requestPage,
            type: 'get', 
            dataType: 'json', 
            success: function(response) { 
                if(response.count > 0){
                    if($('.search-results-container > #page-'+ pageNumber).length > 0){
                        $('.search-results-container > #page-'+ pageNumber).replaceWith(response.view);
                    }
                    $('[data-spy="scroll"]').each(function () {
                        var $spy = $(this).scrollspy('refresh');
                    }); 
                    isEmptySearch = false;
                }
                page = Math.max.apply(Math,existingArray) - 1;
                $(".loading_products").fadeOut();
            }
        }); 
    }

    var requestDivBefore = function(currentPageNumber) {
        var requestPage = currentPageNumber - 1; 
        var appendString;
        var closestNumber; 
        closestNumber = getClosestNumber(existingArray, requestPage);
        if($('.search-results-container > #page-'+ requestPage).length <= 0
             && requestPage > 1){
            existingArray.push(requestPage); 
            appendString = "<div class='row loading-row' id='page-"+requestPage+"'>\
            <center><img src='/assets/images/loading/preloader-whiteBG.gif' /></center>\
            </div>"; 
            if(closestNumber < requestPage){
                $('.search-results-container > #page-'+ closestNumber).after(appendString);
            }
            else{
                $('.search-results-container > #page-'+ closestNumber).before(appendString);
            }
        }
    }

    $(document).on('click',".page-link",function () {
        var currentPageNumber = parseInt($(this).html().trim()); 
        if(isNaN(currentPageNumber) === false){
            currentExistingPage = currentPageNumber;
            canRequestAjax = false;

            requestDivBefore(currentPageNumber);
            if($('.search-results-container > #page-'+ currentPageNumber).length <= 0){
                requestPage(currentPageNumber, true);
            }
            else{
                scrollToElement($('.search-results-container > #page-'+ currentPageNumber));
                page = Math.max.apply(Math,existingArray) - 1;
            }
        }
    });

    $( ".icon-list" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-grid").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).addClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-3");
             $( ".col-search-item" ).addClass("col-xs-12");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });
    
    $( ".icon-grid" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-list").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).removeClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-12");
            $( ".col-search-item" ).addClass("col-xs-3");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });

    //Loading bar animation
    var i = 0;
    var max_value = 300;
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


}(jQuery));


