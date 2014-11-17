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
            <?php if(count($productAttributes) > 0): ?>
            <div class="row pad-top-23">
                <div class="col-md-12"><p class="attr-title">Other Attributes</p></div>
                <!-- Product attributes here -->
                <?php foreach ($productAttributes as $head => $headValue): ?>
                    <div class="col-sm-12 col-md-6 attr-select <?=(count($headValue)  > 1) ? "" : "element-hide";?>">
                        <div class="prod-select-con ui-form-control">
                            <select class="attribute-control" disabled="">
                                <?php if(count($headValue) > 1): ?>
                                <option value="0" data-addprice="0" selected=selected>--<?=ucfirst($head);?>--</option>
                                <?php endif; ?>
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
            <?php endif; ?>
            <div class="clear"></div>
            <div class="row pad-top-23">
                <div class="col-xs-12 col-sm-5 col-md-5">
                    <div class="prod-availability-container prod-border-right">
                        <p>Availability: <span class="in-stock">Select Combination</span></p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-7">
                    <p class="attr-title txt-shipment">Shipment Fee:</p>
                        <?php if(!$isFreeShippingNationwide): ?>
                        <div class="prod-select-con ui-form-control shipment-select">
                            <select class="shiploc" id="shipment_locations" disabled="">
                                <option>Select Location</option>
                            </select>
                        </div>
                        <?php else: ?>
                        <span class="default">FREE SHIPPING NATIONWIDE</span> 
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
 