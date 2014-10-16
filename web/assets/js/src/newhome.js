(function ($) {

    $('.bxslider').bxSlider({
        minslides : 1,
        maxSlides: 1,
        moveSlides: 1,
        speed: 1000,
        auto: true,
        pause: 6000,
        prevText : '',
        nextText : '',
        slideMargin : 10,
        touchEnabled : true
    });

    $('#content .bx-wrapper').removeAttr("style");
    // $('#content .bx-wrapper ul li').removeAttr("style");

        
    $(window).on('load resize', function() {
        var windowWidth = $(window).width(),
        bxSliderWidth = $('#bxslider').width(),
        bxSliderHeight = $('#bxslider').height(),
        shadowWidth = (windowWidth - bxSliderWidth) / 2 ;

        $('.left-side-shadow, .right-side-shadow').css({'width': shadowWidth, 'height': bxSliderHeight});

    });


}(jQuery));