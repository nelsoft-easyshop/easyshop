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
    
    var isRunning = false;
    $('.refresh-captcha').on('click', function(){
        if(isRunning){
            return true;
        }
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $.ajax({
            url: '/home/refreshBugReportCaptcha',
            method: 'POST',
            data: {csrfname: csrftoken},
            beforeSend: function( xhr ) {
                isRunning = true;
            },
            success : function(data) {
                $('.captcha-image-container').html(data);
            },
            complete: function( xhr ) {
                isRunning = false;
            }
        });
    });

})(jQuery);
