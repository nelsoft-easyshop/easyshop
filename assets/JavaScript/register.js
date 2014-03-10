$(document).ready(function(){
	$('.search_box').css('display','none');
});

$(document).ready(function(){
	  
	  $('#username').on('focus', function() {
		  $('div.username_info').show();
		  $(document).bind('focusin.example click.example',function(e) {
			  if ($(e.target).closest('.username_info, #username').length) return;
			  $(document).unbind('.example');
			  $('div.username_info').fadeOut('medium');
		  });
		  hidecheckx($('#username'));
		  $('.username_availability').html('');
	  }).on('blur', function(){
		if($.trim($('#username').val()).length >= 5){
			setTimeout(username_check,1000);
		}
	  });
	  
	  $('div.username_info').hide();
	  
	  $('#password').focus(function() {
		  $('div.password_info, div.pass-container').show();
		  $(document).bind('focusin.example click.example',function(e) {
			  if ($(e.target).closest('.password_info, #password').length) return;
			  $(document).unbind('.example');
			  $('div.password_info, div.pass-container').fadeOut('medium');
			});
	  }).on('input paste', function(){
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
	  
	  $('div.password_info').hide();
	  
	  $('#captcha_refresh').click(function(){
		   $('#captcha_loading').css('display','inline');
		   $.post(config.base_url+"register/recreate_captcha", function(data){
			  $('#captcha_loading').css('display','none');
			  $('#captcha_img').children().attr('src', data);
		   });	
		   $('.red').html('');
	  });
	  
	  $('#verification_code').focus(function() {
		  $('#verification_code_error').fadeOut();
		  $('div.verification_info').fadeIn();
		  $(document).bind('focusin.example click.example',function(e) {
			  if ($(e.target).closest('.verification_info, #verification_code').length) return;
			  $(document).unbind('.example');
			  $('div.verification_info').fadeOut('medium');
		  });
	   });
	 $('div.verification_info').hide();
	 
});


/**********************************************************************************************/
/****************************	Change Password	*******************************************/
/**********************************************************************************************/
$(document).ready(function(){

	  $('#cur_password').on('focus', function() {
		  $('#username_check').hide();
		  $('#username_x').hide();
		  $('.username_availability').html('');
	  }).on('blur', function(){
		if($.trim($('#cur_password').val()).length > 1){
            pass_check();
		}
	  });
	  
	  function pass_check(){
			var username = $("#wsx").val();		  
			var pass 	 = $('#cur_password').val();
            
            var csrftoken = $('#changepass').find('input[name^="es_csrf"]').val();

			$.post(config.base_url+'register/pass_check', {username: username, pass: pass, es_csrf_token : csrftoken}, function(result){
				if(result == 1){
					showcheck($('#username'));
					$('#username_check').hide();
                    $('#cur_password_status').val('disapprove');
					$('#username_x').show();
					$('.username_availability').html('Incorrect Password');
				}
				else{
					showx($('#username'));
					$('#username_check').show();
                    $('#cur_password_status').val('approve');
					$('#username_x').hide();
					$('.username_availability').html('Password Correct');
				}
			});		
	  }	  	 	 
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
				case_all: true
				},
			cpassword: {
				required: true,
				minlength: 6,
                maxlength:25,
				equalTo: '#password'
				},
			captcha_word: {
				required: true,
				minlength: 6
				}
		 },
		 messages:{
		 	username:{
				equalTo: ''
			},
			cpassword:{
				equalTo: ''
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
		 	form.submit();
		 }
	 });
	 
	 
	$('.field input').on('click', function(){
		$('.ci_form_validation_error').text('');
	 });
	 
});
/**********************************************************************************************/
/****************************	Change Password VALIDATION	************************************/
/**********************************************************************************************/
$(document).ready(function(){

    jQuery.validator.addMethod("equals", function(value, element, param) {
      return this.optional(element) || value === param; 
    }, jQuery.format(""));
 
	 $("#changepass").validate({
         ignore: "",
		 rules: {
			cur_password: {
				required: true,
                minlength: 5,
                maxlength:25
				},		
            cur_password_status: {
				required: true,
                equals: "approve"
				},	
			password: {
				required: true,
                minlength: 6,
                maxlength:25,
				alphanumeric: true,
				case_all: true
				},
			cpassword: {
				required: true,
				minlength: 6,
                maxlength:25,
				equalTo: '#password'
				}
		 },
		 messages:{
			cpassword:{
				equalTo: ''
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
		 }	
	 });
	 
	 
	$('.field input').on('click', function(){
		$('.ci_form_validation_error').text('');
	 });
	 
});

/**********************************************************************************************/
/****************************	Forgot Password VALIDATION	************************************/
/**********************************************************************************************/
$(document).ready(function(){

	 $("#forgotpass").validate({
		 rules: {				
			password: {
				required: true,
                minlength: 6,
                maxlength:25,
				alphanumeric: true,
				case_all: true
				},
			cpassword: {
				required: true,
				minlength: 6,
                maxlength:25,
				equalTo: '#password'
				}
		 },
		 messages:{
			cpassword:{
				equalTo: ''
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
		 }
	 	
	 });
	 
	 
	$('.field input').on('click', function(){
		$('.ci_form_validation_error').text('');
	 });	 
	 
});

/**********************************************************************************************/
/****************************	FORM 2A VALIDATION	*******************************************/
/**********************************************************************************************/	
$(document).ready(function(){
	
	$("#register_mobile").numeric({negative : false});
	$("#cregister_mobile").numeric({negative : false});

	$("#register_mobile").on('input paste',function(e){
		$('#err_mobilespan').hide();
		if( $.trim($(this).val()).length == 11 ){
			$("#cregister_mobile").attr('disabled', false);
			showx($('#cregister_mobile'));
			}
		else{
			$("#cregister_mobile").attr('disabled', true);
			$("#cregister_mobile").val('');
			hidecheckx($('#cregister_mobile'))
		}
	});
	
	$("#register_email").on('input paste',function(e){
		$('#err_emailspan').hide();
		if( $.trim($(this).val()).length > 6 ){
			$("#cregister_email").attr('disabled', false);
			showx($('#cregister_email'));
			}
		else{
			$("#cregister_email").attr('disabled', true);
			$("#cregister_email").val('');
			hidecheckx($('#cregister_email'));
		}
		
		if($(this).val() !== $('#cregister_email').val() && !$('#cregister_email')[0].disabled)
			showx($('#cregister_email'));
		else if($(this).val() == $('#cregister_email').val() && !$('#cregister_email')[0].disabled)
			showcheck($('#cregister_email'));
	});
	
	$("#cregister_mobile").on('paste', function(e){
		e.preventDefault();
	}).on('focusin input focusout',function(){
		if($(this).val() !== $('#register_mobile').val())
			showx($(this));
		else
			showcheck($(this));
	});
	
	$("#cregister_email").on('paste', function(e){
		e.preventDefault();
	}).on('focusin input focusout',function(){
		if($(this).val() !== $('#register_email').val())
			showx($(this));
		else
			showcheck($(this));
	});
	
	$('#register_form2_a').validate({
		groups:{
			contactinfo: "register_mobile register_email"
		},
		rules:{
			register_mobile:{
				/*
				required: function(element){
					return(($('#register_mobile').val().length)==0 && ($('#register_email').val().length==0));
				},
				*/
				number: true,
				minlength: 11
			},
			cregister_mobile:{
				required: function(element){
					return(!element.disabled);
				},
				number: true,
				equalTo: '#register_mobile'
			},
			register_email:{
				/*
				required: function(element){
					return(($('#register_mobile').val().length)==0 && ($('#register_email').val().length==0));
				},
				*/
				required: true,
				email: true,
				minlength: 6
			},
			cregister_email:{
				required: function(element){
					return(!element.disabled);
				},
				email: true,
				equalTo: '#register_email'
			},
			terms_checkbox:{
				required: true
			}
		},
		messages:{
			register_mobile:{
				//required: '*Please enter a valid mobile number OR email address',
				number: '*This field should be numeric',
				minlength: '*Mobile number too short'
			},
			cregister_mobile:{
				required: '*Please verify your mobile number',
				equalTo: '',
				number: ''
			},
			register_email:{
				//required: '*Please enter a valid mobile number OR email address',
				required: "Please enter a valid email address",
				email: '*Please enter a valid email address',
				minlength: '*Email too short'
			},
			cregister_email:{
				required: '*Please verify your email address',
				email: '',
				equalTo: ''
			},
			terms_checkbox:{
				required: "*You must agree to Easyshop's terms and conditions before you can proceed."
			}
		},
		errorElement: 'span',
		errorPlacement: function(error, element){
			if(element.attr('name')=="register_mobile" || element.attr('name')=="register_email"){
				error.addClass('red');
				error.insertBefore('#tc').wrap('<p>').css('margin-left','130px');
			}
			else{
			error.addClass('red');
			error.appendTo(element.parent());
			}
		},
		submitHandler: function(form){
			$('#register_loading').css('display', 'inline');
			$('#register_form2_a_btn').attr('disabled', true);
			$.post(config.base_url+'register/send_verification_code', $('#register_form2_a').serializeArray(),function(data){
				$('#register_loading').css('display', 'none');
				$('#register_form2_a_btn').attr('disabled', false);

				if(data != 0){

					//Try parsing data received from server. Handles parsing data when php error encountered.
					try{
						var obj = jQuery.parseJSON(data);	
					}
					catch(e){
						alert('An error was encountered while processing your data. Please try again in a few minutes.');
						submitcountdown();
						return false;
					}

					var email_msg = "";
					var mobile_msg = "";
					
					// will execute only when email | mobile was provided
					if( obj['emailstat'] !== '' || obj['mobilestat'] !== '' ){	

						if(obj['emailstat'] !== ''){
							$('#verification-content .verification_field_email').show();
							if(obj['emailstat'] === 'error')
								$('.emailstat').html($('.emailstat-error').html().replace('%%',obj['email']));
							else if(obj['emailstat'] === 'success'){
								email_msg += "Email: " + obj['email'] + "<br>";
								var email_link = obj['email'].slice(obj['email'].search('@')+1);
								$('.emailstat').html($('.emailstat-success').html().replace(/\%%/g,email_link));
							}
							else if(obj['emailstat'] === 'exceed')
								$('.emailstat').html($('.emailstat-exceed').html());
							else if(obj['emailstat'] === 'exists')
								$('#err_emailspan').show();
						}
						else
							$('#verification-content .verification_field_email').hide();
						

						if(obj['mobilestat'] !== ''){
							$('#verification-content .verification_field_mobile').show();
							if(obj['mobilestat'] === 'error')
								$('.mobilestat').html($('.mobilestat-error').html().replace('%%',obj['mobile']));
							else if(obj['mobilestat'] === 'success'){
								mobile_msg += "Mobile: " + obj['mobile'] + "<br>";
								$('.mobilestat').html($('.mobilestat-success').html());
							}
							else if(obj['mobilestat'] === 'exceed')
								$('.mobilestat').html($('.mobilestat-exceed').html());
							else if(obj['mobilestat'] === 'exists')
								$('#err_mobilespan').show();
						}
						else
							$('#verification-content .verification_field_mobile').hide();
							

						if(( (obj['mobilestat'] == 'error' || obj['mobilestat'] == 'exceed') && obj['emailstat']!='success') || ( (obj['emailstat'] == 'error' || obj['emailstat'] == 'exceed') && obj['mobilestat']!='success') || 
							( (obj['emailstat'] === 'error' || obj['emailstat'] === 'exceed') && (obj['mobilestat'] ==='error' || obj['mobilestat'] === 'exceed') ) )
						{
							$('.verification-span-error').show(); 
							$('.verification-span').hide();
							$('#verification-content .verification-msg').html('');
						}
						else{
							$('.verification-span-error').hide(); 
							$('.verification-span').show();
							$('#verification-content .verification-msg').html("<strong>" + mobile_msg + email_msg + "</strong>" + "<br>");
							if((obj['mobilestat']=='success')&&(obj['emailstat']=='success'))
								$('.or_separator').css('display','inline');
							}

						if(obj['mobilestat'] !== 'exists' && obj['emailstat'] !== 'exists'){
							$('#verification-content').modal({
								position: ["25%","25%"],
								escClose: false,
								onShow: function(){
									if (obj['mobilestat']==='success')
										form2validation();
								},
								onClose: function(){
									//If email only
									if(obj['emailstat'] === 'success' && obj['mobilestat'] === ''){
										$.modal.close();
										window.location.replace('login');
									}
									//If mobile verification passed
									else if($('#verification_code').hasClass('verified')){
										$.modal.close();
									}
									else{
										$.modal.close();
										submitcountdown();
									}
								}
							});
						}
					}//close if mobilestat or emailstat is set

				}//close if data != 0
				else{
					alert('Sorry, we failed to send your form. Please try again in a few seconds.');					
					submitcountdown();
				}
			});
			return false;
		}
	});
	
	

}); //CLOSE DOCUMENT READY

function username_check(){
	var username = $('#username').val();
	var csrftoken = $('#register_form1').find('input[name^="es_csrf"]').val();
	$.post(config.base_url+'register/username_check', {username: username, es_csrf_token : csrftoken}, function(result){
		if(result === '1'){
			showcheck($('#username'));
			$('.username_availability').html('Username available');
			$('#usernamecheck').attr('value', $('#username').val());
		}
		else{
			showx($('#username'));
			$('.username_availability').html('Username already exists');
		}
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

/**********************************************************************************************/
/****************************	FORM 2B VALIDATION	*******************************************/
/**********************************************************************************************/	
	
// FORM VALIDATION FOR MOBILE VERIFICATION
function form2validation(){		
		$('#register_form2_b').validate({
			rules:{
				verification_code:{
					required: true
				}
			},
			messages:{
				verification_code:{
					required: "Please enter your verification code"
				}
			},
			errorElement: 'span',
			errorPlacement: function(error, element){
				error.addClass('red');
				error.insertAfter(element);
			},
			submitHandler: function(form){
				$.post(config.base_url+'register/mobile_verification', $('#register_form2_b').serializeArray(),function(data){
					if(data){
						$('#verification_code').addClass('verified');
						$.modal.close();
						$.post(config.base_url+'register/success_mobile_verification',{mobile_verify: 'submit_mobilenum'}, function(data){
							if(data){
								$('#verification_code').removeClass('verified');
								$('#register_form2_view').html(data);
							}
						});
					}
					else
						$('#verification_code_error').fadeIn();
				});
				return false;
			}
		});
}


		
