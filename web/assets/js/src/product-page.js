(function ($){
    $( ".p-reply-text" ).click(function() {
        $( ".div-reply-container" ).toggle("slow");
        $( ".text-cancel" ).toggle("fade");
    });
    
    $( ".p-reply-text-replied-item" ).click(function() {
        $( ".div-reply-container-replied-item" ).toggle("slow");
        $( ".text-cancel-replied-item" ).toggle("fade");
    });
    
    $(document).ready(function() {
        var recommended = $("#recommended");
        recommended.owlCarousel({
            items : 4,
            itemsCustom : false,
            itemsDesktop : [1199,4],
            itemsDesktopSmall : [980,3],
            itemsTablet: [768,2],
            itemsTabletSmall: false,
            itemsMobile : [479,2],
            pagination: false,
            singleItem : false,
            itemsScaleUp : false
        });
        $(".next").click(function(){
            recommended.trigger('owl.next');
        })
        $(".prev").click(function(){
            recommended.trigger('owl.prev');
        })
    });
    
})(jQuery);