(function ($) {
    $.stickysidebarscroll("#filter-panel-container",{offset: {top: -60, bottom: 600}});
    
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
    
    $(document).ready(function(){
      $('.slider1').bxSlider({
        slideWidth: 390,
        minSlides: 1,
        maxSlides: 3,
        slideMargin: 10,
        autoStart: true
      });
    });
    
    $(window).on('load',function() {
        mainwindowsize = $(window).width();
        if (mainwindowsize < 565) {
            $('.slider1').bxSlider({
                minslides : 1,
                maxSlides: 1,
                moveSlides: 1,
                slideMargin: 10,
                speed: 1000,
                pause: 6000,
                prevText : '',
                nextText : '',
                touchEnabled : true,
                infiniteLoop: true
            });
        }

        else {
            $('.slider1').bxSlider({
                slideWidth: 390,
                minslides : 1,
                maxSlides: 3,
                moveSlides: 1,
                slideMargin: 5,
                speed: 1000,
                auto: true,
                pause: 6000,
                prevText : '',
                nextText : '',
                slideMargin : 10,
                touchEnabled : true,
                infiniteLoop: true
            });
        }
    });
}(jQuery));


