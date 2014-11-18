$(document).ready(function(){
    /* 
     *   Fix for the stupid behaviour of jpagination with chrome when pressing the back button.
     *   See next two lines of code.
     */
    $('.sch_box').val('');
    $('input.items').each(function(k,v){
        $(this).val($(this).data('value'));
    });
    
    $(function () {
        var $print = $(".print");
        var $print_ul = $(".print-ul");
        $(document).mouseup(function (e) {
            if (!$print.is(e.target) // if the target of the click isn't the container...
                && $print.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $print_ul.hide(1);
            }
        });
        $print.click(function() {
            $print_ul.show();
        });
    });
    
     $(function () {
        var $print_s = $(".print-s");
        var $print_ul_s = $(".print-ul-s");
        $(document).mouseup(function (e) {
            if (!$print_s.is(e.target) // if the target of the click isn't the container...
                && $print_s.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $print_ul_s.hide(1);
            }
        });
        $print_s.click(function() {
            $print_ul_s.show();
        });
    });

    $(document).on('click','#printTransactions', function(){
        var url = $(this).data("url");

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(json) {
                var originalContents = $(document.body).html();
                $(document.body).html(json);        
                window.print();
                location.reload();
            },
            error: function(e) {
                alert("Action failed, please try again");
            }
        });
    });      
    
    $(document).on('click','#exportTransactions', function(){
        var url = $(this).data("url");
        document.location.href = url;
    });      
    
    progress_update('');
    handle_fields('');
    
    $.modal.defaults.persist = true;
    
    $('.address_dropdown, .disabled_country').chosen({width:'200px'});
    
    $('.dashboard_table').on('click','.transac_address_details_show', function(){
        $(this).siblings('div.transac_address_cont').slideToggle();
        $(this).hide();
    });
    
    $('.dashboard_table').on('click','.transac_address_details_hide', function(){
        $(this).parent('div').slideToggle();
        $(this).parent('div').siblings('span.transac_address_details_show').fadeIn();
    });
    
    //disable decimal point
    $('#mobile,#c_mobile').on('keypress',function(e){
        var code = e.keyCode || e.which;
        return (code != 46);
    });
    $('#personal_information').on('keypress', 'input.year', function(e){
        var code = e.keyCode || e.which;
        return (code != 46);
    });
    
    // Trigger Search on 'Enter' key press
    $('.sch_box').on('keydown', function(e){
        var code = e.keyCode || e.which;
        if(code===13){
            $(this).siblings('.sch_btn').trigger('click');
            return false;
        }
    });
    
    /******* rotate sort arrow when click *****/
    $(".arrow_sort").on("click", function () {
        $(this).toggleClass("rotate_arrow");
    });
});

$(document).ready(function(){
    jQuery.validator.addMethod("select_is_set", function(value, element, arg) {
        return this.optional(element) || (arg != value?true:false);
     }, "* This field must be set");
     
     jQuery.validator.addMethod("is_validmobile", function(value, element) {
        return this.optional(element) || /^(08|09)[0-9]{9}/.test(value);
     }, "Must begin with 09 or 08");
    
    jQuery.validator.addMethod("alphanumeric", function(value, element, arg) {
        return this.optional(element) || /^[A-Za-z0-9]+$/.test(value);
    }, "Only alphanumeric characters are allowed");
    
     $.datepicker.setDefaults({dateFormat: 'yy-mm-dd'}, $.extend($.datepicker.regional['']));

     jQuery.validator.addMethod("is_validdate", function(value, element) {
        var comp = value.split( /[-\/]+/);
        if(comp.length > 3){
            return this.optional(element) || false;
        }
        var y = parseInt(comp[0], 10);
        var m = parseInt(comp[1], 10);
        var d = parseInt(comp[2], 10);
        var date = new Date(y,m-1,d);

        if ((date.getFullYear() == y) && ((date.getMonth() + 1) == m) && (date.getDate() == d)) 
             return this.optional(element) || true;
        else 
             return this.optional(element) || false;
     }, "This date is invalid");
     
});

var memconf = {
    csrftoken: $("meta[name='csrf-token']").attr('content'),
    csrfname: $("meta[name='csrf-name']").attr('content'),
    ajaxStat: null,
    itemPerPage: 10,
    active: {
        schVal: '',
        sortVal: 1,
        sortOrder: 1,
        status: 0, //Delete Status
        status2: 0 //Draft Status
    },
    deleted: {
        schVal: '',
        sortVal: 1,
        sortOrder: 1,
        status: 1, //Delete Status
        status2: 0 //Draft Status
    },
    draft:{
        schVal: '',
        sortVal: 1,
        sortOrder: 1,
        status: 0, //Delete Status
        status2: 1 //Draft Status
    },
    buy: {
        status : 0,
        schVal: '',
        sortVal: 0,
        sortOrder: 1,
        status2: ""
    },
    sell: {
        status : 0,
        schVal: '',
        sortVal: 0,
        sortOrder: 1,
        status2: ""
    },
    cbuy: {
        status : 1,
        schVal: '',
        sortVal: 0,
        sortOrder: 1,
        status2: ""
    },
    csell: {
        status : 1,
        schVal: '',
        sortVal: 0,
        sortOrder: 1,
        status2: ""
    }
};

/****************** EDIT USER SLUG  ******************************/
(function($){
    
    $('div.quickheader').on('click', '.edit_userslug', function(){
        $('a[href="#security_settings"]').trigger('click');
        $('#security_settings .edit_userslug').trigger('click');
    });
    
    $('.quickheader_close').on('click',function(){
        $(this).parent('div.quickheader').slideUp();
        alert("You can visit and edit your store from the Settings Tab.");
        
        $.cookie("es_qh", 1, {expires:10});
        
    });
    
    $('#security_settings').on('click', '.edit_userslug', function(){
        var urlDisplay = $(this).parent('div');
        var datafield = urlDisplay.siblings('div.datafield');
        
        datafield.find('p.error').remove();
        
        urlDisplay.hide();
        datafield.show();
    });
    
    $('#security_settings').on('click', '.cancel_userslug', function(){     
        var datafield = $(this).closest('div');
        var urlDisplay = datafield.siblings('div.disp_vendor_url');
        var inputSlug = $(this).siblings('input[name="userslug"]');
        
        inputSlug.val(inputSlug.attr('value'));
        
        datafield.hide();
        urlDisplay.show();
    });
    
    $('#form_userslug').validate({
        rules:{
            userslug:{
                required: true,
                alphanumeric: true,
                minlength: 3,
                maxlength: 25
            }
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
                error.addClass('red');
                error.appendTo(element.parent());
        },  
        submitHandler: function(form){
            var datafield = $(form).closest('div.datafield');
            var urlDisplay = $('div.disp_vendor_url');
            var inputSlug = $(form).children('input[name="userslug"]');
            var slugVal = $.trim(inputSlug.val()).toLowerCase();
            var saveBtn = datafield.find('.save_userslug');
            
            $('#vendor_url_dialog').dialog({
                autoOpen: false,
                title: "Confirm URL change",
                modal: true,
                closeOnEscape: false,
                buttons:{
                    OK: function(){
                        $.post(config.base_url+'memberpage/editUserSlug', $(form).serializeArray(), function(data){
                            try{
                                var obj = jQuery.parseJSON(data);
                            }
                            catch(e){
                                alert('Failed to process your request. Please try again later.');
                                return false;
                            }
                            
                            if(obj.result === 'success'){
                                location.reload(true);
                            }else{
                                inputSlug.attr('disabled',false);
                                saveBtn.attr('disabled',false);
                                saveBtn.val('Save');
                                alert(obj.error);
                                inputSlug.val(inputSlug.attr('value'));
                            }
                            
                        });
                        inputSlug.attr('disabled',true);
                        saveBtn.attr('disabled',true);
                        saveBtn.val('Saving...');
                        $(this).dialog('close');
                    },
                    Cancel: function(){
                        $(this).dialog( "close" );
                    }
                }
            });
            $('#vendor_url_dialog').dialog('open');
            return false;
        }
    });
    
})(window.jQuery)


/*********  AJAX PAGING ************/
function ItemListAjax(ItemDiv,start,pageindex,count){
    var loadingDiv = ItemDiv.children('div.page_load');
    var key = ItemDiv.data('key');
    var contkey = parseInt(ItemDiv.data('controller'));
    var thisdiv = ItemDiv.children('div.paging[data-page="'+pageindex+'"]');
    
    var c = typeof(count) !== 'undefined' ? 'count' : '';
    
    switch(contkey){
        case 1:
            var controller = 'getMoreUserItems';
            break;
        case 2:
            var controller = 'getMoreTransactions';
            break;
    }
    
    memconf.ajaxStat = jQuery.ajax({
        type: "GET",
        url: config.base_url+'memberpage/'+controller,
        data: "s="+memconf[key].status+"&p="+start+"&"+memconf.csrfname+"="+memconf.csrftoken+"&nf="+memconf[key].schVal+
            "&of="+memconf[key].sortVal+"&osf="+memconf[key].sortOrder+"&c="+c+"&k="+key+"&s2="+memconf[key].status2,
        beforeSend: function(){
            if(memconf.ajaxStat != null){
                memconf.ajaxStat.abort();
            }
            loadingDiv.show();
        },
        success: function(data){
            memconf.ajaxStat = null;
            loadingDiv.hide();
            try{
                var obj = jQuery.parseJSON(data);
            }
            catch(e){
                alert('Failed to retrieve user product list.');
                return false;
            }
            
            if( typeof(count) !== 'undefined' ){
                var pagingDivBtn = ItemDiv.children('div.pagination');
                pagingDivBtn.jqPagination('option', 'current_page', 1);
                if(obj.count === 0){
                    thisdiv.html('<h2>Search returned no results.</h2>');
                    pagingDivBtn.jqPagination('option', 'max_page', 1);
                }else{
                    pagingDivBtn.jqPagination('option', 'max_page', Math.ceil(obj.count/memconf.itemPerPage));
                }
                if(memconf.ajaxStat != null){
                    memconf.ajaxStat.abort(); //abort all ajax triggered by updating pagination page
                }
                loadingDiv.hide();
                thisdiv.show();
            }
            
            var htmlData = $.parseHTML(obj.html); // contains TextNodes
            if(htmlData){
                var activeContent = $.map(htmlData, function(val,key){if(val.nodeType == 1){return val;}});
                if(activeContent.length > 0){
                    $.each(activeContent, function(k,v){
                        $(v).find('form').append('<input type="hidden" name="'+memconf.csrfname+'" value="'+memconf.csrftoken+'">');
                        thisdiv.append(v);
                    });
                }
                thisdiv.show();
            }
        }
    });//close ajax
}

$(document).ready(function(){
    $('#active_items .paging:not(:first)').hide();
    $('#deleted_items .paging:not(:first)').hide();
    $('#draft_items .paging:not(:first)').hide();
    
    $('#bought .paging.enable:not(:first)').hide();
    $('#sold .paging.enable:not(:first)').hide();
    $('#complete_buy .paging.enable:not(:first)').hide();
    $('#complete_sell .paging.enable:not(:first)').hide();
    
    defaultPaging($('#pagination_active'));
    defaultPaging($('#pagination_deleted'));
    defaultPaging($('#pagination_draft'));
    
    defaultPaging($('#pagination-bought'));
    defaultPaging($('#pagination-sold'));
    defaultPaging($('#pagination-complete-bought'));
    defaultPaging($('#pagination-complete-sold'));
});

