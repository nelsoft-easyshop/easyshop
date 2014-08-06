
 
$(document).ready(function(){

     // if keyword change. counter will change also either increase or decrease until reach its limit..
    updateCountdown();
    $('#prod_keyword').change(updateCountdown);
    $('#prod_keyword').keyup(updateCountdown); 



    // search brand 

    $('#brand_sch').focus(function() {
        $('#brand_search_drop_content').show();
        $(document).bind('focusin.brand_sch_drop_content click.brand_sch_drop_content',function(e) {
          if ($(e.target).closest('#brand_search_drop_content, #brand_sch').length) return;
          $('#brand_search_drop_content').hide();
      });
    });

    $('#brand_search_drop_content').hide();

});
 


// TINYMCE
$(function(){
    tinymce.init({
        mode : "specific_textareas",
        editor_selector : "mceEditor", 
        menubar: "table format view insert edit",
        statusbar: false, 
        height: 300,
        plugins: ["lists link preview","table jbimages fullscreen","textcolor" ],  
        toolbar: "insertfile undo redo | sizeselect | fontselect  fontsizeselect styleselect  forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
        relative_urls: false,
        setup: function(editor) {
            editor.on('change', function(e) {
                $('#prod_description').val(tinyMCE.get('prod_description').getContent());
                $('#prod_description').trigger( "change" );
            });
        }
    });

    tinymce.init({
        mode : "specific_textareas",
        editor_selector : "mceEditor_attr", 
        menubar: "table format view insert edit",
        statusbar: false,
        height: 200,
        plugins: [
        "lists link preview ",
        "table jbimages fullscreen" 
        ],  
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
        relative_urls: false
    });
});

// FUNCTION FOR KEYWORD COUNTER
function updateCountdown() {
    // 140 is the max message length
    var remaining = 150 - $('#prod_keyword').val().length;
    $('.countdown').text(remaining + ' characters remaining.');
}

// ERROR HANDLER
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

// NUMBER ONLY IN SPECIFIC FIELDS
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 
      && (charCode < 48 || charCode > 57))
     return false;

 return true;
 }

 function setChosen(){
    $("#head-data,.value-data").chosen({
        create_option: true,
        skip_no_results: true,
        persistent_create_option: true,
        create_option_text: 'Custom',
        width: "100%"
    });  
}

function appendNewSelectionRow(){
    cnt++;  
    var stringAppend = '<div class="control-panel-'+cnt+' ctrl mrgin-bttm-10 height-35">\
    <div class="display-ib width-100p-40max"></div>\
    <div class="value-section col-xs-12 col-sm-6 col-md-6 pd-bttm-10">\
    <div class="select-value-section">\
    <select id="value-data-'+cnt+'" class="value-data" data-cnt="'+cnt+'" data-placeholder="(e.g Blue, Red, Small, Large,...) ">\
    <option value="0"></option>\
    </select>\
    </div>\
    </div>\
    <div class="price-div col-xs-12 col-sm-3 col-md-3 pd-bttm-10">\
    <input type="text" class="price-val price'+cnt+' ui-form-control width-100p" placeholder="0.00" />\
    </div>\
    <div class="image-div col-xs-12 col-sm-2 col-md-2 pd-bttm-10">\
    <input type="hidden" class="image-val imageText'+cnt+'"/>\
    <a class="attr-image image'+cnt+'" data-cnt="'+cnt+'" href="javascript:void(0)"><img src="'+config.base_url+'assets/images/img_upload_photo.jpg"></a>\
    <a class="remove-attr-image vrtcl-top" data-cnt="'+cnt+'" href="javascript:void(0)"><span style="color:red" class="glyphicon glyphicon-remove"></span></a>\
    </div>\
    <a class="remove-control-panel col-xs-12 col-sm-1 col-md-1" href="javascript:void(0)" data-cnt="'+cnt+'">Remove</a>\
    </div><div class="clear"></div>';

    $('.control-panel').append(stringAppend);

    var selector = $('#head-data');
    var valueData =  $('#value-data-'+cnt);
    var selectedValue = selector.chosen().val(); 
    if(selectedValue != '0'){
        var attrList = attributeArray[selectedValue];  
        valueData.empty(); 

        if(attrList!==undefined){
            $.each(attrList, function(key, value){
                valueData.append('<option>'+value+'</option>');
            });
        }
        valueData.trigger("liszt:updated");
    }
    setChosen(); 
    return cnt;
}


var previousValue;
$(document).on("click",".list-choosen-combination-div > .div-combination > .div2 > span > .selection",function(){
    previousValue = $(this).children('option:selected').text();
});

$(document).on("change",".list-choosen-combination-div > .div-combination > .div2 > span > .selection",function(){
    var selector = $(this);
    var cnt = selector.parent().parent().parent().children('.div3').children('.remove-combination').data('cmbcnt');
    var currentStringId = "";
    $('.list-choosen-combination-div > .combination'+cnt+' > .div2 > span > .selection').each(function() {
        currentStringId += $(this).children('option:selected').data('value');
    });

    var checkIfExist = checkCombination(currentStringId);
    if(checkIfExist == false){
        selector.val(previousValue);
        alert('Combination Already Exist!');
    }
});

function removeDuplicateCombination()
{
    var arrayCombination = new Array();
    $(".div-combination").each(function(){
        var selector = $(this);
        var cnt = selector.children('.div3').children('.remove-combination').data('cmbcnt');
        var select = selector.children('.div2').children('span').children('.selection');
        var stringId = "";
        select.each(function(){
            stringId += $(this).children('option:selected').data('value');
        });

        if($.inArray(stringId,arrayCombination) <= -1){
            arrayCombination.push(stringId);
        }
        else{
            $('.combination'+cnt).remove();
        }
    }); 
}

