(function(){
    
    $(function() {
        if($('#open-description').val() == 'true'){
            $( ".fa-edit-about" ).click();
        }
    });
    
    $(function () {
        $("[rel='tooltip']").tooltip();
    });
    
    $('.tab-content, .feedback-mobile').on('click','.pagination-items li', function(){

        var page = $(this).data('page');
        var memberid = $('#memberid').val();
        
        
        var isMobileView = $(this).closest('.pagination-container').hasClass('mobile-pagination');
        
        var $tabPane = $(this).closest('.tab-pane');
        if(isMobileView){
            var tab = $tabPane.data('identifier'); 
        }
        else{
            var tab = $tabPane.attr('id');   
        }

        $.ajax({
            url: '/store/feedback',
            method: 'GET',
            data: {page:page, memberid:memberid, tab:tab, isMobile: isMobileView},
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
                hasError = true;
            }
            
            if(hasError){
                /**
                 * Work around for IE9
                 */
                if (event.preventDefault) { 
                    event.preventDefault(); 
                } 
                else { 
                    event.returnValue = false;
                }
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
    
    $('.feedback-mobile').on('click', '.feedback-from-seller',  function() {
        $( "#ab" ).trigger("click");
        $(".feedback-cat-mobile").not(".feedback-from-seller").removeClass("active-bar",0);
        $(".feedback-from-seller").addClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideDown();
        $('html, body').animate({
            scrollTop: $(".feedback-from-seller").offset().top
        }, 500);
    });
    
     $( "#ab" ).click(function() {
        $(".feedback-cat-mobile").not(".feedback-from-seller").removeClass("active-bar",0);
        $(".feedback-from-seller").addClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideDown();
     });
    
    $('.feedback-mobile').on('click', '.feedback-from-buyer',  function() {
        $( "#as" ).trigger("click");
        $(".feedback-cat-mobile").not(".feedback-from-buyer").removeClass("active-bar",0);
        $('.feedback-from-buyer').addClass("active-bar",0);
        $('.feedback-mobile-2').slideDown();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideUp();
        $('html, body').animate({
            scrollTop: $(".feedback-from-buyer").offset().top
        }, 500);
    });
    
    $( "#as" ).click(function() {
        $(".feedback-cat-mobile").not(".feedback-from-buyer").removeClass("active-bar",0);
        $(".feedback-from-buyer").addClass("active-bar",0);
        $('.feedback-mobile-2').slideDown();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideUp();
     });
    
    $('.feedback-mobile').on('click', '.feedback-for-seller',  function() {
        $( "#fos" ).trigger("click");
        $(".feedback-cat-mobile").not(".feedback-for-seller").removeClass("active-bar",0);
        $(".feedback-for-seller").addClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-4').slideDown();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-1').slideUp();
        $('html, body').animate({
            scrollTop: $(".feedback-for-seller").offset().top
        }, 500);
    });
    
    $( "#fos" ).click(function() {
        $(".feedback-cat-mobile").not(".feedback-for-seller").removeClass("active-bar",0);
        $(".feedback-for-seller").addClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideUp();
        $('.feedback-mobile-4').slideDown();
        $('.feedback-mobile-1').slideUp();
     });
    
    $('.feedback-mobile').on('click', '.feedback-for-buyer',  function() {
        $( "#fob" ).trigger("click");
        $(".feedback-cat-mobile").not(".feedback-for-buyer").removeClass("active-bar",0);
        $('.feedback-for-buyer').addClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-3').slideDown();
        $('.feedback-mobile-1').slideUp();
        $('html, body').animate({
            scrollTop: $(".feedback-for-buyer").offset().top
        }, 500);
    });
    
     $( "#fob" ).click(function() {
        $(".feedback-cat-mobile").not(".feedback-for-buyer").removeClass("active-bar",0);
        $(".feedback-for-buyer").addClass("active-bar",0);
        $('.feedback-mobile-2').slideUp();
        $('.feedback-mobile-3').slideDown();
        $('.feedback-mobile-4').slideUp();
        $('.feedback-mobile-1').slideUp();
     });
})(jQuery);
