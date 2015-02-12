(function ($) {

    $(".btn-add-cart").on("click", function(){
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var $button = $(this);
        var productId = $button.data('productid');
        var slug = $button.data('slug');
         
        $.ajax({
            type: "POST",
            url: "cart/doAddItem", 
            dataType: "json",
            data: "express=true&"+csrfname+"="+csrftoken+"&productId="+productId,
            success: function(result) {
                if(!result.isLoggedIn){
                    window.location.replace("/login");
                }
                else if(result.isSuccessful){
                    window.location.replace("/cart");
                }
                else{
                    window.location.replace("/item/"+slug);
                }
            }, 
        });
    });

    $(window).on('load',function() {
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

    var  slideCat1 = $('.purchased-items-slider-cat-1.owl-carousel');
        slideCat1.owlCarousel({
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
        }).data('navigationBtns', ['#purchased-items-slider-prev-cat-1', '#purchased-items-slider-next-cat-1']);
        
        var  slideCat2 = $('.purchased-items-slider-cat-2.owl-carousel');
        slideCat2.owlCarousel({
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
        }).data('navigationBtns', ['#purchased-items-slider-prev-cat-2', '#purchased-items-slider-next-cat-2']);
        
        var  slideCat3 = $('.purchased-items-slider-cat-3.owl-carousel');
        slideCat3.owlCarousel({
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
        }).data('navigationBtns', ['#purchased-items-slider-prev-cat-3', '#purchased-items-slider-next-cat-3']);
        
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

    // $('#content .bx-wrapper').removeAttr("style");
    // $('#content .bx-wrapper ul li').removeAttr("style");

        
    $(window).on('load resize', function() {
        var windowWidth = $(window).width(),
        bxSliderWidth = $('#bxslider').width(),
        bxSliderHeight = $('#bxslider').height(),
        shadowWidth = (windowWidth - bxSliderWidth) / 2 ;

        $('.left-side-shadow, .right-side-shadow').css({'width': shadowWidth, 'height': bxSliderHeight});

    });

    $('.sub-class').on('click', function() {
        var $thisObj = $(this);
        var sectionId = $thisObj.data('section-count');
        var products = $('.product-' + $thisObj.data('id')).map(function () {return $(this).data('slug');}).get().join("~");
        var url = '/home/getSubCategoryProductBySlug';
        var csrftoken = $("meta[name='csrf-token']").attr('content');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url : url,
            data : {csrfname : csrftoken, productSlug : products},
            beforeSend: function() {
                $('#category-' + sectionId).find('.owl-wrapper').html('');
            },
            success: function(data) {
                $('#category-' + sectionId).find('.owl-wrapper').html(data.html);
            }

        });
    });

}(jQuery));
