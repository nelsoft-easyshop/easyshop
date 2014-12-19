// global jsonCity
var jsonCity = jQuery.parseJSON($('#json_city').val());

(function ($) {

    // Numeric characters only. Disable negative
    $('#mobileNumberTxt').numeric({negative : false});

    // Disable decimal point
    $('#mobileNumberTxt').on('keypress', function(e){
        var code = e.keyCode || e.which;
        return (code != 46);
    });

})(jQuery);

(function ($) {

    //create a stick nav
    var menuOffset = $('.persistent-nav-container')[0].offsetTop; // replace #menu with the id or class of the target navigation
    $(document).bind('ready scroll', function() {
        var docScroll = $(document).scrollTop();
        if (docScroll >= 455){
            if (!$('.persistent-nav-container').hasClass('sticky-nav-fixed')) {
                $('.persistent-nav-container').addClass('sticky-nav-fixed').css({
                    top: '-155px'
                }).stop().animate({
                    top: 0
                }, 500);
            }
            $('.vendor-content-wrapper').addClass('fixed-vendor-content');
        }
        else{
            $('.persistent-nav-container').removeClass('sticky-nav-fixed').removeAttr('style');
            $('.vendor-content-wrapper').removeClass('fixed-vendor-content');
        }
    });

    var $edit_profile_photo = $(".edit-profile-photo");
    var $edit_profile_photo_menu = $(".edit-profile-photo-menu");

    $(document).mouseup(function (e) {

        if (!$edit_profile_photo_menu.is(e.target) // if the target of the click isn't the container...
            && $edit_profile_photo_menu.has(e.target).length === 0) // ... nor a descendant of the container
        {
           $edit_profile_photo_menu.hide(1);
        }

    });

    $edit_profile_photo.click(function() {
        $edit_profile_photo_menu.show();
    });

    $(document).on('click','#edit-profile-btn',function() {
        $('#display-banner-view').hide();
        $('#edit-banner-view').show();

        var stateRegionSelect = $('.stateregionselect');
        var citySelect = $('.cityselect');
        stateRegionSelect.val(stateRegionSelect.attr('data-origval')).trigger("chosen:updated");
        cityFilter(stateRegionSelect, citySelect);
        citySelect.val(citySelect.attr('data-origval')).trigger("chosen:updated");
    });

    $(document).on('click','#banner-cancel-changes',function() {
        var storeNameField = $('#storeNameTxt');
        var mobileField = $('#mobileNumberTxt');
        var stateRegionSelect = $('.stateregionselect');
        var citySelect = $('.cityselect');

        $('#display-banner-view').show();
        $('#edit-banner-view').hide();

        // Reset displayed values to original
        storeNameField.val(storeNameField.attr('data-origval'));
        mobileField.val(mobileField.attr('data-origval'));
        stateRegionSelect.val(stateRegionSelect.attr('data-origval')).trigger("chosen:updated");
        cityFilter(stateRegionSelect, citySelect);
        citySelect.val(citySelect.attr('data-origval')).trigger("chosen:updated");
    });

    // Search button click
    $(document).on('click','.submitSearch',function() {
        var searchType = $(this).closest(".search-form").find('.search-type').val();
        var action =  (searchType == 1) ? "/" + $('#vendor-slug').val() : "/search/search.html";
        $(this).closest(".search-form").attr("action",action);
        $(this).closest(".search-form").submit();
    });
   
    $(document).on('click','#banner-save-changes',function() {
        
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
 
        // get all variables
        var storName = $("#storeNameTxt").val();
        var mobileNumber = $("#mobileNumberTxt").val();
        var stateRegion = $(".stateregionselect").val();
        var city = $('.cityselect').val();
        var stateRegionSelected = $(".stateregionselect option:selected").html();
        var citySelected = $(".cityselect option:selected").html();
        changeSlug = jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: '/store/updateStoreBannerDetails',
            data: "vendor_details=1&store_name="+storName+
                    "&mobile="+mobileNumber+
                    "&stateregion="+stateRegion+
                    "&city="+city+
                    "&"+csrfname+"="+csrftoken,
            success: function(data){
                if(data.result){
                    // change all related display
                    $('#display-banner-view').show();
                    $('#edit-banner-view').hide();
                    $(".storeName").html(escapeHtml(storName));

                    if( stateRegion === "0" && city === "0" ){
                        $("#placeStock > strong").html("Location not set");
                    }
                    else{
                        $("#placeStock > strong").html(escapeHtml(citySelected)+', '+escapeHtml(stateRegionSelected));
                    }
                    
                    $("#contactContainer").html((mobileNumber == "") ? "N/A" : mobileNumber);

                    // Update custom attr origval for "Cancel" functionality
                    var escapedStoreName = escapeHtml(data.new_data.store_name);
                    var escapedMobile = escapeHtml(data.new_data.mobile);
                    var validatedRegionId = parseInt(data.new_data.state_region_id);
                    var validatedCityId = parseInt(data.new_data.city_id);
                    
                    $("#storeNameTxt").attr('data-origval', escapedStoreName);
                    $("#mobileNumberTxt").attr('data-origval', escapedMobile);
                    $(".stateregionselect").attr('data-origval', validatedRegionId);
                    $(".cityselect").attr('data-origval', validatedCityId);
                    
                    var userDetailsContainer = $('#user-detail-partial');
                    if(userDetailsContainer.length){
                        
                        $('#storeName').val(escapedStoreName);
                        $('#postStoreName').val(escapedStoreName);
                        $('#validatedStoreName').html(escapedStoreName);
                        
                        $('#contactNo').val(escapedMobile);
                        $('#postContactNo').val(escapedMobile);
                        $('#validatedContactNo').html(escapedMobile);
                        
                        $('#streetAddr').val('');
                        $('#validatedStreetAddr').val('');
                        $('#postStreetAddr').val('');
                        
                        $('#regionSelect').val(validatedRegionId);  
                        var regionName = $('#regionSelect :selected').html();
                        $('#postRegion').val(regionName);
                        
                        setTimeout(function() {
                            $('#citySelect').val(validatedCityId);
                            var cityName = $('#citySelect :selected').html();
                            $('#postCity').val(validatedCityId);
                            $('#full-address-display').html(cityName+','+regionName);
                        }, 300);

                        $('#editIconOpen').trigger('click');
                    }
                 
                    
                }
                else{
                    // Display error
                    var errString = "";
                    $.each(data.error, function(k,v){
                        errString = errString + v + "<br>";
                    });
                    alert(errString);
                }
            } 
        });
    });
    

    // Change of state region 
    $('.address_dropdown').chosen({width:'200px'});

    var cityFilter = function(stateregionselect,cityselect){
        var stateregionID = stateregionselect.find('option:selected').attr('value'); 
        cityselect.find('option.echo').remove();
        if(stateregionID in jsonCity){ 
            $('.cityselect').empty();
            jQuery.each(jsonCity[stateregionID], function(k,v){
                $('.cityselect').append('<option value="'+k+'">'+v+'</option>'); 
            });
        }
        else{
            $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');
        }

        $('.cityselect').trigger('chosen:updated');
    } 
    
    $('.cityselect').empty().append('<option value="0">--- Select City ---</option>');

    $('.stateregionselect').on('change', function(){
        var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
        cityselect.val(0);
        cityFilter( $(this), cityselect );
    });

    $('.stateregionselect,.cityselect').trigger('change');

    // Change Profile Picture
    var jcrop_api, width, height, formAction;
    
    $('#avatar_edit').click(function(){
        imageUploadType = "avatar";
        $('#imgupload').click();
    });

    $('#avatar_remove').click(function(){
        confirmRemove = confirm("Are you sure you want to remove you profile picture?");
        if(confirmRemove){
            changeSlug = jQuery.ajax({
                type: "GET",
                dataType: "JSON",
                url: '/memberpage/'+'removeUserImage', 
                success: function(data){
                    window.reload();
                }
            });
        }
        
        return false;
    });


    $('#banner_edit').click(function(){
        imageUploadType = "banner";
        $('#imgupload').click();
    });

    $(document).on('change','#imgupload',function(){ 
        var oldIE;
        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0; 
        formAction = (imageUploadType == "avatar") ? "upload_img" : "banner_upload";

        if ($('html').is('.ie6, .ie7, .ie8, .ie9')){
            oldIE = true;
        }

        if (oldIE || isSafari){
            document.getElementById('form_image').action = '/store/'+formAction;
            $('#isAjax').val('false');
            $('#form_image').submit();
        }
        else{
            imageprev(this);
        }
    });

    var imageprev = function(input) {

        if (input.files 
            && input.files[0] 
            && input.files[0].type.match(/(gif|png|jpeg|jpg)/g) 
            && input.files[0].size <= 5000000) {

            var reader = new FileReader();

            reader.onload = function(e){
                var image = new Image();
                image.src = e.target.result;
                image.onload = function(){
                    width = this.width;
                    height = this.height;
                    $('#user_image_prev').attr('src', this.src);
                    if(width >10 && height > 10 && width <= 5000 && height <= 5000){
                        deploy_imageprev();
                    }
                    else if(width > 5000 || height > 5000){
                        alert('Failed to upload image. Max image dimensions: 5000px x 5000px');
                    }
                    else{
                        $('#div_user_image_prev span:first').html('Preview');
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
        else{
            alert('You can only upload gif|png|jpeg|jpg files at a max size of 5MB! ');
            return false;
        }
    }

    var deploy_imageprev = function(){
        $('#div_user_image_prev').modal({
            escClose: false,
            containerCss:{
                maxWidth: 600,
                minWidth: 505,
                maxHeight: 600
            },
            onShow: function(){
                $('#div_user_image_prev button').on('click', function(){
                    var action = '/store/'+formAction;
                    $('#form_image').ajaxForm({
                        url: action,
                        dataType: "json",
                        beforeSubmit : function(){
                            $('.avatar-modal-content').hide();
                            $('.avatar-modal-loading').fadeIn();
                        },
                        uploadProgress : function(event, position, total, percentComplete) {
                            console.log(percentComplete);
                        },
                        success :function(xhrResponse) { 
                            if(xhrResponse.isSuccessful){
                                if(formAction === 'banner_upload'){
                                    var bannerImage = $('img.banner-image');
                                    bannerImage.attr('src',xhrResponse.banner);
                                }
                                else if(formAction === 'upload_img'){
                                    var avatarImage = $('img.avatar-image');
                                    avatarImage.attr('src',xhrResponse.image);
                                }
                                $.modal.close();
                                $('#banner-cancel-changes').trigger('click');
                            }
                            else{
                                $.modal.close();
                                $('#banner-cancel-changes').trigger('click');
                                alert('Sorry, we are encountering a problem right now. Please try again in a few minutes.');
                            }
                            
                        },
                    }).submit(); 
                });

                if(imageUploadType  == "avatar"){
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
                }
                else{
                    jcrop_api = $.Jcrop($('#user_image_prev'),{
                        aspectRatio: 1475/366,
                        allowSelect: false,
                        setSelect:[0,0,width*0.5,height*0.5],
                        boxWidth: 500,
                        boxHeight: 500,
                        minSize: [width*0.3,height*0.3],
                        trueSize: [width,height],
                        onChange: showCoords,
                        onSelect: showCoords,
                        onRelease: resetCoords
                    });
                }
                this.setPosition();
            },
            onClose: function(){
                $('#user_image_prev').attr('src', '');
                $("#imgupload").replaceWith($("#imgupload").clone(true))
                resetCoords();
                jcrop_api.destroy();
                $('#div_user_image_prev span').after('<img src="" id="user_image_prev">');
                $.modal.close();
            }
        });

        $('#div_user_image_prev').parents('.simplemodal-container').addClass('edit-banner-container');
    }

    var showCoords = function(c)
    {
        $('#image_x').val(c.x);
        $('#image_y').val(c.y);
        $('#image_w').val(c.w);
        $('#image_h').val(c.h);
    }

    var resetCoords = function()
    {
        $('#image_x').val(0);
        $('#image_y').val(0);
        $('#image_w').val(0);
        $('#image_h').val(0);
    }

})(jQuery);


/**
 *  Subscription Functions
 */
(function ($){

    $('.subscription_btn').on('click', function(){
        var $this = $(this);
        var form = $(this).siblings('form');
        var sibling = $(this).siblings('.subscription_btn');
        var isLoggedIn = parseInt($('#is_loggedin').val());
        var vendorLink = form.find('input[name="vendorlink"]').val();

        if(isLoggedIn){
            $.post("/memberpage/vendorSubscription", $(form).serializeArray(), function(data){
                try{
                    var obj = jQuery.parseJSON(data);
                }
                catch(e){
                    alert('There was an error while processing your request. Please try again later.');
                    return false;
                }

                if(obj.result === 'success'){
                    $this.hide();
                    sibling.show();
                }
                else{
                    alert(obj.error);
                }
            });
        }
        else{
            $.removeCookie('es_vendor_subscribe', {path: '/'});
            $.cookie('es_vendor_subscribe', vendorLink, {path: '/'});
            window.location.href = '/login';
        }

    });

    $(document).ready(function(){
        var vendorLink = $.cookie('es_vendor_subscribe');
        var logInStatus = parseInt($('#is_loggedin').val());
        var subscribeStatus = $('#subscribe_status').val();
        var vendorName = $('#vendor_name').val();

        if( typeof vendorLink !== "undefined" && logInStatus && subscribeStatus === "unfollowed"){
            $('#follow_btn').trigger('click');
            alert("You are now following " + vendorName + "'s store!");
            $.removeCookie('es_vendor_subscribe',{path: '/'});
        }
    });

})(jQuery);

function proceedPayment(obj)
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    $.ajax({
        async: true,
        url: "/payment/cart_items",
        type: "POST",
        dataType: "json",
        data: {csrfname: csrftoken},
        success: function (data) {
            if (data == true) {
                window.location.replace("/payment/review");
            } else {
                alert(data, 'Remove these items from your cart to proceed with your checkout.');
            }
        }
    });
}
