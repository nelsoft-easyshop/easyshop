
var memconf = {
    ajaxStat : null,
    csrftoken: $("meta[name='csrf-token']").attr('content'),
    csrfname: $("meta[name='csrf-name']").attr('content'),
    vid: $('#vid').val(),
    vname: $('#vname').val(),
    order: 1,
    orderBy: 1,
    condition: "",
    lprice: "",
    uprice: "",
    countfiltered: 0
};

/**
* Function to handle display of Price Value
**/
function ReplaceNumberWithCommas(thisnumber){
    //Seperates the components of the number
    var n = thisnumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

/**
 *  Behavioral functions
 */
(function ($) {

    $('.sort_select').on('change',function(){
        memconf.orderBy = $(this).val();
        memconf.order = 1;
        if(parseInt(memconf.orderBy,10) === 0){
            memconf.order = 2;
        }
        var catDiv = $('.category-products.active');
        $('.product-paging').remove();
        ItemListAjax(catDiv,1);
    }); 

    $('.div-products').on('click', '.extremes', function(){
        var page = $(this).attr('data-page');
        $(this).siblings('.individual[data-page="'+page+'"]').trigger('click');
    });

    $('.div-products').on('click', '.individual', function(){
        var page = $(this).data('page');
        var catDiv = $(this).closest('div.category-products');
        var pageDiv = catDiv.find('.product-paging[data-page="'+page+'"]');
        var paginationContainer = catDiv.find('.pagination-container');

        // $(this).siblings('.individual').removeClass('active');
        // $(this).addClass('active');

        if(pageDiv.length === 1){
            var lastPage = $(this).parent('ul').attr('data-lastpage');
            var previousPage = page - 1 < 1 ? 1 : page - 1;
            var nextPage = page + 1 <= lastPage ? page + 1 : lastPage;
            catDiv.find('.product-paging').hide();
            paginationContainer.find('.extremes.previous').attr('data-page', previousPage);
            paginationContainer.find('.extremes.next').attr('data-page', nextPage);
            pageDiv.show();
        }
        else{
            ItemListAjax(catDiv,page);
        }
        $('html,body').scrollTo(450); 
        
    });

    $(document).on('click', ".tab_categories", function(e){
        var divId = $(this).attr('data-link');
        var pagingDiv = $(divId).find('.product-paging');
        var productCount = parseInt($(divId).attr('data-productcount'));

        $('.category-products').removeClass('active').hide();
        $(divId).addClass('active').show();

        $('.tab_categories').find('.selected-marker').hide();

        var htmlText = $(this).find('.catText').text();
        $( ".catText" ).each(function(index) {
            if(htmlText === $(this).text()) {
                $(this).closest("li").find(".selected-marker").show();
            }
        });

        if(pagingDiv.length === 0 && productCount !== 0){
            ItemListAjax($(divId), 1);
        }
        $('html,body').scrollTo(450); 
    });

    $(".list-category").find("a[data-link='#def-0']").click();

    $(document).on('change',".price-field",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        }
        else {
            tempval = Math.abs(v);
            this.value = ReplaceNumberWithCommas(tempval.toFixed(2));
        }
    });

})(jQuery);

/**
 *  Filter Button function
 */
(function ($){
    $('#filter-btn').on('click', function(){
        var activeCategoryProductsDiv = $('.category-products.active');
        var condition = $(this).closest("ul").find('#filter-condition').val();
        var lprice = $.trim($(this).closest("ul").find('#filter-lprice').val());
        lprice = lprice.replace(new RegExp(",", "g"), '');
        lprice = parseFloat(lprice).toFixed(2);
        var uprice = $.trim($(this).closest("ul").find('#filter-uprice').val());
        uprice = uprice.replace(new RegExp(",", "g"), '');
        uprice = parseFloat(uprice).toFixed(2);
        memconf.condition = condition;
        memconf.lprice = !isNaN(lprice) ? lprice : "";
        memconf.uprice = !isNaN(uprice) ? uprice : "";
        memconf.countfiltered = memconf.uprice !== "" || memconf.lprice !== "" || memconf.condition !== "" ? 1 : 0;

        $('.product-paging').remove();
        ItemListAjax(activeCategoryProductsDiv,1);
    });

})(jQuery);



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

