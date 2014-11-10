
(function($) {

    $(document).ready(function() {
        $('.jqzoom').jqzoom({
                zoomType: 'standard',
        });
        
        $("#mobile-product-gallery").owlCarousel({
            itemsTablet: [768,2],
            itemsMobile : [479,1],
            responsive: true,
            responsiveRefreshRate : 200,
            responsiveBaseWidth: window,
            pagination : true,
            navigation : true,
            navigationText : ["prev","next"],
            scrollPerPage : false,
            dragBeforeAnimFinish : true,
            mouseDrag : true,
            touchDrag : true,
            navigation : true,
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
                $('.owl-item div').each(function () {
                    var parentWidth = $(this).width();
                    if ($(this).find('img').length) {
                        $(this).find('img').css( 'maxWidth', parentWidth)
                    }
                });
            }, 500);
        });

        $('.footer-primary').addClass('footer-secondary');
    });
 

})(jQuery);

