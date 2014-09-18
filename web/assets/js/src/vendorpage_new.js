(function ($) {

    //create a stick nav
    var menuOffset = $('.vendor-sticky-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
    $(document).bind('ready scroll', function() {
        var docScroll = $(document).scrollTop();
        if (docScroll >= 455) 
            {
                if (!$('.vendor-sticky-nav').hasClass('sticky-nav-fixed')) {
                    $('.vendor-sticky-nav').addClass('sticky-nav-fixed').css({
                        top: '-155px'
                    }).stop().animate({
                        top: 0
                    }, 500);
                    
                }

                $('.vendor-content-wrapper').addClass('fixed-vendor-content');

            } 
        else 
            {
                $('.vendor-sticky-nav').removeClass('sticky-nav-fixed').removeAttr('style');
                $('.vendor-content-wrapper').removeClass('fixed-vendor-content');
            }
    });

    $(document.body).on('click','.icon-grid',function() {
        var view = $("div.view").attr("class");
    
        if(view == "view row row-items list")
        {
            $('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-lg-3 col-md-4 col-xs-6 thumb");
        }    
    });

    $(document).on('click','.icon-list',function() {   
        var view = $("div.view").attr("class");
        if(view == "view row row-items grid")
        {
            $('div.view').removeClass("view row row-items grid").addClass("view row row-items list");
            $('div.col-lg-3').removeClass("col-lg-3 col-md-4 col-xs-6 thumb").addClass("col-md-12 thumb");
        };
    });

    $('.tab_categories').on('click', function(){
        var divId = $(this).attr('data-link');
        $('.div_product').hide();
        $(divId).show();
    });

})(jQuery);