function checkCombination(currentStringId)
{
    var arrayCombination = new Array();
    $(".div-combination").each(function(){
        var select = $(this).children('.div2').children('span').children('.selection');
        var stringId = "";
        select.each(function(){
            stringId += $(this).children('option:selected').data('value');
        });
        arrayCombination.push(stringId);
    });
    if(arrayCombination.length > 1){
        if($.inArray(currentStringId,arrayCombination) <= -1){
            console.log('not exist');
            return true;
        }
        else{
            console.log('exist');
            return false;
        }
    }

}

function resetControlPanel()
{
    var defaultString = '<div class="control-panel-1 ctrl">\
    <div class="display-ib width-100p-40max"></div>\
    <div class="display-ib width-100p-40max value-section vrtcl-top">\
    <div class="select-value-section">\
    <select data-placeholder="(e.g Blue, Red, Small, Large,...) " data-cnt="1" class="value-data" id="value-data-1">\
    <option value="0"></option> \
    </select>\
    </div> \
    </div>\
    <div class="display-ib width-100p-40max price-div vrtcl-top">\
    <input type="text" placeholder="0.00" class="price-val price1">\
    </div>\
    <div class="display-ib width-100p-40max image-div vrtcl-top">\
    <input type="hidden" class="image-val imageText1"/>\
    <a class="attr-image image1" data-cnt="1" href="javascript:void(0)"><img src="'+config.base_url+'assets/images/img_upload_photo.jpg"></a>\
    <a class="remove-attr-image vrtcl-top" data-cnt="1" href="javascript:void(0)"><span style="color:red" class="glyphicon glyphicon-remove"></span></a>\
    </div>\
    </div>';

    $('.control-panel').empty().append(defaultString);
    setChosen();
    $("#head-data").val('').trigger("liszt:updated");
    $('.add-property').val('Add Property');
    editSelectedValue,editSelectedValue = '';
    $('#cancel-changes').remove()
}

function ReplaceNumberWithCommas(thisnumber){
        //Seperates the components of the number
        var n= thisnumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    }

    function get_discPrice() {
        var prcnt = $("#slider_val").val().replace("%",'');
        var act_price = $("#prod_price").val().replace(/,/g,'');
        if (prcnt >= 100) {
            prcnt = 99;
        }
        if (act_price == 0 || act_price == null ) {      
            validateRedTextBox("#prod_price");
            act_price = 0;
        }

        $("#slider_val").val("");
        $("#slider_val").val(prcnt+"%");
        discounted = act_price * (prcnt/100);
        var v = parseFloat(act_price - discounted);
        tempval = Math.abs(v);
        disc_price = ReplaceNumberWithCommas(tempval.toFixed(2));
        $("#discountedP").val(disc_price);
        $( "span#discounted_price_con" ).text( disc_price );
    }



// view more product details
$(document).ready(function() {
    $('.view_more_product_details').on('click', function() {
        $('.more_product_details_container,.prod-details-add-more-link').slideToggle();
        $('.view_more_product_details').toggleClass('active-product-details');
    });
});
// end of view more product JS

// JS Function Discount
$(document).ready(function(){

    $("#dsc_frm").hide();

    $('#prod_price').on('change', function(){
        var prcnt = parseFloat($("#slider_val").val().replace("%",''));
        if( !isNaN(prcnt) ){
            get_discPrice();
        }
    });

    $("#range_1").ionRangeSlider({
        min: 0,
        max: 100,
        type: 'single',
        step: 1,
        postfix: "%",
        prettify: true,
        hasGrid: true,
        onChange: function (obj) {        // callback is called after slider load and update
            var value = obj.fromNumber;
            $("#slider_val").val(value);
            get_discPrice();
        }
    });


    $("#slider_val").bind('change keyup',function(e){
        if(e.which > 13 || e.which < 13){
            return false;
        }
        var thisslider = $(this);
        var newval = (parseFloat($(this).val()) > 100) ? 99 : (parseFloat($(this).val()) == 0 || isNaN(parseFloat($(this).val())))? 0 : parseFloat($(this).val());
        get_discPrice();
        $("#range_1").ionRangeSlider("update", {
            from: newval,                       // change default FROM setting
            onChange: function (obj) {        // callback is called after slider load and update
                var value = obj.fromNumber;
                thisslider.val(value);
                get_discPrice();
            }
        });
    });

    $("#discnt_btn").on("click",function(){
        $("#dsc_frm").toggle();      
    });  

    $("#discountedP").bind('change keyup',function(e){
        if(e.which > 13 || e.which < 13){
            return false;
        }
        validateWhiteTextBox("#discountedP");
        var disc_price = parseFloat($(this).val());
        var base_price = parseFloat($("#prod_price").val().replace(/,/g,''));
        var sum = ((base_price - disc_price) / base_price) * 100;
        sum = sum.toFixed(4);
        if(disc_price > base_price){
            alert("Discount Price cannot be greater than base price.");
            $(this).val("0.00");
            validateRedTextBox("#discountedP");
            return false;
        }
        if(disc_price <= 0){
            alert("Discount Price cannot be equal or less than 0.");
            $(this).val("0.00");
            $( "span#discounted_price_con" ).text( "0.00" );
            validateRedTextBox("#discountedP");
            return false;
        }
        $("#range_1").ionRangeSlider("update", {
            from: sum
        });
        $("#slider_val").val(sum+"%");
        tempval = Math.abs(disc_price);
        disc_price = ReplaceNumberWithCommas(tempval.toFixed(2));
        $(this).val(disc_price);
        $( "span#discounted_price_con" ).text( disc_price );
    });

});
// end of JS Function Discount

