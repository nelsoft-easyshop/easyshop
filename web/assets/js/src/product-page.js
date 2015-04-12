
(function($) {
    
    stickyMenualt();
    
    /**
     * Function call by .sort of the jquery
     * @param  {integer} a
     * @param  {integer} b 
     * @return {integer}
     */
    function sortArrayNumber(a, b) {
        return a - b;
    }

    /**
     * Check if the both given arrays are equal
     * @param  {array} a
     * @param  {array} b
     * @return {boolean}
     */
    function arraysEqual(a, b) {
        if (a === b){
            return true;
        }
        else if (a == null || b == null){
            return false;
        }
        else if (a.length != b.length){
            return false;
        }
        for (var i = 0; i < a.length; ++i) {
            if (a[i] !== b[i]) return false;
        }

        return true;
    }

    /**
     * Seperate the number by comma after 3 digits
     * @param  {string} val
     * @return {string} val
     */
    function commaSeparateNumber(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        return val;
    }

    /**
     * function for reply and review
     * @param  {integer} $parent
     * @param  {string}  $review
     * @param  {string}  $title
     * @param  {integer} $rating
     * @param  {string}  $type
     */
    function submitReviewAndReply($parent, $review, $title, $rating, $type)
    {
        // token
        var $csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        // product id
        var $productId = $("#productId").val();
        var $currentReviewCount = $("#review-count").val();

        $url = $("#submitReplyUrl").val();
        if($type == "review"){
            $url = $("#submitReviewUrl").val();
            $currentReviewCount = parseInt($currentReviewCount) + 1;
        }

        $.ajax({
            url: $url,
            type:"POST",
            dataType:"JSON",
            data: {
                    product_id:$productId,
                    parent_review:$parent,
                    review:$review,
                    rating:$rating,
                    title:$title,
                    csrfname:$csrftoken
                },
            success:function(data){
                if(data.isSuccess){
                    if($type == "reply"){
                        $(".review-container-"+$parent).append(data.html);
                        $(".review-container-"+$parent).prev().prev('.div-reply-container').toggle("slow");
                        $(".review-container-"+$parent).prev().prev('.div-reply-container').prev().children('.p-reply-text').find('.text-cancel').toggle("fade");
                        $("#textareaReview"+$parent).val("");
                    }
                    else{
                        $("#main-review-container").prepend(data.html);
                        $("#star-rate").val(0);
                        $("#review-title").val("");
                        $("#review-comment").val("");
                        $(".span-star-container .fa-star-rate").css("color","#d4d4d4");
                        $("#no-review-div").remove();
                        $(".span-review-count").html($currentReviewCount);
                    }
                }
                else{
                    alert(data.error);
                }
                $('.btn-reply').prop('disabled', false);
            }
        });
    }

    function checkCombination($arraySelected)
    {            
        var $addToCartButton = $('.prod-add-to-cart-btn');
        var $appendString = "";
        // check possible combination
        $.each($productCombQuantity, function(i, val) {
            $arrayCombination = val.product_attribute_ids;
            $arrayCombination.sort(sortArrayNumber);
            $booleanCheck = arraysEqual($arrayCombination,$arraySelected);
            $combinationQuantity = val.quantity;
            $combinationLocation = val.location;
            $("#control-quantity").empty();
            $("#shipment_locations").html($("#shipment_locations_copy").html()); 
            $('#shipment_locations > option').prop('disabled', true);
            $('#shipment_locations > .default').prop('disabled', false);

            // if found atleast one combination
            if($booleanCheck){

                if($combinationQuantity <= 0){
                    $("#control-quantity").append('<option value="0">0</option>');
                    $addToCartButton.removeClass("enabled").addClass("disabled");
                    $("#shipment_locations").val($("#shipment_locations option:first").val());
                    $(".availability-status").html("Out of Stock").removeClass("in-stock").addClass("out-of-stock");
                }
                else{
                    if($combinationQuantity >= 9999){
                        $combinationQuantity = 9999
                    }
                    for (var i = 1 ; i <= $combinationQuantity; i++) { 
                        $appendString += '<option value="'+i+'">'+ i +'</option>';
                    };
                    $("#control-quantity").append($appendString);
                    $addToCartButton.removeClass("disabled").addClass("enabled");
                    $(".availability-status").html("In Stock").removeClass("out-of-stock").addClass("in-stock");
                }
                if($("#isFreeShippingNationwide").val() == ""){
                    $.each($combinationLocation,function(i, val){
                        var $text = $("#locationID_"+val.location_id).data('text');
                        $("#locationID_"+val.location_id).prop('disabled', false).empty().append($text+' - PHP '+ commaSeparateNumber(parseFloat(val.price).toFixed(2))); 
                    });
                }

                $('#shipment_locations > option:disabled').remove();
                
                return false;
            }

            $("#shipment_locations").val($("#shipment_locations option:first").val());
            $addToCartButton.removeClass("enabled").addClass("disabled");
            $("#control-quantity").append('<option value="0">0</option>');
            $(".availability-status").html("Out of Stock").removeClass("in-stock").addClass("out-of-stock");
        });
    }
    
    /**
     * Same function in php in_array
     * @param  valie  value
     * @param  array  array
     * @return boolean
     */
    function isInArray(value, array) {
      return array.indexOf(value) > -1;
    }

    /**
     * Same function in php array_unique
     * @param  array
     * @return array
     */
    function arrayUnique(array){
        var $uniqueNames = [];
        $.each(array, function(i, el){
            if($.inArray(el, $uniqueNames) === -1){
                $uniqueNames.push(el)
            }
        });

        return $uniqueNames;
    }

    function removeNoCombination()
    {
        var $selectedValues = [];
        var $varTempValues = [];
        var $firstValue = 0;

        $(".attribute-control").each(function() {
            var $thisSelect = $(this);
            var $selectValue = $thisSelect.val();
            if($selectValue > 0){
                $selectedValues.push($selectValue);
                $firstValue = $selectValue;
            }
        });
        var $selectedCount = $selectedValues.length;

        $uniqueSelected = disabledSelection($selectedValues);

        if($selectedCount == 1){
            $('.attribute-control > option[value="' + $firstValue + '"]').parent().children("option").prop("disabled",false);
        }

        return $uniqueSelected;
    }

    function disabledSelection($selectedValues)
    { 
        // var $varTempValues = [];
        // var $selectedCount = $selectedValues.length;

        // $.each($productCombQuantity, function(i, val) {
        //     $successCount = 0;
        //     $.each($selectedValues, function(j, selVal) {
        //         if(isInArray(selVal,val.product_attribute_ids) && val.quantity > 0){
        //             $successCount ++;
        //         }
        //     });
        //     if($successCount == $selectedCount){
        //        $.each(val.product_attribute_ids, function(k, idVal) {
        //             $varTempValues.push(idVal); 
        //         });
        //     }
        // });

        // $selectedValues = $selectedValues.concat($varTempValues); 

        // $(".attribute-control > option").prop("disabled",false);
        // var $uniqueSelected = arrayUnique($selectedValues);
        // if($uniqueSelected != undefined && $uniqueSelected != null && $uniqueSelected.length != 0){
        //     $(".attribute-control > option").prop("disabled",true);
        //     $.each($uniqueSelected, function(i, val) {
        //         $('.attribute-control > option[value="0"]').prop("disabled",false);
        //         $('.attribute-control > option[value="' + val + '"]').prop("disabled",false);
        //     });
        // }

        // return $uniqueSelected;
    }

    var $attrSelectCounter = 0;
    var $attrHideCounter = 0;
    $(".attr-select").each(function(){
        var $this = $(this);
        if($this.hasClass('element-hide')){
            $attrHideCounter++;
        }
        $attrSelectCounter++;
    });

    if($attrSelectCounter === $attrHideCounter){
        $("#attribute-container").hide();
    }

    // hiden values variables
    $productCombQuantity = JSON.parse($("#productCombQuantity").val());

    removeNoCombination();
    $("#error-review-title,#error-review-nessage,.error-label-textarea").hide(); 
    $("#shipment_locations_copy").html($("#shipment_locations").html()).hide();
    if($("#noMoreSelection").val() != ""){
        if($("#needToSelect").val() == ""){
            var $arraySelected = [];

            $.each($productCombQuantity, function(i, val) {
                $arraySelected = val.product_attribute_ids;
            });

            checkCombination($arraySelected);
        }
    }

    // js code for base price line through
    var $productprice= $('.prod-price-container').find('.base-price');
    if( $productprice.hasClass('base-price') ){
        $('.base-price').addClass('line-through');
    }

    $(".attribute-control").bind('click',function(e){
        if($("#noMoreSelection").val() != ""){
            $(".attribute-control > option").prop("disabled",false);
        }
        else{
            var $this = $(this);
            var $arraySelectedNoZero = [];

            $(".attribute-control").each(function() { 
                var $thisSelect = $(this);
                var $selectValue = $thisSelect.val();
                if($selectValue > 0){
                    $arraySelectedNoZero.push($selectValue);
                }
            });
     
            var $indexSelected = $arraySelectedNoZero.indexOf($this.val());
            if ($indexSelected > -1) {
                $arraySelectedNoZero.splice($indexSelected, 1);
            }
            disabledSelection($arraySelectedNoZero);
        }
    });

    $(".attribute-control").bind('change',function(e){
        var $this = $(this);
        var $imageid = $this.children('option:selected').data('imageid');
        var $baseFinalPrice = parseFloat($("#finalBasePrice").val());
        var $originalBasePrice = parseFloat($("#originalBasePrice").val()); 
        var $arraySelected = [];
        var $indexImage;
        var $owl;
        if($("#noMoreSelection").val() != ""){ 
            $.each($productCombQuantity, function(i, val) {
                $arraySelected = val.product_attribute_ids;
            });

            $(".attribute-control").each(function() {
                $thisSelect = $(this);
                var $selectValue = $thisSelect.val();
                var $additionalPrice = parseFloat($thisSelect.children('option:selected').data('addprice'));
                $baseFinalPrice += $additionalPrice;
                $originalBasePrice += $additionalPrice;
            });

            checkCombination($arraySelected);
        }
        else{
            // get selected attributes
            $(".attribute-control").each(function() {
                $thisSelect = $(this);
                var $selectValue = $thisSelect.val();
                var $additionalPrice = parseFloat($thisSelect.children('option:selected').data('addprice'));
                $baseFinalPrice += $additionalPrice;
                $originalBasePrice += $additionalPrice;
                $arraySelected.push($selectValue);
            });

            // sort array
            $arraySelected.sort(sortArrayNumber);
            checkCombination($arraySelected);

        }
        $(".discounted-price").html("P"+commaSeparateNumber($baseFinalPrice.toFixed(2)));
        $(".base-price").html("P"+commaSeparateNumber($originalBasePrice.toFixed(2)));

        if($imageid > 0){
            $owl = $("#mobile-product-gallery").data('owlCarousel');
            $indexImage = $('.mobile-image-list').index($('.owl-image'+$imageid));
            $owl.jumpTo($indexImage)
            $("#image"+$imageid).trigger('click'); 
        }
        $(".attribute-control").each(function() {
            if($(this).val() == 0){
                $("#shipment_locations").val($("#shipment_locations option:first").val());
                $(".availability-status").html("Select Combination").removeClass("in-stock").removeClass("out-of-stock");
                $("#control-quantity").html('<option value="0">0</option>');
                $('.prod-add-to-cart-btn').removeClass("enabled").addClass("disabled");
                return false;
            }
        });
    });
    $(".attribute-control").trigger("change");

    // add to cart
    $(document).on('click', '#send.enabled', function(){
        var $button = $(this);
        if(!$button.data('canpurchase')){
            alert('Sorry, this item is currently not available for purchase.');
            return false;
        }

        var $productId = $("#productId").val();
        var $quantity = $("#control-quantity").val();
        var $optionsObject = {};
 
        $(".attribute-control").each(function() {
            $thisSelect = $(this); 
            var $attrParent = $thisSelect.children('option:selected').data('headvalue'); 
            var $attrName = $thisSelect.children('option:selected').data('textvalue');
            var $additionalPrice = parseFloat($thisSelect.children('option:selected').data('addprice'));
            $optionsObject[$attrParent] = $attrName + '~' + $additionalPrice.toFixed(2); 
        });
        var productSlug = $('#product-slug').val();
       
        addToCart($productId, $quantity, $optionsObject, false, productSlug, true);
    });

    /**
     * Add to cart using the express button 
     * 
     */
    $(".btn-add-cart").on("click", function(){
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var $button = $(this);
        var productId = $button.data('productid');
        var slug = $button.data('slug');
         
        addToCart(productId, null, null, true, slug);
    });
    
    // review product
    $(document).on('click', '#submitReview', function(){
        var $this = $(this);
        var $title = $("#review-title").val();
        var $review = $("#review-comment").val();
        var $rate = $("#star-rate").val(); 
        if($review.trim() == "" || $review.length <= 0 || $title.trim() == "" || $title.length <= 0){

            if($title.trim() == "" || $title.length <= 0){
                $("#error-review-title").show();
                $("#review-title").addClass("error-field-review");
            }
            else{ 
                $("#error-review-title").hide();
                $("#review-title").removeClass("error-field-review");
            }

            if($review.trim() == "" || $review.length <= 0){
                $("#error-review-nessage").show();
                $("#review-comment").addClass("error-field-review");
            }
            else{
                $("#error-review-nessage").hide();
                $("#review-comment").removeClass("error-field-review");
            }

            return false;
        }
        else{
            $("#error-review-title").hide();
            $("#review-title").removeClass("error-field-review");
            $("#error-review-nessage").hide();
            $("#review-comment").removeClass("error-field-review");

            $this.prop('disabled', true);
            submitReviewAndReply(0,$review,$title,$rate,"review");
        }
    });

    $(document).on('click', '.js-rate', function(){
        var $rate = $(this).data('count');
        $("#star-rate").val($rate);

    });

    // review product
    $(document).on('click', '.btn-js-reply', function(){
        var $this = $(this);
        var $parent = $this.data('parent'); 
        var $productId = $("#productId").val(); 
        var $review = $("#textareaReview"+$parent).val(); 

        if($review.trim() == "" || $review.length <= 0){
            $(".error-"+$parent).show();
            $("#textareaReview"+$parent).addClass("error-field-review");
            return false;
        }
        else{
            $this.prop('disabled', true); 
            submitReviewAndReply($parent,$review,"",0,"reply");
            $(".error-"+$parent).hide();
            $("#textareaReview"+$parent).removeClass("error-field-review");
        }
    });

    $(document).on('click', '.p-reply-text', function(){ 
        $(this).parent().next( ".div-reply-container" ).toggle("slow");
        $(this).find( ".text-cancel" ).toggle("fade");
    });

    $( "#prodDetails" ).click(function() {
        $( "#tdDetails" ).addClass("active");
        $( "#tdReviews" ).removeClass("active");
    });

    $( "#prodReviews" ).click(function() {
        $( "#tdDetails" ).removeClass("active");
        $( "#tdReviews" ).addClass("active");
    });

    $( ".span-star-container .fa-star-rate" ).click(function() {
        $(this).css("color","#fbd022");
        $(this).prevAll().css("color","#fbd022");
        $(this).nextAll().css("color","#d4d4d4");
    });

    $(document).ready(function() {
        var recommended = $("#recommended");
        recommended.owlCarousel({
            items : 4,
            itemsCustom : false,
            itemsDesktop : [1199,4],
            itemsDesktopSmall : [980,3],
            itemsTablet: [768,2],
            itemsTabletSmall: false,
            itemsMobile : [479,2],
            pagination: false,
            singleItem : false,
            itemsScaleUp : false
        });
        $(".next").click(function(){
            recommended.trigger('owl.next');
        })
        $(".prev").click(function(){
            recommended.trigger('owl.prev');
        })
    });

    function stickyMenualt() {
        $("html, body").delay(500).animate({
            scrollTop: $('#scrollpoint').offset().top 
        }, 500);

        $(".sticky-header-nav").delay(2000).addClass("product-disable-nav", 2000);
    };


    $(document).on('scroll', stickyMenualt2);
    
    function stickyMenualt2() {
        $(".sticky-header-nav").removeClass("product-disable-nav");

    };

   

    if ($(".shipment-period").length) {
        $(".shipment-fee-container").css("margin", "0");
    }
    else {
        $(".shipment-fee-container").css("margin", "auto");
    };

})(jQuery);

