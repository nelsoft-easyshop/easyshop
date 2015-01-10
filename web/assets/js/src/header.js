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
                    $('.autocomplete-suggestions').hide();
                }

            } 
            else {
                $('.nav-suggestion').hide();
                $('.sticky-header-nav').removeClass('sticky-nav-fixed').removeAttr('style');
            }
        });

        var hideSuggestion = function(){ 
            $('.nav-suggestion').css({
                top: $('#primary-search2').offset().top + $('#primary-search2').outerHeight(),
                left: $('#primary-search2').offset().left,
                width: $('#primary-search2').outerWidth()
            });
        }

        $(window).on('scroll', hideSuggestion);

        var $minChars = 3;

        $('#primary-search')
            .autoComplete({
                minChars: $minChars,
                cache: false,
                menuClass: 'autocomplete-suggestions main-nav',
                source: function(term, response){ 
                    try { 
                        xhr.abort(); 
                    } catch(e){}
                    var xhr = $.ajax({ 
                        type: "get",
                        url: '/search/suggest',
                        data: "query=" + term,
                        dataType: "json", 
                        success: function(data){
                            if(data.length <= 0){
                                $('.autocomplete-suggestions').empty();
                                response([]); 
                            }
                            else{
                                response(data); 
                            } 
                        }
                    });
                },
                onSelect: function(term){
                    $('#primary-search').addClass('selectedClass');
                }
            }) 
            .focus(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.main-nav').html() ).length ) {
                            hideSuggestion();
                            $('.main-nav').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .click(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.main-nav').html() ).length ) {
                            hideSuggestion();
                            $('.main-nav').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .focusout(function() {
                $('.nav-suggestion').html($('.main-nav').html());
            })
            .change(function() {
                if($(this).val().length <= 0){
                    $('.autocomplete-suggestions').empty();
                }
            })
            .keyup(function() {
                var searchString = $(this).val();
                $('#primary-search2').val(searchString);
            });

        $('#primary-search2')
            .autoComplete({
                minChars: $minChars,
                cache: false,
                menuClass: 'autocomplete-suggestions nav-suggestion',
                source: function(term, response){ 
                    try { 
                        xhr.abort(); 
                    } catch(e){}
                    var xhr = $.ajax({ 
                        type: "get",
                        url: '/search/suggest',
                        data: "query=" + term,
                        dataType: "json", 
                        success: function(data){
                            if(data.length <= 0){
                                $('.autocomplete-suggestions').empty();
                                response([]); 
                            }
                            else{
                                response(data); 
                            }
                        }
                    });
                },
                onSelect: function(term){
                    $('#primary-search2').addClass('selectedClass');
                }
            })
            .focusout(function() {
                $('.main-nav').html($('.nav-suggestion').html());
            })
            .focus(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.nav-suggestion').html() ).length ) {
                            hideSuggestion();
                            $('.nav-suggestion').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .click(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.nav-suggestion').html() ).length ) {
                            hideSuggestion();
                            $('.nav-suggestion').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .change(function() {
                if($(this).val().length <= 0){
                    $('.autocomplete-suggestions').empty();
                }
            })
            .keyup(function() {
                var searchString = $(this).val();
                $('#primary-search').val(searchString);
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