function defaultPaging(pagingDivBtn){
    var ItemDiv = pagingDivBtn.closest('div.dashboard_table');
    $(pagingDivBtn).jqPagination({
        paged: function(page){
            var start = (page-1) * memconf.itemPerPage;
            var pageindex = page-1;
            
            ItemDiv.children('div.paging').hide();
            
            if( ItemDiv.find('div[data-page="'+pageindex+'"] div.content-paging').length == 0 ){
                if( ItemDiv.children('div[data-page="'+pageindex+'"]').length == 0 ){
                    ItemDiv.append("<div class='paging' data-page='"+pageindex+"' style='display:none;'></div>");
                }
                ItemListAjax(ItemDiv,start,pageindex);
                
            }else{
                ItemDiv.children('.paging[data-page="'+pageindex+'"]').show();
            }
            
        }
    });
}

/******************* ACTIVE and DELETED Search Functions ***********************/
$(document).ready(function(){
    $('span.item_sch_btn, span.tx_sch_btn').on('click',function(){
        var ItemDiv = $(this).closest('div.dashboard_table');
        var key = ItemDiv.data('key');
        var pagingDivBtn = ItemDiv.children('div.pagination');
        
        ItemDiv.children('div.paging:not(:first)').remove();
        ItemDiv.find('div.content-paging').remove();
        ItemDiv.children('div.paging:first').show();
        ItemDiv.find('div.paging:first h2').remove();
        if(memconf[key].schVal.length > 0){
            ItemListAjax(ItemDiv,0,0,true); // true = update maxpage of pagination
        }else{
            pagingDivBtn.jqPagination('option','max_page',pagingDivBtn.children('input').data('origmaxpage'));
            ItemListAjax(ItemDiv,0,0);
        }
    });
    
    $('.item_sch_box, .tx_sch_box').on('keyup',function(){
        var ItemDiv = $(this).closest('div.dashboard_table');
        var key = ItemDiv.data('key');
        memconf[key].schVal = $.trim($(this).val());
    });
    
});

/******************* ACTIVE and DELETED Sort Functions ***********************/
$(document).ready(function(){
    $('select.item_sort_select').on('change',function(){
        var ItemDiv = $(this).closest('div.dashboard_table');
        var key = ItemDiv.data('key');
        var pagingDivBtn = ItemDiv.children('div.pagination');
        
        memconf[key].sortVal = $(this).val();
        
        ItemDiv.children('div.paging:not(:first)').remove();
        ItemDiv.find('div.content-paging').remove();
        ItemDiv.children('div.paging:first').show();
        pagingDivBtn.jqPagination('option','current_page', 1);
    });
    
    $('select.tx_sort_select').on('change',function(){
        var ItemDiv = $(this).closest('div.dashboard_table');
        var key = ItemDiv.data('key');
        var pagingDivBtn = ItemDiv.children('div.pagination');
        
        memconf[key].sortVal = $(this).val();
        
        ItemDiv.children('div.paging:not(:first)').remove();
        ItemDiv.find('div.content-paging').remove();
        ItemDiv.children('div.paging:first').show();
        ItemDiv.find('div.paging:first h2').remove();
        pagingDivBtn.jqPagination('option','current_page', 1);
        ItemListAjax(ItemDiv,0,0,true);
    });
    
    $('.item_arrow_sort, .tx_arrow_sort').on('click', function(){
        var ItemDiv = $(this).closest('div.dashboard_table');
        var key = ItemDiv.data('key');
        var pagingDivBtn = ItemDiv.children('div.pagination');
        
        if( ! $(this).hasClass('rotate_arrow') ){
            memconf[key].sortOrder = 1;
        }else{
            memconf[key].sortOrder = 2;
        }
        
        ItemDiv.children('div.paging:not(:first)').remove();
        ItemDiv.find('div.content-paging').remove();
        ItemDiv.children('div.paging:first').show();
        pagingDivBtn.jqPagination('option','current_page', 1);
    });
    
});

/**************************************************************************************************************/    
/**************************************  PERSONAL INFORMATION MAIN    *****************************************/   
/**************************************************************************************************************/        
$(document).ready(function(){

    $(".year").numeric({negative : false});
    $('#mobile').numeric({negative : false});
    
    $('#mobile, #email').on('keydown', function(e){
        return e.which !== 32;
    });
    
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2005'
    });

    $("#personal_profile_main").validate({
        rules: {
            dateofbirth:{
                is_validdate: true
            },
            mobile:{
                digits: true,
                minlength: 11,
                maxlength: 11,
                is_validmobile: true
            },
            email:{
                email: true,
                required: true,
                minlength: 6
            }
        },
        messages:{
            mobile: {
                minlength: 'Please enter at least 11 characters'
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
                error.addClass('red');
                error.appendTo(element.parent());
        },  
        submitHandler: function(form) {
            var thisbtn = $('#ppm_btn');
            
            $('#mobile, #email').attr('disabled', false);
            thisbtn.attr('disabled', true);
            thisbtn.val('Saving...');
            
            $.post(config.base_url+'memberpage/edit_personal',$(form).serializeArray(),
                function(data){
                    thisbtn.attr('disabled', false);
                    thisbtn.val('Save');
                    $(form).find('input').attr('disabled', false);
                    
                    try{
                        var obj = jQuery.parseJSON(data);   
                    }
                    catch(e){
                        alert('An error was encountered while processing your data. Please try again later.');
                        return false;
                    }

                    if(obj.result === 'success'){
                        progress_update($('#personal_profile_main'));
                        
                        $('#mobile, #email').each(function(){

                            if($(this).attr('value') !== $.trim($(this).prop('value')))
                                $(this).siblings('input[name^="is_"]').val(0);
                            
                            $(this).attr('value', htmlDecode($.trim($(this).prop('value'))));
                            $(this).closest('div').removeClass('fired');
                            $(this).closest('div').next('div[id^="cont"] span:first').html('');
                            $(this).closest('div').next('div[id^="cont"]').hide();
                            $(this).parent().find('span.edit_personal_contact').hide();
                            
                            if($.trim($(this).prop('value')) !== ''){
                                if($(this).siblings('input[name^="is_"]').val() == 0){
                                    $(this).siblings('span.verify').hide();
                                    $(this).parent().find('span.toverify, span.toverify span.verify_now').show();
                                }
                                else if ($(this).siblings('input[name^="is_"]').val() == 1){
                                    $(this).siblings('span.verify').hide();
                                    $(this).siblings('span.doneverify').show();
                                }
                                
                                $(this).siblings('span.personal_contact_cont').show();
                                $(this).siblings('span.personal_contact_cont').children('span.cancel_personal_contact').hide();
                                $(this).attr('disabled', true);
                            }
                            else{
                                $(this).siblings('span.personal_contact_cont').hide();
                                $(this).siblings('span.personal_contact_cont').children('span.cancel_personal_contact').hide();
                                $(this).attr('disabled', false);
                            }
                        });
                    }
                    else if(obj.result === 'fail'){ 
                        var errString = "";
                        $.each(obj.error, function(k,v){
                            errString = errString + v + "<br>";
                        });
                        alert(errString);
                    }
                    else if(obj.result === 'error'){
                        if('mobile' in obj['error']){
                            $('#cont_mobilediv span:first').html(obj.error.mobile);
                            $('#cont_mobilediv').show();
                        }
                        else{
                            var mobileField = $('#mobile');
                            if( $.trim(mobileField.prop('value')) !== '' ){
                                mobileField.attr('disabled', true);
                            }
                            else{
                                mobileField.attr('disabled', false);
                            }
                        }
                        if('email' in obj['error']){
                            $('#cont_emaildiv span:first').html(obj.error.email);
                            $('#cont_emaildiv').show();
                        }
                        else{
                            var emailField = $('#email');
                            if( $.trim(emailField.prop('value')) !== '' ){
                                emailField.attr('disabled', true);
                            }
                            else{
                                emailField.attr('disabled', false);
                            }
                        }
                    }
                });
            $(form).find('input').attr('disabled', true);
            return false;
       }
    });  

    $('.avatar_edit').click(function(){
            $('#imgupload').click();
    }).mouseover(function(){
            $('html,body').css('cursor','pointer');
    }).mouseout(function(){
            $('html,body').css('cursor','default');
    });
    
    $("#imgupload").on("change", function(){
        var oldIE;
        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
        
        if ($('html').is('.ie6, .ie7, .ie8, .ie9')){
            oldIE = true;
        }

        if (oldIE || isSafari){
            $('#form_image').submit();
        }
        else{
            imageprev(this);
        }
    });
    
    $('#emaildiv, #mobilediv').on('mouseover', function(){
        if($(this).hasClass('fired') == false)
            $(this).find('.edit_personal_contact').show();
    })
    .on('mouseleave', function(){
        $(this).next('div[id^="cont"]').fadeOut(3000);
        $(this).next('div[id^="cont"] span:first').html('');
        
        if($(this).hasClass('fired') == false)
            $(this).find('.edit_personal_contact').hide();
    });
    
    $('.edit_personal_contact').on('click', function(){
        $(this).parent().siblings('input[type="text"]').attr('disabled', false).focus();
        $(this).siblings('.cancel_personal_contact').show();
        $(this).parent().siblings('span.verify').hide();
        $(this).hide();
        $(this).closest('div').addClass('fired');
    });

    $('.cancel_personal_contact').on('click', function(){
        var inputsibling = $(this).parent().siblings('input[type="text"]');
        //$(this).parent().siblings('input[type="text"]').prop('value', $(this).siblings('input').attr('value')).attr('disabled', true);
        inputsibling.prop('value', inputsibling.attr('value')).attr('disabled', true);
        $(this).siblings('.edit_personal_contact').show();
        $(this).hide();
        $(this).closest('div').removeClass('fired');
        $(this).parent().siblings('span.error').remove();
        
        if($(this).parent().siblings('input[name^="is_"]').val() == 1)
            $(this).parent().siblings('span.doneverify').show();
        else
            $(this).parent().siblings('span.toverify').show();
    });
    
    $('#verifcode').on('click focus focusin keydown', function(){
        $('.verifcode_error').fadeOut();
    });
    
    /**
     *  CSRF TOKEN
     */
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    
    //Mobile verification dialog box
    $('#verifcode_div').dialog({
        autoOpen: false,
        title: "Enter confirmation code",
        modal: true,
        closeOnEscape: false,
        buttons:{
            Submit: function(){
                var $dialog = $(this);
                var val = $('#verifcode').val();
                $.post(config.base_url+'memberpage/verify_mobilecode',{data:val, mobileverify:'true', csrfname : csrftoken}, function(data){
                    if(data == 1){
                        $('#mobilediv').find('span.doneverify').show();
                        $dialog.dialog( 'close' );
                    }
                    else if(data==0){
                        $('.verifcode_error').fadeIn();
                    }
                })
            },
            Cancel: function(){
                var mobilediv = $('#mobilediv');
                
                $('#verifcode').val('');
                $('#mobile').attr('disabled', true);
                mobilediv.find('span.verify_now').show();
                mobilediv.removeClass('fired');
                mobilediv.find('span.personal_contact_cont').show();
                $('#cont_mobilediv').hide();
                mobilediv.find('span.edit_personal_contact').hide();
                $(this).dialog( "close" );
            }
        }
    });
    
    //Verify button
    $('.verify_now').on('click', function(){
        var data = $(this).parent('span.verify').siblings('input[type="text"]').val();
        var field = $(this).parent('span.verify').siblings('input[type="text"]').attr('name');
        
        var $thisspan = $(this);
        var loadingimg = $(this).siblings('img.verify_img');
        var verifyspan = $(this).parent('span.verify');
        var parentdiv = $(this).closest('div');
        var contdiv = parentdiv.next('div[id^="cont_"]');
        var errorspan = contdiv.children('span:first');
        
        $thisspan.hide();
        loadingimg.show();
        parentdiv.addClass('fired');
        verifyspan.siblings('span.personal_contact_cont').hide();
        $('#ppm_btn').attr('disabled', true);
        
        $.post(config.base_url+'memberpage/verify', {field:field, data:data, reverify:'true', csrfname : csrftoken}, function(data){
            loadingimg.hide();
            contdiv.show();
            $('#ppm_btn').attr('disabled', false);
            
            if(data!=0){
                try{
                    var obj = jQuery.parseJSON(data);   
                }
                catch(e){
                    alert('An error was encountered while processing your data. Please try again later.');
                    $thisspan.show();
                    parentdiv.removeClass('fired');
                    verifyspan.siblings('span.personal_contact_cont').show();
                    contdiv.hide();
                    return false;
                }
                
                if(obj==='dataerror'){
                    verifyspan.after('<p>There was a mismatch of data with the server. Reload the page and try again later.</p>');
                    contdiv.hide();
                }
                else{
                    if(field==='mobile'){
                        if(obj === 'success'){
                            contdiv.hide();
                            $('#verifcode_div').dialog('open');
                        }
                        else if(obj === 'error'){
                            $thisspan.show();
                            parentdiv.removeClass('fired');
                            verifyspan.siblings('span.personal_contact_cont').show();
                            errorspan.html('An error was encountered. Please try again later');
                        }
                        else if(obj === 'exceed')
                            errorspan.html('You have exceeded the number of times to verify your mobile. Try again after 30 mins.');
                    }
                    else if(field==='email'){
                        if(obj === 'success'){
                            parentdiv.find('span.doneverify').show();
                            parentdiv.find('span.doneverify span:nth-child(2)').html('An email has been sent. Please check your e-mail.');
                            errorspan.html('');
                            contdiv.hide();

                            $('.cart_no').hide();
                            $('.cart').css('width','28');
                            $('.big_cart').addClass('cart_zero');
                        }
                        else if(obj ==='error'){
                            $thisspan.show();
                            parentdiv.removeClass('fired');
                            verifyspan.siblings('span.personal_contact_cont').show();
                            //$('#cont_emaildiv span:first').html('An error was encountered. Please try again later');
                            errorspan.html('An error was encountered. Please try again later');
                        }
                        else if(obj === 'exceed')
                            //$('#cont_emaildiv span:first').html('You have exceeded the number of times to verify your email. Try again after 30 mins.');
                            errorspan.html('You have exceeded the number of times to verify your email. Try again after 30 mins.');
                    }
                }
            }
            else
                window.location.replace('<?php echo base_url();?>' + 'memberpage');
        });
    });
});


