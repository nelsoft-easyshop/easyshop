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

    var currentUrl = $('#hidden-currentUrl').val();

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

    $.stickysidebarscroll("#search-tips-container",{offset: {top:50, bottom: 600}});
    $.stickysidebarscroll("#filter-panel-container",{offset: {top: -60, bottom: 600}});
    
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
}(jQuery));


