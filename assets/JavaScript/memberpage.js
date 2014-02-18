

$(document).ready(function(){
	$('.search_wrapper').css('display','none');
});

//Progress update
$(window).load(function(){
	progress_update('');
	handle_fields('');
});

/**************************************************************************************************************/	
/**************************************  PERSONAL INFORMATION MAIN    *****************************************/   
/**************************************************************************************************************/		

$(document).ready(function(){
	$(".year").numeric({negative : false});
	$.datepicker.setDefaults({dateFormat: 'yy-mm-dd'}, $.extend($.datepicker.regional['']));

	$( "#datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true
	});

	 jQuery.validator.addMethod("is_validdate", function(value, element) {
		var comp = value.split( /[-\/]+/);
		var y = parseInt(comp[0], 10);
		var m = parseInt(comp[1], 10);
		var d = parseInt(comp[2], 10);
		var date = new Date(y,m-1,d);
		if ((date.getFullYear() == y) && ((date.getMonth() + 1) == m) && (date.getDate() == d)) 
			 return this.optional(element) || true;
		else 
			 return this.optional(element) || false;
	 }, "This date is invalid");
 
	$("#personal_profile_main").validate({
		rules: {
			dateofbirth:{
				is_validdate: true
			},
			mobile:{
				number: true,
				minlength: 11,
				maxlength: 11
			},
			email:{
				email: true,
				minlength: 6
			}
		},
		errorElement: "span",
		errorPlacement: function(error, element) {
				error.addClass('red');
				error.appendTo(element.parent());
		},	
		submitHandler: function(form) {
		   $('#load_personalinfo').css('display', 'inline');
		   $('#mobile, #email').attr('disabled', false);
		   $('#ppm_btn').attr('disabled', true);
		   $.post(config.base_url+'memberpage/edit_personal',$('#personal_profile_main').serializeArray(),
				function(data){
					$('#ppm_btn').attr('disabled', false);
					$('#load_personalinfo').css('display', 'none');
					if(data === '1'){
						progress_update($('#personal_profile_main'));
						
						$('#mobile, #email').each(function(){

							if($(this).attr('value') !== $.trim($(this).prop('value')))
								$(this).siblings('input[name^="is_"]').val(0);
							
							$(this).attr('value', $.trim($(this).prop('value')));
							$(this).closest('div').removeClass('fired');
							$(this).closest('div').next('div[id^="cont"] span:first').html('');
							$(this).closest('div').next('div[id^="cont"]').hide();
							$(this).parent().find('span.edit_personal_contact').hide();
							
							if($.trim($(this).prop('value')) !== ''){
								if($(this).siblings('input[name^="is_"]').val() == 0){
									$(this).siblings('span.verify').hide();
									$(this).parent().find('span.toverify, span.toverify span.verify_now').show();
								}
								else if ($(this).siblings('input[name^="is_"]').val() == 1){
									$(this).siblings('span.verify').hide();
									$(this).siblings('span.doneverify').show();
								}
								
								$(this).siblings('span.personal_contact_cont').show();
								$(this).siblings('span.personal_contact_cont').children('span.cancel_personal_contact').hide();
								$(this).attr('disabled', true);
							}
							else{
								$(this).siblings('span.personal_contact_cont').hide();
								$(this).siblings('span.personal_contact_cont').children('span.cancel_personal_contact').hide();
								$(this).attr('disabled', false);
							}
						});
					}
					else if(data === '0'){
						alert('An error was encountered in submitting your form. Please try again later.');
						window.location.reload(true);
					}
					else{
						try{
							var obj = jQuery.parseJSON(data);	
						}
						catch(e){
							alert('An error was encountered while processing your data. Please try again later.');
							window.location.reload(true);
						}
						
						if(obj['mobile'] == 1){
							$('#cont_mobilediv span:first').html('Mobile No. already used.');
							$('#cont_mobilediv').show();
						}
						else{
							var mobileField = $('#mobile');
							if( $.trim(mobileField.prop('value')) !== '' ){
								mobileField.attr('disabled', true);
							}
							else{
								mobileField.attr('disabled', false);
							}
						}
						if(obj['email'] == 1){
							$('#cont_emaildiv span:first').html('Email already used.');
							$('#cont_emaildiv').show();
						}
						else{
							var emailField = $('#email');
							if( $.trim(emailField.prop('value')) !== '' ){
								emailField.attr('disabled', true);
							}
							else{
								emailField.attr('disabled', false);
							}
						}
					}
				});
			return false;
	   }
	});  

	$('.avatar_edit').click(function(){
			$('#imgupload').click();
	}).mouseover(function(){
			$('html,body').css('cursor','pointer');
	}).mouseout(function(){
			$('html,body').css('cursor','default');
	});
	
	$("#imgupload").on("change", function(){
		imageprev(this);
	});
	
	$('#emaildiv, #mobilediv').on('mouseover', function(){
		if($(this).hasClass('fired') == false)
			$(this).find('.edit_personal_contact').show();
	})
	.on('mouseleave', function(){
		$(this).next('div[id^="cont"]').fadeOut(3000);
		$(this).next('div[id^="cont"] span:first').html('');
		
		if($(this).hasClass('fired') == false)
			$(this).find('.edit_personal_contact').hide();
	});
	
	$('.edit_personal_contact').on('click', function(){
		$(this).parent().siblings('input[type="text"]').attr('disabled', false).focus();
		$(this).siblings('.cancel_personal_contact').show();
		$(this).parent().siblings('span.verify').hide();
		$(this).hide();
		$(this).closest('div').addClass('fired');
	});

	$('.cancel_personal_contact').on('click', function(){
		var inputsibling = $(this).parent().siblings('input[type="text"]');
		//$(this).parent().siblings('input[type="text"]').prop('value', $(this).siblings('input').attr('value')).attr('disabled', true);
		inputsibling.prop('value', inputsibling.attr('value')).attr('disabled', true);
		$(this).siblings('.edit_personal_contact').show();
		$(this).hide();
		$(this).closest('div').removeClass('fired');
		$(this).parent().siblings('span.error').remove();
		
		if($(this).parent().siblings('input[name^="is_"]').val() == 1)
			$(this).parent().siblings('span.doneverify').show();
		else
			$(this).parent().siblings('span.toverify').show();
	});
	
	$('#verifcode').on('click focus focusin keydown', function(){
		$('.verifcode_error').fadeOut();
	});
	
	//Mobile verification dialog box
	$('#verifcode_div').dialog({
		autoOpen: false,
		title: "Enter confirmation code",
		modal: true,
		buttons:{
			Submit: function(){
				var $dialog = $(this);
				var val = $('#verifcode').val();
				$.post(config.base_url+'memberpage/verify_mobilecode',{data:val, mobileverify:'true'}, function(data){
					if(data == 1){
						$('#mobilediv').find('span.doneverify').show();
						$dialog.dialog( 'close' );
					}
					else if(data==0){
						$('.verifcode_error').fadeIn();
					}
				})
			},
			Cancel: function(){
				$('#verifcode').val('');
				$('#mobile').attr('disabled', true);
				$('#mobilediv').find('span.verify_now').show();
				$('#mobilediv').removeClass('fired');
				$('#mobilediv').find('span.personal_contact_cont').show();
				$('#cont_mobilediv').hide();
				$('#mobilediv').find('span.edit_personal_contact').hide();
				$(this).dialog( "close" );
			}
		},
	});
	
	//Verify button
	$('.verify_now').on('click', function(){
		var data = $(this).parent('span.verify').siblings('input[type="text"]').val();
		var field = $(this).parent('span.verify').siblings('input[type="text"]').attr('name');
		
		var $thisspan = $(this);
		var loadingimg = $(this).siblings('img.verify_img');
		var verifyspan = $(this).parent('span.verify');
		var parentdiv = $(this).closest('div');
		var contdiv = parentdiv.next('div[id^="cont_"]');
		var errorspan = contdiv.children('span:first');
		
		
		$thisspan.hide();
		loadingimg.show();
		parentdiv.addClass('fired');
		verifyspan.siblings('span.personal_contact_cont').hide();
		$('#ppm_btn').attr('disabled', true);
		
		$.post(config.base_url+'memberpage/verify', {field:field, data:data, reverify:'true'}, function(data){
			loadingimg.hide();
			contdiv.show();
			$('#ppm_btn').attr('disabled', false);
			
			if(data!=0){
				try{
					var obj = jQuery.parseJSON(data);	
				}
				catch(e){
					alert('An error was encountered while processing your data. Please try again later.');
					$thisspan.show();
					parentdiv.removeClass('fired');
					verifyspan.siblings('span.personal_contact_cont').show();
					contdiv.hide();
					return false;
				}
				
				if(obj==='dataerror'){
					verifyspan.after('<p>There was a mismatch of data with the server. Reload the page and try again later.</p>');
					contdiv.hide();
				}
				else{
					if(field==='mobile'){
						if(obj === 'success'){
							$('#verifcode_div').dialog('open');
						}
						else if(obj === 'error'){
							$thisspan.show();
							parentdiv.removeClass('fired');
							verifyspan.siblings('span.personal_contact_cont').show();
							//$('#cont_mobilediv span:first').html('An error was encountered. Please try again later');
							errorspan.html('An error was encountered. Please try again later');
						}
						else if(obj === 'exceed')
							//$('#cont_mobilediv span:first').html('You have exceeded the number of times to verify your mobile. Try again after 30 mins.');
							errorspan.html('You have exceeded the number of times to verify your mobile. Try again after 30 mins.');
					}
					else if(field==='email'){
						if(obj === 'success'){
							//$('#emaildiv').find('span.doneverify').show();
							//$('#emaildiv').find('span.doneverify span:first').html('<strong>Email sent.</strong>');
							parentdiv.find('span.doneverify').show();
							parentdiv.find('span.doneverify span:first').html('<strong>Email sent.</strong>');
							contdiv.hide();
						}
						else if(obj ==='error'){
							$thisspan.show();
							parentdiv.removeClass('fired');
							verifyspan.siblings('span.personal_contact_cont').show();
							//$('#cont_emaildiv span:first').html('An error was encountered. Please try again later');
							errorspan.html('An error was encountered. Please try again later');
						}
						else if(obj === 'exceed')
							//$('#cont_emaildiv span:first').html('You have exceeded the number of times to verify your email. Try again after 30 mins.');
							errorspan.html('You have exceeded the number of times to verify your email. Try again after 30 mins.');
					}
				}
			}
			else
				window.location.replace('<?php echo base_url();?>' + 'memberpage');
		});
	});
});