/**************************************************************************************************************/    
/********************************  DROP DOWN PROFILE - GEN FUNCTIONS    ***************************************/   
/**************************************************************************************************************/
$(document).ready(function(){
    /***********    Delete information on click - GENERAL   **************/
    $('.delete_information').on('click', function(){
        var name = $(this).attr('name');
        var parentinfocont = $(this).parent();
        var editprofilebtn = parentinfocont.siblings('div.edit_profile');
        var editfields = parentinfocont.siblings('div.edit_fields');
        var echoedinfo = $(this).siblings('.echoed_info');
        var form = $(this).closest('form');
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        
        
        $.post(config.base_url+"memberpage/deletePersonalInfo", {field : name, csrfname : csrftoken}, function(data){
            if(data == 1){
                editprofilebtn.show();
                parentinfocont.hide();
                
                //Update text boxes pre-filled with server data
                editfields.find('input[type="text"]').each(function(){
                    $(this).attr('value', "");
                    $(this).prop('value', "");
                });
                //Clear echoed info from server
                echoedinfo.html('');
                
                if(name === 'del_school'){                  
                    $('#container_school').html('');
                    var schlevel = editfields.find('select');
                    schlevel.attr('data-status', "");
                    schlevel.val(0);
                }
                else if(name === 'del_work'){
                    $('#container_work').html('');
                }else if(name=== 'del_address'){
                    var addselect = editfields.find('select.address_dropdown');
                    addselect.val(0).attr('data-status', "");
                    addselect.trigger('chosen:updated');
                    clearPersonalAddress();
                }

                progress_update(form);
            }
            else{
                alert('Error deleting data. Please try again later.');
            }
        });
    });
    
    /*******************    ADD Field - General     ********************************/
    $('.edit_profile').on('click', function(){
        $(this).siblings('.edit_fields').fadeIn(300);
        $(this).hide();
    });
    
    /*******************    Mouse Event - General   ********************************/
    $('.work_information, .school_information, .address_information').mouseover(function(){
        $(this).children('.edit_info_btn').addClass('show');
        $(this).children('.delete_information').addClass('show');
    })
    .mouseout(function(){
        $(this).children('.edit_info_btn').removeClass('show');
        $(this).children('.delete_information').removeClass('show');
    });
    
    /*******************    Edit button - General   ********************************/
    $(".edit_info_btn").click(function(){
        $(this).parent('.gen_information').siblings('.edit_fields').fadeIn(300);
        $(this).parent('.gen_information').hide();
      });
    
    /*******************    Cancel button - General ********************************/
    $('.cancel').on('click', function(){
        var editfields = $(this).closest('div.edit_fields');
        var innerfields = editfields.children('div.inner_profile_fields');
        var form = $(this).closest('form.dropdownform');
        var cancelname = $(this).attr('name');
        
        editfields.fadeOut();
        form.validate().resetForm();
        handle_fields(form);
        
        innerfields.find('input[type="text"]').each(function(){
            $(this).prop('value', $(this).attr('value'));
        });
        
        innerfields.find('select:not(.cityselect)').each(function(){
            $(this).val($(this).attr('data-status'));
        });
        
        if(cancelname === 'cancel_school' || cancelname === 'cancel_work'){     
            innerfields.find('div.dynamic_dd').find('input[type="text"]').each(function(){
                if(!($.trim($(this).attr('value')))){
                    $(this).closest('div.dynamic_dd').remove();
                }
            });
            
            var len = parseInt(innerfields.find('div.dynamic_dd').length);
            
            if(len < 3){
                innerfields.find('.add_new_dynamicdd').show();
                console.log(innerfields.find('.add_new_dynamicdd'));
                console.log('lessthan3');
            }else{
                innerfields.find('.add_new_dynamicdd').hide();
                console.log(innerfields.find('.add_new_dynamicdd'));
                console.log('greaterthan3');
            };
            
        }else if( cancelname === 'cancel_address' ){
            var cityselect = innerfields.find('select.cityselect');
            var stateregionselect = innerfields.find('select.stateregionselect');
            
            cityFilter(stateregionselect, cityselect);
            cityselect.val(cityselect.attr('data-status'));
            cityselect.trigger('chosen:updated');
            stateregionselect.trigger('chosen:updated');
            
            var maplat = $('#map_lat').val();
            var maplng = $('#map_lng').val();
            
            if(maplat == 0 && maplng == 0){
                clearPersonalAddress();
            }
            else{
                $('#personal_mapcanvas').siblings('.map_nav').children('.close').trigger('click');
            }
        }
    });
    
    function clearPersonalAddress(){
        var mapcanvas = $('#personal_mapcanvas');
        $('#map_lat').val(0);
        $('#map_lng').val(0);
        $('#temp_lat').val(0);
        $('#temp_lng').val(0);
        mapcanvas.removeAttr('style');
        mapcanvas.removeClass('map_canvas');
        mapcanvas.children().remove();
        mapcanvas.siblings('.map_nav').children('.close').trigger('click');
    }
    
});

function displaySetDefaultAddress(o)
{
    if( o.cityID == o.c_cityID && o.stateregionID == o.c_stateregionID && o.address == o.c_address ){
        $('#c_defaddress_div').css('display','none');
    }else{
        $('#c_defaddress_div').css('display','block');
    }
}

