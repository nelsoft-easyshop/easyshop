(function ($) {

    shippingPreference = jQuery.parseJSON($('#shippingPreference').val());
    checkData = jQuery.parseJSON($('#checkData').val());

})(jQuery);


$(function(){
    $('#step2_link').on('click', function(){
        $('#edit_step2').submit();
    });

    $('#step1_link').on('click', function(){
        $('#edit_step1').submit();
    });
    
    $.modal.defaults.persist = true;
    
});

$(function(){
    $(".product_combination").each(function() {
        if ($(this).find("p").length > 6) {
          $(this).css('overflow-y','scroll');
        }
    });

    $('#tabs').tabs();

    $(".radio-select").on('change',function(){
        var $this = $(this);
        var $value = $this.val();
        var $freeShippingBtn = $('#set_free_shipping');
        var $shippingDetailsBtn = $('#set_shipping_details');

        if($value == "meetup"){ 
            $('#delivery_options').slideUp();
            $('#prod_delivery_cost').val('off');
            $('#shipping_div').hide();
            $freeShippingBtn.addClass('active');
            $shippingDetailsBtn.removeClass('active');
        }
        else{
            $('#delivery_options').slideDown();
            $('#prod_delivery_cost').val('free');
            if(!$freeShippingBtn.hasClass('active')){
                $freeShippingBtn.addClass('active');
            }
            else{
                $shippingDetailsBtn.removeClass('active');
                $('#shipping_div').hide();
            }
        } 
    }); 
    
    $('.delivery_cost').on('click',function(){
        var shippingDiv = $('#shipping_div');
        var setShippingDetailsBtn = $('#set_shipping_details');
        
        if( $(this).hasClass('active') ){
            $(this).removeClass('active');
            $(this).siblings('.delivery_cost').addClass('active');
        }else{
            $(this).addClass('active');
            $(this).siblings('.delivery_cost').removeClass('active');
        }
        
        if( setShippingDetailsBtn.hasClass('active') ){
            shippingDiv.slideDown();
            $('#prod_delivery_cost').val('details');
        }else{
            shippingDiv.slideUp();
            $('#prod_delivery_cost').val('free');
        }
    });
    
    // Activate chosen for select fields and trim nbsp for display purposes
    $('select.shiploc:not(#shiploc_clone)').chosen({width:"480px"});
    $('select.shiploc:not(#shiploc_clone)').each(function(){
        chosenSelectedTrimmer($(this));
    });
    
    
    $('#shipping_div').on('blur','input.shipprice',function(){
        var price = $.trim($(this).val());
        var newPrice = price.replace(new RegExp(",", "g"), '');
        newPrice = parseFloat(newPrice).toFixed(2);
        
        if( $.isNumeric(newPrice) && newPrice >= 0 && !newPrice.match(/[a-zA-Z\+]/) ){
            $(this).val( newPrice );
        }
        else{
            $(this).val('');
        }
    }).on('keyup','input.shipprice',function(e){
        var price = $.trim($(this).val());
        var newPrice = price.replace(new RegExp(",", "g"), '');
        newPrice = parseFloat(newPrice).toFixed(2);
        
        if( (e.keyCode == 13 || e.which == 13) && $.isNumeric(newPrice) && newPrice >= 0 && !newPrice.match(/[a-zA-Z\+]/) ){
            $(this).val( newPrice );
        }
        else if ( (e.keyCode == 13 || e.which == 13) && !$.isNumeric(newPrice) ) {
            $(this).val('');
        }
    });

    $(document).on('change',"#ship-within",function () {
        var qty = this.value;
        var value = parseInt(qty);
        var tempval;
        if (isNaN(value)) {
            this.value = '';
        } else {
            if(value > 127){
                value = 127
            }
            tempval = Math.abs(value);
            this.value = tempval;
        }
    });
    
    $('#shipping_div').on('click', 'input.shipprice,div.chosen-container', function(){
        if( $(this).hasClass('my_err') ){
            validateWhiteTextBox($(this));
        }
    });
    
});