/**************************************************************************************************************/	
/********************************  DROP DOWN PROFILE - GEN FUNCTIONS    ***************************************/   
/**************************************************************************************************************/
$(document).ready(function(){
	/***********	Delete information on click - GENERAL	**************/
	$('.delete_information').on('click', function(){
		var name = $(this).attr('name');
		var parentinfocont = $(this).parent();
		var editprofilebtn = parentinfocont.siblings('div.edit_profile');
		var editfields = parentinfocont.siblings('div.edit_fields');
		var echoedinfo = $(this).siblings('.echoed_info');
		var form = $(this).closest('form');
		
		$.post(config.base_url+"memberpage/deletePersonalInfo", {field : name}, function(data){
			if(data === '1'){
				editprofilebtn.show();
				parentinfocont.hide();
				
				//Update text boxes pre-filled with server data
				editfields.find('input[type="text"]').each(function(){
					$(this).attr('value', "");
					$(this).prop('value', "");
				});
				//Clear echoed info from server
				echoedinfo.html('');
				
				if(name === 'del_school'){					
					$('#container_school').html('');
					var schlevel = editfields.find('select');
					schlevel.attr('data-status', "");
					schlevel.val(0);
				}
				else if(name === 'del_work'){
					$('#container_work').html('');
				}
				
				progress_update(form);
			}
			else{
				alert('Error deleting data. Please try again later.');
			}
		});
	});
	
	/*******************	ADD Field - General		********************************/
	$('.edit_profile').on('click', function(){
		$(this).siblings('.edit_fields').fadeIn(300);
		$(this).hide();
	});
	
	/*******************	Mouse Event - General	********************************/
	$('.work_information, .school_information, .address_information').mouseover(function(){
		$(this).children('.edit_info_btn').addClass('show');
		$(this).children('.delete_information').addClass('show');
	})
	.mouseout(function(){
		$(this).children('.edit_info_btn').removeClass('show');
		$(this).children('.delete_information').removeClass('show');
	});
	
	/*******************	Edit button - General	********************************/
	$(".edit_info_btn").click(function(){
		$(this).parent('.gen_information').siblings('.edit_fields').fadeIn(300);
		$(this).parent('.gen_information').hide();
	  });
	
	/*******************	Cancel button - General	********************************/
	$('.cancel').on('click', function(){
		var editfields = $(this).closest('div.edit_fields');
		var innerfields = editfields.children('div.inner_profile_fields');
		var form = $(this).closest('form.dropdownform');
		var cancelname = $(this).attr('name');
		
		editfields.fadeOut();
		form.validate().resetForm();
		handle_fields(form);
		
		innerfields.find('input[type="text"]').each(function(){
			$(this).prop('value', $(this).attr('value'));
		});
		
		if(cancelname === 'cancel_school' || cancelname === 'cancel_work'){
			innerfields.find('select').each(function(){
				$(this).val($(this).attr('data-status'));
			});
			innerfields.find('div.dynamic_dd').find('input[type="text"]').each(function(){
				if(!($.trim($(this).attr('value')))){
					$(this).closest('div.dynamic_dd').remove();
				}
			});
		}
		
	});
	
});

