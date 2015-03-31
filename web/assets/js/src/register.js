/**********************************************************************/
/**************************	SIGNUP/REGISTER ***************************/
/**********************************************************************/

var ajaxStat = {};

(function( $ ) {
    $(".show-why-create-mobileview").click( function(){
        $(".why-create-mobileview").slideToggle();
    });

    $(".open-create-account").click( function(){
        $("#alter-tab div:first-child").children().removeClass("selected");
        $("#alter-tab div:first-child").next().children().addClass("selected");
        $("#login").fadeOut(0);
        $("#create-account").fadeIn(300);
    });

    $(".terms_and_conditions").click( function(){
        $("#terms-section").fadeIn();
    });

    $(".close-term").click( function(){
        $("#terms-section").fadeOut();
    });

    $("#adv2").idTabs(function(id,list,set){ 
        $("a",set).removeClass("selected") 
        .filter("[href='"+id+"']",set).addClass("selected");

        for(i in list) {
          $(list[i]).hide();
        }

        $(id).fadeIn(); 
        return false; 
    }); 

})(jQuery);


jQuery(document).ready(function(){
    jQuery('#username').on('focus', function() {
        jQuery(document).bind('focusin.example click.example',function(e) {
            if (jQuery(e.target).closest('.username_info, #username').length) return;
            jQuery(document).unbind('.example');
        });
    }).on('blur', function(){
        var fieldlength = jQuery.trim(jQuery('#username').val()).length;
        if(fieldlength >= 5 && jQuery(this).hasClass('forSearch') && jQuery(this).hasClass('valid')){
            setTimeout(username_check,500);
        }
        else if(fieldlength < 5){
            hidecheckx(jQuery('#username'));
            jQuery('.username_availability').html('');
        }
    }).on('input', function(){
    
        var fieldlength = jQuery.trim(jQuery('#username').val()).length;
        hidecheckx(jQuery(this));
        jQuery('.username_availability').html('');
        
        jQuery(this).removeClass('pass');
        
        if(!jQuery(this).hasClass('forSearch') && fieldlength >= 5){
            jQuery(this).addClass('forSearch');
        }
        else if(fieldlength < 5){
            jQuery(this).removeClass('forSearch');
            hidecheckx(jQuery('#username'));
            jQuery('.username_availability').html('');
        }
    });
    
    jQuery('div.pass-container').show();
    jQuery('#password').focus(function() {
        jQuery(document).bind('focusin.example click.example',function(e) {
            if (jQuery(e.target).closest('.password_info, #password').length) return;
            jQuery(document).unbind('.example');
        });
    }).on('input paste keyup', function(e){
        
        if(jQuery.trim(jQuery(this).val()).length >= 6){
            jQuery('#cpassword').attr("disabled", false);
            //showx(jQuery('#cpassword'));
            }
        else {
            jQuery('#cpassword').attr("disabled", true);
            jQuery('#cpassword').val("");
            hidecheckx(jQuery('#cpassword'));
        }

        if(jQuery(this).val() !== jQuery('#cpassword').val() && !jQuery('#cpassword')[0].disabled && jQuery('#cpassword').val().length > 0)
            showx(jQuery('#cpassword'));
        else if(jQuery(this).val() == jQuery('#cpassword').val() && !jQuery('#cpassword')[0].disabled)
            showcheck(jQuery('#cpassword'));
            
        if( !jQuery(this).hasClass('error') ){
            jQuery('div.pass-container').show();
        }else{
            jQuery('div.pass-container').hide();
        }
    }).on('blur', function(){
        if( jQuery(this).hasClass('error') ){
            jQuery('div.pass-container').hide();
        }else{
            jQuery('div.pass-container').show();
        }
    }).on('keypress', function(e){
        var code = e.keyCode || e.which
        return code!=32;
    });
  
    jQuery("#cpassword").on('paste', function(e){
        e.preventDefault();
    }).on('focusin input focusout',function(){
        if(jQuery(this).val() !== jQuery('#password').val())
            showx(jQuery(this));
        else
            showcheck(jQuery(this));
    })
    .on('keypress', function(e){
        var code = e.keyCode || e.which
        return code!=32;
    });
    
    jQuery('#email').on('blur', function(){
        var fieldlength = jQuery.trim(jQuery('#email').val()).length;

        if(fieldlength >= 6 && jQuery(this).hasClass('forSearch') && jQuery(this).hasClass('valid')){
            setTimeout(email_check,500);
        }
        else if(fieldlength < 6){
            hidecheckx(jQuery('#email'));
            jQuery('.email_availability').html('');
        }
    }).on('input', function(){
        var fieldlength = jQuery.trim(jQuery('#email').val()).length;
        hidecheckx(jQuery(this));
        jQuery('.email_availability').html('');
        
        jQuery(this).removeClass('pass');
        
        if(!jQuery(this).hasClass('forSearch') && fieldlength >= 6){
            jQuery(this).addClass('forSearch');
        }
        else if(fieldlength < 6){
            jQuery(this).removeClass('forSearch');
            hidecheckx(jQuery('#email'));
            jQuery('.email_availability').html('');
        }
    });
    
    jQuery('#mobile').on('blur', function(){
        var fieldlength = jQuery.trim(jQuery('#mobile').val()).length;

        if(fieldlength === 11 && jQuery(this).hasClass('forSearch') && jQuery(this).hasClass('valid')){
            setTimeout(mobile_check,500);
        }
        else if(fieldlength < 11){
            hidecheckx(jQuery('#mobile'));
            jQuery('.mobile_availability').html('');
        }
    }).on('input', function(){
        var fieldlength = jQuery.trim(jQuery('#mobile').val()).length;
        hidecheckx(jQuery(this));
        jQuery('.mobile_availability').html('');
        
        jQuery(this).removeClass('pass');
        jQuery(this).removeClass('fail');
        
        if(!jQuery(this).hasClass('forSearch') && fieldlength === 11){
            jQuery(this).addClass('forSearch');
        }
        else if(fieldlength < 11){
            jQuery(this).removeClass('forSearch');
            hidecheckx(jQuery('#mobile'));
            jQuery('.mobile_availability').html('');
        }
    });
});

