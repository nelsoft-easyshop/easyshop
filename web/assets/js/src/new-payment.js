
(function ($) {
    var $csrftoken = $("meta[name='csrf-token']").attr('content');

    $(".payment-label").click(function(){
        var subCatContainer = $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();
        var paymentName = $(this).parents(".payment-method-container").find("label").text();
        $(".payment-method-desc").not(subCatContainer).slideUp();
        $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();      
        $(".btn-payment-button").text("Pay Via "+paymentName);
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

})(jQuery);
