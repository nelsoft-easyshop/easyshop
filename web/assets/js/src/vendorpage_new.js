/**
 * Function to handle display of Price Value
 */
function ReplaceNumberWithCommas(thisnumber){
    //Seperates the components of the number
    var n = thisnumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

(function ($) {
    
    var ORDER_DIRECTION_DESC = 1;
    var ORDER_DIRECTION_ASC = 2;
    var ORDER_PRODUCTS_BY_SORTORDER = parseInt($('.order-by-default').val(), 10);
    var ORDER_PRODUCTS_BY_CLICKCOUNT =  parseInt($('.order-by-popularity').val(), 10);
    var ORDER_PRODUCTS_BY_LASTCHANGE  = parseInt($('.order-by-lastmodified').val(), 10);
    var ORDER_PRODUCTS_BY_HOTNESS = parseInt($('.order-by-hotness').val(), 10);
    
    var memconf = {
        ajaxStat : null,
        csrftoken: $("meta[name='csrf-token']").attr('content'),
        csrfname: $("meta[name='csrf-name']").attr('content'),
        vid: $('#vid').val(),
        vname: $('#vname').val(),
        order: 1,
        orderBy: 0,
        condition: "",
        lprice: "",
        uprice: "",
        countfiltered: 0
    };
    
    $(document).ready(function(){
        memconf.orderBy =  parseInt($('.sort_select').val(), 10);
        if(memconf.orderBy === ORDER_PRODUCTS_BY_SORTORDER){
            memconf.order = ORDER_DIRECTION_ASC;
        }
        
        var isSearching = $('#def-search').length > 0;
        if(isSearching){
            $('.tab_categories[data-link="#def-search"]').click(); 
        }
        else{
            $('.tab_categories').first().click(); 
        }
        
    });

    /**
     *  Behavioral functions
     */
    $('.sort_select').on('change',function(){
        memconf.order = ORDER_DIRECTION_DESC;
        memconf.orderBy =  parseInt($(this).val(), 10);
        if(memconf.orderBy === ORDER_PRODUCTS_BY_SORTORDER){
            memconf.order = ORDER_DIRECTION_ASC;
        }
        var catDiv = $('.category-products.active');
        
        var $paginationContainer = catDiv.find('.simplePagination');
        $paginationContainer.pagination('selectPage', 1);
        $('.product-paging').remove();
        ItemListAjax(catDiv,1);
    }); 

    
       
    $(document).on('click', '.page-link', function(){
        return false;
        var $this = $(this);
        var $pagination = $this.closest('ul.pagination');
        if($this.hasClass('next')){
            page = parseInt($pagination.find("li.active > span").text().trim()) + 1;
        }
        else if($this.hasClass('prev')){
            page = parseInt($pagination.fin("li.active > span").text().trim()) - 1;
        }
        else{
            page = parseInt($this.html().trim());
        }

        var catDiv = $this.closest('div.category-products');

        var pageDiv = catDiv.find('.product-paging[data-page="'+page+'"]');
        var paginationContainer = catDiv.find('.pagination-container');
   
        return false;

        if(pageDiv.length === 1){
            var lastPage = $this.parent('ul').attr('data-lastpage');
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
        var $this = $(this);
        var divId = $(this).attr('data-link');
        var $div = $(divId);
        var pagingDiv = $div.find('.product-paging');
        var productCount = parseInt($div.attr('data-productcount'));
   
        $('.category-products').removeClass('active').hide();
        $div.addClass('active').show();

        $('.tab_categories').find('.selected-marker').hide();

        var htmlText = $this.find('.catText').text();
        $( ".catText" ).each(function(index) {
            if(htmlText === $this.text()) {
                $this.closest("li").find(".selected-marker").show();
            }
        });

        if(pagingDiv.length === 0 && productCount !== 0){
            ItemListAjax($div, 1);
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

    /**
    *  Filter Button function
    */
    $('#filter-btn').on('click', function(){
        var activeCategoryProductsDiv = $('.category-products.active');
        var condition = $(this).closest("ul").find('#filter-condition').val();
        var lprice = $.trim($(this).closest("ul").find('#filter-lprice').val());
        lprice = lprice.replace(new RegExp(",", "g"), '');
        lprice = parseFloat(lprice);
        var uprice = $.trim($(this).closest("ul").find('#filter-uprice').val());
        uprice = uprice.replace(new RegExp(",", "g"), '');
        uprice = parseFloat(uprice); 
        memconf.condition = condition;
        memconf.lprice = !isNaN(lprice) ? lprice : "";
        memconf.uprice = !isNaN(uprice) ? uprice : "";
        memconf.countfiltered = memconf.uprice !== "" || memconf.lprice !== "" || memconf.condition !== "" ? 1 : 0;

        if(isNaN(lprice) && !isNaN(uprice)){ 
            validateRedTextBox('#filter-lprice');
            return false;
        }
        else if(!isNaN(lprice) && isNaN(uprice)){
            validateRedTextBox('#filter-uprice'); 
            return false;
        }
        else if(lprice > uprice){ 
            validateRedTextBox("#filter-lprice,#filter-uprice");  
            return false;
        }
        validateWhiteTextBox("#filter-lprice,#filter-uprice");  

        $('.product-paging').remove();
        ItemListAjax(activeCategoryProductsDiv,1, true);

        $.modal.close();
    });

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

    function ItemListAjax(CatDiv,page, isRequestPagination)
    {
        isRequestPagination = typeof isRequestPagination !== "undefined" &&
                              isRequestPagination !== null ? isRequestPagination:
                              false;
        if(CatDiv.length < 1){
            return false;
        }
        var catId = CatDiv.attr("data-catId");
        var catType = CatDiv.attr("data-catType");
        var loadingDiv = CatDiv.find('div.loading_div');
        var productPage = CatDiv.find('.product-paging');
        var currentQueryString = $("#queryString").val();
        var paginationContainer = CatDiv.find('.simplePagination');
        var isCustom = CatDiv.attr("data-isCustom");

        memconf.ajaxStat = jQuery.ajax({
            type: "GET",
            url: '/store/vendorLoadProducts',
            data: "vendorId="+memconf.vid+"&vendorName="+memconf.vname+"&catId="+catId+"&catType="+catType+
                "&page="+page+"&orderby="+memconf.orderBy+"&order="+memconf.order+"&queryString="+currentQueryString+"&condition="+memconf.condition+"&lowerPrice="+memconf.lprice+"&upperPrice="+memconf.uprice+
                "&count="+memconf.countfiltered+"&"+memconf.csrfname+"="+memconf.csrftoken+"&isCustom="+isCustom+"&hasPagination="+isRequestPagination,
            beforeSend: function(){
                loadingDiv.show();
                productPage.hide();
                paginationContainer.hide();
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

                if(isRequestPagination && typeof obj.pagination !== 'undefined'){
                    paginationContainer.pagination('destroy');
                    paginationContainer.html('');
                    paginationContainer.html(obj.pagination);
                    initializePagination(paginationContainer, obj.pageCount);
                }
                
                paginationContainer.show();
                CatDiv.find('[rel=tooltiplist]').tooltip({
                    placement : 'top'
                });
            }
        });
    }

    /********* DESIGNER ************/
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
    
    $(document).ready(function(){
        $(".list-category > li > a").click(function(){
            var $this = $(this);
            var subCatContainer = $this.parents("li").find(".list-sub-category");
            $(".list-sub-category").slideUp("fast");
            $(".list-category > li > a > .fa").removeClass("toggleIcon");

            if(subCatContainer.is(":visible")){
                subCatContainer.slideUp("fast");
                $this.find(".fa").removeClass("toggleIcon");
            }else{
                $this.parents("li").find(".list-sub-category").slideToggle("fast").toggleClass("toggle");
                $this.find(".fa").toggleClass("toggleIcon");
            }
            $.modal.close();
        });
    });
    
    
    $('.simplePagination').each(function(){
        var $paginationContainer = $(this);
        var lastPage = $paginationContainer.find('ul').data('lastpage');
        initializePagination($paginationContainer, lastPage);
    });

    function initializePagination($paginationContainer, lastPage)
    {
        $paginationContainer.pagination({
            pages: lastPage, 
            displayedPages: 9,
            listStyle: 'pagination pagination-items',
            hasHref: false,
            onPageClick: function(page, event){
                var $catDiv = $paginationContainer.closest('div.category-products');
                var $pageDiv = $catDiv.find('.product-paging[data-page="'+page+'"]');
                if($pageDiv.length === 1){
                    var lastPage = $paginationContainer.find('ul').attr('data-lastpage');
                    var previousPage = page - 1 < 1 ? 1 : page - 1;
                    var nextPage = page + 1 <= lastPage ? page + 1 : lastPage;
                    $catDiv.find('.product-paging').hide();
                    $pageDiv.show();
                }
                else{
                    ItemListAjax($catDiv,page);
                }
                $('html,body').scrollTo(450); 
            }
        });
        
    }
    
    
})(jQuery);
