
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

	$('.signup_register').on('click', function(){
		var parentDiv = $(this).closest('div.fillup');
		parentDiv.hide();
		$('div.fillup').not(parentDiv).fadeIn(300);
	});
	
});

/**********************************************************************/
/**************************	SIGNUP ************************************/
/**********************************************************************/

$(document).ready(function(){
	  
	  $('#username').on('focus', function() {
		  $(document).bind('focusin.example click.example',function(e) {
			  if ($(e.target).closest('.username_info, #username').length) return;
			  $(document).unbind('.example');
		  });
	  }).on('blur', function(){
		var fieldlength = $.trim($('#username').val()).length;
		if(fieldlength >= 5 && $(this).hasClass('forSearch')){
			setTimeout(username_check,500);
		}
		else if(fieldlength < 5){
			hidecheckx($('#username'));
			$('.username_availability').html('');
		}
	  }).on('keyup', function(){
		var fieldlength = $.trim($('#username').val()).length;
		if(!$(this).hasClass('forSearch') && fieldlength >= 5){
			$(this).addClass('forSearch');
		}
		else if(fieldlength < 5){
			$(this).removeClass('forSearch');
			hidecheckx($('#username'));
			$('.username_availability').html('');
		}
	  });
	  
	  $('div.pass-container').show();
	  $('#password').focus(function() {
		  $(document).bind('focusin.example click.example',function(e) {
			  if ($(e.target).closest('.password_info, #password').length) return;
			  $(document).unbind('.example');
			});
	  }).on('input paste keyup', function(){
			   if($.trim($(this).val()).length >= 6){
				   $('#cpassword').attr("disabled", false);
				   showx($('#cpassword'));
				   }
			   else {
				   $('#cpassword').attr("disabled", true);
			       $('#cpassword').val("");
				   hidecheckx($('#cpassword'));
			   }
			   
				if($(this).val() !== $('#cpassword').val() && !$('#cpassword')[0].disabled)
					showx($('#cpassword'));
				else if($(this).val() == $('#cpassword').val() && !$('#cpassword')[0].disabled)
					showcheck($('#cpassword'));
      });
	  
	  $("#cpassword").on('paste', function(e){
			e.preventDefault();
		}).on('focusin input focusout',function(){
			if($(this).val() !== $('#password').val())
				showx($(this));
			else
				showcheck($(this));
		});
	  
	  $('#email').on('blur', function(){
		var fieldlength = $.trim($('#email').val()).length;
		if(fieldlength >= 6 && $(this).hasClass('forSearch')){
			setTimeout(email_check,500);
		}
		else if(fieldlength < 6){
			hidecheckx($('#email'));
			$('.email_availability').html('');
		}
	  }).on('keyup', function(){
		var fieldlength = $.trim($('#email').val()).length;
		if(!$(this).hasClass('forSearch') && fieldlength >= 6){
			$(this).addClass('forSearch');
		}
		else if(fieldlength < 6){
			$(this).removeClass('forSearch');
			hidecheckx($('#email'));
			$('.email_availability').html('');
		}
	  });
	  
});

/**********************************************************************************************/
/****************************	FORM 1 VALIDATION	*******************************************/
/**********************************************************************************************/	
$(document).ready(function(){

	 jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || (/[a-zA-Z]/.test(value) && /\d/.test(value));
     }, "Must contain numbers and letters"); 
	 
	 jQuery.validator.addMethod("case_all", function(value, element) {
        return this.optional(element) || (/[a-z]/.test(value) && /[A-Z]/.test(value));
     }, "Must contain upper-case and lower-case letters"); 
	 
	 jQuery.validator.addMethod("alphanumeric_underscore", function(value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
	 }, "Only letters, numbers, and underscores are allowed");
 
	 $("#register_form1").validate({
		 rules: {
            username: {
                required: true,
                minlength: 5,
                maxlength:25,
				alphanumeric_underscore: true,
				equalTo: '#usernamecheck'
				},
			password: {
				required: true,
                minlength: 6,
                maxlength:25,
				alphanumeric: true,
				//case_all: true
				},
			cpassword: {
				required: true,
				minlength: 6,
                maxlength:25,
				equalTo: '#password'
				},
			email: {
				required: true,
				email: true,
				minlength: 6,
				equalTo: '#emailcheck',
				},
			terms_checkbox:{
				required: true
			}
		 },
		 messages:{
		 	username:{
				equalTo: ''
			},
			cpassword:{
				equalTo: ''
			},
			email:{
				required: "Please enter a valid email address",
				email: 'Please enter a valid email address',
				minlength: '*Email too short',
				equalTo: ''
			},
			terms_checkbox:{
				required: "You must agree to Easyshop's terms and conditions before you can proceed."
			}
		 },
		 errorElement: "span",
		 errorPlacement: function(error, element) {
				error.addClass('red');
				if(element.attr('name') == 'password'){
					var added_span = $('<span/>',{'class':"red"});
					error.insertBefore(element.next());
					added_span.insertBefore(element.next());}
				else
					error.appendTo(element.parent());
		 },
		 submitHandler: function(form){
		 	$('#register_form1_loadingimg').show();
			$('#register_page1').attr('disabled', true);
		 	$.post(config.base_url + 'new_login/signup', $(form).serializeArray(), function(data){
				$('#login_register').html(data);
			});
			return false;
		 }
	 });
	 
	 $('.field input').on('click', function(){
		$('.ci_form_validation_error').text('');
	 });
	 
});


function username_check(){
	var username = $('#username').val();
	var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
	$('#username').siblings('img.check_loader').show();
	$.post(config.base_url+'register/username_check', {username: username, csrfname : csrftoken}, function(result){
		if(result === '1'){
			showcheck($('#username'));
			$('.username_availability').html('Username available');
			$('#usernamecheck').attr('value', $('#username').val());
		}
		else{
			showx($('#username'));
			$('.username_availability').html('Username already exists.');
		}
		$('#username').removeClass('forSearch');
		$('#username').siblings('img.check_loader').hide();
	});
}

function email_check(){
	var email = $('#email').val();
	var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
	$('#email').siblings('img.check_loader').show();
	$.post(config.base_url+'register/email_check', {email: email, csrfname : csrftoken}, function(result){
		if(result === '1'){
			showcheck($('#email'));
			$('.email_availability').html('Email available');
			$('#emailcheck').attr('value', $('#email').val());
		}
		else{
			showx($('#email'));
			$('.email_availability').html('Email already used.');
		}
		$('#email').removeClass('forSearch');
		$('#email').siblings('img.check_loader').hide();
	});
}

function showcheck(element){
	var name = element.attr('name');
	$('#'+name+'_check').show();
	$('#'+name+'_x').hide();
}

function showx(element){
	var name = element.attr('name');
	$('#'+name+'_check').hide();
	$('#'+name+'_x').show();
}

function hidecheckx(element){
	var name = element.attr('name');
	$('#'+name+'_check').hide();
	$('#'+name+'_x').hide();
}

function submitcountdown(){
	var count=5;

	var counter = setInterval(timer,1000);

	$('#register_form2_a_btn').hide();
	$('span.countdown_submit').html('You can resubmit in: ' + count);
	$('span.countdown_submit').show();

	function timer(){
		count -= 1;
		if(count<=0){
			clearInterval(counter);
			$('span.countdown_submit').html('');
			$('span.countdown_submit').hide();
			$('#register_form2_a_btn').show();
			return;
		}

		$('span.countdown_submit').html('You can resubmit in: ' + count);
	}
}


		

