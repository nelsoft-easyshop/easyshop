
(function($){    
    
    $("#login_username, #login_password").click(function(){
        $("#deactivatedAccountPrompt").css("display","none");
    });
    
    
    $(document).ready(function(){
        if($("#loginFail").val() != '' && parseInt($("#timeoutLeft").val()) > 0){
            $("p#lockoutDuration").html("Timeout Remaining: " + $("#timeoutLeft").val());
            $("#failed-login").show();
            $("#login-form").hide();
        }
    });
    
    
    $(document).on('click','#sendReactivationLink',function (e) {
        $('#login')[0].disabled = true;        
        $('#loading_img_activate').show();
        $("#deactivatedAccountPrompt").css("display","none");            
        $('#login_error').hide();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');  
        var username = $("#login_username").val();
        var password = $("#login_password").val();
        $.ajax({
            type: 'post',
            data: {username:username, password:password, csrfname : csrftoken},
            url: "/memberpage/sendDeactivateNotification",
            success: function(data) {
                $('#login')[0].disabled = false;                        
                var obj = jQuery.parseJSON(data);   
                $('#loading_img_activate').hide();
                $('#login_error').show();                
                if(obj !== "Incorrect Password") {
                    $("#login_username").val("");
                    $("#login_password").val("");
                    $("#deactivatePassword").val("");                        
                    $("#login_error").html("<span style='color:green'>Please check your email for the verification link we've just sent to complete the reactivation process of your account. We are looking forward to serving you again! Happy Shopping!</span>");


                }
                else{
                    $("#login_error").html(obj);
                }                

            },
        });         
    });        
    
    $(document).ready(function(){
        
        $("#login_form").validate({
            rules: {
                login_username: {
                    required: true
                },
                login_password: {
                    required: true
                }
            },
            messages: {
                login_username: {
                    required: 'Username is required.'
                },
                login_password: {
                    required: 'Password is required.'
                }
            },
            errorElement: "span",
            errorPlacement: function (error, element) {
                if (element.attr('name') === 'login_username') {
                    error.appendTo($('#username_error'));
                }
                else {
                    error.appendTo($('#passw_error'));
                }
            },
            submitHandler: function (form) {
                $('#loading_img').show();
                $('#login').hide();
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "/login/authenticate",
                    data: $(form).serializeArray(),
                    success:function(data){
                        if(data.timeoutLeft >= 1){
                            $("p#lockoutDuration").html("Timeout Remaining: " + data.timeoutLeft);
                            $("#failed-login").show();
                            $("#login-form").hide();
                        }
                        else{
                            if(data.o_success <= 0){
                                
                                $("#login_error").empty();
                                if(data["o_message"] == "Account Deactivated") {
                                    $("#deactivatedAccountPrompt").css("display","block");
                                    $("#deactivatedAccountPrompt").find("a").attr("data-id",data["errors"][0]["id"]);
                                }
                                else {
                                    $("#login_error").html(data["o_message"] );
                                }
                                $('#loading_img').hide();
                                $('#login').show();
                            }
                            else{
                                $('.error_cont').text('');
                                $('#login_error').text('');
                                $('#loading_img').hide();
                                $('#login').val('Redirecting...');
                                $('#login')[0].disabled = true;
                                $('#login').show();

                                var url = $('#redirect_url').val();
                                var first_uri_segment = url.substring(0, url.indexOf('/'));
                                var vendorSubscriptionUri = $.cookie('es_vendor_subscribe');

                                if( typeof vendorSubscriptionUri !== "undefined" ){
                                    window.location = '/' + vendorSubscriptionUri;
                                }
                                else if (first_uri_segment == 'promo') {
                                    var code = url.split("/");
                                    window.location = '/' + first_uri_segment + '/ScratchCard/claimScratchCardPrize?code=' + code[4];
                                }
                                else{
                                    if((url == 'sell/step1')||(first_uri_segment == 'item')|| (url == 'cart')){
                                        window.location = '/' + url;
                                    }
                                    else if(first_uri_segment == 'cart'){
                                        window.location = '/' + first_uri_segment;
                                    }
                                    else{
                                        window.location = '/';
                                    }                            
                                }
                            }
                        }
                    }
                });
                return false;
            }
        });
        
        $('.login_box input').on('focus', function(){
            $('#login_error').text('');
        });
    });
    
})(jQuery);

