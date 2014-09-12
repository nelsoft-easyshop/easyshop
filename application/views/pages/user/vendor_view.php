<link type="text/css" href='<?=base_url()?>assets/css/main-style.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='<?=base_url()?>assets/css/bootstrap.css' rel="stylesheet" media='screen'/>

<section>
    <div class="pos-rel">
        <div class="vendor-main-bg">
            <img src="<?=base_url()?>assets/images/sample-vendor-img.jpg">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <img src="<?=base_url()?>assets/images/img-default-vendor-profile-photo.jpg">
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
    <div class="pos-rel">
        <div class="vendor-main-bg">
            <div class="edit-cover-photo">
                <a href="">
                    <img src="<?=base_url()?>assets/images/img-default-cover-photo.png" alt="Change Cover Photo"><br />
                    <h4><strong>Change Cover Photo</strong></h4>
                </a>
            </div>
            <img src="<?=base_url()?>assets/images/sample-vendor-img.jpg" alt="sample cover photo">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <div class="edit-profile-photo">
                                <div>
                                    <img src="<?=base_url()?>assets/images/img-default-cover-photo.png" alt="Edit Profile Photo">
                                    <span>Change Profile Photo</span>
                                </div>
                            </div>
                            <div class="edit-profile-photo-menu">
                                <div>Upload Photo</div>
                                <div>Remove Photo</div>
                            </div>
                            <img src="<?=base_url()?>assets/images/mg-default-cover-photo.jpg" alt="Profile Photo">
                        </div>
                    </div>
                </div>
                <div class="pd-lr-20">
                    <input type="text" class="form-control mrgn-bttm-8 mrgn-top-10" placeholder="Seller Name">
                    <input type="text" class="form-control mrgn-bttm-8" placeholder="Contact No.">
                    <div class="mrgn-bttm-8 edit-vendor-location">
                        <input type="text" class="ui-form-control">
                        <input type="text" class="ui-form-control">
                    </div>
                    <div class="vendor-profile-btn edit-profile-btn">
                        <a href="" class="btn btn-default-1">Cancel</a>
                        <a href="" class="btn btn-default-3">Save Changes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="vendor-sticky-nav">
        <div class="main-container">
            <ul class="vendor-nav">
                <li>
                    <a href="" class="vendor-nav-active">
                        <img src="<?=base_url()?>assets/images/img-sticky-nav-home-active.jpg" alt="Store">
                    </a>
                </li>
                <li>
                    <a href="">Promo Page</a>
                </li>
                <li>
                    <a href="">Seller Information</a>
                </li>
                <li>
                    <a href="">Contact</a>
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
                    <select>
                        <option>On Seller's Page</option>
                        <option>Main Page</option>
                        <option>Other Page</option>
                    </select>
                    <input type="text">
                    <input type="submit" value="" class="span_bg">
                </li>
                <li>
                    <div>
                        <span class="cart-items">
                            2 item(s) in your cart
                        </span>
                        <span class="cart-icon-con glyphicon glyphicon-shopping-cart"></span>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section>

<section class="vendor-content-wrapper">
    <div class="main-container">
        <div class="vendor_products_wrapper user-tab">
                <?php if($product_count > 0):?>
                    <?php foreach($products as $catID=>$p):?>
                    <div class="vendor_txt_prod_header" class="<?php echo $p['slug']?>">
                        <div class="home_cat_product_title" style="background-color:#0078d5;">
                            <a target="_blank" href="<?php echo $p['cat_link']?>" <?php echo $p['cat_link']==="" ? 'onclick="return false"':""?> >
                                <img src="<?=base_url()?><?php echo $p['cat_img']?>">
                                <h2><?php echo $p['name']?></h2> 
                            </a>   
                        </div>
                    </div>
                    <div class="vendor_prod_items">
                        <?php foreach($p['products'] as $prod):?>
                            <div class="product vendor_product">
                                <a target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                    <span class="prod_img_wrapper">
                                        <span class="prod_img_container">
                                           <img src="<?=base_url()?><?php echo $prod['product_image_path']?>">
                                        </span>
                                    </span>
                                </a>    
                                <h3>
                                    <a target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                       <?php echo html_escape($prod['name'])?>
                                    </a>
                                </h3>
                                 <div class="price-cnt">
                                    <div class="price">
                                        Php <?php echo html_escape($prod['price'])?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <div class="txt_load_more_con v_loadmore">
                        <a target="_blank" href="<?php echo $p['loadmore_link']?>" class="grey_btn">LOAD MORE ITEMS</a>
                    </div>
                    <?php endforeach;?>
                <?php endif;?>
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

    
})(jQuery);
</script>