/*
|	Bank Edit, Save, Update, Cancel, Functions
*/
$(document).ready(function(){
    
    $('#bank_details').on('click', '.deposit_edit', function(){
        $('#temp_deposit_acct_name').val($('#deposit_acct_name').val());
        $('#temp_deposit_acct_no').val($('#deposit_acct_no').val());
        $('#temp_bank_list').val($('#bank_list').val());
        $('#temp_bank_name').val($('#bank_name').val());

        $('#deposit_acct_name').attr('readonly', false);
        $('#deposit_acct_no').attr('readonly', false);
        $('#bank_list').attr('disabled', false);
        $('.deposit_edit').hide();
        $('.deposit_update').show();
        $('.deposit_cancel').show();
    });
    
    $('#bank_details').on('click', '.deposit_update', function(){
    
        var account = {
            account_name: $('#deposit_acct_name').val(),
            bank_name: $('#bank_name').val(),
            account_no: $('#deposit_acct_no').val(),
            bank_list:  $('#bank_list').val(),
            billing_id: $('#billing_info_id').val()
        };
        
        var valid = true;

        if($.trim(account.account_name) === ''){
            validateRedTextBox('#deposit_acct_name');
            valid = false;
        }
        if($.trim(account.account_no) === ''){
            validateRedTextBox('#deposit_acct_no');
            valid = false;
        }
        if(parseInt(account.bank_list,10) === 0){
            validateRedTextBox('#bank_list');
            valid = false;
        }
        if(!valid){
            return false;
        }
        
        var $prod_div = $('.acct_prod[data-bid='+account.billing_id+']');
        if(typeof $prod_div[0] !== 'undefined'){
            $prod_div.dialog({
                modal:true,
                resizable:false,
                draggable:false,
                width:650,
                height: 400,
                title: 'Confirm Changes',
                buttons:{
                    OK:function(){
                        $(this).dialog('close');
                        update_bank_account(account);
                    },
                    Cancel:function(){
                        $(this).dialog('close');
                    },
                }
            });
        }
        else{
            update_bank_account(account);
        }
    });
    
    function update_bank_account(account){
            
        var selected = $('#deposit_info [value="'+account.billing_id+'"]');
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        jQuery.ajax({
            type: "POST",
            url: '/memberpage/updatePaymentAccount', 
            data: "payment-account-id="+account.billing_id+"&account-name="+account.account_name+"&account-number="+account.account_no+"&bank-id="+account.bank_list+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if(jsonResponse.isSuccessful){
                    selected.data('bankid',account.bank_list);
                    selected.data('acctname',account.account_name);
                    selected.data('acctno',account.account_no);
                    selected.data('bankname',account.bank_name);
                    selected.text('Bank: '+account.bank_name+' - '+ account.account_name);
                    
                    $('#deposit_acct_name').attr('readonly', true);
                    $('#deposit_acct_no').attr('readonly', true);
                    $('#bank_list').attr('disabled', true);
                    $('.deposit_edit').show();
                    $('.deposit_update').hide();
                    $('.deposit_cancel').hide();
                }else{
                    var error = jsonResponse.errors[0];
                    alert(escapeHtml(error));
                }
            }
        });
    }
    
    $('#bank_details').on('click', '.deposit_save', function(){
        var account_name = $('#deposit_acct_name').val();
        var bank_name = $('#bank_name').val();
        var account_no = $('#deposit_acct_no').val();
        var bankId = $('#bank_list').val();
        var valid = true;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        if($.trim(account_name) === ''){
            validateRedTextBox('#deposit_acct_name');
            valid = false;
        }
        if($.trim(account_no) === ''){
            validateRedTextBox('#deposit_acct_no');
            valid = false;
        }
        if(parseInt(bankId,10) === 0){
            validateRedTextBox('#bank_list');
            valid = false;
        }
        if(!valid){
            return false;
        }
        
        jQuery.ajax({
            type: "POST",
            url: '/memberpage/createPaymentAccount', 
            data: "account-bank-id="+bankId+"&account-name="+account_name+"&account-number="+account_no+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if(jsonResponse.isSuccessful){
                    var new_option = $(document.createElement( "option" ));
                    new_option.data('bankname',bank_name);
                    new_option.data('bankid',bankId);
                    new_option.data('acctname',account_name);
                    new_option.data('acctno',account_no);
                    new_option.text('Bank: '+bank_name+' - '+ account_name);
                    var new_id = parseInt(jsonResponse.newId,10);
                    new_option.val(new_id);
                    new_option.insertBefore($('#deposit_info').find(':selected'));
                    $('#deposit_info').find(':selected').prop('selected', false);
                    $('#deposit_info').find('option[value = "'+new_id+'"]').prop('selected', true);
                    $('#billing_info_id').val(new_id);
                    $('#deposit_acct_name').attr('readonly', true);
                    $('#deposit_acct_no').attr('readonly', true);
                    $('#bank_list').attr('disabled', true);
                    $('.deposit_edit').show();
                    $('.deposit_save').hide();
                    $('.deposit_update').hide();
                    $('.deposit_cancel').hide();
                }
                else{
                    var error = jsonResponse.errors[0];
                    alert(escapeHtml(error)); 
                }
            }
        });

    });
    
    $('#bank_details').on('click', '.deposit_cancel', function(){
        $('#deposit_acct_name').val($('#temp_deposit_acct_name').val());
        $('#deposit_acct_no').val($('#temp_deposit_acct_no').val());
        $('#bank_list').val($('#temp_bank_list').val());
        $('#bank_name').val($('#temp_bank_name').val());
        validateWhiteTextBox('#deposit_acct_name');
        validateWhiteTextBox('#deposit_acct_no');
        validateWhiteTextBox('#bank_list');
        $('#deposit_acct_name').attr('readonly', true);
        $('#deposit_acct_no').attr('readonly', true);
        $('#bank_list').attr('disabled', true);
        $('.deposit_edit').show();
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
    });

    $('#bank_details').on('change','#deposit_info',function(){
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
        var selected = $('#deposit_info').find(":selected");
        $this = $(this);
        validateWhiteTextBox('#deposit_acct_name');
        validateWhiteTextBox('#deposit_acct_no');
        validateWhiteTextBox('#bank_list');
        
        if(parseInt(selected.val(),10) !== 0){
            $('#deposit_acct_name').attr('readonly', true);
            $('#deposit_acct_no').attr('readonly', true);
            $('#bank_list').attr('disabled', true);
            $('#billing_info_id').val( $this.val());
            $('#deposit_acct_name').val(selected.data('acctname'));
            $('#deposit_acct_no').val(selected.data('acctno'));
            $('#bank_name').val(selected.data('bankname'));
            $('#bank_list').find(':selected').prop('selected', false);
            $('#bank_list').find('option[value = "'+selected.data('bankid')+'"]').prop('selected', true);
            $('.deposit_edit').show();
            $('.deposit_save').hide();
        }
        else{
            $('#deposit_acct_name').val('');
            $('#deposit_acct_name').attr('readonly', false);
            $('#deposit_acct_no').val('');
            $('#deposit_acct_no').attr('readonly', false);
            $('#billing_info_id').val(0);
            $('#bank_name').val('');
            var default_option = $('#bank_list').find('option[value = "0"]');
            default_option.prop('selected', true);
            $('#bank_list').attr('disabled', false);
            $('.deposit_save').show();
            $('.deposit_edit').hide();
        }
    });
    
    $('#bank_details').on('change','#bank_list',function(){
        var selected = $('#bank_list').find(":selected");
        $('#bank_name').val(selected.text());
    });
    
    $(document).on('keypress',"#deposit_acct_name,#deposit_acct_no",function () {
        var id = $(this).attr('id');
        validateWhiteTextBox("#"+id);
    });

    $(document).on('change',"#bank_list",function () {
        var id = $(this).attr('id');
        validateWhiteTextBox("#"+id);
    });
    
});