/**************************************************************************************************************/    
/**************************************  PERSONAL INFORMATION ADDRESS    ***************************************/   
/**************************************************************************************************************/
$(document).ready(function(){

    $('.stateregionselect').on('change', function(){
        $(this).valid();
        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });
    
    $('.cityselect').on('change', function(){
        $(this).valid();
    });
    
    //************  PERSONAL PROFILE ADDRESS VALIDATION ***************//
    $("#personal_profile_address").validate({
        rules: {
            stateregion:{
                required: true,
                select_is_set: '0'
            },
            city:{
                required: true,
                select_is_set: '0'
            },
            address:{
                required: true
            }
        },
        messages: {
            stateregion:{
                required: '* State/Region is required'
            },
            city:{
                required: '* City is required'
            },
            address:{
                required: '* Please enter your full address'
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element){
                error.addClass('red');
                error.appendTo(element.parent());
        },
        ignore: ":hidden:not(select)",
        submitHandler: function(form){

            $.post(config.base_url+'memberpage/edit_address',$('#personal_profile_address').serializeArray(),
                function(data){
                    $(form).find('input[type="submit"]').attr('disabled', false);

                    try{
                        var obj = jQuery.parseJSON(data);
                    }
                    catch(e){
                        alert('An error was encountered while submitting your form. Please try again later.');
                        //window.location.reload(true);
                        return false;
                    }
                    
                    if(obj['result'] === 'fail' || obj['result'] === 'error'){
                        alert(obj['errmsg']);
                        window.location.reload(true);
                        return false;
                    }else if(obj['result'] === 'success'){                  
                        //overwrite div to display new address data
                        $('.address_information .add_info').html(function(){
                            var string = obj['stateregion'] + ", " + obj['city'] + "<br>" + htmlDecode(obj['address']);
                            return string;
                        });
                        
                        //update input text attribute value to new values
                        $('.address_fields input[type="text"]').each(function(){
                            $(this).attr('value',htmlDecode(obj[$(this).attr('name')]));
                        });
                        
                        //update address drop down select fields
                        $('.address_fields select.address_dropdown').each(function(){
                            $(this).attr('data-status', htmlDecode(obj[$(this).attr('name') + 'ID']));
                        });
                        
                        //fix display of address depending on contents
                        handle_fields($('#personal_profile_address'));
                        progress_update($('#personal_profile_address'));
                        $('#personal_profile_address .edit_fields').fadeOut();
                        
                        // Copy dragged marker coordinates to scanned input for saving - google maps
                        $('#map_lat').val(obj['lat']);
                        $('#map_lng').val(obj['lng']);
                        
                        // Update original checker fields
                        $('.address_fields input[name="stateregion_orig"]').val(obj['stateregionID']);
                        $('.address_fields input[name="city_orig"]').val(obj['cityID']);
                        $('.address_fields input[name="address_orig"]').val(htmlDecode(obj['address']));
                        
                        // Map notification status
                        if(obj['lat'] == 0 && obj['lng'] == 0){
                            $('#personal_profile_address span.maploc_stat').html('Location not marked');
                    
                        }else{
                            $('#personal_profile_address span.maploc_stat').html('Location marked');

                        }
                        
                        //manage display of default address option
                        displaySetDefaultAddress(obj);
                    }
                });
            $(form).find('input[type="submit"]').attr('disabled', true);
            return false;                   
        }
    });
});


/**************************************************************************************************************/    
/**************************************  PERSONAL INFORMATION SCHOOL    ***************************************/   
/**************************************************************************************************************/  
$(document).ready(function(){
    function GetHtml2() {
        var len = $('.add_another_school').length+2;
        var $html1 = $('#add_school').clone();
        $html1.find('[name=schoolname1]').attr('value','');
        $html1.find('[name=schoolyear1]').attr('value','');
        $html1.find('[name=schoollevel1]').find(':selected').attr('selected', false);
        $html1.find('[name=schoollevel1]').find('[value=0]').attr('selected', true);
        $html1.find('[name=schoolcount1]').attr('value',len);
        $html1.find('[name=schoolname1]').attr('name',"schoolname" + len);
        $html1.find('[name=schoolyear1]').attr('name',"schoolyear" + len);
        $html1.find('[name=schoollevel1]').attr('name',"schoollevel" + len);
        $html1.find('[name=schoolcount1]').attr('name',"schoolcount" + len);    
        $html1.find('.error.red').remove();
        return $html1.html();
    }
    
    //ADD 'ANOTHER' - BUTTON CLICK
    $('#addRow_school').click(function () {   
        var cont = $('<div/>', {
            'class': 'add_another_school dynamic_dd',
            html: GetHtml2()
        }).hide().appendTo('#container_school').fadeIn('slow');
        cont.find('*[name^="schoolyear"]').rules('add', {required: true, digits: true,  range: [1901, 2155],
                                           messages: {required: '* The year field is required', range: '* This is an invalid year' }});
        cont.find('*[name^="schoolname"]').rules('add', {required: true, 
                                           messages: {required: '* The school field is required'}});
        cont.find('*[name^="schoollevel"]').rules('add', {select_is_set: 0});          
        $(".year").numeric({negative : false});
        var len = $("#container_school").find(".add_another_school").length;
        if(len == 3){
            $('#addRow_school').hide();
        }
    });
    

    //***************** PERSONAL PROFILE SCHOOL SUBMIT  ****************************//
    $("#personal_profile_school").validate({
        rules: {
            schoolname1:{
                required: true
            },
            schoolyear1:{
                required: true,
                digits: true,
                range: [1901, 2155],
            },
            schoollevel1:{
                select_is_set: '0'
            }
        },
        messages: {
            schoolname1:{
                required: '* The school field is required'
            },
            schoolyear1:{
                required: '* The year field is required',
                range: '* This is an invalid year',
            }
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
                error.addClass('red');
                error.appendTo(element.parent());
        },
        submitHandler: function(form) {

           $.post(config.base_url+'memberpage/edit_school',$('#personal_profile_school').serializeArray(),
                function(data){
                    $(form).find('input[type="submit"]').attr('disabled', false);
                    
                    try{
                        var obj = jQuery.parseJSON(data);
                    }
                    catch(e){
                        alert('An error was encountered while submitting your form. Please try again later.');
                        window.location.reload(true);
                        return false;
                    }
                    
                    if(obj['result'] === 'fail' || obj['result'] === 'error'){
                        alert(obj['errmsg']);
                        window.location.reload(true);
                        return false;
                    }else if(obj['result'] === 'success'){
                        var string = "";
                    
                        //UPDATE DIV SCHOOL INFORMATION
                        $.each(obj.school, function(index, value){
                            var schoollevel_string;
                            switch(value.schoollevel){
                                case '1': schoollevel_string='Undergraduate degree'; break;
                                case '2': schoollevel_string='Masteral degree'; break;
                                case '3': schoollevel_string='Doctorate degree'; break;
                                case '4': schoollevel_string='High School'; break;
                                case '5': schoollevel_string='Elementary'; break;
                                default:   schoollevel_string='Undergraduate degree';
                            }
                            $('.school_information .school_info').html(function(){
                                string += "<p/>"+htmlDecode(value.schoolname)+" "+htmlDecode(value.schoolyear)+" "+htmlDecode(schoollevel_string);
                                return string;
                            });
                        });
                        
                        // UPDATE TEXT BOX AND SELECT PROPERTIES
                        var i = 1;
                        $.each(obj.school, function(){
                            $.each(this, function(k, v){    
                                if(k !='schoollevel'){
                                    $(".school_fields input[name='" + k + i +"']").prop('value',htmlDecode(v));
                                    $(".school_fields input[name='" + k + i +"']").attr('value',htmlDecode(v));
                                }
                                else{
                                    $(".school_fields select[name='"+k+i+"']").val(v);
                                    $(".school_fields select[name='"+k+i+"']").attr('data-status', htmlDecode(v));
                                }
                            });
                            i++;
                        });
                        
                        $(form).find('.edit_fields').fadeOut();
                        handle_fields($(form));
                        progress_update($(form));
                    }
                });
            $(form).find('input[type="submit"]').attr('disabled', true);
            return false;
       }
    });  
});


/**************************************************************************************************************/    
/**************************************  PERSONAL INFORMATION WORK    *****************************************/   
/**************************************************************************************************************/
    
$(document).ready(function(){   
   //clone input fields for work
    function GetHtml() {
        var len = $('.add_another_work').length+2;
        var $html = $('#add_work').clone();
        $html.find('[name=companyname1]').attr('value', "");
        $html.find('[name=companyname1]')[0].name = "companyname" + len;
        $html.find('[name=designation1]').attr('value', "");
        $html.find('[name=designation1]')[0].name = "designation" + len;
        $html.find('[name=year1]').attr('value', "");
        $html.find('[name=year1]')[0].name = "year" + len;
        $html.find('[name=workcount1]')[0].value = len;
        $html.find('[name=workcount1]')[0].name = "workcount" + len;
        $html.find('.error.red').remove();
        return $html.html();
    }

    //add additional work 
    $('#addRow_work').click(function (){        
        var cont = $('<div/>', {
            'class': 'add_another_work dynamic_dd',
            html: GetHtml()
        }).hide().appendTo('#container_work').fadeIn('slow');
        
        cont.find("*[name^='companyname']").rules('add',{required: true, messages:{required: '* Company name is required'}});
        cont.find("*[name^='designation']").rules('add',{required: true, messages:{required: '* Designation is required'}});
        cont.find("*[name^='year']").rules('add',{required: true, digits: true, range: [1901, 2155], messages:{required: '* Year of service is required', range: '* This is an invalid year'}});
        cont.find("*[name^='year']").numeric({negative : false});   
        
        var len = $("#container_work").find(".add_another_work").length;
        if(len == 3){
            $('#addRow_work').hide();
        }
    });

    //***************   PERSONAL PROFILE WORK   ************************//
    $("#personal_profile_work input[name='year1']").numeric({negative : false});

    $('#personal_profile_work').validate({
        rules: {
            companyname1:{
                required: true
            },
            designation1:{
                required: true
            },
            year1:{
                required:true,
                digits: true,
                range: [1901, 2155]
            }
        },
        messages:{
            companyname1:{
                required: '* Company name is required'
            },
            designation1:{
                required: '* Designation is required'
            },
            year1:{
                required: '* Year of service is required',
                range: '* This is an invalid year'
            }
        },
        errorElement: 'p',
        errorPlacement: function(error, element) {
                error.addClass('red');
                error.appendTo(element.parent());
                //console.log(element.parent());
        },
        submitHandler: function(form){

            $.post(config.base_url+'memberpage/edit_work', $('#personal_profile_work').serializeArray(),
                function(data){

                    $(form).find('input[type="submit"]').attr('disabled', false);
                    
                    try{
                        var obj = jQuery.parseJSON(data);
                    }
                    catch(e){
                        alert('An error was encountered while submitting your form. Please try again later.');
                        window.location.reload(true);
                        return false;
                    }
                    
                    if(obj['result'] === 'fail' || obj['result'] === 'error'){
                        alert(obj['errmsg']);
                        window.location.reload(true);
                        return false;
                    }else if(obj['result'] === 'success'){  
                        var string = "";
                        
                        //UPDATE DIV CONTENTS
                        $('#personal_profile_work .work_info').html(function(){
                            for(var x=0; x<obj.work.length; x++){
                                string += "<p>" + htmlDecode(obj.work[x].companyname) + " " + htmlDecode(obj.work[x].designation) + " " + htmlDecode(obj.work[x].year) + "</p>";
                            };
                            return string;
                        });
                        
                        //UPDATE TEXTBOX PROPERTIES
                        var i = 1;
                        $.each(obj.work, function(){
                            $.each(this, function(k, v){    
                                $(".work_fields input[name='" + k + i +"']").prop('value',htmlDecode(v));
                                $(".work_fields input[name='" + k + i +"']").attr('value',htmlDecode(v));
                            });
                            i++;
                        });
                        
                        $('#personal_profile_work .edit_fields').fadeOut();
                        handle_fields($('#personal_profile_work'));
                        progress_update($('#personal_profile_work'));
                        
                    }
                });
            $(form).find('input[type="submit"]').attr('disabled', true);
            return false;
        }
    });

});

/**************************************************************************************************************/    
/****************************  DELIVERY ADDRESS AND CONSIGNEE DETAILS    **************************************/   
/**************************************************************************************************************/

$(document).ready(function(){
    $("#c_mobile").numeric({negative : false});
    $("#c_telephone").numeric({negative : false});
    
    $("#c_deliver_address").validate({
        rules: {
            consignee: {
                required: true
            },
            c_mobile: {
                digits: true,
                minlength: 11,
                maxlength: 11,
                is_validmobile: true,
                required: true
            },
            c_telephone: {
                digits: true
            },
            c_stateregion:{
                required: true,
                select_is_set: '0'
            },
            c_city:{
                required: true,
                select_is_set: '0'
            },
            c_address:{
                required: true
            }
        },
        messages: {
            consignee: {
                required: '* Consignee name required'
            },
            c_mobile: {
                required: '* Mobile Number required'
            },
            c_stateregion:{
                required: '* State/Region is required'
            },
            c_city:{
                required: '* City is required'
            },
            c_address:{
                required: '* Please enter your full address'
            }
        },
        ignore: ":hidden:not(select)",
        errorElement: "span",
        errorPlacement: function(error, element){
                error.addClass('red');
                error.appendTo(element.parent());
        },
        submitHandler: function(form) {
           $('#load_cdeliver_address').css('display', 'inline');
           var formdata = $(form).serializeArray();
           $.post(config.base_url+'memberpage/edit_consignee_address',formdata,
                function(data){
                    $('#c_deliver_address_btn').attr('disabled', false);
                    $('#load_cdeliver_address').css('display', 'none');
                    $('#c_def_address').attr('checked', false);
                    
                    try{
                        var obj = jQuery.parseJSON(data);
                    }
                    catch(e){
                        alert('An error was encountered while submitting your form. Please try again later.');
                        //window.location.reload(true);
                        return false;
                    }
                    
                    if(obj.result === 'success'){
                    
                        //UPDATE INPUTfield ATTRIBUTE and PROPERTY VALUES in Delivery Address Page
                        $('#c_deliver_address .inner_profile_fields input[type="text"]').each(function(){
                            $(this).attr('value', htmlDecode(obj[$(this).attr('name')]));
                            $(this).prop('value', htmlDecode(obj[$(this).attr('name')]));                       
                        });
                        
                        // UPDATE SELECT FIELDS
                        $('#c_deliver_address .inner_profile_fields select.address_dropdown').each(function(){
                            $(this).attr('data-status', htmlDecode(obj[$(this).attr('name') + 'ID']));
                            $(this).trigger('chosen:updated');
                        });
                        
                        // Update map coordinates and status
                        $('#map_clat').val(obj['c_lat']);
                        $('#map_clng').val(obj['c_lng']);
                        
                        // Map notification status
                        if(obj['c_lat'] == 0 && obj['c_lng'] == 0){
                            $('#c_deliver_address span.maploc_stat').html('Location not marked');
                            
                        }else{
                            $('#c_deliver_address span.maploc_stat').html('Location marked');

                        }
                        
                        // Update orig checker fields
                        $('.delivery_address_content input[name="stateregion_orig"]').val(obj['stateregionID']);
                        $('.delivery_address_content input[name="city_orig"]').val(obj['cityID']);
                        $('.delivery_address_content input[name="address_orig"]').val(htmlDecode(obj['address']));
                        
                        //IF SET AS DEFAULT ADDRESS
                        if(obj['default_add'] == "on"){
                            //UPDATE INTPUTfield ATTRIBUTE and PROPERTY VALUES in Personal Information Address
                            $('.address_fields input[type="text"]').each(function(){
                                    $(this).prop('value', htmlDecode(obj["c_" + $(this).attr('name')]));
                                    $(this).attr('value', htmlDecode(obj["c_" + $(this).attr('name')]));
                            });
                            
                            //Update Select Fields
                            $('.address_fields select.address_dropdown').each(function(){
                                $(this).attr('data-status', htmlDecode(obj[$(this).attr('name') + 'ID']));
                                $(this).val(htmlDecode(obj[$(this).attr('name') + 'ID']));
                                $(this).trigger('chosen:updated');
                            });
                            
                            // Update orig checker fields
                            $('.address_fields input[name="stateregion_orig"]').val(obj['stateregionID']);
                            $('.address_fields input[name="city_orig"]').val(obj['cityID']);
                            $('.address_fields input[name="address_orig"]').val(htmlDecode(obj['address']));
                            
                            //OVERWRITE DIV in Personal Information Address
                            $('.address_information .add_info').html(function(){
                                var string = obj['c_stateregion'] + ", " + obj['c_city'] + "<br>" + htmlDecode(obj['c_address']);
                                return string;
                            });
                            
                            //Update Map Coordinates
                            $('.inner_profile_fields').find('div.view_map_btn input[type="hidden"][name$="lat"]').val(obj['c_lat']);
                            $('.inner_profile_fields').find('div.view_map_btn input[type="hidden"][name$="lng"]').val(obj['c_lng']);
                            
                            //Map notification status
                            if(obj['c_lat'] == 0 && obj['c_lng'] == 0){
                                $('#personal_profile_address span.maploc_stat').html('Location not marked');
                               
                            }else{
                                $('#personal_profile_address span.maploc_stat').html('Location marked');
                            }
                            
                            $('#personal_profile_address .address_information').show();
                            $('#personal_profile_address .edit_profile').hide();
                            progress_update($('#personal_profile_address'));
                        }
                        
                        //manage display of default address option
                        displaySetDefaultAddress(obj);
                        
                        progress_update($('#c_deliver_address'));
                        $('.uptd').show().delay(1600).fadeOut(600);;
                    
                    }else{
                        alert(obj.errmsg);
                    }
                    
                });
          $('#c_deliver_address_btn').attr('disabled', true);
          return false;
       }
    });
});

/***********************************************************************************/   
/****************************  TRANSACTIONS    **************************************/   
/***********************************************************************************/
$(document).ready(function(){

    jQuery.validator.addMethod("notEqual", function(value, element, param) {
      return this.optional(element) || value != param;
    }, "Please specify a different (non-default) value");
    
    $(document).on('mouseover','.feedb-star', function(){
        $(this).siblings('.raty-error').html('');
    });
    
    $('.dashboard_table').on('click', '.transac-feedback-btn', function(){
        var divcont = $(this).siblings('.transac-feedback-container');
        var form = divcont.children('form.transac-feedback-form');
        var thisbtn = $(this);
        
        var starset = form.children('div.feedb-star');
        var textarea = form.children('textarea[name="feedback-field"]');
        var econt = form.children('.raty-error');
    
        divcont.modal({
            onShow: function(){
                $('.rating1').raty('destroy').raty({scoreName: 'rating1'});
                $('.rating2').raty('destroy').raty({scoreName: 'rating2'});
                $('.rating3').raty('destroy').raty({scoreName: 'rating3'});
                
                this.setPosition();
                var submitbtn = form.children('.feedback-submit');
                submitbtn.off('click').on('click', function(event){
                    var rating1 = $(this).siblings('div.rating1').children('input[name="rating1"]').val();
                    var rating2 = $(this).siblings('div.rating2').children('input[name="rating2"]').val();
                    var rating3 = $(this).siblings('div.rating3').children('input[name="rating3"]').val();
                    var econt = $(this).siblings('.raty-error');
                    
                    if($.trim(textarea.val()).length < 1)
                        textarea.effect('pulsate',{times:3},800);
                    else if(rating1 === '' || rating2 === '' || rating3 ==='')
                        econt.html('Please rate this user!');
                    else{
                        $.post(config.base_url+'memberpage/addFeedback',form.serialize(),function(data){
                            submitbtn.attr('disabled',false);
                            submitbtn.val('Submit');
                            if(data == 1){
                                divcont.parent('div.feedback_container').html('<p>Your feedback has been submitted.</p>');
                            }
                            else{
                                form.closest('div.feedback_container').html('<p class="error red">An error was encountered. Try again later.</p>');
                            }
                        });
                        submitbtn.attr('disabled',true);
                        submitbtn.val('Sending...');
                        $.modal.close();
                        return false;
                    }
                });
            },
            onClose: function(){
                textarea.val('');
                econt.html('');
                $.modal.close();
            }
        });
    });

    $('#tx_dialog input[type="password"]').on('keypress', function(){
        $(this).siblings('span.error').text('');
    });
    
    
    $('.dashboard_table').on('click', '.transac_response_btn', function(){
        var txResponseBtn = $(this);
        var txStatus = $(this).closest('div.tx_btns').siblings('div.tx_cont').find('.tx_cont_col3 .trans_alert');
        // tx object located in view. contains username and password( requires once every memberpage load )
        var txDialog = $('#tx_dialog');
        var passCont = $('#tx_dialog_pass_cont');
        var hasPass = 'true' == $('#password-is-cached').val();
        var loadingimg = passCont.find('img.loading_img');
         
        if( hasPass){
            passCont.hide();
            var loadingimg = $('#tx_dialog_loadingimg img');
        }
        
        txDialog.children('p.msg').hide();
        if(txResponseBtn.hasClass('tx_forward')){
            txDialog.children('p.forward').show();
        }else if(txResponseBtn.hasClass('tx_return')){
            txDialog.children('p.return').show();
        }else if(txResponseBtn.hasClass('tx_cod')){
            txDialog.children('p.cod').show();
        }
        
        txDialog.dialog({
            modal:true,
            resizable:false,
            draggable:false,
            width:500,
            height:230,
            buttons:{
                OK:function(){
                    var thisdialog = $(this);
                    var form = txResponseBtn.closest('form.transac_response');
                    var data = form.serializeArray();
                    
                    if( !hasPass ){
                        var password = $('#tx_password').val();
                        if(password === ''){
                            $('#tx_password').effect('pulsate',{times:3},800);
                            return false;
                        }
                        else{
                            var username = $('#tx-username').val();
                            data.push({name:'password', value: password},{name:'username', value: username});
                        }
                    }

                    var parentdiv = txResponseBtn.closest('div');
                    txResponseBtn.attr('disabled', true);
                    $('button.ui-button').attr('disabled', true);
                    loadingimg.show();
                    
                    $.post(config.base_url+"memberpage/transactionResponse", data, function(data){
                        loadingimg.hide();
                        try{
                            var serverResponse = jQuery.parseJSON(data);
                        }
                        catch(e){
                            alert('An error was encountered while processing your data. Please try again later.');
                            //window.location.reload(true);
                            return false;
                        }
                        
                        //if invalid password
                        if(serverResponse.result === 'invalid' && !hasPass){
                            var errspan = passCont.children('span');
                            errspan.text(serverResponse.error);
                            txResponseBtn.attr('disabled', false);
                        }else{

                            $('#password-is-cached').val('true');

                            if(serverResponse.result === 'success'){
                                if(txResponseBtn.hasClass('tx_forward')){
                                    txStatus.replaceWith('<span class="trans_alert trans_green">Item Received</span>');
                                }else if(txResponseBtn.hasClass('tx_return')){
                                    txStatus.replaceWith('<span class="trans_alert trans_red">Order Canceled</span>');
                                }else if(txResponseBtn.hasClass('tx_cod')){
                                    txStatus.replaceWith('<span class="trans_alert trans_green">Completed</span>');
                                }
                                
                                txResponseBtn.closest('div.tx_btns').find('input[type="button"]').hide();
                                
                            }else if(serverResponse.result === 'fail'){
                                txResponseBtn.replaceWith('<span class="trans_alert trans_red">Failed to update status.</span>');
                            }
                            if(serverResponse.error.length > 0){
                                alert(serverResponse.error);
                            }
                            thisdialog.dialog('close');
                        }
                        $('button.ui-button').attr('disabled', false);
                    });
                },
                Cancel:function(){
                    $(this).dialog('close');
                }
            },
            open: function(event,ui){
                if( !hasPass ){
                    passCont.children('input[type="password"]').val('');
                    passCont.children('span').text('');
                }
            }
        });
        return false;
    });
    
    
    /********** DRAGONPAY HANDLER *****************/
    $('.dashboard_table').on('click','.dragonpay_update_btn', function(){
        var form = $(this).closest('form');
        var thisbtn = $(this);
        var origval = $(this).val();
        
        $.post(config.base_url+'memberpage/transactionResponse', $(form).serializeArray(), function(data){
            try{
                var obj = jQuery.parseJSON(data);
            }
            catch(e){
                alert('An error was encountered while processing your data. Please try again later.');
                return false;
            }
            
            if(obj.result === 'success'){
                alert('Payment confirmed.');
                window.location.reload(true);
            }else if(obj.result === 'fail'){
                alert(obj.error, 'Should you require further assistance, go to our Contact Us page to get in touch with us.');
                thisbtn.attr('disabled',false);
            }
            thisbtn.val(origval);
        });
        thisbtn.val('Checking...');
        thisbtn.attr('disabled',true);
        return false;
    });
    
    /****** Submit / View shipping Comments ******/
    $('.dashboard_table').on('click', '.shipping_comment', function(){
        var divcont = $(this).parent().siblings('div.shipping_comment_cont');
        var thisbtn = $(this);
        //var txStatus = $(this).parent().siblings('span.tx_cont_col3').children('span.trans_alert');
        var txStatus = $(this).closest('div.tx_btns').siblings('div.tx_cont').find('span.trans_alert');
        
        var form = divcont.find('form.shipping_details');
        var submitbtn = $(form).children('input.shipping_comment_submit');
        var input = $(form).children('input[type="text"]');
        var textarea = $(form).children('textarea');
        var editbtn = $(form).children('.tx_modal_edit');
        var cancelbtn = $(form).children('.tx_modal_cancel');
        
        divcont.modal({
            escClose: false,
            onShow: function(){
                if( thisbtn.hasClass('isform') ){
                    $('input.modal_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: '2013:2050',
                        dateFormat:"yy-M-dd"
                    }).on('keypress',function(){
                        return false;
                    });
                
                    form.validate({
                        rules:{
                            courier:{
                                required: true
                            },
                            /*tracking_num:{
                                required: true
                            },*/
                            delivery_date:{
                                required: true
                                //is_validdate:true
                            },
                            /*expected_date:{
                                is_validdate:true
                            }*/
                        },
                        errorElement: "span",
                        errorPlacement: function(error, element) {
                            error.addClass('red');
                            error.insertAfter(element);
                        },
                        submitHandler: function(form){
                            
                            
                            input.attr('disabled',false);
                            textarea.attr('disabled', false);
                            
                            $.post(config.base_url+'memberpage/addShippingComment', $(form).serializeArray(), function(data){
                                submitbtn.attr('disabled', false);
                                submitbtn.val('Save');
                                
                                try{
                                    var obj = jQuery.parseJSON(data);
                                }
                                catch(e){
                                    alert('An error was encountered while processing your data. Please try again later.');
                                    //window.location.reload(true);
                                    return false;
                                }
                                
                                if(obj.result === 'success'){
                                    
                                    $.each(input, function(k,v){
                                        $(v).attr('value', $(v).prop('value'));
                                        $(v).attr('disabled', true);
                                    });
                                    textarea.attr('data-value', htmlDecode(textarea.val()));
                                    textarea.attr('disabled', true);
                                    
                                    editbtn.show();
                                    cancelbtn.hide();
                                    
                                    if(thisbtn.hasClass('isform')){
                                        txStatus.replaceWith('<span class="trans_alert trans_orange">Item shipped</span>');
                                    }
                                    
                                    $.modal.close();
                                }else{
                                    alert(obj.error);
                                }
                            });
                            submitbtn.attr('disabled', true);
                            submitbtn.val('Saving...');
                        }
                    });
                }
                this.setPosition();
                input.each(function(){
                    if( $.trim($(this).attr('value')).length>0 ){
                        cancelbtn.trigger('click');
                        return false;
                    }
                });
            },
            onClose: function(){
                $('input.modal_date').datepicker('destroy').removeClass('hasDatepicker').removeAttr('id');
                $.modal.close();
            }
        });
    });
    
    //$('input.bankdeposit_amount').numeric({negative:false});
    $(document).on('blur','input.price',function(){
        var price = $.trim($(this).val());
        var newPrice = price.replace(new RegExp(",", "g"), '');
        newPrice = parseFloat(newPrice).toFixed(2);
        if( $.isNumeric(newPrice) ){
            $(this).val( ReplaceNumberWithCommas(newPrice) );
        }else{
            $(this).val('');
        }
    });
    
    /***************    BANK DEPOSIT HANDLERS   *********************/
    $('.dashboard_table').on('click', '.payment_details_btn', function(){
        var thisdiv = $(this).siblings('div.payment_details_cont');
        var thisform = thisdiv.children('form.payment_bankdeposit');
        var submitbtn = thisform.children('input[type="submit"]');
        
        var input = thisform.children('input[type="text"]');
        var textarea = thisform.children('textarea');
        var cancelbtn = thisform.children('.tx_modal_cancel');
        var editbtn = thisform.children('.tx_modal_edit');
        
        var txStatus = $(this).closest('div.transac_title').siblings('div.transac_prod_wrapper').find('.tx_cont .tx_cont_col3 span.trans_alert');
        
        thisdiv.modal({
            escClose: false,
            onShow: function(){
                $('input.modal_date').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '2013:2050',
                    dateFormat:"yy-M-dd"
                }).on('keypress',function(){
                    return false;
                });
            
                this.setPosition();
                thisform.validate({
                    rules: {
                        bank:{
                            required: true
                        },
                        ref_num:{
                            required: true
                        },
                        amount:{
                            required: true
                        },
                        date:{
                            required: true
                            //is_validdate: true
                        }
                    },
                    errorElement: "span",
                    errorPlacement: function(error, element) {
                        error.addClass('red');
                        error.insertAfter(element);
                    },  
                    submitHandler: function(form) {
                        submitbtn.val('Sending...');
                        input.attr('disabled',false);
                        textarea.attr('disabled',false);
                        $.post(config.base_url+'memberpage/transactionResponse', $(form).serializeArray(), function(data){
                            submitbtn.val('Submit');
                            submitbtn.attr('disabled',false);
                            try{
                                var obj = jQuery.parseJSON(data);
                            }
                            catch(e){
                                alert('An error was encountered while processing your data. Please try again later.');
                                return false;
                            }
                            if(obj.result === 'success'){
                                $.each(input, function(k,v){
                                    $(v).attr('value', $(v).prop('value'));
                                    $(v).attr('disabled', true);
                                });
                                textarea.attr('data-value', htmlDecode(textarea.val()));
                                textarea.attr('disabled', true);
                                
                                editbtn.show();
                                cancelbtn.hide();
                                
                                txStatus.replaceWith('<span class="trans_alert trans_orange">PROCESSING DEPOSIT DETAILS</span>');
                                
                                $.modal.close();
                            }else{
                                alert(obj.error);
                            }
                        });
                        submitbtn.attr('disabled', true);
                        return false;
                    }
                });
                thisform.validate().resetForm();
                input.each(function(){
                    if( $.trim($(this).attr('value')).length>0 ){
                        cancelbtn.trigger('click');
                        return false;
                    }
                });
            },
            onClose: function(){
                $.modal.close();
            }
        });
    });
    
    $('.dashboard_table').on('click', '.reject_item', function(){
        var form = $(this).closest('form');
        var thisbtn = $(this);
        var thismethod = $(this).siblings('input[name="method"]');
        var status = $(this).closest('div.tx_btns').siblings('div.tx_cont').find('.tx_cont_col3 .trans_alert');
        $.post(config.base_url+'memberpage/rejectItem', $(form).serializeArray(), function(data){
            try{
                var obj = jQuery.parseJSON(data);
            }
            catch(e){
                alert('An error was encountered while processing your data. Please try again later.');
                return false;
            }
            thisbtn.attr('disabled', false);
            
            if(obj.result === 'success'){
                if ( thisbtn.hasClass('reject') ){
                    thisbtn.removeClass('reject').addClass('unreject').val('Unreject Item');
                    thismethod.val('unreject');
                    status.replaceWith('<span class="trans_alert trans_red">Item Rejected</span>');
                }else if ( thisbtn.hasClass('unreject') ){
                    thisbtn.removeClass('unreject').addClass('reject').val('Reject Item');
                    thismethod.val('reject');
                    status.replaceWith('<span class="trans_alert trans_red">Item Unrejected</span>');
                }
            }
            else{
                alert(obj.error);
            }
        });
        thisbtn.attr('disabled', true);
        thisbtn.val('Sending...');
        return false;
    });
    
    $(document).on('click', '.feedback-cancel', function(){
        $.modal.close();
    });
    
    $(document).on('click','.tx_modal_edit', function(){
        $(this).siblings('input[type="text"],textarea').attr('disabled', false);
        $(this).hide();
        $(this).siblings('.tx_modal_cancel').show();
    });
    
    $(document).on('click','.tx_modal_cancel', function(){
        var input = $(this).siblings('input[type="text"]');
        var textarea = $(this).siblings('textarea');
        
        input.attr('disabled', true);
        textarea.attr('disabled', true);
        
        $.each(input, function(k,v){
            $(v).prop('value', htmlDecode($(v).attr('value')));
        });
        textarea.val(htmlDecode(textarea.attr('data-value')));
        
        $(this).hide();
        $(this).siblings('.tx_modal_edit').show();
        $(this).closest('form').validate().resetForm();
    });
    
});

