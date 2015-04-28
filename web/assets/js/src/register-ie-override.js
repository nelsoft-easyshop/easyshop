(function( $ ) {

    $(document).ready(function(){

        $("#username").off('input');
        $("#username").on('keyup', function(){
            var fieldlength = $.trim(jQuery('#username').val()).length;
            hidecheckx($(this));
            $('.username_availability').html('');
            
            $(this).removeClass('pass');
            
            if(!$(this).hasClass('forSearch') && fieldlength >= 5){
                $(this).addClass('forSearch');
            }
            else if(fieldlength < 5){
                $(this).removeClass('forSearch');
                hidecheckx($('#username'));
                $('.username_availability').html('');
            }
        });
        
        $("#email").off('input');
        $("#email").on('keyup', function(){
            var fieldlength = jQuery.trim(jQuery('#email').val()).length;
            hidecheckx(jQuery(this));
            jQuery('.email_availability').html('');
            
            jQuery(this).removeClass('pass');
            
            if(!jQuery(this).hasClass('forSearch') && fieldlength >= 6){
                jQuery(this).addClass('forSearch');
            }
            else if(fieldlength < 6){
                jQuery(this).removeClass('forSearch');
                hidecheckx(jQuery('#email'));
                jQuery('.email_availability').html('');
            }
        });

    });

})(jQuery);

