(function ($) {

    $( "#dash" ).click(function() {
        $( "#my-account-menu" ).slideUp();
        var attr = $("i.m").attr("class");
        if(attr == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        
        $( "#my-store-menu" ).slideUp();
        var attr2 = $("i.a").attr("class");
        if(attr2 == "a icon-control-up toggle-down pull-right"){
            $('i.a').removeClass("a icon-control-up toggle-down pull-right").addClass("a icon-control-down toggle-down pull-right");
            $( ".ml-li" ).css("border-radius", "0px 0px 7px 7px");
            $( ".submenu-my-account" ).css("border-radius", "0px");
            $( ".f-a" ).css("border-radius", "0px");
        }
    });
    
    $( "#my-store-menu-trigger" ).click(function() {
    $( "#my-account-menu" ).slideUp();
     var attr4 = $("i.a").attr("class");
    if(attr4 == "a icon-control-up toggle-down pull-right"){
            $('i.a').removeClass("a icon-control-up toggle-down pull-right").addClass("a icon-control-down toggle-down pull-right");
            $( ".ml-li" ).css("border-radius", "0px 0px 7px 7px");
            $( ".submenu-my-account" ).css("border-radius", "0px");
            $( ".f-a" ).css("border-radius", "0px");
        }
        $( "#my-store-menu" ).slideToggle( "slow", function() {
            var attr = $("i.m").attr("class");
        if(attr == "m icon-control-down toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-down toggle-down pull-right").addClass("m icon-control-up toggle-down pull-right");
        }
        else if(attr == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        });
        
    });

    $( "#my-account-menu-trigger" ).click(function() {
        $( "#my-store-menu" ).slideUp();
        var attr3 = $("i.m").attr("class");
        if(attr3 == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        $( "#my-account-menu" ).slideToggle( "slow", function() {
            var attr = $("i.a").attr("class");
            if(attr == "a icon-control-down toggle-down pull-right"){
                $('i.a').removeClass("a icon-control-down toggle-down pull-right").addClass("a icon-control-up toggle-down pull-right");
                $( ".ml-li" ).css("border-radius", "0px");
                $( ".submenu-my-account" ).css("border-radius", "0px 0px 7px 7px");
                $( ".f-a" ).css("border-radius", "0px 0px 7px 7px");
            }
            else if(attr == "a icon-control-up toggle-down pull-right"){
                $('i.a').removeClass("a icon-control-up toggle-down pull-right").addClass("a icon-control-down toggle-down pull-right");
                $( ".ml-li" ).css("border-radius", "0px 0px 7px 7px");
                $( ".submenu-my-account" ).css("border-radius", "0px");
                $( ".f-a" ).css("border-radius", "0px");
            }
        });
    });

    $( "#info-item-1" ).click(function() {
        $( "#info-attributes-1" ).slideToggle( "slow", function() {
            var i_icon = $("i.info-item-icon-1").attr("class");
             if(i_icon == "info-item-icon-1 fa fa-plus-circle"){
                $('i.info-item-icon-1').removeClass("info-item-icon-1 fa fa-plus-circle").addClass("info-item-icon-1 fa fa-minus-circle");
                $(".text-info-icon-1").text("less info");
            }
            else if(i_icon == "info-item-icon-1 fa fa-minus-circle"){
                $('i.info-item-icon-1').removeClass("info-item-icon-1 fa fa-minus-circle").addClass("info-item-icon-1 fa fa-plus-circle");
                $(".text-info-icon-1").text("more info");
            }
        });
    });

    $(document.body).on('click',".more-info-attribute",function () {
        $this = $(this);
        $iconElement = $this.children('i');
        $spanElement = $this.children('span');
        $this.next().slideToggle( "fast", function() {
            var i_icon = $iconElement.attr("class");
             if(i_icon == "info-item-icon fa fa-plus-circle"){
                $iconElement.removeClass("info-item-icon fa fa-plus-circle").addClass("info-item-icon fa fa-minus-circle");
                $spanElement.text("less info");
            }
            else if(i_icon == "info-item-icon fa fa-minus-circle"){
                $iconElement.removeClass("info-item-icon fa fa-minus-circle").addClass("info-item-icon fa fa-plus-circle");
                $spanElement.text("more info");
            }
        });
    });

    $( "#dash" ).click(function() { 
        $("#active-items").css("display", "block");
    });

    $('.transaction-title-bought').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought').slideToggle();
        $('.on-going-transaction-list-sold').slideUp();
        $('.transaction-title-sold').removeClass("active-bar");
    });

    $('.transaction-title-sold').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold').slideToggle();
        $('.on-going-transaction-list-bought').slideUp();
        $('.transaction-title-bought').removeClass("active-bar");
    });

        $('.transaction-title-bought-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought-completed').slideToggle();
        $('.on-going-transaction-list-sold-completed').slideUp();
        $('.transaction-title-sold-completed').removeClass("active-bar");
    });

    $('.transaction-title-sold-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold-completed').slideToggle();
        $('.on-going-transaction-list-bought-completed').slideUp();
        $('.transaction-title-bought-completed').removeClass("active-bar");
    });
    
    $('.sales-title-total').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.sales-breakdown-container').slideToggle();
        $('.payout-breakdown-container').slideUp();
        $('.payout-title-total').removeClass("active-bar");
    });
    
    $('.payout-title-total').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.payout-breakdown-container').slideToggle();
        $('.sales-breakdown-container').slideUp();
       $('.sales-title-total').removeClass("active-bar");
    });
    
    $(".trans-item-info").click(function() {
        $(this).children("i").toggleClass("fa-minus-circle");
        $(this).next(".info-attributes").slideToggle();
    });

    $(".view-delivery-lnk").click(function() {
        $(this).next(".view-delivery-details").slideToggle();
    });
    
    $( "#set-default" ).hover(function() {
        $( ".default-ad-explain" ).slideToggle( "slow" );
    });
    
    $( ".map-trigger" ).click(function() {
        $( ".map-container" ).slideToggle( "slow" );
    });

    $(document.body).on('click',".individual",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $parentContainer = $this.parent().parent().parent().parent();
        var $textInput = $parentContainer.find('.search-field').val();
        var $filterInput = $parentContainer.find('.search-filter').val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val();
        isAjaxRequestForProduct($page, $textInput, $filterInput, $requestType, $container);
    });

    $(document.body).on('change','.search-filter',function () {
        var $this = $(this);
        var $page = 1;
        var $parentContainer = $this.parent().parent().parent();
        var $textInput = $parentContainer.find('.search-field').val();
        var $filterInput = $this.val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val(); 

        isAjaxRequestForProduct($page, $textInput, $filterInput, $requestType, $container);
    });

    $(".search-field").keyup(function(event){
        if(event.keyCode == 13){
            var $this = $(this);
            var $page = 1;
            var $parentContainer = $this.parent().parent().parent();
            var $textInput = $this.val();
            var $filterInput = $parentContainer.find('.search-filter').val();
            var $requestType = $parentContainer.find('.request-type').val();
            var $container = $parentContainer.find('.container-id').val(); 

            requestProduct($page, $textInput, $filterInput, $requestType, $container, true);
        }
    });

    $(document.body).on('click','.soft-delete',function () {
        var $confirm = confirm("Are you sure you want to move this item to deleted item?");
        if($confirm){
            var $this = $(this);
            var $productId = $this.data('id');
            var $urlRequest = $("#request-url-soft-delete").val();
            var $deletedCount = parseInt($(".deleted-span-circle").html());
            var $ajaxRequest = $.ajax({
                        type: "get",
                        url: $urlRequest,
                        data: {
                                product_id:$productId
                            },
                        success: function(d){ 
                            var $response = $.parseJSON(d); 
                            if($response.isSuccess){
                                window.location = "/me";
                            }
                            else{
                                alert($response.message);
                            }
                        }
                    });
        }
    });

    $(document.body).on('click','.hard-delete',function () {
        var $confirm = confirm("Are you sure you want to permanently delete this item?");
        if($confirm){
            var $this = $(this);
            var $productId = $this.data('id');
            var $urlRequest = $("#request-url-hard-delete").val();

            var $ajaxRequest = $.ajax({
                        type: "get",
                        url: $urlRequest,
                        data: {
                                product_id:$productId,
                            },
                        success: function(d){ 
                            var $response = $.parseJSON(d); 
                            if($response.isSuccess){
                                window.location = "/me";
                            }
                            else{
                                alert($response.message);
                            }
                        }
                    });
        }
    });

    $(document.body).on('click','.btn-restore',function () {
        var $confirm = confirm("Are you sure you want to restore this item?");
        if($confirm){
            var $this = $(this);
            var $productId = $this.data('id');
            var $urlRequest = $("#request-url-resotre").val();

            var $ajaxRequest = $.ajax({
                        type: "get",
                        url: $urlRequest,
                        data: {
                                product_id:$productId,
                            },
                        success: function(d){ 
                            var $response = $.parseJSON(d); 
                            if($response.isSuccess){
                                window.location = "/me";
                            }
                            else{
                                alert($response.message);
                            }
                        }
                    });
        }
    });
    

    $(document.body).on('click','.btn-edit-product',function () {
        var $this = $(this);
        var $productId = $this.data('productid');
        var $categoryId = $this.data('categoryid');
        var $categoryName = $this.data('othercategoryname'); 

        $("#editTextProductId").val($productId);
        $("#editTextCategoryId").val($categoryId);
        $("#editTextCategoryNamehala ").val($categoryName);
        $("#formEdit").submit();
    });
    
    var isAjaxRequestForProduct = function($page, $textInput, $filterInput, $requestType, $container)
    {
        if($container == "deleted-product-container"){
            if($("#hidden-deleted-container-" + $filterInput + " > #page-"+$page).length > 0){
                $('#'+$container).empty().append($("#hidden-deleted-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        else if($container == "drafted-product-container"){
            if($("#hidden-drafted-container-" + $filterInput + " > #page-"+$page).length > 0){ 
                $('#'+$container).empty().append($("#hidden-drafted-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        else{
            if($("#hidden-active-container-" + $filterInput + " > #page-"+$page).length > 0){
                $('#'+$container).empty().append($("#hidden-active-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
    }

    /**
     * Ajax Request for getting product in active, draft and 
     * @param  integer $page
     * @param  string  $textInput
     * @param  string  $filterInput
     * @param  string  $requestType
     * @param  string $container
     */
    var requestProduct = function($page, $textInput, $filterInput, $requestType, $container, $searchByString)
    {
        var $urlRequest = $('#request-url').val();
        var $ajaxRequest = $.ajax({
            type: "get",
            url: $urlRequest,
            data: {
                    page:$page,
                    search_string:$textInput,
                    sort:$filterInput,
                    request:$requestType,

                } ,
            beforeSend: function(){ 
                $('#'+$container).hide();
            },
            success: function(d){ 
                var $response = $.parseJSON(d);
                var $appendString = "<div id='page-"+$page+"'>"+$response.html+"</div>";
                $('#'+$container).html($response.html);
                $('#'+$container).show();

                if( !$searchByString ){
                    if($container == "deleted-product-container"){
                        $("#hidden-deleted-container-" + $filterInput).append($appendString);
                    }
                    else if($container == "drafted-product-container"){
                        $("#hidden-drafted-container-" + $filterInput).append($appendString);
                    }
                    else{
                        $("#hidden-active-container-" + $filterInput).append($appendString);
                    }
                }
            }
        });
    }
   
   $( "#btn-edit-email" ).click(function() {
      $( ".current-email" ).slideToggle( "slow" );
      $( ".edit-email" ).slideToggle( "slow" );
    });
    
    $( "#cancel-edit-email" ).click(function() {
        $( "#btn-edit-email" ).trigger( "click" );
    });
    
    $( "#btn-edit-password" ).click(function() {
      $( ".current-password" ).slideToggle( "slow" );
      $( ".edit-password" ).slideToggle( "slow" );
    });
    
    $( "#cancel-edit-password" ).click(function() {
        $( "#btn-edit-password" ).trigger( "click" );
    });
    
    $( "#btn-edit-status" ).click(function() {
      $( ".current-status" ).slideToggle( "slow" );
      $( ".edit-status" ).slideToggle( "slow" );
    });
    
    $( "#cancel-deact-status" ).click(function() {
        $( "#btn-edit-status" ).trigger( "click" );
    });
}(jQuery));

