<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/product-page-css.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" >
<link rel="stylesheet" href="/assets/css/owl.carousel.css">
<section>
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
                <div class="">
                    <div class="prod-price-container">
                        <span class="discounted-price" data-baseprice="<?=number_format($product->getFinalPrice(),2,'.',',');?>"> 
                            P <?=number_format($product->getFinalPrice(),2,'.',',');?> 
                        </span>
                        <?php if(floatval($product->getDiscountPercentage()) > 0):?>
                                <span class="base-price"> P <?php echo number_format($product->getOriginalPrice(),2,'.',','); ?></span> 
                            </div>
                            <div class="prod-dc-container text-right">
                                <span class="prod-dc-badge"> -<?=number_format( $product->getDiscountPercentage(),0,'.',',');?>%</span>
                            </div>
                        <?PHP endif;?>
                    
                    <div class="clear"></div>
                    <div class="col-md-12 prod-border-bttm"></div>

                    <div class="clear"></div>

                    <div class="row pd-top-15">
                        <div class="col-md-12"><p class="attr-title">Other Attributes</p></div>
                        <div class="col-sm-12 col-md-6 attr-select">
                            <div class="prod-select-con ui-form-control">
                                <select>
                                    <option selected=selected>Color</option>
                                    <option>Black</option>
                                    <option>Red</option>
                                    <option>White</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="prod-select-con ui-form-control">
                                <select>
                                    <option selected=selected>Size</option>
                                    <option>Small</option>
                                    <option>Medium</option>
                                    <option>Large</option>
                                </select>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="col-md-12 prod-border-bttm pd-top-20"></div>

                    <div class="clear"></div>

                    <div class="row pd-top-15">
                        <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="prod-availability-container prod-border-right">
                                <p>Availability: <span class="in-stock">In Stock</span></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-7 col-md-7">
                            <p class="attr-title">Shipment Fee:</p>
                            <div class="prod-select-con ui-form-control shipment-select">
                                <select>
                                    <option selected=selected>NCR - P 15.00</option>
                                    <option>Antipolo - P 15.00</option>
                                    <option>Bulacan - P 15.00</option>
                                    <option>Cavite - P 15.00</option>
                                </select>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="col-md-12 prod-border-bttm pd-top-20"></div>
                    <div class="clear"></div>

                    <div class="row pd-top-15">
                        <div class="col-sm-12 col-md-5 prod-quantity-container">
                            <p class="attr-title">Quantity:</p>
                            <div class="prod-select-con ui-form-control quantity-select">
                                <select>
                                    <option selected=selected>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <input type="submit" value="Add to Cart" class="prod-add-to-cart-btn">
                        </div>
                    </div>
                    <div class="clear"></div>

                    <div class="col-md-12 prod-border-bttm pd-top-20"></div>

                    <div class="clear"></div>

                    <div class="row pd-top-15">
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

                    <div class="col-md-12 prod-border-bttm pd-top-20"></div>

                    <div class="clear"></div>

                    <div class="row pd-top-15">
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
    </div>
</section>

<?php
    include("productpage_view_review.php");
    include("productpage_view_recommend.php");
?>

<script type="text/javascript" src="/assets/js/src/vendor/jquery.jqzoom-core.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.bxslider1.min.js"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<script type="text/javascript" src="/assets/js/src/productpage.js?ver=<?=ES_FILE_VERSION?>" ></script>
<script type="text/javascript" src="/assets/js/src/vendor/owl.carousel.min.js"></script>
