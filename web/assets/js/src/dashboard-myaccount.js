(function ($) {


    /**************** GOOGLE MAPS ******************************/

    $(".refresh_map").click(function(){     
        var stateregion = $('#delivery_stateregion').find('option:selected').text();
        var city = $('#delivery_city').find('option:selected').text();
        var type = "delivery";

        
        var address = stateregion + " " + city + " PH";
        console.log(address);
        if(address === " --- Select City --- PH") {
            alert('Please specify a valid address.');            
            $( ".map-container" ).slideToggle( "slow" );
        }
        else {
            codeAddress(address, type);
        }            
        
    });

    function codeAddress(address, type) {
        $("#delivery_mapcanvas").css("display","block");
      geocoder = new google.maps.Geocoder();
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {

            google.maps.event.addDomListener(window, 'load', initialize(results[0].geometry.location, type));
        }
      });
    }  
    
    //all DOM elements accessed via id
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

    $('.address_dropdown, .disabled_country').chosen({width:'200px'});
    $('.stateregionselect').on('change', function(){

        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

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

    /************** End Delivery Address ***************************/
    
    /************* Personal Information **************/
    var formPersonalInfo = $("#formPersonalInfo");
    formPersonalInfo.find( "#birthday-picker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $("#formPersonalInfo").on('click','#savePersonalInfo',function (e) {
        $("#savePersonalInfo").text("Saving...");
        e.preventDefault();

        var isEmailVerified = formPersonalInfo.find("#is_email_verify").val().trim();
        var fullname = formPersonalInfo.find("#fullname").val().trim();
        var gender = formPersonalInfo.find('input:radio[name=gender]:checked').val();        
        var bday = formPersonalInfo.find("#birthday-picker").val();
        var mobileNumber = formPersonalInfo.find("#mobileNumber").val();
        var originalEmail = formPersonalInfo.find("#email_orig").val();
        var email = formPersonalInfo.find("#emailAddress").val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $.ajax({
            type: 'post',
            data: {fullname:fullname, gender:gender, dateofbirth:bday, mobile: mobileNumber, email:email, csrfname : csrftoken},
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
                        if(obj.error.email) {
                            $("#errorIndicatoreEmailAddress").css("display","block");
                            $("#errorTextEmail").text(obj.error.email);
                        }                        
                    }
                    else {
                        if(email !== originalEmail) {
                            $("#verifyEmail").css("display","block");
                            $("#verifiedEmail").css("display","none");                            
                        }
                    }
            },
        });     
    });



    $(document.body).on('click','#verifyEmailAction',function (e) {

        var data = $("#emailAddress").val();
        var field = $("#emailAddress").attr('name');
        var loadingimg = $('img.verify_img'); 
        var verifyspan = $('#verifyEmail');  
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');               
        verifyspan.hide();
        loadingimg.show();

        $.ajax({
            type: 'post',
            data: {field:field, data:data, reverify:'true', csrfname : csrftoken},
            url: "/memberpage/verify",
            success: function(data) {
                var obj = jQuery.parseJSON(data);   
                loadingimg.hide();
                $("#verifyEmail").css("display","none");    
                if(obj === "success") {
                    $("#verifiedEmail").css("display","block");                     
                    $("#verifiedEmailText").text("An email has been sent. Please check your e-mail.");
                }
                else {
                    $("#errorIndicatoreEmailAddress").css("display","block");
                    $("#errorTextEmail").text("You have exceeded the number of times to verify your mobile. Try again after 30 mins.");
                }
 
            },
        });             
    });    


    /**************** END PERSONAL INFORMATION ******************/


}(jQuery));