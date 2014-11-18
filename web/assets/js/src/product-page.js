
(function($) {

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
                    $(".availability-status").html("Out of Stock").removeClass("in-stock").addClass("out-of-stock");
                }
                else{
                    for (var i = 1 ; i <= $combinationQuantity; i++) { 
                        $("#control-quantity").append('<option value="'+i+'">'+ i +'</option>');
                    };
                    $('.prod-add-to-cart-btn').removeClass("disabled").addClass("enabled");
                    $(".availability-status").html("In Stock").removeClass("out-of-stock").addClass("in-stock");
                }
                if($("#isFreeShippingNationwide").val() == ""){
                    $.each($combinationLocation,function(i, val){
                        var $text = $("#locationID_"+val.location_id).data('text');
                        $("#locationID_"+val.location_id).prop('disabled', false).empty().append($text+' -'+val.price); 
                    });
                }

                $('#shipment_locations > option:disabled').hide();
                
                return false;
            }

            $("#shipment_locations > option").each(function() { 
                $(this).empty().append($(this).data('text'));
            });

            $('.prod-add-to-cart-btn').removeClass("enabled").addClass("disabled");
            $("#control-quantity").append('<option value="0">0</option>');
            $(".availability-status").html("Out of Stock").removeClass("in-stock").addClass("out-of-stock");
        });
    }


    // hiden values variables
    $productCombQuantity = JSON.parse($("#productCombQuantity").val());
    $("#error-review-title,#error-review-nessage,.error-label-textarea").hide();

    if($("#noMoreSelection").val() != ""){
        var $arraySelected = [];
        $arraySelected.push("0"); 
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


        $(".attribute-control").each(function() {
            if($(this).val() == 0){
                $(".availability-status").html("Select Combination").removeClass("in-stock").removeClass("out-of-stock");
                return false;
            }
        });
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
         
        $.ajax({
            type: "POST",
            url: "/cart/doAddItem", 
            dataType: "json",
            data: "express=true&"+csrfname+"="+csrftoken+"&productId="+productId,
            success: function(result) {
                if(!result.isLoggedIn){
                    window.location.replace("/login");
                }
                else if(result.isSuccessful){
                    window.location.replace("/cart");
                }
                else{
                    window.location.replace("/item/"+slug);
                }
            }, 
        });
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
        $("html, body").delay(200).animate({
            scrollTop: $('#scrollpoint').offset().top 
        }, 200);

        $(".sticky-header-nav").delay(1000).addClass("product-disable-nav", 1000);
    };

    $(window).on('load', stickyMenualt);

    function stickyMenualt2() {
        $(".sticky-header-nav").removeClass("product-disable-nav");

    };

    $(document).on('scroll', stickyMenualt2);

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=711804058875446&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];
        if(d.getElementById(id)){return}js=d.createElement(s);
        js.id=id;js.src="https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));

})(jQuery);

