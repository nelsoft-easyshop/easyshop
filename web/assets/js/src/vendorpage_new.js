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

    $('#sort_select').on('change',function(){
        memconf.orderBy = $(this).val();
        $('.product-paging').remove();
        $('.pagination-indiv:first').trigger('click');
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
        var pageDiv = $('.product-paging[data-page="'+page+'"]');
        var catDiv = $(this).closest('div.category-products');

        $(this).siblings('.pagination-indiv').removeClass('active');
        $(this).addClass('active');

        if(pageDiv.length === 1){
            $('.product-paging').hide();
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

    memconf.ajaxStat = jQuery.ajax({
        type: "GET",
        url: config.base_url+'memberpage/'+'vendorLoadProducts',
        data: "vid="+memconf.vid+"&vn="+memconf.vname+"&cid="+catId+"&ct="+catType+
            "&p="+page+"&ob="+memconf.orderBy+"&o="+memconf.order+"&"+memconf.csrfname+"="+memconf.csrftoken,
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

    $(document.body).on('click','.icon-grid',function() {
        var view = $("div.view").attr("class");
    
        if(view == "view row row-items list")
        {
            $('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-lg-3 col-md-4 col-xs-6 thumb");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
        }
    });

    $(document).on('click','.icon-list',function() {   
        var view = $("div.view").attr("class");
        if(view == "view row row-items grid")
        {
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

    $(document).on('click','#edit-profile-btn',function() {
        $('#display-banner-view').hide();
        $('#edit-banner-view').show();
    });

    $(document).on('click','#banner-cancel-changes',function() {
        $('#display-banner-view').show();
        $('#edit-banner-view').hide();
    });
    

})(jQuery);