/**************************************************************************************************************/	
/**************************************  PERSONAL INFORMATION ADDRESS    ***************************************/   
/**************************************************************************************************************/
$(document).ready(function(){
	//************	PERSONAL PROFILE ADDRESS VALIDATION	***************//
	$("#personal_profile_address #streetno").numeric({negative : false});
	$("#personal_profile_address #postalcode").numeric({negative : false});
	
	$("#personal_profile_address").validate({
		rules: {
			streetno:{
				required: true
			},
			streetname:{
				required: true
			},
			citytown: {
				required: true
			},
			country: {
				required: true
			}
		},
		messages: {
			streetno:{
				required: '* Street or Building Number required'
			},
			streetname:{
				required: '* Streetname required'
			},
			citytown:{
				required: '* City or Town required'
			},
			country: {
				required: '* Country required'
			},
		},
		errorElement: 'span',
		errorPlacement: function(error, element){
				error.addClass('red');
				//error.appendTo('#personal_profile_address .error_container').wrap('<p>');
				error.appendTo(element.parent());
		},
		submitHandler: function(form){
			$('#load_address').css('display', 'inline');
			$.post(config.base_url+'memberpage/edit_address',$('#personal_profile_address').serializeArray(),
					function(data){
						$('#load_address').css('display', 'none');
						var obj = jQuery.parseJSON(data);
						
						//overwrite div to display new address data
						$('.address_information .add_info').html(function(){
							var string = obj['streetno'] + " " + obj['streetname'] + " " + obj['barangay'] + " " + obj['citytown'] + " " + obj['country'] + " " + obj['postalcode'];
							return string;
						});
						
						//update attribute value to new values
						$('.address_fields input[type="text"]').each(function(){
							$(this).attr('value',obj[$(this).attr('name')]);
						});
						
						//fix display of address depending on contents
						handle_fields($('#personal_profile_address'));
						progress_update($('#personal_profile_address'));
						$('#personal_profile_address .edit_fields').fadeOut();
					});
			return false;					
		}
	});
});


