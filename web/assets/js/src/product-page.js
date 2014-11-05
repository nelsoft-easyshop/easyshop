

    function sortArrayNumber(a,b) {
        return a - b;
    }

    function arraysEqual(a, b) {
        if (a === b) return true;
        if (a == null || b == null) return false;
        if (a.length != b.length) return false;
     
        for (var i = 0; i < a.length; ++i) {
            if (a[i] !== b[i]) return false;
        }
        return true;
    }

    function commaSeparateNumber(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        return val;
    }

    // function for reply and review
    function submitReviewAndReply($parent,$review,$title,$rating,$type)
    {
        // token
        var $csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        // product id
        var $productId = $("#productId").val();
        var $currentReviewCount = $("#review-count").val();
        

        $url = "/product/submit-reply";
        if($type == "review"){
            $url = "/product/submit-review";
            $currentReviewCount = parseInt($currentReviewCount) + 1;
        }

        $.ajax({
            url: $url,
            type:"POST",
            dataType:"JSON",
            data:{product_id:$productId,parent_review:$parent,review:$review,rating:$rating,title:$title,csrfname:$csrftoken},
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
        // check possible combination
        $.each($productCombQuantity, function(i, val) {
            $arrayCombination = val.product_attribute_ids;
            $arrayCombination.sort(sortArrayNumber);
            $booleanCheck = arraysEqual($arrayCombination,$arraySelected);
            $combinationQuantity = val.quantity;
            $combinationLocation = val.location;
            $("#control-quantity").empty();
            $('#shipment_locations > option').show().prop('disabled', true);
            $('#shipment_locations > .default').prop('disabled', false);

            // if found atleast one combination
            if($booleanCheck){

                if($combinationQuantity <= 0){
                    $("#control-quantity").append('<option value="0">0</option>');
                    $('.prod-add-to-cart-btn').removeClass("enabled").addClass("disabled");
                }
                else{
                    for (var i = 1 ; i <= $combinationQuantity; i++) { 
                        $("#control-quantity").append('<option value="'+i+'">'+ i +'</option>');
                    };
                    $('.prod-add-to-cart-btn').removeClass("disabled").addClass("enabled");
                }
                if($("#totallyFreeShipping").val() != ""){
                    $("#control-quantity").append('<option value="0">FREE SHIPPING NATIONWIDE</option>');
                }
                else{
                    $.each($combinationLocation,function(i, val){
                        var $text = $("#locationID_"+val.location_id).data('text');
                        $("#locationID_"+val.location_id).prop('disabled', false).empty().append($text+' -'+val.price); 
                    });
                }

                $('#shipment_locations > option:disabled').hide();
                
                return false;
            }
            $("#control-quantity").append('<option value="0">0</option>');
        });
    }

    (function($) {

        // hiden values variables
        $productCombQuantity = JSON.parse($("#productCombQuantity").val());

        console.log($productCombQuantity);

        if($("#noMoreSelection").val() != ""){
            var $arraySelected = [];
            $arraySelected.push("0");
            console.log($arraySelected);
            console.log($productCombQuantity);
            checkCombination($arraySelected);
        }

        // js code for base price line through
        var $productprice= $('.prod-price-container').find('.base-price');
        if( $productprice.hasClass('base-price') ){
            $('.base-price').addClass('line-through');
        }

        $(".attribute-control").bind('change',function(e){
            var $this = $(this);
            var $arraySelected = [];
            var $baseFinalPrice = parseFloat($("#finalBasePrice").val());
            var $imageid = $this.children('option:selected').data('imageid');

            if($imageid > 0){
                $("#image"+$imageid).trigger('click'); 
            }

            // get selected attributes
            $(".attribute-control").each(function() {
                $thisSelect = $(this);
                var $selectValue = $thisSelect.val();
                var $additionalPrice = parseFloat($thisSelect.children('option:selected').data('addprice'));
                $baseFinalPrice += $additionalPrice;
                $arraySelected.push($selectValue);
            });

            // update price 
            $(".discounted-price").html("P"+commaSeparateNumber($baseFinalPrice.toFixed(2)));

            // sort array
            $arraySelected.sort(sortArrayNumber);
            checkCombination($arraySelected);
        });
        
        // add to cart
        $(document).on('click', '#send.enabled', function(){

            // token
            var $csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
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
         
            var $request = $.ajax({
                                url: "/cart/doAddItem",
                                type:"POST",
                                dataType:"JSON",
                                data:{productId:$productId,quantity:$quantity,options:$optionsObject,csrfname:$csrftoken},
                                success:function(data){

                                    if(!data.isLoggedIn){
                                        window.location.replace("/login");
                                    }
                                    
                                    if(data.isSuccessful){
                                        window.location.replace("/cart");
                                    }
                                    else{
                                        alert("We cannot process your request at this time. Please try again in a few moment");
                                    }
                                }
                            });

        });

        // review product
        $(document).on('click', '#submitReview', function(){
            var $this = $(this);
            var $title = $("#review-title").val();
            var $review = $("#review-comment").val();
            var $rate = $("#star-rate").val(); 
            if($review.trim() == "" || $review.length <= 0 || $title.trim() == "" || $title.length <= 0){
                return false;
            }
            else{
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
                return false;
            }
            else{
                $this.prop('disabled', true); 
                submitReviewAndReply($parent,$review,"",0,"reply");
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

    })(jQuery);

