(function ($) {
    $.stickysidebarscroll("#filter-panel-container",{offset: {top: -60, bottom: 100}});
    
    $('body').attr('data-spy', 'scroll').attr('data-target', '#myScrollspy').attr('data-offset','0');
    $("body").scrollspy({target: "#myScrollspy"});
    
    $( ".icon-list" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-grid").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).addClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-3");
             $( ".col-search-item" ).addClass("col-xs-12");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });
    
    $( ".icon-grid" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-list").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).removeClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-12");
            $( ".col-search-item" ).addClass("col-xs-3");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });

    $(window).bind('scroll',function(e){
        parallaxScroll();
    });

    /* parallax control */
    function parallaxScroll(){
        var scrolled = $(window).scrollTop();
        var x1 = '0';
        var y2 = (0-(scrolled*.76));
        $('#parallax-3').css({
            "background-position": x1+'px '+y2+'px', 
            "top": (0-(scrolled*.25))+'px',
            "height":(300+(scrolled*.25))+'px',
        });
    }

    $(document).ready(function(){
        $('.slider1').bxSlider({
            slideWidth: 390,
            minSlides: 1,
            maxSlides: 3,
            moveSlides: 1,
            slideMargin: 5,
            autoStart: true,
            speed: 1000,
            pause: 6000,
            prevText: '',
            nextText: '',
            touchEnabled: true,
            infiniteLoop: true,
            pager: false
        });

        $('.top-slider').bxSlider({
            prevText: '<i class="fa fa-angle-left fa-lg"></i>',
            nextText: '<i class="fa fa-angle-right"></i>'
        });
        
    });

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
        };
    })();

    var $window = $(window);
    $window.on('load resize', function() {
        delay(function(){

            var windowWidth = $(window).width();
            var bxSliderWidth = $('.bx-wrapper').width();
            var bxSliderHeight = $('.bx-wrapper').height();
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
            }
            if (windowWidth <= 395) {
                $(".search-slider .bx-viewport .slide").css("width", searchslidewidth);
            }
        });
    });
    
    //determine the search results container reached the bottom 
    var sticky_offset;
    $(document).ready(function() {
        var original_position_offset = $('#sticky-pagination').offset();
        sticky_offset = original_position_offset.top;
        $('#sticky-pagination').css('position', 'fixed').css('width', '64%').css('bottom', '-200px');
    });

    $(window).scroll(function () {
        var sticky_height = $('#sticky-pagination').outerHeight();
        var where_scroll = $(window).scrollTop();
        var window_height = $(window).height();

        if(where_scroll <= 400)  {
            $('#sticky-pagination').css('bottom', '-400px');
        }else{
            $('#sticky-pagination').css('bottom', '0px');
        }
    });
}(jQuery));