/*
|
|	Shipping Functions
|
*/
$(function(){
    
    // Add new price and location fields
    $('#shipping_div').on('click', '.new_shipping_input', function(){
        var datagroup = $(this).parent().siblings('div.data_group');
        var clonefield = datagroup.children('div.shipping_input:first').clone();
        var shippinginputfield = $(this).parent().siblings('.shipping_input_count');
        var inputkey = parseInt(shippinginputfield.val()) + 1;
        var groupkey = parseInt($(this).closest('div.shipping_group').data('sgkey'));
        var removeRow = $('<div class="del_shipping_input col-xs-2"><span class="del_bgs3 glyphicon glyphicon-remove"></span><span>Remove row</span></div>');
        
        var disabledOption = datagroup.find('option:selected');

        var selectClone = $('#shiploc_clone').clone();
        selectClone.removeAttr('style id');
        
        clonefield.find('select.shiploc').remove();
        clonefield.find('div.chosen-container').remove();
        clonefield.find('label[for="location"]').after(selectClone);
        clonefield.find('.upload_chosen').after(removeRow);
        
        clonefield.attr('data-sikey',inputkey);
        clonefield.find('input.shipprice').attr('name',"shipprice["+groupkey+"]["+inputkey+"]").val('');
        clonefield.find('select.shiploc').attr('name',"shiploc["+groupkey+"]["+inputkey+"][]");
        
        // Disable all selected options for this clone based on its sibling selects
        if(disabledOption.length > 0){
            disabledOption.each(function(){
                var disabledVal = parseInt($(this).val());
                clonefield.find('option[value="'+disabledVal+'"]').attr('disabled',true);
            });
        }
        
        clonefield.find('select.shiploc').chosen({width:"77%"});
        
        datagroup.find('div.attr_border').before(clonefield.show()); //Insert clonefield before this btn
        shippinginputfield.val(inputkey);
    });
    
    // Add new group
    $('#shipping_div').on('click', 'div.new_shipping_group', function(){
        var clonefield = $(this).siblings('div.shipping_group:first').clone();
        var shippinggroupfield = $(this).siblings('input.shipping_group_count');
        var inputkey = 0;
        var groupkey = parseInt(shippinggroupfield.val()) + 1;
        var removegroup = $('<div class="del_shipping_group wbtn"><div class="btn btn-default"><span class="del_bgs3 glyphicon glyphicon-remove-sign"></span><span>REMOVE GROUP</span></div></div>');
        
        var selectClone = $('#shiploc_clone').clone();
        selectClone.removeAttr('style id');
        
        clonefield.find('div.shipping_input:not(:first)').remove();
        
        clonefield.find('select.shiploc').remove();
        clonefield.find('div.chosen-container').remove();
        clonefield.find('label[for="location"]').after(selectClone);
        
        clonefield.attr('data-sgkey',groupkey);
        clonefield.children('input.shipping_input_count').val(inputkey);
        
        clonefield.find('div.shipping_input').attr('data-sikey', inputkey);
        clonefield.find('input.shipprice').attr('name',"shipprice["+groupkey+"]["+inputkey+"]").val('');
        clonefield.find('select.shiploc').attr('name',"shiploc["+groupkey+"]["+inputkey+"][]");
        clonefield.find('input.shipattr').attr('name','shipattr['+groupkey+'][]');
        clonefield.find('select.shipping_preference').val(0);
        clonefield.find('.delete_ship_pref').hide();
        clonefield.find('input.shipattr').prop('checked', true);
        clonefield.find('input.shipattr').parent('label').addClass('active');
        
        clonefield.find('select.shiploc').chosen({width:"480px"});
        
        clonefield.find('div.button_group div.new_shipping_input').after(removegroup);
        
        $(this).before(clonefield.show());
        shippinggroupfield.val(groupkey);
    });
    
    // Remove Group
    $('#shipping_div').on('click','div.del_shipping_group', function(){
        var datagroup = $(this).parent().siblings('div.data_group');
        var divInput = datagroup.children('div.shipping_input');
        var selectedOption = divInput.find('option:selected');
        var divAttr = datagroup.children('div.shipping_attr');
        var selectedAttr = divAttr.find('input:checked');

        if(selectedOption.length>0){
            selectedAttr.each(function(){ // Delete selected locations from selected attributes
                var attrkey = parseInt($(this).val());
                selectedOption.each(function(){
                    var loc = parseInt($(this).val());
                    checkData[attrkey] = $.grep(checkData[attrkey],function(value){
                        return value != loc;
                    });
                });
            });
        }
        $(this).closest('div.shipping_group').remove();
        
    });
    
    // Remove price and location fields
    $('#shipping_div').on('click', 'div.del_shipping_input', function(){
        var divInput = $(this).closest('div.shipping_input');
        var selectShipLoc = divInput.find('select.shiploc');
        var divSiblingInput = divInput.siblings('div.shipping_input');
        
        var divAttr = divInput.siblings('div.shipping_attr');
        var selectedAttr = divAttr.find('input:checked');
        
        var selectedOption = selectShipLoc.find('option:selected');
        
        if(selectedOption.length>0){ //Check for selected options 
            var siblingSelect = divSiblingInput.find('select.shiploc');
            selectedOption.each(function(){ // Re enable selected locations in sibling selects
                var loc = parseInt($(this).val());
                siblingSelect.find('option[value="'+loc+'"]').attr('disabled',false);
            });
            siblingSelect.trigger('chosen:updated');
            chosenSelectedTrimmer(siblingSelect);
            
            selectedAttr.each(function(){ // Delete selected locations from selected attributes
                var attrkey = parseInt($(this).val());
                selectedOption.each(function(){
                    var loc = parseInt($(this).val());
                    checkData[attrkey] = $.grep(checkData[attrkey],function(value){
                        return value != loc;
                    });
                });
            });
            
        }
        
        divInput.remove();
    });
    
    // On change handler for select location
    $('#shipping_div').on('change','select.shiploc',function(evt,params){
        var thisSelect = $(this);
        var divInput = thisSelect.closest('div.shipping_input');
        var divSiblingInput = divInput.siblings('div.shipping_input');
        var divAttr = divInput.siblings('div.shipping_attr');
        var selectedAttr = divAttr.find('input:checked');
        
        var hasDuplicate = false;
        
        for(k in params){
            var action = k; //selected or deselected
            var loc = parseInt(params[action]); //location id
        }
        
        if( action === 'selected' ){
            // Disable selected option in other sister selects
            if( divSiblingInput.length > 0 ){
                divSiblingInput.find('option[value="'+loc+'"]').attr('disabled',true);
                divSiblingInput.find('select.shiploc').trigger('chosen:updated');
                chosenSelectedTrimmer(divSiblingInput.find('select.shiploc'));
            }
            
            if( selectedAttr.length > 0 ){ //if attributes are selected
                selectedAttr.each(function(){ //cycle through each attribute and check for duplicate
                    var attrkey = parseInt($(this).val());
                    if( $.inArray(loc,checkData[attrkey]) !== -1 ){ //if location exists for selected attribute
                        hasDuplicate = true;
                        return false; //exit .each function
                    }
                });
                if( !hasDuplicate ){ //if no duplicate insert in array
                    selectedAttr.each(function(){
                        var attrkey = parseInt($(this).val());
                        checkData[attrkey].push(loc);
                    });
                }else{ //else alert and deselect location selected by user
                    alert('Location already set for selected attribute');
                    thisSelect.find('option:selected[value="'+loc+'"]').attr('selected', false);
                    thisSelect.trigger('chosen:updated');
                }
            }
            chosenSelectedTrimmer(thisSelect);
            
        }else if( action === 'deselected' ){ // delete location entry from checkData[attrkey]
            if( divSiblingInput.length > 0 ){
                divSiblingInput.find('option[value="'+loc+'"]').attr('disabled',false);
                divSiblingInput.find('select.shiploc').trigger('chosen:updated');
                
                // Trim nbsp in option for display purposes
                divSiblingInput.each(function(){
                    var siblingChildSelect = $(this).find('select.shiploc');
                    chosenSelectedTrimmer(siblingChildSelect);
                });
            }
            if( selectedAttr.length > 0 ){
                selectedAttr.each(function(){
                    var attrkey = parseInt($(this).val());
                    checkData[attrkey] = jQuery.grep(checkData[attrkey], function(value){
                        return value != loc;
                    });
                });
            }
        }
    });
    
    // On change handler for checkbox
    $('#shipping_div').on('change', 'input.shipattr', function(){
        var thisCheckbox = $(this);
        var attrkey = parseInt(thisCheckbox.val());
        
        var divAttr = thisCheckbox.closest('div.shipping_attr');
        var divInput = divAttr.siblings('div.shipping_input');
        var selectedLoc = divInput.find('option:selected');
        
        var hasDuplicate = false;
        
        if( thisCheckbox.is(':checked') ){ //if checkbox is checked
            if( selectedLoc.length > 0 ){ // cycle through each location and check for duplicate
                selectedLoc.each(function(){
                    var loc = parseInt($(this).val());
                    if( $.inArray(loc, checkData[attrkey]) !== -1 ){ // if location exists for selected attr
                        hasDuplicate = true;
                        return false;
                    }
                });
                if( !hasDuplicate ){ // if no duplicate insert in array
                    selectedLoc.each(function(){
                        var loc = parseInt($(this).val());
                        checkData[attrkey].push(loc);
                    });
                }else{ // if has duplicate, alert and uncheck checked textbox
                    alert('Location already set for selected attribute');
                    thisCheckbox.attr('checked',false);
                }
            }
            if( !hasDuplicate ){
                thisCheckbox.parent('label').addClass('active');
            }
        }else{ //if checkbox is unchecked
            if( selectedLoc.length > 0 ){
                selectedLoc.each(function(){
                    var loc = parseInt($(this).val());
                    checkData[attrkey] = $.grep(checkData[attrkey], function(value){
                        return value != loc;
                    });
                });
            }
            thisCheckbox.parent('label').removeClass('active');
        }
    });
});


