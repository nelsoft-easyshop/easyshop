(function ($) {  

    var $csrftoken = $("meta[name='csrf-token']").attr('content'); 
    var $removeCombination = [];
    var $slug;
    var $productId; 

    function removeComma(number)
    {
        return number.replace(new RegExp(",", "g"), '');
    }

    function computeDiscountPrice(basePrice, discountRate, discountPrice)
    {
        var finalPrice;
        var finalDiscountRate;
        var discounted;
        if(discountPrice === 0){
            discounted = basePrice * (discountRate/100);
            finalPrice = Math.abs(parseFloat(basePrice - discounted)); 
            $(".discount-price").val(replaceNumberWithCommas(finalPrice.toFixed(2)));
        }
        else{
            finalDiscountRate = ((basePrice - discountPrice) / basePrice) * 100;
            $(".discount-rate").val(finalDiscountRate.toFixed(2));
        }
    }

    function showError(message)
    {
        $(".error-message").show().append(message + "<br>");
    } 

    function getTotalStock($this)
    {
        var $container = $this.closest('.express-edit-content');
        var $totalStock = 0;
        $container.find('.txt-quantity').each(function(){
            $totalStock += parseInt($(this).val());
        });

        return $totalStock;
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

        if(parseInt($basePrice) > 0){
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

        if($discountPrice <= $basePrice && parseInt($discountPrice) > 0){
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

    $(document).on('change',".txt-quantity",function () {
        var $this = $(this);
        $(".txt-total-stock").val(getTotalStock($this));
    });

    $(document).on('change',".base-price",function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $value = parseFloat(removeComma($this.val())).toFixed(2);
        var $discountRate = parseFloat(removeComma($container.find('.discount-rate').val())); 
        
        if(parseInt($value) >= 0){
            computeDiscountPrice($value, $discountRate, 0);
        }
        $this.val(replaceNumberWithCommas($value));
        validate($this);
    });

    $(document).on('change',".discount-rate",function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $value = parseFloat(removeComma($this.val())).toFixed(2);
        var $basePrice = parseFloat(removeComma($container.find('.base-price').val()));

        if(parseInt($value) >= 0 && parseInt($value) <= 99){  
            computeDiscountPrice($basePrice, $value, 0);
        }
        $this.val($value);
        validate($this);
    });

    $(document).on('change',".discount-price",function () {
        var $this = $(this);
        var $container = $this.closest('.express-edit-content');
        var $value = parseFloat(removeComma($this.val())).toFixed(2);
        var $basePrice = parseFloat(removeComma($container.find('.base-price').val()));

        if($value <= $basePrice && parseInt($value) > 0){  
            computeDiscountPrice($basePrice, 0, $value);
        }
        $this.val(replaceNumberWithCommas($value));
        validate($this);
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
        var $discountPrice = parseFloat(removeComma($container.find('.discount-price').val()));
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
                    var $itemContainer = $("#item-list-"+$productId);
                    var $imageContainer = $itemContainer.find('.div-product-image')
                    var $discountContainer = $imageContainer.find('.pin-discount');
                    var $amountContainer = $itemContainer.find('.item-amount');
                    var $originalAmountContainer = $amountContainer.find('.item-original-amount');
                    if($response.result){
                        $removeCombination = [];
                        $itemContainer
                            .find(".item-list-name")
                            .children("a")
                            .html(escapeHtml($productName));
                        $itemContainer
                            .find(".stock-number")
                            .html(escapeHtml(getTotalStock($this)));

                        if(parseInt($discountRate) > 0){
                            if($discountContainer.length > 0){
                                $discountContainer.html(escapeHtml(parseInt($discountRate)) + "%");
                            }
                            else{
                                $imageContainer.html('<div class="pin-discount">'+escapeHtml(parseInt($discountRate))+'%</div>');
                            }
                            if($originalAmountContainer.length > 0){
                                $originalAmountContainer.html(escapeHtml("P"+replaceNumberWithCommas(parseFloat(removeComma($basePrice)).toFixed(2))));
                            }
                            else{
                                $amountContainer.prepend('<span class="item-original-amount">P'+replaceNumberWithCommas(parseFloat(removeComma($basePrice)).toFixed(2))+'</span>');
                            }
                            $amountContainer.find('.item-current-amount').html("P"+replaceNumberWithCommas($discountPrice.toFixed(2)));
                        }
                        else{
                            $amountContainer.find('.item-current-amount').html("P"+replaceNumberWithCommas($basePrice));
                            $originalAmountContainer.remove();
                            $discountContainer.remove();
                        }
                        $this.closest('.ui-dialog-content').dialog('close'); 
                        $slug = null;
                        $productId = null; 
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
        $productId = $this.data('pid');

        $(".loading-image").show();
        $(".ui-dialog > #express-edit-section .express-edit-section-content").html("");
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
                         $(".loading-image").hide();
                        $(".ui-dialog > #express-edit-section .express-edit-section-content").html($response); 
                        $(".error-message").hide(); 
                        $(".base-price").trigger("change");
                        fluidDialog();
                    }
                });
            }
        });
    });

    $('.open-express-edit').click(function() {
        $('#express-edit-section').dialog({
            autoOpen: true,
            dialogClass: 'express-edit-wrapper',
            width: 1180,
            modal: true,
            fluid: true,
            draggable: false,
        });
    });

    $("#express-edit-section").dialog({
        autoOpen: false,
        open: function(){
            $('.ui-widget-overlay').bind('click',function(){
                $('#express-edit-section').dialog('close');
            })
        }
    });

    // on window resize run function
    $(window).resize(function () {
        fluidDialog();
    });

    // catch dialog if opened within a viewport smaller than the dialog width
    $(document).on("dialogopen", ".ui-dialog", function (event, ui) {
        fluidDialog();
    });

    function fluidDialog() {
        var $visible = $(".ui-dialog:visible");
        // each open dialog
        $visible.each(function () {
            var $this = $(this);
            var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
            // if fluid option == true
            if (dialog.options.fluid) {
                var wWidth = $(window).width();
                // check window width against dialog width
                if (wWidth < (parseInt(dialog.options.maxWidth) + 50))  {
                    // keep dialog from filling entire screen
                    $this.css("max-width", "90%");
                } else {
                    // fix maxWidth bug
                    $this.css("max-width", dialog.options.maxWidth + "px");
                }
                //reposition dialog
                dialog.option("position", dialog.options.position);
            }
        });
    };

})(jQuery);
