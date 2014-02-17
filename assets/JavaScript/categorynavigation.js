  
$(document).ready(function() {

    $('.slider_product').bxSlider({
        slideWidth: 70,
        minSlides: 2,
        maxSlides: 3,
        moveSlides: 1,
        slideMargin: 0,
        infiniteLoop:true,
        autoControls: false,
        pager:false
    });
    
    $('.bx-viewport').addClass('product_slides').css('height','105px');
    $('.bx-wrapper').addClass('slide_arrows');
    
});