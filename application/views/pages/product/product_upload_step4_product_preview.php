
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/product-page-css.css?ver=<?php echo ES_FILE_VERSION ?>" media='screen'>
    <link rel="stylesheet" type="text/css" href="/assets/css/step4-product-preview-css.css?ver=<?php echo ES_FILE_VERSION ?>" media='screen'>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.upload-step4-preview.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<section class="product-main-top-content">
    <div class="product-name-seller col-md-12 pd-bttm-30">
        <h1> 
            <span id="pname"> <?=html_escape($product->getName())?> </span>
        </h1>
        <div>
            By:
               <span class="product-profile-photo"><img src="<?php echo getAssetsDomain().'.'.$avatarImage?>"></span>
               <?=html_escape($product->getMember()->getStoreName());?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="prod-border-bttm"></div>
</section>


<div class="pd-top-40">
    <div class="col-md-6">
        <div class="display-when-desktop" style="position: relative; z-index: 2;">
            <div class="row">
                <div class="col-md-3">
                    <div class="thumbnails-img-container">
                        <div class="thumb-list-container">
                            <ul id="thumblist">
                                <?php foreach($productImages as $image): ?>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <img src='<?php echo getAssetsDomain().$image->getDirectory(); ?>categoryview/<?=$image->getFilename(); ?>'> 
                                        </a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </div>

                        <div class="carousel-nav-btn-wrapper">
                            <a href="javascript:void(0);" id="prev" class="jcarousel-control-prev inactive">&lt;&lt; Prev </a>
                            <a href="javascript:void(0);" id="next" class="jcarousel-control-next inactive"> Next &gt;&gt; </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="prod-gallery-container">
                        <div class="prod_con_gal text-center">
                            <img src="<?php echo getAssetsDomain().$productImages[0]->getDirectory(); ?>small/<?=$productImages[0]->getFilename(); ?>"  title="product"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="mobile-product-gallery">
            <div id="mobile-product-gallery" class="owl-carousel">
                    <div> 
                        <img src="<?php echo getAssetsDomain().$productImages[0]->getDirectory(); ?>small/<?=$productImages[0]->getFilename(); ?>"  title="product"> 
                    </div>
                    <div class="owl-controls">
                        <div class="owl-prev">prev</div>
                        <div class="owl-next">next</div>
                    </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <?php if(floatval($product->getDiscountPercentage()) > 0):?>
        <div class="prod-price-container">
            <span class="base-price" data-baseprice="<?=number_format($product->getOriginalPrice(),2,'.',','); ?>"> 
                P<?=number_format($product->getOriginalPrice(),2,'.',',');?> 
            </span>

            <span class="discounted-price"> P<?=number_format($product->getFinalPrice(),2,'.',','); ?></span> 
        </div>
        <div class="prod-dc-container text-right">
            <span class="prod-dc-badge"> -<?=number_format($product->getDiscountPercentage(),0,'.',',');?>%</span>
        </div>
        <?php else: ?>
        <div class="prod-price-container">
            <span class="discounted-price" data-baseprice="<?=number_format($product->getOriginalPrice(),2,'.',','); ?>"> 
                P<?=number_format($product->getFinalPrice(),2,'.',',');?> 
            </span>
        </div>
        <?php endif;?>
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
                        <select class="attribute-control" disabled="">
                            <option value="0" data-addprice="0" selected=selected>--<?=ucfirst(html_escape($head));?>--</option>
                            <?php foreach ($headValue as $key => $value):?>
                                <option value="<?=$value['attr_id']; ?>" data-headvalue="<?=strtolower(html_escape($head))?>" data-textvalue="<?=strtolower(html_escape($value['attr_value'])); ?>" data-imageid=<?=$value['image_id']; ?> data-addprice="<?=$value['attr_price']?>"><?=html_escape($value['attr_value']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?> 
            <!-- end of Product attributes -->
            <div class="clear"></div>
        </div>
        <div class="col-md-12 prod-border-bttm pad-top-23"></div>
        <?php endif; ?>
        <div class="clear"></div>
        <div class="row pad-top-23">
            <div class="col-md-12 col-lg-5">
                <div class="prod-availability-container prod-border-right">
                    <p>Availability: <span class="in-stock">Select Combination</span></p>
                </div>
            </div>
            <div class="col-md-12 col-lg-7">
                <p class="attr-title txt-shipment">Shipment Fee:</p>
                    <?php if(count($shippingLocation) === 0 && (bool) $product->getIsMeetup()): ?> 
                        <span class="default">NOT AVAILABLE</span>
                    <?php else: ?>
                        <?php if(!$isFreeShippingNationwide): ?>
                        <div class="prod-select-con ui-form-control shipment-select">
                            <select class="shiploc" id="shipment_locations" disabled="">
                                <option>Select Location</option>
                            </select>
                        </div>
                        <?php else: ?>
                        <span class="default">FREE SHIPPING NATIONWIDE</span> 
                        <?php endif; ?>
                    <?php endif; ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="col-md-12 prod-border-bttm pad-top-23"></div>
        <div class="clear"></div>
        <div class="row pad-top-23">
            <div class="col-sm-12 col-md-5 prod-quantity-container">
                <p class="attr-title">Quantity:</p>
                <div class="prod-select-con ui-form-control quantity-select">
                    <select id="control-quantity" disabled="">
                        <option value="0">0</option>
                    </select>
                </div>
            </div>
            <div class="col-md-7" align="center">
                <?php if(count($shippingLocation) === 0 && (bool)$product->getIsMeetup()): ?>
                    <a class="btn-meet-up modal_msg_launcher" title="Send <?=html_escape($product->getMember()->getUsername())?> a message" ><div class="btn-contact-seller"><i class="icon-message"></i> Contact Seller</div></a>
                    <span class="span-after-btn" width="100%">Item is listed as an ad only. *</span>
                <?php elseif($product->getPromoType() == \EasyShop\Entities\EsPromoType::BUY_AT_ZERO && (bool) $product->getStartPromo() ): ?>
                    <input type="button" value="Buy Now" class="prod-add-to-cart-btn btn-buy-now disabled" >
                    <span class="span-after-btn" width="100%">Click buy to qualify for the promo*</span> 
                <?php else: ?>
                    <input type="button" value="" class="prod-add-to-cart-btn disabled" >
                    <span class="span-after-btn" width="100%">Delivers upon seller confirmation*</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="col-md-12 prod-border-bttm pad-top-23"></div>
        <div class="clear"></div>
        <div class="row pad-top-23">
            <div class="col-md-12 prod-payment-img-container">
                <p class="attr-title">Payment:</p>
                <?php if(isset($paymentMethod['cdb'])): ?> 
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-mastercard-black.png" alt="Mastercard">
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-visa-black.png" alt="Visa">
                <?php endif; ?>

                <?php if(isset($paymentMethod['dragonpay'])) : ?> 
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-dragonpay-black.png" alt="Dragon Pay">
                <?php endif; ?> 

                <?php if(isset($paymentMethod['paypal'])) : ?> 
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-paypal-black.png" alt="Paypal">
                <?php endif; ?>

                <?php if(isset($paymentMethod['cod']) && intval($product->getIsCod(),10) === 1): ?> 
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-cod-black.png" alt="Cash on Delivery">
                <?php endif; ?>


                <?php if(isset($paymentMethod['pesopaycdb'])) : ?> 
                     <img src="<?php echo getAssetsDomain(); ?>assets/images/img-pesopay-black.png" alt="PesoPay Credit/Debit Card">
                <?php endif; ?>
            </div>
        </div>
        <div class="clear"></div> 
    </div>
    <div class="clear"></div>
</div>


<section>
    <div class="col-xs-12">
        <div class="prod-detail-main">
            <div class="div-prod-lower">
                <div class="div-detail-nav">
                    <ul class="ul-detail-nav">
                        <li class="active"><a href="#details" role="tab" data-toggle="tab">Product Detail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="div-detail-nav-mobile">
                <table width="100%" class="table-nav-prod">
                    <tbody>
                        <tr>
                            <td class="td-detail active" width="50%" id="tdDetails">
                                <a href="#details" id="prodDetails">
                                    <p class="p-detail-a">Product Detail</p>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <div class="tab-pane fade in active" id="details">
            <div class="div-detail-container ">
                <div class="p-html-description external-links-container">
                    <p>
                        <?=$productDescription;?> 
                        <br>
                        <?php if($product->getCondition() !== ""): ?>
                            Condition: <?=html_escape($product->getCondition()); ?>
                            <br>
                        <?php endif; ?>
                        <?php if($product->getBrief() !== ""): ?>
                            Brief Description: <?=html_escape($product->getBrief()); ?>
                            <br>
                        <?php endif; ?>
                        <?php if($product->getBrand()->getName() !== "" && $product->getBrand()->getIdBrand() !== \EasyShop\Entities\EsBrand::CUSTOM_CATEGORY_ID): ?>
                            Brand: <?=html_escape($product->getBrand()->getName()); ?>
                            <br>
                        <?php elseif ($product->getBrandOtherName() !== ""):?>
                            Brand: <?=html_escape($product->getBrandOtherName()); ?>
                            <br>
                        <?php endif; ?>
                        <?php if($product->getSku() !== ""): ?>
                            SKU: <?=html_escape($product->getSku()); ?>
                        <?php endif; ?>
                    </p>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</section>

<script type="text/javascript">

$(document).ready(function() {

    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();

    var $window = $(window);
    $window.on('load resize', function() {
         delay(function(){

            var prodcongal= $(".prod_con_gal").width();
            $(".prod_con_gal img").css("max-width", prodcongal);

            var owlcarousel= $(".owl-carousel div").width();
            var owlcarouselimg= $(".owl-carousel img");

            owlcarouselimg.css({"max-width": owlcarousel});


        }, 500);
    });
});
</script>
