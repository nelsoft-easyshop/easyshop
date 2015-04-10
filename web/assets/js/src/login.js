
(function($){    
    
    $("#login_username, #login_password").click(function(){
        $("#deactivatedAccountPrompt").css("display","none");
    });
    
    
    $(document).ready(function(){
        
        $('#tab-login , #tab-create').on('click', function(){
            $('.login-throttle').hide();
        });
        
        $('#login-try-again').on('click', function(){
            $('#login_username').val('');
            $('#login_password').val('');
            $('.login-throttle').hide();
            $('#login').fadeIn();
            $('#passw_error').hide();
        });
        
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
            beforeSend: function(){
                $('.login-btn').val('Please wait...');
            },
            success: function(data) {
                $('.login-btn').val('Login');
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

            }
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
                    required: '<span class="input-error">Username is required.</span>'
                },
                login_password: {
                    required: '<span class="input-error">Password is required.</span>'
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
                $('#login').show();
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "/login/authenticate",
                    data: $(form).serializeArray(),
                    beforeSend: function(){
                        $('.login-btn').val('Please wait...');
                    },
                    success:function(data){                        
                        $('.login-btn').val('Login');
                        if(data.timeoutLeft >= 1){
                            $('#login').hide();
                            $('.login-throttle').fadeIn();
                            $('#login-timeout').html(data.timeoutLeft);
                        }
                        else{
                            var $loginErrorContainer = $("#login_error");
                            if(data.o_success <= 0){
                                $loginErrorContainer.empty();
                                if(data['o_message'] === 'Account Banned'){
                                    var officeHours = $('#office_hours').val();
                                    var officeContactno = $('#office_contactno').val();
                                    var message = data['errors'][0]['message'];
                                    var messageSpan =  document.createElement("span");
                                    var newContent = document.createTextNode(message); 
                                    messageSpan.appendChild(newContent);
                                    $loginErrorContainer.append(messageSpan);
                                    var linebreak = document.createElement("br");
                                    $loginErrorContainer.append(linebreak);
                                    linebreak = document.createElement("br");
                                    $loginErrorContainer.append(linebreak);
                                    message = "Contact our Customer Service Support for further details: " + officeHours + " " +  officeContactno;
                                    newContent = document.createTextNode(escapeHtml(message)); 
                                    messageSpan =  document.createElement("span");
                                    messageSpan.appendChild(newContent);
                                    $loginErrorContainer.append(messageSpan);
                                }
                                else if(data["o_message"] == "Account Deactivated") {
                                    $("#deactivatedAccountPrompt").css("display","block");
                                    $("#deactivatedAccountPrompt").find("a").attr("data-id",data["errors"][0]["id"]);
                                }
                                else {
                                    $loginErrorContainer.html(data["o_message"] );
                                }
                                $('#loading_img').hide();
                                $('#login').show();
                            }
                            else{
                                $('.error_cont').text('');
                                $loginErrorContainer.text('');
                                $('#loading_img').hide();
                                $('#login')[0].disabled = true;
                                $('#login').show();
                                $('.login-btn').val('Redirecting...');

                                var url = $('.referrer').val();      
                                var first_uri_segment = url.substring(0, url.indexOf('/'));
                                var vendorSubscriptionUri = $.cookie('es_vendor_subscribe');

                                if( typeof vendorSubscriptionUri !== "undefined" ){
                                    window.location = '/' + vendorSubscriptionUri;
                                }
                                else if (first_uri_segment === 'promo') {
                                    var code = url.split("/");
                                    window.location = '/' + first_uri_segment + '/ScratchCard/claimScratchCardPrize?code=' + code[4];
                                }
                                else if (url === 'estudyantrepreneur') {
                                    window.location = '/' + url;
                                }
                                else{
                                    if((url === 'sell/step1')||(first_uri_segment === 'item')|| (url === 'cart')){
                                        window.location = '/' + url;
                                    }
                                    else if(first_uri_segment === 'cart'){
                                        window.location = '/' + first_uri_segment;
                                    }
                                    else{
                                        window.location = '/';
                                    }                            
                                }
                            }
                        }
                    },
                    error: function(xhr, error) {
                        $('.login-btn').val('Login');
                        $('#login').show();
                        var $loginErrorContainer = $("#login_error");
                        $loginErrorContainer.html('Ooops, we are currently experiencing a problem. Please refresh the page and try again.');
                    }            
                });
                return false;
            }
        });
        
        $('.login_box input').on('focus', function(){
            $('#login_error').text('');
        });
    });
    
    $(document).ready(function(){
        var $accountBannedElement = $('#account-banned-error');
        var isAccountBanned = $accountBannedElement.val();
        if(isAccountBanned){
            alert(escapeHtml($accountBannedElement.data('message')));
        }
    });
    
    var $window = $(window);
    $window.on('load resize', function() {

        setTimeout(function(){
            $(".login-loading-content").hide();
            $(".login-hide-content").fadeIn();
            
            var pathName = window.location.pathname.substring(1);
            if (pathName === "login") {
                $('#tab-login').trigger('click');
            }
            else if (pathName === "register") {
                $('#tab-create').trigger('click');
                $("#login").hide();
            }

        }, 300);

        setTimeout(function(){
            var windowsHeight = $(window).height();
            var logincontainer = $(".new-login-register-content").outerHeight();
            var loginHeight = (windowsHeight - logincontainer)/2;

            if (logincontainer <= windowsHeight) {
                $(".new-login-register-content").animate({
                    'margin-top': loginHeight,
                    'margin-bottom' : '20px'
                }, 300);
            }
            else {
                $(".new-login-register-content").css({
                    'margin-top' : '20px',
                    'margin-bottom' : '20px'
                });
            }
        }, 1000);
    });

    $(".login-hide-content").hide();

    $('#password').pwstrength();

})(jQuery);