/**********************************************************************************************/
/****************************	FORM 1 VALIDATION	*******************************************/
/**********************************************************************************************/	
jQuery(document).ready(function(){

    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || (/[a-zA-Z]/.test(value) && /\d/.test(value));
    }, "Must contain numbers and letters");

    jQuery.validator.addMethod("alphanumeric_underscore", function(value, element) {
        return this.optional(element) || /^\w+$/i.test(value);
    }, "Only letters, numbers, and underscores are allowed");

    jQuery.validator.addMethod("is_validmobile", function(value, element) {
        return this.optional(element) || /^(09|08)[0-9]{9}/.test(value);
    }, "Mobile numbers must begin with 09 or 08");

    jQuery.validator.addMethod("no_space", function(value, element) {
        return this.optional(element) || /[^\s]/g.test(value);
    }, "Spaces are not allowed.");

    jQuery('#mobile').numeric({negative : false});

    jQuery("#register_form1").validate({
        rules: {
            username: {
                required: true,
                minlength: 5,
                maxlength:25,
                alphanumeric_underscore: true
                },
                fullname: {
                    required: true,
                    minlength: 5,
                    maxlength:25
                },
            password: {
                required: true,
                minlength: 6,
                alphanumeric: true,
                no_space: true
                },
            cpassword: {
                required: true,
                minlength: 6,
                equalTo: '#password',
                no_space: true
                },
            email: {
                required: true,
                email: true,
                minlength: 6
                },
            mobile:{
                number: true,
                minlength: 11,
                maxlength: 11,
                is_validmobile: true
            },
        },
        messages:{
            cpassword:{
                equalTo: ''
            },
            email:{
                required: "Please enter a valid email address",
                email: 'Please enter a valid email address',
                minlength: '*E-mail is too short'
            },
            mobile:{
                minlength: 'Mobile should be at least 11 digits long',
                maxlength: 'Mobile should be no longer than 11 digits'
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('red');
            if(element.attr('name') !== 'cpassword'){
                error.insertAfter(element);
            }
        },
        submitHandler: function(form){
            var userfield = jQuery('#username');
            var emailfield = jQuery('#email');
            var mobilefield = jQuery('#mobile');
            var thisbtn = jQuery('#register_form1_btn');
            
            if( userfield.hasClass('pass') && emailfield.hasClass('pass') && !(mobilefield.hasClass('fail')) && !(mobilefield.hasClass('forSearch')) ){
                jQuery('#register_form1_loadingimg').show().css('display','inline-block');
                
                jQuery.post('/register/signup', jQuery(form).serializeArray(), function(data){
                    jQuery('#register_form1_loadingimg').hide();
                    thisbtn.attr('disabled', false);
                    try{
                        var serverResponse = jQuery.parseJSON(data);	
                    }
                    catch(e){
                        alert('We are currently encountering a problem. Please try again later.');
                        window.location.reload(true);
                        return;
                    }
                    
                    if(serverResponse['result']){
                        jQuery('#result_desc').html("Thank you for registering to Easyshop.ph!");
                        var title = "Registration Complete";

                        jQuery('div.pass-container, .fieldstatus').hide();
                        jQuery(form).find('input.reqfield').each(function(){
                            jQuery(this).prop('value', '');
                        });
                        
                        jQuery('#success_register').submit();
                    }
                    else{
                        if(typeof serverResponse["errors"] !== 'undefined'){
                            if(typeof serverResponse["errors"].username !== "undefined") {
                                var field = jQuery('#username');
                                showx(jQuery('#username'));
                                jQuery('.username_availability').html('Username already exists.');
                                field.removeClass('pass');                      
                            }
                            if(typeof serverResponse["errors"].contactno !== "undefined") {
                                var field = jQuery('#mobile');                          
                                showx(jQuery('#mobile'));
                                jQuery('.mobile_availability').html('Mobile is already in use.');
                                field.removeClass('pass');
                                field.addClass('fail');                 
                            }                       
                            if(typeof serverResponse["errors"].email !== "undefined") {
                                var field = jQuery('#email');                           
                                showx(jQuery('#email'));
                                jQuery('.email_availability').html('Email is already in use.');
                                field.removeClass('pass');          
                            }
                        }
                        else if(typeof serverResponse["dbError"] !== "undefined") {
                            alert(serverResponse["dbError"]);
                        }
                        thisbtn.val('SEND');
                    }
                    
                });
                thisbtn.attr('disabled',true);
                thisbtn.val('SAVING...');
            }
            else{
                // Codes below will execute only once as compensation for slow loaders.
                if( !userfield.hasClass('exec') ){
                    username_check(1);
                    ajaxStat.username = true;
                }
                if( !emailfield.hasClass('exec') ){
                    email_check(1);
                    ajaxStat.email = true;
                }
                if( $.trim(mobilefield.val()).length == 11 && !mobilefield.hasClass('exec') ){
                    mobile_check(1);
                    ajaxStat.mobile = true;
                }
            }
            return false;
        }
    });

    jQuery('.field input').on('click', function(){
        jQuery('.ci_form_validation_error').text('');
    });

});

