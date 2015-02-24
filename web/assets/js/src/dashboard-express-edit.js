(function ($) {  

    var $csrftoken = $("meta[name='csrf-token']").attr('content'); 
    var $removeCombination = [];
    var $slug;

    function removeComma(number)
    {
        return number.replace(new RegExp(",", "g"), '');
    }

    function computeDiscountPrice(basePrice, discountRate, discountPrice)
    {
        var finalPrice;
        var finalDiscountRate;
        if(discountPrice === 0){ 
            discountPrice = basePrice * (discountRate/100);
            finalPrice = basePrice - discountPrice;
            $(".discount-price").val(replaceNumberWithCommas(finalPrice.toFixed(2)));
        }
        else{
            finalDiscountRate = ((basePrice - discountPrice) / basePrice) * 100;
            $(".discount-rate").val(finalDiscountRate.toFixed(4));
        }
    }

    function showError(message)
    {
        $(".error-message").show().append(message + "<br>");
    } 

    function validate($this)
    {   
        $(".error-message").hide().html(""); 
        var $container = $this.closest('.express-edit-content');
        var $basePrice = parseFloat(removeComma($container.find('.base-price').val()));
        var $discountRate = parseFloat(removeComma($container.find('.discount-rate').val()));
        var $discountPrice = parseFloat(removeComma($container.find('.discount-price').val()));
        var $productName = $.trim($container.find('.product-name').val());
        var $errorCount = 0;

        if($basePrice > 0){
            validateWhiteTextBox('.base-price');
        }
        else{
            $errorCount++;
            validateRedTextBox('.base-price');
            showError("Invalid price. Product price cannot be less than 0.");
        }

        if($productName.length > 0){
            validateWhiteTextBox('.product-name');
        }
        else{
            $errorCount++;
            validateRedTextBox('.product-name');
            showError("Product name cannot be empty.");
        }

        if(parseInt($discountRate) >= 0 && parseInt($discountRate) <= 99){
            validateWhiteTextBox('.discount-rate');
        }
        else{
            $errorCount++;
            validateRedTextBox('.discount-rate');
            showError("Invalid discount. Range must be 0 - 99 only.");
        }

        if($discountPrice <= $basePrice && $discountPrice > 0){
            validateWhiteTextBox('.discount-price');
        }
        else{
            $errorCount++;
            validateRedTextBox('.discount-price');
            showError("Invalid discount price. Range must be less than base price and greater than 0")
        }

        if($errorCount > 0){
            return false;
        }
        return true;
    }

    $(document).on('change',".base-price",function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $value = parseInt(removeComma($this.val()));
        var $discountRate = parseFloat(removeComma($container.find('.discount-rate').val()));
        validate($this);
        if($value >= 0){  
            computeDiscountPrice($value, $discountRate, 0);
        }
        $this.val($value.toFixed(2));
    });

    $(document).on('change',".discount-rate",function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $value = parseInt(removeComma($this.val()));
        var $basePrice = parseFloat(removeComma($container.find('.base-price').val()));

        validate($this);
        if($value >= 0 && $value <= 99){  
            computeDiscountPrice($basePrice, $value, 0);
            validate($this);
        }
        $this.val($value.toFixed(4));
    });

    $(document).on('change',".discount-price",function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $value = parseInt(removeComma($this.val()));
        var $basePrice = parseFloat(removeComma($container.find('.base-price').val()));

        validate($this);
        if($value <= $basePrice && $value > 0){  
            computeDiscountPrice($basePrice, 0, $value);
            validate($this);
        }
        $this.val($value.toFixed(2));
    });

    $(document.body).on('click','.cancel-btn',function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        $container.find('cancel-btn').trigger('click');
    });

    $(document.body).on('click','.save-btn',function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $itemArray;
        var $retainCombination = [];
        var $productName = $container.find('.product-name').val();
        var $basePrice = $container.find('.base-price').val();
        var $discountRate = $container.find('.discount-rate').val();
        var $soloCombination = $container.find('.solo-quantity').val();
        $container.find('.combination-row').each(function(){
            $itemArray = {};
            $itemArray.quantity = $(this).find('.quantity-control').val();
            $itemArray.itemId = $(this).find('.item-control').val();
            $retainCombination.push($itemArray);
        });

        if(validate($this)){
            var $ajaxRequest = $.ajax({
                type: "POST",
                data:{
                    slug: $slug,
                    csrfname: $csrftoken,
                    productName: $productName,
                    productPrice: $basePrice,
                    discount: $discountRate,
                    quantity: $soloCombination,
                    retain: JSON.stringify($retainCombination),
                    remove: JSON.stringify($removeCombination),
                },
                url: '/me/product/expressedit-update',
                success: function(requestResponse){
                    var $response = $.parseJSON(requestResponse);
                    if($response.result){
                        $removeCombination = [];
                        reload();
                    }
                    else{
                        alert($response.error);
                    }
                }
            });
        }
    });

    $(document.body).on('click','.remove-row',function () {
        var $ask = confirm("Are you sure want to remove this row?");
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $count; 
        var $closestTR = $this.closest('tr');
        if($ask){  
            $removeCombination.push($closestTR.find('.item-control').val());
            $closestTR.remove();
            $count = $container.find('.combination-row').length;
            if($count <= 1){
                $container.find('.prod-att-table').find('.remove-row').remove();
            }
        } 
    });

    $(document).on('change',".product-name",function () {
        var $this = $(this); 
        validate($this);
    });

    $(document.body).on('click','.btn-edit-product',function () {
        var $this = $(this);
        $slug = $this.data('slug');

        $(".ui-dialog > #express-edit-section").html("PLEASE WAIT!");
        $('#express-edit-section').dialog({
            autoOpen: true,
            dialogClass: 'express-edit-wrapper',
            width: 1180,
            modal: true,
            fluid: true,
            draggable: false,
            open: function() { 
                var $ajaxRequest = $.ajax({
                    type: "POST",
                    data:{slug:$slug,csrfname:$csrftoken},
                    url: '/me/product/expressedit-request',
                    success: function(requestResponse){ 
                        var $response = $.parseJSON(requestResponse);
                        $(".ui-dialog > #express-edit-section").html($response); 
                        $(".error-message").hide(); 
                        $(".base-price").trigger("change");
                    }
                });
            }
        });
    });

})(jQuery);
