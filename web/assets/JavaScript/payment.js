$(document).ready(function(){
 
    $('.paypal_loader').hide();
    $('.div_change_addree').hide();
    $('.paypal_button').show(); 
    $('.div_view_avail_location').hide();
    

    function cityFilter(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value');
        var optionclone = cityselect.find('option.optionclone').clone();
        optionclone.removeClass('optionclone').addClass('echo').attr('disabled', false);
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){
            jQuery.each(jsonCity[stateregionID], function(k,v){
                optionclone.attr('value', k).html(v).css('display', 'block');
                cityselect.append(optionclone.clone());
            });
        } 
        cityselect.trigger('chosen:updated');
    }

    $('.stateregionselect').on('change', function(){
        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });


// -- PAYPAL PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.paypal',function () {
        var action = config.base_url + "pay/setting/paypal"; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var type = $(this).data('type');

        if(type == 1){
            if(!$('#chk_paypal1').is(':checked')){
                $("#chk_paypal1").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                    "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                    "box-shadow": "0px 0px 2px 2px #FF0000"}); 
                $('#paypal > .chck_privacy').append('<br><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span> ')

                return false;
            }
        }else{
            if(!$('#chk_paypal2').is(':checked')){
                $("#chk_paypal2").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                    "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                    "box-shadow": "0px 0px 2px 2px #FF0000"}); 
                $('#cdb > .chck_privacy').append('<br><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span> ')

                return false;
            }
        } 

        $.ajax({
            type: "POST",
            url: action, 
            dataType: "json",
            data:   csrfname+"="+csrftoken+"&paypal="+type, 
            beforeSend: function(jqxhr, settings) { 
                $('.paypal_loader').show();
                $('.paypal_button').hide();
            },
            success: function(d) {
                if (d.e == 1) { 
                    window.location.replace(d.d);
                }else{
                    alert(d.d);
                    $('.paypal_loader').hide();
                    $('.paypal_button').show();
                }
             
            }
        });
    });

// -- END OF PAYPAL PROCESS PAYMENT SECTION -- // 

// -- DRAGON PAY PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.btnDp',function () {
        var action = config.base_url + "payment/payDragonPay"; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var type = $(this).data('type');

        if($('#chk_dp').is(':checked')){
            $.ajax({
                type: "POST",
                url:  action, 
                dataType: "json",
                data: csrfname+"="+csrftoken, 
                success: function(d) {
                    if(d.e == 1){ 
                        window.location.replace(d.u);
                    }else{
                        alert(d.m);
                    }
                }
            });
        }else{
            $("#chk_dp").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"}); 
            $('#dragonpay > .chck_privacy').append('<br><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span> ');
        }
    });

// -- END OFDRAGON PAY PROCESS PAYMENT SECTION -- // 

// -- CASH ON DELIVERY PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.payment_cod',function () {
        if($('#chk_cod').is(':checked')){
            $('#codFrm').submit();
        }else{
           $("#chk_cod").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
            "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
            "box-shadow": "0px 0px 2px 2px #FF0000"}); 
           $('#cod > .chck_privacy').append('<br><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span> ')
        }
    });

// -- END OF CASH ON DELIVERY PROCESS PAYMENT SECTION -- // 

// -- DIRECT BANK DEPOSIT PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.payment_dbd',function () {
        if($('#chk_dbd').is(':checked')){
            $('#dbdFrm').submit();
        }else{
         $("#chk_dbd").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
            "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
            "box-shadow": "0px 0px 2px 2px #FF0000"}); 
           $('#dbd > .chck_privacy').append('<br><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span> ')
        }
    });

// -- END OF DIRECT BANK DEPOSIT PROCESS PAYMENT SECTION -- // 

// -- CHANGE ADDRESS SECTION -- // 

    $(document).on('click','.link_address',function () {
        $('.div_change_addree').modal({
            escClose: false,
            containerCss:{
                maxWidth: 900,
                minWidth: 605,
                maxHeight: 600,
            },
            
        });
        $('#simplemodal-container').addClass('div_change_addree');
    });

    function validateRedTextBox(idclass)
    {
      $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
        "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
        "box-shadow": "0px 0px 2px 2px #FF0000"});
    } 
    
    function validateWhiteTextBox(idclass)
    {
      $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
        "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
        "box-shadow": "0px 0px 2px 2px #FFFFFF"});
    }


    $(document).on('click','.changeAddressBtn',function () {
        var action = config.base_url + "payment/changeAddress";
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        var consignee = $('#consignee');
        var stateregion = $('.stateregionselect');
        var cityselect = $('.cityselect');
        var address =  $('.c_address');
        var mobile = $('#c_mobile');
        var telephone = $('#c_telephone');


        validateWhiteTextBox('#consignee');
        validateWhiteTextBox('.stateregionselect');
        validateWhiteTextBox('.cityselect');
        validateWhiteTextBox('.c_address');
        validateWhiteTextBox('#c_mobile');

        if(consignee.val().length == 0){
            validateRedTextBox('#consignee');
        }

        if(stateregion.val() == '0'){
            validateRedTextBox('.stateregionselect');
        }

        if(cityselect.val() == '0'){
            validateRedTextBox('.cityselect');
        }

        if(address.val().length == 0){
            validateRedTextBox('.c_address');
        }
        if(mobile.val().length == 0){  
            validateRedTextBox('#c_mobile');
        }



        if(consignee.val().length == 0 || stateregion.val() == '0' || cityselect.val() == '0' || address.val().length == 0 || mobile.val().length == 0){
            alert('Fill up Required Fields!');
            return false;
        }else if(mobile.val().length != 10){
            alert('Mobile number should be numeric and 10 digits. eg: 9051235678');
            validateRedTextBox('#c_mobile');
            return false;
        }

        var formD = $('#delAddressFrm').serializeArray();
        formD.push({name:'temp_lat', value:0});
        formD.push({name:'temp_lng', value:0});
        formD.push({name:'map_lat', value:0});
        formD.push({name:'map_lng', value:0});
        formD.push({name:'c_deliver_address_btn', value:"Save"});

        $.ajax({
            type: "POST", 
            url:  action,
            data:formD,
            dataType: "json", 
            success: function(d) {
                if(d == "success"){
                    location.reload();
                }else{
                    alert(d);
                }
            }
        });
    });

// -- END OF CHANGE ADDRESS SECTION -- // 

// -- START OF VIEW ITEM AVAILABILITY LOCATION -- //


 
    $(document).on('click','.view_location_item',function () {

        $('.div_view_avail_location').modal({
            escClose: false,
            containerCss:{
                maxWidth: 900,
                minWidth: 605,
                maxHeight: 600,
            }
        }); 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var slug = $(this).data('slug');
        var iid = $(this).data('iid');
        var pname = $(this).data('name');
        var action = config.base_url + "payment/getLocation"; 
            $.ajax({
                type: "POST",
                url:  action, 
                dataType: "json",
                data: csrfname+"="+csrftoken+"&sid="+slug+"&iid="+iid+"&name="+pname, 
                success: function(d) {
                    $('.div_view_avail_location').empty();
                    $('.div_view_avail_location').html(d);
                }
            });
    });


// -- END OF VIEW ITEM AVAILABILITY LOCATION -- //

});