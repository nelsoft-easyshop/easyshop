(function ($) {




    $(".pass-container").css("display","block");
    $("div.pass-container").css("margin-left","0px");
    $("div.pass-container").css("width","100%");

    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');  

    /********************* DEACTIVATE ACCOUNT **************************/

     $("#deactivateAccountForm").validate({
         rules: {
            deactivatePassword: {
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
            event.preventDefault();
            var password = $("#deactivatePassword").val();
            var id = $("#idMember").val();

            var actionGroupChangePass = $('#deactivateActionPanel');    
            var loadingimg = $('#deactivateAccountLoader'); 
            actionGroupChangePass.hide();
            loadingimg.show();
            $.ajax({
                type: 'post',
                data: {password:password, id:id, csrfname : csrftoken},
                url: "/memberpage/sendDeactivateNotification",
                success: function(data) {
                    actionGroupChangePass.show();
                    loadingimg.hide();  
                    var obj = jQuery.parseJSON(data);    
                    if(obj === "Incorrect Password") {
                        $("#deactivatePassword").val("");                        
                        alert(obj);                        
                    }
                    else {
                        alert("You have successfully deactivated your account");
                        window.location = "/login/logout";                        
                    }

                },
            });   

        }
     });


    /********************* END DEACTIVATE ACCOUNT **************************/

    /********************* START CHANGE PASSWORD ***********************/

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
                maxlength:25
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
         submitHandler: function(form){
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
                data: {wsx:username, cur_password:currentPassword, password:newPassword, csrfname : csrftoken},
                url: "/register/changepass",
                success: function(data) {
                    actionGroupChangePass.show();
                    loadingimg.hide();
                    var obj = jQuery.parseJSON(data); 
                    if(obj.result === "success") {                    
                        alert("You have successfully changed your password");
                    }
                    else {
                        alert(obj.error);
                    }
                    $("#password").val("");
                    $("#confirmPassword").val("");
                    $("#currentPassword").val("");                    
                    $( "#cancel-edit-password" ).trigger( "click" );                         
                },
            });   

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

        var stateregion = $('#delivery_stateregion').find('option:selected').text().trim();
        var city = $('#delivery_city').find('option:selected').text().trim();
        if( (stateregion === "--Select State/Region--" || city === "--- Select City ---")) {
            alert('Please specify a valid address.');    
            $( ".map-container" ).slideToggle( "slow" );
        }

        if ((maplat == 0 && maplng == 0)){
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


    $('.map-trigger').click(function () {
        var maplat = $(this).siblings('input[name="map_lat"]').val();
        var maplng = $(this).siblings('input[name="map_lng"]').val();
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
                else if( type === 'delivery' ){
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
    $("#deliverAddressForm").on('click','#saveDeliverAddressBtn',function (e) {
        $("#saveDeliverAddressBtn").attr("value","Saving..");
        var postData = $("#deliverAddressForm").serializeArray()
        postData.push({ name: this.name, value: this.value });
        e.preventDefault();
        $.ajax({
            type: 'post',
            data: postData,
            url: "/memberpage/edit_consignee_address",
            success: function(data) {
                $("#saveDeliverAddressBtn").attr("value","Save Changes");                
                var obj = jQuery.parseJSON(data);
                if(obj.errors) {
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
                    if( typeof(obj.errors.street_address) !== "undefined") {
                        $("#errorsDivStreetAddress").css("display","block");
                        $("#errorTextStreetAddress").text(obj.errors.street_address[0]);                   
                    }                    
                    else {
                        $("#errorsDivStreetAddress").css("display","none");                                                
                    }
                }
                else {
                    $("#errorsDivConsignee, #errorsDivMobile, #errorsDivStreetAddress").css("display","none");
                }
            },
        });            

    });

    $('#consigneeMobile, #consigneeLandLine').on('keypress',function(e){
        var code = e.keyCode || e.which;
        return (code != 46);
    });

    $('.address_dropdown, .disabled_country').chosen({width:'200px'});
    $('.stateregionselect').on('change', function(){

        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

    /************** End Delivery Address ***************************/
    
    /************* Personal Information **************/
    var formPersonalInfo = $("#formPersonalInfo");
    formPersonalInfo.find( "#birthday-picker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $("#formPersonalInfo").on('click','#savePersonalInfo',function (e) {
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
                        else {
                            $("#errorIndicatorMobileNumber").css("display","none");                            
                        }                 
                    }
                    else {
                        $("#errorIndicatorMobileNumber").css("display","none");
                        
                    }
            },
        });     
    });

    $("#editEmailPanel").on('click','#changeEmailBtn',function (e) {

        email = $("#emailAddressEdit").val().trim();
        var loadingimg = $('img.changeEmailLoader'); 
        var verifyspan = $('#changeEmailBtnAction');  
        var currentEmail =  $("#currentEmail").text();
        verifyspan.hide();
        loadingimg.show();

        $.ajax({
            type: 'post',
            data: {email:email, csrfname : csrftoken},
            url: "/memberpage/edit_email",
            success: function(data) {
              
                var obj = jQuery.parseJSON(data);   
                loadingimg.hide();
                verifyspan.show();
                $("#verifyEmail").css("display","none");    
                    if(obj.result !== "success") {
                        if(obj.error.email) {
                            $("#errorIndicatoreEmailAddress").css("display","block");
                            $("#errorTextEmail").text(obj.error.email);
                        }                    
                    }
                    else {
                        $("#currentEmail").text(email);                       
                        $( "#btn-edit-email" ).trigger( "click" );                          
                        if(email !== currentEmail) {
                            $("#verifyEmail").css("display","block");
                            $("#verifiedEmail, #errorIndicatoreEmailAddress").css("display","none");                            
                        }                        
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
        verifyspan.hide();
        loadingimg.show();

        $.ajax({
            type: 'post',
            data: {field:field, data:data, reverify:'true', csrfname : csrftoken},
            url: "/memberpage/verify",
            success: function(data) {
                $( "#btn-edit-email" ).prop("disabled", false);
                var obj = jQuery.parseJSON(data);   
                loadingimg.hide();
                verifyspan.show();
                $("#verifyEmail").css("display","none");    
                if(obj === "success") {
                    $("#verifiedEmail").css("display","block");                     
                    $("#verifiedEmailText").text("An email has been sent. Please check your e-mail.");
                }
                else {
                    $("#errorIndicatoreVerify").css("display","block");
                    $("#errorTextVerify").text("You have exceeded the number of times to verify your mobile. Try again after 30 mins.");
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
            jQuery.each(jsonCity[stateregionID], function(k,v){
                //optionclone.attr('value', k).html(v).show();
                optionclone.attr('value', k).html(v).css('display', 'block');
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
        else {
            alert('Please specify a valid address.');
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



}(jQuery));

