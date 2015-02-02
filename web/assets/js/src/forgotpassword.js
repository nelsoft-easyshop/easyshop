(function ($) {
    
    $(document).ready(function(){
        $('#email, #password').on('click', function(){
            $('.response-dialog').fadeOut();
        });
    });
    
    $(document).ready(function(){
        
        var minimumPasswordLength = parseInt($('#min-length-password').val());
        
        $('#password').on('keyup', function(){
            var $this = $(this);
            if($.trim($(this).val()).length >= minimumPasswordLength){
                $('#confirmpassword').attr("disabled", false);
            }
            else{
                $('#confirmpassword').attr("disabled", true);
                $('#confirmpassword').val("");
            }
        });
        
         $("#update-password").validate({
            rules: {               
                password: {
                    required: true,
                    minlength: 6
                },
                confirmpassword: {
                    required: true,
                    minlength: 6,
                    equalTo: '#password'
                }
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
               error.addClass('red');
               error.appendTo(element.parent());
            }
        });

         
        var options = {
            minChar: minimumPasswordLength,
            bootstrap3: true,
        };
        $('#password').pwstrength(options);
    
    });
    
    

})(jQuery);

