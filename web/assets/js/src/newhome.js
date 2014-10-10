(function ($) {

    $('.bxslider').bxSlider({
        maxSlides: 4,
        moveSlides: 1,
        speed: 1000,
        auto: true,
        pause: 6000,
        prevText : '',
        nextText : '',
        slideWidth: 270,
        slideMargin: 20,
        infiniteLoop: true
    });

    $('#content .bx-wrapper').removeAttr("style");
        
    $(window).on('load resize', function() {
        var windowWidth = $(window).width(),
        bxSliderWidth = $('#bxslider').width(),
        bxSliderHeight = $('#bxslider').height(),
        shadowWidth = (windowWidth - bxSliderWidth) / 2 ;

        $('.left-side-shadow, .right-side-shadow').css({'width': shadowWidth, 'height': bxSliderHeight});

    });


}(jQuery));