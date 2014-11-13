<link rel="stylesheet" type="text/css" href="/assets/css/product-page-css.css" media='screen'>
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
                    <span id="pname"> <?=htmlspecialchars(iconv("cp1252", "utf-8", $product->getName()),ENT_IGNORE,'UTF-8',true);?> </span>
                </h1>
                <div>
                    By:
                        <span class="product-profile-photo"><img src="<?php echo getAssetsDomain().'.'.$ownerAvatar?>"></span>
                        <?=html_escape($product->getMember()->getStoreName());?>
                </div>
            </div>
        </div>
        <div class="prod-border-bttm"></div>
        
       
    </div>
    
</section>

<section class="product-main-mid-content">
    <?=$bannerView; ?>
    <div class="container">
        <div class="row pd-top-40">
                
            <div class="col-md-6">
                <?=$imagesView?>
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
                                <select class="attribute-control">
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
                <div class="clear"></div>
                <?php endif; ?>

                <div class="row pad-top-23">
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="prod-availability-container prod-border-right">
                            <p>Availability: <span class="in-stock"><?=(intval($product->getIsSoldOut()) === 0)?'In Stock':'Out Of Stock';?></span></p>
                        </div>
                        <?php if(trim($product->getShippedWithinCount()) !== ""): ?>
                            <div class=" prod-border-right">
                                <p class="attr-title txt-shipment">Shipped within:</p> <span class="default"><?=$product->getShippedWithinCount(); ?> days</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-7">
                        <p class="attr-title txt-shipment">Shipment Fee:</p>
                            <?php if(count($shippingInfo) === 0 && intval($product->getIsMeetup()) === 1): ?>
                                <span class="default" selected="" value="0">NOT AVAILABLE</span>
                            <?php else: ?>
                                <?php if(!$isFreeShippingNationwide): ?>
                                    <div class="prod-select-con ui-form-control shipment-select">
                                        <select class="shiploc" id="shipment_locations">
                                            <option class="default" selected="" data-text="Select Location" value="0">Select Location</option>
                                            <?php foreach($shiploc['area'] as $island=>$loc):?>
                                                <option data-price="0" data-text="<?=$island;?>" data-type="1" id="<?='locationID_'.$shiploc['islandkey'][$island];?>" value="<?=$shiploc['islandkey'][$island];?>" disabled><?=$island;?></option>
                                                <?php foreach($loc as $region=>$subloc):?>
                                                    <option data-price="0" data-text="&nbsp;&nbsp;&nbsp;<?=$region;?>" data-type="2" id="<?='locationID_'.$shiploc['regionkey'][$region];?>" value="<?=$shiploc['regionkey'][$region];?>" style="margin-left:15px;" disabled>&nbsp;&nbsp;&nbsp;<?=$region;?></option>
                                                    <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                                        <option data-price="0" data-text="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$cityprov;?>" data-type="3" id="<?='locationID_'.$id_cityprov;?>" value="<?=$id_cityprov;?>" style="margin-left:30px;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$cityprov;?></option>
                                                    <?php endforeach;?>
                                                <?php endforeach;?>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <span class="default">FREE SHIPPING NATIONWIDE</span>
                                <?php endif; ?>
                            <?php endif; ?>
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
                    <div class="col-md-7" align="center">
                        <?php if($isLoggedIn && intval($userData['is_email_verify']) !== 1): ?>
                            <p class="btn-text"> <i class="fa fa-info-circle"></i> Verify your email </p>
                        <?php elseif($isLoggedIn && $viewerId == $product->getMember()->getIdMember()): ?>
                            <p class="btn-text"> <i class="fa fa-info-circle"></i> This is your own listing </p>
                        <?php else: ?>
                            <?php if(count($shippingInfo) === 0 && intval($product->getIsMeetup()) === 1): ?>
                                <a href="javascript:void(0)" class="btn-meet-up modal_msg_launcher" title="Send <?=html_escape($product->getMember()->getUsername())?> a message" ><div class="btn-contact-seller"><i class="icon-message"></i> Contact Seller</div></a>
                                <span class="span-after-btn" width="100%">Item is listed as an ad only. *</span>
                            <?php elseif($product->getPromoType() == 6 && $product->getStartPromo() == 1): ?>
                                <!--Changed button tag-->
                                <input type="button" id='<?=$canPurchase?'send':'' ?>_registration' value="Buy Now" class="prod-add-to-cart-btn btn-buy-now" >
                                <span class="span-after-btn" width="100%">Click buy to qualify for the promo*</span>
                            <?php elseif(!$isBuyButtonViewable && intval($product->getStartPromo()) === 1) : ?>
                                <p class="buy_btn_sub"> This product is for promo use only. </p>
                            <?php else: ?>
                                <input type="button" id="<?=$canPurchase?'send':'' ?>" value="Add to Cart" class="prod-add-to-cart-btn disabled" >
                                <span class="span-after-btn" width="100%">Delivers upon seller confirmation*</span>
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
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-mastercard-colored.png" alt="Mastercard">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-visa-colored.png" alt="Visa">
                        <?php else: ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-mastercard-black.png" alt="Mastercard">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-visa-black.png" alt="Visa">
                        <?php endif; ?>

                        <?php if(isset($paymentMethod['dragonpay'])) : ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-dragonpay-colored.png" alt="Dragon Pay">
                        <?php else: ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-dragonpay-black.png" alt="Dragon Pay">
                        <?php endif; ?> 

                        <?php if(isset($paymentMethod['paypal'])) : ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-paypal-colored.png" alt="Paypal">
                        <?php else: ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-paypal-black.png" alt="Paypal">
                        <?php endif; ?>

                        <?php if(isset($paymentMethod['cod']) && intval($product->getIsCod(),10) === 1): ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-cod-colored.png" alt="Cash on Delivery">
                        <?php else: ?>
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/img-cod-black.png" alt="Cash on Delivery">
                        <?php endif; ?>


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
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img-facebook-prod.png" alt="Facebook"> 
                                <span>Facebook</span>
                                <div id="fb-root"></div>
                                <div class="fb-share-button" data-href="<?=$url?>" data-width="150"></div>
                            </div>
                        </a>
                        <span class="twitter-lnk">
                            <div class="ui-form-control share-twitter">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img-twitter-prod.png" alt="Twitter"> 
                                <span>Twitter</span>
                                <a class="twitter-share-button" data-url="<?=$url?>" data-related="twitterdev" data-size="large"
                                    data-count="none">
                                    
                                </a>
                            </div>
                        </span>
                        <a href="https://plus.google.com/share?url=<?=$url?>">
                            <div class="ui-form-control share-googleplus">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img-googleplus-prod.png" alt="Google+"> 
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
    <input id="isFreeShippingNationwide" type="hidden" value="<?=$isFreeShippingNationwide;?>">
    <input id="submitReplyUrl" type="hidden" value="/product/submit-reply">
    <input id="submitReviewUrl" type="hidden" value="/product/submit-review">
</div>

<!-- display view for product details and review -->
<?=$reviewDetailsView;?>

<!-- display recommended products view -->
<?=$recommendedView;?>

<script type="text/javascript" src="/assets/js/src/vendor/jquery.jqzoom-core.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.bxslider1.min.js"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script> 
<script type="text/javascript" src="/assets/js/src/vendor/owl.carousel.min.js"></script>
<script type='text/javascript' src='/assets/js/src/bootstrap.js?ver=<?=ES_FILE_VERSION?>'></script>
<script type='text/javascript' src='/assets/js/src/product-page.js?ver=<?=ES_FILE_VERSION?>'></script>
<script type='text/javascript' src='/assets/js/src/social_media_share.js?ver=<?=ES_FILE_VERSION?>'></script>
<script type='text/javascript' src='/assets/js/src/promo/scratch-and-win.js?ver=<?=ES_FILE_VERSION?>'></script>

