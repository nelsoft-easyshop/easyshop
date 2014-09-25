(function(){
    
    $(function () {
        $("[rel='tooltip']").tooltip();
    });
    
    $('.tab-content').on('click','.pagination-items li', function(){

        var page = $(this).data('page');
        var memberid = $('#memberid').val();
        var $tabPane = $(this).closest('.tab-pane');
        var tab = $tabPane.attr('id');
        $.ajax({
            url: '/home/feedback',
            method: 'GET',
            data: {page:page, memberid:memberid, tab:tab},
            success : function(data) {
                $tabPane.replaceWith(data);
            }
            
        });
    });
    
    $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        scrollDistance: 300, // Distance from top/bottom before showing element (px)
        scrollFrom: 'top', // 'top' or 'bottom'
        scrollSpeed: 300, // Speed back to top (ms)
        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 100, // Animation in speed (ms)
        animationOutSpeed: 100, // Animation out speed (ms)
        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
        scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
        scrollImg: false, // Set true to use image
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647, // Z-Index for the overlay
    });

    $( ".fa-edit-icon" ).click(function() {
        $(".input-detail").css("display","inline");
        $(".fa-cancel-edit").css("display","inline");
        $("#save-edit").css("display","inline");
        $(".fa-edit-icon").css("display","none");
        $(".text-contact").css("display","none");
     });
    
     $( ".fa-cancel-edit" ).click(function() {
        $(".input-detail").css("display","none");
        $(".fa-cancel-edit").css("display","none");
        $("#save-edit").css("display","none");
        $(".fa-edit-icon").css("display","inline");
        $(".text-contact").css("display","inline");
     });
    
    $( "#ab" ).click(function() {
        $(this).css("color","#fe893a");
        $("#as").css("color","#575759");
        $("#fob").css("color","#575759");
        $("#fos").css("color","#575759");
    });
    $( "#as" ).click(function() {
        $("#ab").css("color","#575759");
        $(this).css("color","#fe893a");
        $("#fob").css("color","#575759");
        $("#fos").css("color","#575759");
    });
    $( "#fob" ).click(function() {
        $("#ab").css("color","#575759");
        $("#as").css("color","#575759");
        $(this).css("color","#fe893a");
        $("#fos").css("color","#575759");
    });
    $( "#fos" ).click(function() {
        $("#ab").css("color","#575759");
        $("#as").css("color","#575759");
        $("#fob").css("color","#575759");
        $(this).css("color","#fe893a");
    });
    //FOR RATING DEMO ONLY
     $( ".i5" ).mouseover(function() {
        $(".i1").css("color","#fbd022");
        $(".i2").css("color","#fbd022");
        $(".i3").css("color","#fbd022");
        $(".i4").css("color","#fbd022");
        $(".i5").css("color","#fbd022");
    });
     $( ".i5" ).click(function() {
        $(".i1").addClass(" star-active");
        $(".i2").addClass(" star-active");
        $(".i3").addClass(" star-active");
        $(".i4").addClass(" star-active");
        $(".i5").addClass(" star-active");
    });
    $( ".i5" ).mouseout(function() {
        $(".i1").css("color","#d4d4d4");
        $(".i2").css("color","#d4d4d4");
        $(".i3").css("color","#d4d4d4");
        $(".i4").css("color","#d4d4d4");
        $(".i5").css("color","#d4d4d4");
    });
    $( ".i4" ).mouseover(function() {
        $(".i1").css("color","#fbd022");
        $(".i2").css("color","#fbd022");
        $(".i3").css("color","#fbd022");
        $(".i4").css("color","#fbd022");
    });
    $( ".i4" ).click(function() {
        $(".i1").addClass(" star-active");
        $(".i2").addClass(" star-active");
        $(".i3").addClass(" star-active");
        $(".i4").addClass(" star-active");
        $(".i5").removeClass(" star-active");
    });
    $( ".i4" ).mouseout(function() {
        $(".i1").css("color","#d4d4d4");
        $(".i2").css("color","#d4d4d4");
        $(".i3").css("color","#d4d4d4");
        $(".i4").css("color","#d4d4d4");
    });
    $( ".i3" ).mouseover(function() {
        $(".i1").css("color","#fbd022");
        $(".i2").css("color","#fbd022");
        $(".i3").css("color","#fbd022");
    });
    $( ".i3" ).click(function() {
        $(".i1").addClass(" star-active");
        $(".i2").addClass(" star-active");
        $(".i3").addClass(" star-active");
        $(".i4").removeClass(" star-active");
        $(".i5").removeClass(" star-active");
    });
    $( ".i3" ).mouseout(function() {
        $(".i1").css("color","#d4d4d4");
        $(".i2").css("color","#d4d4d4");
        $(".i3").css("color","#d4d4d4");
    });
    $( ".i2" ).mouseover(function() {
        $(".i1").css("color","#fbd022");
        $(".i2").css("color","#fbd022");
    });
    $( ".i2" ).click(function() {
        $(".i1").addClass(" star-active");
        $(".i2").addClass(" star-active");
        $(".i3").removeClass(" star-active");
        $(".i4").removeClass(" star-active");
        $(".i5").removeClass(" star-active");
    });
    $( ".i2" ).mouseout(function() {
        $(".i1").css("color","#d4d4d4");
        $(".i2").css("color","#d4d4d4");
    });
    $( ".i1" ).mouseover(function() {
        $(".i1").css("color","#fbd022");
    });
     $( ".i1" ).click(function() {
        $(".i1").addClass(" star-active");
        $(".i2").removeClass(" star-active");
        $(".i3").removeClass(" star-active");
        $(".i4").removeClass(" star-active");
        $(".i5").removeClass(" star-active");
    });
    $( ".i1" ).mouseout(function() {
        $(".i1").css("color","#d4d4d4");
    });
    $( ".c-5" ).mouseover(function() {
        $(".c-1").css("color","#fbd022");
        $(".c-2").css("color","#fbd022");
        $(".c-3").css("color","#fbd022");
        $(".c-4").css("color","#fbd022");
        $(".c-5").css("color","#fbd022");
    });
     $( ".c-5" ).click(function() {
        $(".c-1").addClass(" star-active");
        $(".c-2").addClass(" star-active");
        $(".c-3").addClass(" star-active");
        $(".c-4").addClass(" star-active");
        $(".c-5").addClass(" star-active");
    });
    $( ".c-5" ).mouseout(function() {
        $(".c-1").css("color","#d4d4d4");
        $(".c-2").css("color","#d4d4d4");
        $(".c-3").css("color","#d4d4d4");
        $(".c-4").css("color","#d4d4d4");
        $(".c-5").css("color","#d4d4d4");
    });
    $( ".c-4" ).mouseover(function() {
        $(".c-1").css("color","#fbd022");
        $(".c-2").css("color","#fbd022");
        $(".c-3").css("color","#fbd022");
        $(".c-4").css("color","#fbd022");
    });
    $( ".c-4" ).click(function() {
        $(".c-1").addClass(" star-active");
        $(".c-2").addClass(" star-active");
        $(".c-3").addClass(" star-active");
        $(".c-4").addClass(" star-active");
        $(".c-5").removeClass(" star-active");
    });
    $( ".c-4" ).mouseout(function() {
        $(".c-1").css("color","#d4d4d4");
        $(".c-2").css("color","#d4d4d4");
        $(".c-3").css("color","#d4d4d4");
        $(".c-4").css("color","#d4d4d4");
    });
    $( ".c-3" ).mouseover(function() {
        $(".c-1").css("color","#fbd022");
        $(".c-2").css("color","#fbd022");
        $(".c-3").css("color","#fbd022");
    });
    $( ".c-3" ).click(function() {
        $(".c-1").addClass(" star-active");
        $(".c-2").addClass(" star-active");
        $(".c-3").addClass(" star-active");
        $(".c-4").removeClass(" star-active");
        $(".c-5").removeClass(" star-active");
    });
    $( ".c-3" ).mouseout(function() {
        $(".c-1").css("color","#d4d4d4");
        $(".c-2").css("color","#d4d4d4");
        $(".c-3").css("color","#d4d4d4");
    });
    $( ".c-2" ).mouseover(function() {
        $(".c-1").css("color","#fbd022");
        $(".c-2").css("color","#fbd022");
    });
    $( ".c-2" ).click(function() {
        $(".c-1").addClass(" star-active");
        $(".c-2").addClass(" star-active");
        $(".c-3").removeClass(" star-active");
        $(".c-4").removeClass(" star-active");
        $(".c-5").removeClass(" star-active");
    });
    $( ".c-2" ).mouseout(function() {
        $(".c-1").css("color","#d4d4d4");
        $(".c-2").css("color","#d4d4d4");
    });
    $( ".c-1" ).mouseover(function() {
        $(".c-1").css("color","#fbd022");
    });
     $( ".c-1" ).click(function() {
        $(".c-1").addClass(" star-active");
        $(".c-2").removeClass(" star-active");
        $(".c-3").removeClass(" star-active");
        $(".c-4").removeClass(" star-active");
        $(".c-5").removeClass(" star-active");
    });
    $( ".c-1" ).mouseout(function() {
        $(".c-1").css("color","#d4d4d4");
    });
    $( ".s-5" ).mouseover(function() {
        $(".s-1").css("color","#fbd022");
        $(".s-2").css("color","#fbd022");
        $(".s-3").css("color","#fbd022");
        $(".s-4").css("color","#fbd022");
        $(".s-5").css("color","#fbd022");
    });
     $( ".s-5" ).click(function() {
        $(".s-1").addClass(" star-active");
        $(".s-2").addClass(" star-active");
        $(".s-3").addClass(" star-active");
        $(".s-4").addClass(" star-active");
        $(".s-5").addClass(" star-active");
    });
    $( ".s-5" ).mouseout(function() {
        $(".s-1").css("color","#d4d4d4");
        $(".s-2").css("color","#d4d4d4");
        $(".s-3").css("color","#d4d4d4");
        $(".s-4").css("color","#d4d4d4");
        $(".s-5").css("color","#d4d4d4");
    });
    $( ".s-4" ).mouseover(function() {
        $(".s-1").css("color","#fbd022");
        $(".s-2").css("color","#fbd022");
        $(".s-3").css("color","#fbd022");
        $(".s-4").css("color","#fbd022");
    });
    $( ".s-4" ).click(function() {
        $(".s-1").addClass(" star-active");
        $(".s-2").addClass(" star-active");
        $(".s-3").addClass(" star-active");
        $(".s-4").addClass(" star-active");
        $(".s-5").removeClass(" star-active");
    });
    $( ".s-4" ).mouseout(function() {
        $(".s-1").css("color","#d4d4d4");
        $(".s-2").css("color","#d4d4d4");
        $(".s-3").css("color","#d4d4d4");
        $(".s-4").css("color","#d4d4d4");
    });
    $( ".s-3" ).mouseover(function() {
        $(".s-1").css("color","#fbd022");
        $(".s-2").css("color","#fbd022");
        $(".s-3").css("color","#fbd022");
    });
    $( ".s-3" ).click(function() {
        $(".s-1").addClass(" star-active");
        $(".s-2").addClass(" star-active");
        $(".s-3").addClass(" star-active");
        $(".s-4").removeClass(" star-active");
        $(".s-5").removeClass(" star-active");
    });
    $( ".s-3" ).mouseout(function() {
        $(".s-1").css("color","#d4d4d4");
        $(".s-2").css("color","#d4d4d4");
        $(".s-3").css("color","#d4d4d4");
    });
    $( ".s-2" ).mouseover(function() {
        $(".s-1").css("color","#fbd022");
        $(".s-2").css("color","#fbd022");
    });
    $( ".s-2" ).click(function() {
        $(".s-1").addClass(" star-active");
        $(".s-2").addClass(" star-active");
        $(".s-3").removeClass(" star-active");
        $(".s-4").removeClass(" star-active");
        $(".s-5").removeClass(" star-active");
    });
    $( ".s-2" ).mouseout(function() {
        $(".s-1").css("color","#d4d4d4");
        $(".s-2").css("color","#d4d4d4");
    });
    $( ".s-1" ).mouseover(function() {
        $(".s-1").css("color","#fbd022");
    });
     $( ".s-1" ).click(function() {
        $(".s-1").addClass(" star-active");
        $(".s-2").removeClass(" star-active");
        $(".s-3").removeClass(" star-active");
        $(".s-4").removeClass(" star-active");
        $(".s-5").removeClass(" star-active");
    });
    $( ".s-1" ).mouseout(function() {
        $(".s-1").css("color","#d4d4d4");
    });
    
    $( ".fa-edit-about" ).click(function() {
        $(".div-about-edit-area").css("display","inline");
        $(".p-about").css("display","none");
        $(".fa-cancel-about").css("display","inline");
        $(".fa-edit-about").css("display","none");
    });
    
    $( ".fa-cancel-about" ).click(function() {
        $(".div-about-edit-area").css("display","none");
        $(".p-about").css("display","inline");
        $(".fa-cancel-about").css("display","none");
        $(".fa-edit-about").css("display","inline");
    });
    
    
    
})(jQuery);