/**************************************************************************************************************/	
/**************************************  PERSONAL INFORMATION SCHOOL    ***************************************/   
/**************************************************************************************************************/  
$(document).ready(function(){
	function GetHtml2() {
		var len = $('.add_another_school').length+2;
		var $html1 = $('#add_school').clone();
		$html1.find('[name=schoolname1]').attr('value','');
		$html1.find('[name=schoolyear1]').attr('value','');
		$html1.find('[name=schoollevel1]').find(':selected').attr('selected', false);
		$html1.find('[name=schoollevel1]').find('[value=0]').attr('selected', true);
		$html1.find('[name=schoolcount1]').attr('value',len);
		$html1.find('[name=schoolname1]').attr('name',"schoolname" + len);
		$html1.find('[name=schoolyear1]').attr('name',"schoolyear" + len);
		$html1.find('[name=schoollevel1]').attr('name',"schoollevel" + len);
		$html1.find('[name=schoolcount1]').attr('name',"schoolcount" + len);	
		$html1.find('.error.red').remove();
		return $html1.html();
	}
	
	//ADD 'ANOTHER' - BUTTON CLICK
	$('#addRow_school').click(function () {   
		var cont = $('<div/>', {
			'class': 'add_another_school dynamic_dd',
			html: GetHtml2()
		}).hide().appendTo('#container_school').fadeIn('slow');
		cont.find('*[name^="schoolyear"]').rules('add', {required: true, is_numeric: true,
										   messages: {required: '* The year field is required'}});
		cont.find('*[name^="schoolname"]').rules('add', {required: true, 
										   messages: {required: '* The school field is required'}});
		cont.find('*[name^="schoollevel"]').rules('add', {select_is_set: 0});		   
		$(".year").numeric({negative : false});
		var len = $("#container_school").find(".add_another_school").length;
		if(len == 3){
			$('#addRow_school').hide();
		}
	});
	

	//*****************	PERSONAL PROFILE SCHOOL SUBMIT	****************************//
	 jQuery.validator.addMethod("is_numeric", function(value, element) {
		return this.optional(element) || /[0-9]/.test(value);
	 }, "* This field should be numeric");
	
	 jQuery.validator.addMethod("select_is_set", function(value, element, arg) {
		return this.optional(element) || (arg != value?true:false);
	 }, "* This field must be set");
	
	
	$("#personal_profile_school").validate({
		rules: {
			schoolname1:{
				required: true
			},
			schoolyear1:{
				required: true,
				is_numeric: true,
			},
			schoollevel1:{
				select_is_set: '0'
			}
		},
		messages: {
			schoolname1:{
				required: '* The school field is required'
			},
			schoolyear1:{
				required: '* The year field is required'
			}
		},
		errorElement: "p",
		errorPlacement: function(error, element) {
				error.addClass('red');
				error.appendTo(element.parent());
		},
		submitHandler: function(form) {
		   $('#load_school').css('display', 'inline');
		   $.post(config.base_url+'memberpage/edit_school',$('#personal_profile_school').serializeArray(),
				function(data){
					$('#load_school').css('display', 'none');
					var obj = jQuery.parseJSON(data);
					var string = "";
					
					//UPDATE DIV SCHOOL INFORMATION
					$.each(obj.school, function(index, value){
						var schoollevel_string;
						switch(value.schoollevel){
							case '1': schoollevel_string='Undergraduate degree'; break;
							case '2': schoollevel_string='Masteral degree'; break;
							case '3': schoollevel_string='Doctorate degree'; break;
							case '4': schoollevel_string='High School'; break;
							case '5': schoollevel_string='Elementary'; break;
							default:   schoollevel_string='Undergraduate degree';
						}
						$('.school_information .school_info').html(function(){
							string += "<p/>"+value.schoolname+" "+value.schoolyear+" "+schoollevel_string;
							return string;
						});
					});
					
					// UPDATE TEXT BOX AND SELECT PROPERTIES
					var i = 1;
					$.each(obj.school, function(){
						$.each(this, function(k, v){	
							if(k !='schoollevel'){
								$(".school_fields input[name='" + k + i +"']").prop('value',v);
								$(".school_fields input[name='" + k + i +"']").attr('value',v);
							}
							else{
								$(".school_fields select[name='"+k+i+"']").val(v);
								$(".school_fields select[name='"+k+i+"']").attr('data-status', v);
							}
						});
						i++;
					});
					
					$('.save_school').parent('.edit_fields').fadeOut();
					handle_fields($('#personal_profile_school'));
					progress_update($('#personal_profile_school'));
				});
			return false;
	   }
	});  
});


