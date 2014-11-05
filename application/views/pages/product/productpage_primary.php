<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/product-page-css.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" >
<link rel="stylesheet" href="/assets/css/owl.carousel.css">
<section class="breadcrumbs-bg">
    <div class="container">
        <div class="default-breadcrumbs-container col-md-12 col-sm-12 col-xs-12">
            <ul>
                <li><a href="/">Home</a></li> 
                <?php foreach ($breadCrumbs as $crumbs): ?>
                <li class="bc-arrow"> 
                    <a href="/category/<?php echo $crumbs['slug']?>">
                        <?php echo html_escape($crumbs['name']);?>
                    </a>
                </li> 
                <?php endforeach; ?>
                <li class="bc-arrow"><?=html_escape($product->getName());?></li>
            </ul>
        </div>
    </div>
</section>

<section class="product-main-top-content">
    <div class="container">
        <div class="row">
            <div class="product-name-seller col-md-12">
                <h1 id="<?=$product->getIdProduct();?>"> 
                    <span id="pname"> <?=html_escape($product->getName())?> </span>
                </h1>
                <div>
                    By:
                        <span class="product-profile-photo"><img src="<?=$ownerAvatar?>"></span>
                        <?=html_escape($product->getMember()->getStoreName());?>
                </div>
            </div>
        </div>
        <div class="prod-border-bttm"></div>
        
       
    </div>
    
</section>