// Manipulating of additional attributes
var cnt = 1; 
var previous,editSelectedValue,editSelectedId; 
$(document).ready(function() {   

    setChosen(); 

    $(document).on('change',".price-val,#prod_price",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        } else {
            tempval = Math.abs(v);
            this.value = tempval.toFixed(2);
        }
    });


    $(document).on("click","#head_data_chzn",function (){
        var selector = $("#head-data");  
        previous = selector.chosen().val();
    });

    $(document).on("change","#head-data",function (){
        var selector = $(this); 
        var valueData =  $('.value-data'); 
        var selectedValue = selector.chosen().val();  
        var cleanString = selectedValue.toLowerCase().replace(/ /g,'');
        var length = $(".select-control-panel-option > .div2 > span > #"+cleanString).length; 
        var buttonValue = $('.add-property').val(); 
        var attrList = attributeArray[selectedValue]; 

        if(buttonValue === 'Add Property'){
            valueData.empty(); 
            if(attrList!==undefined){
                $.each(attrList, function(key, value){
                    valueData.append('<option>'+value+'</option>');
                });
            }
            else{
                attributeArray[selectedValue] = new Array();
            }
        }
        else{

            if(length > 0 && editSelectedValue != selectedValue){
                $('#head-data').val(previous).trigger("liszt:updated");
                alert('already exist');
            }
            else{
                $('.value-data option').not(':selected').remove();
                if(attrList!==undefined){
                    $.each(attrList, function(key, value){
                        valueData.append('<option>'+value+'</option>');
                    });
                }
            }
        }
        valueData.trigger("liszt:updated");
    });

    $(document).on("click",".add-property-value",function (){
       appendNewSelectionRow();
    });

    $(document).on("click",".remove-control-panel",function (){
        var cnt = $(this).data('cnt'); 
        $('.control-panel-'+cnt).remove();
    });

    $(document).on("click",".add-property",function (){
        var selector = $('#head-data');
        buttonValue = $(this).val();
        var selectedValue = selector.chosen().val();
        var cleanString = selectedValue.toLowerCase().replace(/ /g,'');
        var optionString = "";
        var eachCtrlValue = new Array();
        var checked = $('.set-default:checked').length > 0; 

        if(selectedValue == 0){
            console.log('no value selected');
            return false;
        }

        $(".ctrl").each(function(){
            var selectList = $(this).find(".value-section").find('.select-value-section').find('.value-data').val();
            var priceString = $(this).find(".price-div").find('.price-val').val();
            var price = (priceString.length == 0) ? '0.00' : priceString;
            var image = $(this).find(".image-div").find('.image-val').val();
            
            if(selectList){
                if($.inArray(selectList,eachCtrlValue) <= -1){
                    if($.trim( $('.select-control-panel-option > .div2').html() ).length ) {
                        if($(".select-control-panel-option > .div2 > span >#"+cleanString).length > 0){
                            if($('.div2 > span > #'+cleanString + ' option[data-value="'+selectList+'"]').length > 0){
                                $('.div2 > span > #'+cleanString + ' option[data-value="'+selectList+'"]').remove()
                            }
                        } 
                    }
                    optionString += "<option data-value='"+selectList+"' data-head='"+selectedValue+"' data-price='"+price+"' data-image='"+image+"'>"+selectList+" - "+price+"</option>";       
                    if($.inArray(selectList,attributeArray[selectedValue]) <= -1){
                        attributeArray[selectedValue].push(selectList);
                    }
                    eachCtrlValue.push(selectList);
                }
            }
        });

        if(buttonValue === 'Add Property'){
            if(optionString == ""){
                return false;
            }
            var selectString = "<span class='spanSelect"+cleanString+"'><select class='selection' id='"+cleanString+"' data-id='"+cleanString+"'>";
            selectString += optionString + "</select><a data-id='"+cleanString+"' data-head='"+selectedValue+"' class='edit-attr' href='javascript:void(0)'><span class='glyphicon glyphicon-pencil'></span></a>  <a data-id='"+cleanString+"' data-head='"+selectedValue+"' class='remove-attr' href='javascript:void(0)'><span class='glyphicon glyphicon-remove'></span></a></span>";

            if( !$.trim( $('.select-control-panel-option > .div2').html() ).length ) {

                var rowString = '<div class="col-xs-2 col-sm-2 col-md-2 div1"><input class="qty" onkeypress="return isNumberKey(event)" name="allQuantity" type="text" size=3 value="1" /></div>\
                <div class="col-xs-8 col-sm-8 col-md-8  div2">'+selectString+'</div>\
                <div class="col-xs-2 col-sm-2 col-md-2 div3"><input type="button" class="select-combination  orange_btn3" value="Add" /><input class="set-default" type="checkbox" /></div>';

                $('.select-control-panel-option').empty().append(rowString);
            }
            else{
                var length = $(".select-control-panel-option > .div2 > span >#"+cleanString).length;
                
                if(length <= 0){
                    $('.list-choosen-combination-div > .div-combination > .div2').append(selectString);
                    $('.select-control-panel-option > .div2').append(selectString);
                    $('.list-choosen-combination-div > .div-combination > .div2 > span > .remove-attr').remove();
                }
                else{
                    $('.div2 > span > #'+cleanString).append(optionString);
                }
                if(checked){
                    $('.list-choosen-combination-div > .div-combination > .div2 > span > .selection').empty().append('<option>All Combination</option>');
                }
            } 
        }
        else{

            var currentSelector = $('.select-control-panel-option > .div2 > span > #'+editSelectedId);
            currentSelector.empty().append(optionString).attr("id",cleanString);
            currentSelector.data('id',cleanString);
            currentSelector.parent().children('a').data('head',selectedValue);
            currentSelector.parent().children('a').data('id',cleanString);
            currentSelector.parent().removeClass().addClass('spanSelect'+cleanString)
            
            if(!checked){
                $('.list-choosen-combination-div > .div-combination > .div2 > span > #'+editSelectedId).empty().append(optionString).attr("id",cleanString);
            }

            if(optionString == "")
            {
                $('.spanSelect'+cleanString).remove();
                if( !$.trim( $('.select-control-panel-option > .div2').html() ).length ) {
                    $('.list-choosen-combination-div,.select-control-panel-option ').empty();
                    $('.select-control-panel-option').append('\
                        <div class="col-xs-2 col-sm-2 col-md-2 div1">\
                        <input type="text" value="1" name="allQuantity" size="3" class="qty" onkeypress="return isNumberKey(event)">\
                        </div>\
                        <div class="col-xs-8 col-sm-8 col-md-8 div2"></div>\
                        <div class="col-xs-2 col-sm-2 col-md-2 div3"></div>');
                    resetControlPanel();
                }
            }
            removeDuplicateCombination();
        }
        $(".ctrl").remove();    
        resetControlPanel();
    });

    $(document).on("click",".select-combination",function (){

        currentStringId = "";
        $(".select-control-panel-option > .div2 > span > .selection").each(function() {
            currentStringId += $('.select-control-panel-option > .div2 > span > #'+$(this).data('id') +' option:selected').data('value');
        });

        var checkIfExist = checkCombination(currentStringId);
        if(checkIfExist == false){
            alert('Combination Already Exist!');
            return false;
        }
        var combinationQuantity = $('.select-control-panel-option > .div1 > .qty').val();
        if( combinationQuantity <= 0 || $.isNumeric(combinationQuantity) == false){
            validateRedTextBox('.qty');
            return false;
        }else{
            validateWhiteTextBox('.qty');
        }

        var checked = $('.set-default:checked').length > 0;


        if(!checked){
            $('.set-default').remove(); 
            $(".div-combination > .div2").each(function() {
                $(".select-control-panel-option > .div2 > .selection").each(function() {
                    var id = $(this).data('id');
                    var length = $(".div-combination > .div2 > #"+id).length; 
                    if(length <= 0){
                        $(".div-combination > .div2 ").append($('.select-control-panel-option > .div2 > #'+id).clone());
                    }
                });
            });

            $('.list-choosen-combination-div').append('<div class="div-combination combination'+combinationcnt+'"></div>');
            $('.combination'+combinationcnt).append($('.select-control-panel-option').children().clone());
            $('.list-choosen-combination-div > .div-combination > .div2 > span > .remove-attr').remove();
            $(".select-control-panel-option > .div2 > span > .selection").each(function() {
                var selValue = $('.select-control-panel-option > .div2 > span > #'+$(this).data('id') +' option:selected').text();
                $(".combination"+combinationcnt+" > .div2 > span > #" + $(this).data('id')).val(selValue);
            });

            $('.combination'+combinationcnt +' > .div3').empty().append('<input class="remove-combination btn btn-danger" data-cmbcnt="'+combinationcnt+'" type="button" value="Remove">')
            $('.combination'+combinationcnt).append('<div class="clear"></div>');
            combinationcnt++; 
        }
        else{ 
            $('.select-combination').prop("disabled",true); 
            $('.list-choosen-combination-div').append('<div class="div-combination combinationAll"></div>');
            $('.combinationAll').append($('.select-control-panel-option').children().clone());
            $('.list-choosen-combination-div > .div-combination > .div2 > span > .remove-attr').remove(); 
            $('.list-choosen-combination-div > .div-combination > .div2 > span > .edit-attr').remove();    
            $('.combinationAll > .div2 > span > .selection').empty().append('<option>All Combination</option>');
            $('.combinationAll > .div3').empty().append('<input class="remove-combination btn btn-danger" data-cmbcnt="All" type="button" value="Remove">')
        }
    });

    $(document).on("click",".remove-combination",function (){
        var selector = $(this);
        cmbcnt = selector.data('cmbcnt');
        $('.combination'+cmbcnt).remove();
        if( !$.trim( $('.list-choosen-combination-div').html() ).length ) { 
            $('.select-combination').prop("disabled",false); 
            $('.select-control-panel-option > .div3 > .set-default').remove();
            $('.select-control-panel-option > .div3').append('<input type="checkbox" class="set-default">');
        }
    });

    $(document).on("click",".remove-attr",function (){
        var selector = $(this);
        console.log('you press remove-attr class');
        console.log('confirmation ask');
        var confirmation = confirm('Are you sure you want to remove?');
        var id = selector.data('id');
        if(confirmation === true){
            $('.spanSelect'+id).remove();
            if( !$.trim( $('.select-control-panel-option > .div2').html() ).length ) {
                $('.list-choosen-combination-div,.select-control-panel-option ').empty();
                $('.select-control-panel-option').append('\
                    <div class="col-xs-2 col-sm-2 col-md-2 div1">\
                    <input type="text" name="allQuantity" value="1" size="3" class="qty" onkeypress="return isNumberKey(event)">\
                    </div>\
                    <div class="col-xs-8 col-sm-8 col-md-8 div2"></div>\
                    <div class="col-xs-2 col-sm-2 col-md-2 div3"></div>');
            }
        }
        removeDuplicateCombination();
    });

    $(document).on("click",".edit-attr",function (){
        var selector = $(this);
        var head = editSelectedValue = selector.data('head');
        var id = editSelectedId = selector.data('id'); 

        $('.add-property').val('Save Property').nextAll().remove();
        $('.add-property').after('<input type="button" id="cancel-changes" value="Cancel" />')
        $('#head-data').val(head).trigger("liszt:updated");
        $('.control-panel').empty();
        $(".select-control-panel-option > .div2 > span > #"+id +" option").each(function(){
            var row = appendNewSelectionRow();
            var value = $(this).data('value'); 
            var price = $(this).data('price'); 
            var image = $(this).data('image'); 
            $('.control-panel-'+row +' > .value-section > .select-value-section > #value-data-'+row).val(value);
            $('.control-panel-'+row +' > .price-div > .price'+row).val(price);
            $('.control-panel-'+row +' > .image-div > .imageText'+row).val(image);
            $('.control-panel-'+row +' > .image-div > .image'+row+' img').attr("src",config.base_url+tempDirectory+'other/'+image);
            $('#value-data-'+row).trigger("liszt:updated");
        });
    });

    $(document).on("click","#cancel-changes",function (){
        resetControlPanel();
    });

});
// END of Manipulating of additional attributes

