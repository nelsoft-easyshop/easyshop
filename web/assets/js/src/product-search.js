(function ($) {
    $.stickysidebarscroll("#search-tips-container",{offset: {top:50, bottom: 600}});
    $.stickysidebarscroll("#filter-panel-container",{offset: {top: -60, bottom: 600}});
    
    $('body').attr('data-spy', 'scroll').attr('data-target', '#myScrollspy').attr('data-offset','0');
    $("body").scrollspy({target: "#myScrollspy"});
    
    //determine the search results container reached the bottom 
    var sticky_offset;
    $(document).ready(function() {
        var original_position_offset = $('#sticky-pagination').offset();
        sticky_offset = original_position_offset.top;
        $('#sticky-pagination').css('position', 'fixed').css('width', '64%');
    });

    $(window).scroll(function () {
        var sticky_height = $('#sticky-pagination').outerHeight();
        var where_scroll = $(window).scrollTop();
        var window_height = $(window).height();

        if((where_scroll + window_height) > sticky_offset) {
            $('#sticky-pagination').css('position', 'relative').css('width', '100%');
            $('.search-results-container').css('margin-bottom', '0px');
        }
        
        if((where_scroll + window_height) < (sticky_offset + sticky_height))  {
            $('#sticky-pagination').css('position', 'fixed').css('width', '64%');
            $('.search-results-container').css('margin-bottom', '100px');
        }
        
    });
        
    
    $( ".icon-list" ).click(function() {
        $(this).addClass("active-view");
        $(".icon-grid").removeClass("active-view");
        $('.search-results-container').animate({opacity:0},function(){
            $( ".search-results-container" ).addClass("list-search");
            $( ".col-search-item" ).removeClass("col-xs-3");
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
            $( ".col-search-item" ).addClass("col-xs-3");
            $('.search-results-container').stop().animate({opacity:1},"fast");
        });
    });
}(jQuery));


