
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
        var group = $(this).data('group');
        $('#def-'+group+' > .product-paging').remove();
        $('#paginationDiv-'+group+' > center > ul > .pagination-indiv:first').trigger('click');
    }); 

    $('.pagination-maxleft').on('click', function(){
        $(this).siblings('.pagination-indiv:first').trigger('click');
    });
    $('.pagination-maxright').on('click', function(){
        $(this).siblings('.pagination-indiv:last').trigger('click');
    });

    $('.pagination-indiv').on('click', function(){
        var page = $(this).data('page');
        var group = $(this).data('group');

        var pageDiv = $('#def-'+group+' > .product-paging[data-page="'+page+'"]');
        var catDiv = $(this).closest('div.category-products');

        $(this).siblings('.pagination-indiv').removeClass('active');
        $(this).addClass('active');

        if(pageDiv.length === 1){
            $('#def-'+group+' > .product-paging').hide();
            pageDiv.show();
        }
        else{
            ItemListAjax(catDiv,page);
        }
    });

    $('.tab_categories').on('click', function(){
        var divId = $(this).attr('data-link');
        $('.category-products').removeClass('active').hide();
        $(divId).addClass('active').show();
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
            this.value = tempval.toFixed(2);
        }
    });

})(jQuery);

/**
 *  Filter Button function
 */
(function ($){

    $('#filter-btn').on('click', function(){
        var activeCategoryProductsDiv = $('.category-products.active');

        var condition = $('#filter-condition').val();
        var lprice = $.trim($('#filter-lprice').val());
        lprice = lprice.replace(new RegExp(",", "g"), '');
        lprice = parseFloat(lprice).toFixed(2);
        var uprice = $.trim($('#filter-uprice').val());
        uprice = uprice.replace(new RegExp(",", "g"), '');
        uprice = parseFloat(uprice).toFixed(2);

        memconf.condition = condition;
        memconf.lprice = !isNaN(lprice) ? lprice : "";
        memconf.uprice = !isNaN(uprice) ? uprice : "";
        memconf.countfiltered = memconf.uprice !== "" || memconf.lprice !== "" || memconf.condition !== "" ? 1 : 0;

        activeCategoryProductsDiv.find('.product-paging').remove();
        activeCategoryProductsDiv.find('li.pagination-indiv[data-page="1"]').trigger('click');
    });

})(jQuery);

/**
 *  Subscription Functions
 */
(function ($){

    $('.subscription_btn').on('click', function(){
        var $this = $(this);
        var form = $(this).siblings('form');
        var sibling = $(this).siblings('.subscription_btn');
        var isLoggedIn = parseInt($('#is_loggedin').val());
        var vendorLink = form.find('input[name="vendorlink"]').val();

        if(isLoggedIn){
            $.post(config.base_url+"memberpage/vendorSubscription", $(form).serializeArray(), function(data){
                try{
                    var obj = jQuery.parseJSON(data);
                }
                catch(e){
                    alert('There was an error while processing your request. Please try again later.');
                    return false;
                }

                if(obj.result === 'success'){
                    $this.hide();
                    sibling.show();
                }
                else{
                    alert(obj.error);
                }
            });
        }
        else{
            $.removeCookie('es_vendor_subscribe');
            $.cookie('es_vendor_subscribe', vendorLink, {path: '/'});
            window.location.href = config.base_url + 'login';
        }

    });

    $(document).ready(function(){
        var vendorLink = $.cookie('es_vendor_subscribe');
        var logInStatus = parseInt($('#is_loggedin').val());
        var subscribeStatus = $('#subscribe_status').val();
        var vendorName = $('#vendor_name').val();

        if( typeof vendorLink !== "undefined" && logInStatus && subscribeStatus === "unfollowed"){
            $('#follow_btn').trigger('click');
            alert("You are now following " + vendorName + "'s store!");
            $.removeCookie('es_vendor_subscribe');
        }
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
    var catId = CatDiv.attr("data-catId");
    var catType = CatDiv.attr("data-catType");
    var loadingDiv = CatDiv.find('div.loading_div');
    var productPage = CatDiv.find('.product-paging');
    var currentQueryString = $("#queryString").val();

    memconf.ajaxStat = jQuery.ajax({
        type: "GET",
        url: config.base_url+'memberpage/'+'vendorLoadProducts',
        data: "vendorId="+memconf.vid+"&vendorName="+memconf.vname+"&catId="+catId+"&catType="+catType+
            "&page="+page+"&orderby="+memconf.orderBy+"&order="+memconf.order+"&queryString="+currentQueryString+"&condition="+memconf.condition+"&lowerPrice="+memconf.lprice+"&upperPrice="+memconf.uprice+
            "&count="+memconf.countfiltered+"&"+memconf.csrfname+"="+memconf.csrftoken,
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

            if(obj.isCount){
                CatDiv.find('.pagination-indiv:gt('+(obj.pageCount-1)+')').hide();
            }
            else{
                CatDiv.find('.pagination-indiv').show();
            }
            
        } 
    });
}


/********* DESIGNER ************/

(function ($) {

    //create a stick nav
    var menuOffset = $('.vendor-sticky-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
    $(document).bind('ready scroll', function() {
        var docScroll = $(document).scrollTop();
        if (docScroll >= 455){
                if (!$('.vendor-sticky-nav').hasClass('sticky-nav-fixed')) {
                    $('.vendor-sticky-nav').addClass('sticky-nav-fixed').css({
                        top: '-155px'
                    }).stop().animate({
                        top: 0
                    }, 500);
                    
                }
                $('.vendor-content-wrapper').addClass('fixed-vendor-content');
            } 
        else{
                $('.vendor-sticky-nav').removeClass('sticky-nav-fixed').removeAttr('style');
                $('.vendor-content-wrapper').removeClass('fixed-vendor-content');
            }
    });

    $(document).on('click','.icon-grid',function() {
        var div = $("div.view");
        
        if( div.hasClass("view") && div.hasClass("row") && div.hasClass("row-items") && div.hasClass("list") )
        {
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

  
})(jQuery);
