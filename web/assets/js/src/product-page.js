(function ($){
    $( ".p-reply-text" ).click(function() {
        $(this).parent().next( ".div-reply-container" ).toggle("slow");
        $(this).find( ".text-cancel" ).toggle("fade");
    });

    $( ".p-reply-text-replied-item" ).click(function() {
        $( ".div-reply-container-replied-item" ).toggle("slow");
        $( ".text-cancel-replied-item" ).toggle("fade");
    });

    $( "#prodDetails" ).click(function() {
        $( "#tdDetails" ).addClass("active");
        $( "#tdReviews" ).removeClass("active");
    });

    $( "#prodReviews" ).click(function() {
        $( "#tdDetails" ).removeClass("active");
        $( "#tdReviews" ).addClass("active");
    });

    // $(".span-star-container .fa-star-rate").mouseover(function(){
    //     $(this).css("color","#fbd022");
    //     $( this ).prevAll().css("color","#fbd022");
    // });

    // $( ".span-star-container .fa-star-rate" ).mouseout(function() {
    //     $(this).css("color","#d4d4d4");
    //     $( this ).prevAll().css("color","#d4d4d4");
    // });

    $( ".span-star-container .fa-star-rate" ).click(function() {
        $(this).css("color","#fbd022");
        $(this).prevAll().css("color","#fbd022");
        $(this).nextAll().css("color","#d4d4d4");
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