(function ($) {
    $(document).ready(function(){
         $("#forgotpass").validate({
             rules: {               
                password: {
                    required: true,
                    minlength: 6,
                    maxlength:25,
                    alphanumeric: true,
                    },
                cpassword: {
                    required: true,
                    minlength: 6,
                    maxlength:25,
                    equalTo: '#password'
                    }
             },
             messages:{
                cpassword:{
                    equalTo: ''
                }
             },
             errorElement: "span",
             errorPlacement: function(error, element) {
                    error.addClass('red');
                    if(element.attr('name') == 'password'){
                        var added_span = $('<span/>',{'class':"red"});
                        error.insertBefore(element.next());
                        added_span.insertBefore(element.next());
                    }else{
                        error.appendTo(element.parent());
                    }
             }
            
         });
         
        $('.field input').on('click', function(){
            $('.ci_form_validation_error').text('');
         });
    });

    $(document).ready(function(){ 
        var redurl = '/login/resetconfirm?&tgv=';
        $( "#forgotpass_btn" ).click(function() {
            if($("#forgotpass").valid()){       
                currentRequest = jQuery.ajax({
                    type: "POST",
                    url: '/login/xresetconfirm', 
                    data: $("#forgotpass").serialize(), 
                    beforeSend : function(){       
                    },
                    success: function(response){
                        $("#password, #cpassword").val('');
                        $("#tgv").val(response);
                        $('#fp_complete').submit();
                    }
                });
            }
        }); 
    });
})(jQuery);