function ajaxCheck(key){
    var count = 0;
    var i;
    var submitbtn = jQuery('#register_form1_btn');

    delete ajaxStat[key];

    for (i in ajaxStat){
        if( ajaxStat.hasOwnProperty(i) ){
            count++;
        }
    }

    if( count === 0 ){
        submitbtn.submit();
    }
}

function username_check(trigger){
    var username = jQuery('#username').val();
    var csrftoken = jQuery("meta[name='csrf-token']").attr('content');
    var csrfname = jQuery("meta[name='csrf-name']").attr('content');
    var field = jQuery('#username');

    //Prevents execution of post triggered by blur from field, (fixes dual request due to setTimeout)
    if(typeof ajaxStat.username != "undefined" && typeof trigger == "undefined"){
        ajaxCheck('username');
        return false;
    }

    field.addClass('exec');

    jQuery.post('/register/username_check', {username: username, csrfname : csrftoken}, function(result){
        if(result == 1){
            showcheck(jQuery('#username'));
            jQuery('.username_availability').html('<span class="input-success">Username is available</span>');
            jQuery('#usernamecheck').attr('value', jQuery('#username').val());
            field.addClass('pass');
        }
        else{
            showx(jQuery('#username'));
            jQuery('.username_availability').html('<span class="input-error">Username already exists.</span>');
            field.removeClass('pass');
        }
        field.removeClass('forSearch');
        jQuery('#username_loader').hide();
    });
}

