$(document).ready(function(){
    
    var modal = $('#modal').val();
    if(parseInt(modal,10) === 0){
         $('#tabs').tabs();         
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
        var account_name = $('#deposit_acct_name').val();
        var bank_name = $('#bank_name').val();
        var account_no = $('#deposit_acct_no').val();
        var bank_list = $('#bank_list').val();
        var billing_id = $('#billing_info_id').val();
        var selected = $('#deposit_info [value="'+billing_id+'"]');
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
            url: config.base_url + 'memberpage/billing_info_u', 
            data: "bi_id="+billing_id+"&bi_acct_name="+account_name+"&bi_acct_no="+account_no+"&bi_bank="+bank_list+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                if(!response){
                    alert('We are having a problem right now. Refresh the page to try again.');
                }
                else{
                    selected.data('bankid',bank_list);
                    selected.data('acctname',account_name);
                    selected.data('acctno',account_no);
                    selected.data('bankname',bank_name);
                    selected.text('Bank: '+bank_name+' - '+ account_name);
                }
            }
        });
        $('#deposit_acct_name').attr('readonly', true);
        $('#deposit_acct_no').attr('readonly', true);
        $('#bank_list').attr('disabled', true);
        $('.deposit_edit').show();
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
    });
    
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
            url: config.base_url + 'memberpage/billing_info', 
            data: "express=true&bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                if(!response){
                    alert('We are having a problem right now. Refresh the page to try again.');
                }
                else{
                    var new_option = $(document.createElement( "option" ));
                    new_option.data('bankname',bank_name);
                    new_option.data('bankid',bank_list);
                    new_option.data('acctname',account_name);
                    new_option.data('acctno',account_no);
                    new_option.text('Bank: '+bank_name+' - '+ account_name);
                    var new_id = parseInt(response,10);
                    new_option.val(new_id);
                    new_option.insertBefore($('#deposit_info').find(':selected'));
                    $('#deposit_info').find(':selected').prop('selected', false);
                    $('#deposit_info').find('option[value = "'+parseInt(response,10)+'"]').prop('selected', true);
                    $('#prod_billing_id').val(new_id);
                }
            }
        });
        $('#deposit_acct_name').attr('readonly', true);
        $('#deposit_acct_no').attr('readonly', true);
        $('#bank_list').attr('disabled', true);
        $('.deposit_edit').show();
        $('.deposit_save').hide();
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
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
                    url: config.base_url + 'memberpage/billing_info', 
                    data: "express=true&bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
                    success: function(response) {
                            if(!response){
                                alert('We are having a problem right now. Refresh the page to try again.');
                            }
                            else{
                                var new_id = parseInt(response,10);
                                $('#prod_billing_id').val(new_id);
                                $('#step4_form').submit();
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