/*******************    HTML Decoder    ********************************/
function htmlDecode(value) {
    
    //value = value.replace(/script>/g, '');
    
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}

/*******************    Handle Fields Display   ********************************/
function handle_fields(form)
{
    if(form === '')
        var o = $('form.dropdownform');
    else
        var o = form;
        
    o.each(function(){
        var is_empty = true;
        var echoedfield = $(this).find('div.echoed_info');
        var editprof = $(this).find('div.edit_profile');
        var geninfo = $(this).find('div.gen_information');
        
        if ($.trim(echoedfield.html()).length > 0){
            is_empty = false;
        }
        
        if(is_empty){
            editprof.show();
            geninfo.hide();
        }
        else{
            editprof.hide();
            geninfo.show();
        }
    });
}

/************************** CITY FILTER SELECT  **************************************/
/*
 *  Function to generate provinces in dropdown.
 */
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

/***************************** PERSONAL PROFILE PROGRESSBAR ************************************/
function progress_update(form){
    var fcount = 0;
    
    if(form==='')
        var o = $('div.progress_update');
    else
        var o = form.find('div.progress_update');
        
    o.each(function(){
        var thisclass = $(this).attr('class');
        var n = thisclass.indexOf('update_all');
        var m = thisclass.indexOf('update_once');
        var c = thisclass.indexOf('update_consignee');
        
        if(n!=-1 && m==-1){// if update_all
            var count = 0;
            $(this).find('input[type="text"]').each(function(){
                if($(this).prop('value').length > 0)
                    count++;
            });
            $(this).find('input[type="radio"]:checked').each(function(){
                count++;
                return false;
            });
            $(this).find('input[type="hidden"].progress_update_hidden').attr('value', count);
        }
        else if(n==-1 && m!=-1){ // if update_once
            var count = 0;
            $(this).find('input[type="text"]').each(function(){
                if($(this).prop('value').length > 0){
                    count++;
                    return false;
                }
            });
            $(this).find('input[type="hidden"].progress_update_hidden').attr('value', count);
        }
        else if(c!=-1){ // for consignee
            var count = 0;
            // reads consignee (includes select and address since required), 
            // mobile, and telephone fields
            $(this).find('input[type="text"]:not([name="c_telephone"])').each(function(){
                if($(this).prop('value').length > 0){
                    count++;
                    return false;
                }
            });
            
            if( $(this).find('input[name="c_telephone"]').prop('value').length > 0 ){
                count++;
            }
            
            $(this).find('input[type="hidden"].progress_update_hidden').attr('value', count);
        }
    });
    
    $('input.progress_update_hidden').each(function(){
        fcount += +$(this).attr('value');
    });
    
    var imgdir = $('#user_image').attr('src');
    var n = imgdir.search("/user/default");
    if (n==-1)
        fcount++;
    
    // 12 inputs/sets
    var percentage = Math.ceil(fcount/12 * 100);
    
    $('#progressbar').progressbar({
        value:percentage
    });
    $('#profprog_percentage').attr('value', percentage);
    $('#profprog_percentage').html(percentage + '%');
}


