$(document).ready(function(){
    var stringAcknowledge = '<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>';
    var PAY_BY_GATEWAY = false;
    PAY_BY_GATEWAY ? $('#pointInput').show() : $('#pointInput').hide();
    $('.paypal_loader').hide();
    $('.div_change_addree').hide();
    $('.paypal_button').show();  
    $('#c_mobile').numeric({negative : false});

    $(document).on('keypress','#c_telephone',function (evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 45  && charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        }
        return true;
    }); 

    var cityFilter = function(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value'); 
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){ 
            $('.cityselect').empty();
            $.each(jsonCity[stateregionID], function(k,v){
                $('.cityselect').append('<option value="'+k+'">'+v+'</option>'); 
            });
        }
    }

    $('.stateregionselect').on('change', function(){
        var $this = $(this);
        var cityselect = $this.parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $this, cityselect );
    });

    $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
    $('.stateregionselect').trigger('change');

    // PAYPAL PROCESS PAYMENT SECTION
    $(document).on('click','.paypal',function () {
        var $this = $(this);
        var action = ''; 
        var postData = '';
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var payType = parseInt($this.data('type'));
        var $container = $this.closest('.paypal_button').parent();
        var $checkBox = $container.find('.chk_paypal');

        $container.find('.chck_privacy > p').remove();
        validateWhiteTextBox('.chk_paypal');

        if($checkBox.is(':checked') === false){
            validateRedTextBox('.chk_paypal');
            $container.find('.chck_privacy').append(stringAcknowledge); 
            return false;
        } 

        if(PAY_BY_GATEWAY){
            var pointAllocated = $('#pointsAllocated').val();
            var paymentMethod;
            if($.isNumeric(pointAllocated) && parseInt(pointAllocated) > 0){
                paymentMethod = JSON.stringify({
                    PaypalGateway:{
                        method: "PayPal", 
                        type: payType
                    },
                    PointGateway:{
                        method: "Point",
                        amount: pointAllocated,
                        pointtype: "purchase"
                    }
                });
            }
            else{
                paymentMethod = JSON.stringify({
                    PaypalGateway:{
                        method: "PayPal", 
                        type: payType
                    }
                });
            }
            postData = csrfname+"="+csrftoken+"&paymentMethods="+paymentMethod;
            action = "/pay/pay";
        }
        else{
            postData = csrfname+"="+csrftoken+"&paypal="+payType;
            action = "/pay/setting/paypal";
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
                }
                else{
                    alert(escapeHtml(d.d));
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

    // DRAGON PAY PROCESS PAYMENT SECTION
    function submitDragonpay()
    { 
        var action;
        var postData; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content'); 
        var paymentMethod;
        if(PAY_BY_GATEWAY){
            var pointAllocated = $('#pointsAllocated').val();
            if($.isNumeric(pointAllocated) && parseInt(pointAllocated) > 0){
                paymentMethod = JSON.stringify({
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
                paymentMethod = JSON.stringify({
                    DragonPayGateway:{
                        method:"DragonPay", 
                    }
                });
            }
            postData = csrfname+"="+csrftoken+"&paymentMethods="+paymentMethod;
            action = "/pay/pay";
        }
        else{
            postData = csrfname+"="+csrftoken;
            action = "/payment/payDragonPay";
        }

        $.ajax({
            type: "POST",
            url: action, 
            dataType: "json",
            data: postData, 
            success: function(d) {
                if(d.e == 1){ 
                    window.location.replace(d.u);
                }
                else{
                    $('.btnDp').val('Pay via DRAGON PAY');
                    $('.btnDp').removeAttr('disabled');
                    if(d.m == 'Item quantity not available.'){
                        location.reload();
                    }
                    alert(escapeHtml(d.m));
                }
            }
        });
    }

    $(document).on('click','.btnDp',function () {
        var $this = $(this);
        var $container = $this.parent();
        var $checkBox = $container.find('.chk_dp');

        validateWhiteTextBox(".chk_dp");
        $container.find('.chck_privacy > p').remove();
        if($checkBox.is(':checked')){
            $this.val('Please wait...'); 
            $this.attr('disabled','disabled');
            submitDragonpay();
        }
        else{
            validateRedTextBox(".chk_dp"); 
            $container.find('.chck_privacy').append(stringAcknowledge);
        }
    });

    // CASH ON DELIVERY PROCESS PAYMENT SECTION 
    $(document).on('click','.payment_cod',function () {
        var $this = $(this);
        var $formContainer = $this.closest('.codFrm');
        var $checkBox = $formContainer.find('.chk_cod');

        validateWhiteTextBox(".chk_cod");
        $formContainer.find('.chck_privacy > p').remove();
        if($checkBox.is(':checked')){
            var askConfirm = confirm('Are you sure you want to make a purchase through Cash on Delivery?');
            if(askConfirm){
                $this.val('Please wait...'); 
                $this.attr('disabled','disabled');
                if(PAY_BY_GATEWAY){
                    var action = "/pay/pay"; 
                    var pointAllocated = $('#pointsAllocated').val();
                    var paymentMethod = JSON.stringify({
                        CODGateway:{
                            method:"CashOnDelivery"
                        }
                    });
                    var data = $formContainer.serialize() + "&paymentMethods=" + paymentMethod;
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
                    $formContainer.submit();
                }
            }
        }
        else{
            validateRedTextBox('.chk_cod'); 
            $formContainer.find('.chck_privacy').append(stringAcknowledge);
        }
    }); 

    // PESO PAY CC PROCESS PAYMENT SECTION
    function requestPesopay()
    {
        var action = "/pay/pay"; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var paymentMethod = JSON.stringify({
            PesoPayGateway:{
                method:"PesoPay", 
            }
        });
        $.ajax({
            type: "POST",
            url:  action, 
            dataType: "json",
            data: csrfname+"="+csrftoken+ "&paymentMethods=" + paymentMethod,
            success: function(d) {
                if(d.error === false){
                    $('#pesopaycdb').append(d.form);
                    $('#payFormCcard').submit();
                }
                else{
                    $('.pesopaycdb_btn').val('Pay via Credit or Debit Card'); 
                    $('.pesopaycdb_btn').removeAttr('disabled');
                    if(d.message == 'Item quantity not available.'){
                        location.reload();
                    }
                    alert(escapeHtml(d.message));
                }
            },
            error: function(err){
                alert('Something went wrong. Please try again later');
                $('.pesopaycdb_btn').val('Pay via Credit or Debit Card'); 
                $('.pesopaycdb_btn').removeAttr('disabled');
            }
        });
    }
    $(document).on('click','.pesopaycdb',function () {
        validateWhiteTextBox("#chk_ppcdb");
        $('#pesopaycdb > .chck_privacy > p').remove();
        if($('#chk_ppcdb').is(':checked')){ 
            $(this).val('Please wait...')
                   .attr('disabled','disabled');
            requestPesopay();
        }
        else{
            validateRedTextBox("#chk_ppcdb");
            $('#pesopaycdb > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>');
        }
    });

    $(document).on('click','.pesopaycdb_mobile',function () {
        validateWhiteTextBox(".pesopay_chk_mobile");
        $('#pesopaycdb_mobile > .chck_privacy > p').remove();
        if($('.pesopay_chk_mobile').is(':checked')){ 
            $(this).val('Please wait...')
                   .attr('disabled','disabled');
            requestPesopay();
        }
        else{
            validateRedTextBox(".pesopay_chk_mobile");
            $('#pesopaycdb_mobile > .chck_privacy').append('<p><span style="color:red"> * Please acknowledge that you have read and understood our privacy policy.</span></p>');
        }
    });

    // CHANGE ADDRESS SECTION 
    $(document).on('click','.show-form-address',function () {
        $("#delAddressFrm")[0].reset();
        $('.stateregionselect').trigger('change');
        validateWhiteTextBox('#consignee');
        validateWhiteTextBox('.stateregionselect');
        validateWhiteTextBox('.cityselect');
        validateWhiteTextBox('.c_address');
        validateWhiteTextBox('#c_mobile'); 
    });

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
        var action = "/payment/changeAddress";
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

        if(consignee.val().length == 0 
            || stateregion.val() == '0' 
            || cityselect.val() == '0' 
            || address.val().length == 0 
            || mobile.val().length == 0){
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
                }
                else{
                    alert(escapeHtml(d));
                }
            }, 
            error: function (request, status, error) {
                    alert(error);
            }
        });
    });

    // VIEW ITEM AVAILABILITY LOCATION
    $(document).on('click','.view_location_item',function () {
        var $this = $(this);
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var slug = $this.data('slug');
        var iid = $this.data('iid');
        var pname = $this.data('name');
        var action = "/payment/getLocation"; 
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
                alert(escapeHtml(error));
            }
        });
    });

    // REMOVE ITEM FROM SELECTED CART
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
                if(d.isSuccessful){
                    location.reload();
                }
                else{
                    alert('Something went wrong. Please refresh this page.');
                }
            } 
        });
    });
});

