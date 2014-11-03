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

                <div class="row pad-top-23">
                    <div class="col-md-12"><p class="attr-title">Other Attributes</p></div>

                    <!-- Product attributes here -->
                    <?php foreach ($productAttributes as $head => $headValue): ?>
                        <div class="col-sm-12 col-md-6 attr-select">
                            <div class="prod-select-con ui-form-control">
                                <select class="attribute-control">
                                    <option value="0" data-addprice="0" selected=selected>--<?=ucfirst($head);?>--</option>
                                    <?php foreach ($headValue as $key => $value):?>
                                        <option value="<?=$value['attr_id']; ?>" data-addprice="<?=$value['attr_price']?>"><?=$value['attr_value']; ?></option>
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

                <div class="row pad-top-23">
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="prod-availability-container prod-border-right">
                            <p>Availability: <span class="in-stock">In Stock</span></p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-7">
                        <p class="attr-title txt-shipment">Shipment Fee:</p>
                        <div class="prod-select-con ui-form-control shipment-select">
                            <select class="shiploc" id="shipment_locations">
                                <option class="default" selected="" value="0">Select Location</option>
                                <?php foreach($shiploc['area'] as $island=>$loc):?>
                                    <option data-price="0" data-type="1" id="<?php echo 'locationID_'.$shiploc['islandkey'][$island];?>" value="<?php echo $shiploc['islandkey'][$island];?>" disabled><?php echo $island;?></option>
                                    <?php foreach($loc as $region=>$subloc):?>
                                        <option data-price="0" data-type="2" id="<?php echo 'locationID_'.$shiploc['regionkey'][$region];?>" value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" disabled>&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                                        <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                            <option data-price="0" data-type="3" id="<?php echo 'locationID_'.$id_cityprov;?>" value="<?php echo $id_cityprov;?>" style="margin-left:30px;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
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
                                <option selected=selected>0</option> 
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <input type="submit" value="Add to Cart" class="prod-add-to-cart-btn">
                    </div>
                </div>
                <div class="clear"></div>

                <div class="col-md-12 prod-border-bttm pad-top-23"></div>

                <div class="clear"></div>

                <div class="row pad-top-23">
                    <div class="col-md-12 prod-payment-img-container">
                        <p class="attr-title">Payment:</p>
                        <img src="/assets/images/img-visa-black.png" alt="Visa">
                        <img src="/assets/images/img-paypal-black.png" alt="Paypal">
                        <img src="/assets/images/img-mastercard-black.png" alt="Mastercard">
                        <img src="/assets/images/img-dragonpay-black.png" alt="Dragon Pay">
                        <img src="/assets/images/img-cod-black.png" alt="Cash on Delivery">
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
</div>

<?php
    include("productpage_view_review.php");
    include("productpage_view_recommend.php");
?>

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

(function($) {

    // hiden values variables
    $productCombQuantity = JSON.parse($("#productCombQuantity").val());

    // js code for base price line through
    var $productprice= $('.prod-price-container').find('.base-price');
    if( $productprice.hasClass('base-price') ){
        $('.discounted-price').addClass('line-through');
    }

    $(".attribute-control").bind('change',function(e){
        var $this = $(this);
        var $arraySelected = [];
        var $baseFinalPrice = parseFloat($("#finalBasePrice").val());

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
        
        // check possible combination
        $.each($productCombQuantity, function(i, val) {
            $arrayCombination = val.product_attribute_ids;
            $arrayCombination.sort(sortArrayNumber);
            $booleanCheck = arraysEqual($arrayCombination,$arraySelected);
            $combinationQuantity = val.quantity;
            $("#control-quantity").empty();
            // if found atleast one combination
            if($booleanCheck){ 
                for (var i = 1 ; i <= $combinationQuantity; i++) { 
                    $("#control-quantity").append('<option>'+ i +'</option>');
                };
                return false;
            }
            $("#control-quantity").append('<option>0</option>');
        });
    });

    // location
        $("body").on('change','#shipment_locations', function(){
            var selected = $('#shipment_locations :selected');
            if(selected.val() == 0){
                $('.shipping_fee').html("<span class='loc_invalid'>Select location*</span>");
            }
            else{
                var fee = parseFloat(selected.data('price'));
                if(fee > 0){
                    $('.shipping_fee').html("<span class='shipping_fee_php'>PHP <span><span class='shipping_fee_price'>"+fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</span>");
                }
                else{
                    //$('.shipping_fee').html("<span class='free_shipping_logo'></span>");
                    $('.shipping_fee').html("<span class='free_shipping_logo'><span class='shipping_fee_php' style='font-weight:bold; font-size:10px; color:#F18200;'>Free shipping</span></span>");
                }
            }
        });

        //Loads the default shipment locations
        $.each(shipment, function(index, value){
            
            if(value.location_id == 1 && value.location_type == 0){
                var firstOption = $('.shiploc option:not(.default)').first();
                firstOption.data('price',value.price);
                firstOption.prop('disabled', false);
                $.each(firstOption.nextAll(), function(){
                    $(this).prop('disabled', false);
                    $(this).data('price',value.price);
                });
                return false;
            }

            var option =  $('#locationID_' + value.location_id);
            option.data('price',value.price);
            option.prop('disabled', false);
            $.each(option.nextAll(), function(){
                if($(this).data('type') === option.data('type')){
                return false;
                }
                $(this).prop('disabled', false);
                $(this).data('price',value.price);
            }); 
        });
        

})(jQuery);
</script>
