(function ($) { 

    var ERROR_MESSAGE = "Something went wrong. Please try again later.";
    var heightOfModal = 0;
    var $csrftoken = $("meta[name='csrf-token']").attr('content');

    $(window).on("load resize",function(){
        var browserWidth = $(window).width();
        heightOfModal = $(".simplemodal-wrap").outerHeight();
        
        $('.circle-breadcrumb').animate({ background:'#000000' }, 3000);
        $(".calculate-shipping-label").click(function(){
            $(".shipping-calculator-modal").modal({
                containerCss:{
                    height: heightOfModal
                }
            });
            $(".shipping-calculator-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
        });

        $(".payment-label").click(function(){
            var subCatContainer = $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();
            var paymentName = $(this).parents(".payment-method-container").find("label").text();
            $(".payment-method-desc").not(subCatContainer).slideUp();
            $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();      
            $(".btn-payment-button").text("Pay Via "+paymentName);
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

    $('.calculate-shipping').on('click', function(){
        var $cityId = $("#shipping-state").val();
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
                $("#shipping-total").val(parseFloat(response).toFixed(2)); 
            },
            error: function (request, status, error) {
                alert(ERROR_MESSAGE);
            } 
        });
    });

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
                        computePrices();
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
    var $maxPoints = parseInt($("#points-total").data('totalpoints'));
    var $shippingFee = parseFloat($("#summary-shipping").data('totalshipping'));
    var $cartSubtotal = parseFloat($("#summary-cart-total").data('cartprice'));
    var $usedPoints = 0;
    $('.btn-deduct-points').on('click', function(){
        var $pointHolder = $("#points-total"); 
        $usedPoints = $pointHolder.val().trim() === "" ? 0 : parseInt($pointHolder.val().trim());
        $usedPoints = isNaN($usedPoints) ? 0 : parseInt($usedPoints);
        $("#used-points").val($usedPoints);
        computePrices();
    });

    function computePrices()
    {
        var $summaryContainer = $(".summary-container"); 
        var $cartTotalPrice = (parseFloat($cartSubtotal) + parseFloat($shippingFee)) - parseInt($usedPoints);

        $summaryContainer.find('#summary-points').html($usedPoints);
        $summaryContainer.find('#summary-cart-subtotal').html(replaceNumberWithCommas($cartSubtotal.toFixed(2)));
        $summaryContainer.find('#summary-cart-total').html(replaceNumberWithCommas($cartTotalPrice.toFixed(2)));
    }

    function removeCommas(string)
    {
        return string.replace(/\,/g,"");
    }
})(jQuery);


        