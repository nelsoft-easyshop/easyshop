<section>
    <div class="pos-rel">
        <div class="vendor-main-bg">
            <img src="/assets/images/sample-vendor-img.jpg">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <img src="/assets/images/img-default-vendor-profile-photo.jpg">
                        </div>
                    </div>
                </div>
                <div>
                    <h4>Air 21</h4>
                    <p><strong>Contact No. :</strong>09171234567</p>
                    <p>
                        <span class="glyphicon glyphicon-map-marker"></span>
                        <span class="cl-1"><strong>Location not set</strong></span>
                    </p>
                    <div class="vendor-profile-btn">
                        <a href="" class="btn btn-default-2">
                            <span class="glyphicon glyphicon-plus-sign"></span> Follow
                        </a>
                        <a href="" class="btn btn-default-1">
                            <span class="glyphicon glyphicon-envelope"></span> Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="sticky-nav-bg">
    <div class="vendor-sticky-nav">
        <div class="main-container">
            <ul class="vendor-nav">
                <?php
                    $url_id = $this->uri->segment(2, 0);
                ?>
                <li>
                    <a href="" class="<?php if($url_id=="0"){ echo "vendor-nav-active"; }else{ echo " ";}?>">
                        <img src="<?=base_url()?>assets/images/<?php if($url_id=="0"){ echo "img-sticky-nav-home-active"; }else{ echo "img-sticky-nav-home";}?>.jpg" alt="Store">
                    </a>
                </li>
                <li>
                    <a href="">Promo Page</a>
                </li>
                <li >
                    <a href="" class="<?php if($url_id=="about"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Seller Information</a>
                </li>
                <li>
                    <a href="" class="<?php if($url_id=="contact"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Contact</a>
                </li>
            </ul>
            <ul class="sticky-nav">
                <li>
                    <div class="vendor-profile-img-con">
                        <img src="<?=base_url()?>assets/images/img-default-vendor-profile-photo.jpg" alt="Profile Photo">
                    </div>
                    <h4>Air 21</h4>
                </li>
                <li>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-promo.jpg"></a>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-info.jpg"></a>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-contact.jpg"></a>
                </li>
                <li>
                    <select class="ui-form-control">
                        <option>On Seller's Page</option>
                        <option>Main Page</option>
                        <option>Other Page</option>
                    </select>
                    <input type="text" class="ui-form-control">
                    <input type="submit" value="" class="span_bg">
                </li>
                <li class="pos-rel">
                    <div class="header-cart-container">
                        <span class="header-cart-items-con sticky-cart">
                            <span class="header-cart-item"><?=$cart_size?> item(s)</span> in your cart
                        </span>
                        <span class="header-cart-icon-con span_bg cart-icon"></span>
                    </div>
                    <div class="sticky-header-cart-item-list">
                        <?PHP if ((intval(sizeof($cart_items))) === 0 ) : ?>
                        <?PHP else : ?>
                            <p>Recently add item(s)</p>
                            <?PHP for($cnt = sizeof($cart_items) - 1; $cnt > -1 ;$cnt--) : ?>
                                <?PHP if(sizeof($cart_items) - 1 == $cnt || sizeof($cart_items) - 1 == $cnt +1) : ?>
                                    <div class="mrgn-bttm-15">
                                        <div class="header-cart-item-img">
                                            <a href="/item/<?=$cart_items[$cnt]['slug']?>">
                                                <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                                <!--                                    <span><img src="/--><?php //echo $row['imagePath']; ?><!--thumbnail/--><?php //echo $row['imageFile']; ?><!--" alt="Doraemon"></span>-->
                                            </a>
                                        </div>
                                        <div class="header-cart-item-con">
                                            <a href=""><span><?=$cart_items[$cnt]['name']?></span></a>
                                            <span>x <?=$cart_items[$cnt]['qty']?></span>
                                            <span class="header-cart-item-price">&#8369; <?=$cart_items[$cnt]['price']?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                <?PHP endif; ?>
                            <?PHP endfor; ?>
                            <div class="header-cart-lower-content">
                                <div class="header-cart-shipping-total">
                                    <p>Items(s) in cart: <span><?=$cart_size?></span></p>
                                    <p>Total: <span>&#8369; <?=$total?></span></p>
                                </div>
                                <div class="header-cart-buttons">
                                    <a href="/cart" class="header-cart-lnk-cart">go to cart</a>
                                    <a href="javascript:void(0)" onclick="proceedPayment(this)" class="header-cart-lnk-checkout">checkout</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?PHP endif;?>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section>


<script type="text/javascript">

    (function ($) {
        //create a stick nav
        var menuOffset = $('.vendor-sticky-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
        $(document).bind('ready scroll', function() {
            var docScroll = $(document).scrollTop();
            if (docScroll >= 455) 
                {
                    if (!$('.vendor-sticky-nav').hasClass('sticky-nav-fixed')) {
                        $('.vendor-sticky-nav').addClass('sticky-nav-fixed').css({
                            top: '-155px'
                        }).stop().animate({
                            top: 0
                        }, 500);
                        
                    }
                    $('.vendor-content-wrapper').addClass('fixed-vendor-content');
                } 
            else 
                {
                    $('.vendor-sticky-nav').removeClass('sticky-nav-fixed').removeAttr('style');
                    $('.vendor-content-wrapper').removeClass('fixed-vendor-content');
                }
        });
        var $edit_profile_photo = $(".edit-profile-photo");
        var $edit_profile_photo_menu = $(".edit-profile-photo-menu");

        $(document).mouseup(function (e) {
            if (!$edit_profile_photo_menu.is(e.target) // if the target of the click isn't the container...
                && $edit_profile_photo_menu.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $edit_profile_photo_menu.hide(1);
            }
        });
        
        $edit_profile_photo.click(function() {
            $edit_profile_photo_menu.show();
        });
                
    })(jQuery);


</script>

