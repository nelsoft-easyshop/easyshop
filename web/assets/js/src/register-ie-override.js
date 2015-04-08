(function( $ ) {

    $(document).ready(function(){https://docs.google.com/a/easyshop.ph/document/d/1kmp0qLQ4y_MS6zA8Ck4zajJklWmIACF92Grpg47-hdA/edit

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

    });

})(jQuery);

