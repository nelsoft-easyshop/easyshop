(function ($) {

    $('#content').on("click", ".btn-add-cart" , function(){
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var $button = $(this);
        var productId = $button.data('productid');
        var slug = $button.data('slug');
        
        addToCart(productId, null, null, true, slug);
    });
    
    $(window).bind('load', function(){
        setTimeout(function(){
            mainwindowsize = $(window).width();
            if (mainwindowsize < 565) {
                $('.bxslider').bxSlider({
                    minslides : 1,
                    maxSlides: 1,
                    moveSlides: 1,
                    speed: 1000,
                    pause: 6000,
                    prevText : '',
                    nextText : '',
                    touchEnabled : true,
                    infiniteLoop: true
                });
            }

            else {
                $('.bxslider').bxSlider({
                    minslides : 1,
                    maxSlides: 2,
                    moveSlides: 1,
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
        }, 300);
    });

    //Sort random function
    function random(owlSelector){
        owlSelector.children().sort(function(){
            return Math.round(Math.random()) - 0.5;
        }).each(function(){
            $(this).appendTo(owlSelector);
        });
    }
    
    $("#featured-seller").owlCarousel({
            pagination: false,
            items: 3,
            itemsDesktop : [1199,3],
            itemsDesktopSmall: [979,2],
            itemsTablet: [768,2],
            itemsMobile : [479,2],
            navigation: false,
            beforeInit : function(elem){
                random(elem);
            }
    });
    
    var owl = $("#featured-seller");

    $(".next").click(function(){
        owl.trigger('owl.next');
    })
    
    $(".prev").click(function(){
        owl.trigger('owl.prev');
    })

    for (var count = 1; count <= $('.row-category').length; count++) {
        var slideCat = $('.purchased-items-slider-cat-' + count + '.owl-carousel');
        slideCat.owlCarousel({
            items: 4,
            itemsDesktop : [1199,3],
            itemsDesktopSmall: [979,2],
            itemsTablet: [768,2],
            itemsMobile : [479,2],
            slideSpeed: 400,
            autoPlay: 8000,
            stopOnHover: true,
            navigation: false,
            pagination: false,
            responsive: true,
            mouseDrag: true,
            autoHeight : true
        }).data('navigationBtns', ['#purchased-items-slider-prev-cat-' + count, '#purchased-items-slider-next-cat-' + count]);
    }

        var  featuredCarousel = $('.featured-products-carousel');
        featuredCarousel.owlCarousel({
            items: 4,
            itemsDesktop : [1199,3],
            itemsDesktopSmall: [979,2],
            itemsTablet: [768,2],
            itemsMobile : [479,2],
            slideSpeed: 400,
            autoPlay: 8000,
            stopOnHover: true,
            navigation: false,
            pagination: false,
            responsive: true,
            mouseDrag: true,
            autoHeight : true
        }).data('navigationBtns', ['#featured-items-slider-prev', '#featured-items-slider-next']);

        var  popularBrands = $('.popular-brands.owl-carousel');
        popularBrands.owlCarousel({
            items: 8,
            itemsDesktop : [1199,8],
            itemsDesktopSmall: [979,8],
            itemsTablet: [768,4],
            itemsMobile : [479,2],
            slideSpeed: 400,
            autoPlay: 8000,
            stopOnHover: true,
            navigation: false,
            pagination: false,
            responsive: true,
            mouseDrag: true,
            autoHeight : true
        }).data('navigationBtns', ['#popular-brand-prev', '#popular-brand-next']);
        
        
        $('.owl-carousel').each(function() {
            var $this = $(this),
                owlCarousel = $this.data('owlCarousel'),
                owlBtns = $this.data('navigationBtns'),
                prevBtn, nextBtn;

            if (typeof owlCarousel === 'undefined' || typeof owlBtns === 'undefined') {
                return;
            }

            for(var key in owlBtns) {
                if (owlBtns[key].indexOf('next') == -1) {
                    prevBtn = $(owlBtns[key]);
                }else {
                    nextBtn = $(owlBtns[key]);
                }
            }

            prevBtn.on('click touchstart', function(e) {
                owlCarousel.prev();
                e.preventDefault();
            });

            nextBtn.on('click touchstart', function(e) {
                owlCarousel.next();
                e.preventDefault();
            });
        });


    $(window).on('load resize', function() {
        var windowWidth = $(window).width(),
        bxSliderWidth = $('#bxslider').width(),
        bxSliderHeight = $('#bxslider').height(),
        shadowWidth = (windowWidth - bxSliderWidth) / 2 ;

        $('.left-side-shadow, .right-side-shadow').css({'width': shadowWidth, 'height': bxSliderHeight});

    });
    
        
    $('.featured-categories .btn-tag').on('click', function(){
        var $this = $(this);
        var headerCount = $this.data('subcounter');
        var sectionId = $this.data('section-id');
        var $container = $('#category-' + sectionId);
        var productSlugs = $container.find('.product-slugs-' + headerCount).val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        if(!$this.hasClass('btn-tag-active')) {
            var productView = $container.find('.product-view-' + headerCount).html(); 
            var currentlyActiveTab = $container.find('.featured-categories .btn-tag-active');
            var activeHeaderCounter = currentlyActiveTab.data('subcounter');
            
            if ($.trim(productView) === "") {
                $.ajax({
                    type: "POST",
                    url: "/home/getCategorySectionProducts",
                    dataType: "json",
                    data: "productSlugs="+productSlugs+"&csrfname="+csrftoken,
                    success: function(result) {
                        $container.find('.product-view-' + headerCount).html(result);
                        $container.find('.purchased-items-slider-cat-' + sectionId).html(result);
                        $container.find('.purchased-items-slider-cat-' + sectionId).data('owlCarousel').reinit({});
                        currentlyActiveTab.removeClass('btn-tag-active');
                        $this.addClass('btn-tag-active');
                    }
                });
            }
            else {
                $container.find('.purchased-items-slider-cat-' + sectionId).html(productView);
                $container.find('.purchased-items-slider-cat-' + sectionId).data('owlCarousel').reinit({});
                currentlyActiveTab.removeClass('btn-tag-active');
                $this.addClass('btn-tag-active');
            }

        }

    });
    
    
    $(document).ready(function(){
        $('.hide-owlcarousel-div').each(function(){
            var owlCarouselSectionId = $(this).data('sectioncount');
            $('.purchased-items-slider-cat-' + owlCarouselSectionId).css('display','none')
        });
    });
                                 
                  
    
}(jQuery));
