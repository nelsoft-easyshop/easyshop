
$(document).ready(function(){
    $(".search_box").css('display','none');
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
         errorPlacement: function(error, element) {
				if(element.attr('name') === 'login_username'){
					error.appendTo($('#username_error'));
				}
				else{
					error.appendTo($('#passw_error'));
				}
         },
         submitHandler: function(form) {
			$('#loading_img').show();
			$('#login').hide();
            $.ajax({
                async: false,
                type : "POST",
                dataType: "JSON",
                url : config.base_url + "login/authenticate",
                data : $(form).serializeArray(),
                success:function(data){
                    if(data.o_success <= 0){
                        //$("#login_error").empty();
                        //$("#login_error").html(data[3]);
						$("#passw_error").empty();
                        $("#passw_error").html(data[3]);
						$('#loading_img').hide();
						$('#login').show();
                    }
                    else{
                        $('#loading_img').hide();
                        $('#login').val('Redirecting...');
                        $('#login').show();
                        var url = $('#redirect').val();
                        window.location = config.base_url+'home';
                        /*
                        if(url.length > 0){
                            window.location = config.base_url+url;
                        }else{
                            window.location = config.base_url+'home';
                        }*/
                    }
                }
            });
            return false;
         }
    });
     
    $('.login_box input').on('click', function(){
        //$('.error_cont').text('');
		$(this).siblings('.error_cont').text('');
    });

});
