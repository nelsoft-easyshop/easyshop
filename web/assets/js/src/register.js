$(document).ready(function(){
    $('.search_box').css('display','none');
});

$(document).ready(function(){

    $('#username').on('focus', function() {
        $(document).bind('focusin.example click.example',function(e){
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
        hidecheckx($(this));
        $('.username_availability').html('');

        if($(this).hasClass('pass')){
            $(this).removeClass('pass');
        }
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
            //showx($('#cpassword'));
        }
        else {
            $('#cpassword').attr("disabled", true);
            $('#cpassword').val("");
            hidecheckx($('#cpassword'));
        }
        if($(this).val() !== $('#cpassword').val() && !$('#cpassword')[0].disabled && $('#cpassword').val().length > 0)
            showx($('#cpassword'));
        else if($(this).val() == $('#cpassword').val() && !$('#cpassword')[0].disabled)
            showcheck($('#cpassword'));

        if( !$(this).hasClass('error') ){
            $('div.pass-container').show();
        }
        else{
            $('div.pass-container').hide();
        }
    }).on('blur', function(){
        if( $(this).hasClass('error') ){
            $('div.pass-container').hide();
        }
        else{
            $('div.pass-container').show();
        }
    }).on('keypress', function(e){
        var code = e.keyCode || e.which
        return code!=32;
    });

    $("#cpassword").on('paste', function(e){
        e.preventDefault();
    }).on('focusin input focusout',function(){
        if($(this).val() !== $('#password').val())
            showx($(this));
        else
            showcheck($(this));
    }).on('keypress', function(e){
        var code = e.keyCode || e.which
        return code!=32;
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
        hidecheckx($(this));
        $('.email_availability').html('');

        if($(this).hasClass('pass')){
            $(this).removeClass('pass');
        }
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
                alphanumeric: true
                },
            cpassword: {
                required: true,
                minlength: 6,
                equalTo: '#password'
                },
            email: {
                required: true,
                email: true,
                minlength: 6
                },
            terms_checkbox:{
                required: true
            }
        },
        messages:{
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
            else{
                error.appendTo(element.parent());
            }
        },
        submitHandler: function(form){
            if( $('#username').hasClass('pass') && $('#email').hasClass('pass') ){
                $('#register_form1_loadingimg').show();
                form.submit();
                $('#register_page1_btn').attr('disabled', true);
            }
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
                minlength: 6,
                maxlength:25
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
                added_span.insertBefore(element.next());
            }
            else
                error.appendTo(element.parent());
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
    $.post('/register/username_check', {username: username, csrfname : csrftoken}, function(result){
        if(result == 1){
            showcheck($('#username'));
            $('.username_availability').html('');
            $('#usernamecheck').attr('value', $('#username').val());
            $('#username').addClass('pass');
        }
        else{
            showx($('#username'));
            $('.username_availability').html('Username already exists.');
            $('#username').removeClass('pass');
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
    $.post('/register/email_check', {email: email, csrfname : csrftoken}, function(result){
        if(result == 1){
            showcheck($('#email'));
            $('.email_availability').html('');
            $('#emailcheck').attr('value', $('#email').val());
            $('#email').addClass('pass');
        }
        else{
            showx($('#email'));
            $('.email_availability').html('Email already used.');
            $('#email').removeClass('pass');
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

/*******************************************************************************************************/
/******************************* Terms and Conditions Dialog box ***************************************/
/*******************************************************************************************************/
$(function() {
    $( ".dialog" ).dialog({
            width:"70%",
            height: 500,
            autoOpen: false,
            modal: true,
            title: "Terms and Conditions",
            closeOnEscape: true,
            draggable:false,
    });
    $( ".terms_and_conditions" ).click(function() {
        $('.dialog').dialog('open');
    });

});

/**********************************************************************************************/
/****************************	Change Password VALIDATION	************************************/
/**********************************************************************************************/
$(document).ready(function(){

    $("#notify").delay(3000).fadeOut(2000);

    jQuery.validator.addMethod("equals", function(value, element, param) {
      return this.optional(element) || value === param;
    }, jQuery.format(""));

    $("#identify_form").validate({
        ignore: "",
        rules: {
            email: {
                required: true,
                email: true,
                minlength: 6
            }
        },
        messages:{
            email:{
                required: "<br>Please enter a valid email address",
                email: "<br>Please enter a valid email address",
                minlength: "*Email too short",
                equalTo: ""
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('red');
            error.appendTo(element.parent());
        }
    });


    $('.field input').on('click', function(){
        $('.ci_form_validation_error').text('');
    });

});