/***************    Image preview for cropping  ************************/
function imageprev(input) {

    var jcrop_api, width, height;
    
    if (input.files && input.files[0] && input.files[0].type.match(/(gif|png|jpeg|jpg)/g) && input.files[0].size <= 5000000) {
        var reader = new FileReader();

        reader.onload = function(e){
            var image = new Image();
            image.src = e.target.result;
            image.onload = function(){
                width = this.width;
                height = this.height;
                $('#user_image_prev').attr('src', this.src);
                if(width >10 && height > 10 && width <= 5000 && height <= 5000)
                    deploy_imageprev();
                else if(width > 5000 || height > 5000)
                    alert('Failed to upload image. Max image dimensions: 5000px x 5000px');
                else
                    $('#div_user_image_prev span:first').html('Preview');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
    else
        alert('You can only upload gif|png|jpeg|jpg files at a max size of 5MB! ');
    
    
    function deploy_imageprev(){
        $('#div_user_image_prev').modal({
                escClose: false,
                containerCss:{
                    maxWidth: 600,
                    minWidth: 505,
                    maxHeight: 600
                },
                onShow: function(){
                    $('#div_user_image_prev button').on('click', function(){
                        $('#form_image').submit();
                        $.modal.close();
                    });
                    jcrop_api = $.Jcrop($('#user_image_prev'),{
                        aspectRatio: 1,
                        boxWidth: 500,
                        boxHeight: 500,
                        minSize: [width*0.1,height*0.1],
                        trueSize: [width,height],
                        onChange: showCoords,
                        onSelect: showCoords,
                        onRelease: resetCoords
                    });
                    this.setPosition();
                },
                onClose: function(){
                    $('#user_image_prev').attr('src', '');
                    resetCoords();
                    jcrop_api.destroy();
                    $('#div_user_image_prev span').after('<img src="" id="user_image_prev">');
                    $.modal.close();
                }
            });
    }
}

function showCoords(c){
    $('#image_x').val(c.x);
    $('#image_y').val(c.y);
    $('#image_w').val(c.w);
    $('#image_h').val(c.h);
}

function resetCoords(){
    $('#image_x').val(0);
    $('#image_y').val(0);
    $('#image_w').val(0);
    $('#image_h').val(0);
}


/*********  DASHBOARD BUTTONS - ETC ***************/
$(document).ready(function(){

    $('div.dashboard_table').on('click', '.show_prod_desc', function(){
        $(this).siblings('.item_prod_desc_content').addClass('show_desc');
        $(this).fadeOut();
    });

    $('div.dashboard_table').on('click', '.show_more_options', function(){
        $(this).siblings('.attr_hide').slideToggle();
        $(this).toggleClass("active");
    });
    
    $('.sales_info').on('click', function(){
        var sub = $(this).attr('data-div');
        var modaldiv = $('#sales_'+sub);
        
        modaldiv.modal({
            containerCss:{
                maxHeight: '350px'
            },
            onShow: function(){
                this.setPosition();
            },
            onClose: function(dialog){
                $.modal.close();
            }
        });

        $('#simplemodal-container').addClass('sales-details-modal');

    });
});

/********************   PAGING FUNCTIONS    ************************************************/
$(document).ready(function(){
    
    $('#op_buyer .paging:not(:first)').hide();
    $('#op_seller .paging:not(:first)').hide();
    $('#yp_buyer .paging:not(:first)').hide();
    $('#yp_seller .paging:not(:first)').hide();
    
    $('#pagination-opbuyer').jqPagination({
        paged: function(page) {
            $('#op_buyer .paging').hide();
            $($('#op_buyer .paging')[page-1]).show();
        }
    });
    $('#pagination-opseller').jqPagination({
        paged: function(page) {
            $('#op_seller .paging').hide();
            $($('#op_seller .paging')[page-1]).show();
        }
    });
    $('#pagination-ypbuyer').jqPagination({
        paged: function(page) {
            $('#yp_buyer .paging').hide();
            $($('#yp_buyer .paging')[page-1]).show();
        }
    });
    $('#pagination-ypseller').jqPagination({
        paged: function(page) {
            $('#yp_seller .paging').hide();
            $($('#yp_seller .paging')[page-1]).show();
        }
    });
    
});

function triggerTab(x){
    $('.idTabs a[href="#'+x+'"]').trigger('click');
}


/***** create wishlist modal *****/
$(document).ready(function(){
     $('.wishlist_create').click(function (e) {
        $("#create_wishlist").modal({position: ["25%","35%"]});
        $('#create_wishlist').parent().removeAttr('style');
        });

     });
        



/***********************    GOOGLE MAPS     ***************************/

$(document).ready(function(){
    var mapPersonal, markerPersonal, geocoder;
    var mapDelivery, markerDelivery;

    $(".refresh_map").click(function(){     
        if($(this).attr('name') === 'personal_rmap'){
            var stateregion = $('#personal_stateregion').find('option:selected').text();
            var city = $('#personal_city').find('option:selected').text();
            var type = "personal";
        }
        else if($(this).attr('name') === 'delivery_rmap'){
            var stateregion = $('#delivery_stateregion').find('option:selected').text();
            var city = $('#delivery_city').find('option:selected').text();
            var type = "delivery";
        }
        
        var address = stateregion + " " + city + " PH";
        codeAddress(address, type);
    });

    function codeAddress(address, type) {
      geocoder = new google.maps.Geocoder();
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            google.maps.event.addDomListener(window, 'load', initialize(results[0].geometry.location, type));
        } else {
            if(type==='personal'){
                $('#personal_mapcanvas').siblings('.map_nav').children('.close').trigger('click');
            }else if(type==='delivery'){
                $('#delivery_mapcanvas').siblings('.map_nav').children('.close').trigger('click');
            }
            alert('Please specify a valid address.');
        }
      });
    }
    
    //all DOM elements accessed via id
    function initialize(myLatlng, type) {
        var mapOptions = {
          center:myLatlng,
          zoom: 15
        };
        if( type === 'personal' ){
            var templat = $('#temp_lat');
            var templng = $('#temp_lng');
            mapPersonal = new google.maps.Map(document.getElementById("personal_mapcanvas"),mapOptions);
            markerPersonal = new google.maps.Marker({
                position: myLatlng,
                map: mapPersonal,
                title:"I'm here!",
                draggable: true
            });
            google.maps.event.addListener(markerPersonal, 'dragend', function(evt){
                templat.val(evt.latLng.lat());
                templng.val(evt.latLng.lng());
                
                window.setTimeout(function(){
                    mapPersonal.panTo(markerPersonal.getPosition());
                }, 500);
            });
            google.maps.event.addListenerOnce(mapPersonal, 'idle', function(){
                google.maps.event.trigger(mapPersonal, 'resize');
                window.setTimeout(function(){
                    mapPersonal.panTo(markerPersonal.getPosition());
                }, 500);
            });
        }
        else if( type === 'delivery' ){
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
        }
        templat.val(myLatlng.lat());
        templng.val(myLatlng.lng());
    }
    
    $('.view_map').click(function () {
        var maplat = $(this).siblings('input[name="map_lat"]').val();
        var maplng = $(this).siblings('input[name="map_lng"]').val();
        var refreshmapbtn = $(this).parent('div').siblings('div.map_nav').children('span.refresh_map');
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
        
        $(this).fadeOut();
        $(this).parent('div').siblings('.map_nav').fadeIn();
        $(this).parent('div').siblings('.map-canvas').addClass('map_canvas');
        $(this).parent('div').siblings('.map-canvas').fadeIn();             
    });
    
    $('.current_loc').on('click', function(){
        var maplat = $(this).parent('div').siblings('div.view_map_btn').children('input[name="map_lat"]').val();
        var maplng = $(this).parent('div').siblings('div.view_map_btn').children('input[name="map_lng"]').val();
        var type = $(this).attr('name');

        if(maplat != 0 && maplng != 0){
            var myLatlng =  new google.maps.LatLng(maplat,maplng);
            if( type === 'personal_cmap' ){
                mapPersonal.setCenter(myLatlng);
                markerPersonal.setPosition(myLatlng);
            }
            else if( type === 'delivery_cmap'){
                mapDelivery.setCenter(myLatlng);
                markerDelivery.setPosition(myLatlng);
            }
        }else{
            alert('You have not marked your location yet.');
        }
    });
    
    $('.close').click(function () {
        $(this).parent('div').fadeOut();
        $(this).parent('div').siblings('.map-canvas').fadeOut();
        $(this).parent('div').siblings('.view_map_btn').find('.view_map').fadeIn();
    });
    
});


