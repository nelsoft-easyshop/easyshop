
(function ($) {

    var ERROR_MESSAGE = "Something went wrong. Please try again later.";
    var MIN_AMOUNT_ALLOWED = $("#min-amount-allowed").val();
    var heightOfModal = 0;
    var $csrftoken = $("meta[name='csrf-token']").attr('content');

    $(window).on("load resize",function(){ 
        heightOfModal = $(".simplemodal-wrap").outerHeight();
        $(".active-left-wing-cart-1").animate({width: "50%"}, 1000);
        $(".active-breadcrumb-icon").delay(1000).animate({backgroundColor: "#00a388"}, 1000);
        $(".active-breadcrumb-title").delay(1000).animate({color: "#00a388"}, 1000).css("font-weight", "bold");;

        $(".calculate-shipping-label").click(function(){
            $(".shipping-calculator-modal").modal({
                containerCss:{
                    height: heightOfModal
                }
            });
            $(".shipping-calculator-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
        }); 

        $('#shipping-state, #shipping-city').bind('change click',function () {
            $('body').animate({scrollTop: 1 }, 1000);
            $('body').animate({scrollTop: 0 }, 1000);
        });
    });
    var generateQuantitySelect = function ()
    {
        $(".item-quantity").each(function() {
            var $thisSelect = $(this);
            var $maxQuantity = $thisSelect.data('max');
            var $value = $thisSelect.data('value');
            var $appendString;
            var $selectedString;

            if($maxQuantity >= 9999){
                $maxQuantity = 9999
            }
            for (var i = 1 ; i <= $maxQuantity; i++) {
                $appendString += '<option value="'+i+'">'+ i +'</option>';
            }

            $thisSelect.append($appendString);
            $thisSelect.val($value);
        });
    }
    generateQuantitySelect();

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
                    if($usedPoints > 0){
                        window.location.href = "/cart?points=" + $usedPoints;
                    }
                    else{
                        location.reload();
                    }
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

        $(".remove-item-name").html(escapeHtml($productName));
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
                        checkPointsAvailability();
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
        var $maxQuantity = parseInt($this.data('max'));
        $cartRowId = $this.data('rowid');
        if($quantity.trim() == "" 
            || parseInt($quantity) <= 0 
            || parseInt($quantity) > $maxQuantity){
            validateRedTextBox(".item-quantity");
        }
        else{
            validateWhiteTextBox(".item-quantity");
            changeItemQuantity($quantity);
        }
    });

    function changeItemQuantity($quantity)
    {
        if($cartRowId == null){
            alert('Unable to change item quantity.');
            return false;
        }
        else{
            $quantity = parseInt($quantity);
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
                        $shippingFee = parseFloat(jsonResponse.totalShippingFee);
                        checkPointsAvailability();
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
    var $shippingFee = isNaN(parseFloat($("#summary-shipping").data('totalshipping'))) ? 0 : parseFloat($("#summary-shipping").data('totalshipping'));
    var $cartSubtotal = $("#summary-cart-subtotal").data('cartprice');
    var $usedPoints = 0;
    $('.btn-deduct-points').on('click', function(){
        var $pointHolder = $("#points-total"); 
        var $pointValue = Math.abs($pointHolder.val().trim());
        var $cartTotalPrice = parseFloat($cartSubtotal) + parseFloat($shippingFee);
        $usedPoints = $pointValue === "" ? 0 : parseFloat($pointValue);
        $usedPoints = isNaN($usedPoints) ? 0 : parseFloat($usedPoints);

        if($usedPoints <= 0){
            $('.btn-reset-points').hide();
        }
        else{
            $('.btn-reset-points').show();
        }

        if($usedPoints > $maxPoints){
            $usedPoints = 0;
            validateRedTextBox("#points-total");
        }
        else if($usedPoints > $cartTotalPrice){
            $usedPoints = 0;
            validateRedTextBox("#points-total");
        }
        else{
            validateWhiteTextBox("#points-total");
        }

        $("#used-points").val($usedPoints);
        computePrices();
    });
    $('.btn-deduct-points').trigger('click');

    $('.btn-reset-points').on('click', function(){
        var $this = $(this);
        $this.hide();
        $usedPoints = 0;
        $("#points-total").val("");
        $("#used-points").val($usedPoints);
        validateWhiteTextBox("#points-total");
        computePrices();
    });

    function checkPointsAvailability()
    {
        var $cartTotalPrice = parseFloat($cartSubtotal) + parseFloat($shippingFee)
        if($cartTotalPrice >= MIN_AMOUNT_ALLOWED){
            enablePoints();
        }
        else{
            disablePoints();
        }
    }

    function computePrices()
    {
        var $summaryContainer = $(".summary-container"); 
        var $cartTotalPrice = (parseFloat($cartSubtotal) + parseFloat($shippingFee)) - parseFloat($usedPoints);
        $summaryContainer.find('#summary-points').html(replaceNumberWithCommas($usedPoints.toFixed(2)));
        $summaryContainer.find('#summary-shipping').html(replaceNumberWithCommas($shippingFee.toFixed(2)));
        $summaryContainer.find('#summary-cart-subtotal').html(replaceNumberWithCommas($cartSubtotal));
        $summaryContainer.find('#summary-cart-total').html(replaceNumberWithCommas($cartTotalPrice.toFixed(2)));
    }

    function disablePoints()
    {
        $usedPoints = 0;
        $('.btn-reset-points').trigger("click");
        $("#points-total, .btn-deduct-points")
            .attr("disabled", "true")
            .prop("disabled", "true")
            .attr("readonly", "true")
            .prop("readonly", "true");
    }

    function enablePoints()
    {
        $("#points-total, .btn-deduct-points")
            .removeAttr("disabled")
            .removeAttr("readonly");
    }

    function removeCommas(string)
    {
        return string.replace(/\,/g,"");
    }
})(jQuery);