/**************************************************************************************************************/	
/**************************************  PERSONAL INFORMATION WORK    *****************************************/   
/**************************************************************************************************************/
	
$(document).ready(function(){	
   //clone input fields for work
	function GetHtml() {
		var len = $('.add_another_work').length+2;
		var $html = $('#add_work').clone();
		$html.find('[name=companyname1]').attr('value', "");
		$html.find('[name=companyname1]')[0].name = "companyname" + len;
		$html.find('[name=designation1]').attr('value', "");
		$html.find('[name=designation1]')[0].name = "designation" + len;
		$html.find('[name=year1]').attr('value', "");
		$html.find('[name=year1]')[0].name = "year" + len;
		$html.find('[name=workcount1]')[0].value = len;
		$html.find('[name=workcount1]')[0].name = "workcount" + len;
		$html.find('.error.red').remove();
		return $html.html();
	}

   //add additional work 
	$(document).ready(function () {
		$('#addRow_work').click(function (){        
			var cont = $('<div/>', {
				'class': 'add_another_work dynamic_dd',
				html: GetHtml()
			}).hide().appendTo('#container_work').fadeIn('slow');
			
			cont.find("*[name^='companyname']").rules('add',{required: true, messages:{required: '* Company name is required'}});
			cont.find("*[name^='designation']").rules('add',{required: true, messages:{required: '* Designation is required'}});
			cont.find("*[name^='year']").rules('add',{required: true, is_numeric: true, messages:{required: '* Year of service is required'}});
			cont.find("*[name^='year']").numeric({negative : false});	
			
			var len = $("#container_work").find(".add_another_work").length;
			if(len == 3){
				$('#addRow_work').hide();
			}
		});
	});

	//***************	PERSONAL PROFILE WORK	************************//
	$("#personal_profile_work input[name='year1']").numeric({negative : false});

	$('#personal_profile_work').validate({
		rules: {
			companyname1:{
				required: true
			},
			designation1:{
				required: true
			},
			year1:{
				required:true,
				is_numeric: true
			}
		},
		messages:{
			companyname1:{
				required: '* Company name is required'
			},
			designation1:{
				required: '* Designation is required'
			},
			year1:{
				required: '* Year of service is required'
			}
		},
		errorElement: 'p',
		errorPlacement: function(error, element) {
				error.addClass('red');
				error.appendTo(element.parent());
				//console.log(element.parent());
		},
		submitHandler: function(form){
			$('#load_work').css('display','inline');
			$.post(config.base_url+'memberpage/edit_work', $('#personal_profile_work').serializeArray(),
				function(data){
					$('#load_work').css('display','none');
					
					var obj = jQuery.parseJSON(data);
					var string = "";
					
					//UPDATE DIV CONTENTS
					$('#personal_profile_work .work_info').html(function(){
						for(var x=0; x<obj.work.length; x++){
							string += "<p>" + obj.work[x].companyname + " " + obj.work[x].designation + " " + obj.work[x].year + "</p>";
						};
						return string;
					});
					
					//UPDATE TEXTBOX PROPERTIES
					var i = 1;
					$.each(obj.work, function(){
						$.each(this, function(k, v){	
							$(".work_fields input[name='" + k + i +"']").prop('value',v);
							$(".work_fields input[name='" + k + i +"']").attr('value',v);
						});
						i++;
					});
					
					$('#personal_profile_work .edit_fields').fadeOut();
					handle_fields($('#personal_profile_work'));
					progress_update($('#personal_profile_work'));
				}
			);
			return false;
		}
	});

});

/**************************************************************************************************************/	
/****************************  DELIVERY ADDRESS AND CONSIGNEE DETAILS    **************************************/   
/**************************************************************************************************************/

