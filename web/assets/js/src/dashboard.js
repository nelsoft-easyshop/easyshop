(function ($) {
    
    $( ".dash-me" ).click(function() {
        $( ".active-me" ).trigger( "click" );
    });
    
    
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

    $("#active-items, #deleted-items, #draft-items").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $parentContainer = $this.parent().parent().parent().parent().parent();
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

    $("#feedbacks").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $requestType = $("#select-feedback-filter").val();

        if($("#hidden-feedback-container > #feedback-" + $requestType + " > #page-" + $page).length > 0){
             $("#feedback-view-container").html($("#hidden-feedback-container > #feedback-" + $requestType + " > #page-" + $page).html());
        }
        else{
            requestFeedback($page, $requestType);
        }
    });

    $("#feedbacks").on('change','#select-feedback-filter',function () {
        var $this = $(this);
        var $requestType = $this.val();
        var $page = 1;

        if($("#hidden-feedback-container > #feedback-" + $requestType + " > #page-" + $page).length > 0){
             $("#feedback-view-container").html($("#hidden-feedback-container > #feedback-" + $requestType + " > #page-" + $page).html());
        }
        else{
            requestFeedback($page, $requestType);
        }
    });


    $("#sales").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $dateFrom = $("#"+$container).find(".date-from").val();
        var $dateTo = $("#"+$container).find(".date-to").val();
        var $requestType = $mainContainer.find("#request-type-container").val();

        if($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).length > 0 && $dateFrom == "" && $dateTo == ""){
            $("#" + $container + " > .sales-container").html($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).html());
        }
        else{
            requestSales($page, $requestType, $container, $dateFrom, $dateTo);
        }
    });

    $("#sales").on('click','.filter-sales',function() {
        var $this = $(this);
        var $page = 1;
        var $container = $this.data('container');
        var $requestType = $this.data('request');
        var $dateFrom = $("#"+$container).find(".date-from").val();
        var $dateTo = $("#"+$container).find(".date-to").val();

        if($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).length > 0 && $dateFrom == "" && $dateTo == ""){
            $("#" + $container + " > .sales-container").html($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).html());
        }
        else{
            requestSales($page, $requestType, $container, $dateFrom, $dateTo);
        }
    });

    var isAjaxRequestForProduct = function($page, $textInput, $filterInput, $requestType, $container)
    {
        if($container == "deleted-product-container"){
            if($("#hidden-deleted-container-" + $filterInput + " > #page-"+$page).length > 0){
                $('#'+$container).html($("#hidden-deleted-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        else if($container == "drafted-product-container"){
            if($("#hidden-drafted-container-" + $filterInput + " > #page-"+$page).length > 0){ 
                $('#'+$container).html($("#hidden-drafted-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        else{
            if($("#hidden-active-container-" + $filterInput + " > #page-"+$page).length > 0){
                $('#'+$container).html($("#hidden-active-container-" + $filterInput + " > #page-"+$page).html());
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
                page : $page,
                search_string : $textInput,
                sort : $filterInput,
                request : $requestType,
            },
            beforeSend: function(){ 
                $('#'+$container).html($('#hidden-paginate-loader').html());
            },
            success: function(requestResponse){ 
                var $response = $.parseJSON(requestResponse);
                var $appendString = "<div id='page-"+$page+"'>"+$response.html+"</div>";
                $('#'+$container).html($response.html);

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

    var requestFeedback = function($page, $requestType)
    {
        var $urlRequest = $('#feedback-request-url').val();
        var $ajaxRequest = $.ajax({
            type: "get",
            url: $urlRequest,
            data: {
                page : $page, 
                request : $requestType, 
            },
            beforeSend: function(){ 
                $("#feedback-view-container").html($('#hidden-paginate-loader').html());
            },
            success: function(requestResponse){
                var $response = $.parseJSON(requestResponse); 
                var $appendString = "<div id='page-"+$page+"'>"+$response.html+"</div>";
                $("#feedback-view-container").html($response.html);
                $("#hidden-feedback-container > #feedback-" + $requestType).append($appendString);
            }
        });
    }

    var requestSales = function($page, $requestType, $container, $dateFrom, $dateTo)
    {
        console.log($container);
        var $urlRequest = $('#sales-request-url').val();
        var $ajaxRequest = $.ajax({
            type: "get",
            url: $urlRequest,
            data: {
                page : $page, 
                request : $requestType,
                date_from : $dateFrom,
                date_to : $dateTo,
            },
            beforeSend: function(){ 
                $("#" + $container + " > .sales-container").html($('#hidden-paginate-loader').html());
            },
            success: function(requestResponse){
                var $response = $.parseJSON(requestResponse); 
                var $appendString = "<div id='page-"+$page+"'>"+$response.html+"</div>";
                $("#" + $container + " > .sales-container").html($response.html);
                $("#" + $container + " > .p-stat-total").html("&#8369; "+$response.netAmount);
                if($dateFrom == "" && $dateTo == ""){
                    $("#hidden-sales-container > #sales-" + $requestType).append($appendString);
                }
            }
        });
    }

   $( "#btn-edit-email" ).click(function() {
        $( ".current-email" ).slideToggle( "fast" );
        $( ".edit-email" ).slideToggle( "fast" );
    });

    $( "#cancel-edit-email" ).click(function() {
        $( "#btn-edit-email" ).trigger( "click" );
    });

    $( "#btn-edit-password" ).click(function() {
        $( ".current-password" ).slideToggle( "fast" );
        $( ".edit-password" ).slideToggle( "fast" );
    });

    $( "#cancel-edit-password" ).click(function() {
        $( "#btn-edit-password" ).trigger( "click" );
    });

    $( "#btn-edit-status" ).click(function() {
        $( ".current-status" ).slideToggle( "fast" );
        $( ".edit-status" ).slideToggle( "fast" );
    });

    $( "#cancel-deact-status" ).click(function() {
        $( "#btn-edit-status" ).trigger( "click" );
    });
    
    $( "#btn-edit-store-name" ).click(function() {
        $( ".current-store-name" ).slideToggle( "fast" );
        $( ".edit-store-name" ).slideToggle( "fast" );
    });

    $( "#cancel-edit-store-name" ).click(function() {
        $( "#btn-edit-store-name" ).trigger( "click" );
        $('#input-store-name').val($('#store-name-display').html())
        $("#fail-message-store-name").css('display', 'none');
        $("#fail-icon-store-name").css('display', 'none');
    });

    $( "#btn-edit-store-url" ).click(function() {
        $( ".current-store-url" ).slideToggle( "fast" );
        $( ".edit-store-url" ).slideToggle( "fast" );
    });

    $( "#cancel-edit-store-url" ).click(function() {
        $( "#btn-edit-store-url" ).trigger( "click" );
        $('#input-store-slug').val($('#store-slug-display').data('slug'));
        $("#fail-message-store-slug").css('display', 'none');
        $("#fail-icon-store-slug").css('display', 'none');
    });

    $( "#btn-edit-store-theme" ).click(function() {
        $( ".current-store-theme" ).slideToggle( "fast" );
        $( ".edit-store-theme" ).slideToggle( "fast" );
        $('#store-color-error').html('');
    });

    $( "#cancel-edit-store-theme" ).click(function() {        
        $( "#btn-edit-store-theme" ).trigger( "click" );
        var currentStoreColor = $('#current-store-color-id').val();
        $('#color-item-'+currentStoreColor).trigger('click');
    });

    $( ".current-color-drop" ).click(function() {
        $( ".color-dropdown" ).slideToggle( "fast" );
        var attr8 = $("i.cd").attr("class");
        if(attr8 == "cd icon-dropdown pull-right"){
            $('i.cd').removeClass("cd icon-dropdown pull-right").addClass("cd icon-dropup pull-right");

        }
        else if(attr8 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $('.color-theming').on('click', '.color-li',function(){
        var colorList = $(this);
        $( ".color-name-drop" ).text(colorList.data('name'));
        $( ".current-color-drop" ).css("backgroundColor", colorList.css('backgroundColor'));
        $( ".current-color-drop" ).trigger( "click" );
        var arrowIconClass = $("i.cd").attr("class");
        if(arrowIconClass == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
        var selectedColorList = $('.color-li.selected');
        selectedColorList.removeClass('selected');
        var checkIcon = selectedColorList.find('i');
        var newCheckIcon = checkIcon.clone();
        checkIcon.remove();
        colorList.addClass('selected');
        colorList.append(newCheckIcon);
    });
    

    
    $( "#btn-edit-store-cat" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "fast" );
      $( ".edit-store-cat" ).slideToggle( "fast" );
    });

    $( "#cancel-edit-store-cat" ).click(function() {
        $( "#btn-edit-store-cat" ).trigger( "click" );
    });

    $( "#btn-edit-store-cat-new" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "fast" );
      $( ".edit-store-cat-new" ).slideToggle( "fast" );
    });

    $( "#cancel-store-cat-new" ).click(function() {
        $( "#btn-edit-store-cat-new" ).trigger( "click" );
    });

    $(function() {
        $('.category_sort').sortable();
    });
    
    $('.feedback-from-seller').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-from-seller-container').slideToggle();
        $('.feedback-from-buyer-container').slideUp();
        $('.feedback-for-buyer-container').slideUp();
        $('.feedback-for-seller-container').slideUp();
        $('.feedback-from-buyer').removeClass("active-bar");
        $('.feedback-for-seller').removeClass("active-bar");
        $('.feedback-for-buyer').removeClass("active-bar");
    });
    
    $('.feedback-from-buyer').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-from-buyer-container').slideToggle();
        $('.feedback-from-seller-container').slideUp();
        $('.feedback-for-buyer-container').slideUp();
        $('.feedback-for-seller-container').slideUp();
        $('.feedback-from-seller').removeClass("active-bar");
        $('.feedback-for-seller').removeClass("active-bar");
        $('.feedback-for-buyer').removeClass("active-bar");
    });
    
    $('.feedback-for-seller').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-for-seller-container').slideToggle();
        $('.feedback-from-seller-container').slideUp();
        $('.feedback-from-buyer-container').slideUp();
        $('.feedback-for-buyer-container').slideUp();
        $('.feedback-from-seller').removeClass("active-bar");
        $('.feedback-from-buyer').removeClass("active-bar");
        $('.feedback-for-buyer').removeClass("active-bar");
    });
    
    $('.feedback-for-buyer').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-for-buyer-container').slideToggle();
        $('.feedback-from-seller-container').slideUp();
        $('.feedback-from-buyer-container').slideUp();
        $('.feedback-for-seller-container').slideUp();
        $('.feedback-from-seller').removeClass("active-bar");
        $('.feedback-from-buyer').removeClass("active-bar");
        $('.feedback-for-seller').removeClass("active-bar");
    });
    //FOR MOBILE
    $('.my-store-menu-mobile').click(function() {
        $('.my-account-menu-mobile-cont').slideUp();
        $('.my-store-menu-mobile-cont').slideToggle("fast", function(){
            var attr_ms = $("i.ms").attr("class");
            if(attr_ms == "ms fa fa-angle-down"){
                $('i.ms').removeClass("ms fa fa-angle-down").addClass("ms fa fa-angle-up");
            }
            else if(attr_ms == "ms fa fa-angle-up"){
                $('i.ms').removeClass("ms fa fa-angle-up").addClass("ms fa fa-angle-down");
            }
            
            var attr_ma2 = $("i.ma").attr("class");
            if(attr_ma2 == "ma fa fa-angle-up"){
                $('i.ma').removeClass("ma fa fa-angle-up").addClass("ma fa fa-angle-down");
            }
        });
    });
    
    $('.my-account-menu-mobile').click(function() {
        $('.my-store-menu-mobile-cont').slideUp();
        $('.my-account-menu-mobile-cont').slideToggle("fast", function(){
            var attr_ma = $("i.ma").attr("class");
            if(attr_ma == "ma fa fa-angle-down"){
                $('i.ma').removeClass("ma fa fa-angle-down").addClass("ma fa fa-angle-up");
            }
            else if(attr_ma == "ma fa fa-angle-up"){
                $('i.ma').removeClass("ma fa fa-angle-up").addClass("ma fa fa-angle-down");
            }
            
            var attr_ms2 = $("i.ms").attr("class");
            if(attr_ms2 == "ms fa fa-angle-up"){
                $('i.ms').removeClass("ms fa fa-angle-up").addClass("ms fa fa-angle-down");
            }
        });
    });
    
    $('.dash-mobile-trigger').click(function() {
        $('.my-store-menu-mobile-cont').slideUp("fast");
        $('.my-account-menu-mobile-cont').slideUp("fast");
        
        var attr_ms3 = $("i.ms").attr("class");
        if(attr_ms3 == "ms fa fa-angle-up"){
            $('i.ms').removeClass("ms fa fa-angle-up").addClass("ms fa fa-angle-down");
        }
        
        
        var attr_ma3 = $("i.ma").attr("class");
        if(attr_ma3 == "ma fa fa-angle-up"){
            $('i.ma').removeClass("ma fa fa-angle-up").addClass("ma fa fa-angle-down");
        }
    });
    
   
    $('.dash-mobile-trigger').click(function() {
        $('.my-store-menu-mobile-cont').slideUp("fast");
        $('.my-account-menu-mobile-cont').slideUp("fast");
    });
    
    $('#sc-selection-trigger').click(function() {
        $('.hide-selection-cont').slideToggle("fast");
        
    });
    
    $('#hide-date').click(function() {
        $('#th-date').toggle();
        $('.td-date').toggle();
    });
    
    $('#hide-trans').click(function() {
        $('#th-trans').toggle();
        $('.td-trans').toggle();
    });
    
    $('#hide-base-price').click(function() {
        $('#th-base-price').toggle();
        $('.td-base-price').toggle();
    });
    
    $('#hide-quantity').click(function() {
        $('#th-quantity').toggle();
        $('.td-quantity').toggle();
    });
    
    $('#hide-handling').click(function() {
        $('#th-handling').toggle();
        $('.td-handling').toggle();
    });
    
    $('#hide-total').click(function() {
        $('#th-total').toggle();
        $('.td-total').toggle();
    });
    
    $('#hide-es-charge').click(function() {
        $('#th-es-charge').toggle();
        $('.td-es-charge').toggle();
    });
    
    $('#hide-payment').click(function() {
        $('#th-payment').toggle();
        $('.td-payment').toggle();
    });
    
    $('#hide-net').click(function() {
        $('#th-net').toggle();
        $('.td-net').toggle();
    });
    
    $('#sc-p-selection-trigger').click(function() {
        $('.hide-p-selection-cont').slideToggle("fast");
        
    });
    
    $('#hide-p-date').click(function() {
        $('#th-p-date').toggle();
        $('.td-p-date').toggle();
    });
    
    $('#hide-p-trans').click(function() {
        $('#th-p-trans').toggle();
        $('.td-p-trans').toggle();
    });
    
    $('#hide-p-base-price').click(function() {
        $('#th-p-base-price').toggle();
        $('.td-p-base-price').toggle();
    });
    
    $('#hide-p-quantity').click(function() {
        $('#th-p-quantity').toggle();
        $('.td-p-quantity').toggle();
    });
    
    $('#hide-p-handling').click(function() {
        $('#th-p-handling').toggle();
        $('.td-p-handling').toggle();
    });
    
    $('#hide-p-total').click(function() {
        $('#th-p-total').toggle();
        $('.td-p-total').toggle();
    });
    
    $('#hide-p-es-charge').click(function() {
        $('#th-p-es-charge').toggle();
        $('.td-p-es-charge').toggle();
    });
    
    $('#hide-p-payment').click(function() {
        $('#th-p-payment').toggle();
        $('.td-p-payment').toggle();
    });
    
    $('#hide-p-net').click(function() {
        $('#th-p-net').toggle();
        $('.td-p-net').toggle();
    });

    $(function() {
        $( "#birthday-picker2" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( "#sales-end-date" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( "#sales-start-date" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( ".date-picker-sales" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( "#payout-end-date" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( "#payout-start-date" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( ".modal_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '2013:2050',
            dateFormat:"yy-M-dd"
        }).on('keypress',function(){
            return false;
        });
    });

    $('.shipment-detail-button').on('click', function(e) {
        var shipmentModal = $(this).parent().find('div.shipping-details');
        var shipmentContainer = $(this).parent().find('div.shipping-details-container');
        var thisbtn = $(this);
        var txStatus = $(this).parent().parent().parent().parent().find('span.status-class');
        var form = shipmentModal.find('.shipping_details_form');
        var submitbtn = $(form).find('.shipping_comment_submit');
        var input = $(form).find('input[type="text"]');
        var editbtn = $(form).find('.tx_modal_edit');
        var cancelbtn = $(form).find('.tx_modal_cancel');
        var courier = form.find('input[name="courier"]');
        var tracking_num = form.find('input[name="tracking_num"]');
        var delivery_date = form.find('input[name="delivery_date"]');
        var expected_date = form.find('input[name="expected_date"]');
        var textarea = $(form).find('textarea');

        courier.val(shipmentContainer.find('input[name="courier"]').val());
        tracking_num.val(shipmentContainer.find('input[name="tracking_num"]').val());
        delivery_date.val(shipmentContainer.find('input[name="delivery_date"]').val());
        expected_date.val(shipmentContainer.find('input[name="expected_date"]').val());
        textarea.val(shipmentContainer.find('input[name="comment"]').val());

        shipmentModal.modal({
            escClose: false,
            onShow: function() {
                if ( thisbtn.hasClass('isform') ) {
                    form.validate({
                        rules:{
                            courier:{
                                required: true
                            },
                            delivery_date:{
                                required: true
                            }
                        },
                        errorElement: "span",
                        errorPlacement: function(error, element) {
                            error.addClass('red');
                            error.insertAfter(element);
                        },
                        submitHandler: function(form){
                            input.attr('disabled',false);
                            textarea.attr('disabled', false);

                            $.post('/memberpage/addShippingComment', $(form).serializeArray(), function(data){
                                submitbtn.attr('disabled', false);
                                submitbtn.val('Save');

                                try{
                                    var obj = jQuery.parseJSON(data);
                                }
                                catch(e){
                                    alert('An error was encountered while processing your data. Please try again later.');
                                    return false;
                                }

                                if (obj.result === 'success') {
                                    shipmentContainer.find('input[name="courier"]').val(courier.val());
                                    shipmentContainer.find('input[name="tracking_num"]').val(tracking_num.val());
                                    shipmentContainer.find('input[name="delivery_date"]').val(delivery_date.val());
                                    shipmentContainer.find('input[name="expected_date"]').val(expected_date.val());
                                    shipmentContainer.find('input[name="comment"]').val(textarea.val());
                                    shipmentContainer.find('input[name="is_new"]').val(1);
                                    textarea.attr('data-value', htmlDecode(textarea.val()));
                                    textarea.attr('disabled', true);

                                    if(thisbtn.hasClass('isform')) {
                                        txStatus.replaceWith('<span class="trans-status-pending status-class">Item shipped</span>');
                                    }
                                    $.modal.close();
                                }else{
                                    alert(obj.error);
                                }
                            });
                            submitbtn.attr('disabled', true);
                            submitbtn.val('Saving...');
                        }
                    });
                }
                this.setPosition();
                input.each(function(){
                    if( $.trim($(this).attr('value')).length>0 ){
                        cancelbtn.trigger('click');
                        return false;
                    }
                });
            }
        });
        shipmentModal.parents("#simplemodal-container").addClass("shipping-details-container");
        return false;
    });

    $(".order-checkbox").on('change', function() {
        var $container = $(this).parent().parent().parent().parent().parent().parent();
        var $checkboxes = $container.find('.order-checkbox');
        var checkedValues = $checkboxes.filter(':checked').map(function() {
            return this.value;
        }).get().join('-');
        $container.find('.tx_cod').prop('disabled', true);
        $container.find('input[name=cash_on_delivery]').val('');
        if ($checkboxes.length == $checkboxes.filter(':checked').length) {
            $container.find('.tx_cod').prop('disabled', false);
            $container.find('input[name=cash_on_delivery]').val(checkedValues);
        }
    });

    $('.transac_response_btn.enabled').on('click', function() {
        var isConfirmed = confirm('You are about to update this transaction. Are you sure?');
        if(!isConfirmed){
            return false;
        }
        var txResponseBtn = $(this);
        var form = txResponseBtn.closest('form.transac_response');
        var txStatus = $(this).parent().parent().parent().parent().parent().find('span.status-class');
        var alltxStatus = $(this).parent().parent().parent().parent().parent().parent().find('span.status-class');
        var data = form.serializeArray();
        var buttonText = txResponseBtn.val();
        txResponseBtn.addClass('loading');
        txResponseBtn.removeClass('enabled');
        txResponseBtn.val('Please wait..');

        $.post("/memberpage/transactionResponse", data, function(data) {
            try {
                var serverResponse = jQuery.parseJSON(data);
            }
            catch (e) {
                alert('An error was encountered while processing your data. Please try again later.');
                txResponseBtn.val(buttonText);
                txResponseBtn.addClass('enabled').removeClass('loading');
                return false;
            }

            if (serverResponse.result !== 'success') {
                alert('Sorry we cannot process your request at this time. Please try again in a few minutes.');
                txResponseBtn.val(buttonText);
                txResponseBtn.addClass('enabled').removeClass('loading');
            }
            else{
                if(txResponseBtn.hasClass('tx_forward')){
                    txStatus.replaceWith('<span class="trans-status-cod status-class">Item Received</span>');
                }else if(txResponseBtn.hasClass('tx_return')){
                    txStatus.replaceWith('<span class="trans-status-pending status-class">Order Canceled</span>');
                }else if(txResponseBtn.hasClass('tx_cod')){
                    alltxStatus.replaceWith('<span class="trans-status-cod status-class">Completed</span>');
                }
                txResponseBtn.val('Successful');
                txResponseBtn.parent().parent().find('.txt_buttons').hide();
            }
            txResponseBtn.addClass('enabled');
        });


    });

    $('#on-going-transaction').on('click','.exportTransactions', function(){
        var url = $(this).data("url");
        document.location.href = url;
    });

    $('#on-going-transaction').on('click','.printTransactions', function() {
        var url = $(this).data("url");

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(json) {
                var originalContents = $(document.body).html();
                $(document.body).html(json);
                window.print();
                location.reload();
            },
            error: function(e) {
                alert("Action failed, please try again");
            }
        });
    });

    function htmlDecode(value) {
        if (value) {
            return $('<div />').html(value).text();
        } else {
            return '';
        }
    }

    $(document).on('mouseover','.feedb-star', function(){
        $(this).siblings('.raty-error').html('');
    });

    $('.item-list-panel').on('click', '.give-feedback-button', function(e) {
        var feedbackModal = $(this).parent().find('div.give-feedback-modal');
        var form = feedbackModal.find('form.transac-feedback-form');
        var textArea = form.find('textarea[name="feedback-field"]');
        var econt = form.find('.raty-error');
        var btn = $(this);

        feedbackModal.modal({
            onShow: function() {
                $('.rating1').raty('destroy').raty({scoreName: 'rating1'});
                $('.rating2').raty('destroy').raty({scoreName: 'rating2'});
                $('.rating3').raty('destroy').raty({scoreName: 'rating3'});

                this.setPosition();
                var submitbtn = form.find('.feedback-submit');
                submitbtn.off('click').on('click', function(event) {
                    var rating1 = form.find('input[name="rating1"]').val();
                    var rating2 = form.find('input[name="rating2"]').val();
                    var rating3 = form.find('input[name="rating3"]').val();
                    if ($.trim(textArea.val()).length < 1) {
                        textArea.effect('pulsate',{times:3},800);
                    }
                    else if (rating1 === '' || rating2 === '' || rating3 ==='') {
                        econt.html('Please rate this user!');
                    }
                    else {
                        $.post('/memberpage/addFeedback',form.serialize(),function(data) {
                            if (parseInt(data) === 1) {
                                alert('Your feedback has been submitted.');
                                btn.remove();
                            }
                            else {
                                alert('An error was encountered. Try again later.');
                            }
                        });
                        $.modal.close();
                        return false;
                    }
                });
            }
        });
        feedbackModal.parents("#simplemodal-container").addClass("feedback-container");

        return false;
    });

    $("#ongoing-bought").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'ongoing-bought';

        getTransactionDetails($page, $requestType, $container);
    });

    $("#ongoing-sold").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'ongoing-sold';

        getTransactionDetails($page, $requestType, $container);
    });

    $("#complete-bought").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'complete-bought';

        getTransactionDetails($page, $requestType, $container);
    });

    $("#complete-sold").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'complete-sold';

        getTransactionDetails($page, $requestType, $container);
    });

    $(".search-transaction-num").on('keypress', function(e) {
        var code = e.keyCode || e.which;
        var $value = $(this).val();
        var $container =  $(this).attr('data');
        var $searchFor = 'transactionNumber';
        if (code === 13) {
            searchForTransaction($container, $searchFor, $value, $container);
            return false;
        }
    });

    $('.payment-filter').on('change',function() {
        var $value = $(this).val();
        var $container =  $(this).attr('data');
        var $searchFor = 'paymentMethod';
        searchForTransaction($container, $searchFor, $value, $container);
    });

    function searchForTransaction($requestType, $searchFor, $value, $container)
    {
        var $ajaxRequest = $.ajax({
            type: 'get',
            url: 'memberpage/getTransactionsForPagination',
            data: {
                page : 0,
                value : $value,
                searchFor : $searchFor,
                request : $requestType
            },
            beforeSend: function() {
                $("#" + $container).empty();
            },
            success: function(requestResponse) {
                var $response = $.parseJSON(requestResponse);
                $("#" + $container).append($response.html);
            }
        });
    }

    $(".save-store-setting").click(function(){
        var $this = $(this);
        var storename = $('#input-store-name').val();
        var storeslug = $('#input-store-slug').val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var buttonHtml = $this.html();
        var field = $this.data('variable');
        $("#fail-message-"+field).hide();
        $("#fail-icon-"+field).hide();

        var postData = {csrfname: csrftoken};
        var expectedField = '';
        var action = '';
        if(field == 'store-slug'){
            postData['storeslug'] = storeslug;
            action = '/memberpage/updateStoreSlug';
        }
        else{
            postData['storename'] = storename;
            action = '/memberpage/updateStoreName';
        }
        $this.html('PLEASE WAIT');
        $.ajax({
            type: "post",
            url: action,
            data: postData,
            success: function(data){ 
                var response = $.parseJSON(data);
                if(response.isSuccessful == 'true'){
                    $('.edit-'+field).slideToggle( "fast" );
                    var currentSettingContainer = $('.current-'+field);
                    currentSettingContainer.slideToggle( "fast" );
                    var displayHtml = response.updatedValue;
                    if(field == 'store-slug'){
                        var escapedUrl = config.base_url + escapeHtml(response.updatedValue);
                        displayHtml =   '<a href="' + escapedUrl  +'" > ' + escapedUrl +'</a>';
                        $('#btn-edit-store-url').css('display', 'none');
                    } 
                    currentSettingContainer.find('span').html(displayHtml);
                }
                else{
                    var failMessageContainer = $("#fail-message-"+field);
                    failMessageContainer.html(response.errors);
                    failMessageContainer.show();
                    $("#fail-icon-"+field).show();
                }
            }
        });
        $this.html(buttonHtml);
    });
        
    var isStoreSetupInitialized = false;
    $('#store-setup-tab').on('click', function(){
        if(!isStoreSetupInitialized){
            $.ajax({
                type: "get",
                url: '/memberpage/getStoreSettings',
                success: function(data){ 
                    var jsonResponse = $.parseJSON(data);
                    var unorderedList = $("#store-color-dropdown");
                    var colorList = [];
                    var currentColorId = $('#current-store-color-id').val();
                    var isCurrentColorSet = false;

                    $.each(jsonResponse.colors, function(index, color) {
                        var icon = '';
                        var currentColorClass = '';
                        if(!isCurrentColorSet && color.idStoreColor == currentColorId){
                            icon = '<i class="fa fa-check pull-right"></i>';
                            currentColorClass = 'selected';
                            isCurrentColorSet = true;
                        }
                        var listHtml = '<li class="color-li '+currentColorClass+'" data-name="'+color.name+'" data-id="'+color.idStoreColor+'" ' +
                                           'style="background: #'+color.hexadecimal+'; text-transform: uppercase;" ' +
                                           'id="color-item-'+color.idStoreColor+'">' +color.name + icon +
                                        '</li>';
                        colorList.push(listHtml);
                    });
                    unorderedList.append( colorList.join('') );
                    unorderedList.find('#color-item-'+currentColorId).append(' </i>');
                
                    isStoreSetupInitialized = true;
                }
            });
        }
    });

        
    $('#store-color-save').on('click', function(){
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var selectedList = $('.color-li.selected');
        var colorId = selectedList.data('id');
        $.ajax({
            type: "post",
            url: '/memberpage/updateStoreColorScheme',
            data: {csrfname: csrftoken, colorId: colorId},
            success: function(data){
                var response = $.parseJSON(data);
                if(response.isSuccessful == 'true'){
                    $('#current-store-color-id').val(colorId);
                    var currentColorChoiceContainer = $('.current-color-choice');
                    currentColorChoiceContainer.css('background',selectedList.css('background'));
                    currentColorChoiceContainer.html(selectedList.data('name'));
                    $( "#btn-edit-store-theme" ).trigger( "click" );
                }
                else{
                    $('#store-color-error').html(response.errors);
                }
                
            }
        });
    });

    $(".add-bank-account").click(function() {
        $(this).fadeOut();
        $(".select-bank").slideDown();
    });

    $(".cancel-add-bank").click(function() {
        $(".select-bank").slideUp();
        $(".add-bank-account").fadeIn();
    });

    $(".trans-btn-con1").parents(".trans-right-panel").siblings(".trans-left-panel").addClass("trans-btn-con1-1");

    function getTransactionDetails($page, $requestType, $container)
    {
        console.log($container);
        var $ajaxRequest = $.ajax({
            type: 'get',
            url: 'memberpage/getTransactionsForPagination',
            data: {
                page : $page,
                request : $requestType
            },
            beforeSend: function() {
                $("#" + $container).empty();
            },
            success: function(requestResponse) {
                var $response = $.parseJSON(requestResponse);
                $("#" + $container).append($response.html);
            }
        });
    }

    var isPaymentAccountInitialized = false;
    $('#payment-account-tab').on('click', function(){
         if(!isPaymentAccountInitialized){
            $.ajax({
                type: "get",
                url: '/memberpage/getPaymentAccounts',
                success: function(data){ 
                    var jsonResponse = $.parseJSON(data);  
                    var bankOptionString = '';
                    
                                        
                    var template = $('#payment-account-template');
                    template.find('.edit-bank')
                    $.each(jsonResponse.bankList, function(index, bank) {
                        bankOptionString += '<option value="'+bank.idBank+'">' 
                                                + escapeHtml(bank.bankName) +
                                            '</option>';
                    });
                    template.find('.edit-bank select').append(bankOptionString);
                    $('.bank-dropdown').append(bankOptionString);
                    $.each(jsonResponse.paymentAccount, function(index, paymentAccount) {
                        var templateClone =  template.clone();
                        templateClone.css('display', 'block');
                        templateClone.find('.bank-name-container').html(escapeHtml(paymentAccount.bankName));
                        templateClone.find('.account-name-container').html(escapeHtml(paymentAccount.bankAccountName));
                        templateClone.find('.account-number-container').html(escapeHtml(paymentAccount.bankAccountNumber));
                        templateClone.find('.payment-account-id').val(paymentAccount.idBillingInfo);
                        templateClone.find('.bank-id').val(paymentAccount.idBank);
                        templateClone.attr('id', 'payment-account-' + paymentAccount.idBillingInfo);
                        if(paymentAccount.isDefault){
                            templateClone.find('.btn.btn-set-default').removeClass('btn-set-default').addClass('default-account');
                        }    
                        $('.payment-account-container').append(templateClone);
                            
                    });
                
                    isPaymentAccountInitialized = true;
                }
            });   
        }
    });
    
    $('.bank-dropdown').on('change', function(){
        $(this).removeClass('input-error');
    });
    
    $('.account-name-input').on('keyup', function(){
        $(this).removeClass('input-error');
    });
   
    $('.account-number-input').on('keyup', function(){
        $(this).removeClass('input-error');
    });
   
   
    $('#newPaymentForm').on('submit', function(e){
        e.preventDefault()
        $('#payment-create-error').hide();
        var $bankDropdown = $('.bank-dropdown');
        var $accountName = $('.account-name-input');
        var $accountNumber = $('.account-number-input');
        $bankDropdown.removeClass('input-error');
        $accountName.removeClass('input-error');
        $accountNumber.removeClass('input-error');
        var bankId = $bankDropdown.val();
        var accountNameValue = $accountName.val();
        var accountNumberValue = $accountNumber.val();
        var hasErrors = false;
        if(parseInt(bankId) <= 0){
            $bankDropdown.addClass('input-error');
            hasErrors = true;
        }
        if($.trim(accountNameValue).length <= 0){
            $accountName.addClass('input-error');
            hasErrors = true;
        }
        if($.trim(accountNumberValue).length <= 0){
            $accountNumber.addClass('input-error');
            hasErrors = true;
        }
        if(hasErrors){
            return false;
        }
        var $ajaxRequest = $.ajax({
            type: 'post',
            url: 'memberpage/createPaymentAccount',
            data: $( this ).serialize(),
            success: function(response) {
                var jsonResponse = $.parseJSON(response);  
                if(jsonResponse.isSuccessful){
                    var templateClone =  $('#payment-account-template').clone();
                    var bankName = $bankDropdown.find('option:selected').text();
                    templateClone.css('display', 'block');
                    templateClone.find('.bank-name-container').html(escapeHtml(bankName));
                    templateClone.find('.account-name-container').html(escapeHtml(accountNameValue));
                    templateClone.find('.account-number-container').html(escapeHtml(accountNumberValue));
                    templateClone.find('.bank-id').val(bankId);
                    templateClone.find('.payment-account-id').val(jsonResponse.newId);
                    templateClone.attr('id', 'payment-account-' + jsonResponse.newId);
                    if(jsonResponse.isDefault){
                        templateClone.find('.btn-set-default').removeClass('btn-set-default').addClass('default-account');
                    }
                    $('.payment-account-container').append(templateClone);
                    $('.cancel-add-bank').trigger('click');
                }
                else{
                    var paymentErrorContainer = $('#payment-create-error');
                    paymentErrorContainer.html(jsonResponse.errors);
                    paymentErrorContainer.show();
                }
            }
        });
        
        
    });
    
    $('.cancel-add-bank').on('click', function(){
        var $bankDropdown = $('.bank-dropdown');
        var $accountName = $('.account-name-input');
        var $accountNumber = $('.account-number-input');
        $bankDropdown.removeClass('input-error');
        $accountName.removeClass('input-error');
        $accountNumber.removeClass('input-error');
        $bankDropdown.val(0);
        $accountName.val('');
        $accountName.val('');
    });
    
    $('.payment-account-container').on('click', '.btn-set-default', function(){
        var button = $(this);
        var paymentAccountId = button.parent().siblings('.payment-account-id').val();
        var csrftoken = $("meta[name='csrf-token']").attr('content'); 
        var $ajaxRequest = $.ajax({
            type: 'post',
            url: 'memberpage/changeDefaultPaymentAccount',
            data: {'csrfname': csrftoken, 'payment-account-id': paymentAccountId}, 
            success: function(response) {
                var jsonResponse = $.parseJSON(response); 
                if(jsonResponse){
                    var oldDefaultTab = $('.payment-account-container .bank-account-item').first();
                    $('.default-account').removeClass('default-account').addClass('btn-set-default');
                    button.addClass('default-account').removeClass('btn-set-default');
                    var newDefaultTab = button.closest('.bank-account-item');
                    newDefaultTab.find('.cancel-edit-btn').trigger('click');
                    newDefaultTab.insertBefore(oldDefaultTab);
                    $('html, body').animate({
                        scrollTop: $("#panel-setting-title").offset().top
                    }, 1000);
                }
            }
        });
    });
    
    $('.payment-account-container').on('click', '.delete-account-btn', function(){
        var button = $(this);
        var paymentAccountId = button.parent().siblings('.payment-account-id').val();
        var csrftoken = $("meta[name='csrf-token']").attr('content'); 
        var $ajaxRequest = $.ajax({
            type: 'post',
            url: 'memberpage/deletePaymentAccount',
            data: {'csrfname': csrftoken, 'payment-account-id': paymentAccountId}, 
            success: function(response) {
                var jsonResponse = $.parseJSON(response); 
                if(jsonResponse.isSuccessful){
                    var newDefaultId = jsonResponse.defaultId;
                    var currentDefaultContainer = $('.payment-account-container .bank-account-item').first();
                    var currentDefaultId = currentDefaultContainer.find('.payment-account-id').val();
                    if(newDefaultId !== 0 && currentDefaultId !== newDefaultId){
                        var newDefaultContainer = $('#payment-account-' + newDefaultId);
                        newDefaultContainer.find('.btn-set-default').addClass('default-account').removeClass('btn-set-default');
                        newDefaultContainer.insertBefore(currentDefaultContainer);
                    }
                    button.closest('.bank-account-item').remove(); 
                }
            }
        });
    });
    
    
    $('.payment-account-container').on('click', '.edit-account-btn', function(){
        var button = $(this);
        var container = button.closest('.bank-account-item');
        var accountNameDisplay = container.find('.account-name-container');
        var accountNumberDisplay = container.find('.account-number-container');
        var bankNameDisplay = container.find('.bank-name-container');
        var accountNameInput =  container.find('.edit-account-name');
        var accountNumberInput =  container.find('.edit-account-number')
        var bankSelect = container.find('.edit-bank');
        var accountName = accountNameDisplay.html();
        var accountNumber = accountNumberDisplay.html();
        var bankId = container.find('.bank-id').val(); 
        bankNameDisplay.css('display','none');
        accountNameDisplay.css('display','none');
        accountNumberDisplay.css('display','none');
        bankSelect.css('display', 'inline');   
        accountNameInput.css('display', 'inline');
        accountNumberInput.css('display', 'inline');
        accountNameInput.find('input').val(accountName);
        accountNumberInput.find('input').val(accountNumber);
        bankSelect.find('select').val(bankId);
        container.find('.default-account, .btn-set-default').css('display', 'none');
        container.find('.edit-account-btn').css('display', 'none');
        container.find('.delete-account-btn').css('display', 'none');
        container.find('.save-edit-btn').css('display', 'inline-block');
        container.find('.cancel-edit-btn').css('display', 'inline-block');
       
    });
    
    
    $('.payment-account-container').on('click', '.save-edit-btn', function(){
        
        var button = $(this);
        var container = button.closest('.bank-account-item');
        var accountNameInput =  container.find('.edit-account-name input');
        var accountNumberInput =  container.find('.edit-account-number input')
        var accountName = accountNameInput.val();
        var accountNumber = accountNumberInput.val();
        var selectElement = container.find('.edit-bank select');
        var selectedBank = selectElement.find(':selected');
        var bankId = selectedBank.val();
        
        var hasErrors = false;
        if(parseInt(bankId) <= 0){
            selectElement.addClass('input-error');
            hasErrors = true;
        }
        if($.trim(accountName).length <= 0){
            accountNameInput.addClass('input-error');
            hasErrors = true;
        }
        if($.trim(accountNumber).length <= 0){
            accountNumberInput.addClass('input-error');
            hasErrors = true;
        }
        if(hasErrors){
            return false;
        }
        
        
        var cancelButton =  container.find('.cancel-edit-btn');
        var paymentAccountId = container.find('.payment-account-id').val();
        var csrftoken = $("meta[name='csrf-token']").attr('content'); 
        var $ajaxRequest = $.ajax({
            type: 'post',
            url: 'memberpage/updatePaymentAccount',
            data: {'csrfname': csrftoken, 'payment-account-id': paymentAccountId, 'account-name' : accountName,
                   'account-number' : accountNumber, 'bank-id' : bankId
            }, 
            success: function(response) {
                var jsonResponse = $.parseJSON(response); 
                if(jsonResponse.isSuccessful){  
                    var accountNameDisplay = container.find('.account-name-container');
                    var accountNumberDisplay = container.find('.account-number-container');
                    var bankNameDisplay = container.find('.bank-name-container');
                    container.find('.bank-id').val(bankId); 
                    accountNameDisplay.html(accountName);
                    accountNumberDisplay.html(accountNumber);
                    bankNameDisplay.html(selectedBank.html());
                    cancelButton.trigger('click');
                }
                else{
                    var errorContainer = container.find('.update-payment-account-error');
                    errorContainer.html(jsonResponse.errors);
                    errorContainer.css('display', 'block');
                    setTimeout(function(){
                        errorContainer.fadeOut('slow');
                    },2500);
                }
            }
        });
    });
        

    $('.payment-account-container').on('click', '.cancel-edit-btn', function(){
        var button = $(this);
        var container = button.closest('.bank-account-item');
        container.find('.bank-name-container').css('display','inline');
        container.find('.account-name-container').css('display','inline');
        container.find('.account-number-container').css('display','inline');
        var accountNameContainer =  container.find('.edit-account-name');
        var accountNumberContainer =  container.find('.edit-account-number');
        var bankContainer =  container.find('.edit-bank');
        bankContainer.css('display', 'none');
        accountNameContainer.css('display', 'none');
        accountNumberContainer.css('display', 'none');
        container.find('.default-account,.btn-set-default').css('display', 'inline-block');
        container.find('.edit-account-btn').css('display', 'inline-block');
        container.find('.delete-account-btn').css('display', 'inline-block');
        container.find('.save-edit-btn').css('display', 'none');
        container.find('.cancel-edit-btn').css('display', 'none');
        accountNameContainer.find('input').removeClass('input-error');
        accountNumberContainer.find('input').removeClass('input-error');
        bankContainer.find('input').removeClass('input-error');
    });
  
    $('.payment-account-container').on('keyup', '.edit-account-name input, .edit-account-number input', function(){
        $(this).removeClass('input-error');
    });

    $('.payment-account-container').on('change', '.edit-bank select', function(){
        $(this).removeClass('input-error');
    });

    
    
}(jQuery));


