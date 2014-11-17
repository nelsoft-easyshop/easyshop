<link rel="stylesheet" type="text/css" href="/assets/css/product-page-css.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/step4-product-preview-css.css" media='screen'>


<section class="product-main-top-content">
    <div class="container">
        <div class="row">
            <div class="product-name-seller col-md-12">
                <h1> 
                    <span id="pname"> <?=html_escape($product->getName())?> </span>
                </h1>
                <div>
                    By:
                        <span class="product-profile-photo"><img src="<?=$avatarImage?>"></span>
                       <?=html_escape($product->getMember()->getStoreName());?>
                </div>
            </div>
        </div>
        <div class="prod-border-bttm"></div>
    </div>
</section>

<div class="container">
    <div class="row pd-top-40">
        <div class="col-md-6">
            <div class="display-when-desktop" style="position: relative; z-index: 2;">
                <div class="col-md-3">
                    <div class="thumbnails-img-container">
                        <div class="slideshow">
                            <?php foreach($productImages as $image): ?>
                                <a href="javascript:void(0);">
                                    <img src='/<?=$image->getDirectory(); ?>categoryview/<?=$image->getFilename(); ?>'> 
                                </a>
                            <?php endforeach;?>
                        </div>
                        <div class="center disable-buttons">
                            <a href="javascript:void(0);" id="prev">&lt;&lt; Prev </a>
                            <a href="javascript:void(0);" id="next"> Next &gt;&gt; </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="prod-gallery-container">
                        <div class="prod_con_gal text-center">
                            <img src="/<?=$productImages[0]->getDirectory(); ?>small/<?=$productImages[0]->getFilename(); ?>"  title="product"> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="mobile-product-gallery">
                <div id="mobile-product-gallery" class="owl-carousel">
                        <div> 
                            <img src="/<?=$productImages[0]->getDirectory(); ?>small/<?=$productImages[0]->getFilename(); ?>"  title="product"> 
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
            <div class="row pad-top-23">
                <div class="col-md-12"><p class="attr-title">Other Attributes</p></div>
                <div class="col-sm-12 col-md-6">
                    <div class="prod-select-con ui-form-control">
                        <select class="attribute-control">
                            <option>Please Select</option>
                            <option>first</option>
                            <option>Second</option>
                            <option>Third</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="prod-select-con ui-form-control">
                        <select class="attribute-control">
                            <option>Please Select</option>
                            <option>first</option>
                            <option>Second</option>
                            <option>Third</option>
                        </select>
                    </div>
                </div>
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
                            <option>Please Select</option>
                            <option>first</option>
                            <option>Second</option>
                            <option>Third</option>
                        </select>
                    </div>
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
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-7" align="center">
                    <input type="button" value="Add to Cart" class="prod-add-to-cart-btn disabled" >
                    <span class="span-after-btn" width="100%">Delivers upon seller confirmation*</span>
                </div>
            </div>
            <div class="clear"></div>
            <div class="col-md-12 prod-border-bttm pad-top-23"></div>
            <div class="clear"></div>
            <div class="row pad-top-23">
                <div class="col-md-12 prod-payment-img-container">
                    <p class="attr-title">Payment:</p>
                    <img src="/assets/images/img-mastercard-colored.png" alt="Mastercard">
                    <img src="/assets/images/img-visa-colored.png" alt="Visa">
                    <img src="/assets/images/img-dragonpay-colored.png" alt="Dragon Pay">
                    <img src="/assets/images/img-paypal-colored.png" alt="Paypal">
                    <img src="/assets/images/img-cod-colored.png" alt="Cash on Delivery">
                </div>
            </div>
            <div class="clear"></div>
            <div class="col-md-12 prod-border-bttm pad-top-23"></div>
            <div class="clear"></div>

            <div class="row pad-top-23">
                <div class="col-md-12 prod-share-container">
                    <p class="attr-title">Share with:</p>
                    <a href="javascript:void(0)" class="facebook-lnk">
                        <div class="ui-form-control share-facebook">
                            <img src="/assets/images/img-facebook-prod.png" alt="Facebook"> 
                            <span>Facebook</span>
                        </div>
                    </a>
                    <a class="twitter-lnk">
                        <div class="ui-form-control share-twitter">
                            <img src="/assets/images/img-twitter-prod.png" alt="Twitter"> 
                            <span>Twitter</span>
                        </div>
                    </a>
                    <a href="javascript:void(0);">
                        <div class="ui-form-control share-googleplus">
                            <img src="/assets/images/img-googleplus-prod.png" alt="Google+"> 
                            <span>Google+</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<section>
    <div class="container">
        <div class="prod-detail-main">
            <div class="div-prod-lower">
                <div class="div-detail-nav">
                    <ul class="ul-detail-nav">
                        <li class="active"><a href="#details" role="tab" data-toggle="tab">Product Detail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in active" id="details">
            <div class="div-detail-container ">
                <div class="p-html-description">
                    <p>  
                        <?=$productDescription;?> 
                    </p>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
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
            var prod_con_gal= $(".prod_con_gal").width();
            $(".prod_con_gal img").css("max-width", prod_con_gal);

            var mobile_prod_gal= $(".owl-carousel div").width();
            $(".owl-carousel div img").css("max-width", mobile_prod_gal);
        }, 500);
    });
});
</script>