/****************************************************************************************/
/************************	Submit Handler	*********************************************/
/****************************************************************************************/
$(function(){
    $('#finish_step3').on('click',function(){
        var thisbtn = $(this);
        var form = $('#form_shipping');
        var data = $(form).serializeArray();
        var prodDeliveryCost = $('#prod_delivery_cost').val();
        var shippingGroup = $('#shipping_div div.shipping_group');
        var inputGroup = $('#shipping_div div.shipping_input');
        
        var paymentAccountSelectBox = $('#deposit_info');
        if(paymentAccountSelectBox.length > 0){
            var paymentAccountId = paymentAccountSelectBox.find('option').length;
            if(paymentAccountId <= 1){
                var isProceedPaymentAccount = confirm("We can't send your money if your bank details are empty, are you sure you want to proceed without entering your bank details?");
                if(!isProceedPaymentAccount){
                    $('html, body').animate({
                        scrollTop:$('#bank_details').offset().top
                    }, 1500);
                    return false;
                }
            }
        }

        // Check for "Meetup" or "Delivery"
        var checkedDeliveryOption = form.find('input.delivery_option:checked');
        if( checkedDeliveryOption.length <= 0){
            alert('Please select at least one delivery option');
            return false;
        }
        
        // Check if price and location pair exists for every input group
        var isIncomplete = false;
        if(prodDeliveryCost === 'details'){ // if shipping is not free, verify details
            // Check all rows with data
            inputGroup.each(function(){
                var thisgroup = $(this);
                var priceField = thisgroup.find('input.shipprice');
                var price = $.trim(priceField.val());
                var newPrice = price.replace(new RegExp(",", "g"), '');
                newPrice = parseFloat(newPrice).toFixed(2);
                
                var locField = thisgroup.find('select.shiploc');
                var selectedLoc = locField.find('option:selected');
                var chosenDiv = locField.siblings('div.chosen-container');
                
                // If more than 1 input group, highlight input group with only 1 data (price or loc) provided
                // All input groups with no price and no location are disregarded both client side and server side
                if( inputGroup.length > 1 ){
                    // If price provided with no location, highlight location
                    if( $.isNumeric(newPrice) && selectedLoc.length === 0 ){
                        isIncomplete = true;
                        validateRedTextBox(chosenDiv);
                    // If location provided with no price, highlight location
                    }else if( selectedLoc.length > 0 && !$.isNumeric(newPrice) ){
                        isIncomplete = true;
                        validateRedTextBox(priceField);
                    }
                // If only 1 input group, highlight blank field since it is required
                }else if ( inputGroup.length === 1 ){
                    if( !$.isNumeric(newPrice) ){
                        isIncomplete = true;
                        validateRedTextBox(priceField);
                    }
                    if( selectedLoc.length === 0 ){
                        isIncomplete = true;
                        validateRedTextBox(chosenDiv);
                    }
                }
                
            });
            
            //Check if all attributes have indicated locations
            var incAttr = false;
            $.each(checkData, function(attrkey,locationArr){
                if(locationArr.length <= 0){
                    incAttr = true;
                    return false;
                }
            });
            if(incAttr){
                alert("Please provide shipping details for all item properties.");
                return false;
            }
        }
        
        // If incomplete, scroll to first error field,
        if( isIncomplete ){
            myScrollTo($('.my_err:first'));
        }
        // If data is complete, allow posting of data to server
        else{
            $.post("/sell/step4", data, function(d){
                thisbtn.attr('disabled', false);
                try{
                    var obj = jQuery.parseJSON(d);
                }
                catch(e){
                    alert('Error submitting your request. Please try again later.');
                    return false;
                }
                
                if(obj.result === 'success'){
                    $('#finish_upload_form').submit();
                }else{
                    thisbtn.val("Finish");
                    alert(obj.error);
                }
            });
            
            thisbtn.val('Saving...');
            thisbtn.attr('disabled', true);
        }
        
    });
});


