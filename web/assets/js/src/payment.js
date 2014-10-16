$(document).ready(function(){
    var PAY_BY_GATEWAY = false;

    PAY_BY_GATEWAY ? $('#pointInput').show() : $('#pointInput').hide();
    $('.paypal_loader').hide();
    $('.div_change_addree').hide();
    $('.paypal_button').show();  
    $('#c_mobile').numeric({negative : false});

    var cityFilter = function(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value'); 
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){ 
            $('.cityselect').empty();
            jQuery.each(jsonCity[stateregionID], function(k,v){
                $('.cityselect').append('<option value="'+k+'">'+v+'</option>'); 
            });
        }
    }

    var validateRedTextBox = function (idclass)
    {
      $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
        "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
        "box-shadow": "0px 0px 2px 2px #FF0000"});
    } 
    
    var validateWhiteTextBox = function (idclass)
    {
      $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
        "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
        "box-shadow": "0px 0px 2px 2px #FFFFFF"});
    }

    $('.stateregionselect').on('change', function(){
        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

    $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
    $('.stateregionselect').trigger('change');
    

// -- PAYPAL PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.paypal',function () {
        var action = ''; 
        var postData = '';
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var type = $(this).data('type');

        if(type == 1){
            if(!$('#chk_paypal1').is(':checked')){
                $("#chk_paypal1").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                    "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                    "box-shadow": "0px 0px 2px 2px #FF0000"});
                $('#paypal > .chck_privacy > p').empty();
                $('#paypal > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>');

                return false;
            }
        }else{
            if(!$('#chk_paypal2').is(':checked')){
                $("#chk_paypal2").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                    "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                    "box-shadow": "0px 0px 2px 2px #FF0000"}); 
                $('#cdb > .chck_privacy > p').empty();
                $('#cdb > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>');

                return false;
            }
        }

        if(PAY_BY_GATEWAY){
            var pointAllocated = $('#pointsAllocated').val();
            if($.isNumeric(pointAllocated) && parseInt(pointAllocated) > 0){
                var paymentMethod = JSON.stringify(
                {
                    PaypalGateway:{
                        method:"PayPal", 
                        type:$(this).data('type')
                    },
                    PointGateway:{
                        method:"Point",
                        amount:pointAllocated,
                        pointtype: "purchase"
                    }
                });
            }
            else{
                var paymentMethod = JSON.stringify(
                {
                    PaypalGateway:{
                        method:"PayPal", 
                        type:$(this).data('type')
                    }
                });
            }
            postData = csrfname+"="+csrftoken+"&paymentMethods="+paymentMethod;
            action = config.base_url + "pay/pay";
        }
        else{
            postData = csrfname+"="+csrftoken+"&paypal="+type;
            action = config.base_url + "pay/setting/paypal";
        }

        $.ajax({
            type: "POST",
            url: action, 
            dataType: "json",
            data:   postData, 
            beforeSend: function(jqxhr, settings) { 
                $('.paypal_loader').show();
                $('.paypal_button').hide();
            },
            success: function(d) {
                if (d.e == 1) { 
                    window.location.replace(d.d);
                }else{
                    alert(d.d);
                    if(d.d == 'Item quantity not available.'){
                        location.reload();
                    }
                    $('.paypal_loader').hide();
                    $('.paypal_button').show();
                }
             
            }, 
            error: function (request, status, error) {
                    alert('We are currently experiencing problems.','Please try again after a few minutes.');
                    $('.paypal_loader').hide();
                    $('.paypal_button').show();      
            }
        });
    });

// -- END OF PAYPAL PROCESS PAYMENT SECTION -- // 

// -- DRAGON PAY PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.btnDp',function () {
        var action = '';
        var postData = ''; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var type = $(this).data('type');
        
        if(PAY_BY_GATEWAY){
            var pointAllocated = $('#pointsAllocated').val();
            if($.isNumeric(pointAllocated) && parseInt(pointAllocated) > 0){
                var paymentMethod = JSON.stringify(
                {
                    DragonPayGateway:{
                        method:"DragonPay", 
                    },
                    PointGateway:{
                        method:"Point",
                        amount:pointAllocated,
                        pointtype: "purchase"
                    }
                });
            }
            else{
                var paymentMethod = JSON.stringify(
                {
                    DragonPayGateway:{
                        method:"DragonPay", 
                    }
                });
            }
            postData = csrfname+"="+csrftoken+"&paymentMethods="+paymentMethod;
            action = config.base_url + "pay/pay";
        }
        else{
            postData = csrfname+"="+csrftoken;
            action = config.base_url + "payment/payDragonPay";
        }

        if($('#chk_dp').is(':checked')){
            $(this).val('Please wait...'); 
            $(this).attr('disabled','disabled');
            $.ajax({
                type: "POST",
                url:  action, 
                dataType: "json",
                data: postData, 
                success: function(d) {
                    if(d.e == 1){ 
                        window.location.replace(d.u);
                    }else{   

                        $('.btnDp').val('Pay via DRAGON PAY'); 
                        $('.btnDp').removeAttr('disabled');    
                        if(d.m == 'Item quantity not available.'){
                            location.reload();
                        }
                        alert(d.m);

                    }
                }
            });
        }else{
            $("#chk_dp").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"}); 
            $('#dragonpay > .chck_privacy > p').empty();
            $('#dragonpay > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>');
        }
    });

