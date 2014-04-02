/**********************************************************************/
/**************************	SIGNUP/REGISTER ***************************/
/**********************************************************************/

$(document).ready(function(){
	  
	  $('#username').on('focus', function() {
		  $(document).bind('focusin.example click.example',function(e) {
			  if ($(e.target).closest('.username_info, #username').length) return;
			  $(document).unbind('.example');
		  });
	  }).on('blur', function(){
		var fieldlength = $.trim($('#username').val()).length;
		if(fieldlength >= 5 && $(this).hasClass('forSearch') && $(this).hasClass('valid')){
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
		if(fieldlength >= 6 && $(this).hasClass('forSearch') && $(this).hasClass('valid')){
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
	 
	 jQuery.validator.addMethod("alphanumeric_underscore", function(value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
	 }, "Only letters, numbers, and underscores are allowed");
 
	 $("#register_form1").validate({
		 rules: {
            username: {
                required: true,
                minlength: 5,
                maxlength:25,
				alphanumeric_underscore: true
				},
			password: {
				required: true,
                minlength: 6,
                maxlength:25,
				alphanumeric: true
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
				minlength: 6
				}
		 },
		 messages:{
			cpassword:{
				equalTo: ''
			},
			email:{
				required: "Please enter a valid email address",
				email: 'Please enter a valid email address',
				minlength: '*Email too short'
			}
		 },
		 errorElement: "span",
		 errorPlacement: function(error, element) {
				error.addClass('red');
				/*
				if(element.attr('name') == 'password'){
					var added_span = $('<span/>',{'class':"red"});
					error.insertBefore(element.next());
					added_span.insertBefore(element.next());}
				else
					error.appendTo(element.parent());
				*/
				if(element.attr('name') !== 'cpassword'){
					error.insertAfter(element);
				}
		 },
		 submitHandler: function(form){
			if( $('#username').hasClass('pass') && $('#email').hasClass('pass') ){
				$('#register_form1_loadingimg').show();
				$('#register_form1_btn').attr('disabled', true);

			 	$.post(config.base_url + 'landingpage/signup', $(form).serializeArray(), function(data){
					$('#register_form1_loadingimg').hide();
					$('#register_form1_btn').attr('disabled', false);
					if(data == 1){
						$('#result_desc').html("Thank you for registering to Easyshop.ph!");
						var title = "Registration Complete";

						$('div.pass-container, .fieldstatus').hide();
						$(form).find('input.reqfield').each(function(){
							$(this).prop('value', '');
						});
					}
					else{
						$('#result_desc').html("An error was encountered. Try again later.");
						var title= "Failed to Register";
					}
					$('#register_result').dialog({
						autoOpen: false,
						title: title,
						modal: true,
						closeOnEscape: false,
						buttons:{
							OK: function(){
								$(this).dialog("close");
							}
						}
					});
					$('#register_result').dialog('open');
				});
			}
			return false;
		 }
	 });
	 
	 $('.field input').on('click', function(){
		$('.ci_form_validation_error').text('');
	 });
	 
	 $('#subscription_form').validate({
		rules: {           
			subscribe_email: {
				required: true,
				email: true,
				minlength: 6,
				}
		 },
		 messages:{
			subscribe_email:{
				required: "Please enter a valid email address",
				email: 'Please enter a valid email address',
				minlength: '*Email too short',
			}
		 },
		 errorElement: "span",
		 errorPlacement: function(error, element) {
				error.addClass('red');
				error.appendTo(element.parent());
		 },
		 submitHandler: function(form){
			$('#subscribe_loadingimg').show();
			$('#subscribe_btn').attr('disabled', true);

			$.post(config.base_url + 'landingpage/subscribe', $(form).serializeArray(), function(data){
				$('#subscribe_loadingimg').hide();
				$('#subscribe_btn').attr('disabled', false);
				if(data == 1){
					$('#result_desc').html("Thank you for subscribing to Easyshop.ph!");
					var title = "Subscription Complete";
					$(form).find('input[type="text"]').each(function(){
						$(this).prop('value', '');
					});
				}
				else{
					$('#result_desc').html("An error was encountered. Try again later.");
					var title= "Failed to Subscribe";
				}
				$('#register_result').dialog({
					autoOpen: false,
					title: title,
					modal: true,
					closeOnEscape: false,
					buttons:{
						OK: function(){
							$(this).dialog("close");
						}
					}
				});
				$('#register_result').dialog('open');
			});
			return false;
		}
	});
});


function username_check(){
	var username = $('#username').val();
	var csrftoken = $('#register_form1').find('input[name^="es_csrf"]').val();
	var field = $('#username');
	$('#username_loader').show().css('display','inline-block');
	$.post(config.base_url+'landingpage/username_check', {username: username, es_csrf_token : csrftoken}, function(result){
		if(result == 1){
			showcheck($('#username'));
			$('.username_availability').html('Username available');
			$('#usernamecheck').attr('value', $('#username').val());
			field.addClass('pass');
		}
		else{
			showx($('#username'));
			$('.username_availability').html('Username already exists.');
			field.removeClass('pass');
		}
		field.removeClass('forSearch');
		$('#username_loader').hide();
	});
}

function email_check(){
	var email = $('#email').val();
	var csrftoken = $('#register_form1').find('input[name^="es_csrf"]').val();
	var field = $('#email');
	$('#email_loader').show().css('display','inline-block');
	$.post(config.base_url+'landingpage/email_check', {email: email, es_csrf_token : csrftoken}, function(result){
		if(result == 1){
			showcheck($('#email'));
			$('.email_availability').html('Email available');
			$('#emailcheck').attr('value', $('#email').val());
			field.addClass('pass');
		}
		else{
			showx($('#email'));
			$('.email_availability').html('Email already used.');
			field.removeClass('pass');
		}
		field.removeClass('forSearch');
		$('#email_loader').hide();
	});
}

function showcheck(element){
	var name = element.attr('name');
	$('#'+name+'_check').show().css('display','inline-block');
	$('#'+name+'_x').hide();
}

function showx(element){
	var name = element.attr('name');
	$('#'+name+'_check').hide();
	$('#'+name+'_x').show().css('display','inline-block');
}

function hidecheckx(element){
	var name = element.attr('name');
	$('#'+name+'_check').hide();
	$('#'+name+'_x').hide();
}