function ItemListAjax(CatDiv,page)
{
    if(CatDiv.length < 1){
        return false;
    }
    var catId = CatDiv.attr("data-catId");
    var catType = CatDiv.attr("data-catType");
    var loadingDiv = CatDiv.find('div.loading_div');
    var productPage = CatDiv.find('.product-paging');
    var currentQueryString = $("#queryString").val();
    var paginationContainer = CatDiv.find('.pagination-container');
    var isCustom = CatDiv.attr("data-isCustom");

    memconf.ajaxStat = jQuery.ajax({
        type: "GET",
        url: '/store/vendorLoadProducts',
        data: "vendorId="+memconf.vid+"&vendorName="+memconf.vname+"&catId="+catId+"&catType="+catType+
            "&page="+page+"&orderby="+memconf.orderBy+"&order="+memconf.order+"&queryString="+currentQueryString+"&condition="+memconf.condition+"&lowerPrice="+memconf.lprice+"&upperPrice="+memconf.uprice+
            "&count="+memconf.countfiltered+"&"+memconf.csrfname+"="+memconf.csrftoken+"&isCustom="+isCustom,
        beforeSend: function(){
            loadingDiv.show();
            productPage.hide();

            if(memconf.ajaxStat != null){
                memconf.ajaxStat.abort();
            }
        },
        success: function(data){
            memconf.ajaxStat = null;
            loadingDiv.hide();
            try{
                var obj = jQuery.parseJSON(data);
            }
            catch(e){
                alert('Failed to retrieve user product list.');
                return false;
            }

            if(productPage.length > 0){
                CatDiv.find('.product-paging:last').after(obj.htmlData);
            }
            else{
                CatDiv.find('.loading_div').after(obj.htmlData);
            }

            $(paginationContainer).children('center').html(obj.paginationData);

            CatDiv.find('[rel=tooltiplist]').tooltip({
                placement : 'top'
            });            
        }
    });
}

/********* DESIGNER ************/

(function ($) {
     $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        scrollDistance: 300, // Distance from top/bottom before showing element (px)
        scrollFrom: 'top', // 'top' or 'bottom'
        scrollSpeed: 300, // Speed back to top (ms)
        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 100, // Animation in speed (ms)
        animationOutSpeed: 100, // Animation out speed (ms)
        scrollText: '', // Text for element, can contain HTML
        scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
        scrollImg: false, // Set true to use image
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647, // Z-Index for the overlay
    });

    $(document).ready(function(){
        $('[rel=tooltiplist]').tooltip({
            placement : 'top'
        });
    });

    $("#cat-header").on('click','.a-category',function() {
        var attr = $("b.cat").attr("class");
        if(attr == "cat fa fa-minus-square-o pull-right"){
            $('b.cat').removeClass("cat fa fa-minus-square-o pull-right").addClass("cat fa fa-plus-square-o pull-right");
            
        }
        else if(attr == "cat fa fa-plus-square-o pull-right"){
            $('b.cat').removeClass("cat fa fa-plus-square-o pull-right").addClass("cat fa fa-minus-square-o pull-right");
            
        }
    });

    $("#filter-header").on('click','.a-filter',function() {
        var attr = $("b.fil").attr("class");
        if(attr == "fil fa fa-minus-square-o pull-right"){
            $('b.fil').removeClass("fil fa fa-minus-square-o pull-right").addClass("fil fa fa-plus-square-o pull-right");
        }
        else if(attr == "fil fa fa-plus-square-o pull-right"){
            $('b.fil').removeClass("fil fa fa-plus-square-o pull-right").addClass("fil fa fa-minus-square-o pull-right");
        }
    });
    
    
    $(document).on('click','.icon-grid',function() {
        var div = $("div.view");
        
        if( div.hasClass("view") && div.hasClass("row") && div.hasClass("row-items") && div.hasClass("list") )
        {
            $( "#filter-list" ).slideToggle( "slow" );
            div.removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-lg-3 col-md-4 col-xs-6 thumb");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
        }
    });

    $(document).on('click','.icon-list',function() {   
        var div = $("div.view");
    
        if( div.hasClass("view") && div.hasClass("row") && div.hasClass("row-items") && div.hasClass("grid") )
        {
            div.removeClass("view row row-items grid").addClass("view row row-items list");
            $('div.col-lg-3').removeClass("col-lg-3 col-md-4 col-xs-6 thumb").addClass("col-md-12 thumb");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view").addClass("gv fa fa-th-large fa-2x icon-view icon-grid");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list").addClass("lv fa fa-th-list fa-2x icon-view icon-list active-view");

        };
    });
    

    $(document).ready(function(){
        
        $( "#filter-list1" ).clone(true).appendTo( ".filter-modal" );

        $( "#toggle-cat" ).click(function() {
          $( "#category-list" ).slideToggle( "slow" );
        });
        $( "#toggle-filter" ).click(function() {
          $( "#filter-list1" ).slideToggle( "slow" );
        });        
        $( ".icon-list" ).click(function() {
          $( ".panel-item" ).hide();
          $( ".panel-list-item" ).fadeIn( "fast" );
        });
        
        $( ".icon-grid" ).click(function() {
          $( ".panel-list-item" ).hide();
          $( ".panel-item" ).fadeIn( "fast" );
        });
        
        $('.col-categories').click(function (e) {
            $('.categories-modal').modal();
            return false;
        });
        
        $('.col-filter').click(function (e) {
            $('.filter-modal').modal({persist:true});
            $('.simplemodal-container').addClass("filter-modal-container");
            return false;
        });
        
    });
    
    var $window = $(window);

    function checkWidthVendor() {
        var windowsize = $window.width();
        if (windowsize > 440) {
            //if the window is greater than 440px wide then turn on jScrollPane..
            $( ".close-hide" ).trigger("click");
        }
    }

    // Execute on load
    checkWidthVendor();
    // Bind event listener
    $window.resize(checkWidthVendor);
    
})(jQuery);