/***********************************************************************************/
/************************	Shipping Preference Functions	************************/
/***********************************************************************************/
(function($){
    /*
     *	On change handler for select shipping preference
     */ 
    $('#shipping_div').on('change', '.shipping_preference', function(){
        var option = parseInt($(this).children('option:selected').val());
        var parentDiv = $(this).closest('div.prefsel');
        var groupDiv = parentDiv.parent('div.shipping_group');
        var datagroup = parentDiv.siblings('div.data_group');
        var newInput = parentDiv.siblings('div.button_group').children('div.new_shipping_input');
        
        var selectedLocations = groupDiv.find('select.shiploc option:selected');
        var selectedAttributes = groupDiv.find('input.shipattr:checked');
        var isDuplicate = false;
        
        var delPrefOpt = $(this).siblings('.delete_ship_pref');
        
        var siblingGroupDiv = groupDiv.siblings('div.shipping_group');
        
        if(option === 0){
            delPrefOpt.hide();
            return false;
        }else{
            
            // Perform duplicate check with alert error when more than 1 group is created
            if( siblingGroupDiv.length > 0 ){
                // Check if locations in preference selected are already used by selected attributes. If yes, deny usage
                $.each(shippingPreference[option], function(price,locationArr){
                    if( !isDuplicate ){
                        $.each(locationArr, function(k,locationID){
                            if( !isDuplicate ){
                                selectedAttributes.each(function(){
                                    var attrkey = parseInt($(this).val());
                                    if( jQuery.inArray(locationID, checkData[attrkey]) !== -1 ){
                                        isDuplicate = true;
                                    }
                                });
                            }else{
                                return false;
                            }
                        });
                    }else{
                        return false;
                    }
                });
                
                // If duplicate is found, exit this function and set select option to default
                if( isDuplicate ){
                    $(this).val(0);
                    alert("Locations from selected preference are already in use by selected attributes.");
                    delPrefOpt.hide();
                    return false;
                }
            }
            
            // Show Delete option
            delPrefOpt.show();
            
            // Remove all currently selected locations from checked attributes from checkData
            if(selectedLocations.length > 0){
                selectedAttributes.each(function(){
                    var attrkey = parseInt($(this).val());
                    selectedLocations.each(function(){
                        var lockey = parseInt($(this).val());
                        checkData[attrkey] = $.grep(checkData[attrkey],function(value){
                            return value != lockey;
                        });
                    });
                });
            }
            
            // Remove input fields except first for cloning
            datagroup.children('div.shipping_input:not(:first)').remove();
            
            var i = 0;
            newInput.trigger('click'); // Triggers cloning
            datagroup.find('option:selected').attr('selected',false);
            datagroup.find('option:disabled').attr('disabled',false);
            datagroup.find('select.shiploc').trigger('chosen:updated');
            datagroup.children('div.shipping_input:first').remove(); // Remove first input field
            datagroup.find('div.shipping_input:first div.del_shipping_input').remove(); // Remove "remove row" option
            var firstLocField = datagroup.children('div.shipping_input:eq(0)').find('select.shiploc'); // Re-define first location field
            
            // Assign values to select.shiploc fields
            $.each(shippingPreference[option], function(price,locationArr){
                if( i !== 0 ){
                    newInput.trigger('click');
                }
                var inputDiv = datagroup.children('div.shipping_input:eq('+i+')');
                var priceField = inputDiv.find('input.shipprice');
                var locField = inputDiv.find('select.shiploc');
                
                priceField.val(price);
                
                $.each(locationArr, function(k,locationID){
                    // Push locations from preference to selected attributes
                    selectedAttributes.each(function(){
                        var attrkey = parseInt($(this).val());
                        checkData[attrkey].push(locationID);
                    });
                    // Select location on select element
                    locField.find('option[value="'+locationID+'"]').prop('selected', true);
                    if( i!==0 ){
                        firstLocField.find('option[value="'+locationID+'"]').attr('disabled',true);
                        firstLocField.trigger('chosen:updated');
                        chosenSelectedTrimmer(firstLocField);
                    }
                });
                locField.trigger('chosen:updated');
                chosenSelectedTrimmer(locField);
                i++;
            });
        }
    }); 
    
    /*
     *	Add shipping preferences
     */
    $('#shipping_div').on('click','.add_ship_preference', function(){
        var $thisButton = $(this);
        var $closesSelect = $thisButton.closest('.shipping_group').find('.shipping_preference');
        var isIncomplete = false;
        var preferenceData = {};
        var preferenceName = "";
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        
        // Get location vs price details for first attr array, since data is same for all attr arrays
        var inputDiv = $thisButton.parent().siblings('div.data_group').children('div.shipping_input');
        
        inputDiv.each(function(){
            var priceField = $(this).find('input.shipprice');
            var locField = $(this).find('select.shiploc');
            var price = $.trim($(this).find('input.shipprice').val());
            var newPrice = price.replace(new RegExp(",", "g"), '');
            newPrice = parseFloat(newPrice).toFixed(2);
            var locArr = $(this).find('select.shiploc option:selected');
            
            if( $.isNumeric(newPrice) && locArr.length > 0 ){
                locArr.each(function(){
                    var locID = parseInt($(this).val());
                    preferenceData[locID] = newPrice;
                });
            }else{
                isIncomplete = true;
                preferenceData = {};
                if( !$.isNumeric(newPrice) ){
                    validateRedTextBox(priceField);
                }
                if( locArr.length === 0 ){
                    validateRedTextBox(locField);
                }
                myScrollTo($(this).find('.my_err:first'));
                return false;
            }
        });
        
        if( !isIncomplete ){
            $('#dialog_preference_name').dialog({
                height: 'auto',
                width: 'auto',
                autoOpen: false,
                title: "Enter Preference name",
                modal: true,
                closeOnEscape: false,
                draggable: false,
                fluid: true,
                buttons:[
                    {
                        text: "Ok",
                        click: function(){
                            var namefield = $('#preference_name');
                            var thisdialog = $(this);
                            preferenceName = namefield.val();
                            
                            if( ($.trim(preferenceName)).length > 0){
                                $('button.ui-button').attr('disabled',true);
                                namefield.attr('disabled',true);
                                namefield.siblings('img.loading').show();
                                
                                $.post('/productUpload/step3_addPreference',{data:preferenceData, name:preferenceName, csrfname:csrftoken},function(data){
                                    namefield.siblings('img.loading').hide();
                                    namefield.attr('disabled',false);
                                    $('button.ui-button').attr('disabled',false);
                                    
                                    try{
                                        var obj = jQuery.parseJSON(data);
                                    }
                                    catch(e){
                                        alert('An error was encountered while processing your data. Please try again later.');
                                        window.location.reload(true);
                                        return false;
                                    }
                                    
                                    if( obj['result'] === 'success' ){
                                        shippingPreference = obj['shipping_preference'];
                                        var prefSelect = $('select.shipping_preference');
                                        prefSelect.each(function(){
                                            var thisSelect = $(this);
                                            $.each(shippingPreference['name'], function(headId, name){
                                                thisSelect.append('<option value="'+escapeHtml(headId)+'">'+escapeHtml(name)+'</option>');
                                            });
                                        });
                                        $closesSelect.children('option:last').attr("selected","selected");
                                    }
                                    else{
                                        alert(obj['error']);
                                    }
                                    thisdialog.dialog("close");
                                });
                            } else {
                                namefield.effect('pulsate', {times:3}, 800);
                            }
                        }
                    },
                    {
                        text: "Cancel",
                        click: function(){
                            $(this).dialog("close");
                        }
                    },
                ],
                close: function(){
                    $('#dialog_preference_name input[name="preference_name"]').val('');
                }
            });
            $('#dialog_preference_name').dialog("open");
        }
    });
    
    /*
     *	Delete Shipping Preference
     */
     $('#shipping_div').on('click','.delete_ship_pref', function(){
        var allSelect = $('select.shipping_preference');
        var siblingSelect = $(this).siblings('select.shipping_preference');
        var headId = parseInt(siblingSelect.val());
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        
        var thisspan = $(this);
        
        var r=confirm("Confirm delete?");
        if (r==true){
            $.post('/productUpload/step3_deletePreference', {head:headId, csrfname:csrftoken}, function(data){
                try{
                    var obj = jQuery.parseJSON(data);
                }
                catch(e){
                    alert('An error was encountered while processing your data. Please try again later.');
                    window.location.reload(true);
                    return false;
                }
                
                if( obj['result'] === 'success' ){
                    // Check each select and hide delete button when headId is selected (then is removed)
                    allSelect.each(function(){
                        selectedVal = parseInt($(this).val());
                        if( selectedVal === headId ){
                            $(this).siblings('.delete_ship_pref').hide();
                        }
                    });
                    
                    allSelect.find('option[value="'+headId+'"]').remove();
                    siblingSelect.val(0);
                    thisspan.hide();
                    
                }else{
                    alert( obj['error'] );
                }
            });
        }
     });
     
})(window.jQuery);


/**
 *	Function to scroll to desired element
 */
function myScrollTo(scrollTo){	
    $('html, body').animate({
        scrollTop: scrollTo.offset().top - scrollTo.offset().top * 0.05
    });
}
 
 
/**
  *	Function to fix display of chosen. removes nbsp for display purposes
  */ 
function chosenSelectedTrimmer(selectObj){
    var selectedSpan = selectObj.siblings('div.chosen-container').find('.search-choice span');
    
    selectedSpan.each(function(){
        var origtext = $(this).text();
        var newtext = $.trim(origtext);
    
        $(this).text(newtext);
    });
}

/**
 * Check if the event keycode is number key
 * @param  {mixed}  evt
 * @return {Boolean}
 */
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57)){
        return false;
    }

    return true;
}

