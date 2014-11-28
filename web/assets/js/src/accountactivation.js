(function ($) {


    var loadingimg = $('img#deactivateAccountLoader'); 
    var verifyspan = $('.activateActions');
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');  

    /********************* ACTIVATE ACCOUNT ****************************/

     $("#activateAccountForm").validate({
         rules: {
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
         submitHandler: function(form){

            verifyspan.hide();
            loadingimg.show();                          
            event.preventDefault();
            var postData = $("#activateAccountForm").serializeArray();
            $.ajax({
                type: 'get',
                data: postData,
                url: "/memberpage/activateAccount",                
                success: function(data) {                      
                    var obj = jQuery.parseJSON(data); 
                    if(obj.result === "success") {
                        login(obj.username,$("#password").val());
                    }
                    else {
                        alert("Invalid Password");
                    }
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
                $('#activated-modal').modal();
            },
        });          
    }

    /********************* END ACTIVATE ACCOUNT ****************************/

})( jQuery );
