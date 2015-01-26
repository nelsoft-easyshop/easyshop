(function ($) {
    $.stickysidebarscroll("#search-tips-container",{offset: {top:50, bottom: 600}});
    $.stickysidebarscroll("#filter-panel-container",{offset: {top: -60, bottom: 100}});
    
    $('body').attr('data-spy', 'scroll').attr('data-target', '#myScrollspy').attr('data-offset','0');
    $("body").scrollspy({target: "#myScrollspy"});     
    
    $( ".icon-list" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-grid").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).addClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-6").removeClass("col-sm-3");
             $( ".col-search-item" ).addClass("col-xs-12");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });
    
    $( ".icon-grid" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-list").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).removeClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-12");
            $( ".col-search-item" ).addClass("col-sm-3").addClass("col-xs-6");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });
}(jQuery));