$(document).ready(function(){
	$("#c_mobile").numeric({negative : false});
	$("#c_telephone").numeric({negative : false});
	$("#c_streetno").numeric({negative : false});
	$("#c_postalcode").numeric({negative : false});
	
	$("#c_deliver_address").validate({
		rules: {
			consignee: {
				required: true
			},
			c_mobile: {
				required: true,
				is_numeric: true
			},
			c_telephone: {
				required: true,
				is_numeric: true
			},
			c_streetno: {
				required: true,
				is_numeric: true
			},
			c_streetname: {
				required: true
			},
			c_citytown: {
				required: true
			},
			c_country: {
				required: true
			}
		},
		messages: {
			consignee: {
				required: '* Consignee name required'
			},
			c_mobile: {
				required: '* Mobile Number required'
			},
			c_telephone: {
				required: '* Telephone Number required'
			},
			c_streetno: {
				required: '* Street or Building Number required'
			},
			c_streetname: {
				required: '* Street required'
			},
			c_citytown: {
				required: '* City or Town required'
			},
			c_country: {
				required: '* Country required'
			}
		},
		errorElement: "span",
		errorPlacement: function(error, element){
				error.addClass('red');
				error.appendTo(element.parent());
		},
		submitHandler: function(form) {
		   $('#load_deliver_address').css('display', 'inline');
		   $.post(config.base_url+'memberpage/edit_consignee_address',$('#c_deliver_address').serializeArray(),
				function(data){
					$('#load_deliver_address').css('display', 'none');
					$('#c_def_address').attr('checked', false);
					var obj = jQuery.parseJSON(data);
					
					//UPDATE INPUTfield ATTRIBUTE and PROPERTY VALUES in Delivery Address Page
					$('#c_deliver_address .inner_profile_fields input').each(function(){
						if($(this).attr('name') !== 'c_def_address'){
							$(this).attr('value', obj[$(this).attr('name')]);
							$(this).prop('value', obj[$(this).attr('name')]);
						}
					});
					
					//IF SET AS DEFAULT ADDRESS
					if(obj['default_add'] == "on"){
						//UPDATE INTPUTfield ATTRIBUTE and PROPERTY VALUES in Personal Information Address
						$('.address_fields input[type="text"]').each(function(){
								$(this).prop('value', obj["c_" + $(this).attr('name')]);
								$(this).attr('value', obj["c_" + $(this).attr('name')]);
						});
						//OVERWRITE DIV in Personal Information Address
						$('.address_information .add_info').html(function(){
							var string = obj['c_streetno'] + " " + obj['c_streetname'] + " " + obj['c_barangay'] + " " + obj['c_citytown'] + " " + obj['c_country'] + " " + obj['c_postalcode'];
							return string;
						});
						$('#personal_profile_address .address_information').show();
						$('#personal_profile_address .edit_profile').hide();
					}
					progress_update($('#c_deliver_address'));
				});
		  return false;
	   }
	});
});

/***********************************************************************************/	
/****************************  TRANSACTIONS    **************************************/   
/***********************************************************************************/
$(document).ready(function(){
	$('.rating1').raty({scoreName: 'rating1'});
	$('.rating2').raty({scoreName: 'rating2'});
	$('.rating3').raty({scoreName: 'rating3'});
	
	jQuery.validator.addMethod("notEqual", function(value, element, param) {
	  return this.optional(element) || value != param;
	}, "Please specify a different (non-default) value");
	
	$('.feedb-star').on('mouseover', function(){
		$(this).siblings('.raty-error').html('');
	});
	
	$('.transac-feedback-btn').on('click', function(){
		$(this).siblings('.transac-feedback-container').fadeIn();
		$(this).fadeOut();
	});
	
	$('.feedback-cancel').on('click', function(){
		$(this).closest('.transac-feedback-container').fadeOut();
		$(this).closest('.transac-feedback-container').siblings('.transac-feedback-btn').fadeIn();
		$(this).siblings('[name="feedback-field"]').val('');
	});
	
	$('.feedback-submit').on('click', function(event){
		var form = $(this).parent('form.transac-feedback-form');
		var feedbackfield = $(this).siblings('[name="feedback-field"]');
		var rating1 = $(this).siblings('div.rating1').children('input[name="rating1"]').val();
		var rating2 = $(this).siblings('div.rating2').children('input[name="rating2"]').val();
		var rating3 = $(this).siblings('div.rating3').children('input[name="rating3"]').val();
		var econt = $(this).siblings('.raty-error');
		
		if($.trim(feedbackfield.val()).length < 1)
			feedbackfield.effect('pulsate',{times:3},800);
		else if(rating1 === '' || rating2 === '' || rating3 ==='')
			econt.html('You cannot submit with a 0 rating score!');
		else{
			$.post(config.base_url+'memberpage/addFeedback',form.serialize(),function(data){
				if(data == 1){
					form.parent().fadeOut();
					form.closest('div.feedback_container').html('<p>Your feedback has been submitted.</p>');
				}
				else{
					form.parent().fadeOut();
					form.closest('div.feedback_container').html('<p class="error red">An error was encountered. Try again later.</p>');
				}
			});
		}
	});
	
});



