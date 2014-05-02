/*$(window).load(function(){
	progress_update('');
	handle_fields('');
	
	$('.address_dropdown, .disabled_country').chosen({width:'200px'});
	
});*/

$(document).ready(function(){
	progress_update('');
	handle_fields('');
	
	$('.address_dropdown, .disabled_country').chosen({width:'200px'});
	
});

$(document).ready(function(){
	jQuery.validator.addMethod("select_is_set", function(value, element, arg) {
		return this.optional(element) || (arg != value?true:false);
	 }, "* This field must be set");
	 
	 jQuery.validator.addMethod("is_validmobile", function(value, element) {
		return this.optional(element) || /^9[0-9]{9}/.test(value);
	 }, "Invalid mobile number");
});


/******* rotate sort arrow when click *****/
$(".arrow_sort").on("click", function () {
    $(this).toggleClass("rotate_arrow");
});

/**************************************************************************************************************/	
/**************************************  PERSONAL INFORMATION MAIN    *****************************************/   
/**************************************************************************************************************/		
$(document).ready(function(){

	$(".year").numeric({negative : false});
	$('#mobile').numeric({negative : false});
	
	$('#mobile, #email').on('keydown', function(e){
		return e.which !== 32;
	});
	
	$.datepicker.setDefaults({dateFormat: 'yy-mm-dd'}, $.extend($.datepicker.regional['']));

	$( "#datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
        yearRange: '1950:2005'
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
				minlength: 10,
				maxlength: 10,
				is_validmobile: true
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
					if(data == 1){
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
					else if(data == 0){
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
							return false;
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
	
	/**
	 *	CSRF TOKEN
	 */
	var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
	
	//Mobile verification dialog box
	$('#verifcode_div').dialog({
		autoOpen: false,
		title: "Enter confirmation code",
		modal: true,
		closeOnEscape: false,
		buttons:{
			Submit: function(){
				var $dialog = $(this);
				var val = $('#verifcode').val();
				$.post(config.base_url+'memberpage/verify_mobilecode',{data:val, mobileverify:'true', csrfname : csrftoken}, function(data){
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
				var mobilediv = $('#mobilediv');
				
				$('#verifcode').val('');
				$('#mobile').attr('disabled', true);
				mobilediv.find('span.verify_now').show();
				mobilediv.removeClass('fired');
				mobilediv.find('span.personal_contact_cont').show();
				$('#cont_mobilediv').hide();
				mobilediv.find('span.edit_personal_contact').hide();
				$(this).dialog( "close" );
			}
		}
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
		
		$.post(config.base_url+'memberpage/verify', {field:field, data:data, reverify:'true', csrfname : csrftoken}, function(data){
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
							contdiv.hide();
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
							parentdiv.find('span.doneverify span:nth-child(2)').html('<strong>Email sent.</strong>');
							errorspan.html('');
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
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        
		
		$.post(config.base_url+"memberpage/deletePersonalInfo", {field : name, csrfname : csrftoken}, function(data){
			if(data == 1){
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
				}else if(name=== 'del_address'){
					var addselect = editfields.find('select.address_dropdown');
					addselect.val(0).attr('data-status', "");
					addselect.trigger('chosen:updated');
					clearPersonalAddress();
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
		
		innerfields.find('select:not(.cityselect)').each(function(){
			$(this).val($(this).attr('data-status'));
		});
		
		if(cancelname === 'cancel_school' || cancelname === 'cancel_work'){			
			innerfields.find('div.dynamic_dd').find('input[type="text"]').each(function(){
				if(!($.trim($(this).attr('value')))){
					$(this).closest('div.dynamic_dd').remove();
				}
			});
		}else if( cancelname === 'cancel_address' ){
			var cityselect = innerfields.find('select.cityselect');
			var stateregionselect = innerfields.find('select.stateregionselect');
			cityFilter(stateregionselect, cityselect);
			cityselect.val(cityselect.attr('data-status'));
			cityselect.trigger('chosen:updated');
			stateregionselect.trigger('chosen:updated');
			
			var maplat = $('#map_lat').val();
			var maplng = $('#map_lng').val();
			
			if(maplat == 0 && maplng == 0){
				clearPersonalAddress();
			}
			else{
				$('#personal_mapcanvas').siblings('.map_nav').children('.close').trigger('click');
			}
		}
	});
	
	function clearPersonalAddress(){
		var mapcanvas = $('#personal_mapcanvas');
		$('#map_lat').val(0);
		$('#map_lng').val(0);
		$('#temp_lat').val(0);
		$('#temp_lng').val(0);
		mapcanvas.removeAttr('style');
		mapcanvas.removeClass('map_canvas');
		mapcanvas.children().remove();
		mapcanvas.siblings('.map_nav').children('.close').trigger('click');
	}
	
});

/**************************************************************************************************************/	
/**************************************  PERSONAL INFORMATION ADDRESS    ***************************************/   
/**************************************************************************************************************/
$(document).ready(function(){

	$('.stateregionselect').on('change', function(){
		$(this).valid();
		var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
		cityselect.val(0);
		cityFilter( $(this), cityselect );
	});
	
	$('.cityselect').on('change', function(){
		$(this).valid();
	});
	
	//************	PERSONAL PROFILE ADDRESS VALIDATION	***************//
	$("#personal_profile_address").validate({
		rules: {
			stateregion:{
				required: true,
				select_is_set: '0'
			},
			city:{
				required: true,
				select_is_set: '0'
			},
			address:{
				required: true
			}
		},
		messages: {
			stateregion:{
				required: '* State/Region is required'
			},
			city:{
				required: '* City is required'
			},
			address:{
				required: '* Please enter your full address'
			}
		},
		errorElement: 'span',
		errorPlacement: function(error, element){
				error.addClass('red');
				error.appendTo(element.parent());
		},
		ignore: ":hidden:not(select)",
		submitHandler: function(form){
			$('#load_address').css('display', 'inline');
			$.post(config.base_url+'memberpage/edit_address',$('#personal_profile_address').serializeArray(),
				function(data){
					$(form).find('input[type="submit"]').attr('disabled', false);
					$('#load_address').css('display', 'none');
					
					try{
						var obj = jQuery.parseJSON(data);
					}
					catch(e){
						alert('An error was encountered while submitting your form. Please try again later.');
						window.location.reload(true);
						return false;
					}
					
					if(obj['result'] === 'fail' || obj['result'] === 'error'){
						alert(obj['errmsg']);
						window.location.reload(true);
						return false;
					}else if(obj['result'] === 'success'){					
						//overwrite div to display new address data
						$('.address_information .add_info').html(function(){
							var string = obj['stateregion'] + ", " + obj['city'] + "<br>" + htmlDecode(obj['address']);
							return string;
						});
						
						//update input text attribute value to new values
						$('.address_fields input[type="text"]').each(function(){
							$(this).attr('value',htmlDecode(obj[$(this).attr('name')]));
						});
						
						//update address drop down select fields
						$('.address_fields select.address_dropdown').each(function(){
							$(this).attr('data-status', htmlDecode(obj[$(this).attr('name') + 'ID']));
						});
						
						//fix display of address depending on contents
						handle_fields($('#personal_profile_address'));
						progress_update($('#personal_profile_address'));
						$('#personal_profile_address .edit_fields').fadeOut();
						
						// Copy dragged marker coordinates to scanned input for saving - google maps
						$('#map_lat').val(obj['lat']);
						$('#map_lng').val(obj['lng']);
						
						// Update original checker fields
						$('.address_fields input[name="stateregion_orig"]').val(obj['stateregionID']);
						$('.address_fields input[name="city_orig"]').val(obj['cityID']);
						$('.address_fields input[name="address_orig"]').val(htmlDecode(obj['address']));
						
						// Map notification status
						if(obj['lat'] == 0 && obj['lng'] == 0){
							$('#personal_profile_address span.maploc_stat').html('Location not marked');
                            $('#personal_profile_address span.maploc_stat').css('color', '#f18200');
						}else{
							$('#personal_profile_address span.maploc_stat').html('Location marked');
                            $('#personal_profile_address span.maploc_stat').css('color', '#287520');
						}
					}
				});
			$(form).find('input[type="submit"]').attr('disabled', true);
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
	
	$("#personal_profile_school").validate({
		rules: {
			schoolname1:{
				required: true
			},
			schoolyear1:{
				required: true,
				is_numeric: true
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
					$(form).find('input[type="submit"]').attr('disabled', false);
					$('#load_school').css('display', 'none');
					
					try{
						var obj = jQuery.parseJSON(data);
					}
					catch(e){
						alert('An error was encountered while submitting your form. Please try again later.');
						window.location.reload(true);
						return false;
					}
					
					if(obj['result'] === 'fail' || obj['result'] === 'error'){
						alert(obj['errmsg']);
						window.location.reload(true);
						return false;
					}else if(obj['result'] === 'success'){
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
						
						$(form).find('.edit_fields').fadeOut();
						handle_fields($(form));
						progress_update($(form));
					}
				});
			$(form).find('input[type="submit"]').attr('disabled', true);
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
					$(form).find('input[type="submit"]').attr('disabled', false);
					
					try{
						var obj = jQuery.parseJSON(data);
					}
					catch(e){
						alert('An error was encountered while submitting your form. Please try again later.');
						window.location.reload(true);
						return false;
					}
					
					if(obj['result'] === 'fail' || obj['result'] === 'error'){
						alert(obj['errmsg']);
						window.location.reload(true);
						return false;
					}else if(obj['result'] === 'success'){	
						var string = "";
						
						//UPDATE DIV CONTENTS
						$('#personal_profile_work .work_info').html(function(){
							for(var x=0; x<obj.work.length; x++){
								string += "<p>" + htmlDecode(obj.work[x].companyname) + " " + htmlDecode(obj.work[x].designation) + " " + htmlDecode(obj.work[x].year) + "</p>";
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
				});
			$(form).find('input[type="submit"]').attr('disabled', true);
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
	
	$("#c_deliver_address").validate({
		rules: {
			consignee: {
				required: true
			},
			c_mobile: {
				number: true,
				minlength: 10,
				maxlength: 10,
				is_validmobile: true,
				required: true
			},
			c_telephone: {
				//required: true,
				is_numeric: true
			},
			c_stateregion:{
				required: true,
				select_is_set: '0'
			},
			c_city:{
				required: true,
				select_is_set: '0'
			},
			c_address:{
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
			c_stateregion:{
				required: '* State/Region is required'
			},
			c_city:{
				required: '* City is required'
			},
			c_address:{
				required: '* Please enter your full address'
			}
			
		},
		ignore: ":hidden:not(select)",
		errorElement: "span",
		errorPlacement: function(error, element){
				error.addClass('red');
				error.appendTo(element.parent());
		},
		submitHandler: function(form) {
		   $('#load_cdeliver_address').css('display', 'inline');
		   var formdata = $(form).serializeArray();
		   $.post(config.base_url+'memberpage/edit_consignee_address',formdata,
				function(data){
					$('#c_deliver_address_btn').attr('disabled', false);
					$('#load_cdeliver_address').css('display', 'none');
					$('#c_def_address').attr('checked', false);
					
					try{
						var obj = jQuery.parseJSON(data);
					}
					catch(e){
						alert('An error was encountered while submitting your form. Please try again later.');
						window.location.reload(true);
						return false;
					}
					
					//UPDATE INPUTfield ATTRIBUTE and PROPERTY VALUES in Delivery Address Page
					$('#c_deliver_address .inner_profile_fields input[type="text"]').each(function(){
						$(this).attr('value', htmlDecode(obj[$(this).attr('name')]));
						$(this).prop('value', htmlDecode(obj[$(this).attr('name')]));						
					});
					
					// UPDATE SELECT FIELDS
					$('#c_deliver_address .inner_profile_fields select.address_dropdown').each(function(){
						$(this).attr('data-status', htmlDecode(obj[$(this).attr('name') + 'ID']));
						$(this).trigger('chosen:updated');
					});
					
					// Update map coordinates and status
					$('#map_clat').val(obj['c_lat']);
					$('#map_clng').val(obj['c_lng']);
					
					// Map notification status
					if(obj['c_lat'] == 0 && obj['c_lng'] == 0){
						$('#c_deliver_address span.maploc_stat').html('Location not marked');
                        $('#c_deliver_address span.maploc_stat').css('color', '#f18200');
                    }else{
						$('#c_deliver_address span.maploc_stat').html('Location marked');
                        $('#c_deliver_address span.maploc_stat').css('color', '#287520');
					}
					
					// Update orig checker fields
					$('.delivery_address_content input[name="stateregion_orig"]').val(obj['stateregionID']);
					$('.delivery_address_content input[name="city_orig"]').val(obj['cityID']);
					$('.delivery_address_content input[name="address_orig"]').val(htmlDecode(obj['address']));
					
					//IF SET AS DEFAULT ADDRESS
					if(obj['default_add'] == "on"){
						//UPDATE INTPUTfield ATTRIBUTE and PROPERTY VALUES in Personal Information Address
						$('.address_fields input[type="text"]').each(function(){
								$(this).prop('value', htmlDecode(obj["c_" + $(this).attr('name')]));
								$(this).attr('value', htmlDecode(obj["c_" + $(this).attr('name')]));
						});
						
						//Update Select Fields
						$('.address_fields select.address_dropdown').each(function(){
							$(this).attr('data-status', htmlDecode(obj[$(this).attr('name') + 'ID']));
							$(this).val(htmlDecode(obj[$(this).attr('name') + 'ID']));
							$(this).trigger('chosen:updated');
						});
						
						// Update orig checker fields
						$('.address_fields input[name="stateregion_orig"]').val(obj['stateregionID']);
						$('.address_fields input[name="city_orig"]').val(obj['cityID']);
						$('.address_fields input[name="address_orig"]').val(htmlDecode(obj['address']));
						
						//OVERWRITE DIV in Personal Information Address
						$('.address_information .add_info').html(function(){
							var string = obj['c_stateregion'] + ", " + obj['c_city'] + "<br>" + htmlDecode(obj['c_address']);
							return string;
						});
						
						//Update Map Coordinates
						$('.inner_profile_fields').find('div.view_map_btn input[type="hidden"][name$="lat"]').val(obj['c_lat']);
						$('.inner_profile_fields').find('div.view_map_btn input[type="hidden"][name$="lng"]').val(obj['c_lng']);
						
						//Map notification status
						if(obj['c_lat'] == 0 && obj['c_lng'] == 0){
							$('#personal_profile_address span.maploc_stat').html('Location not marked');
                            $('#personal_profile_address span.maploc_stat').css('color', '#f18200');
                        }else{
							$('#personal_profile_address span.maploc_stat').html('Location marked');
                            $('#personal_profile_address span.maploc_stat').css('color', '#287520');
                        }
						
						$('#personal_profile_address .address_information').show();
						$('#personal_profile_address .edit_profile').hide();
						progress_update($('#personal_profile_address'));
					}
					progress_update($('#c_deliver_address'));
				});
		  $('#c_deliver_address_btn').attr('disabled', true);
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
			$(this).hide();
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
	
	$('.transac_response_btn').on('click', function(){
		var form = $(this).closest('form.transac_response');
		var thisbtn = $(this);
		var parentdiv = $(this).closest('div');
		$.post(config.base_url+"memberpage/transactionResponse", form.serializeArray(), function(data){
			try{
				var serverResponse = jQuery.parseJSON(data);
			}
			catch(e){
				alert('An error was encountered while processing your data. Please try again later.');
				//window.location.reload(true);
				return false;
			}
			
			if(serverResponse.error.length > 0){
				alert(serverResponse.error);
			}
			
			if(serverResponse.result === 'success'){
				parentdiv.html('<span class="trans_alert transac_req_submit">Request submitted.</span>');
			}else if(serverResponse.result === 'fail'){
				parentdiv.html('<span class="trans_alert">Failed to update status.</span>');
			}
		});
		$(this).val('Please wait');
		$(this).attr('disabled', true);
		return false;
	});
	
	// DRAGON PAY RESPONSE
	/*$('.dragonpayupdate').on('click', function(){
		var form = $(this).closest('form.dragonpay_update');
		
		$.post(config.base_url+'memberpage/transactionResponse', form.serializeArray(), function(){
			try{
				var serverResponse = jQuery.parseJSON(data);
			}
			catch(e){
				alert('An error was encountered while processing your data. Please try again later.');
				window.location.reload(true);
				return false;
			}
		});
	});
	*/
});


/*******************	HTML Decoder	********************************/
function htmlDecode(value) {
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}

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
		
		if ($.trim(echoedfield.html()).length > 0){
			is_empty = false;
		}
		
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

/**************************	CITY FILTER SELECT	**************************************/
/*
 *	Function to generate provinces in dropdown.
 */
function cityFilter(stateregionselect,cityselect){
	var stateregionID = stateregionselect.find('option:selected').attr('value');
	var optionclone = cityselect.find('option.optionclone').clone();
	optionclone.removeClass('optionclone').addClass('echo').attr('disabled', false);

	cityselect.find('option.echo').remove();
	
	if(stateregionID in jsonCity){
		jQuery.each(jsonCity[stateregionID], function(k,v){
			//optionclone.attr('value', k).html(v).show();
			optionclone.attr('value', k).html(v).css('display', 'block');
			cityselect.append(optionclone.clone());
		});
	}
	
	cityselect.trigger('chosen:updated');
	
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
		var c = thisclass.indexOf('update_consignee');
		
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
		else if(c!=-1){ // for consignee
			var count = 0;
			// reads consignee (includes select and address since required), 
			// mobile, and telephone fields
			$(this).find('input[type="text"]:not([name="c_telephone"])').each(function(){
				if($(this).prop('value').length > 1){
					count++;
					return false;
				}
			});
			
			if( $(this).find('input[name="c_telephone"]').prop('value').length > 1 ){
				count++;
			}
			
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
	
	// 12 inputs/sets
	var percentage = Math.ceil(fcount/12 * 100);
	
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
	$('#complete_buy .paging:not(:first)').hide();
	$('#complete_sell .paging:not(:first)').hide();
	
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
			$('#sold .paging').hide();
			$($('#sold .paging')[page-1]).show();
		}
	});
	
	$('#pagination-complete-bought').jqPagination({
		paged: function(page) {
			$('#complete_buy .paging').hide();
			$($('#complete_buy .paging')[page-1]).show();
		}
	});
	
	$('#pagination-complete-sold').jqPagination({
		paged: function(page) {
			$('#complete_sell .paging').hide();
			$($('#complete_sell .paging')[page-1]).show();
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

function setDefaultPagination(pagingButton, pagingDiv) {
	pagingButton.jqPagination({
		paged: function(page) {
		    pagingDiv.hide();
			$(pagingDiv[page - 1]).show();
		}
	});
	pagingButton.jqPagination('option','current_page', 1);
}

function setFilterResultPagination(pagingButton, filterDiv, resultCounter){
	pagingButton.jqPagination('destroy');
	pagingButton.jqPagination({
		max_page: Math.ceil((resultCounter===0 ? 10:resultCounter) / 10),
		paged: function(page) {
			filterDiv.hide();
			$(filterDiv[page-1]).show();
		}
	});
	pagingButton.jqPagination('option', 'current_page', 1);
	filterDiv.eq(0).show();
}


/***** create wishlist modal *****/
$(document).ready(function(){
	 $('.wishlist_create').click(function (e) {
		$("#create_wishlist").modal({position: ["25%","35%"]});
		$('#create_wishlist').parent().removeAttr('style');
		});

	 });
		

/******************	DASHBOARD Search Box	********************/
$(function(){
	var schResult = [];
	var schValue = '';
	
	$('.sch_btn').on('click', function(){
		// Remove filter result and re-append new one
		var divActiveItems = $(this).closest('div.dashboard_table');
		divActiveItems.children('div.filter_result').remove();
		divActiveItems.append('<div class="filter_result" style="display:none;"></div>');
		var filterDiv = divActiveItems.children('div.filter_result:last');
		var sortSelect = $(this).siblings('.sort_select');
		var sortArrow = $(this).siblings('.arrow_sort');
		var paginationButton = $(this).parent('div').siblings('div.pagination');
		var divPaging = divActiveItems.children('div.paging');
		var resultCounter = 0;
		var schValue = $(this).siblings('input.sch_box').val().toLowerCase().replace(/\s/g,'');
		sortSelect.val('date');
		sortArrow.removeClass('rotate_arrow');
		$(this).siblings('img.loading_img').show();
		
		if(schValue !== ''){
			divPaging.hide();
			
			//cycle through each Product Title
			divPaging.children('div.post_items_content').each(function(){
				var prodTitle = $(this).find('div.post_item_content_right').find('.post_item_product_title a').text();
				prodTitle = prodTitle.toLowerCase().replace(/\s/g,'');
				
				// Search for search string in product title
				if(prodTitle.indexOf(schValue) != -1){
					if(resultCounter % 10 === 0 && resultCounter !== 0){
						divActiveItems.append('<div class="filter_result" style="display:none;"></div>');
						filterDiv = divActiveItems.children('div.filter_result:last');
					}
					filterDiv.append($(this).clone());
					resultCounter++;
				}
			});
			var divFilter = divActiveItems.children('div.filter_result');
			setFilterResultPagination(paginationButton, divFilter, resultCounter);
			
			if( !sortSelect.hasClass('hasSearch') ) {
				sortSelect.addClass('hasSearch');
			}
		}
		else if(schValue === ''){
			divActiveItems.children('div.filter_result').remove();
			divActiveItems.children('div.paging:first').show();
			paginationButton.jqPagination('destroy');
			//setDefaultActivePagination();
			setDefaultPagination(paginationButton, divPaging);
			sortSelect.removeClass('hasSearch');
		}
		$(this).siblings('img.loading_img').hide();
	});
	
	// Trigger Search on 'Enter' key press
	$('.sch_box').on('keydown', function(e){
		var code = e.keyCode || e.which;
		if(code===13){
			$(this).siblings('.sch_btn').trigger('click');
		}
	});
	
});


/*******************	DASHBOARD - SORT	**************************/
$(function(){
	
	function sortNameDesc(a,b){
		return $(a).find('.product_title_container').find('a').text().toLowerCase() < $(b).find('.product_title_container').find('a').text().toLowerCase() ? 1 : -1;
	}
	
	function sortPriceDesc(a,b){
		var pricea = parseFloat($(a).find('.price_container').attr('data-prodprice'));
		var priceb = parseFloat($(b).find('.price_container').attr('data-prodprice'));
		return priceb-pricea;
	}
	
	function sortDateDesc(a,b){
		var datea = $(a).attr('data-order');
		var dateb = $(b).attr('data-order');
		return datea-dateb;
	}
	
	$('.sort_select').on('change', function(){
		var selectedOption = $(this).find('option:selected');
		var sortVals = [];
		var resultCounter = 0;
		$(this).siblings('img.loading_img').show();
		
		if( $(this).hasClass('hasSearch') ){
			var parentDiv = $(this).closest('div.dashboard_table').children('div.filter_result').find('div.post_items_content');
			var contDiv = $(this).closest('div.dashboard_table').children('div.filter_result');
		}
		else{
			var parentDiv = $(this).closest('div.dashboard_table').children('div.paging').find('div.post_items_content');
			var contDiv = $(this).closest('div.dashboard_table').children('div.paging')
		}
		
		switch(selectedOption.val()){
			case 'date':
				sortVals = parentDiv.sort(sortDateDesc);
				break;
			case 'name':
				sortVals = parentDiv.sort(sortNameDesc);
				break;
			case 'price':
				sortVals = parentDiv.sort(sortPriceDesc);
				break;
			default:
				break;
		}
		
		if( $(this).siblings('.arrow_sort').hasClass('rotate_arrow') ){
			sortVals = $(sortVals.get().reverse());
		}
		
		// Re-order results
		var resultCounter = divPosition = 0;
		contDiv.children().remove();
		$.each(sortVals, function(k,v){
			if(resultCounter === 10){
				resultCounter = 0;
				divPosition++;
			}
			contDiv.eq(divPosition).append($(v));
			resultCounter++;
		});
		
		$(this).siblings('img.loading_img').hide();
	});
	
	$('.arrow_sort').on('click', function(){
		var parentDiv = $(this).closest('div.dashboard_table');
		$(this).siblings('img.loading_img').show();
		if( parentDiv.children('div.filter_result').length !==0 ){
			var divPostItems = parentDiv.children('div.filter_result').children('div.post_items_content');
			var divCont = parentDiv.children('div.filter_result');
		}
		else{
			var divPostItems = parentDiv.children('div.paging').children('div.post_items_content');
			var divCont = parentDiv.children('div.paging');
		}
		var resultCounter = divPosition = 0;
		$(divPostItems.get().reverse()).each(function(){
			if(resultCounter === 10){
				resultCounter = 0;
				divPosition++;
			}
			divCont.eq(divPosition).append($(this).clone());
			$(this).remove();
			resultCounter++;
		});
		$(this).siblings('img.loading_img').hide();
	});
	
});

/***********************	GOOGLE MAPS		***************************/

$(document).ready(function(){
	var mapPersonal, markerPersonal, geocoder;
	var mapDelivery, markerDelivery;

	$(".refresh_map").click(function(){		
		if($(this).attr('name') === 'personal_rmap'){
			var stateregion = $('#personal_stateregion').find('option:selected').text();
			var city = $('#personal_city').find('option:selected').text();
			var type = "personal";
		}
		else if($(this).attr('name') === 'delivery_rmap'){
			var stateregion = $('#delivery_stateregion').find('option:selected').text();
			var city = $('#delivery_city').find('option:selected').text();
			var type = "delivery";
		}
		
		var address = stateregion + " " + city + " PH";
		codeAddress(address, type);
	});

	function codeAddress(address, type) {
	  geocoder = new google.maps.Geocoder();
	  geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			google.maps.event.addDomListener(window, 'load', initialize(results[0].geometry.location, type));
		} else {
			if(type==='personal'){
				$('#personal_mapcanvas').siblings('.map_nav').children('.close').trigger('click');
			}else if(type==='delivery'){
				$('#delivery_mapcanvas').siblings('.map_nav').children('.close').trigger('click');
			}
			alert('Location invalid');
		}
	  });
	}
	
	//all DOM elements accessed via id
	function initialize(myLatlng, type) {
		var mapOptions = {
		  center:myLatlng,
		  zoom: 15
		};
		if( type === 'personal' ){
			var templat = $('#temp_lat');
			var templng = $('#temp_lng');
			mapPersonal = new google.maps.Map(document.getElementById("personal_mapcanvas"),mapOptions);
			markerPersonal = new google.maps.Marker({
				position: myLatlng,
				map: mapPersonal,
				title:"I'm here!",
				draggable: true
			});
			google.maps.event.addListener(markerPersonal, 'dragend', function(evt){
				templat.val(evt.latLng.lat());
				templng.val(evt.latLng.lng());
				
				window.setTimeout(function(){
					mapPersonal.panTo(markerPersonal.getPosition());
				}, 500);
			});
			google.maps.event.addListenerOnce(mapPersonal, 'idle', function(){
				google.maps.event.trigger(mapPersonal, 'resize');
				window.setTimeout(function(){
					mapPersonal.panTo(markerPersonal.getPosition());
				}, 500);
			});
		}
		else if( type === 'delivery' ){
			var templat = $('#temp_clat');
			var templng = $('#temp_clng');
			mapDelivery = new google.maps.Map(document.getElementById("delivery_mapcanvas"),mapOptions);
			markerDelivery = new google.maps.Marker({
				position: myLatlng,
				map: mapDelivery,
				title:"I'm here!",
				draggable: true
			});
			google.maps.event.addListener(markerDelivery, 'dragend', function(evt){
				templat.val(evt.latLng.lat());
				templng.val(evt.latLng.lng());
				
				window.setTimeout(function(){
					mapDelivery.panTo(markerDelivery.getPosition());
				}, 500);
			});
			google.maps.event.addListenerOnce(mapDelivery, 'idle', function(){
				google.maps.event.trigger(mapDelivery, 'resize');
				window.setTimeout(function(){
					mapDelivery.panTo(markerDelivery.getPosition());
				}, 500);
			});
		}
		templat.val(myLatlng.lat());
		templng.val(myLatlng.lng());
	}
	
	$('.view_map').click(function () {
		var maplat = $(this).siblings('input[name="map_lat"]').val();
		var maplng = $(this).siblings('input[name="map_lng"]').val();
		var refreshmapbtn = $(this).parent('div').siblings('div.map_nav').children('span.refresh_map');
		var mapcanvas = $(this).parent('div').siblings('div.map-canvas');
		var type = this.name;
		
		if (maplat == 0 && maplng == 0){
			refreshmapbtn.trigger('click');
		}else{
			var myLatlng =  new google.maps.LatLng(maplat,maplng);
			if(mapcanvas.hasClass('map_canvas')){
				if( type === 'personal' ){
					mapPersonal.setCenter(myLatlng);
					markerPersonal.setPosition(myLatlng);
				}
				else if( type === 'delivery' ){
					mapDelivery.setCenter(myLatlng);
					markerDelivery.setPosition(myLatlng);
				}
			}else{
				google.maps.event.addDomListener(window, 'load', initialize(myLatlng, type));
			}
		}	
		
		$(this).fadeOut();
		$(this).parent('div').siblings('.map_nav').fadeIn();
		$(this).parent('div').siblings('.map-canvas').addClass('map_canvas');
		$(this).parent('div').siblings('.map-canvas').fadeIn();				
	});
	
	$('.current_loc').on('click', function(){
		var maplat = $(this).parent('div').siblings('div.view_map_btn').children('input[name="map_lat"]').val();
		var maplng = $(this).parent('div').siblings('div.view_map_btn').children('input[name="map_lng"]').val();
		var type = $(this).attr('name');

		if(maplat != 0 && maplng != 0){
			var myLatlng =  new google.maps.LatLng(maplat,maplng);
			if( type === 'personal_cmap' ){
				mapPersonal.setCenter(myLatlng);
				markerPersonal.setPosition(myLatlng);
			}
			else if( type === 'delivery_cmap'){
				mapDelivery.setCenter(myLatlng);
				markerDelivery.setPosition(myLatlng);
			}
		}else{
			alert('You have not marked your location yet.');
		}
	});
	
	$('.close').click(function () {
		$(this).parent('div').fadeOut();
		$(this).parent('div').siblings('.map-canvas').fadeOut();
		$(this).parent('div').siblings('.view_map_btn').find('.view_map').fadeIn();
	});
	
});


/*******************	TRANSACTION MAPS	********************************************/
$(document).ready(function(){
	
	var map, marker;
	
	$('.tsold_viewmap').on('click', function(){
	
		var maplat = $(this).attr('data-lat');
		var maplng = $(this).attr('data-lng');
		var myLatlng =  new google.maps.LatLng(maplat,maplng);

		$('#map_modalcont').modal({
			onShow: function(){
				google.maps.event.addDomListener(window, 'load', initialize(myLatlng));
				this.setPosition();
			},
			onClose: function(){
				$.modal.close();
			}
		});
	});
	
	function initialize(myLatlng) {
		var mapOptions = {
		  center:myLatlng,
		  zoom: 15
		};
		map = new google.maps.Map(document.getElementById("tsold_mapview"), mapOptions);
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title:"I'm here!"
		});
	}
	
});



/*******************	Billing Info - Bank Selection	********************************/


$(document).ready(function(){
	
	//// GET BANK DROPDOWN - START
	(function($){
	   $.fn.getbank = function(selected) {
			
			var appendTarget = "#" + this.attr('id');
			var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
			
			$.getJSON('memberpage/bank_info',{q:'%',name:'',csrfname:csrftoken},function(data){
				var html = '';
				var len = data.length;
				for (var i = 0; i< len; i++) {
					html += '<option value="' + data[i].id + '" title="' + data[i].name + '">' + data[i].name + '</option>';
				}
				$(appendTarget).append(html);
			
				var SelectedValue = selected;
				$(appendTarget + " option").filter(function(){
					return $(this).text() == SelectedValue;
				}).first().prop("selected", true);
			});
		  	return this;	   
	   }; 
	})(jQuery);
	//// GET BANK DROPDOWN - END	
		
	$('#abi_btn').click(function(){
		$('#abi').toggle("slow");
	});
	
    $('#bi_bank').selectize();
	
	////// START /////////////////////////////////////////////////////////////////////
	
	// CHECKBOX
	$(":checkbox[name^='bi_chk_bictr']").click(function(){
    	$('input:checkbox').not(this).prop("checked", false);
	});
	
	// DELETE BUTTON
	$(":button[name^='del_bictr']").click(function(){
	
		var getbictr = $(this).attr('name');
		var bictr = getbictr.substring(4,30);
		var bid = 'bi_id_' + bictr;
		var del = confirm("Delete bank info?");
		
		if(del){
						
			var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
			var bidval = $("#"+bid).val();				
			var currentRequest = null;
			var redurl =  config.base_url+'memberpage/billing_info_d';
			currentRequest = jQuery.ajax({
				type: "POST",
				url: redurl, 
				data: {bi_id:bidval, csrfname:csrftoken},
				success: function(data){
					$("#bi_div_" + bictr).remove();
					$("#ubi_" + bictr).remove();
					alert("Bank info successfully deleted!");
					return false;
				}
			});		
		}
	
	});
	
	// EDIT BUTTON
	$(":button[name^='bictr']").click(function(){
		
		$("#billing_info_x").find('div[id*=bi_check_bictr]').hide();
		
		var bictr = $(this).attr('name');
		var ban = 'bi_ban_' + bictr;
		var bar = 'bi_bar_' + bictr;
		var bn = 'bi_bn_' + bictr;
		var bns = 'bi_bns_' + bictr;
		var bid = 'bi_id_' + bictr;
		var bch = 'bi_chk_' + bictr;

		
		$("#"+ban).prop("disabled", false);
		$("#"+bar).prop("disabled", false);
		$("#"+bn).prop("disabled", false);
		$("#"+bch).prop("disabled", false);
		
		$("#sv_"+bictr+", #cn_"+bictr+", #bi_bns_"+bictr).show("fast");		
		$("#del_"+bictr+", #"+bictr+", #bi_bn_"+bictr).hide("fast");
		$(":button[name^='bictr']").prop("disabled", true);
		$(":button[name^='del_bictr']").prop("disabled", true);
		
		var bankname = $('#bi_bn_' + bictr).val();	
		$('#bi_bns_' + bictr).getbank(bankname).show();
			
	});
	
	// Cancel Button
	$(":button[name^='cn_bictr']").click(function(){
		
		var getbictr = $(this).attr('name');
		var bictr = getbictr.substring(3,99);
		
		var ban = 'bi_ban_' + bictr;
		var bar = 'bi_bar_' + bictr;
		var bn = 'bi_bn_' + bictr;
		var bns = 'bi_bns_' + bictr;
		var bid = 'bi_id_' + bictr;
		var bch = 'bi_chk_' + bictr;
		
		$(":input[name^='hbi_chk_bictr']").filter(function(){
			
			var hid = $(this).attr('id');
			var fid = hid.substring(1,30);
			
			if($(this).val() == "checked"){
				$("#"+fid).prop("checked", true);
			}else{
				$("#"+fid).prop("checked", false);
			}
		});
		
		$("#"+ban).val($("#h"+ban).val());
		$("#"+bar).val($("#h"+bar).val());
		$("#"+bn).val($("#h"+bn).val());
		
		$("#"+ban).prop("disabled", true);
		$("#"+bar).prop("disabled", true);
		$("#"+bn).prop("disabled", true);
		$("#"+bch).prop("disabled", true);			

		$("#sv_"+bictr+", #cn_"+bictr+", #bi_bns_"+bictr).hide();		
		$("#del_"+bictr+", #"+bictr+", #bi_bn_"+bictr).show();
		$(":button[name^='bictr']").prop("disabled", false);
		$(":button[name^='del_bictr']").prop("disabled", false);
	   
		$(this).closest('form').find('span.error').remove();
	});
	
	// Save Button
	$(":button[name^='sv_bictr']").click(function(){
		
		var banRule = {
			required: true,
			messages: {
				required: "* Account Name Required"
			}
		}

		var barRule = {
//			required: true, minlength: 12, maxlength: 18, 
			required: true,  
			messages: {
				required: "* Account Number Required",
//				minlength: jQuery.format("At least {0} characters are necessary")
			}
		}
		
		var bnRule = {
			required: true,
			messages: {
				required: "* Bank Required"
			}
		}			
		
		var getbictr = $(this).attr('name');
		var bictr = getbictr.substring(3,99);
		
		var ban = 'bi_ban_' + bictr;
		var bar = 'bi_bar_' + bictr;
		var bn = 'bi_bn_' + bictr;
		var bns = 'bi_bns_' + bictr;
		var bid = 'bi_id_' + bictr;
		var bch = 'bi_chk_' + bictr;
		var updt = confirm("Update bank info?");
		
		if(updt){
			 $("#ubi_"+bictr).validate({
				errorElement: "span",
				errorPlacement: function(error, element){
						error.addClass('red');
						error.appendTo(element.parent());
				}		
			 });
			
			$("[name='bi_ban_"+bictr+"']").rules("add", banRule);
			$("[name='bi_bar_"+bictr+"']").rules("add", barRule);
			$("[name='bi_bn_"+bictr+"']").rules("add", bnRule);
			
			var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
			var banval = $("#"+ban).val();
			var barval = $("#"+bar).val();
			var bnval = $("#"+bns).val();
			var bntit = $("#"+bns).find("option:selected").attr("title");
			var bidval = $("#"+bid).val();
			var bchval = $("#"+bch).val();			
			var currentRequest = null;
			
			var redurl = config.base_url+'memberpage/billing_info_u';
			if($("#ubi_"+bictr).valid()){	
				currentRequest = jQuery.ajax({
					type: "POST",
					url: redurl, 
					data: {bi_acct_name:banval, bi_acct_no:barval, bi_bank:bnval, bi_id:bidval, bi_def:bchval, csrfname:csrftoken},
					success: function(data){
						
						$(":checkbox[name^='bi_chk_bictr']").filter(function(){
							var hid = $(this).attr('id');
							if($(this).prop("checked") == true){
								$("#h"+hid).val("checked");
							}else{
								$("#h"+hid).val("");
							}
						});							
						
						$("#bi_check_"+bictr).show().delay(1600).fadeOut(600);
						$("#h"+ban).val($("#"+ban).val());
						$("#h"+bar).val($("#"+bar).val());
						$("#h"+bn).val(bntit);
						$("#"+bn).val(bntit);							
						
						$("#"+ban).prop("disabled", true);
						$("#"+bar).prop("disabled", true);
						$("#"+bn).prop("disabled", true);
						$("#"+bch).prop("disabled", true);			
			
						$("#sv_"+bictr+", #cn_"+bictr+", #bi_bns_"+bictr).hide();		
						$("#del_"+bictr+", #"+bictr+", #bi_bn_"+bictr).show();
						$(":button[name^='bictr']").prop("disabled", false);
						$(":button[name^='del_bictr']").prop("disabled", false);							
						return false;
					}
				});		
			}	
		}else{
			return false;
		}
	});
	
	
	/////// END /////////////////////////////////////////////////////////////

});

$(document).ready(function(){
	 $("#billing_info").validate({
		 ignore: ':hidden:not([class~=selectized]),:hidden > .selectized, .selectize-control .selectize-input input',
		 rules: {
			bi_bank: {
				required: true			
			},
			bi_acct_name: {
				required: true		
			},			
			bi_acct_no: {
				//required: true, minlength: 12, maxlength: 18
				required: true					
			} 
		 },
		 messages:{
			bi_bank: {
				required: '* Bank Required'
			},
			bi_acct_name: {
				required: '* Account Name Required'
			},
			bi_acct_no: {
				required: '* Account Number Required'
			}						
		 },
		errorElement: "span",
		errorPlacement: function(error, element) {
				error.addClass('red');
				error.appendTo(element.parent());
		}
			 	
	 });
	 	 
	var redurl =  config.base_url+'memberpage/billing_info';
    $("#billing_info_btn").click(function() {
		if($("#billing_info").valid()){	
			jQuery.ajax({
				type: "POST",
				url: redurl, 
				data: $("#billing_info").serialize(),
				success: function(data){
					$("#bi_bank, #bi_acct_name, #bi_acct_no").val('');
					window.location.href = config.base_url+'me?me=pmnt';
				}
			});		
		}		
    });	
	
});
