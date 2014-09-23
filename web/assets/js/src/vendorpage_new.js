
var memconf = {
    ajaxStat : null,
    csrftoken: $("meta[name='csrf-token']").attr('content'),
    csrfname: $("meta[name='csrf-name']").attr('content'),
    vid: $('#vid').val(),
    vname: $('#vname').val(),
    order: 1,
    orderBy: 1
};

(function ($) {

    $('.sort_select').on('change',function(){
        memconf.orderBy = $(this).val();
        var group = $(this).data('group');
        $('#def-'+group+' > .product-paging').remove();
        $('#paginationDiv-'+group+' > center > ul > .pagination-indiv:first').trigger('click');
    }); 

    $(document.body).on('click','.icon-grid',function() {
        var view = $("div.view").attr("class");
        if(view == "view row row-items list"){
            $('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-lg-3 col-md-4 col-xs-6 thumb");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
        }
    });

    $(document).on('click','.icon-list',function() {   
        var view = $("div.view").attr("class");
        if(view == "view row row-items grid"){
            $('div.view').removeClass("view row row-items grid").addClass("view row row-items list");
            $('div.col-lg-3').removeClass("col-lg-3 col-md-4 col-xs-6 thumb").addClass("col-md-12 thumb");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view").addClass("gv fa fa-th-large fa-2x icon-view icon-grid");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list").addClass("lv fa fa-th-list fa-2x icon-view icon-list active-view");
        };
    });

    $('.tab_categories').on('click', function(){
        var divId = $(this).attr('data-link');
        $('.category-products').hide();
        $(divId).show();
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

    var currentUrl = $('#hidden-currentUrl').val();

    $('#btnFilter').click(function() {
        var price1 = parseFloat($('#price1').val());
        var price2 = parseFloat($('#price2').val()); 
        var condition = $("#condition-filter").val();

        currentUrl = removeParam("condition", currentUrl);
        currentUrl = removeParam("startprice", currentUrl);
        currentUrl = removeParam("endprice", currentUrl);

        if(condition != ""){
            currentUrl = currentUrl +'&condition='+condition;
        }

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

var ItemListAjax = function(CatDiv,page)
{
    var catId = CatDiv.attr("data-catId");
    var catType = CatDiv.attr("data-catType");
    var loadingDiv = CatDiv.find('div.loading_div');
    var productPage = CatDiv.find('.product-paging');
    var currentQueryString = $("#queryString").val();

    memconf.ajaxStat = jQuery.ajax({
        type: "GET",
        url: config.base_url+'memberpage/'+'vendorLoadProducts',
        data: "vid="+memconf.vid+"&vn="+memconf.vname+"&cid="+catId+"&ct="+catType+
            "&p="+page+"&ob="+memconf.orderBy+"&o="+memconf.order+"&qs="+currentQueryString+
            "&"+memconf.csrfname+"="+memconf.csrftoken,
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

            if(productPage.lengt > 0){
                CatDiv.find('.product-paging:last').after(obj.htmlData);    
            }
            else{
                CatDiv.find('.loading_div').after(obj.htmlData);
            }
            
        } 
    });
}
 