function email_check(trigger){
    var email = jQuery('#email').val();
    var csrftoken = jQuery("meta[name='csrf-token']").attr('content');
    var csrfname = jQuery("meta[name='csrf-name']").attr('content');
    var field = jQuery('#email');

    //Prevents execution of post triggered by blur from field, (fixes dual request due to setTimeout)
    if(typeof ajaxStat.email != "undefined" && typeof trigger == "undefined"){
        ajaxCheck('email');
        return false;
    }

    field.addClass('exec');

    jQuery.post('/register/email_check', {email: email, csrfname : csrftoken}, function(result){
        if(result == 1){
            showcheck(jQuery('#email'));
            jQuery('.email_availability').html('');
            jQuery('#emailcheck').attr('value', jQuery('#email').val());
            field.addClass('pass');
        }
        else{
            showx(jQuery('#email'));
            jQuery('.email_availability').html('<span class="input-error">Email is already in use.</span>');
            field.removeClass('pass');
        }
        field.removeClass('forSearch');
        jQuery('#email_loader').hide();
    });
}

function mobile_check(trigger){
    var mobile = jQuery('#mobile').val();
    //If mobile is 11 digit long, get the digit value
    if(parseInt($.trim(mobile).length,10) === 11){
        if(mobile.substr(0,1) === '0'){
            mobile = mobile.substr(1,11);
        }
    }		
    var csrftoken = jQuery("meta[name='csrf-token']").attr('content');
    var csrfname = jQuery("meta[name='csrf-name']").attr('content');
    var field = jQuery('#mobile');

    //Prevents execution of post triggered by blur from field, (fixes dual request due to setTimeout)
    if(typeof ajaxStat.mobile != "undefined" && typeof trigger == "undefined"){
        ajaxCheck('mobile');
        return false;
    }

    field.addClass('exec');

    jQuery.post('/register/mobile_check', {mobile: mobile, csrfname : csrftoken}, function(result){
        if(result == 1){
            showcheck(jQuery('#mobile'));
            jQuery('.mobile_availability').html('');
            jQuery('#mobilecheck').attr('value', jQuery('#mobile').val());
            field.addClass('pass');
            field.removeClass('fail');
        }
        else{
            showx(jQuery('#mobile'));
            jQuery('.mobile_availability').html('<span class="input-error">Mobile is already in use.</span>');
            field.removeClass('pass');
            field.addClass('fail');
        }
        field.removeClass('forSearch');
        jQuery('#mobile_loader').hide();
    });
}

function showcheck(element){
    var name = element.attr('name');
    jQuery('#'+name+'_check').show().css('display','inline-block');
    jQuery('#'+name+'_x').hide();
}

function showx(element){
    var name = element.attr('name');
    jQuery('#'+name+'_check').hide();
    jQuery('#'+name+'_x').show().css('display','inline-block');
}

function hidecheckx(element){
    var name = element.attr('name');
    jQuery('#'+name+'_check').hide();
    jQuery('#'+name+'_x').hide();
}



