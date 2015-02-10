(function ($) {
    
    $(document).ready(function(){
        $('#email, #password').on('click', function(){
            $('.response-dialog').fadeOut();
        });
    });
    
    $(document).ready(function(){
        
        var minimumPasswordLength = parseInt($('#min-length-password').val());

        $.validator.addMethod("passwordAlphanumeric", function(value, element) {
            var isPassed =  /[a-zA-Z]/i.test(value) && /\d/i.test(value) && !/\s/i.test(value);
            return this.optional(element) || isPassed;
        }, "Must only contain alphanumeric characters with no spaces");
        
        
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
                    minlength: 6,
                    passwordAlphanumeric: true
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
        
        
        $('#password').on('blur', function(){
            var $this = $(this);
            if($this.hasClass('error')){
                $('.progress-bar').css('width', '0');
            }
        });
    
    });
    
    

})(jQuery);