/*******************	Handle Fields Display	********************************/
function handle_fields(form)
{
	if(form === '')
		var o = $('form.dropdownform');
	else
		var o = form;
		
	o.each(function(){
		var is_empty = true;
		var echoedfield = $(this).find('div.echoed_info');
		var editprof = $(this).find('div.edit_profile');
		var geninfo = $(this).find('div.gen_information');
		
		if ($.trim(echoedfield.html()).length > 0)
			is_empty = false;
		
		if(is_empty){
			editprof.show();
			geninfo.hide();
		}
		else{
			editprof.hide();
			geninfo.show();
		}
	});
}

/***************************** PERSONAL PROFILE PROGRESSBAR	************************************/
function progress_update(form){
	var fcount = 0;
	
	if(form==='')
		var o = $('div.progress_update');
	else
		var o = form.find('div.progress_update');
		
	o.each(function(){
		var thisclass = $(this).attr('class');
		var n = thisclass.indexOf('update_all');
		var m = thisclass.indexOf('update_once');
		
		if(n!=-1 && m==-1){// if update_all
			var count = 0;
			$(this).find('input[type="text"]').each(function(){
				if($(this).prop('value').length > 1)
					count++;
			});
			$(this).find('input[type="radio"]:checked').each(function(){
				count++;
				return false;
			});
			$(this).find('input[type="hidden"].progress_update_hidden').attr('value', count);
		}
		else if(n==-1 && m!=-1){ // if update_once
			var count = 0;
			$(this).find('input[type="text"]').each(function(){
				if($(this).prop('value').length > 1){
					count++;
					return false;
				}
			});
			$(this).find('input[type="hidden"].progress_update_hidden').attr('value', count);
		}
	});
	
	$('input.progress_update_hidden').each(function(){
		fcount += +$(this).attr('value');
	});
	
	var imgdir = $('#user_image').attr('src');
	var n = imgdir.search("/user/default");
	if (n==-1)
		fcount++;
	
	//Total of 9 sections/inputs
	var percentage = Math.ceil(fcount/11 * 100);
	
	$('#progressbar').progressbar({
		value:percentage
	});
	$('#profprog_percentage').attr('value', percentage);
	$('#profprog_percentage').html(percentage + '%');
}


/***************	Image preview for cropping	************************/
function imageprev(input) {

	var jcrop_api, width, height;
	
    if (input.files && input.files[0] && input.files[0].type.match('image.*') && input.files[0].size < 5000001) {
		var reader = new FileReader();

		reader.onload = function(e){
			var image = new Image();
			image.src = e.target.result;
			image.onload = function(){
				width = this.width;
				height = this.height;
				$('#user_image_prev').attr('src', this.src);
				if(width >60 && height > 60 && width <= 1024 && height <= 768)
					deploy_imageprev();
				else if(width > 1024 || height > 768)
					alert('Failed to upload image. Max image dimensions: 1024 x 768');
				else
					$('#div_user_image_prev span:first').html('Preview');
			}
		}
		reader.readAsDataURL(input.files[0]);
    }
	else
		alert('You can only upload gif|png|jpeg files at a max size of 5MB! ');
	
	function deploy_imageprev(){
		$('#div_user_image_prev').modal({
				escClose: false,
				containerCss:{
					maxWidth: 600,
					minWidth: 505,
					maxHeight: 600
				},
				onShow: function(){
					$('#div_user_image_prev button').on('click', function(){
						$('#form_image').submit();
						$.modal.close();
					});
					jcrop_api = $.Jcrop($('#user_image_prev'),{
						aspectRatio: width/height,
						boxWidth: 500,
						minSize: [width*0.1,height*0.1],
						trueSize: [width,height],
						onChange: showCoords,
						onSelect: showCoords,
						onRelease: resetCoords
					});
					this.setPosition();
				},
				onClose: function(){
					$('#user_image_prev').attr('src', '');
					resetCoords();
					jcrop_api.destroy();
					$.modal.close();
				}
			});
	}
}

function showCoords(c){
	$('#image_x').val(c.x);
	$('#image_y').val(c.y);
	$('#image_w').val(c.w);
	$('#image_h').val(c.h);
}

function resetCoords(){
	$('#image_x').val(0);
	$('#image_y').val(0);
	$('#image_w').val(0);
	$('#image_h').val(0);
}

/*************** Personal Profile Dashboard circular progress bar **************/
$(function($) {
	$(".items").knob({
		change : function (value) {
			//console.log("change : " + value);
		},
		release : function (value) {
			//console.log(this.$.attr('value'));
			//console.log("release : " + value);
		},
		cancel : function () {
			//console.log("cancel : ", this);
		},
		draw : function () {

			// "tron" case
			if(this.$.data('skin') == 'tron') {

				var a = this.angle(this.cv)  // Angle
					, sa = this.startAngle          // Previous start angle
					, sat = this.startAngle         // Start angle
					, ea                            // Previous end angle
					, eat = sat + a                 // End angle
					, r = 1;

				this.g.lineWidth = this.lineWidth;

				this.o.cursor
					&& (sat = eat - 0.3)
					&& (eat = eat + 0.3);

				if (this.o.displayPrevious) {
					ea = this.startAngle + this.angle(this.v);
					this.o.cursor
						&& (sa = ea - 0.3)
						&& (ea = ea + 0.3);
					this.g.beginPath();
					this.g.strokeStyle = this.pColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
					this.g.stroke();
				}

				this.g.beginPath();
				this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
				this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
				this.g.stroke();

				this.g.lineWidth = 2;
				this.g.beginPath();
				this.g.strokeStyle = this.o.fgColor;
				this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
				this.g.stroke();

				return false;
			}
		}
	});
});

