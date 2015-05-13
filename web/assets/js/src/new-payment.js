
(function ($) {
    var $csrftoken = $("meta[name='csrf-token']").attr('content');
    var heightOfModal = 0;
    var $selectedMethod = "paypalcdb";
    var $cityselect = $('.cityselect');
    var heightOfModal = $(".simplemodal-wrap").outerHeight();

    $(window).on("load resize",function(){
        var heightOfModal = $(".simplemodal-wrap").outerHeight();
        $(".done-icon").slideUp(250);
        $(".new-icon").slideDown(250);
        $(".active-right-wing-cart-1").delay(500).animate({width: "50%"}, 1000);
        $(".active-left-wing-cart-2").delay(1500).animate({width: "50%"}, 1000);
        $(".active-breadcrumb-icon").delay(2500).animate({backgroundColor: "#00a388"}, 1000);
        $(".active-breadcrumb-title").delay(2500).animate({color: "#00a388"}, 1000).css("font-weight", "bold");  
    });

    $(".payment-label").click(function(){
        var $this = $(this);
        var subCatContainer = $this.parents(".payment-method-container").find(".payment-method-desc").slideDown();
        var paymentName = $this.parents(".payment-method-container").find("label").text();
        $(".payment-method-desc").not(subCatContainer).slideUp();
        $this.parents(".payment-method-container").find(".payment-method-desc").slideDown();      
        $(".btn-payment-button").text("Pay Via "+paymentName);
        $selectedMethod = $this.val();
    });


    $(".payment-label:first").trigger("click");

    $('.privacy-check').click(function(){
        if($(this).is(':checked')){
            validateWhiteTextBox('.privacy-check');
        }
    });

    $(".available-location-trigger").click(function(){
        var $itemId = $(this).data('itemid');
        $(".available-location-modal").modal({
            onShow : function() {  
                getProductLocation($itemId);
            },
            containerCss:{
                height: heightOfModal
            }
        });

        getMarginTop();
        $(window).on("resize",function(){
            getMarginTop();
        }); 
        //$(".available-location-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
    });

    $(".checkout-item-attribute-container-header").click(function(){
        var $this = $(this);
        var triggeredAttributeBody = $this.parent().find(".checkout-item-attribute-container-body");
        var triggeredAttributeActionText = $this.parent().find(".checkout-item-attribute-container-action");
        
        $(".checkout-item-attribute-container-header").not($this).removeClass("toggled");
        $(".checkout-item-attribute-container-body").not(triggeredAttributeBody).slideUp("fast");
        $(".checkout-item-attribute-container-action").not(triggeredAttributeActionText).text("show");
        
        $this.toggleClass("toggled");
        triggeredAttributeBody.slideToggle("fast");

        if ($('.checkout-item-attribute-container-header').hasClass('toggled')) {
            triggeredAttributeActionText.text("hide");
        }
        else {
            triggeredAttributeActionText.text("show");  
        }
    });

    function disableTextBox()
    {
        $("#fname, #telephone, #mobile, #fullAddress").attr("readonly", "true");
        $("#shipping-city, #shipping-state").attr("disabled", "true");
        $("#fname, #telephone, #mobile, #fullAddress").prop("readonly", "true");
        $("#shipping-city, #shipping-state").prop("disabled", "true");
        $("#delivery-address-error").hide();
        $(".error-span").html("");
    }

    function toggleButton()
    {
        $(".div-change-shipping-btn").slideToggle("fast");
        $(".div-save-shipping-btn").slideToggle("fast");
    }

    function disableButton()
    {
        var $paymentButton = $(".btn-payment-button, .pay-via-easypoints");
        $paymentButton.attr("disabled", "true"); 
        $paymentButton.prop("disabled", "true");
    }

    function enableButton()
    {
        var $paymentButton = $(".btn-payment-button, .pay-via-easypoints");
        $paymentButton.removeAttr("disabled");  
    }
    enableButton();

    $(".btn-change-shipping").click(function(){
        toggleButton();
        $("#fname, #telephone, #mobile, #fullAddress").removeAttr("readonly");
        $("#shipping-city, #shipping-state").removeAttr("disabled");
    });

    $(".btn-change-shipping-cancel").click(function(){
        toggleButton();
        disableTextBox();
        $(".addressForm")[0].reset();
        $('.stateregionselect').trigger('change');
        $cityselect.val($cityselect.data('usercity'));
    });

    $(".btn-save-changes").click(function(){
        $(".error").empty();
        var $consigneeName = $("#fname").val();
        var $fullAddress = $("#fullAddress").val();
        var $telephone = $("#telephone").val();
        var $mobile = $("#mobile").val();
        var $shippingCity = $("#shipping-city").val();
        var $shippingRegion = $("#shipping-state").val();
        var $currentLat = $("#currentLat").val();
        var $currentLang = $("#currentLang").val();

        var $currentRequest = $.ajax({
            type: "POST",
            url: '/memberpage/edit_consignee_address',
            dataType: "json",
            data:{
                csrfname: $csrftoken,
                c_address: $fullAddress,
                c_stateregion: $shippingRegion,
                c_city: $shippingCity,
                consignee: $consigneeName,
                c_mobile: $mobile,
                c_telephone: $telephone,
                temp_lat: $currentLat,
                temp_lng: $currentLang,
                c_deliver_address_btn: true,
            },
            success: function(jsonResponse) {
                if(jsonResponse.isSuccessful){ 
                    $("#delivery-address-success").fadeIn().delay(3000).fadeOut();; 
                    toggleButton();
                    disableTextBox();
                    location.reload();
                }
                else{
                    $("#delivery-address-error").show();
                    $.each(jsonResponse.errors, function(index, value) { 
                        $(".error-"+index).html(escapeHtml(value[0]));
                    });
                }
            },
            error: function (request, status, error) { 
                alert('Someting went wrong try again later.');
                toggleButton();
                disableTextBox();
            } 
        });
    });

    // shipping fee section
    var cityFilter = function(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value'); 
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){ 
            $('.cityselect').empty();
            $.each(jsonCity[stateregionID], function(key, value){
                $('.cityselect').append('<option value="'+escapeHtml(key)+'">'+escapeHtml(value)+'</option>'); 
            });
        }
    }

    $('.stateregionselect').on('change', function(){
        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

    $cityselect.empty().append('<option value="0">--- Select City ---</option>');
    $('.stateregionselect').trigger('change');
    $cityselect.val($cityselect.data('usercity'));

    // payment request 
    function submitPaypal($pointAllocated, $payType)
    {
        var $paymentMethod; 
        if($.isNumeric($pointAllocated) && parseInt($pointAllocated) > 0){
            $paymentMethod = JSON.stringify({
                PaypalGateway:{
                    method: "PayPal", 
                    type: $payType
                },
                PointGateway:{
                    method: "Point",
                    amount: $pointAllocated,
                    pointtype: "purchase"
                }
            });
        }
        else{
            $paymentMethod = JSON.stringify({
                PaypalGateway:{
                    method: "PayPal", 
                    type: $payType
                }
            });
        }

        $.ajax({
            type: "POST",
            url: "/pay/pay", 
            dataType: "json",
            data: "csrfname="+$csrftoken+"&paymentMethods="+$paymentMethod, 
            beforeSend: function(jqxhr, settings) { 
                $('.paypal_loader').show();
                $('.paypal_button').hide();
            },
            success: function(jsonResponse) {
                if (jsonResponse.error) { 
                    alert(escapeHtml(jsonResponse.message));
                    if(jsonResponse.message == 'Item quantity not available.'){
                        location.reload();
                    }
                }
                else{
                    window.location.replace(jsonResponse.url);
                }
                enableButton();
            }, 
            error: function (request, status, error) {
                alert('We are currently experiencing problems.','Please try again after a few minutes.');
                enableButton();
            }
        });
    }

    function submitDragonpay($pointAllocated)
    {
        var $paymentMethod;
        if($.isNumeric($pointAllocated) && parseInt($pointAllocated) > 0){
            $paymentMethod = JSON.stringify({
                DragonPayGateway:{
                    method: "DragonPay", 
                },
                PointGateway:{
                    method: "Point",
                    amount: $pointAllocated,
                    pointtype: "purchase"
                }
            });
        }
        else{
            $paymentMethod = JSON.stringify({
                DragonPayGateway:{
                    method: "DragonPay", 
                }
            });
        }

        $.ajax({
            type: "POST",
            url: "/pay/pay", 
            dataType: "json",
            data: "csrfname="+$csrftoken+"&paymentMethods="+$paymentMethod, 
            success: function(jsonResponse) {
                if(jsonResponse.error){ 
                    if(jsonResponse.message == 'Item quantity not available.'){
                        location.reload();
                    }
                    alert(escapeHtml(jsonResponse.message));
                }
                else{ 
                    window.location.replace(jsonResponse.url);
                }
                enableButton();
            },
            error: function(err){
                alert('Something went wrong. Please try again later'); 
                enableButton();
            }
        });
    }

    function submitCashOnDelivery()
    {
        var $paymentMethod = JSON.stringify({
            CODGateway:{
                method:"CashOnDelivery"
            }
        });
        $.ajax({
            url: "/pay/pay",
            type: 'POST',
            dataType: 'json',
            data: "csrfname="+$csrftoken+"&paymentMethods="+$paymentMethod,
            success: function(jsonResponse){
                if(jsonResponse.error === false){
                    window.location.replace(jsonResponse.url);
                }
                else{
                    alert(jsonResponse.message);
                    enableButton();
                }
            },
            error: function(err){
                alert('Something went wrong. Please try again later'); 
                enableButton();
            }
        });
    }

    function submitPesopay($pointAllocated)
    {
        var $paymentMethod; 
        if($.isNumeric($pointAllocated) && parseInt($pointAllocated) > 0){
            $paymentMethod = JSON.stringify({
                PesoPayGateway:{
                    method:"PesoPay", 
                },
                PointGateway:{
                    method: "Point",
                    amount: $pointAllocated,
                    pointtype: "purchase"
                }
            });
        }
        else{
            $paymentMethod = JSON.stringify({
                PesoPayGateway:{
                    method:"PesoPay", 
                }
            });
        }
        $.ajax({
            type: "POST",
            url:  "/pay/pay", 
            dataType: "json",
            data: "csrfname="+$csrftoken+ "&paymentMethods=" + $paymentMethod,
            success: function(jsonResponse) {
                if(jsonResponse.error === false){
                    $('#pesopaycdb').append(jsonResponse.form);
                    $('#payFormCcard').submit();
                }
                else{ 
                    if(jsonResponse.message == 'Item quantity not available.'){
                        location.reload(); 
                    }
                    alert(escapeHtml(jsonResponse.message));
                }
                enableButton();
            },
            error: function(err){
                alert('Something went wrong. Please try again later'); 
                enableButton();
            }
        });
    }

    function submitEasyPoints($pointAllocated)
    {
        var $paymentMethod = JSON.stringify({ 
            PointGateway:{
                method: "Point",
                amount: $pointAllocated,
                pointtype: "purchase"
            }
        });
        $.ajax({
            url: "/pay/pay",
            type: 'POST',
            dataType: 'json',
            data: "csrfname="+$csrftoken+"&paymentMethods="+$paymentMethod,
            success: function(jsonResponse){
                if(jsonResponse.error === false){
                    window.location.replace(jsonResponse.url);
                }
                else{
                    alert(jsonResponse.message);
                    enableButton();
                }
            },
            error: function(err){
                alert('Something went wrong. Please try again later'); 
                enableButton();
            }
        });
    }

    $(".btn-payment-button").click(function(){
        var $this = $(this);
        var $pointsUsed = $this.data('points');
        var $cdbPaypalType = 2;
        var $loginPaypalType = 1;
        if($(".privacy-check").is(':checked')){ 
            disableButton();
            if($selectedMethod === "paypal"){
                submitPaypal($pointsUsed, $loginPaypalType);
            }
            else if($selectedMethod === "dragonpay"){
                submitDragonpay($pointsUsed);
            }
            else if($selectedMethod === "pesopay"){
                submitPesopay($pointsUsed);
            }
            else if($selectedMethod === "cod"){
                var $confirmation = confirm("Are you sure you want to pay using Cash on Delivery?");
                if($confirmation){
                    submitCashOnDelivery();
                }
                else{
                    enableButton();
                }
            }
            else{
                submitPaypal($pointsUsed, $cdbPaypalType);
            }
        }
        else{
            validateRedTextBox('.privacy-check');
        }
    });

    $(".pay-via-easypoints").click(function(){
        var $this = $(this);
        var $pointsUsed = $this.data('points');
        var $confirmation = confirm("Are you sure you want to pay using Easy Points?");

        if($confirmation){
            disableButton();
            submitEasyPoints($pointsUsed);
        }
    });

    // remove cart item
    $(".remove-item").click(function(){
        var $this = $(this);
        var $cartRowId = $this.data('rowid');
        var $currentRequest = $.ajax({
            type: "POST",
            url: '/cart/doRemoveItem',
            dataType: "json",
            data:{
                id:$cartRowId, 
                csrfname:$csrftoken
            },
            success: function(jsonResponse) {
                if(jsonResponse.isSuccessful){
                    location.reload();
                } 
            },
            error: function (request, status, error) {
                alert('Something went wrong. Please try again later'); 
            } 
        });
    });

    // get city availables
    function getProductLocation($itemId)
    {
        var $currentRequest = $.ajax({
            type: "POST",
            url: '/payment/getProductLocation',
            dataType: "json",
            data:{
                itemId:$itemId, 
                csrfname:$csrftoken
            },
            success: function(jsonResponse) {
                if(jsonResponse.isSuccessful){
                    $(".location-container").replaceWith(jsonResponse.view);
                }
                else{
                    alert(escapeHtml(jsonResponse.errorMessage));
                }
            },
            error: function (request, status, error) {
                alert('Something went wrong. Please try again later'); 
            } 
        });
    }

    function getMarginTop()
    {
        var windowHeight = $(window).outerHeight(); //Height of window
        var modalContentHeight = $("#simplemodal-data").outerHeight(); //Height modal content
        var remainingVerticalSpace = windowHeight - modalContentHeight; //Remaining vertical space of the window when modal is present
        var halfOfVerticalSpace = remainingVerticalSpace/2; //Half of the vertical space that will represent as the margin-top of the modal to center its vertical alignment

        $(".simplemodal-container").css("top", halfOfVerticalSpace+"px");
    }
})(jQuery);
