(function ($) {
    
    $( "#activateProducts, #deleteProducts, #disableProducts" ).click(function() {
        var btn = $(this);
        var submitBtn = btn.closest("form");
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');          
        var username = submitBtn.find("#usernameField");
        var password = submitBtn.find("#passwordField");
        var action = btn.data("action");
        var errorPrompt = submitBtn.find("#errorPrompt");
        var successPrompt = submitBtn.find("#successPrompt");
        var loader = submitBtn.find("#actionLoader");
        var activateButtons = btn.parent();

        if(username !== "" || password !== "") {
            $.ajax({
                type: "post",
                data: {username:username.val().trim(), 
                       password:password.val().trim(), 
                       action:action, 
                       csrfname : csrftoken},
                url: '/memberpage/manageUserProduct',
                beforeSend: function() {
                    activateButtons.hide()
                    errorPrompt.hide();
                    successPrompt.hide();
                    loader.show();
                },
                success: function(data){ 
                    activateButtons.show();
                    loader.hide();
                    var obj = jQuery.parseJSON(data);
                    if(obj.result) {
                        successPrompt.fadeIn().delay(1000).fadeOut("slow");
                    }
                    else {
                        errorPrompt.fadeIn().delay(1000).fadeOut("slow");
                        errorPrompt.find(".message").html(obj.message);
                    }
                    username.val("");
                    password.val("");

                    $deletedItemsHeader = $("#button-deleted-item");
                    if(!$deletedItemsHeader.hasClass('can-request')){
                        $deletedItemsHeader.addClass('can-request');
                    }
                    $activeItemsHeader = $("#button-active-item");
                    if(!$activeItemsHeader.hasClass('can-request')){
                        $activeItemsHeader.addClass('can-request');
                    }
                    /**
                     * Update counters in products listing 
                     */
                    var deletedCounterCircle = $('#button-deleted-item .circle-total');
                    var activeCounterCircle = $('#button-active-item .circle-total');
                    var numberOfDeleted = parseInt( deletedCounterCircle.html(), 10);
                    var numberOfActive = parseInt( activeCounterCircle.html(), 10);
                    var numberOfUpdatedProducts = parseInt(obj.updatedProductCounter);
                    var $containerEmptiedSection = null;
                    var $containerPopulatedSection = null;
                    switch(action){
                        case 'restore':
                            numberOfActive += numberOfUpdatedProducts;
                            numberOfDeleted -= numberOfUpdatedProducts;
                            $containerEmptiedSection = $('#deleted-items');
                            $containerPopulatedSection = $('#active-items');
                            break;
                        case 'disable':
                            numberOfActive -= numberOfUpdatedProducts;
                            numberOfDeleted += numberOfUpdatedProducts;
                            $containerEmptiedSection = $('#active-items');
                            $containerPopulatedSection = $('#deleted-items');
                            break;
                        case 'delete':
                            numberOfDeleted -= numberOfUpdatedProducts;
                            $containerEmptiedSection = $('#deleted-items');
                            break;
                    }
                    deletedCounterCircle.html(numberOfDeleted);
                    activeCounterCircle.html(numberOfActive);
                    if($containerEmptiedSection !== null){
                        $containerEmptiedSection.find('.no-items').show();
                        $containerEmptiedSection.find('.with-items').hide();
                    }
                    if($containerPopulatedSection !== null){
                        $containerPopulatedSection.find('.no-items').hide();
                        $containerPopulatedSection.find('.with-items').show();
                    }
                },
                error: function(data) {
                    activateButtons.show();
                    loader.hide();
                }
            });
        }
        else {
            errorPrompt.fadeIn().delay(1000).fadeOut("slow");
            errorPrompt.find(".message").html("Please supply values to the required fields");
        }

    });

    $( ".dash-me" ).click(function() {
        $( ".active-me" ).trigger( "click" );
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".dashboard-home-mobile" ).addClass( "selectedM" );
    });
    
    $( ".id-transactions-trigger" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $( ".ms-trans" ).addClass( "selectedM" );
        
    });
    
    $( "#store-setup-tab" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $( ".ms-setup" ).addClass( "selectedM" );
    });

    $( "#customize-category-tab" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $( ".ms-customize" ).addClass( "selectedM" );
    });

    $( "#product-management-tab" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $( ".ms-prod" ).addClass( "selectedM" );
    });
    
    $( ".personal-info-trigger" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $( ".ma-info" ).addClass( "selectedM" );
    });
    
    var isDeliveryAddressLoaded = false;
    $( ".delivery-address-trigger" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $( ".ma-delivery" ).addClass( "selectedM" );
        $('#delivery-address-error').hide();
        $('#delivery-address-success').hide();
        if(!isDeliveryAddressLoaded) {
            $.ajax({
                type: "get",
                url: '/memberpage/getDeliveryAddress',
                success: function(data){ 
                    var serverResponse = $.parseJSON(data);
                    /**
                     * Turn cityLookupObject into an array and sort alphabetically 
                     */
                    var cityLookupObject = $.parseJSON(serverResponse.cities);
                    var cityLookupArray = [];
                    $.each(cityLookupObject, function(parentId, cityList) {
                        var cityArray = [];
                        $.each(cityList, function(cityId, city) {
                            cityArray.push({'id': cityId, 'name': city});
                        });
                        cityArray.sort(function(a,b) {
                            var cityNameA = a.name.toLowerCase();
                            var cityNameB = b.name.toLowerCase();
                            return cityNameA < cityNameB ? -1 : cityNameA > cityNameB ? 1 : 0;
                        });
                        cityLookupArray[parentId] = cityArray;
                    });  
                    jsonCity = cityLookupArray;
                    var mobile = serverResponse.address ? ( serverResponse.address.mobile !== '' ? '0'+serverResponse.address.mobile : '' ) : '';
                    var telephone = serverResponse.address ? ( serverResponse.address.telephone !== '' ? serverResponse.address.telephone : '' ) : '';
                    var consignee = serverResponse.address ? ( serverResponse.address.consignee !== '' ? serverResponse.address.consignee : '' ) : '';
                    var consigneeAddress = serverResponse.address ? ( serverResponse.address.address !== '' ? serverResponse.address.address : '' ) : '';
                    $("#consigneeName").val(consignee);
                    $("#consigneeMobile").val(mobile);
                    $("#consigneeLandLine").val(telephone);
                    $("#deliveryAddress").val(consigneeAddress);
                    var consigneeStateRegion = serverResponse.consigneeStateRegionId;
                    var stateRegionDropDown = $("#deliver_stateregion");
                    var dropDownTemplate = "";
                    
                    /**
                     * Turn stateRegion object into an array and sort alphabetically 
                     */
                    var stateRegionArray = [];
                    $.each(serverResponse.stateRegionLists, function(index, stateRegion) {
                        stateRegionArray.push({'id': index, 'name': stateRegion});
                    });  
                    stateRegionArray.sort(function(a,b) {
                        var stateNameA = a.name.toLowerCase();
                        var stateNameB = b.name.toLowerCase();
                        return stateNameA < stateNameB ? -1 : stateNameA > stateNameB ? 1 : 0;
                    });
                    $.each(stateRegionArray, function(index, stateRegion) {
                        dropDownTemplate += '<option class="echo" value="'+stateRegion.id+'">' + stateRegion.name + '</option>';
                    });  

                    stateRegionDropDown.append(dropDownTemplate);                         
                    stateRegionDropDown.val(consigneeStateRegion);
                    var cityDropDown = $("#delivery_city");
                    dropDownTemplate = "";
                    if(serverResponse.consigneeCityId !== '' && serverResponse.consigneeStateRegionId !== '') {
                        var deliveryStateRegionId = parseInt(serverResponse.consigneeStateRegionId, 10);
                        if(typeof cityLookupArray[deliveryStateRegionId] !== 'undefined'){
                            $.each(cityLookupArray[deliveryStateRegionId], function(index, city){
                                dropDownTemplate += '<option class="echo" value="'+city.id+'">' + city.name + '</option>';
                            });
                            cityDropDown.append(dropDownTemplate);  
                        }
                    }
                    
                    cityDropDown.val(serverResponse.consigneeCityId);
                    var lat  = (serverResponse.address !== null) ? serverResponse.address.lat : 0;
                    var lng = (serverResponse.address !== null) ? serverResponse.address.lng : 0;
                    if(serverResponse.address && (parseInt(lat) !== 0 || parseInt(lng) !== 0 )) {
                        $("#locationMarkedText").text("Location marked");
                    }
                    else {
                        $("#locationMarkedText").text("Location not marked");
                    }
                    $("#map_clat, #temp_clat").val(lat);
                    $("#map_clng, #temp_clng").val(lng);
                    $('.address_dropdown, .disabled_country').chosen({width:'200px'});                                          
                    $('.delivery-setup-loading').hide();
                    $('#deliverAddressDiv').fadeIn();
                }
            });            
        }
        isDeliveryAddressLoaded = true;
    });
    
    
    $( ".payment-address-trigger" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $( ".ma-payment" ).addClass( "selectedM" );
    });
    
    $( ".settings-trigger" ).click(function() {
        $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
        $( ".dashboard-home-mobile" ).removeClass( "selectedM" );
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $( ".ma-settings" ).addClass( "selectedM" );
    });
    
    $( ".dashboard-home-mobile" ).click(function() {
         $( ".dash-me" ).trigger( "click" );
         $( ".dash-mobile-trigger" ).removeClass( "selectedM" );
         $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".dashboard-home-mobile" ).addClass( "selectedM" );
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
        $('html, body').animate({
            scrollTop: $(".transaction-tabs").offset().top
        }, 300);
    });

    $('.transaction-title-sold').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold').slideToggle();
        $('.on-going-transaction-list-bought').slideUp();
        $('.transaction-title-bought').removeClass("active-bar");
        
        $('html, body').animate({
            scrollTop: $(".transaction-tabs").offset().top
        }, 300);
    });

    $('.transaction-title-bought-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought-completed').slideToggle();
        $('.on-going-transaction-list-sold-completed').slideUp();
        $('.transaction-title-sold-completed').removeClass("active-bar");
        
        $('html, body').animate({
            scrollTop: $(".transaction-tabs").offset().top
        }, 500);
    });

    $('.transaction-title-sold-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold-completed').slideToggle();
        $('.on-going-transaction-list-bought-completed').slideUp();
        $('.transaction-title-bought-completed').removeClass("active-bar");
        
         $('html, body').animate({
            scrollTop: $(".transaction-tabs").offset().top
        }, 500);
    });

    $("#sales").on("click", ".sales-title-total",function () {
        $(this).toggleClass("active-bar",0);
        $(this).next('.sales-breakdown-container').slideToggle();
        $('.payout-breakdown-container').slideUp();
        $('.payout-title-total').removeClass("active-bar");
    });

    $("#sales").on("click", ".payout-title-total",function () {
        $(this).toggleClass("active-bar",0);
        $(this).next('.payout-breakdown-container').slideToggle();
        $('.sales-breakdown-container').slideUp();
       $('.sales-title-total').removeClass("active-bar");
    });

    $('#transactions').on('click', '.trans-item-info', function() {
        $(this).children("i").toggleClass("fa-minus-circle");
        $(this).next(".info-attributes").slideToggle();
    });

    $('#transactions').on('click', '.view-delivery-lnk', function() {
        $(this).next(".view-delivery-details").slideToggle();
    });

    $( "#set-default" ).hover(function() {
        $( ".default-ad-explain" ).slideToggle( "slow" );
    });

    $( ".map-trigger" ).click(function() {
        $( ".map-container" ).slideToggle( "slow" );
    });

    $('.idTabs').idTabs({
        click: function(id, all, container, settings){
            var $button;
            var $page = 1;
            var $parentContainer;
            var $textInput;
            var $filterInput;
            var $requestType;
            var $container;
            
            if(id === "#deleted-items" || id === "#draft-items" || id === "#active-items"){ 
                if(id === "#deleted-items"){
                    $button = $("#button-deleted-item");
                }
                else if(id === "#draft-items"){
                    $button = $("#button-draft-item");
                }                
                else if(id === "#active-items"){
                    $button = $("#button-active-item");
                }
                if($button.hasClass('can-request')){
                    $parentContainer = $(id);
                    $textInput = $parentContainer.find('.search-field').val();
                    $filterInput = $parentContainer.find('.search-filter').val();
                    $requestType = $parentContainer.find('.request-type').val();
                    $container = $parentContainer.find('.container-id').val();
                    $button.removeClass('can-request');
                    isAjaxRequestForProduct($page, $textInput, $filterInput, $requestType, $container);
                }
            }
            else if(id == "#feedbacks"){
                $button = $("#button-feedback");
                if($button.hasClass('can-request')){
                    $requestType = $("#select-feedback-filter").val();
                    requestFeedback($page, $requestType);
                    $button.removeClass('can-request');
                }
            }
            else if(id == "#sales"){
                $button = $("#button-sales");
                if($button.hasClass('can-request')){
                    var $ajaxRequest = $.ajax({
                        type: "get",
                        url: $("#first-sales-request-url").val(),
                        beforeSend: function(){ 
                            $("#sales").html($('#hidden-paginate-loader').html());
                        },
                        success: function(d){ 
                            var $response = $.parseJSON(d); 
                            $("#sales").html($response.salesView);
                            $("#sales-1").html('<div id="page-1">'+$response.currentSales+'</div>');
                            $("#sales-4").html('<div id="page-1">'+$response.historySales+'</div>');
                            $( ".date-picker-sales" ).datepicker({
                                changeMonth: true,
                                changeYear: true
                            });
                        }
                    });
                    $button.removeClass('can-request');
                }
            }
        }
    });

    $("#active-items, #deleted-items, #draft-items").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $parentContainer = $this.closest('.dashboard-product-container');
        var $textInput = $parentContainer.find('.search-field').val();
        var $filterInput = $parentContainer.find('.search-filter').val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val();
        isAjaxRequestForProduct($page, $textInput, $filterInput, $requestType, $container);
    });

    $(document.body).on('change','.search-filter',function () {
        var $this = $(this);
        var $page = 1;
        var $parentContainer = $this.closest('.dashboard-product-container');
        var $textInput = $parentContainer.find('.search-field').val();
        var $filterInput = $this.val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val(); 

        isAjaxRequestForProduct($page, $textInput, $filterInput, $requestType, $container);
    });

    $(document.body).on('keydown','.search-field',function (event) {
        if(event.keyCode == 13){
            var $this = $(this);
            var $page = 1;
            var $parentContainer = $this.closest('.dashboard-product-container');
            var $textInput = $this.val();
            var $filterInput = $parentContainer.find('.search-filter').val();
            var $requestType = $parentContainer.find('.request-type').val();
            var $container = $parentContainer.find('.container-id').val(); 

            requestProduct($page, $textInput, $filterInput, $requestType, $container, true);
        }
    });

    $(document.body).on('change','.search-field',function (event) { 
        var $this = $(this);
        var $textInput = $this.val();
        var $page = 1;
        var $parentContainer = $this.closest('.dashboard-product-container');
        var $filterInput = $parentContainer.find('.search-filter').val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val(); 

        requestProduct($page, $textInput, $filterInput, $requestType, $container, true); 
    });

    $(document.body).on('click','.soft-delete',function () {
        var $confirm = confirm("Are you sure you want to move this item to deleted item?");
        if($confirm){
            var $this = $(this);
            var $productId = $this.data('id');
            var $urlRequest = $("#request-url-soft-delete").val();
            var $deletedCount = parseInt($(".deleted-span-circle").html());

            var $parentContainer = $this.closest('.dashboard-product-container');
            var $currentPage = $parentContainer.find('.pagination-section li.active').data('page');
            var $textInput = $parentContainer.find('.search-field').val();
            var $filterInput = $parentContainer.find('.search-filter').val();
            var $requestType = $parentContainer.find('.request-type').val();
            var $container = $parentContainer.find('.container-id').val();

            var $ajaxRequest = $.ajax({
                type: "get",
                url: $urlRequest,
                data: {
                    product_id: $productId,
                    page: $currentPage,
                    search_string: $textInput,
                    sort: $filterInput,
                    request: $requestType
                },
                success: function(requestResponse){ 
                    var $response = $.parseJSON(requestResponse); 
                    if($response.isSuccess){
                        
                        var deletedCounterCircle = $('#button-deleted-item .circle-total');
                        var activeCounterCircle = $('#button-active-item .circle-total');
                        var numberOfDeleted = parseInt( deletedCounterCircle.html(), 10) + 1;
                        var numberOfActive = parseInt( activeCounterCircle.html(), 10) - 1;
                        numberOfActive = numberOfActive < 0 ? 0 : numberOfActive;
                        deletedCounterCircle.html(numberOfDeleted);
                        activeCounterCircle.html(numberOfActive);

                        $('#'+$container).html($response.html);

                        $('#hidden-active-container > div').each(function(){
                            $(this).html('');
                        });   
                        var $appendString = "<div id='page-"+$currentPage+"'>"+$response.html+"</div>";
                        $("#hidden-active-container-" + $filterInput).append($appendString);
                        
                        $button = $("#button-deleted-item");
                        if(!$button.hasClass('can-request')){
                            $button.addClass('can-request');
                        }
                        $('#hidden-deleted-container > div').each(function(){
                            $(this).html('');
                        });   
                        
                        $('#deleted-items .no-items').hide();
                        if(numberOfActive === 0){
                            $parentContainer.find('.no-items').show();
                            $parentContainer.find('.with-items').hide();
                        }
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
            
            var $parentContainer = $this.closest('.dashboard-product-container');
            var $currentPage = $parentContainer.find('.pagination-section li.active').data('page');
            var $textInput = $parentContainer.find('.search-field').val();
            var $filterInput = $parentContainer.find('.search-filter').val();
            var $requestType = $parentContainer.find('.request-type').val();
            var $container = $parentContainer.find('.container-id').val();

            
            var $ajaxRequest = $.ajax({
                type: "get",
                url: $urlRequest,
                data: {
                        product_id: $productId,
                        page: $currentPage,
                        search_string: $textInput,
                        sort: $filterInput,
                        request: $requestType
                    },
                success: function(d){ 
                    var $response = $.parseJSON(d); 
                    if($response.isSuccess){

                        $('#'+$container).html($response.html);
                        var $appendString = "<div id='page-"+$currentPage+"'>"+$response.html+"</div>";
                        if($container == "deleted-product-container"){
                            $('#hidden-deleted-container > div').each(function(){
                                $(this).html('');
                            });   
                            $("#hidden-deleted-container-" + $filterInput).append($appendString);
                            var deletedCounterCircle = $('#button-deleted-item .circle-total');
                            var numberOfDeleted = parseInt( deletedCounterCircle.html(), 10) - 1;
                            numberOfDeleted = numberOfDeleted < 0 ? 0 : numberOfDeleted;
                            deletedCounterCircle.html(numberOfDeleted);
                            if(numberOfDeleted === 0){
                                $parentContainer.find('.no-items').show();
                                $parentContainer.find('.with-items').hide();
                            }
                        }
                        else if($container == "drafted-product-container"){
                            $('#hidden-drafted-container > div').each(function(){
                                $(this).html('');
                            });   
                            $("#hidden-drafted-container-" + $filterInput).append($appendString);
                            var draftCounterCircle = $('#button-draft-item .circle-total');
                            var numberOfDrafts = parseInt( draftCounterCircle.html(), 10) - 1;
                            numberOfDrafts = numberOfDrafts < 0 ? 0 : numberOfDrafts;
                            draftCounterCircle.html(numberOfDrafts);
                            if(numberOfDrafts === 0){
                                $parentContainer.find('.no-items').show();
                                $parentContainer.find('.with-items').hide();
                            }
                        }
                    }
                    else{
                        alert($response.message);
                    }
                }
            });
        }
    });

    $(document.body).on('click','.btn-edit-draft-product, .btn-advance-edit',function () {
        var $this = $(this);
        var $productId = $this.data('productid');
        var $categoryId = $this.data('categoryid');
        var $categoryName = $this.data('othercategoryname'); 

        $("#editTextProductId").val($productId);
        $("#editTextCategoryId").val($categoryId);
        $("#editTextCategoryName").val($categoryName);
        $("#formEdit").submit();
    });

    $(document.body).on('click','.btn-restore',function () {
        var $confirm = confirm("Are you sure you want to restore this item?");
        if($confirm){
            var $this = $(this);
            var $productId = $this.data('id');
            var $urlRequest = $("#request-url-resotre").val();
        
            var $parentContainer = $this.closest('.dashboard-product-container');
            var $currentPage = $parentContainer.find('.pagination-section li.active').data('page');
            var $textInput = $parentContainer.find('.search-field').val();
            var $filterInput = $parentContainer.find('.search-filter').val();
            var $requestType = $parentContainer.find('.request-type').val();
            var $container = $parentContainer.find('.container-id').val();
            
            var $ajaxRequest = $.ajax({
                type: "get",
                url: $urlRequest,
                data: {
                        product_id: $productId,
                        page: $currentPage,
                        search_string: $textInput,
                        sort: $filterInput,
                        request: $requestType
                    },
                success: function(d){ 
                    var $response = $.parseJSON(d); 
                    if($response.isSuccess){
      
                        var deletedCounterCircle = $('#button-deleted-item .circle-total');
                        var activeCounterCircle = $('#button-active-item .circle-total');
                        var numberOfDeleted = parseInt( deletedCounterCircle.html(), 10) - 1;
                        var numberOfActive = parseInt( activeCounterCircle.html(), 10) + 1;
                        numberOfDeleted = numberOfDeleted < 0 ? 0 : numberOfDeleted;
                        deletedCounterCircle.html(numberOfDeleted);
                        activeCounterCircle.html(numberOfActive);
                        
                        $('#'+$container).html($response.html);
                        $('#hidden-deleted-container > div').each(function(){
                            $(this).html('');
                        });   
                        var $appendString = "<div id='page-"+$currentPage+"'>"+$response.html+"</div>";
                        $("#hidden-deleted-container-" + $filterInput).append($appendString);
                                          
                        $button = $("#button-active-item");
                        if(!$button.hasClass('can-request')){
                            $button.addClass('can-request');
                        }
                        $('#hidden-active-container > div').each(function(){
                            $(this).html('');
                        });   
                        
                        $('active-items .no-items').hide();
                        if(numberOfDeleted === 0){
                            $parentContainer.find('.no-items').show();
                            $parentContainer.find('.with-items').hide();
                        }
                    }
                    else{
                        alert($response.message);
                    }
                }
            });
        }
    });

    $("#feedbacks").on('click',".individual, .extremes",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $requestType = $("#select-feedback-filter").val();

        mainDashboardAnchorToTop();
        
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
        var $netTotal = $("#"+$container).find("#net-total-container").val();

        mainDashboardAnchorToTop();
        if($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).length > 0 && $dateFrom == "" && $dateTo == ""){
            $("#" + $container + " > .sales-container").html($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).html());
            $("#" + $container + " > .p-stat-total").html("&#8369; "+$netTotal);
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
        var $dateFromContainer = $("#"+$container).find(".date-from"); 
        var $dateFrom = $dateFromContainer.val();
        var $dateTo = $("#"+$container).find(".date-to").val();
        var $netTotal = $("#"+$container).find("#net-total-container").val();

        if($dateFrom.trim() === "" && $dateTo.trim() !== ""){
            validateRedTextBox($dateFromContainer);
            return false;
        }
        validateWhiteTextBox($dateFromContainer);

        if($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).length > 0 && $dateFrom == "" && $dateTo == ""){
            $("#" + $container + " > .sales-container").html($("#hidden-sales-container > #sales-" + $requestType + " > #page-" + $page).html());
            $("#" + $container + " > .p-stat-total").html("&#8369; "+$netTotal);
        }
        else{
            requestSales($page, $requestType, $container, $dateFrom, $dateTo);
        }
    });

    var isAjaxRequestForProduct = function($page, $textInput, $filterInput, $requestType, $container)
    {
        if($container == "deleted-product-container"){
            if($("#hidden-deleted-container-" + $filterInput + " > #page-"+$page).length > 0 && $textInput.trim() == ""){
                $('#'+$container).html($("#hidden-deleted-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        else if($container == "drafted-product-container"){
            if($("#hidden-drafted-container-" + $filterInput + " > #page-"+$page).length > 0 && $textInput.trim() == ""){ 
                $('#'+$container).html($("#hidden-drafted-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        else{
            if($("#hidden-active-container-" + $filterInput + " > #page-"+$page).length > 0 && $textInput.trim() == ""){
                $('#'+$container).html($("#hidden-active-container-" + $filterInput + " > #page-"+$page).html());
            }
            else{
                requestProduct($page, $textInput, $filterInput, $requestType, $container);
            }
        }
        mainDashboardAnchorToTop();
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
                var $parentContainer =  $('#'+$container).closest('.dashboard-product-container');
                $parentContainer.find('.no-items').hide();
                $parentContainer.find('.with-items').show();

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
        $('#password-change-error').hide();
        $('#password-change-success').hide();
        $('#currentPassword').val('');
        $password = $('#password');
        $password.val('');
        $password.trigger('keyup');
        $('#confirmPassword').val('');
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
        $('#store-category-error').hide();
        $( "#btn-edit-store-cat" ).trigger( "click" );
        $('#edit-category-tree').jstree(true).refresh();
    });

    $( "#btn-edit-store-cat-new" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "fast" );
      $( ".edit-store-cat-new" ).slideToggle( "fast" );
    });

    $( "#cancel-store-cat-new" ).click(function() {
        $( "#btn-edit-store-cat-new" ).trigger( "click" );
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
        $('#my-account-menu-trigger').trigger("click");
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
    
   
    $('.dashboard-home-mobile').click(function() {
        $('.dash-me').trigger("click");
        $(".dash-me").addClass("selectedM");
        $(this).addClass("selectedM");
        $('.ms-trans').removeClass("selectedM");
        $('.my-store-menu-mobile-cont').slideUp("fast");
        $('.my-account-menu-mobile-cont').slideUp("fast");
    });
    
    $('.ms-trans').click(function() {
        $('.id-transactions-trigger').trigger("click");
        $('.dash-mobile-trigger ').removeClass("selectedM");
        $(".dash-me").removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $(this).addClass("selectedM");
    });
    
    
    $('.ms-setup').click(function() {
        $('#store-setup-tab').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $(this).addClass("selectedM");
    });

    $('.ms-customize').click(function() {
        $('#customize-category-tab').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $(this).addClass("selectedM");
    });

    $('.ms-prod').click(function() {
        $('#product-management-tab').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-store-menu-mobile" ).addClass( "selectedCol" );
        $(this).addClass("selectedM");
    });
    
    $('.ma-info').click(function() {
        $('.personal-info-trigger').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $(".ma-info").addClass("selectedM");
    });
    
    $('.ma-delivery').click(function() {
        $('.delivery-address-trigger').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $(".ma-delivery").addClass("selectedM");
    });
    
    $('.ma-payment').click(function() {
        $('.payment-account-trigger').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $(".ma-payment").addClass("selectedM");
    });
    
    $('.ma-settings').click(function() {
        $('.settings-trigger').trigger("click");
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dashboard-home-mobile').removeClass("selectedM");
        $( ".col-dash-mobile" ).removeClass( "selectedCol" );
        $( ".my-account-menu-mobile" ).addClass( "selectedCol" );
        $(".ma-settings").addClass("selectedM");
    });
    
    $(document).on("click", "#sc-selection-trigger",function () {
        $('.hide-selection-cont').slideToggle("fast");
    });
    
    $(document).on("click", "#hide-date",function () {
        $('#th-date').toggle();
        $('.td-date').toggle();
    });
    

    $(document).on("click", "#hide-trans",function () {
        $('#th-trans').toggle();
        $('.td-trans').toggle();
    });
    
    $(document).on("click", "#hide-base-price",function () {
        $('#th-base-price').toggle();
        $('.td-base-price').toggle();
    });
    
    $(document).on("click", "#hide-quantity",function () {
        $('#th-quantity').toggle();
        $('.td-quantity').toggle();
    });

    $(document).on("click", "#hide-handling",function () {
        $('#th-handling').toggle();
        $('.td-handling').toggle();
    });
    
    $(document).on("click", "#hide-total",function () {
        $('#th-total').toggle();
        $('.td-total').toggle();
    });
    
    $(document).on("click", "#hide-es-charge",function () {
        $('#th-es-charge').toggle();
        $('.td-es-charge').toggle();
    });

    $(document).on("click", "#hide-payment",function () {
        $('#th-payment').toggle();
        $('.td-payment').toggle();
    });
    
    $(document).on("click", "#hide-net",function () {
        $('#th-net').toggle();
        $('.td-net').toggle();
    });
    
    $(document).on("click", "#sc-p-selection-trigger",function () {
        $('.hide-p-selection-cont').slideToggle("fast");
        
    });
    
    $(document).on("click", "#hide-p-date",function () {
        $('#th-p-date').toggle();
        $('.td-p-date').toggle();
    });
    
    $(document).on("click", "#hide-p-trans",function () { 
        $('#th-p-trans').toggle();
        $('.td-p-trans').toggle();
    });
    
    $(document).on("click", "#hide-p-base-price",function () { 
        $('#th-p-base-price').toggle();
        $('.td-p-base-price').toggle();
    });
    
    $(document).on("click", "#hide-p-quantity",function () {
        $('#th-p-quantity').toggle();
        $('.td-p-quantity').toggle();
    });
    
    $(document).on("click", "#hide-p-handling",function () {
        $('#th-p-handling').toggle();
        $('.td-p-handling').toggle();
    });
    
    $(document).on("click", "#hide-p-total",function () {
        $('#th-p-total').toggle();
        $('.td-p-total').toggle();
    });
    
    $(document).on("click", "#hide-p-es-charge",function () {
        $('#th-p-es-charge').toggle();
        $('.td-p-es-charge').toggle();
    });
    
    $(document).on("click", "#hide-p-payment",function () {
        $('#th-p-payment').toggle();
        $('.td-p-payment').toggle();
    });
    
    $(document).on("click", "#hide-p-net",function () {
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

    });

    $('#transactions').on('click', '.shipment-detail-button', function(e) {
        var shipmentModal = $(this).parent().find('div.shipping-details');
        var shipmentContainer = $(this).parent().find('div.shipping-details-container');
        var thisbtn = $(this);
        var txStatus = $(this).closest('.item-list-panel').find('span.status-class');
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
        var hiddenInputs = thisbtn.closest('.item-list-panel').find('.order-product-ids');
        var orderProductIds = hiddenInputs.map(function() {
            return this.value;
        }).get().join('-');
        shipmentModal.find('input[name="order_product"]').val(orderProductIds);

        courier.val(shipmentContainer.find('input[name="courier"]').val());
        tracking_num.val(shipmentContainer.find('input[name="tracking_num"]').val());
        delivery_date.val(shipmentContainer.find('input[name="delivery_date"]').val());
        expected_date.val(shipmentContainer.find('input[name="expected_date"]').val());
        textarea.val(shipmentContainer.find('input[name="comment"]').val());

        shipmentModal.modal({
            escClose: false,
            onShow: function() {
                if ( thisbtn.hasClass('isform') ) {
                    $( ".dp-delivery-date" ).datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: '2013:2050',
                        dateFormat:"yy-M-dd",
                        onClose: function( selectedDate ) {
                            $( ".dp-expected-date" ).datepicker( "option", "minDate", selectedDate );
                        }
                    }).on('keypress',function(){
                        return false;
                    });
                    $( ".dp-expected-date" ).datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: '2013:2050',
                        dateFormat:"yy-M-dd",
                        onClose: function( selectedDate ) {
                            $( ".dp-delivery-date" ).datepicker( "option", "maxDate", selectedDate );
                        }
                    }).on('keypress',function(){
                        return false;
                    });
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
                            var serializedData = $(form).serializeArray();
                            serializedData.push({name :'csrfname', value: $("meta[name='csrf-token']").attr('content')});
                            input.attr('disabled',false);
                            textarea.attr('disabled', false);
                            $.ajax({
                                url : '/memberpage/addShippingComment',
                                method : 'POST',
                                data : serializedData,
                                success : function (data) {
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
                                            txStatus.replaceWith('<span class="trans-status-cod status-class">Item shipped</span>');
                                        }
                                        $.modal.close();
                                    }else{
                                        alert(obj.error);
                                    }
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

    $('#transactions').on('change', '.order-checkbox',function() {
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

    $('#transactions').on('click', '.transac_response_btn.enabled', function() {
        var isConfirmed = confirm('You are about to update this transaction. Are you sure?');
        if(!isConfirmed){
            return false;
        }
        var txResponseBtn = $(this);
        var form = txResponseBtn.closest('form.transac_response');
        var txStatus = $(this).parent().parent().parent().parent().parent().find('span.status-class');
        var alltxStatus = $(this).closest('.item-list-panel').find('span.status-class');
        var buttonText = txResponseBtn.val();
        var msg = "";
        var invoiceNum = txResponseBtn.parent().parent().find("input[name='invoice_num']");
        txResponseBtn.addClass('loading');
        txResponseBtn.removeClass('enabled');
        txResponseBtn.val('Please wait..');

        if( txResponseBtn.hasClass('tx_return') || txResponseBtn.hasClass('tx_forward')) {
            var inputName;
            var hiddenInputs = txResponseBtn.closest('.item-list-panel').find('.order-product-ids');
            var orderProductIds = hiddenInputs.map(function() {
                return this.value;
            }).get().join('-');
            if (txResponseBtn.hasClass('tx_return')) {
                inputName = 'input[name="seller_response"]';
            }
            else if (txResponseBtn.hasClass('tx_forward')) {
                inputName = 'input[name="buyer_response"]';
            }
            txResponseBtn.closest('.item-list-panel').find(inputName).val(orderProductIds);
        }
        var serializedData = form.serializeArray();
        serializedData.push({name :'csrfname', value: $("meta[name='csrf-token']").attr('content')});

        $.ajax({
            url : '/memberpage/transactionResponse',
            method : 'POST',
            data : serializedData,
            success : function (data) {
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
                else {
                    if (txResponseBtn.hasClass('tx_forward')) {
                        alltxStatus.replaceWith('<span class="trans-status-cod status-class">Item Received</span>');
                        msg = "<h3>ITEM RECEIVED</h3> <br> Transaction has been moved to completed tab.";
                    }
                    else if (txResponseBtn.hasClass('tx_return')) {
                        alltxStatus.replaceWith('<span class="trans-status-pending status-class">Order Canceled</span>');
                        txResponseBtn.closest('.trans-btn-wrapper').find('.shipment-detail-button').remove()
                        msg = "<h3>ORDER CANCELED</h3> <br> Transaction has been moved to completed tab.";
                    }
                    else if (txResponseBtn.hasClass('tx_cod')) {
                        alltxStatus.replaceWith('<span class="trans-status-cod status-class">Completed</span>');
                        msg = "<h3>COMPLETED</h3> <br> Transaction has been moved to completed tab.";
                    }
                    $('.invoiceno-'+invoiceNum.val()).replaceWith('<div class="alert alert-success wipeOut" role="alert">' + msg + '</div>');
                    $('.wipeOut').fadeOut(5000);
                }
                txResponseBtn.addClass('enabled');
            }
        });
    });

    $("#on-going-transaction, #completed-transaction").on('click','.exportTransactions', function(){
        var url = $(this).data("url");
        var invoiceNo = $.trim($(this).parent().find(".search-transaction-num").val());
        var isOngoing = $(this).data("isongoing");   
        var paymentMethod = $(this).parent().parent().find(".select-filter-item").val();                
        document.location.href = url+"?invoiceNo="+invoiceNo+"&isOngoing="+isOngoing+"&paymentMethod="+paymentMethod;        
    });

    $("#on-going-transaction, #completed-transaction").on('click','.printTransactions', function() {
        var url = $(this).data("url");
        var isOngoing = $(this).data("isongoing");
        var invoiceNo = $.trim($(this).parent().find(".search-transaction-num").val());
        var paymentMethod = $(this).parent().parent().find(".select-filter-item").val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');                 
        $.ajax({
            type: "post",
            url: url,
            data: {paymentMethod:paymentMethod, isOngoing:isOngoing, invoiceNo : invoiceNo , 'csrfname': csrftoken},
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

    $('#transactions').on('click', '#ongoing-bought .reject_item', function() {
        var thisbtn = $(this);
        var form = thisbtn.closest('form');
        var thismethod = thisbtn.siblings('input[name="method"]');
        var status = thisbtn.closest('.item-list-panel').find('.status-class');
        var hiddenInputs = thisbtn.closest('.item-list-panel').find('.order-product-ids');
        var orderProductIds = hiddenInputs.map(function() {
            return this.value;
        }).get().join('-');
        thisbtn.closest('.item-list-panel').find('input[name="order_product"]').val(orderProductIds);
        var serializedData = $(form).serializeArray();
        serializedData.push({name :'csrfname', value: $("meta[name='csrf-token']").attr('content')});

        $.ajax({
            url : '/memberpage/rejectItem',
            method : 'POST',
            data : serializedData,
            success : function (data) {
                try{
                    var obj = jQuery.parseJSON(data);
                }
                catch(e){
                    alert('An error was encountered while processing your data. Please try again later.');
                    return false;
                }
                thisbtn.attr('disabled', false);

                if(obj.result === 'success'){
                    if ( thisbtn.hasClass('reject') ) {
                        thisbtn.removeClass('reject').addClass('unreject').val('Unreject Item');
                        thismethod.val('unreject');
                        status.replaceWith('<span class="trans-status-pending status-class">ITEM REJECTED</span>');
                    }else if ( thisbtn.hasClass('unreject') ){
                        thisbtn.removeClass('unreject').addClass('reject').val('Reject Item');
                        thismethod.val('reject');
                        status.replaceWith('<span class="trans-status-pending status-class">ITEM UNREJECTED</span>');
                    }
                }
                else{
                    alert(obj.error);
                }
            }
        });
        thisbtn.attr('disabled', true);
        thisbtn.val('Sending...');
        return false;
    });

    var htmlDecode = function (value) {
        if (value) {
            return $('<div />').html(value).text();
        } else {
            return '';
        }
    }

    $('#transactions').on('mouseover','.feedb-star', function(){
        $(this).siblings('.raty-error').html('');
    });

    $('#transactions').on('click', '.item-list-panel .give-feedback-button', function(e) {
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
                        var serializedData = $(form).serializeArray();
                        serializedData.push({name :'csrfname', value: $("meta[name='csrf-token']").attr('content')});
                        $.ajax({
                            url : '/memberpage/addFeedback',
                            method : 'POST',
                            data : serializedData,
                            success : function (data) {
                                var jsonResponse = $.parseJSON(data);
                                if (jsonResponse.isSuccess) {
                                    alert('Your feedback has been submitted.');
                                    btn.remove();
                                }
                                else {
                                    var errorMessage = "An error was encountered. Please try again later";
                                    if(jsonResponse.error !== ""){
                                        errorMessage = jsonResponse.error;
                                    }
                                    alert(escapeHtml(errorMessage));
                                }
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

    $("#ongoing-bought").on('click', ".individual, .extremes", function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'ongoing-bought';
        var $searchFor = 'paymentMethod';
        var $paymentFilter = $mainContainer.closest('.list-container').find('.select-filter-item');

        getTransactionDetails($page, $requestType, $container, $searchFor, $paymentFilter.val());

        $('html, body').animate({
            scrollTop:$('#transactions').offset().top
        }, 1000);
    });

    $("#ongoing-sold").on('click', ".individual, .extremes", function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'ongoing-sold';
        var $searchFor = 'paymentMethod';
        var $paymentFilter = $mainContainer.closest('.list-container').find('.select-filter-item');

        getTransactionDetails($page, $requestType, $container, $searchFor, $paymentFilter.val());

        $('html, body').animate({
            scrollTop:$('#transactions').offset().top
        }, 1000);
    });

    $("#complete-bought").on('click', ".individual, .extremes", function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'complete-bought';
        var $searchFor = 'paymentMethod';
        var $paymentFilter = $mainContainer.closest('.list-container').find('.select-filter-item');

        getTransactionDetails($page, $requestType, $container, $searchFor, $paymentFilter.val());

        $('html, body').animate({
            scrollTop:$('#transactions').offset().top
        }, 1000);
    });

    $("#complete-sold").on('click', ".individual, .extremes", function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $mainContainer = $this.parent().parent().parent().parent().parent();
        var $container = $mainContainer.attr('id');
        var $requestType = 'complete-sold';
        var $searchFor = 'paymentMethod';
        var $paymentFilter = $mainContainer.closest('.list-container').find('.select-filter-item');

        getTransactionDetails($page, $requestType, $container, $searchFor, $paymentFilter.val());

        $('html, body').animate({
            scrollTop:$('#transactions').offset().top
        }, 1000);
    });

    $('#transactions').on('keypress', ".search-transaction-num", function(e) {
        var code = e.keyCode || e.which;
        var $value = $(this).val();
        var $container =  $(this).attr('data');
        var $searchFor = 'transactionNumber';
        var $page = 1;

        if (code === 13) {
            getTransactionDetails($page, $container, $container, $searchFor, $value);
        }
    });

    $('#transactions').on('change', '.payment-filter', function() {
        var $value = $(this).val();
        var $container =  $(this).attr('data');
        var $searchFor = 'paymentMethod';
        var $page = 1;

        getTransactionDetails($page, $container, $container, $searchFor, $value);
    });

    $('#transactions').on('click', '.transaction-button-head', function() {
        var $this = $(this);
        if($this.hasClass('active-bar')){
            var $container = $this.data('method');
            $('.payment-filter[data="'+$container+'"]').val('all');
            var $page = 1;
            getTransactionDetails($page, $container, $container, '', '');
        }
    });

    var getTransactionDetails = function ($page, $requestType, $container, $searchFor, $value)
    {
        $.ajax({
            type: 'get',
            url: 'memberpage/getTransactionsForPagination',
            data: {
                page : $page,
                request : $requestType,
                value : $value,
                searchFor : $searchFor
            },
            beforeSend: function() {
                $("#" + $container).html($('#hidden-paginate-loader').html());
            },
            success: function(requestResponse) {
                var $response = $.parseJSON(requestResponse);
                $("#" + $container).html($response.html);
                $(".trans-btn-con1").parents(".trans-right-panel").siblings(".trans-left-panel").addClass("trans-btn-con1-1");
                $(".reject_btn").parents(".trans-right-panel").siblings(".trans-left-panel").addClass("trans-btn-con1-1");
                if ($searchFor === "transactionNumber") {
                    $('#' + $container).find('.jumbotron').html('<i class="icon-category"></i>' +
                        'Ooops! You\'ve entered an invalid transaction number. Please try again.');
                }
                else if ($searchFor === "paymentMethod") {
                    $('#' + $container).find('.jumbotron').html('<i class="icon-category"></i>' +
                        'Sorry, you don\'t have any transactions with this payment gateway.');
                }
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
                if(response.isSuccessful){
                    $('.edit-'+field).slideToggle( "fast" );
                    var currentSettingContainer = $('.current-'+field);
                    currentSettingContainer.slideToggle( "fast" );
                    var displayHtml = escapeHtml(response.updatedValue);
                    if(field == 'store-slug'){
                        var escapedUrl = config.base_url + escapeHtml(response.updatedValue);
                        displayHtml =   '<a href="' + escapedUrl  +'" > ' + escapedUrl +'</a>';
                        $('#btn-edit-store-url').css('display', 'none');
                    } 
                    currentSettingContainer.find('span').html(displayHtml);
                }
                else{
                    var failMessageContainer = $("#fail-message-"+field);
                    failMessageContainer.html(escapeHtml(response.errors));
                    failMessageContainer.show();
                    $("#fail-icon-"+field).show();
                }
            }
        });
        $this.html(buttonHtml);
    });

    var isStoreSetupInitialized = false;
    $('#store-setup-tab').on('click', function(){
        $('.dash-mobile-trigger').removeClass("selectedM");
        $('.dash-me').removeClass("selectedM");
        $('.ms-setup').addClass("selectedM");
        $(".ms-trans").removeClass("selectedM");
        if(!isStoreSetupInitialized){
            $.ajax({
                type: "get",
                url: '/memberpage/getStoreColor',
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
                        var escapedColorName = escapeHtml(color.name);
                        var escapedColorHex = escapeHtml(color.hexadecimal);
                        var escapedColorId = escapeHtml(color.idStoreColor);
                        var listHtml = '<li class="color-li '+currentColorClass+'" data-name="'+escapedColorName+'" data-id="'+escapedColorId+'" ' +
                                           'style="background: #'+escapedColorHex+'; text-transform: uppercase;" ' +
                                           'id="color-item-'+escapedColorId+'">' + escapedColorName + icon +
                                        '</li>';
                        colorList.push(listHtml);
                    });
                    unorderedList.append( colorList.join('') );
                    unorderedList.find('#color-item-'+currentColorId).append(' </i>');
                    isStoreSetupInitialized = true;
                    $('.store-setup-loading').hide();
                    $('.store-setup-ajax').fadeIn();
                }
            });
        }
    });
    
    
    
    var isCategorySetupInitialized = false;
    $('#customize-category-tab').on('click', function(){
        if(!isCategorySetupInitialized){
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            $.ajax({
                type: "POST",
                url: '/memberpage/getStoreCategories',
                data: {csrfname: csrftoken},
                success: function(data){ 
                    var jsonResponse = $.parseJSON(data);
                    createCategoryList(jsonResponse.storeCategories);
                    isCategorySetupInitialized = true;
                    $('.category-setup-loading').hide();
                    $('.category-setup-ajax').fadeIn();
                }
            });
        }
    });
   
    
    $('#category-order-save').on('click', function(){
        var errorContainer = $('#store-category-error');
        errorContainer.hide();
        var csrftoken = $("meta[name='csrf-token']").attr('content');

        var categoryOrderData = [];
        var treedata = $('#edit-category-tree').jstree(true).get_json('#');
        var topLevelOrder = 0;
        var categoryOrderData = $.map(treedata, function(element, topLevelOrder) {
            var categoryId = element.li_attr['data-categoryid'];
            var children = element.children;
            var childOrder = 0;
            var childrenData = $.map(children, function(element, topLevelOrder) {
                var childCategoryId = element.li_attr['data-categoryid'];
                return {order: childOrder++, categoryid: childCategoryId, children: []}
            });
            return {order: topLevelOrder++, categoryid: categoryId, children: childrenData }
        });

        $.ajax({
            type: "post",
            url: '/memberpage/updateStoreCategoryOrder',
            data: {csrfname: csrftoken, categoryData: JSON.stringify(categoryOrderData)},
            success: function(data){ 
                var response = $.parseJSON(data);
                if(response.isSuccessful){
                    createCategoryList(response.categoryData);
                    $('#cancel-edit-store-cat').trigger('click');
                }
                else{
                    errorContainer.fadeIn();
                }
            },
        });
    });
    
    
    function createCategoryList(categoryData)
    {       
        if(categoryData.length === 0){
            $('.div-store-content.concealable').hide();
            $('.no-category-display').show();
            return false;
        }
        /**
         * Build reference tree 
         */
        var $referenceTreeList = $('#category-tree-reference ul');
        $referenceTreeList.html('');
        var referenceTreeList = [];
        $.each(categoryData, function(index, category) {
            var escapedName = escapeHtml(category.categoryName);
            var categoryIdentifier = parseInt(category.memberCategoryId, 10);
            var referenceTreeHtml = '<li data-categoryid="'+categoryIdentifier + '">'+escapedName;
            if(category.children.length > 0){
                referenceTreeHtml += '<ul>';
                $.each(category.children, function(index, child){
                    var childCategoryIdentifier = parseInt(child.memberCategoryId, 10);
                    var childEscapedName = escapeHtml(child.categoryName);
                    referenceTreeHtml += '<li data-categoryid="'+childCategoryIdentifier + '">'+childEscapedName+'<span class="icon-edit modal-category-edit pull-right edit-category"></span></li>';
                });
                referenceTreeHtml += '</ul>';
            }
            referenceTreeHtml += '<span class="icon-edit modal-category-edit pull-right edit-category"></span></li>';
            referenceTreeList.push(referenceTreeHtml);
        });

        $referenceTreeList.html();
        $referenceTreeList.append(referenceTreeList.join(''));
        populateCategoryTrees($referenceTreeList);
    }
    
    function populateCategoryTrees($referenceTreeList)
    {
        var $parentSelect = $('.parent-category-dropdown');
        var $categoryView = $('.store-category-view');
        var $deletableCategoryList = $('#delete-category-tree ul');
        var $draggableCategoryList = $('#edit-category-tree ul');
        var $listElements = $('#category-tree-reference>ul');
        var listElementsHtml = '<ul>' + $listElements.get(0).innerHTML + '</ul>';
        initializeEditTree(listElementsHtml);
        initializeDeleteTree(listElementsHtml);
        var categoryViewList = [];
        var parentCategoryDroddownList = [];
        var children = $listElements.children('li').clone();
        children.find('ul').remove();
        children.find('span').remove();
        parentCategoryDroddownList.push('<option value="0">None</option>');
        $.each(children, function(index,listItem){
            var categoryIdentifier = parseInt(listItem.getAttribute('data-categoryid'), 10);
            var escapedName = listItem.innerHTML;
            var viewHtml = '<div class="div-cat" data-categoryid="'+categoryIdentifier+'">'+escapedName+'</div>';
            var parentDropdownHtml = '<option value="'+categoryIdentifier+'">'+escapedName+'</option>';
            categoryViewList.push(viewHtml);
            parentCategoryDroddownList.push(parentDropdownHtml);
        });
        $categoryView.html('');
        $parentSelect.html('');
        $categoryView.append( categoryViewList.join('') );
        $parentSelect.append(parentCategoryDroddownList.join(''));
    }
    
    function initializeEditTree(data)
    {       
        $('#edit-category-tree').jstree('destroy');
        if(typeof data === 'undefined'){
            $('#edit-category-tree').jstree({
                "core": {
                    "check_callback":true
                },
                "types" : {
                    "#" : {
                        "max_depth" : 2
                    },
                },
                "plugins" : [
                    "dnd", "types"
                ],
            });
        }
        else{
         
            $('#edit-category-tree').jstree({
                "core": {
                    "check_callback":true,
                    "data": data
                },
                "types" : {
                    "#" : {
                        "max_depth" : 2
                    },
                },
                "plugins" : [
                    "dnd", "types"
                ],
            });
        }
    }
    
    function initializeDeleteTree(data)
    {
        $('#delete-category-tree').jstree('destroy');
        if(typeof data === 'undefined'){
            $('#delete-category-tree').on('check_node.jstree', function (e, data) {
                data.instance.open_all(data.node, 300);
            })
            .jstree({
                "checkbox" : {
                    "keep_selected_style" : false,
                    "cascade" : "down", 
                    "three_state" : false ,
                    "tie_selection" : false
                },
                "plugins" : [
                    "checkbox"
                ],
            });
        }
        else{
            $('#delete-category-tree').on('check_node.jstree', function (e, data) {
                data.instance.open_all(data.node, 300);
            })
            .jstree({
                "core": {
                    "data": data
                },
                "checkbox" : {
                    "keep_selected_style" : false,
                    "cascade" : "down", 
                    "three_state" : false ,
                     "tie_selection" : false
                },
                "plugins" : [
                    "checkbox"
                ],
            });
        }
    }

    
   
    
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
                    currentColorChoiceContainer.css('backgroundColor',selectedList.css('backgroundColor'));
                    currentColorChoiceContainer.html(selectedList.data('name'));
                    $( "#btn-edit-store-theme" ).trigger( "click" );
                }
                else{
                    $('#store-color-error').html(response.errors);
                }
                
            }
        });
    });

    $('#setup').on('click','.printQrCode', function() {
        var url = $(this).data("url");
        window.open(url, '_blank'); 
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
                        templateClone.addClass('appended-payment-account');
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
                    $('.payment-account-loading').hide();
                    $('.appended-payment-account').fadeIn();
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
                    paymentErrorContainer.html(escapeHtml(jsonResponse.errors));
                    paymentErrorContainer.show();
                }
            }
        });
        
        
    });

    $('.cancel-add-bank').on('click', function(){
        $('#payment-create-error').hide();
        var $bankDropdown = $('.bank-dropdown');
        var $accountName = $('.account-name-input');
        var $accountNumber = $('.account-number-input');
        $bankDropdown.removeClass('input-error');
        $accountName.removeClass('input-error');
        $accountNumber.removeClass('input-error');
        $bankDropdown.val(0);
        $accountName.val('');
        $accountNumber.val('');
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
        
        var isConfirmed = confirm('Are you sure you would like to delete this payment account?');
        if(isConfirmed === false){
            return false;
        }
        
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
                    accountNameDisplay.html(escapeHtml(accountName));
                    accountNumberDisplay.html(escapeHtml(accountNumber));
                    bankNameDisplay.html(escapeHtml(selectedBank.html()));
                    cancelButton.trigger('click');
                }
                else{
                    var errorContainer = container.find('.update-payment-account-error');
                    errorContainer.html(escapeHtml(jsonResponse.errors));
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

    $( document ).ready(function() {
        $("#mobileNumber").numeric()
        $("#consigneeMobile").numeric();
        var $tab = $('#page-tab').val();
        handleUrlTabs($tab);
    });

    function handleUrlTabs(tab)
    {
        if (tab === "ongoing") {
            $('#my-store-menu-trigger').trigger('click');
             setTimeout(function() {
                 $('.id-transactions-trigger').trigger('click');
            }, 500);
            setTimeout(function() {
                 $('.transaction-title-bought').trigger('click');
            }, 1000);
        }
        else if(tab === "settings"){
            $('#my-account-menu-trigger').trigger('click');
             setTimeout(function() {
                 $('.settings-trigger').trigger('click');
            }, 500);
        }
    }
    
    function mainDashboardAnchorToTop()
    {
        $('html,body').animate({
            scrollTop: $('.input-shop-link').offset().top
        }, 700);
    }

    $( "#activate-products" ).click(function() {
        $( ".current-activate-prod" ).slideToggle( "fast" );
        $( ".edit-activate-prod" ).slideToggle( "fast" );
    });
    
    $( "#cancel-activate-products" ).click(function() {
        $( "#activate-products" ).trigger( "click" );
    });

    $( "#deactivate-products" ).click(function() {
        $( ".current-deactivate-prod" ).slideToggle( "fast" );
        $( ".edit-deactivate-prod" ).slideToggle( "fast" );
    });
    
    $( "#cancel-deactivate-products" ).click(function() {
        $( "#deactivate-products" ).trigger( "click" );
    });

    $( "#delete-products" ).click(function() {
        $( ".current-delete-prod" ).slideToggle( "fast" );
        $( ".edit-delete-prod" ).slideToggle( "fast" );
    });
    
    $( "#cancel-delete-products" ).click(function() {
        $( "#delete-products" ).trigger( "click" );
    });


    $( "#btn-edit-delete-categories" ).click(function() {
        var categoryIds = [];
        var $deleteTree = $("#delete-category-tree");
        var selectedTreeNodes = $deleteTree.jstree("get_checked");
        var $treeReference = $('#category-tree-reference');
        $.each(selectedTreeNodes, function(index, nodeId){
            var categoryId = parseInt($deleteTree.find('#'+nodeId+'').data('categoryid'), 10);
            categoryIds.push(categoryId);
        });
        if($.isEmptyObject(categoryIds)){
            return false;
        }
        
        var isConfirmed = confirm('Are you sure you want to delete the selected categories?');

        if(isConfirmed){
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');   
            $.ajax({
                type: "POST",
                url: '/memberpage/deleteCustomCategory',
                data: {categoryIds:JSON.stringify(categoryIds), csrfname:csrftoken},
                success: function(data){ 
                    var response = $.parseJSON(data);
                    if(response.isSuccess){
                        $('.delete-dialog-success').fadeIn().delay(5000).fadeOut();   
                        var $categoryTreeReference = $('#category-tree-reference');
                        $.each(response.deletedCategoryIds, function(key, categoryId){
                            $categoryTreeReference.find('li[data-categoryid="'+categoryId+'"]').remove();
                        });
                        populateCategoryTrees();
                        
                        var numberOfCategories = $categoryTreeReference.find('li').length;
                        if(numberOfCategories === 0){
                            $('#no-category-display-edit').show();
                            $('#div-store-content-edit').hide();
                            $('#no-category-display-delete').show();
                            $('#div-store-content-delete').hide();
                        }
                    }
                    else{
                        var $deleteDialog = $('.delete-dialog-fail');
                        $deleteDialog.find('.message').html(escapeHtml(response.message));
                        $deleteDialog.fadeIn().delay(5000).fadeOut();
                    }
                }
            });
        }
    });
    
    var browserWidth;
    var modalCategoryModalWidth;
    var modalCategoryModalSmWidth;
    var modalCategoryModalWidthMobile;
    var widthOfDragabbleItem = 70;
    var mobileViewPortWidthLimit = 769;


    $(window).on("load resize",function(){
        browserWidth = $(window).width();
        browserHeight = $(window).outerHeight();
        modalCategoryModalWidth = browserWidth * 0.6;
        modalCategoryModalSmWidth = browserWidth * 0.5;
        modalCategoryModalWidthMobile = browserWidth * 0.95;
        $(".overlay-for-waiting-modal, .overlay-loader-container-main, .overlay-loader-container").css("height", browserHeight+"px").css("width", browserWidth+"px");
    });

  
    
    $("#add-category").click(function(){
        
        $(".overlay-for-waiting-modal").css("display", "block");
        
        var clonedDiv = $(".add-category-modal").clone(); 
        
        clonedDiv.find('.category-items .product-list').html('');
        
        retrieveAllProductList(clonedDiv, 1);
    });
     
    $(".category-setup-ajax").on('click','.edit-category', function(){
        $(".overlay-for-waiting-modal").css("display", "block");
        var $listItem = $(this).closest('li');
        var parentCategoryId = 0;
        var $parentListItem = $listItem.parentsUntil("#edit-category-tree", "li");
        if($parentListItem.length > 0){
            parentCategoryId = $parentListItem.data('categoryid');
        }
        var categoryIdString = $listItem.data('categoryid');
        var categoryId = parseInt(categoryIdString,10);
        $("#edit-category-tree").jstree("open_node", $('li[data-categoryid="'+categoryId+'"]'));
        var numberOfchildren = $('#edit-category-tree li[data-categoryid="'+categoryId+'"] ul>li').length;

        $.ajax({
            type: "GET",
            url: '/memberpage/getCustomCategory',
            data: {categoryId:categoryId},
            success: function(data){ 
                var response = $.parseJSON(data);
                if(response){
                    var clonedDiv = $(".edit-category-modal").clone();  
                    clonedDiv.find('.category-name').val(escapeHtml(response.categoryName));
                    clonedDiv.find('.hidden-category-id').val(response.categoryId);
                    clonedDiv.find('.category-items .product-list').html('');
                    var dropdown = clonedDiv.find('.parent-category-dropdown');
                    dropdown.find('option[value="'+categoryId+'"]').hide();
                    if(parentCategoryId > 0){
                        dropdown.val(parentCategoryId);
                    } 
                    if(numberOfchildren > 0){
                        dropdown.prop('disabled', true);
                    }
                    appendCategoryProductList(clonedDiv.find('.category-items') , response.products)
                    retrieveAllProductList(clonedDiv, 1);
                }            
            },
        });
    });

                
    $(document.body).on('click', '.icon-move-to-all-items, .icon-move-to-custom-category', function () {
        var listItem = $(this).parent();
        var unorderList = listItem.parent();

        listItem.fadeOut(function () {
            if (unorderList.hasClass('allItemsEdit')) {
                $('.customCategoryEdit').prepend(listItem.fadeIn());
            } 
            else if (unorderList.hasClass('customCategoryEdit')){
                $('.allItemsEdit').prepend(listItem.fadeIn());
            }
            else if (unorderList.hasClass('allItems')){
                $('.customCategory').prepend(listItem.fadeIn());
            }
            else if (unorderList.hasClass('customCategory')){
                $('.allItems').prepend(listItem.fadeIn());
            }
        });
        
        var $listContainer = listItem.closest('.category-items-holder');
        checkContainerScrollable($listContainer);
    });
    

    $(document.body).on('keypress', '.search-category', function(event){
        if ( event.which == 13 ) {
            event.preventDefault();
            var $this = $(this);
            var isSearchingField = $this.siblings('.isSearching');
            var isSearching = isSearchingField.val();

            if(isSearching === 'true'){
                return false;
            }
            
            var $searchDiv = $this.closest('.category-panel-header');
            var $modalDiv = $this.closest(".category-modal");  
            var $itemListDiv = $searchDiv.siblings('.category-items-holder');
            var page = 1;
            var categoryId = $modalDiv.find('.hidden-category-id').val();
            var searchString = $this.val();
            var $categoryProductList = $itemListDiv.find('.product-list');
            var $loader = $itemListDiv.find('.loader');
            $itemListDiv.attr('data-isComplete', 'false');
            var url;
            var data = "";

            if($itemListDiv.hasClass('category-items')){
                url = '/memberpage/getCustomCategory';
                data = 'categoryId='+categoryId+'&page='+page+'&searchString='+searchString; 
            }
            else if($itemListDiv.hasClass('all-items')){
                var $currentCategoryProductList = $modalDiv.find('.category-product-list .category-item-name');  
                var currentCategoryProductsIds = [];
                $.each($currentCategoryProductList, function(key, $itemNameDiv){
                    currentCategoryProductsIds.push($itemNameDiv.getAttribute('data-id'));
                });
                url = '/memberpage/getAllMemberProducts';
                data = 'excludeCategoryId='+categoryId+'&page='+page+'&searchString='+searchString+
                       '&excludeProductId='+JSON.stringify(currentCategoryProductsIds);
            }
            else{
                return false;
            }
            
            $itemListDiv.attr('data-page', page);
            $categoryProductList.html('');
            $loader.show();
            isSearchingField.val('true');
            $.ajax({
                type: "GET",
                url: url,
                data: data,
                success: function(data){
                    var jsonResponse = $.parseJSON(data);
                    appendCategoryProductList($itemListDiv, jsonResponse.products)
                    $loader.hide();
                    isSearchingField.val('false');
                }
            });  
        }
    });   
    
    $(document.body).on('click', '.save-category-changes', function(){
        var $btn = $(this);
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');   
        var $modalDiv = $btn.closest('.edit-category-modal');
        var categoryId = $modalDiv.find('.hidden-category-id').val();
        var categoryName = $modalDiv.find('.category-name').val();

        var $currentCategoryProductList = $modalDiv.find('.category-product-list .category-item-name');
        var newParentCategory = parseInt($modalDiv.find('.parent-category-dropdown option:selected').val(), 10);
        var allLoadedProductIds = $.parseJSON($modalDiv.find('.all-loaded-products-ids').val());
        var productIdsInEditableList = [];
        
        var sortOrder = 0;
        var sortArray = [];
        $.each($currentCategoryProductList, function(key, itemNameDiv){
            var productId = parseInt(itemNameDiv.getAttribute('data-id'), 10);
            sortArray[productId] = sortOrder++;
            productIdsInEditableList.push(productId);      
        });

        var addedProductIds = $(productIdsInEditableList).not(allLoadedProductIds).get();
        var deletedProductIds = $(allLoadedProductIds).not(productIdsInEditableList).get();
        /**
         * Get product that changed sort order 
         */
        var sortedEditableListLessAdded = $(productIdsInEditableList).not(addedProductIds).get();
        var originalListLessDeleted = $(allLoadedProductIds).not(deletedProductIds).get();
        var sortedProductIds = [];
        if(sortedEditableListLessAdded.length === originalListLessDeleted.length){
            for(i = 0; i < sortedEditableListLessAdded.length; i++){
                if(sortedEditableListLessAdded[i] !== originalListLessDeleted[i]){
                    sortedProductIds.push(sortedEditableListLessAdded[i]);
                }
            }
        }
        
        var addData = [];
        $.each(addedProductIds, function(key, productId){
            addData.push({ productId: productId, order: sortArray[productId] });
        });
        var sortData = [];
        $.each(sortedProductIds, function(key, productId){
            addData.push({ productId: productId, order: sortArray[productId] });
            deletedProductIds.push(productId);
        });
        $.ajax({
            type: "POST",
            url: '/memberpage/editCustomCategory',
            data: {
                categoryId:categoryId, 
                categoryName: categoryName, 
                parentCategory: newParentCategory,
                addData: JSON.stringify(addData),
                deleteData: JSON.stringify(deletedProductIds),
                csrfname: csrftoken
            },
            success: function(data){ 
                var jsonData = $.parseJSON(data);
                if(jsonData.result){
                    var escapedCategoryName = escapeHtml(categoryName);
                    var previousParentCategory = 0;
                    var $parentListItem = $('#category-tree-reference li[data-categoryid="'+categoryId+'"]').parentsUntil("#category-tree-reference", "li");
                    if($parentListItem.length > 0){
                        previousParentCategory = parseInt($parentListItem.data('categoryid'),10);
                    }
                    if(newParentCategory !== previousParentCategory){
                        $('#category-tree-reference li[data-categoryId="'+categoryId+'"]').remove();
                        var parent = $('#category-tree-reference>ul');
                        var childHtml = '<li data-categoryid="'+categoryId+'">'+escapedCategoryName+'<span class="icon-edit modal-category-edit pull-right edit-category"></span></li>';
                        if(newParentCategory !== 0){
                            var parent = $('#category-tree-reference li[data-categoryId="'+newParentCategory+'"]');
                            if(parent.find('ul').length > 0){
                                parent = parent.find('ul');
                            }
                            else{
                                childHtml = '<ul>'+childHtml+'</ul>';
                            }
                        }
                        parent.append(childHtml);
                    }
                    else{
                        var categoryListItemToUpdate = $('#category-tree-reference li[data-categoryid="'+categoryId+'"]');
                        var children = categoryListItemToUpdate.find('ul');
                        var childrenHtml = '';
                        if(children.length > 0){
                            childrenHtml = '<ul>'+children[0].innerHTML+'</ul>';
                        }
                        categoryListItemToUpdate.html(escapedCategoryName+'<span class="icon-edit modal-category-edit pull-right edit-category"></span>'+childrenHtml);                        
                    }
                    populateCategoryTrees();
                    $modalDiv.find('.simplemodal-close').click();
                }
                else{
                    var errorDiv = $modalDiv.find('.customized-category-error');
                    errorDiv.fadeIn().delay(5000).fadeOut();
                    errorDiv.find('.error-message').html('Sorry, please fix the following errors: ' + escapeHtml(jsonData.errorMessage));
                }
            }
        });
    });

     $(document.body).on('click', '.save-new-category', function(){
        var $btn = $(this);
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');   
        var $modalDiv = $btn.closest('.add-category-modal');
        var categoryName = $modalDiv.find('.category-name').val();
        var $currentCategoryProductList = $modalDiv.find('.category-product-list .category-item-name');
        var parentCategory = parseInt($modalDiv.find('.parent-category-dropdown option:selected').val(), 10);
        var currentCategoryProductsIds = [];
        $.each($currentCategoryProductList, function(key, $itemNameDiv){
            currentCategoryProductsIds.push($itemNameDiv.getAttribute('data-id'));
        });

        $.ajax({
            type: "POST",
            url: '/memberpage/addCustomCategory',
            data: {
                categoryName: categoryName, 
                parentCategory: parentCategory,
                productIds: JSON.stringify(currentCategoryProductsIds),
                csrfname: csrftoken
            },
            success: function(data){ 
                var jsonData = $.parseJSON(data);
                if(jsonData.result){
                    var escapedCategoryName = escapeHtml(categoryName);
                    var memberCategoryId = jsonData.newCategoryId;
                    var $referenceTreeList = $('#category-tree-reference>ul');
                    var newListElement = '<li data-categoryid="'+memberCategoryId+'">'+escapedCategoryName+'<span class="icon-edit modal-category-edit pull-right edit-category"></span></li>';
                
                    if(parentCategory === 0){
                        $referenceTreeList.append(newListElement);
                    }
                    else{
                        var $editTreeParent = $referenceTreeList.find('li[data-categoryid="'+parentCategory+'"]');
                        if($editTreeParent.find('ul').length === 0){
                            $editTreeParent.append('<ul></ul>')
                        }
                        $editTreeParent.find('ul').append(newListElement);
                    }
                    populateCategoryTrees();

                    $modalDiv.find('.simplemodal-close').click();
                    $('.add-store-cat-message').fadeIn().delay(5000).fadeOut();
                    $('#no-category-display-edit').hide();
                    $('#div-store-content-edit').show();
                    $('#no-category-display-delete').hide();
                    $('#div-store-content-delete').show();
                }
                else{
                    var errorDiv = $modalDiv.find('.customized-category-error');
                    errorDiv.fadeIn().delay(5000).fadeOut();
                    errorDiv.find('.error-message').html('Sorry, please fix the following errors: ' + escapeHtml(jsonData.errorMessage));
                }
            }
        });

    });

    
    function retrieveAllProductList(modalDiv, page, searchString)
    {
        page = typeof page !== 'undefined' ? page : 1;
        searchString = typeof searchString !== 'undefined' ? searchString : '';
        
        var $allProductDiv = modalDiv.find('.all-items');
        var isProcessing = JSON.parse($allProductDiv.attr('data-isProcessing'));
        if(isProcessing){
            return false;
        }
   
        var categoryId = modalDiv.find('.hidden-category-id').val();
        var $currentCategoryProductList = modalDiv.find('.category-product-list .category-item-name');  
        var currentCategoryProductsIds = [];
        $.each($currentCategoryProductList, function(key, $itemNameDiv){
            currentCategoryProductsIds.push($itemNameDiv.getAttribute('data-id'));
        });
        

        $.ajax({
            type: "GET",
            url: '/memberpage/getAllMemberProducts',
            data: {
                page:page, 
                excludeCategoryId: categoryId, 
                searchString: searchString, 
                excludeProductId: JSON.stringify(currentCategoryProductsIds)
            },
            beforeSend: function( xhr ) {
                $allProductDiv.attr('data-isProcessing', 'true');
            },
            success: function(data){ 
                var response = $.parseJSON(data);
                $allProductDiv.attr('data-isProcessing', 'false');
               
                $allProductDiv.find('.loader').hide();
                if(response.products.length === 0){
                    $allProductDiv.attr('data-isComplete', 'true');
                }
                else{
                    $allProductDiv.attr('data-page', page);
                    var listHtmlCollection = [];
                    $.each(response.products, function(key, product){
                        var image  = config.assetsDomain+product.imageDirectory+'thumbnail/'+product.imageFilename;
                        var listHtml = ' <li class="ui-widget-content ui-corner-tr"> ' +
                                            '<a href="javascript:void(0)" class="icon-move icon-move-to-custom-category pull-right" ></a>'+
                                            '<div class="category-item-image" style="background: #fff url('+image+')center no-repeat; background-size: 90%;" ></div>'+
                                            '<div class="category-item-name" data-id="'+product.id+'">'+escapeHtml(product.productName)+'</div>'+
                                        '</li>';
                        listHtmlCollection.push(listHtml);
                    });
                    var $allProductList = modalDiv.find('.all-product-list');
                    $allProductList.append(listHtmlCollection);
                }
                createCustomizedCategoryModal(modalDiv);
            }
        });
    }
    
    
    function appendCategoryProductList($itemsDiv, products)
    {
        var listHtmlCollection = [];
        var loadedProductIds = [];
        $.each(products, function(key, product){
            var image  = config.assetsDomain+product.imageDirectory+'thumbnail/'+product.imageFilename;
            var listHtml = ' <li class="ui-widget-content ui-corner-tr"> ' +
                                '<a href="javascript:void(0)" class="icon-move icon-move-to-all-items pull-right" ></a>'+
                                '<div class="category-item-image" style="background: #fff url('+image+')center no-repeat; background-size: 90%;" ></div>'+
                                '<div class="category-item-name" data-id="'+product.id+'">'+escapeHtml(product.productName)+'</div>'+
                            '</li>';
            listHtmlCollection.push(listHtml);
            loadedProductIds.push(product.id)
        });
        var $categoryProductList = $itemsDiv.find('.product-list');
        $categoryProductList.append(listHtmlCollection);
      
        if($categoryProductList.hasClass('category-product-list')){

            $allLoadedProductInput = $itemsDiv.find('.all-loaded-products-ids');
            var currentProductIds = $.parseJSON($allLoadedProductInput.val());
            var newProductIds = $.merge(currentProductIds, loadedProductIds);
            $allLoadedProductInput.val(JSON.stringify(newProductIds));
        }
    }

    
    function loadMoreCategoryProducts($div, isScrollIgnore)
    {    
        isScrollIgnore = typeof isScrollIgnore === 'undefined' ? false : isScrollIgnore;
        var isComplete = $div.attr('data-isComplete');
        if($.parseJSON(isComplete)){
            return false;
        }
        var div = $div[0];
        if(isScrollIgnore === true || div.scrollTop + div.clientHeight >= div.scrollHeight){
            
            $modalDiv = $div.closest('.category-modal');            
            var categoryId = $modalDiv.find('.hidden-category-id').val();
            var page = parseInt($div.attr('data-page'), 10) + 1;
            var $searchDiv = $div.siblings('.category-panel-header');
            var searchString = $searchDiv.find('.search-category').val();

            var $loader = $div.find('.loader');
            $loader.show();

            if($div.hasClass('category-items')){
                var isProcessing = $.parseJSON($div.attr('data-isProcessing'));
                if(isProcessing){
                    return false;
                }
                
                $.ajax({
                    type: "GET",
                    url: '/memberpage/getCustomCategory',
                    data: {categoryId:categoryId, page: page, searchString: searchString},
                    beforeSend: function( xhr ) {
                        $div.attr('data-isProcessing', 'true');
                    },
                    success: function(data){
                        $loader.hide();
                        var jsonResponse = $.parseJSON(data);
                        $div.attr('data-isProcessing', 'false');
                        if(jsonResponse.products.length === 0){
                            $div.attr('data-isComplete', 'true');
                        }
                        else{
                            $div.attr('data-page', page);
                            appendCategoryProductList($div, jsonResponse.products)
                        }
                    }
                });
            }
            else if($div.hasClass('all-items')){
                retrieveAllProductList($modalDiv, page, searchString)
            }
        }
    }

    function createCustomizedCategoryModal(div)
    {
        div.modal({
            persist:true
        });
        
        var $allProductList = div.find('.all-product-list');              
        var $categoryProductList = div.find('.category-product-list');
        $categoryProductList.sortable({
            connectWith: $allProductList,
            receive: function( event, ui ) {
                var $listContainer = $allProductList.closest('.category-items-holder');
                checkContainerScrollable($listContainer);
            }
        });                 
        $allProductList.sortable({
            connectWith: $categoryProductList,
            receive: function( event, ui ) {
                var $listContainer = $categoryProductList.closest('.category-items-holder');
                checkContainerScrollable($listContainer);
            }
        });
        div.parents(".simplemodal-container").addClass("my-category-modal").removeAttr("id");
        var addContentHeight = div.outerHeight();
        var countAllItems = $allProductList.find('li').size();
        var totalWidthOfMobileDroppable = countAllItems * widthOfDragabbleItem;
        if(browserWidth <= mobileViewPortWidthLimit){
            $(".my-category-modal").css("width", modalCategoryModalWidthMobile+"px").css("height","auto").css("bottom","auto").css("top","15px");
            //$(".category-items-holder .ui-sortable").css("width", totalWidthOfMobileDroppable+"px");
        }
        else{
            $(".my-category-modal").css("width", modalCategoryModalWidth+"px").css("height","auto").css("bottom","auto").css("top","15px");
            $(".category-items-holder .ui-sortable").css("width", "100%");
        }

        div.find(".category-items-holder").bind('scroll', function(){
            loadMoreCategoryProducts($(this));
        });

        $(".overlay-for-waiting-modal").css("display", "none");
    }

    var modalDeleteHeight =  $(".delete-confirmation-modal").outerHeight();
    $(window).on("load resize",function(){
        var modalDeleteHeight =  $(".delete-confirmation-modal").outerHeight();
        if(browserWidth <= mobileViewPortWidthLimit){
            $(".my-category-modal").css("width", modalCategoryModalWidthMobile+"px").css("height","auto").css("bottom","auto").css("top","15px");
            $(".my-category-modal-sm").css("width", "95%").css("height",modalDeleteHeight+"px");
        }
        else if (browserWidth > mobileViewPortWidthLimit){
            $(".my-category-modal").css("width", modalCategoryModalWidth+"px").css("height","auto").css("bottom","auto").css("top","15px");
            $(".my-category-modal-sm").css("width", modalCategoryModalSmWidth+"px").css("height",modalDeleteHeight+"px");
        }
    });
    
    function checkContainerScrollable($categoryItemHolder)
    {
        var listContainer = $categoryItemHolder[0];
        var isContainerScrollable = listContainer.scrollHeight > listContainer.clientHeight;
        if(isContainerScrollable === false){
           loadMoreCategoryProducts($categoryItemHolder, true); 
        }  
    }

}(jQuery));