// -- END OFDRAGON PAY PROCESS PAYMENT SECTION -- // 

// -- CASH ON DELIVERY PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.payment_cod',function () {
         if($('#chk_cod').is(':checked')){
             var r = confirm('Are you sure you want to make a purchase through Cash on Delivery?');

             if(r == true){
                $(this).val('Please wait...'); 
                $(this).attr('disabled','disabled');

                if(PAY_BY_GATEWAY){
                    var action = config.base_url + "pay/pay"; 
                    var pointAllocated = $('#pointsAllocated').val();

                    if($.isNumeric(pointAllocated) && parseInt(pointAllocated) > 0){
                        var paymentMethod = JSON.stringify(
                        {
                            CODGateway:{
                                method:"CashOnDelivery", 
                                lastDigit:$('input[name=paymentToken]').val().slice(-1)
                            },
                            PointGateway:{
                                method:"Point",
                                amount:pointAllocated,
                                pointtype: "purchase"
                            }
                        });
                    }
                    else{
                        var paymentMethod = JSON.stringify(
                        {
                            CODGateway:{
                                method:"CashOnDelivery", 
                                lastDigit:$('input[name=paymentToken]').val().slice(-1)
                            }
                        });
                    }
                    var data = $('#codFrm').serialize() + "&paymentMethods=" + paymentMethod;
                    $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'html',
                        data: data,
                        success: function(d){
                            window.location.replace(d);
                        }
                    });
                }
                else{
                    $('#codFrm').submit();
                }
             }
         }else{
            $("#chk_cod").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
             "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
             "box-shadow": "0px 0px 2px 2px #FF0000"});
             $('#cod > .chck_privacy > p').empty();
             $('#cod > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>')
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
            $('#dbd > .chck_privacy > p').empty();
            $('#dbd > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>')
        }
    });

// -- END OF DIRECT BANK DEPOSIT PROCESS PAYMENT SECTION -- // 

// -- PESO PAY CC PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.pesopaycdb',function () {
        var action = config.base_url + "payment/payPesoPay"; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        if($('#chk_ppcdb').is(':checked')){
            $(this).val('Please wait...');  
            $.ajax({
                type: "POST",
                url:  action, 
                dataType: "json",
                data: csrfname+"="+csrftoken, 
                success: function(d) {
                     
                    if(d.e == 1){ 
                        console.log(d.d);
                        $('#pesopaycdb').append(d.d); 
                        $('#payFormCcard').submit();
                    }else{   

                        $('.btnDp').val('Pay via Credit or Debit Card'); 
                        $('.btnDp').removeAttr('disabled');    
                        if(d.m == 'Item quantity not available.'){
                            location.reload();
                        }
                        alert(d.m);
                    }
                },
                error: function(err){
                    alert('Something went wrong. Please try again later');
                      $('.btnDp').val('Pay via Credit or Debit Card'); 
                        $('.btnDp').removeAttr('disabled');    
                }
            });
        }else{
            $("#chk_ppcdb").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"}); 
            $('#pesopaycdb > .chck_privacy > p').empty();
            $('#pesopaycdb > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>');
        }
    });

// -- END OF PESO PAY CC PROCESS PAYMENT SECTION -- // 

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
            alert('Please fill up all required fields.');
            return false;
        }
        
        if(!/^(09|08)[0-9]{9}$/.test(mobile.val())){
            alert('Mobile numbers be should 11 digits long and start with 09. eg: 09051235678');
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
            }, 
            error: function (request, status, error) {
                    alert(error);
            }
        });
    });

// -- END OF CHANGE ADDRESS SECTION -- // 

// -- START OF VIEW ITEM AVAILABILITY LOCATION -- //


 
    $(document).on('click','.view_location_item',function () {

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
                    $('.div_view_avail_location').append(d);
                },       
                error: function (request, status, error) {
                    alert(error);
                }
            });
    });


// -- END OF VIEW ITEM AVAILABILITY LOCATION -- //

// -- START REMOVE ITEM FROM SELECTED CART --//

    $(document).on('click','.removeitem',function () {
        
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content'); 
        var rowid = $(this).data('cart-id'); 

        $.ajax({
            type: "POST",
            url:  "/cart/doRemoveSelected",
            dataType: "json",
            data: csrfname+"="+csrftoken+"&rowid="+rowid, 
            success: function(d) {
                if(d.isSuccessful)
                {
                    location.reload();
                }else{
                    alert('Something went wrong. Please refresh this page.');
                }
            } 
        });
    });


// -- END OF REMOVE ITEM FROM SELECTED CART --//

});