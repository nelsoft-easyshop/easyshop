$(document).ready(function(){

    $('.mid_slide1').bxSlider({
      mode: 'horizontal',
      auto:true,
      autoControls: true,
      pause: 3500,
      controls:false,
      slideWidth: 510
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

    $('.slider3').bxSlider({
      slideWidth: 452,
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