
(function ($) {

    var ERROR_MESSAGE = "Something went wrong. Please try again later.";
    var heightOfModal = 0;
    var $csrftoken = $("meta[name='csrf-token']").attr('content');

    $(window).on("load resize",function(){ 
        heightOfModal = $(".simplemodal-wrap").outerHeight();

        $(".calculate-shipping-label").click(function(){
            $(".shipping-calculator-modal").modal({
                containerCss:{
                    height: heightOfModal
                }
            });
            $(".shipping-calculator-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
        }); 
    }); 

    // shipping fee section
    var cityFilter = function(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value'); 
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){ 
            $('.cityselect').empty();
            $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
            $.each(jsonCity[stateregionID], function(key, value){
                $('.cityselect').append('<option value="'+escapeHtml(key)+'">'+escapeHtml(value)+'</option>'); 
            });
        }
        else{
            $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
        }
    }

    $('.stateregionselect').on('change', function(){
        var $this = $(this);
        var $updateLocationButton = $(".update-shipping");
        var cityselect = $this.parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $this, cityselect );
        $updateLocationButton.prop("disabled", "true");
        $updateLocationButton.attr("disabled", "true");
        $("#shipping-total").val("Please select city");
        validateWhiteTextBox("#shipping-total");
    });

    $('.cityselect').on('change', function(){
        var $cityId = $("#shipping-state").val();
        var $updateLocationButton = $(".update-shipping");
        $updateLocationButton.prop("disabled", "true");
        $updateLocationButton.attr("disabled", "true"); 

        if(parseInt($cityId) != 0){
            var $currentRequest = $.ajax({
                type: "POST",
                url: '/cart/getCartTotalShippingFee',
                data:{
                    city_id:$cityId, 
                    csrfname:$csrftoken
                },
                beforeSend : function(){
                    if($currentRequest != null) {
                        $currentRequest.abort();
                    }
                },
                success: function(response) {
                    if(isNaN(response)){
                        validateRedTextBox("#shipping-total");
                    }
                    else{
                        validateWhiteTextBox("#shipping-total");
                        $updateLocationButton.removeAttr("disabled");  
                    }
                    $("#shipping-total").val(response);
                },
                error: function (request, status, error) {
                    alert(ERROR_MESSAGE);
                } 
            });
        }
    });

    $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
    $('.stateregionselect').trigger('change');
    $(".update-shipping").prop("disabled", "true").attr("disabled", "true");

    $('.update-shipping').on('click', function(){
        var $errorCount = 0;
        if($("#shipping-city").val() == 0){
            $errorCount++;
        }
        if($("#shipping-state").val() == 0){
            $errorCount++;
        }

        if($errorCount === 0){
            updateAddress();
        }
    });

    function updateAddress()
    {
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
                    location.reload();
                } 
            },
            error: function (request, status, error) { 
                alert('Someting went wrong try again later.');
            } 
        });
    }

    // cart item manipulation
    var $cartRowId = null;
    var $currentModal = null;
    $('.cart-item-remove').on('click', function(){
        var $this = $(this);
        var $productName = $this.data('name');
        $cartRowId = $this.data('rowid');

        $(".remove-item-name").html($productName);
        $(".remove-item-modal").modal({
            onShow : function() { 
                $currentModal = this;
            },
            onClose: function(){
                $cartRowId = null;
                $currentModal.close();
            }
         });
        $(".remove-item-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
        $(".my-modal").css("height", heightOfModal+"px");
    });

    $('.remove-item').on('click', function(){
        removeItem();
    });

    function removeItem()
    {
        if($cartRowId == null){
            alert('Unable to remove item.');
            return false;
        }
        else{
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
                        $(".row-"+$cartRowId).remove();
                        $cartSubtotal = removeCommas(jsonResponse.totalPrice);
                        $shippingFee = parseFloat(jsonResponse.totalShippingFee);
                        computePrices();
                        if(jsonResponse.numberOfItems <= 0){
                            location.reload();
                        }
                    }
                    $cartRowId = null;
                    $currentModal.close()
                },
                error: function (request, status, error) {
                    alert(ERROR_MESSAGE);
                    $cartRowId = null;
                    $currentModal.close();
                } 
            });
        }
    }

    $('.item-quantity').on('change', function(){
        var $this = $(this);
        var $quantity = $this.val();
        $cartRowId = $this.data('rowid');
        changeItemQuantity($quantity);
    });

    function changeItemQuantity($quantity)
    {
        if($cartRowId == null){
            alert('Unable to change item quantity.');
            return false;
        }
        else{
            var $container = $(".row-"+$cartRowId);
            var $currentRequest = $.ajax({
                type: "POST",
                url: '/cart/doChangeQuantity',
                dataType: "json",
                data:{
                    id: $cartRowId, 
                    qty: $quantity,
                    csrfname: $csrftoken
                },
                success: function(jsonResponse) {
                    if(jsonResponse.isSuccessful){
                        $container.find('.cart-item-subtotal')
                                  .html(jsonResponse.itemSubtotal);
                        $cartSubtotal = removeCommas(jsonResponse.cartTotal);
                        computePrices();
                    }
                    $cartRowId = null;
                },
                error: function (request, status, error) {
                    alert(ERROR_MESSAGE);
                    $cartRowId = null;
                } 
            });
        }
    }

    // summary computation
    var $maxPoints = parseFloat($("#points-total").data('totalpoints'));
    var $shippingFee = parseFloat($("#summary-shipping").data('totalshipping'));
    var $cartSubtotal = $("#summary-cart-total").data('cartprice');
    var $usedPoints = 0;
    $('.btn-deduct-points').on('click', function(){
        var $pointHolder = $("#points-total"); 
        var $pointValue = $pointHolder.val().trim();
        $usedPoints = $pointValue === "" ? 0 : parseFloat($pointValue);
        $usedPoints = isNaN($usedPoints) ? 0 : parseFloat($usedPoints);

        if($usedPoints > $maxPoints){
            $usedPoints = 0;
            validateRedTextBox("#points-total");
        }
        else{
            validateWhiteTextBox("#points-total");
        }

        $("#used-points").val($usedPoints);
        computePrices();
    });

    $('.btn-reset-points').on('click', function(){
        $usedPoints = 0;
        $("#points-total").val("");
        $("#used-points").val($usedPoints);
        computePrices();
    });

    function computePrices()
    {
        var $summaryContainer = $(".summary-container"); 
        var $cartTotalPrice = (parseFloat($cartSubtotal) + parseFloat($shippingFee)) - parseInt($usedPoints);

        $summaryContainer.find('#summary-points').html(replaceNumberWithCommas($usedPoints.toFixed(2)));
        $summaryContainer.find('#summary-shipping').html(replaceNumberWithCommas($shippingFee));
        $summaryContainer.find('#summary-cart-subtotal').html(replaceNumberWithCommas($cartSubtotal));
        $summaryContainer.find('#summary-cart-total').html(replaceNumberWithCommas($cartTotalPrice.toFixed(2)));
    }

    function removeCommas(string)
    {
        return string.replace(/\,/g,"");
    }
})(jQuery);

