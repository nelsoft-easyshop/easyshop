
(function($) {

    $(document).ready(function() {
        $('.jqzoom').jqzoom({
                zoomType: 'innerzoom',
        });
        
        $("#mobile-product-gallery").owlCarousel({
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
            autoPlay : false,
            singleItem : true,
            autoHeight : true,
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

            var imgthumb= $(".cycle-carousel-wrap > a").length;
            if (imgthumb <= 4) {
                $(".center a:eq(1)").addClass("btn-disabled");
                $(".center").addClass("disable-next");
            }

            var prodcongalwidth = $('.prod-gallery-container').width();
            var zoompadimg= $('.zoomPad img[title="product"]');
            zoompadimg.css({
                "max-width": prodcongalwidth
            });

            $(".zoomPad").css("width", zoompadimg.width());
            $(".zoomWindow").css("width", zoompadimg.width());
            }, 500);
        });

        $('.footer-primary').addClass('footer-secondary');
    });
 

})(jQuery);

