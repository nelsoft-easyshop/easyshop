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
        console.log(group);
        $('#paginationDiv-'+group+' > center > ul > .pagination-indiv:first').trigger('click');
    });

})(jQuery);

(function ($) {

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

})(jQuery);

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
 

