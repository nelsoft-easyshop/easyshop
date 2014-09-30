(function(){
    
    $(function() {
        if($('#open-description').val() == 'true'){
            $( ".fa-edit-about" ).click();
        }
    });
    
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
    
    $(function(){
        
        $('#feedback-select').on('change', function(){
             $feedbackSelect.removeClass("input-error");
             $('#feedback-order-error-icon').hide();
        });
        
        $( "#feedback-form" ).submit(function( event ) {
            $feedbackSelect = $('#feedback-select');
            if($feedbackSelect.val() == 0){
                $feedbackSelect.addClass("input-error");
                event.preventDefault();
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
    
    
    
    $(".feedback-ratings .fa-star-rate").mouseover(function(){
        $(this).css("color","#fbd022");  
        $( this ).prevAll().css("color","#fbd022");  
    });
    
    
    $( ".feedback-ratings .fa-star-rate" ).mouseout(function() {
        $(this).css("color","#d4d4d4");  
        $( this ).prevAll().css("color","#d4d4d4");  
    });
    
      
    $( ".feedback-ratings .fa-star-rate" ).click(function() {
        var ratingHeaderCount = $(this).closest('.rating-header').attr('id');
        $('#input-'+ratingHeaderCount).val($(this).data('number'));
        $(this).addClass("star-active"); 
        $(this).prevAll().addClass("star-active"); 
        $(this).nextAll().removeClass("star-active");
    });
    

    
    
    $( ".fa-edit-about" ).click(function() {
        $(".div-about-edit-area").css("display","inline");
        $(".p-about").css("display","none");
        $(".fa-cancel-about").css("display","inline");
        $(".fa-edit-about").css("display","none");
    });
    
    $( ".fa-cancel-about" ).click(function() {
        var $description = $('#description');
        $description.val($description.data('value'));
        $(".div-about-edit-area").css("display","none");
        $(".p-about").css("display","inline");
        $(".fa-cancel-about").css("display","none");
        $(".fa-edit-about").css("display","inline");
    });
    

    
})(jQuery);
