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

    $("#active-items, #deleted-items, #draft-items").on('click',".individual, .extremes",function () {
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
        var $mainContainer = $this.parent().parent().parent();
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
                $('#'+$container).hide();
            },
            success: function(requestResponse){ 
                var $response = $.parseJSON(requestResponse);
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
                $("#feedback-view-container").hide();
            },
            success: function(requestResponse){
                var $response = $.parseJSON(requestResponse); 
                var $appendString = "<div id='page-"+$page+"'>"+$response.html+"</div>";
                $("#feedback-view-container").html($response.html);
                $("#feedback-view-container").show();

                $("#hidden-feedback-container > #feedback-" + $requestType).append($appendString);
            }
        });
    }

    var requestSales = function($page, $requestType, $container, $dateFrom, $dateTo)
    {
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
                $("#" + $container + " > .sales-container").hide();
            },
            success: function(requestResponse){
                var $response = $.parseJSON(requestResponse); 
                var $appendString = "<div id='page-"+$page+"'>"+$response.html+"</div>";
                $("#" + $container + " > .sales-container").html($response.html);
                $("#" + $container + " > .sales-container").show();
                $("#" + $container + " > .p-stat-total").html("&#8369; "+$response.netAmount);
                if($dateFrom == "" && $dateTo == ""){
                    $("#hidden-sales-container > #sales-" + $requestType).append($appendString);
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
    
    $( "#btn-edit-store-name" ).click(function() {
        $( ".current-store-name" ).slideToggle( "slow" );
        $( ".edit-store-name" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-name" ).click(function() {
        $( "#btn-edit-store-name" ).trigger( "click" );
    });

    $( "#btn-edit-store-url" ).click(function() {
        $( ".current-store-url" ).slideToggle( "slow" );
        $( ".edit-store-url" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-url" ).click(function() {
        $( "#btn-edit-store-url" ).trigger( "click" );
    });

    $( "#btn-edit-store-theme" ).click(function() {
        $( ".current-store-theme" ).slideToggle( "slow" );
        $( ".edit-store-theme" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-theme" ).click(function() {
        $( "#btn-edit-store-theme" ).trigger( "click" );
    });

    $( ".current-color-drop" ).click(function() {
        $( ".color-dropdown" ).slideToggle( "slow" );
        var attr8 = $("i.cd").attr("class");
        if(attr8 == "cd icon-dropdown pull-right"){
            $('i.cd').removeClass("cd icon-dropdown pull-right").addClass("cd icon-dropup pull-right");

        }
        else if(attr8 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-1" ).click(function() {
      $( ".color-name-drop" ).text("Easyshop");
      $( ".current-color-drop" ).css("background", "#ff893a");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor1 = $("i.cd").attr("class");
        if(attrColor1 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });
    $( "#color-item-2" ).click(function() {
      $( ".color-name-drop" ).text("Chestnut Rose");
      $( ".current-color-drop" ).css("background", "#D24D57");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor2 = $("i.cd").attr("class");
        if(attrColor2 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-3" ).click(function() {
      $( ".color-name-drop" ).text("POMEGRANATE");
      $( ".current-color-drop" ).css("background", "#F22613");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor3 = $("i.cd").attr("class");
        if(attrColor3 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-4" ).click(function() {
      $( ".color-name-drop" ).text("RED");
      $( ".current-color-drop" ).css("background", "#FF0000");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor4 = $("i.cd").attr("class");
        if(attrColor4 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-5" ).click(function() {
      $( ".color-name-drop" ).text("THUNDERBIRD");
      $( ".current-color-drop" ).css("background", "#D91E18");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor5 = $("i.cd").attr("class");
        if(attrColor5 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-6" ).click(function() {
      $( ".color-name-drop" ).text("FLAMINGO");
      $( ".current-color-drop" ).css("background", "#EF4836");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor6 = $("i.cd").attr("class");
        if(attrColor6 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-7" ).click(function() {
      $( ".color-name-drop" ).text("TALL POPPY");
      $( ".current-color-drop" ).css("background", "#C0392B");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor7 = $("i.cd").attr("class");
        if(attrColor7 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-8" ).click(function() {
      $( ".color-name-drop" ).text("MONZA");
      $( ".current-color-drop" ).css("background", "#CF000F");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor8 = $("i.cd").attr("class");
        if(attrColor8 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-9" ).click(function() {
      $( ".color-name-drop" ).text("CINNABAR");
      $( ".current-color-drop" ).css("background", "#E74C3C");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor9 = $("i.cd").attr("class");
        if(attrColor9 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#btn-edit-store-cat" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "slow" );
      $( ".edit-store-cat" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-cat" ).click(function() {
        $( "#btn-edit-store-cat" ).trigger( "click" );
    });

    $( "#btn-edit-store-cat-new" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "slow" );
      $( ".edit-store-cat-new" ).slideToggle( "slow" );
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
        $( "#birthday-picker" ).datepicker({
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
    });
    
    $('#give-feedback').click(function (e) {
        $('#feedback-modal').modal();
        $('#feedback-modal').parents("#simplemodal-container").addClass("feedback-container");
        return false;
    });

    $('#ship-item').click(function (e) {
        $('#shipping-details').modal();
        $('#shipping-details').parents("#simplemodal-container").addClass("shipping-details-container");
        return false;
    });

}(jQuery));


