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

/* ================================================
----------- Vendor ---------- */
(function ($) {
    "use strict";
    
    // Check for Mobile device
    var mobileDetected;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        mobileDetected = true;
    } else {
        mobileDetected = false;
    }

    // Check for placeholder support
    jQuery.support.placeholder = (function(){
        var i = document.createElement('input');
        return 'placeholder' in i;
    })();

    // if Placeholder is not supported call plugin
    if (!jQuery.support.placeholder && $.fn.placeholder) {
        $('input, textarea').placeholder();
    }
    

    // function check for window width
    function checkWindowWidth() {
        return $(window).width();
    }
    

/* =========================================
---- Create Responsive Menu
=========================================== */
    var menu = $('.menu').clone(true).removeClass('menu').addClass('responsive-nav'),
        container = $('#responsive-nav');
            
    container.append(menu);
    
    
    
    container.find('li, .col-2, .col-3, .col-4, .col-5').each(function () {

        var $this = $(this);
        
        
        if ($this.hasClass('mega-menu-container')) {
            $this.removeClass('mega-menu-container');
        }

        
        
        $this.has('ul, .megamenu').prepend('<span class="menu-button"></span>');
        
    });
    

    $('span.menu-button').on('click', function () {
        var $this= $(this);
        
        if (! $this.hasClass('active')) {
            $(this)
            .addClass('active')
            .siblings('ul, .mega-menu')
            .slideDown('800');
        }else {
            $(this)
            .removeClass('active')
            .siblings('ul, .mega-menu')
            .slideUp('800');
        }
    });
            

    $('#responsive-nav-button').on('click', function () {
        var $this = $(this);
        
        if( $this.hasClass('active')) {
            $('#responsive-nav').find('.responsive-nav').slideUp(300, function () {
                $this.removeClass('active');
            });
        
        }else {
            $('#responsive-nav').find('.responsive-nav').slideDown(300, function () {
                $this.addClass('active');
            });
        }
    });
    

    // Sub menu show/hide with hoverIntent plugin
    if ($.fn.hoverIntent) {
        $('ul.menu').hoverIntent(function() {
            $(this).children('ul, .mega-menu').fadeIn(100);

        }, function() {
            $(this).children('ul, .mega-menu').fadeOut(50);
        },
        'li');

    } else {

        $('ul.menu').find('li').mouseover(function() {
            $(this).children('ul, .mega-menu').css('display','block');

        }).mouseout(function() {
            $(this).children('ul, .mega-menu').css('display','none');
        });
    }

/* =========================================
---- Sticky Menu
=========================================== */
    
    // function stickyMenu() {
    //     var windowTop = $(window).scrollTop(),
 //            windowWidth = checkWindowWidth(),
 //            header = $('#header'),
 //            navContainer = $('#main-nav-container'),
 //            navDist = navContainer.offset().top,
 //            headerHeight = header.height();
               
 //        if (windowTop >= navDist && windowTop > headerHeight&& windowWidth > 768) {
 //            navContainer.addClass('fixed');
 //        } else {
 //            navContainer.removeClass('fixed');
 //        }
    // }

    // $(window).on('scroll resize', stickyMenu);
//start
    // var menuOffset = $('.sticky-header-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
    // $(document).bind('ready scroll', function() {
        // var docScroll = $(document).scrollTop();
        // if (docScroll >= 180) 
            // {
                // if (!$('.sticky-header-nav').hasClass('sticky-nav-fixed')) {
                    // $('.sticky-header-nav').addClass('sticky-nav-fixed').css({
                        // top: '-155px'
                    // }).stop().animate({
                        // top: 0
                    // }, 100);
                    
                // }

            // } 
        // else 
            // {
                // $('.sticky-header-nav').removeClass('sticky-nav-fixed').removeAttr('style');
            // }

    // });
    
}(jQuery));