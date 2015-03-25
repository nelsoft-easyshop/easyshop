
(function ($) {
    var $csrftoken = $("meta[name='csrf-token']").attr('content');
    var heightOfModal = 0;
    var $selectedMethod = "paypalcdb";

    $(window).on("load resize",function(){ 
        heightOfModal = $(".simplemodal-wrap").outerHeight();
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

    $('.privacy-check').click(function(){
        if($(this).is(':checked')){
            validateWhiteTextBox('.privacy-check');
        }
    });

    $(".available-location-trigger").click(function(){
        $(".available-location-modal").modal({
            containerCss:{
                height: heightOfModal
            }
        });
        $(".available-location-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
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
        var $paymentButton = $(".btn-payment-button");
        $paymentButton.attr("disabled", "true"); 
        $paymentButton.prop("disabled", "true");
    }

    function enableButton()
    {
        var $paymentButton = $(".btn-payment-button");
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
    });

    $(".btn-save-changes").click(function(){

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
                        $(".error-"+index).html(value[0]);
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
            $.each(jsonCity[stateregionID], function(k,v){
                $('.cityselect').append('<option value="'+k+'">'+v+'</option>'); 
            });
        }
    }

    $('.stateregionselect').on('change', function(){
        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

    $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
    $('.stateregionselect').trigger('change');

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
                if (jsonResponse.e) { 
                    window.location.replace(jsonResponse.d);
                }
                else{
                    alert(escapeHtml(jsonResponse.d));
                    if(jsonResponse.d == 'Item quantity not available.'){
                        location.reload();
                    }
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
                if(jsonResponse.e){ 
                    window.location.replace(jsonResponse.u);
                }
                else{ 
                    if(jsonResponse.m == 'Item quantity not available.'){
                        location.reload();
                    }
                    alert(escapeHtml(jsonResponse.m));
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
            dataType: 'html',
            data: "csrfname="+$csrftoken+"&paymentMethods="+$paymentMethod,
            success: function(jsonResponse){
                window.location.replace(jsonResponse);
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
                submitCashOnDelivery();
            }
            else{
                submitPaypal($pointsUsed, $cdbPaypalType);
            }
        }
        else{
            validateRedTextBox('.privacy-check');
        }
    });
})(jQuery);
