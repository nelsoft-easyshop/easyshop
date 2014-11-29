

$(document).ready(function(){

    $('.mid_slide1').bxSlider({
      mode: 'horizontal',
      auto:true,
      autoControls: true,
      pause: 3500,
      controls:false,
      slideWidth: 587
    });

    $('.mid_slide2').bxSlider({
      slideWidth: 160,
       minSlides: 2,
        maxSlides: 3,
        moveSlides: 1,
        slideMargin: 0,
      infiniteLoop:true,
      autoControls: false,
      pager:false
    });

    $('.countdown_slides').bxSlider({
      slideWidth: 220,
       minSlides: 3,
        maxSlides: 4,
        moveSlides: 2,
        slideMargin: 0,
      infiniteLoop:true,
      autoControls: false,
      pager:false
    });

    $('.slider3').bxSlider({
      slideWidth: 486,
       minSlides: 1,
        maxSlides: 1,
        moveSlides: 1,
        slideMargin: 0,
      infiniteLoop:true,
      autoControls: false,
      pager:false
    });

    $('.bx-wrapper').addClass('slide_arrows');

    //middle content top slides
    $('.mid_slide1').parent('.bx-viewport').addClass('mid_top_slides');

     //middle content countdown slides
    $('.countdown_slides').parent('.bx-viewport').parent('.bx-wrapper').addClass('countdown_slides_wrapper');

    //middle content bottom slides
    $('.mid_slide2').parent('.bx-viewport').parent('.bx-wrapper').addClass('mid_bottom_slides');
    $('.mid_slide2').parent('.bx-viewport').addClass('inner_mid_bottom_slides');

    //electronics slides
    $('.slider3').parent('.bx-viewport').addClass('electronic_slides');

    //side navigation menu slides
    $('.slides_prod').parent('.bx-viewport').addClass('side_menu_slides');
    $('.side_menu_slides').parent('.bx-wrapper').addClass('side_menu_nav_slides');
    $('.side_menu_nav_slides').children('.bx-controls').addClass('side_menu_nav_arrow');

    $(document).ready(function() {
    
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
        
      
    });
});