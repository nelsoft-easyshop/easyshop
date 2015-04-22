(function ($) {
    
    var remainingTimeMillisecond = $('#remainingTime').val() * 1000;
    var currentTime = new Date();
    var endDate = new Date(currentTime.getTime() + remainingTimeMillisecond);
    
    $('#table-countdown').countdown(endDate).on('update.countdown', function(event) {
        var $this = $(this).html(event.strftime(''
            + '<td class="td-time-num"><span class="span-time-num">%D</span><span class="span-time-label">DAYS</td></td>'
            + '<td class="td-time-num"><span class="span-time-num">%H</span><span class="span-time-label">HOURS</td></td>'
            + '<td class="td-time-num"><span class="span-time-num">%M</span><span class="span-time-label">MINUTES</td></td>'
            + '<td class="td-time-num"><span class="span-time-num">%S</span><span class="span-time-label">SECONDS</td></td>'));
    }).on('finish.countdown', function(event) {
        window.location.reload();
    });
    
    $('#top').click(function(){
        $("html, body").animate({ scrollTop: "0px" },'slow');
        return false;
    });

    $("a[rel^='lightwindow']").prettyPhoto({deeplinking:false});

    $('.flexslider').flexslider({
        animation : 'slide',
        directionNav : false,
        controlNav : true
    });
  
    $('.fullslideshow').flexslider({
        animation : 'slide',
        directionNav : false,
        controlNav : true,
        animationSpeed : 1500,
        slideshowSpeed : 7000,
        smoothHeight: true,
        start: function(slider) {
        fullslideshow_calc_captions();
        fullslideshow_height(slider.currentSlide, 0);
        },
        before: function(slider) {
        fullslideshow_calc_captions();
        fullslideshow_height(slider.animatingTo, 1);
        },
        after: function(slider) {
        fullslideshow_height(slider.currentSlide, 0);
        }
    });
    
    $(".navbar .nav > li:not('.logo') > a, .footer_links a").append('<span class="underline"></span>');
    
    $('.navbar .nav > li > a, .footer_links a').hover(function(){
        $(this).children('span.underline').animate({'width':'100%', 'margin-left':'0'}, {easing: 'easeOutExpo'}, {queue:false});
    }, function(){
        $(this).children('span.underline').stop().animate({'opacity':'0'}, function(){
        $(this).css({'width':'0', 'margin-left':'50%', 'opacity':'1'});
        });
    });

    /* Waypoints */
    
    $('.load-animate').waypoint({
        triggerOnce: true,
        offset: '70%',
        handler: function() {
        $(this).addClass('animated fadeInUp');
        }
    });
})(jQuery);

function fullslideshow_height (slide, reset) {
    "use strict";

    var height_slide = $('.fullslideshow .slides li:eq(' + (slide+1) + ') img').height();
    var height_caption = $('.fullslideshow .captions li:eq(' + slide + ')').height();
    var top = Math.round(height_slide/2 - height_caption/2) + 'px';

    var caption_top = $('.fullslideshow .captions li:eq(' + slide + ') .caption-top').css('height');
    var caption_bottom = $('.fullslideshow .captions li:eq(' + slide + ') .caption-bottom').css('height');

    var caption_top_all = $('.fullslideshow .caption-top *');
    var caption_bottom_all = $('.fullslideshow .caption-bottom *');
    var caption_li = $('.fullslideshow .captions li');

    if ( reset===1 ) {
        caption_top_all.animate({'margin-top': caption_top, 'opacity':'0'}, { duration : 250, easing : 'easeInOutQuart' });
        caption_bottom_all.animate({'margin-top': '-'+caption_bottom, 'opacity':'0'}, { duration : 250, easing : 'easeInOutQuart' });
        caption_li.animate({'opacity' : 0}, function(){ jQuery(this).css({'top' : top}); });
    } 
    else {
        caption_li.eq(slide).filter(':not(:animated)').css({'top': top}).animate({'opacity' : 1}, { duration : 250, complete:function(){
            caption_top_all.animate({'margin-top': '0', 'opacity': '1'}, { duration : 1000, easing : 'easeInOutQuart' });
            caption_bottom_all.animate({'margin-top': '0', 'opacity': '0.9'}, { duration : 1000, easing : 'easeInOutQuart' });
        }});
    }
}

function fullslideshow_calc_captions () {
    "use strict";

    $('.fullslideshow .captions li').each(function(i){
        var caption_top = $(this).children('.caption-top').height();
        var caption_bottom = $(this).children('.caption-bottom').height();

        $('.fullslideshow .captions li:eq(' + i + ') .caption-top').css({'height': caption_top});
        $('.fullslideshow .captions li:eq(' + i + ') .caption-bottom').css({'height': caption_bottom});
    });
}


(function ($) {
    $('.btn-primary').click(function() {

        $(".error").hide();
        var hasError = false;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var emailaddressVal = $("#useremail").val();

        if(emailaddressVal == '') {
            $(".newsletter-info-blank").show();
            $(".newsletter-validate, .newsletter-info").hide();
            hasError = true;
        }

        else if(!emailReg.test(emailaddressVal)) {
            $(".newsletter-validate").show();
            $(".newsletter-info-blank, .newsletter-info").hide();
            hasError = true;
        }

        if(hasError == true) { 
            return false;
        }

        else {
            
            $('#register').submit();
            $(".newsletter-info").show();
            $(".newsletter-validate, .newsletter-info-blank").hide();
            
            return false;
        }
    });
})(jQuery); 
