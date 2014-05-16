$(document).ready(function(){
 
    $('.paypal_loader').hide();
    $('.div_change_addree').hide();
    $('.paypal_button').show(); 
    

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
                alert('accept our terms!');
                return false;
            }
        }else{
            if(!$('#chk_paypal2').is(':checked')){
                alert('accept our terms!');
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
                }
                $('.paypal_loader').hide();
                $('.paypal_button').show();
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
            alert('accept our terms!');
        }
    });

// -- END OFDRAGON PAY PROCESS PAYMENT SECTION -- // 

// -- CASH ON DELIVERY PROCESS PAYMENT SECTION -- // 

    $(document).on('click','.payment_cod',function () {
        if($('#chk_cod').is(':checked')){
            $('#codFrm').submit();
        }else{
            alert('accept our terms!');
        }
    });

// -- END OF CASH ON DELIVERY PROCESS PAYMENT SECTION -- // 

// -- CHANGE ADDRESS SECTION -- // 

    $(document).on('click','.link_address',function () {
        $('.div_change_addree').modal({
            escClose: false,
            containerCss:{
                maxWidth: 900,
                minWidth: 605,
                maxHeight: 600,
            },
            persist: true
        });
        $('#simplemodal-container').addClass('div_change_addree');
    });

    $(document).on('click','.changeAddressBtn',function () {
        var action = config.base_url + "payment/changeAddress";
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        if($('.stateregionselect').val() == '0' || $('.cityselect').val() == '0' || $('.c_address').val().length == 0 || $('#c_mobile').val().length < 10){
            alert('Fill up Required Fields!');
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
    

});