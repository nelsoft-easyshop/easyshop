(function ($) {
    $(document).ready (function(){
        var isValid = $("#validFlag").attr('val')
        $("#success-alert").hide();
        
        if(isValid){
            $("#success-alert").show();
            $("#success-alert").fadeTo(5000, 500).slideUp(500, function(){
                $("#success-alert").hide();
            });   
        }

        $("#bug-report").click(function(){
            $("div.error.red").hide();
        });
        

    });
    
    $('.refresh-captcha').on('click', function(){
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $.ajax({
            url: '/home/refreshBugReportCaptcha',
            method: 'POST',
            data: {csrfname: csrftoken},
            success : function(data) {
                $('.captcha-image-container').html(data);
            }
        });
    });

})(jQuery);
