(function ($) {
    $(window).on("load resize",function(){
        $(".cart-item-remove").click(function(){
            $(".remove-item-modal").modal();
            var heightOfRemoveItemModal = $("remove-item-modal").outerHeight();
            $(".remove-item-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
            $(".my-modal").css("height", heightOfRemoveItemModal+"px");
        });

        $(".payment-label").click(function(){
            var subCatContainer = $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();
            var paymentName = $(this).parents(".payment-method-container").find("label").text();
            $(".payment-method-desc").not(subCatContainer).slideUp();
            $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();
            $(".btn-payment-button").text("Pay Via "+paymentName);
        });
    });
})(jQuery);


// shipping fee section
(function ($) {
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
        var $csrftoken = $("meta[name='csrf-token']").attr('content');
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
            }
        });
    });
})(jQuery);