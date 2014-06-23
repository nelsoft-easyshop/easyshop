

$(document).ready(function(){
    var timer_date = new Date($('#timer_date').val());
    $('.cd_timer_container').countdown({
        until : timer_date,
        serverSync: serverTime,
        layout: ' <div class="cd_timer_days"><span id="countdown_days">{dnn}</span> <span>DAYS</span> </div>'+
                ' <div class="cd_timer_hours"><span id="countdown_hours">{hnn}</span> <span>HOURS</span> </div>'+
                ' <div class="cd_timer_minutes"><span id="countdown_minutes">{mnn}</span> <span>MINUTES</span> </div>' +
                ' <div class="cd_timer_seconds"><span id="countdown_second">{snn}</span> <span>SECONDS</span> </div>',
        onExpiry: reload,
    });

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
});