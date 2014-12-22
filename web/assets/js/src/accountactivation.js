(function ($) {


    var loadingimg = $('img#deactivateAccountLoader'); 
    var verifyspan = $('.activateActions');
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');  
     $("#activateAccountForm").validate({
         rules: {
            username: {
                required: true,
                },            
            password: {
                required: true,
                },
            confirmPassword: {
                required: true,
                equalTo: '#password'
                }
         },
         messages:{
            confirmPassword: {
                equalTo: 'Both of your entered passwords must match'
            },
         },
         errorElement: "span",
         errorPlacement: function(error, element) {
            error.addClass("val-error");
            error.appendTo(element.parent());
                      
         },
         submitHandler: function(form, event){
            $('#password-change-error').hide();
            verifyspan.hide();
            loadingimg.show();                                         
            event.preventDefault();
            var postData = $("#activateAccountForm").serializeArray();
            $.ajax({
                type: 'get',
                data: postData,
                url: "/memberpage/doReactivateAccount",                
                success: function(data) {   
                    
                    try{              
                        var obj = jQuery.parseJSON(data); 
                        if(obj.result === "success") {
                            login($("#username").val(),$("#password").val());
                        }
                        else {
                            $('#password-change-error').fadeIn();
                        }
                    }
                    catch(e){                         
                        $('#invalidCredentials').modal({       
                            onShow: function(){
                                $(".invalidCredentialsMessage").html("An error was encountered while processing your data. Please try again later.");
                            }
                         });                        
                    }                    
                    verifyspan.show();
                    loadingimg.hide();   
                },
            });            
        }
     });
    
    function login(username, password)
    {
        $.ajax({
            type: "post",
            data: {login_form:"submit",login_username:username, login_password:password, csrfname : csrftoken},
            url: "/login/authenticate",                
            success: function(data) {
                verifyspan.show();
                loadingimg.hide();                 
                $('#activated-modal').modal({       
                    onClose: function(){
                        window.location = "/";
                    }
                 });
            },
        });          
    }



})( jQuery );
