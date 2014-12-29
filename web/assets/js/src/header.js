(function ($) {

    $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        scrollDistance: 300, // Distance from top/bottom before showing element (px)
        scrollFrom: 'top', // 'top' or 'bottom'
        scrollSpeed: 300, // Speed back to top (ms)
        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 100, // Animation in speed (ms)
        animationOutSpeed: 100, // Animation out speed (ms)
        scrollText: '', // Text for element, can contain HTML
        scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
        scrollImg: false, // Set true to use image
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647, // Z-Index for the overlay
    });
    

    $(document).ready(function() {
        
        
        $('.main-search-input').on('keyup', function(){
            var searchString = $(this).val();
            $('.main-search-input').val(searchString);
        });
    });

/* ================================================
----------- Vendor ---------- */

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

/*
    function stickyMenu() {
        var windowTop = $(window).scrollTop(),
            windowWidth = checkWindowWidth(),
            header = $('#header'),
            navContainer = $('#main-nav-container'),
            navDist = navContainer.offset().top,
            headerHeight = header.height();
                
        if (windowTop >= navDist && windowTop > headerHeight&& windowWidth > 768) {
            navContainer.addClass('fixed');
        }
        else {
            navContainer.removeClass('fixed');
        }
    }

    $(window).on('scroll resize', stickyMenu);
*/


        var menuOffset = $('.sticky-header-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
        var browserWidth = $(window).width();

        $(document).bind('ready scroll resize', function() {
            var docScroll = $(document).scrollTop();
            if ((docScroll >= 180) && (browserWidth > 991)) {
                if (!$('.sticky-header-nav').hasClass('sticky-nav-fixed')) {
                    $('.sticky-header-nav').addClass('sticky-nav-fixed').css({
                        top: '-155px'
                    }).stop().animate({
                        top: 0
                    }, 200);
                }

            } 

            else {
                $('.sticky-header-nav').removeClass('sticky-nav-fixed').removeAttr('style');
            }
        });

        $(".suggested-result-container").hide();
 
        var $primarySearch= $("#primary-search");
        var $suggestedResult= $(".suggested-result-container");

        $(document).mouseup(function (e) {

            if (!$suggestedResult.is(e.target) 
                && $suggestedResult.has(e.target).length === 0)
            {
               $suggestedResult.hide(1);
            }
        });

        $("#primary-search").on('click input keypress',function() {

            if($(this).val().length >= 3) {
                $(".suggested-result-container").slideDown(300);
            } 
            if ($(this).val().length <= 2) {
                 $(".suggested-result-container").slideUp(300);
            }
        });

        var $primarySearch2= $("#primary-search2");
        var $suggestedResult2= $(".suggested-result-container2");

        $(document).mouseup(function (e) {

            if (!$suggestedResult2.is(e.target) 
                && $suggestedResult2.has(e.target).length === 0)
            {
               $suggestedResult2.hide(1);
            }
        });

            $("#primary-search2").on('click input keypress',function() {
            if($(this).val().length >= 3) {
                 $(".suggested-result-container2").slideDown(300);
            } 
            if ($(this).val().length <= 2) {
                 $(".suggested-result-container2").slideUp(300);
            }
        });

        $(".suggested-result-container2").hide();

 
    /* initially hide product list items */
    $("#suggested-search-result li a").hide();
 
    /* highlight matches text */
    var highlight = function (string) {
        $("#suggested-search-result li a.match").each(function () {
            var matchStart = $(this).text().toLowerCase().indexOf("" + string.toLowerCase() + "");
            var matchEnd = matchStart + string.length - 1;
            var beforeMatch = $(this).text().slice(0, matchStart);
            var matchText = $(this).text().slice(matchStart, matchEnd + 1);
            var afterMatch = $(this).text().slice(matchEnd + 1);
            $(this).html(beforeMatch + "<strong>" + matchText + "</strong>" + afterMatch);
        });
    };
 
 
    /* filter products */
    $("#primary-search").on("keyup click input", function () {
        if (this.value.length > 0) {
            $("#suggested-search-result li a").removeClass("match").hide().filter(function () {
                return $(this).text().toLowerCase().indexOf($("#primary-search").val().toLowerCase()) != -1;
            }).addClass("match").show();
            highlight(this.value);
            $("#suggested-search-result").show();
        }
        else {
            $("#suggested-search-result, #suggested-search-result li a").removeClass("match").hide();
        }
    });

    $("#suggested-search-result2 li a").hide();

    var highlight2 = function (string) {
        $("#suggested-search-result2 li a.match2").each(function () {
            var matchStart = $(this).text().toLowerCase().indexOf("" + string.toLowerCase() + "");
            var matchEnd = matchStart + string.length - 1;
            var beforeMatch = $(this).text().slice(0, matchStart);
            var matchText = $(this).text().slice(matchStart, matchEnd + 1);
            var afterMatch = $(this).text().slice(matchEnd + 1);
            $(this).html(beforeMatch + "<strong>" + matchText + "</strong>" + afterMatch);
        });
    };
 
 
    /* filter products */
    $("#primary-search2").on("keyup click input", function () {
        if (this.value.length > 0) {
            $("#suggested-search-result2 li a").removeClass("match2").hide().filter(function () {
                return $(this).text().toLowerCase().indexOf($("#primary-search2").val().toLowerCase()) != -1;
            }).addClass("match2").show();
            highlight2(this.value);
            $("#suggested-search-result2").show();
        }
        else {
            $("#suggested-search-result2, #suggested-search-result2 li a").removeClass("match2").hide();
        }
    });

}(jQuery));

function proceedPayment(obj)
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    $.ajax({
        async: true,
        url: "/payment/cart_items",
        type: "POST",
        dataType: "json",
        data: {csrfname: csrftoken},
        success: function (data) {
            if (data == true) {
                window.location.replace("/payment/review");
            } else {
                alert(data, 'Remove these items from your cart to proceed with your checkout.');
            }
        }
    });
}