$(document).ready(function(){

	$(".show_prod_desc").click(function(){
		$(this).siblings('.item_prod_desc_content').addClass('show_desc');
		$(this).fadeOut();
	});

	$(".show_more_options").click(function(){
		$(this).siblings('.attr_hide').slideToggle();
		$(this).toggleClass("active");
	});
 
});

/********************	PAGING FUNCTIONS	************************************************/

$(document).ready(function(){
	$('#active_items .paging:not(:first)').hide();
	$('#deleted_items .paging:not(:first)').hide();
	
	$('#bought .paging:not(:first)').hide();
	$('#sold .paging:not(:first)').hide();
	
	$('#op_buyer .paging:not(:first)').hide();
	$('#op_seller .paging:not(:first)').hide();
	$('#yp_buyer .paging:not(:first)').hide();
	$('#yp_seller .paging:not(:first)').hide();
	
	$('#pagination_active').jqPagination({
		paged: function(page) {
		    $('#active_items .paging').hide();
			$($('#active_items .paging')[page - 1]).show();
		}
	});
	
	$('#pagination_deleted').jqPagination({
		paged: function(page) {
		    $('#deleted_items .paging').hide();
			$($('#deleted_items .paging')[page - 1]).show();
		}
	});
	
	$('#pagination-bought').jqPagination({
		paged: function(page) {
			$('#bought .paging').hide();
			$($('#bought .paging')[page-1]).show();
		}
	});
	
	$('#pagination-sold').jqPagination({
		paged: function(page) {
			$('#bought .paging').hide();
			$($('#bought .paging')[page-1]).show();
		}
	});
	
	
	$('#pagination-opbuyer').jqPagination({
		paged: function(page) {
			$('#op_buyer .paging').hide();
			$($('#op_buyer .paging')[page-1]).show();
		}
	});
	$('#pagination-opseller').jqPagination({
		paged: function(page) {
			$('#op_seller .paging').hide();
			$($('#op_seller .paging')[page-1]).show();
		}
	});
	$('#pagination-ypbuyer').jqPagination({
		paged: function(page) {
			$('#yp_buyer .paging').hide();
			$($('#yp_buyer .paging')[page-1]).show();
		}
	});
	$('#pagination-ypseller').jqPagination({
		paged: function(page) {
			$('#yp_seller .paging').hide();
			$($('#yp_seller .paging')[page-1]).show();
		}
	});
	
});

function triggerTab(x){
	$('.idTabs a[href="#'+x+'"]').trigger('click');
}


/***** create wishlist modal *****/

$(document).ready(function(){
	 $('.wishlist_create').click(function (e) {
		$("#create_wishlist").modal({position: ["25%","35%"]});
		$('#create_wishlist').parent().removeAttr('style');
		});

	 });
		

/*
$(document).ready(function(){
	$("#view_map").click(function(){       
	var streetno = $("#streetno").val();
	var streetname = $("#streetname").val();
	var barangay = $("#barangay").val();
	var citytown = $("#citytown").val();
	var country = $("#country").val();
	var address = streetno + " " + streetname + " Street " + ", " + barangay + " " + citytown + ", " + country;
	$.ajax({
		async:true,
		url:config.base_url+"memberpage/toCoordinates",
		type:"POST",
		dataType:"JSON",
		data:{address:address},
		success:function(data){
			if(data['lat']==false || data['lng']==false){
				alert("Cannot retrieve map,Address is invalid");
			}else{
				var myLatlng =  new google.maps.LatLng(data['lat'],data['lng']);
				$("#map").show();
				google.maps.event.addDomListener(window, 'load', initialize(myLatlng));
			}
		}

		});
	});

	function initialize(myLatlng) {
	var mapOptions = {
	  center:myLatlng,
	  zoom: 15
	};
	var map = new google.maps.Map(document.getElementById("map-canvas"),
		mapOptions);
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title:"You! :)"
		});
	}
});

$(document).ready(function(){
    
	$('#close').click(function () {
		$(this).parent('#map').fadeOut();
		$(this).parent('#map').siblings('#map-canvas').fadeOut();
		$(this).parent('#map').siblings('.view_map_btn').find('#view_map').fadeIn();
	});

	$('#view_map').click(function () {
		$(this).fadeOut();
		$(this).parent('div').siblings('#map-canvas').addClass('map_canvas');
		$(this).parent('div').siblings('#map-canvas').fadeIn();
	});

});

*/