/*******************    TRANSACTION MAPS    ********************************************/
$(document).ready(function(){
    
    var map, marker;
    
    $('.dashboard_table').on('click','.tsold_viewmap', function(){
        var maplat = $(this).attr('data-lat');
        var maplng = $(this).attr('data-lng');
        var myLatlng =  new google.maps.LatLng(maplat,maplng);

        $('#map_modalcont').modal({
            onShow: function(){
                google.maps.event.addDomListener(window, 'load', initialize(myLatlng));
                this.setPosition();
            },
            onClose: function(){
                $.modal.close();
            }
        });
    });
    
    function initialize(myLatlng) {
        var mapOptions = {
          center:myLatlng,
          zoom: 15
        };
        map = new google.maps.Map(document.getElementById("tsold_mapview"), mapOptions);
        marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title:"I'm here!"
        });
    }
    
});



/*******************    Billing Info - Bank Selection   ********************************/


$(document).ready(function(){
    
    //// GET BANK DROPDOWN - START
    (function($){
       $.fn.getbank = function(selected) {
            
            var appendTarget = "#" + this.attr('id');
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            
            $.getJSON('memberpage/bank_info',{q:'%',name:'',csrfname:csrftoken},function(data){
                var html = '';
                var len = data.length;
                for (var i = 0; i< len; i++) {
                    html += '<option value="' + data[i].id + '" title="' + data[i].name + '">' + data[i].name + '</option>';
                }
                $(appendTarget).append(html);
            
                var SelectedValue = selected;
                $(appendTarget + " option").filter(function(){
                    return $(this).text() == SelectedValue;
                }).first().prop("selected", true);
            });
            return this;       
       }; 
    })(jQuery);
    //// GET BANK DROPDOWN - END    
        
    $('#abi_btn').click(function(){
        $('#bi_bank').getbank("");
        $('#abi').toggle("slow");
    });
    
    ////// START /////////////////////////////////////////////////////////////////////
    
    // CHECKBOX
    $(":checkbox[name^='bi_chk_bictr']").click(function(){
        $('input:checkbox').not(this).prop("checked", false);
    });
    
    $("span[id^='bi_txt_bictr']").click(function(){

        var getbictr = $(this).attr('id');
        var bictr = getbictr.substring(7,30);
        var bid = 'bi_id_' + bictr;
        var def = confirm("Set as default account?");
        if(def){            
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var bidval = $("#"+bid).val();              
            var currentRequest = null;
            var redurl =  config.base_url+'memberpage/billing_info_f';
            currentRequest = jQuery.ajax({
                type: "POST",
                url: redurl, 
                data: {bi_id:bidval, csrfname:csrftoken},
                success: function(data){
                    window.location.href = config.base_url+'me?me=pmnt';
                }
            });     
        }
    }); 
    
    // DELETE BUTTON
    $(":button[name^='del_bictr']").click(function(){
        var getbictr = $(this).attr('name');
        var bictr = getbictr.substring(4);
        var bid = 'bi_id_' + bictr;
        var bi = $('#'+bid).val();
        
        var $prod_div = $('#acct_prod_'+bi);
        if(typeof $prod_div[0] !== 'undefined'){
            $prod_div.dialog({
                modal:true,
                resizable:false,
                draggable:false,
                width:650,
                height: 400,
                title: 'Confirm Delete | Easyshop.ph',
                buttons:{
                    OK:function(){
                        $(this).dialog('close');
                        delete_bank_account(bi, false);
                    },
                    Cancel:function(){
                        $(this).dialog('close');
                    },
                }
            });
        }
        else{
            delete_bank_account(bi);
        }
    });

    // EDIT BUTTON
    $(":button[name^='bictr']").click(function(){
        
        $("#billing_info_x").find('div[id*=bi_check_bictr]').hide();
        
        var bictr = $(this).attr('name');
        var ban = 'bi_ban_' + bictr;
        var bar = 'bi_bar_' + bictr;
        var bn = 'bi_bn_' + bictr;
        var bns = 'bi_bns_' + bictr;
        var bid = 'bi_id_' + bictr;
        var bch = 'bi_chk_' + bictr;

        $("#"+ban).prop("disabled", false).removeClass("bi_input");
        $("#"+bar).prop("disabled", false).removeClass("bi_input");
        $("#"+bn).prop("disabled", false).removeClass("bi_input");
        $("#"+bch).prop("disabled", false).removeClass("bi_input");
        
        $("#sv_"+bictr+", #cn_"+bictr+", #bi_bns_"+bictr).show();       
        $("#del_"+bictr+", #"+bictr+", #bi_bn_"+bictr).hide();
        $(":button[name^='bictr']").prop("disabled", true);
        $(":button[name^='del_bictr']").prop("disabled", true);
        
        var bankname = $('#bi_bn_' + bictr).val();  
        $('#bi_bns_' + bictr).getbank(bankname).show();
            
    });
    
    // Cancel Button
    $(":button[name^='cn_bictr']").click(function(){
        
        var getbictr = $(this).attr('name');
        var bictr = getbictr.substring(3,99);
        
        var ban = 'bi_ban_' + bictr;
        var bar = 'bi_bar_' + bictr;
        var bn = 'bi_bn_' + bictr;
        var bns = 'bi_bns_' + bictr;
        var bid = 'bi_id_' + bictr;
        var bch = 'bi_chk_' + bictr;
        
        $(":input[name^='hbi_chk_bictr']").filter(function(){
            
            var hid = $(this).attr('id');
            var fid = hid.substring(1,30);
            
            if($(this).val() == "checked"){
                $("#"+fid).prop("checked", true);
            }else{
                $("#"+fid).prop("checked", false);
            }
        });
        
        $("#"+ban).val($("#h"+ban).val());
        $("#"+bar).val($("#h"+bar).val());
        $("#"+bn).val($("#h"+bn).val());
        
        $("#"+ban).prop("disabled", true).addClass("bi_input");
        $("#"+bar).prop("disabled", true).addClass("bi_input");
        $("#"+bn).prop("disabled", true).addClass("bi_input");
        $("#"+bch).prop("disabled", true).addClass("bi_input");         

        $("#sv_"+bictr+", #cn_"+bictr+", #bi_bns_"+bictr).hide();       
        $("#del_"+bictr+", #"+bictr+", #bi_bn_"+bictr).show();
        $(":button[name^='bictr']").prop("disabled", false);
        $(":button[name^='del_bictr']").prop("disabled", false);
       
        $(this).closest('form').find('span.error').remove();
    });
    
    // Save Button
    $(":button[name^='sv_bictr']").click(function(){
        var getbictr = $(this).attr('name');
        var bictr = getbictr.substring(3);
        var $prod_div = $('#acct_prod_'+bictr);
        if(typeof $prod_div[0] !== 'undefined'){
            $prod_div.dialog({
                modal:true,
                resizable:false,
                draggable:false,
                width:650,
                height: 400,
                title: 'Confirm Changes | Easyshop.ph',
                buttons:{
                    OK:function(){
                        $(this).dialog('close');
                        update_bank_account(bictr, false);
                    },
                    Cancel:function(){
                        $(this).dialog('close');
                    },
                }
            });
        }
        else{
            update_bank_account(bictr);
        }
    });
    
    
    
    function delete_bank_account(bid,xconfirm){
        var xconfirm = (typeof xconfirm !== 'undefined') ? xconfirm : true;
        if(xconfirm){
            var del = confirm("Delete bank info?");
        }else{
            var del = true;
        }
        if(del){
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');     
            var currentRequest = null;
            var redurl =  config.base_url+'memberpage/billing_info_d';
            currentRequest = jQuery.ajax({
                type: "POST",
                url: redurl, 
                data: {bi_id:bid, csrfname:csrftoken},
                success: function(data){
                    window.location.href = config.base_url+'me?me=pmnt';
                }
            });     
        }
    }

    
    
    function update_bank_account(bictr, xconfirm){
        var xconfirm = (typeof xconfirm !== 'undefined') ? xconfirm : true;
        var banRule = {
            required: true,
            messages: {
                required: "* Account Name Required"
            }
        }

        var barRule = {
            required: true,  
            messages: {
                required: "* Account Number Required",
            }
        }
        
        var bnRule = {
            required: true,
            messages: {
                required: "* Bank Required"
            }
        }
        var ban = 'bi_ban_' + bictr;
        var bar = 'bi_bar_' + bictr;
        var bn = 'bi_bn_' + bictr;
        var bns = 'bi_bns_' + bictr;
        var bid = 'bi_id_' + bictr;
        var bch = 'bi_chk_' + bictr;

        if(xconfirm){
            var updt = confirm("Update bank info?");
        }else{
            var updt = true;
        }

        if(updt){
            $("#ubi_"+bictr).validate({
                errorElement: "span",
                errorPlacement: function(error, element){
                        error.addClass('red');
                        error.appendTo(element.parent());
                }       
            });

            $("[name='bi_ban_"+bictr+"']").rules("add", banRule);
            $("[name='bi_bar_"+bictr+"']").rules("add", barRule);
            $("[name='bi_bn_"+bictr+"']").rules("add", bnRule);

            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var banval = $("#"+ban).val();
            var barval = $("#"+bar).val();
            var bnval = $("#"+bns).val();
            var bntit = $("#"+bns).find("option:selected").attr("title");
            var bidval = $("#"+bid).val();
            var bchval = $("#"+bch).val();      
            
            var redurl = config.base_url+'memberpage/billing_info_u';
            if($("#ubi_"+bictr).valid()){   
                currentRequest = jQuery.ajax({
                    type: "POST",
                    url: redurl, 
                    data: {bi_acct_name:banval, bi_acct_no:barval, bi_bank:bnval, bi_id:bidval, bi_def:bchval, csrfname:csrftoken, bi_payment_type:'Bank'},
                    success: function(data){
                        var obj = JSON.parse(data);
                        if((parseInt(obj.e,10) == 1) && (obj.d=='success')){
                            $(":checkbox[name^='bi_chk_bictr']").filter(function(){
                                var hid = $(this).attr('id');
                                if($(this).prop("checked") == true){
                                    $("#h"+hid).val("checked");
                                }else{
                                    $("#h"+hid).val("");
                                }
                            });                         
                            $("#bi_check_"+bictr).show().delay(1600).fadeOut(600);
                            $("#h"+ban).val($("#"+ban).val());
                            $("#h"+bar).val($("#"+bar).val());
                            $("#h"+bn).val(bntit);
                            $("#"+bn).val(bntit);                           
                            
                            $("#"+ban).prop("disabled", true).addClass("bi_input");;
                            $("#"+bar).prop("disabled", true).addClass("bi_input");;
                            $("#"+bn).prop("disabled", true).addClass("bi_input");;
                            $("#"+bch).prop("disabled", true).addClass("bi_input");;            
                
                            $("#sv_"+bictr+", #cn_"+bictr+", #bi_bns_"+bictr).hide();       
                            $("#del_"+bictr+", #"+bictr+", #bi_bn_"+bictr).show();
                            $(":button[name^='bictr']").prop("disabled", false);
                            $(":button[name^='del_bictr']").prop("disabled", false);    

                            return false;
                        }else if((parseInt(obj.e,10) == 0) && (obj.d=='duplicate')){
                            $("#bi_err_"+bictr).show().delay(2000).fadeOut(800);
                        }else{
                            alert('Something went wrong. Please try again later.');
                        }
                    }
                });     
            }
        }else{
            return false;
        }
    }
    


});

