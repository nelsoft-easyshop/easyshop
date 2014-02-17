
$(document).ready(function(){
    $(".search_box").css('display','none');
});

$(document).ready(function(){
    
   $("#login_form").validate({
         rules: {
            login_username: {
                required: true,
                },
            login_password: {
                required: true,
                },
         },
         messages: {
            login_username: {
                required: 'Username is required.'
                },
            login_password: {
                required: 'Password is required.',
                },
         },
         errorElement: "p",
         errorPlacement: function(error, element) {
                error.addClass('red_center');
                error.appendTo($('#login_error').parent());
         },
         submitHandler: function(form) {
            $.ajax({
                async: false,
                type : "POST",
                dataType: "JSON",
                url : config.base_url + "login/authenticate",
                data : $(form).serializeArray(),
                success:function(data){
                    if(data.o_success <= 0){
                        $("#login_error").empty();
                        $("#login_error").append("<span style='color:red'>"+data[3]+"</span>");
                    }
                    else{
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
        $('#login_error').text('');
    });

});