// BRAND SEARCH
var currentRequest = null;
$(document).ready(function(){
    $('#brand_search_drop_content').hide();
    $(document).on('keyup','#brand_sch',function(){

        $('#prod_brand').val(0)
        $('#prod_brand').trigger( "change" );
        jQuery(".brand_sch_loading").hide();
        var searchQuery = $(this).val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        if(searchQuery != ""){
            currentRequest = jQuery.ajax({
                type: "GET",
                url: config.base_url+'product_search/searchBrand', 
                onLoading:jQuery(".brand_sch_loading").html('<img src="'+config.base_url+'assets/images/orange_loader_small.gif" />').show().css('display','inline-block'),
                data: "data="+searchQuery+"&"+csrfname+"="+csrftoken, 
                beforeSend : function(){       
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                    $('.brand_sch_drop_content').show();
                },
                success: function(response) {
                    currentRequest = null;
                    var obj = jQuery.parseJSON(response);
                    var html = '<ul>';
                    if((obj.length)>0){
                        jQuery.each(obj,function(){
                            html += '<li class="brand_result" data-brandid="'+(this.id_brand) +'">'+(this.name)+'</li>' ;                             
                        });
                        html += '<li class="add_brand blue">Use your own brand name</li>';
                        jQuery(".brand_sch_loading").hide();
                    }
                    else{
                        addNewBrand();
                    }

                    html += '</ul>';
                    $("#brand_search_drop_content").html(html);

                    if(!$("#brand_sch").is(":focus")){
                        var available = false;
                        $('#brand_search_drop_content li.brand_result a').each(function(){
                            if($(this).text().toLowerCase() ===  $('#brand_sch').val().toLowerCase()){
                                $(this).click();
                                available = true;
                                return false;
                            }
                        });
                        if(!available){
                            addNewBrand();
                        }
                    }
                }
            });
        }
    });

    $('#brand_search_drop_content').on('click', 'li.brand_result', function(){
        $this = $(this);     
        $('#prod_brand').val($this.data('brandid'));
        $("#brand_sch").val($this.text());
        $('#prod_brand').trigger( "change" );
        $("#brand_sch").trigger( "change" );
        jQuery(".brand_sch_loading").html('<img src="'+config.base_url+'assets/images/check_icon.png" />').show().css('display','inline-block');

        $('#brand_search_drop_content').hide();
    });

    $(document).on("click",".add_brand", function(){    
        if(currentRequest != null) {
            currentRequest.abort();
        }
        addNewBrand();
        $('#brand_search_drop_content').hide();
    });

    $('#brand_sch').focusout(function(){
        var available = false;
        $('#brand_search_drop_content li.brand_result').each(function(){
            if($(this).text().toLowerCase() ===  $('#brand_sch').val().toLowerCase()){
                $(this).click();
                available = true;
                return false;
            }
            if(!available){
                addNewBrand();
            }
        });
    });  

    $('#brand_sch').focus(function() {
        $('#brand_search_drop_content').show();
        $(document).bind('focusin.brand_sch_drop_content click.brand_sch_drop_content',function(e) {
            if ($(e.target).closest('#brand_search_drop_content, #brand_sch').length) return;
            $('#brand_search_drop_content').hide();
        });
    });

    function addNewBrand(){
        $('#prod_brand').val(1)
        $('#prod_brand').trigger( "change" ); 
        jQuery(".brand_sch_loading").html('<img src="'+config.base_url+'assets/images/img_new_txt.png" />').show().css('display','inline-block');
    }
});
// BRAND SEARCH END


