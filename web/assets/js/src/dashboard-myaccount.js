(function ($) {

    var options = {
        minChar: 8,
        bootstrap3: true,
    };
    $('#password').pwstrength(options);
    $(".pass-container").css("display","block");
    $("div.pass-container").css("margin-left","0px");
    $("div.pass-container").css("width","100%");

    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');  

    /********************* DEACTIVATE ACCOUNT **************************/
    
    $("#deactivateAccountForm").submit(function(e) {
        e.preventDefault();
    }).validate({

         rules: {
            deactivatePassword: {
                required: true,
                },
            deactivateUserName: {
                required: true,
                },                
         },
         messages:{
            deactivatePassword:{
                required: 'This field is required'
            },
         },
         errorElement: "span",
         errorPlacement: function(error, element) {
            error.addClass("val-error");
            error.appendTo(element.parent());
                      
         },
         submitHandler: function(form){
            var password = $("#deactivatePassword").val();
            var username = $("#deactivateUsername").val();
            var actionGroupChangePass = $('#deactivateActionPanel');    
            var loadingimg = $('#deactivateAccountLoader'); 
            actionGroupChangePass.hide();
            loadingimg.show();
            $.ajax({
                type: 'post',
                data: {username:username, password:password, csrfname : csrftoken},
                url: "/memberpage/sendDeactivateNotification",
                success: function(data) {
                    actionGroupChangePass.show();
                    loadingimg.hide();  
                    var obj = jQuery.parseJSON(data);    
                    if(obj === "Invalid Username/Password") {
                        $("#deactivatePassword").val("");                        
                        alert(obj);                        
                    }
                    else {
                        $('#activated-modal').modal({       
                            onClose: function(){
                                $.modal.close();                                
                                window.location = "/login/logout";
                            }
                         });
                        $('#activated-modal').parents("#simplemodal-container").addClass("deactivated-container");                                                   
                    }

                },
            });   

        }
     });


    /********************* END DEACTIVATE ACCOUNT **************************/

    /********************* START CHANGE PASSWORD ***********************/
     jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || (/[a-zA-Z]/.test(value) && /\d/.test(value));
     }, "Must contain numbers and letters");    

     $("#changePassForm").validate({
         rules: {
            currentPassword: {
                required: true,
                minlength: 6,
                maxlength:25
                },
            password: {
                required: true,
                minlength: 6,
                maxlength:25,
                alphanumeric: true
                },
            confirmPassword: {
                required: true,
                minlength: 6,
                maxlength:25,
                equalTo: '#password'
                }
         },
         messages:{
            confirmPassword:{
                equalTo: 'The password you have entered does not match your new one'
            },
         },
         errorElement: "span",
         errorPlacement: function(error, element) {
            error.addClass("val-error");
            error.appendTo(element.parent());
                      
         },
         submitHandler: function(form, event){
            var errorContainer = $('#password-change-error');
            var succcessContainer =  $('#password-change-success');
            errorContainer.hide();
            succcessContainer.hide();
            event.preventDefault();
            var newPassword = $("#password").val();
            var confirmPassword = $("#confirmPassword").val();
            var currentPassword = $("#currentPassword").val();
            var username = $("#username").val();  
            
            var loadingimg = $('img.changePasswordLoader'); 
            var actionGroupChangePass = $('#actionGroupChangePass');             
            actionGroupChangePass.hide();
            loadingimg.show(); 
            $.ajax({
                type: 'post',
                data: {currentPassword:currentPassword, password:newPassword, csrfname : csrftoken},
                url: "/register/changepass",
                success: function(data) {
                    actionGroupChangePass.show();
                    loadingimg.hide();
                    var obj = jQuery.parseJSON(data); 
                    if(obj.result === "success") {      
                       errorContainer.hide();
                       succcessContainer.fadeIn();
                    }
                    else {
                        errorContainer.html(obj.error);
                        errorContainer.fadeIn();
                        succcessContainer.hide();
                    }
                },
            });   

        }
     });
     
     
    $('#changePassForm input[name="password"]').keyup(function(){
        var $this = $(this);
        var $confirmPasswordInput = $('#changePassForm input[name="confirmPassword"]');
        if($this.val() === ''){
            $confirmPasswordInput.val('');
            $confirmPasswordInput.prop('disabled', true);
        }
        else{
            $confirmPasswordInput.prop('disabled', false);
        }
    });

    /**************** GOOGLE MAPS ******************************/

    $(".refresh_map").click(function(){  
        var stateregion = $('#delivery_stateregion').find('option:selected').text().trim();
        var city = $('#delivery_city').find('option:selected').text().trim();
        var type = "delivery";
        var address = stateregion + " " + city + " PH";
        if(address === "--- Select City --- PH" || city === "--- Select City ---") {
            alert('Please specify a valid address.');            
            $( ".map-container" ).slideToggle( "slow" );
        }
        else {        
            codeAddress(address, type);
        }

    });


    $('.map-trigger').click(function () {
        var maplat = $(this).siblings('input[name="map_lat"]').val();
        var maplng = $(this).siblings('input[name="map_lng"]').val();
        var refreshmapbtn = $('.refresh_map');
        var mapcanvas = $(this).parent('div').siblings('div.map-canvas');
        var type = this.name;
        if(maplat == 0 && maplng == 0 ) {
            refreshmapbtn.trigger("click");
        }
        else{
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
                  
    });    

    $('.span-current-location').click(function () {
        var maplat = $("#current_lat").val();
        var maplng = $("#current_lang").val();
        $("#temp_clat").val(maplat);
        $("#temp_clng").val(maplng);
        var refreshmapbtn = $('.refresh_map');
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
                else{
                    mapDelivery.setCenter(myLatlng);
                    markerDelivery.setPosition(myLatlng);
                }
            }else{
                google.maps.event.addDomListener(window, 'load', initialize(myLatlng, type));
            }
        }   
                  
    });



    /**************** END GOOGLE MAPS ******************************/

    /************** Delivery Address ***************************/
    $("#deliverAddressForm").on('change','#deliver_stateregion, #delivery_city',function (e) {
        $("#errorsRegionDiv, #errorsCityDiv").css("display","none");

    });
    $("#deliverAddressForm").on('keyup','#consigneeMobile, #consigneeName, #deliveryAddress',function (e) {
        $("#errorsDivConsignee, #errorsDivMobile, #errorsDivStreetAddress").css("display","none");

    });    
    $("#deliverAddressForm").on('click','#saveDeliverAddressBtn',function (e) {
        $('#delivery-address-error').hide();
        $('#delivery-address-success').hide();
        $("#saveDeliverAddressBtn").attr("value","Saving..");
        var postData = $("#deliverAddressForm").serializeArray()
        postData.push({ name: this.name, value: this.value });
        e.preventDefault();
        if(parseInt($("#temp_clat").val()) !== 0 && parseInt($("temp_clng").val()) !== 0) {
            $("#locationMarkedText").text("Location Marked");
        }
        $.ajax({
            type: 'post',
            data: postData,
            url: "/memberpage/edit_consignee_address",
            success: function(data) {
                $("#saveDeliverAddressBtn").attr("value","Save Changes");                
                var obj = jQuery.parseJSON(data);
                if(!obj.isSuccessful) {
                    if(typeof(obj.errors.consignee) !== "undefined") {
                        $("#errorsDivConsignee").css("display","block");
                        $("#errorTextConsignee").text(obj.errors.consignee[0]);
                    }
                    else {
                        $("#errorsDivConsignee").css("display","none");
                    }
                    if(typeof(obj.errors.mobile_number) !== "undefined") {
                        $("#errorsDivMobile").css("display","block");
                        $("#errorsDivMobile #errorTextMobile").text(obj.errors.mobile_number[0]);              
                    }
                    else {
                        $("#errorsDivMobile").css("display","none");                        
                    }
                    if(typeof(obj.errors.region) !== "undefined") {
                        $("#errorsRegionDiv").css("display","block");
                        $("#errorTextRegion").text(obj.errors.region[0]);              
                    }
                    else {
                        $("#errorsRegionDiv").css("display","none");                        
                    }       
                    if(typeof(obj.errors.city) !== "undefined") {
                        $("#errorsCityDiv").css("display","block");
                        $("#errorTextCity").text(obj.errors.city[0]);              
                    }
                    else {
                        $("#errorsRegionDiv").css("display","none");                        
                    }                                  
                    if( typeof(obj.errors.street_address) !== "undefined") {
                        $("#errorsDivStreetAddress").css("display","block");
                        $("#errorTextStreetAddress").text(obj.errors.street_address[0]);                   
                    }                    
                    else {
                        $("#errorsDivStreetAddress").css("display","none");                                                
                    }
                    if( typeof(obj.errors.telephone_number) !== "undefined") {
                        $("#errorsDivTelephone").css("display","block");
                        $("#errorTextTelephone").text(obj.errors.telephone_number[0]);                   
                    }                    
                    else {
                        $("#errorTextTelephone").css("display","none");                                                
                    }                    
                    $('#delivery-address-error').fadeIn();
                    $('#delivery-address-success').hide();
                }
                else {
                    $("#errorsDivConsignee, #errorsDivMobile, #errorsDivStreetAddress, #errorsDivTelephone, #errorsRegionDiv, #errorsCityDiv").css("display","none");
                    $('#delivery-address-success').fadeIn();
                    $('#delivery-address-error').hide();
                }
            },
        });            

    });

    $('#consigneeMobile, #consigneeLandLine').on('keypress',function(e){
        var code = e.keyCode || e.which;
        return (code != 46);
    });


    $('#consigneeLandLine').on('keypress',function(e){
        var keyCode = event.keyCode;
        if ( ! (keyCode >= 48 && keyCode <= 57) && keyCode !== 45 && keyCode !== 43) {
              event.preventDefault();
        }
    });



    $('.stateregionselect').on('change', function(){

        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

    /************** End Delivery Address ***************************/
    
    /************* Personal Information **************/
    var formPersonalInfo = $("#formPersonalInfo");
    formPersonalInfo.find( "#birthday-picker" ).datepicker({ changeMonth:true, changeYear:true, dateFormat: "yy-mm-dd" });
    $("#formPersonalInfo").on('click','#savePersonalInfo',function (e) {
        $("#errorIndicatorMobileNumber").css("display","none");        
        $("#errorIndicatorBirthday").css("display","none");        
        $("#errorIndicatorGender").css("display","none");        
        $("#savePersonalInfo").text("Saving...");
        e.preventDefault();

        var fullname = formPersonalInfo.find("#fullname").val().trim();
        var gender = formPersonalInfo.find('input:radio[name=gender]:checked').val();        
        var bday = formPersonalInfo.find("#birthday-picker").val();
        var mobileNumber = formPersonalInfo.find("#mobileNumber").val();
        var originalEmail = formPersonalInfo.find("#email_orig").val();
        var email = formPersonalInfo.find("#emailAddress").val();
        $.ajax({
            type: 'post',
            data: {fullname:fullname, gender:gender, dateofbirth:bday, mobile: mobileNumber, csrfname : csrftoken},
            url: "/memberpage/edit_personal",
            success: function(data) {                
                    $("#savePersonalInfo").text("SAVE CHANGES");
                    var obj = jQuery.parseJSON(data);
                    if(obj.result !== "success") {
                        $("#verifiedEmail, #verifyEmail").css("display","none");
                        if(obj.error.mobile) {
                            $("#errorIndicatorMobileNumber").css("display","block");
                            $("#errorTextMobile").text(obj.error.mobile);
                        }
                        if(obj.error.dateofbirth) {
                            $("#errorIndicatorBirthday").css("display","block");
                            $("#errorTextBirthday").text(obj.error.dateofbirth );                            
                        }     
                        if(obj.error.gender) {
                            $("#errorIndicatorGender").css("display","block");
                            $("#errorTextGender").text(obj.error.gender );                            
                        }                                   
                    }
                    else {
                        $("#errorIndicatorMobileNumber, #errorIndicatorBirthday, #errorIndicatorGender").css("display","none");
                        
                    }
            },
        });     
    });

    $("#editEmailPanel").on('click','#changeEmailBtn',function (e) {

        email = $("#emailAddressEdit").val().trim();
        var currentEmail =  $("#currentEmail").text();
        $('img.changeEmailLoader').show(); 
        $('#changeEmailBtnAction').hide();

        $.ajax({
            type: 'post',
            data: {email:email, csrfname : csrftoken},
            url: "/memberpage/edit_email",
            success: function(data) {
              
                var obj = jQuery.parseJSON(data);   
                $("#verifyEmail").css("display","none");    
                    if(obj.result !== "success") {
                        if(obj.error.email) {
                            $("#errorIndicatoreEmailAddress").css("display","block");
                            $("#errorTextEmail").text(obj.error.email);
                             $('img.changeEmailLoader').hide(); 
                             $('#changeEmailBtnAction').show();
                        }                    
                    }
                    else {
                        $("#currentEmail").text(email);                                              
                        if(email !== currentEmail) {
                            $("#verifyEmail").css("display","block");
                            $("#verifiedEmail, #errorIndicatoreEmailAddress").css("display","none");                            
                        }
                        verifyEmail();
                    }
            },
        });             
    });   

    $("#verifyEmail").on('click','#verifyEmailAction',function (e) {
        $( "#btn-edit-email" ).prop("disabled", true);
        var data = $("#currentEmail").text();
        var field = "email";
        var loadingimg = $('img.verify_img'); 
        var verifyspan = $('#verifyEmailAction');  
        $("#verifiedEmailText").text('');
        $("#errorTextVerify").text('');
        verifyspan.hide();
        loadingimg.show();

        $.ajax({
            type: 'post',
            data: {field:field, data:data, reverify:'true', csrfname : csrftoken},
            url: "/memberpage/verify",
            success: function(data) {
                $( "#btn-edit-email" ).prop("disabled", false);
                var obj = jQuery.parseJSON(data);   
                var emailCooldownDuration = $('#email-cooldown-mins').val();
                loadingimg.hide();
                verifyspan.show();
                $("#verifyEmail").css("display","none");    
                if(obj === 'success') {
                    $("#verifiedEmail").css("display","block");                     
                    $("#verifiedEmailText").text("An email has been sent. Please check your e-mail.");
                }
                  else if(obj === 'limit-of-requests-reached'){
                    $("#errorIndicatoreVerify").css("display","block");
                    $("#errorTextVerify").text("You have exceeded the number of times to verify your email. Try again after "+emailCooldownDuration+" mins.");
                }
 
            },
        });             
    });       


    /**************** END PERSONAL INFORMATION ******************/

    function cityFilter(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value');
        var optionclone = cityselect.find('option.optionclone').clone();
        optionclone.removeClass('optionclone').addClass('echo').attr('disabled', false);
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){
            jQuery.each(jsonCity[stateregionID], function(key,city){
                //optionclone.attr('value', k).html(v).show();
                optionclone.attr('value', city.id).html(city.name).css('display', 'block');
                cityselect.append(optionclone.clone());
            });
        }
        
        cityselect.trigger('chosen:updated');
        
    }

    function codeAddress(address, type) {
        $("#delivery_mapcanvas").css("display","block");
        geocoder = new google.maps.Geocoder();
        geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            google.maps.event.addDomListener(window, 'load', initialize(results[0].geometry.location, type));
        }
        else{
            alert('Please specify a valid address.');            
            $( ".map-container" ).slideToggle( "slow" );            
        }
      });
    }  
    
    function initialize(myLatlng, type) {
        var mapOptions = {
            center:myLatlng,
            zoom: 15
        };

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
        
        templat.val(myLatlng.lat());
        templng.val(myLatlng.lng());
    }      
    /*********************** LOCATION ACCESOR ******************************/

    function GEOprocess(position) {
        $("#current_lat").val(position.coords.latitude);
        $("#current_lang").val(position.coords.longitude);
        // alert("Latitude: " + position.coords.latitude + "Longitude: " +  position.coords.longitude);
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(GEOprocess);
    }

    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }else if(window.ActiveXObject){
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    function GEOajax(url) {
        xmlHttp.open("GET", url, true);
        xmlHttp.onreadystatechange = updatePage;
        xmlHttp.send(null);
    }
    function verifyEmail()
    {
        var loadingimg = $('img.verify_img'); 
        var verifyspan = $('#verifyEmailAction');          
        var data = $("#currentEmail").text();
        $("#verifiedEmailText").text('');
        $("#errorTextVerify").text('');
        var field = "email";        
        $.ajax({
            type: 'post',
            data: {field:field, data:data, reverify:'true', csrfname : csrftoken},
            url: "/memberpage/verify",
            success: function(data) {
                $( "#btn-edit-email" ).trigger( "click" );   
                var obj = jQuery.parseJSON(data); 
                var emailCooldownDuration = $('#email-cooldown-mins').val();
                loadingimg.hide();
                verifyspan.show();
                $('img.changeEmailLoader').hide(); 
                $('#changeEmailBtnAction').show();
                $("#verifyEmail").css("display","none");   
                if(obj === 'success') {
                    $("#verifiedEmail").css("display","block");                     
                    $("#verifiedEmailText").text("An email has been sent. Please check your e-mail.");
                }
                else if(obj === 'limit-of-requests-reached'){
                    $("#verifiedEmail").css("display","none");
                    $("#errorIndicatoreVerify").css("display","block");
                    $("#errorTextVerify").text("You have exceeded the number of times to verify your email. Try again after " +emailCooldownDuration+" mins.");
                }
 
            },
        });              
    }


}(jQuery));

