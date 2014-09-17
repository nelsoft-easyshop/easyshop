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
</section>
<section class="sticky-nav-bg">
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
                    <select class="ui-form-control">
                        <option>On Seller's Page</option>
                        <option>Main Page</option>
                        <option>Other Page</option>
                    </select>
                    <input type="text" class="ui-form-control">
                    <input type="submit" value="" class="span_bg">
                </li>
                <li>
                    <div>
                        <span class="cart-items-con">
                            <span class="cart-item">2 item(s)</span> in your cart
                        </span>
                        <span class="cart-icon-con fa fa-shopping-cart"></span>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>assets/js/src/jquery-1.8.2.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/bootstrap.js" type="text/javascript"></script>
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
	
	var $window = $(window),
       $stickyLeft = $('#the-sticky-div'),
       leftTop = $stickyLeft.offset().top;

	   $window.scroll(function() {
			$stickyLeft.toggleClass('sticky', $window.scrollTop() > leftTop);
		});
	
</script>