// ES_UPLOADER BETA     
var canProceed = true; 
var removeThisPictures = []; var imageAttr = [];
var pictureCountOther  = 0; var primaryPicture = 0;
$(document).ready(function() {

    if(window.FileReader) {   
        badIE = false;
        $('#inputList').append('<input type="file" id="files" class="files active" name="files[]" multiple accept="image/*" required = "required"  /><br/><br/>');
    } else { 
        badIE = true;
        $('#inputList').append('<input type="file" id="files" class="files active" name="files[]" accept="image/*" required = "required"  /><br/><br/>');
    }

    $(".labelfiles").click(function(){
        $('.files.active').click(); 
    });


    var filescnt = 1;
    var imageCustom =  new Array();
    var editRemoveThisPictures = new Array();
    var editPrimaryPicture = 0;
    var response;
    var filescntret;
    var currentCnt;

    $(document).on('change',".files.active",function (e){
        var arrayUpload = new Array();
        var afstart = new Array();

        if(badIE == false){
            var fileList = this.files;
            var anyWindow = window.URL || window.webkitURL; 

            for(var i = 0; i < fileList.length; i++){
                var activeText= ""; 
                var errorValues = "";
                var primaryText = "Make Primary"; 
                var size = fileList[i].size
                var val = fileList[i].name;
                var extension = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
                var objectUrl = anyWindow.createObjectURL(fileList[i]); 
                pictureInDiv = $("#list > div").length;
                if(pictureInDiv == 0){
                    primaryText = "Your Primary";
                    activeText = "active_img";
                    primaryPicture = pictureCount;
                }
                console.log(activeText);

                if((extension == 'gif' || extension == 'jpg' || extension == 'png' || extension == 'jpeg') && size < 5242880){
                    $('#list').append('<div id="previewList'+pictureCount+'" class="new_img upload_img_div '+activeText+' filescnt filescntactive filescnt'+filescnt+'">\
                        <span class="upload_img_con loading_opacity"><img src="'+objectUrl+'"></span>\
                        <a href="javascript:void(0)" class="removepic" data-number="'+pictureCount+'">x</a><br>\
                        <a href="javascript:void(0)" class="makeprimary photoprimary'+pictureCount+'" data-number="'+pictureCount+'">'+primaryText+'</a>\
                        <div class="loadingfiles"></div>\
                        </div>');
                    $('.filescnt'+filescnt+' > .makeprimary').hide(); 
                    $('.filescnt'+filescnt+' > .removepic').hide(); 

                }else{
                    if(size < 5*1024*1024){
                        errorValues += val + "\n(Invalid file type).\n<br>";
                    }
                    else{
                        errorValues += val + "\n(The file size exceeds 5 MB).\n<br>";
                    }
                    removeThisPictures.push(pictureCount);
                }

                af.push(tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension+'||'+extension); 
                afstart.push(tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension); 

                window.URL.revokeObjectURL(fileList[i]);
                arrayUpload.push(pictureCount);
                pictureCount++; 

            }
            console.log(primaryPicture);
            if(errorValues != ""){
                alert("Sorry, the following files cannot be uploaded:", errorValues)
            }

            $(".files").hide();  
            $(".files.active").each(function(){
                $(this).removeClass('active');
            });


            startUpload(pictureCount,filescnt,arrayUpload,afstart);
            filescnt++;
            $('#inputList').append('<input type="file"  id="files" class="files active" name="files[]" multiple accept="image/*" required = "required"  /> ');
            $(this).remove();

        }else{

            var activeText= ""; 
            var errorValues = "";
            var primaryText = "Make Primary"; 
            var val = $(this).val();
            pictureInDiv = $("#list > div").length;
            if(pictureInDiv == 0){
                primaryText = "Your Primary";
                activeText = "active_img";   
                primaryPicture = pictureCount;
            }

            var id = "imgid" + pictureCount;
            imageCustom = document.getElementById('files').value;

            var filename = imageCustom.match(/[^\/\\]+$/);
            extension = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
            switch(extension){
                case 'gif': case 'jpg': case 'png': case 'jpeg':

                $('#list').append('<div id="previewList'+pictureCount+'" class="new_img upload_img_div '+activeText+' filescnt filescntactive filescnt'+filescnt+'"><span class="upload_img_con"><img src="'+imageCustom+'" alt="'+filename+'" style="height:100px;"></span><a href="javascript:void(0)" class="removepic" data-number="'+pictureCount+'">x</a><br><a href="javascript:void(0)" class="makeprimary photoprimary'+pictureCount+'" data-number="'+pictureCount+'">'+primaryText+'</a><div class="loadingfiles"></div></div>');   
                $('.filescnt'+filescnt+' > .makeprimary').hide(); 
                $('.filescnt'+filescnt+' > .removepic').hide(); 
                break;
                default:
                removeThisPictures.push(pictureCount); 
                break;
            }

            af.push(tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension+'||'+extension); 
            afstart.push(tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension); 

            arrayUpload.push(pictureCount);  
            pictureCount++;

            $(".files").hide();  
            $(".files.active").each(function(){
                $(this).removeClass('active');
            });

            startUpload(pictureCount,filescnt,arrayUpload,afstart);
            filescnt++;
        }
    });

    function startUpload(cnt,filescnt,arrayUpload,afstart){

        $('.counter').val(cnt); 
        $('.filescnttxt').val(filescnt); 
        $('#afstart').val(JSON.stringify(afstart));   
        $('#form_files').ajaxForm({
            url: config.base_url+'productUpload/uploadimage',
            type: "POST", 
            dataType: "json",         
            uploadProgress : function(event, position, total, percentComplete) {
                canProceed = false;
                console.log(percentComplete);
            },
            success :function(d) {   
                filescntret = d.fcnt;
                $('.filescnt'+filescntret+' > .loadingfiles').remove();
                $('.filescnt'+filescntret+' > span').removeClass('loading_opacity');
                $('.filescnt'+filescnt+' > .makeprimary').show(); 
                $('.filescnt'+filescnt+' > .removepic').show(); 
                canProceed = true;

                if(d.err == '1'){
                    alert(d.msg);
                    $.each( arrayUpload, function( key, value ) {
                        removeThisPictures.push(value); 
                        $('#previewList'+value).remove();
                    });
                } 
                if(badIE == true){
                    $(".files").remove();
                    $('#inputList').append('<input type="file"  id="files" class="files active" name="files[]" accept="image/*" required = "required"  /> ');
                }
            },
            error: function (request, status, error) {
                response = request.responseText;
                if (response.toLowerCase().indexOf("1001") >= 0){
                    alert('Sorry, the images you are uploading are too large.');
                }
                else{
                    alert('Sorry, we have encountered a problem.','Please try again after a few minutes.');
                }
                $.each( arrayUpload, function( key, value ) {
                    removeThisPictures.push(value); 
                    $('#previewList'+value).remove();
                });

                canProceed = true;
                if(badIE == true){
                    $(".files").remove();
                    $('#inputList').append('<input type="file"  id="files" class="files active" name="files[]"  accept="image/*" required = "required"  /> ');
                }
            }
        }); 
        $('#form_files').submit();
    }

    $(document).on('click','.remove-attr-image',function(e){
        var selector = $(this);
        currentCnt = selector.data('cnt');
        $('.imageText'+currentCnt).val('');
        $('.image'+currentCnt+' > img').attr("src",config.base_url+"assets/images/img_upload_photo.jpg");
    });

    $(document).on('click',".attr-image",function (e){
        var selector = $(this);
        currentCnt = selector.data('cnt');
        $('.attr-image-input').click(); 
    });

    $(document).on('change',".attr-image-input",function (e){

        var val = $(this).val();
        extension = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
        picName = tempId+'_'+memberId+'_'+fulldate+pictureCountOther+'o.'+extension;
        $('#other_files').ajaxForm({
            url: config.base_url+'productUpload/uploadimageOther',
            type: "POST", 
            dataType: "json", 
            beforeSubmit : function(arr, $form, options){
                arr.push({name:'pictureName', value:picName});
                arr.push({name:'pictureCount', value:pictureCountOther});
            },
            uploadProgress : function(event, position, total, percentComplete) {

            },
            success :function(d) {   
                imageAttr.push(picName);
                $('.imageText'+currentCnt).val(picName);

                $('.image'+currentCnt+' > img').attr("src",config.base_url+tempDirectory+'other/'+picName);
                console.log(currentCnt);
            } 
        }).submit(); 
        pictureCountOther++; 
    });


    $(document).on('click',".removepic",function (){
        /* 
        * Altered ON: 5/6/2014
        * Altered BY: SAM (for edit functionlity)
        * Changed ".photoprimary"+idNumber selector to $(this) + sibling/closest selectors
        * SEE REVISION 1529 for original code
        */
        var text = $(this).siblings('.makeprimary').first().text();
        var idNumber = $(this).data('number');
        removeThisPictures.push(idNumber);

        $(this).closest('.upload_img_div').remove();
        if(text === "Your Primary"){
            var first_img_div = $("#list > div");
            var primary_control_anchor = $("#list > div:first > .makeprimary");
            primaryPicture = 0;
            primary_control_anchor.text('Your Primary');     
            first_img_div.addClass("active_img"); 
        }
        console.log('Primary Picture: ' + primaryPicture);
        console.log('Picture to remove: ' + removeThisPictures); 
    });

    $(document).on('click','.makeprimary',function(){
        /* 
         * Altered ON: 5/6/2014
         * Altered BY: SAM (for edit functionlity)
         * Changed ".photoprimary"+idNumber selector to $(this) + sibling/closest selectors
         * SEE SVN REVISION 1529 for original code
         */

        if($(this).closest('.upload_img_div').hasClass('new_img')){
            primaryPicture = $(this).data('number');
            editPrimaryPicture = -1;
        }
        else if($(this).closest('.upload_img_div').hasClass('edit_img')){
            editPrimaryPicture = $(this).data('imgid');
            primaryPicture = 0;
        }
        else{
            return false;
        }
        $(".makeprimary").text('Make Primary');
        $(".upload_img_div").removeClass("active_img");
        $(this).text('Your Primary');
        $(this).closest('.upload_img_div').addClass("active_img");
    });
});
// ES_UPLOADER BETA END

