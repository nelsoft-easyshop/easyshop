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
})(jQuery);
