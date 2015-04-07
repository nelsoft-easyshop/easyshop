(function ($) {

    $(window).load(function(){

        $('.slider1').bxSlider({
            minSlides: 1,
            maxSlides: 3,
            moveSlides: 1,
            slideWidth: 0,
            slideMargin: 5,
            speed: 1000,
            pause: 6000,
            infiniteLoop: true, 
            pager: false,
        });

        $('.top-slider').bxSlider({
            prevText: '<i class="fa fa-angle-left fa-lg"></i>',
            nextText: '<i class="fa fa-angle-right"></i>'
        });

        $('div.loader').fadeOut("fast");
    });
   
    $(window).bind('scroll',function(e){
        parallaxScroll();
        parallaxScroll2();
    });

    /* parallax control */
    function parallaxScroll(){
        var scrolled = $(window).scrollTop();
        var x1 = '0';
        var y2 = '0';
            $('.slider-bottom').css({ 
                "margin-top": (-71+(scrolled*.48))+'px',
            });
    }

    /* parallax control for top banner*/
    function parallaxScroll2(){
        var scrolled2 = $(window).scrollTop();
        var x12 = '0';
        var y22 = (0-(scrolled2*.76));
        $('.top-slider-item').css({
            "margin-top": (-100+(scrolled2*.6))+'px',
        });
    }

    var $window = $(window);
    $window.on('load resize', function() {
        setTimeout(function(){

            var windowWidth = $(window).width();
            var bxSliderWidth = $('.bx-wrapper').width();
            var bxSliderHeight = $('.bx-wrapper .slide').height();
            var shadowWidth = (windowWidth - bxSliderWidth) / 2;
            var searchslideimage = $(".search-slider .slider1 .slide img").height();
            var sliderTop = (searchslideimage - 32) /2;
            var searchcontainerwidth = $(".search-slider .bx-viewport").width();
            var searchslidewidth = (searchcontainerwidth - 2);
            var searchslidewidth2 = (searchcontainerwidth - 10)/ 2;
            $('.left-shade, .right-shade').css({'width': shadowWidth, 'height': bxSliderHeight});
            $(".search-slider .bx-controls-direction .bx-prev, .search-slider .bx-controls-direction .bx-next").css("top",sliderTop);

            if (windowWidth <= 720) {
                $(".search-slider .bx-viewport .slide").css("width", searchslidewidth2);
                $(".top-slider-img").css("display","none");
            }

            if (windowWidth <= 395) {
                $(".search-slider .bx-viewport .slide").css("width", searchslidewidth);
            }
        }, 300);
    }); 

}(jQuery));