$(function() {

});

    // SAVING AND PROCEED 
    $(document).on('click','#proceed_form',function(){

        $('#form_product').validate({  
            rules: {
                prod_title: {
                    required: true, 
                },
                prod_price :{
                    required: true,
                    number: true,
                    range :[1,Infinity]
                },
                prod_condition: {
                    required: true
                },
                prod_description:{
                    required: true
                }
            },
            messages: {
                prod_title: "Enter title for your listing",
                prod_condition: "Select condition",
                prod_price: {
                    required: "Enter a valid price",
                    range : "Price must not be less than 0"
                }
            }
        });

        if( !$.trim( $('.list-choosen-combination-div').html() ).length ) {
            var qtySelector = $(".select-control-panel-option > .div1 > .qty");
            var soloQty = qtySelector.val();
            if( soloQty <= 0 || $.isNumeric(soloQty) == false){
                validateRedTextBox(qtySelector);
                qtySelector.focus();
                return false;
            }else{
                validateWhiteTextBox(qtySelector);
            }
        }
        else{
            error = 0;
            $(".list-choosen-combination-div > .div-combination > .div1 > .qty").each(function() {
                var combinationQuantity = $(this).val();
                if( combinationQuantity <= 0 || $.isNumeric(combinationQuantity) == false){
                    validateRedTextBox(this);
                    this.focus();
                    error++;
                }else{
                    validateWhiteTextBox(this);
                }
            });
            if(error > 0){
                return false;
            }  
        }


        window.onbeforeunload=null;
        $('.arrayNameOfFiles').val(JSON.stringify(af));
        tinyMCE.triggerSave();   
        proceedStep3('processing');     
        $('#form_product').submit();
    });

    // back to step1 
    $(document).on('click','.step1_link',function(){
        if(currentRequest != null) {
            $('#prod_brand').val(1)
            $('#prod_brand').trigger( "change" );
        }
        confirm_unload = false;
        $('#edit_step1').submit();
    });

    function processCombination()
    {
        var completeCombination = [];   
        $(".div-combination").each(function(){
            var currentDiv = $(this);
            var selectList = currentDiv.children('.div2').children('span');

            var eachData = {};
            var eachCombination = {};

            selectList.each(function(){
                var currentSelect = $(this);
                var optionSelected = $(this).children('select').children('option:selected');
                var head = optionSelected.data('head');
                var value = optionSelected.data('value');
                var price = optionSelected.data('price');
                eachData[head] = value; 
            });

            eachCombination.quantity = currentDiv.children('.div1').children('.qty').val(); 
            eachCombination.data = eachData; 
            completeCombination.push(eachCombination);
        });

        return JSON.stringify(completeCombination);
    }

    function processAttributes()
    {
        var span = $('.select-control-panel-option > .div2 > span');
        var completeAttributes = {}; 
        span.each(function(){
            var currentSelect = $(this);
            var select = $(this).children('select').children('option');
            var currentHead;
            var eachHead = [];
            select.each(function(){
                var eachValue = {};
                var optionSelected = $(this);
                var head = currentHead = optionSelected.data('head');
                var value = optionSelected.data('value');
                var price = optionSelected.data('price');
                var image = optionSelected.data('image');
                eachValue.value =value;
                eachValue.price =price;
                eachValue.image =image;
                eachHead.push(eachValue);
            });
            completeAttributes[currentHead] =  eachHead;
            
        });

        return JSON.stringify(completeAttributes);
    }


    function proceedStep3(url)
    {
        $('#form_product').ajaxForm({ 
            url: url,
            dataType: "json",
            beforeSubmit : function(arr, $form, options){

                var combination = processCombination();
                var attributes = processAttributes(); 

                var percentVal = '0%';
                $('.percentage').html(percentVal);
                $( ".button_div" ).hide();
                $( ".loader_div" ).show();
                arr.push({name:'primaryPicture', value:primaryPicture});
                arr.push({name:'removeThisPictures', value:JSON.stringify(removeThisPictures)});
                arr.push({name:'combination',value:combination});
                arr.push({name:'attributes',value:attributes});
            },
            uploadProgress : function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                $('.percentage').empty();
                if(percentComplete >= 100){
                    percentVal = '100%'
                    $('.percentage').html(percentVal);
                }else{
                    $('.percentage').html(percentVal);
                }
            },
            success :function(d) { 
                $('.percentage').html('100%');
                if (d.e == 1) {
                    $('#prod_h_id').val(d.d); 
                    document.getElementById("hidden_form").submit();
                } else {
                    $( ".button_div" ).show();
                    $( ".loader_div" ).hide();
                    $('.percentage').empty();
                    alert(d.d);
                } 
            },
            error: function (request, status, error) {
                $( ".button_div" ).show();
                $( ".loader_div" ).hide();
                $('.percentage').empty();
                response = request.responseText;
                if (response.toLowerCase().indexOf("1001") >= 0){
                    alert('Something Went Wrong. The images you are uploading in [OTHER ATTRIBUTES] are too large.');
                }else{
                    alert('Something Went Wrong. Please try again.');
                }
            } 
        }); 
    }

    function saveAsDraftProceed(url)
    {
        $('.arrayNameOfFiles').val(JSON.stringify(af));
        tinyMCE.triggerSave(); 
        $('#form_product').ajaxForm({ 
            url: url,
            dataType: "json",
            async: false,
            beforeSubmit : function(arr, $form, options){
                var combination = processCombination();
                var attributes = processAttributes(); 
                arr.push({name:'primaryPicture', value:primaryPicture});
                arr.push({name:'removeThisPictures', value:JSON.stringify(removeThisPictures)});
                arr.push({name:'combination',value:combination});
                arr.push({name:'attributes',value:attributes});
                arr.push({name:'savedraft',value:'1'});
            },success :function(d) { 
                $("#form_product").attr("action", "/sell/edit/processing");
                $("#form_product").append('<input type="hidden" name="p_id" id="p_id" value="'+d.d+'">');
            }
        }).submit(); 
    }
 
