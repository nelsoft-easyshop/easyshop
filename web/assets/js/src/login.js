
(function($){    

    $(document).ready(function(){
        $("#failed-login").hide();
        $(".search_box").css('display','none');
        if($("#loginFail").val() != '' && parseInt($("#timeoutLeft").val()) > 0){
            $("p#lockoutDuration").html($("#timeoutLeft").val());
            $("#failed-login").show();
            $("#login-form").hide();
        }
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
                    url: config.base_url + "login/authenticate",
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
                                $("#login_error").html(data[3]);
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
                                    window.location = config.base_url + vendorSubscriptionUri;
                                }
                                else{
                                    if((url == 'sell/step1')||(first_uri_segment == 'item')|| (url == 'cart')){
                                        window.location = config.base_url+ url;
                                    }
                                    else if(first_uri_segment == 'cart'){
                                        window.location = config.base_url + first_uri_segment;
                                    }
                                    else{
                                        window.location = config.base_url;
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

