
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
                type: "POST",
                dataType: "JSON",
                url: config.base_url + "login/authenticate",
                data: $(form).serializeArray(),
                success:function(data){
                    if(data.o_success <= 0){
						$("#login_error").empty();
                        $("#login_error").html(data[3]);
						$('#loading_img').hide();
						$('#login').show();
                    }
                    else{
						var curl = $.cookie('rn');
                        $('.error_cont').text('');
						$('#login_error').text('');
                        $('#loading_img').hide();
                        $('#login').val('Redirecting...');
                        $('#login')[0].disabled = true;
                        $('#login').show();

                        var url = $('#redirect_url').val();
                        var first_uri_segment = url.substring(0, url.indexOf('/'));
                        if((url == 'sell/step1')||(first_uri_segment == 'item')){
                            window.location = config.base_url+ url;
                        }
                        else{
                            window.location = config.base_url+ 'home';
                        }                        
                    }
                }
            });
            return false;
         }
    });
     
    $('.login_box input').on('click', function(){
		$('#login_error').text('');
    });

});