jQuery(function($){

    function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
    // To disable f5
    /* jQuery < 1.7 */
    $(document).bind("keydown", disableF5);
    /* OR jQuery >= 1.7 */
    $(document).on("keydown", disableF5);

    var confirm_unload = true;
    window.onbeforeunload = function (e) { 
        if(confirm_unload){ 
            if(isEdit == 1){
                saveAsDraftProceed(); 
            }
        }
    };
 
    $(window).data('beforeunload',window.onbeforeunload);  

    $(document).on('mouseover mouseout','a[href="javascript:void(0)"]', function(event) {
        if (event.type == 'mouseover') {
            window.onbeforeunload=null;
        }
        else{
            window.onbeforeunload=$(window).data('beforeunload');
        }
    });

    $(document).on('click', '.prevent', function(event){
        confirm_unload = false;
        var loc = $(this).attr("href");       
        event.preventDefault(); 
        askDraft(loc);
    });                 
});

    function askDraft(location)
    {
        if(isEdit == 0){
            $("#question").dialog({
                resizable: false,
                height: 100,
                width: 530,
                modal: true, 
                open: function() {
                },
                buttons: {
                    "Yes Save as Draft and Leave": function() {
                        $(".ui-dialog-title").text('Please wait while saving your data...'); 
                        saveAsDraftProceed();
                        window.location = location;
                    },
                    "Dont save as draft just leave": function() {
                        $(".ui-dialog-title").text('Please wait...'); 
                        window.location = location;
                    },
                    "Dont leave": function() { 
                        $(this).dialog("close");
                    }
                },
                "title": "Your about to leave this page without saving. Do you want this to save as draft?"
            });   
        }
        else{
            $("#question").dialog({
                resizable: false,
                height: 100,
                width: 530,
                modal: true, 
                open: function() {
                },
                buttons: {
                    "Ok I understand": function() {
                        $(".ui-dialog-title").text('Please wait while saving your data...'); 
                        saveAsDraftProceed();
                        window.location = location;
                    }
                },
                "title": "Remember this item is in your draft you can edit this in step1 page."
            });
        }

    }
