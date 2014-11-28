jQuery(document).ready(function() {
    "use strict";

    jQuery('#top').click(function(){
        jQuery("html, body").animate({ scrollTop: "0px" },'slow');
        return false;
    });

    jQuery("a[rel^='lightwindow']").prettyPhoto({deeplinking:false});

    jQuery('.flexslider').flexslider({
        animation : 'slide',
        directionNav : false,
        controlNav : true
    });
  
    jQuery('.fullslideshow').flexslider({
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
    
    jQuery(".navbar .nav > li:not('.logo') > a, .footer_links a").append('<span class="underline"></span>');
    
    jQuery('.navbar .nav > li > a, .footer_links a').hover(function(){
        jQuery(this).children('span.underline').animate({'width':'100%', 'margin-left':'0'}, {easing: 'easeOutExpo'}, {queue:false});
    }, function(){
        jQuery(this).children('span.underline').stop().animate({'opacity':'0'}, function(){
        jQuery(this).css({'width':'0', 'margin-left':'50%', 'opacity':'1'});
        });
    });

    /* Waypoints */
    
    jQuery('.load-animate').waypoint({
        triggerOnce: true,
        offset: '70%',
        handler: function() {
        jQuery(this).addClass('animated fadeInUp');
        }
    });
    
    /* Newsletter Form */

    var newsletter_form = jQuery('.newsletter-form');

    newsletter_form.submit(function() {
        var form_data = jQuery(this).serialize();
        var email = jQuery('.newsletter-form input').prop('value');
        if (validateEmail(email)) {
        jQuery.post(jQuery(this).attr('action'), form_data, function() {
            newsletter_form.css({'background' : 'none'});
            jQuery('.newsletter-fields, .newsletter-validate, .newsletter-form fieldset').fadeOut('fast');
            jQuery('.newsletter-info').fadeIn('fast');
        });
        } else {
            jQuery('.newsletter-validate').fadeIn('fast');
        }
        return false;
    });

});

function fullslideshow_height (slide, reset) {
    "use strict";

    var height_slide = jQuery('.fullslideshow .slides li:eq(' + (slide+1) + ') img').height();
    var height_caption = jQuery('.fullslideshow .captions li:eq(' + slide + ')').height();
    var top = Math.round(height_slide/2 - height_caption/2) + 'px';

    var caption_top = jQuery('.fullslideshow .captions li:eq(' + slide + ') .caption-top').css('height');
    var caption_bottom = jQuery('.fullslideshow .captions li:eq(' + slide + ') .caption-bottom').css('height');

    var caption_top_all = jQuery('.fullslideshow .caption-top *');
    var caption_bottom_all = jQuery('.fullslideshow .caption-bottom *');
    var caption_li = jQuery('.fullslideshow .captions li');

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

    jQuery('.fullslideshow .captions li').each(function(i){
        var caption_top = jQuery(this).children('.caption-top').height();
        var caption_bottom = jQuery(this).children('.caption-bottom').height();

        jQuery('.fullslideshow .captions li:eq(' + i + ') .caption-top').css({'height': caption_top});
        jQuery('.fullslideshow .captions li:eq(' + i + ') .caption-bottom').css({'height': caption_bottom});
    });
}

Modernizr.load([
    { load : '//connect.facebook.net/en_US/all.js#xfbml=1' },
    { load : '//platform.twitter.com/widgets.js' },
    { load : '//assets.pinterest.com/js/pinit.js' }
]);

/* validate email */ 
function validateEmail(a){
    "use strict";
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}