$(document).ready(function(){
     $("#billing_info").validate({
         ignore: ':hidden:not([class~=selectized]),:hidden > .selectized, .selectize-control .selectize-input input',
         rules: {
            bi_bank: {
                required: true          
            },
            bi_acct_name: {
                required: true      
            },          
            bi_acct_no: {
                //required: true, minlength: 12, maxlength: 18
                required: true                  
            } 
         },
         messages:{
            bi_bank: {
                required: '* Bank Required'
            },
            bi_acct_name: {
                required: '* Account Name Required'
            },
            bi_acct_no: {
                required: '* Account Number Required'
            }                       
         },
        errorElement: "span",
        errorPlacement: function(error, element) {
                error.addClass('red');
                error.appendTo(element.parent());
        }
                
     });
         
    var redurl =  config.base_url+'memberpage/billing_info';
    $("#billing_info_btn").click(function() {
        if($("#billing_info").valid()){ 
            jQuery.ajax({
                type: "POST",
                url: redurl, 
                data: $("#billing_info").serialize(),
                success: function(data){
                    var obj = JSON.parse(data);
                    if((parseInt(obj.e,10) == 0)&&(obj.d=='duplicate')){
                        $("#bi_err_add").show().delay(2000).fadeOut(800);                       
                    }else if((parseInt(obj.e,10) == 1)&&(obj.d=='success')){
                        $("#bi_bank, #bi_acct_name, #bi_acct_no").val('');
                        window.location.href = config.base_url+'me?me=pmnt';
                    }else{
                        alert('Something went wrong. Please try again later.');
                    }
                }
            });     
        }       
    }); 
    
    
    $(document).on('click','.fulldelete',function(event){ 
        var bool = confirm('This item will be deleted from Easyshop.ph for good. Are you sure?');
        return bool;
    });
    
});

/**
* Function to handle display of Price Value
**/
function ReplaceNumberWithCommas(thisnumber){
    //Seperates the components of the number
    var n = thisnumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

