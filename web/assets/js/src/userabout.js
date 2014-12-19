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
            url: '/store/feedback',
            method: 'GET',
            data: {page:page, memberid:memberid, tab:tab},
            success : function(data) {
                $tabPane.replaceWith(data);
            }
            
        });
    });
    
    $(function(){
        
        $('#feedback-select').on('change', function(){
             $(this).removeClass("input-error");
             $('#feedback-order-error-icon').hide();
        });
        
        $('#feedback-message').on('keyup', function(){
             $(this).removeClass("input-error");
        });
        
        $( "#feedback-form" ).submit(function( event ) {
            $feedbackSelect = $('#feedback-select');
            $feedbackMessage = $('#feedback-message');
            $rating1 = $("#input-rating-header1");
            $rating2 = $("#input-rating-header2");
            $rating3 = $("#input-rating-header3");
            var hasError = false;
            if($.trim($feedbackMessage.val()).length == 0){
                $feedbackMessage.addClass("input-error");
                hasError = true;
            }
            
            if($feedbackSelect.val() == 0){
                $feedbackSelect.addClass("input-error");
                $('#feedback-order-error-icon').show();
                hasError = true;
            }

            if($rating1.val() == 0 || $rating2.val() == 0 || $rating3.val() == 0){
                $('#feedback-star-error').removeClass('hide');
                console.log('angular');
                hasError = true;
            }
            
            if(hasError){
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
        scrollText: '', // Text for element, can contain HTML
        scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
        scrollImg: false, // Set true to use image
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647, // Z-Index for the overlay
    });
    
    $( "#ab" ).click(function() {
        $(this).addClass('tab-active-link');
        $("#as").removeClass('tab-active-link');
        $("#fob").removeClass('tab-active-link');
        $("#fos").removeClass('tab-active-link');;
    });
    $( "#as" ).click(function() {
        $("#ab").removeClass('tab-active-link');
        $(this).addClass('tab-active-link');
        $("#fob").removeClass('tab-active-link');
        $("#fos").removeClass('tab-active-link');
    });
    $( "#fob" ).click(function() {
        $("#ab").removeClass('tab-active-link');
        $("#as").removeClass('tab-active-link');
        $(this).addClass('tab-active-link');
        $("#fos").removeClass('tab-active-link');
    });
    $( "#fos" ).click(function() {
        $("#ab").removeClass('tab-active-link');
        $("#as").removeClass('tab-active-link');
        $("#fob").removeClass('tab-active-link');
        $(this).addClass('tab-active-link');
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
        $('#feedback-star-error').addClass('hide');
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
        $(".p-about").css("display","block");
        $(".fa-cancel-about").css("display","none");
        $(".fa-edit-about").css("display","inline");
    });
    

    $('.feedback-from-seller').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).siblings(".feedback-cat-mobile").removeClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideToggle();
         $('html, body').animate({
            scrollTop: $(this).offset().top
        }, 500);
    });
    
    $('.feedback-from-buyer').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).siblings(".feedback-cat-mobile").removeClass("active-bar",0);
        $('.feedback-mobile-2').slideToggle();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideUp();
         $('html, body').animate({
            scrollTop: $(this).offset().top
        }, 500);
    });
    
    $('.feedback-for-seller').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).siblings(".feedback-cat-mobile").removeClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideToggle();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideUp();
         $('html, body').animate({
            scrollTop: $(this).offset().top
        }, 500);
    });
    
    $('.feedback-for-buyer').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).siblings(".feedback-cat-mobile").removeClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideToggle();
        $('.feedback-mobile-1').slideUp();
         $('html, body').animate({
            scrollTop: $(".feedback-for-buyer").offset().top
        }, 500);
    });
})(jQuery);