<section class="product-main-mid-content">
 <?php
            include("promo_banners.php");
        ?>
    <div class="container">
        <div class="row pd-top-40">
                
            <div class="col-md-6">
                <?=$imagesView?>
            </div>
            
            <div class="col-md-6">
                <div class="prod-price-container">
                    <span class="discounted-price" data-baseprice="<?=number_format($product->getOriginalPrice(),2,'.',','); ?>"> 
                        P <?=number_format($product->getFinalPrice(),2,'.',',');?> 
                    </span>

                <?php if(floatval($product->getDiscountPercentage()) > 0):?>
                    <span class="base-price"> P <?=number_format($product->getOriginalPrice(),2,'.',','); ?></span> 
                </div>
                <div class="prod-dc-container text-right">
                    <span class="prod-dc-badge"> -<?=number_format($product->getDiscountPercentage(),0,'.',',');?>%</span>
                <?PHP endif;?>

                </div>
                <div class="clear"></div>
                <div class="col-md-12 prod-border-bttm"></div>

                <div class="clear"></div>
                <?php if(count($productAttributes) > 0): ?>
                <div class="row pad-top-23">
                    
                    <div class="col-md-12"><p class="attr-title">Other Attributes</p></div>
                    <!-- Product attributes here -->
                    <?php foreach ($productAttributes as $head => $headValue): ?>
                        <div class="col-sm-12 col-md-6 attr-select">
                            <div class="prod-select-con ui-form-control">
                                <select class="attribute-control">
                                    <option value="0" data-addprice="0" selected=selected>--<?=ucfirst($head);?>--</option>
                                    <?php foreach ($headValue as $key => $value):?>
                                        <option value="<?=$value['attr_id']; ?>" data-headvalue="<?=strtolower($head)?>" data-textvalue="<?=strtolower($value['attr_value']); ?>" data-imageid=<?=$value['image_id']; ?> data-addprice="<?=$value['attr_price']?>"><?=$value['attr_value']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?> 
                    <!-- end of Product attributes -->
                   
                    <div class="clear"></div>
                </div>
                <div class="col-md-12 prod-border-bttm pad-top-23"></div>
                <div class="clear"></div>
                <?php endif; ?>

                <div class="row pad-top-23">
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="prod-availability-container prod-border-right">
                            <p>Availability: <span class="in-stock"><?=(intval($product->getIsSoldOut()) === 0)?'In Stock':'Out Of Stock';?></span></p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-7">
                        <p class="attr-title txt-shipment">Shipment Fee:</p>
                        <div class="prod-select-con ui-form-control shipment-select">
                            <select class="shiploc" id="shipment_locations">
                                <option class="default" selected="" value="0">Select Location</option>
                                <?php foreach($shiploc['area'] as $island=>$loc):?>
                                    <option data-price="0" data-text="<?=$island;?>" data-type="1" id="<?='locationID_'.$shiploc['islandkey'][$island];?>" value="<?=$shiploc['islandkey'][$island];?>" disabled><?=$island;?></option>
                                    <?php foreach($loc as $region=>$subloc):?>
                                        <option data-price="0" data-text="<?=$region;?>" data-type="2" id="<?='locationID_'.$shiploc['regionkey'][$region];?>" value="<?=$shiploc['regionkey'][$region];?>" style="margin-left:15px;" disabled>&nbsp;&nbsp;&nbsp;<?=$region;?></option>
                                        <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                            <option data-price="0" data-text="<?=$cityprov;?>" data-type="3" id="<?='locationID_'.$id_cityprov;?>" value="<?=$id_cityprov;?>" style="margin-left:30px;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$cityprov;?></option>
                                        <?php endforeach;?>
                                    <?php endforeach;?>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="shipping_fee"></div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="col-md-12 prod-border-bttm pad-top-23"></div>
                <div class="clear"></div>

                <div class="row pad-top-23">
                    <div class="col-sm-12 col-md-5 prod-quantity-container">
                        <p class="attr-title">Quantity:</p>
                        <div class="prod-select-con ui-form-control quantity-select">
                            <select id="control-quantity">
                                <option value="0" selected=selected>0</option> 
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <?php if($isLoggedIn && intval($userData['is_email_verify']) !== 1): ?>
                            <p class="buy_btn_sub"> Verify your email </p>
                        <?php elseif($isLoggedIn && $viewerId == $product->getMember()->getIdMember()): ?>
                            <p class="buy_btn_sub"> This is your own listing </p>
                        <?php else: ?>
                            <?php if(count($shippingInfo) === 0 && intval($product->getIsMeetup()) === 1): ?>
                                <a href="javascript:void(0)" class="btn-meet-up modal_msg_launcher font-14" title="Send <?=html_escape($product->getMember()->getUsername())?> a message" >Contact Seller</a> <br/>
                                <span class="font-10" width="100%">Item is listed as an ad only. *</span>
                            <?php elseif($product->getPromoType() == 6 && $product->getStartPromo() == 1): ?>
                                <a href="javascript:void(0)" id='<?=$canPurchase?'send':'' ?>_registration' class="fm1 orange_btn3 disabled font-14">Buy Now</a> <br/>
                                <span class="font-10" width="100%">Click buy to qualify for the promo*</span>
                            <?php elseif(!$isBuyButtonViewable && intval($product->getStartPromo()) === 1) : ?>
                                <p class="buy_btn_sub"> This product is for promo use only. </p>
                            <?php else: ?>
                                <input type="button" id="<?=$canPurchase?'send':'' ?>" value="Add to Cart" class="prod-add-to-cart-btn disabled">
                                <span class="font-10" width="100%">Delivers upon seller confirmation*</span>
                            <?php endif; ?>
                        <?php endif;?>
                    </div>
                </div>
                <div class="clear"></div>

                <div class="col-md-12 prod-border-bttm pad-top-23"></div>

                <div class="clear"></div>

                <div class="row pad-top-23">
                    <div class="col-md-12 prod-payment-img-container">
                        <p class="attr-title">Payment:</p>

                        <?php if(isset($paymentMethod['cdb'])): ?>
                            <img src="/assets/images/img-mastercard-black.png" alt="Mastercard">
                            <img src="/assets/images/img-visa-black.png" alt="Visa">
                        <?php endif; ?> 

                        <?php if(isset($paymentMethod['dragonpay'])) : ?>
                            <img src="/assets/images/img-dragonpay-black.png" alt="Dragon Pay">
                        <?php endif; ?> 

                        <?php if(isset($paymentMethod['paypal'])) : ?>
                            <img src="/assets/images/img-paypal-black.png" alt="Paypal">
                        <?php endif; ?>

                        <?php if(isset($paymentMethod['cod']) && intval($product->getIsCod(),10) === 1): ?>
                            <img src="/assets/images/img-cod-black.png" alt="Cash on Delivery">
                        <?php endif; ?>

                    </div>
                </div>
                <div class="clear"></div>

                <div class="col-md-12 prod-border-bttm pad-top-23"></div>

                <div class="clear"></div>

                <div class="row pad-top-23">
                    <div class="col-md-12 prod-share-container">
                        <p class="attr-title">Share with:</p>
                        <a href="">
                            <div class="ui-form-control share-facebook">
                                <img src="/assets/images/img-facebook-prod.png" alt="Facebook"> 
                                <span>Facebook</span>
                            </div>
                        </a>
                        <a href="">
                            <div class="ui-form-control share-twitter">
                                <img src="/assets/images/img-twitter-prod.png" alt="Twitter"> 
                                <span>Twitter</span>
                            </div>
                        </a>
                        <a href="">
                            <div class="ui-form-control share-googleplus">
                                <img src="/assets/images/img-googleplus-prod.png" alt="Google+"> 
                                <span>Google+</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="hidden-values">
    <input id="productCombQuantity" type="hidden" value='<?=$productCombinationQuantity; ?>' />
    <input id="finalBasePrice" type="hidden" value="<?=$product->getFinalPrice();?>" />
    <input id='p_shipment' type='hidden' value='<?=json_encode($shippingInfo);?>'>
    <input id='productId' type='hidden' value='<?=$product->getIdProduct();?>'>
    <input id='review-count' type='hidden' value='<?=count($productReview);?>'>
    <input id="noMoreSelection" type="hidden" value="<?=$noMoreSelection;?>">
</div>

<!-- display view for product details and review -->
<?=$reviewDetailsView;?>

<!-- display recommended products view -->
<?=$recommendedView;?>

<script type="text/javascript" src="/assets/js/src/vendor/jquery.jqzoom-core.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.bxslider1.min.js"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script> 
<script type="text/javascript" src="/assets/js/src/vendor/owl.carousel.min.js"></script>

<script type="text/javascript">

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

                $.each($combinationLocation,function(i, val){ 
                    var $text = $("#locationID_"+val.location_id).data('text');
                    $("#locationID_"+val.location_id).prop('disabled', false).empty().append($text+' -'+val.price); 
                });

                $('#shipment_locations > option:disabled').hide();
                
                return false;
            }
            $("#control-quantity").append('<option value="0">0</option>');
        });
    }

    (function($) {

        // hiden values variables
        $productCombQuantity = JSON.parse($("#productCombQuantity").val());

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
            $('.discounted-price').addClass('line-through');
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
            $(".discounted-price").html("P "+commaSeparateNumber($baseFinalPrice.toFixed(2)));

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



    })(jQuery);
</script>
