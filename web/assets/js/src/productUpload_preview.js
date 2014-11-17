$(document).ready(function(){
    
    var modal = $('#modal').val();
    if(parseInt(modal,10) === 0){
        $('#tabs').tabs();         
        $('#prod_billing_id').val( $('#billing_info_id').val());
    }
    
    $('#previewProduct').on('click', '.deposit_edit', function(){
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
    
    $('#previewProduct').on('click', '.deposit_update', function(){
    
        var account = {
            account_name: $('#deposit_acct_name').val(),
            bank_name: $('#bank_name').val(),
            account_no: $('#deposit_acct_no').val(),
            bank_list:  $('#bank_list').val(),
            billing_id: $('#billing_info_id').val()
        };
        
	console.log(account);
	
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
            url: '/memberpage/billing_info_u', 
            data: "bi_id="+account.billing_id+"&bi_payment_type"+"=Bank&bi_acct_name="+account.account_name+"&bi_acct_no="+account.account_no+"&bi_bank="+account.bank_list+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                var obj = JSON.parse(response);
                if((parseInt(obj.e,10) == 1) && (obj.d=='success')){
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
                    
                    
                }else if((parseInt(obj.e,10) == 0) && (obj.d=='duplicate')){
                    alert('You are already using this account number.');
                }else{
                    alert('We are having a problem right now. Refresh the page to try again.');
                }
            }
        });
    }
    
    $('#previewProduct').on('click', '.deposit_save', function(){
        var account_name = $('#deposit_acct_name').val();
        var bank_name = $('#bank_name').val();
        var account_no = $('#deposit_acct_no').val();
        var bank_list = $('#bank_list').val();
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
        if(parseInt(bank_list,10) === 0){
            validateRedTextBox('#bank_list');
            valid = false;
        }
        if(!valid){
            return false;
        }
        
        jQuery.ajax({
            type: "POST",
            url: '/memberpage/billing_info', 
            data: "express=true&bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                var obj = JSON.parse(response);
                if((parseInt(obj.e,10) == 0)&&(obj.d=='duplicate')){
                    alert('You are already using this account number');					
                }else if((parseInt(obj.e,10) == 1)&&(obj.d=='success')){
                    var new_option = $(document.createElement( "option" ));
                    new_option.data('bankname',bank_name);
                    new_option.data('bankid',bank_list);
                    new_option.data('acctname',account_name);
                    new_option.data('acctno',account_no);
                    new_option.text('Bank: '+bank_name+' - '+ account_name);
                    var new_id = parseInt(obj.id,10);
                    new_option.val(new_id);
                    new_option.insertBefore($('#deposit_info').find(':selected'));
                    $('#deposit_info').find(':selected').prop('selected', false);
                    $('#deposit_info').find('option[value = "'+new_id+'"]').prop('selected', true);
                    $('#prod_billing_id').val(new_id);
                    $('#billing_info_id').val(new_id)
                    $('#deposit_acct_name').attr('readonly', true);
                    $('#deposit_acct_no').attr('readonly', true);
                    $('#bank_list').attr('disabled', true);
                    $('.deposit_edit').show();
                    $('.deposit_save').hide();
                    $('.deposit_update').hide();
                    $('.deposit_cancel').hide();

                }else{
                    alert('Something went wrong. Pleasy try again later.');
                }
            }
        });

    });
    
    $('#previewProduct').on('click', '.deposit_cancel', function(){
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

    $('#previewProduct').on('change','#deposit_info',function(){
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
            $('#prod_billing_id').val($this.val());
        }
        else{
            $('#deposit_acct_name').val('');
            $('#deposit_acct_name').attr('readonly', false);
            $('#deposit_acct_no').val('');
            $('#deposit_acct_no').attr('readonly', false);
            $('#billing_info_id').val(0);
            $('#prod_billing_id').val(0);
            $('#bank_name').val('');
            var default_option = $('#bank_list').find('option[value = "0"]');
            default_option.prop('selected', true);
            $('#bank_list').attr('disabled', false);
            $('.deposit_save').show();
            $('.deposit_edit').hide();
        }
    });
    
    $('#previewProduct').on('change','#bank_list',function(){
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
    
    $(document).on('change',"#allow_cashondelivery",function () {
        var $this = $(this);
        if($this.is(':checked')){
            $('#allow_cod').prop('checked', true);
        }
        else{
            $('#allow_cod').prop('checked', false);
        }
    });
    
    /*
     *   This event listener is specifically used for the non-modal product preview page
     */
    
    $(document).on('click', '#previewSubmit', function(){
    
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var account_name = $('#deposit_acct_name').val();
            var bank_name = $('#bank_name').val();
            var account_no = $('#deposit_acct_no').val();
            var bank_list = $('#bank_list').val();
            var prod_billing_id = parseInt($('#prod_billing_id').val(),10);

            var cod_only = ((prod_billing_id === 0)&&($('#allow_cashondelivery').is(':checked')))?true:false;
            var valid = true;
            if(!cod_only){
                if($.trim(account_name) === ''){
                    $('#deposit_acct_name').focus();
                    validateRedTextBox('#deposit_acct_name');
                    valid = false;
                }
                if($.trim(account_no) === ''){
                    $('#deposit_acct_no').focus();
                    validateRedTextBox('#deposit_acct_no');
                    valid = false;
                }
                if(parseInt(bank_list,10) === 0){
                    $('#bank_list').focus();
                    validateRedTextBox('#bank_list');
                    valid = false;
                }
                if(!valid){
                    return false;
                }
            }
          
            if((prod_billing_id === 0)&&(!cod_only)){
                jQuery.ajax({
                    type: "POST",
                    url: '/memberpage/billing_info', 
                    data: "bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
                    success: function(response) {
                            var obj = JSON.parse(response);
                            if((parseInt(obj.e,10) == 1) && (obj.d == 'success')){
                                var new_id = parseInt(obj.id,10);
                                $('#prod_billing_id').val(new_id);
                                $('#step4_form').submit();
                            }else if((parseInt(obj.e,10) == 0) && (obj.d == 'duplicate')){
                                alert('You are already using this account number.');
                            }else{
                                alert('Something went wrong please try again later.');
                            }
                        }
                });
            }
            else{
                $('#step4_form').submit();
            }
    });
    
     /*
      *  Back links for non-modal preview item
      */
    
     $('#previewProduct').on('click', '#step1_link', function(){
         $('#edit_step1').submit();
     });
     
     $('#previewProduct').on('click', '#step2_link', function(){
        $('#edit_step2').submit();
     });
     
     $('#previewProduct').on('click', '#step3_link', function(){
        $('#edit_step3').submit();
     });
    
    
    
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