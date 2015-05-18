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
            $('.autocomplete-suggestions').hide();
            $('.vendor-content-wrapper').addClass('fixed-vendor-content');
        }
        else{
            $('.nav-suggestion').hide();
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
        $(this).closest(".search-form").submit();
    });

    $(document).on('submit','form.search-form',function(){
        var searchType = $(this).find('.search-type').val();
        var action =  (searchType == 1) ? "/" + $('#vendor-slug').val() : "/search/search.html";
        $(this).attr("action",action);
    });
   
    $(document).on('click','#banner-save-changes.clickable',function() {
        
        var button = $(this);
        button.removeClass('clickable');
        
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
 
        var storName = $("#storeNameTxt").val();
        var mobileNumber = $("#mobileNumberTxt").val(); 
        var stateRegion = $(".stateregionselect").val();
        var city = $('.cityselect').val();
        var stateRegionSelected = $(".stateregionselect option:selected").html();
        var citySelected = $(".cityselect option:selected").html();

        stateRegion = (stateRegion === null) ? 0 : stateRegion;
        city = (city === null) ? 0 : city;
        stateRegionSelected = (typeof stateRegionSelected === "undefined") ? $('.stateregionselect option[value="0"]') : stateRegionSelected;
        citySelected = (typeof citySelected === "undefined") ? $('.cityselect option[value="0"]') : citySelected;

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
                    if(!isDesktop) {
                        window.reload();
                    }
                }
                else{
                    // Display error
                    var errString = "";
                    $.each(data.error, function(key,errorMessage){
                        errString = errorMessage[0];
                        return false;
                    });
                    alert(escapeHtml(errString));
                }
                button.addClass('clickable');
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


    if(window.FileReader){
        // I intentionally add ie 10 into restriction because of error in simplemodal
        // see reference https://github.com/ericmmartin/simplemodal/pull/34
        if (navigator.appVersion.indexOf("MSIE 10") != -1){
            badIE = true;
        }
        else{
            badIE = false;
        }
    }
    else{
        badIE = true;
    }

    var max_upload_size = $("#max-upload-size").val()  * 1000;
    var max_upload_height = $("#max-upload-height").val();
    var max_upload_width = $("#max-upload-width").val(); 

    var $windowProfile = $(window);
    var isDesktop = true;
    function checkWidthProfile() {
        var windowsizeProfile = $windowProfile.width();
        if (windowsizeProfile > 440) {
            isDesktop = true;
            $( ".btn-cancel-me" ).trigger("click");
        }
        else{
            isDesktop = false;
            $( ".btn-cancel-me-wide" ).trigger("click");
        }
    }

    // Execute on load
    checkWidthProfile();
    // Bind event listener
    $windowProfile.resize(checkWidthProfile);

    $(document).on('change','#imgupload',function(){  
        formAction = (imageUploadType == "avatar") ? "upload_img" : "banner_upload";
        if(isDesktop === true) {
            imageprev(this);
        }
        else {
            $(".loader-upload").css("display","block");        
            submitForm();
        }
    });

    var imageprev = function(input) {

        if(badIE == false){
            if (input.files 
                && input.files[0] 
                && input.files[0].type.match(/(gif|png|jpeg|jpg)/g) 
                && input.files[0].size <= max_upload_size) {
                var reader = new FileReader();
                reader.onload = function(e){
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function(){
                        width = this.width;
                        height = this.height;
                        $('#user_image_prev').attr('src', this.src);
                        if(width >10 && height > 10 && width <= max_upload_width && height <= max_upload_height){
                            deploy_imageprev();
                        }
                        else if(width > max_upload_width || height > max_upload_height){
                            alert('Failed to upload image. Max image dimensions: '+max_upload_width+'px x '+max_upload_height+'px');
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
        else{
            submitForm();
        }
    }

    var submitForm = function(){
        var action = '/store/'+formAction;
        var $editContainer = $('.edit-profile-photo > div');
        var $defaultValue = $editContainer.html();
        var $editBanner = $('#banner_edit');
        var $editBannerDefault = $('#banner_edit').html();

        $('#form_image').ajaxForm({
            url: action,
            dataType: "json",
            beforeSubmit : function(){
                $('.avatar-modal-content').hide();
                $('.avatar-modal-loading').fadeIn();

                if(badIE){
                    if(formAction === 'banner_upload'){
                        $('#banner_edit').children('img').attr('src', $('.avatar-modal-loading > img').attr('src'));
                        $('#banner_edit').children('h4').html('Uploading please wait..');
                    }
                    else if(formAction === 'upload_img'){
                        $editContainer.children('img').attr('src', $('.avatar-modal-loading > img').attr('src'));
                        $editContainer.children('span').html('Uploading please wait..');
                        $('.edit-profile-photo-menu').hide();
                    }
                }
            },
            uploadProgress : function(event, position, total, percentComplete) {
                console.log(percentComplete);
            },
            success :function(xhrResponse) { 
                $(".loader-upload").css("display","none");
                if(xhrResponse.isSuccessful){
                    if(formAction === 'banner_upload'){
                        $(".vendor-main-bg").css({ "background-image" : "url('"+xhrResponse.banner+"')"});
                    }
                    else if(formAction === 'upload_img'){
                        var avatarImage = $('img.avatar-image');
                        var smallAvatar = $('img#vendor-profile-avatar');
                        avatarImage.attr('src',config.assetsDomain + '.' + xhrResponse.image);
                        smallAvatar.attr('src',config.assetsDomain + '.' + xhrResponse.smallImage);
                    }
                    $.modal.close();
                    $('#banner-cancel-changes').trigger('click');
                }
                else{
                    $.modal.close();
                    $('#banner-cancel-changes').trigger('click');

                    if(xhrResponse.message == ""){
                        alert('Sorry, we are encountering a problem right now. Please try again in a few minutes.');
                    }
                    else{
                        alert(xhrResponse.message);
                    }
                }
                $editContainer.html($defaultValue);
                $editBanner.html($editBannerDefault);
            },
        }).submit(); 
    };

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
                    submitForm();
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
                    alert(escapeHtml(obj.error));
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

    var $mobilesearchbtn= $(".mobile-search");
    var $mobilesearchform= $(".search-form");
    var $mobilevendorcart= $(".mobile-vendor-cart");
    var $mobilecartitemlist= $(".header-cart-item-list");
    var $mobileloginbtn= $(".vendor-out-con2");
    var $mobileloginuser= $(".mobile-user-login");

    $(document).mouseup(function (e) {

        if (!$mobilesearchform.is(e.target) 
            && $mobilesearchform.has(e.target).length === 0)
        {
           $mobilesearchform.hide(1);
        }

        if (!$mobilecartitemlist.is(e.target) 
            && $mobilecartitemlist.has(e.target).length === 0)
        {
           $mobilecartitemlist.hide(1);
        }

        else (!$mobileloginuser.is(e.target) 
            && $mobileloginuser.has(e.target).length === 0)
        {
           $mobileloginuser.hide(1);
        }

    });

    $mobilesearchbtn.click(function() {
        $mobilesearchform.show();
    });

    $mobilevendorcart.click(function() {
        $mobilecartitemlist.show();
    });

    $mobileloginbtn.click(function() {
        $mobileloginuser.show();
    });
    
    $('#modal-edit-trigger').click(function (e) {
        $('.edit-profile-mobile').modal();
        $( ".edit-profile-modal" ).closest(".simplemodal-container").css( "height", "300px" ).css("background", "#fff").css("border-radius", "4px").css("padding-top", "4px").removeAttr("id");
        return false;
    });

    $(".btn-change-cover-photo-mobile").click(function() {
        $("#banner_edit").trigger("click");
    });

    $(".btn-cancel-me-wide").click(function() {
        $(".simplemodal-close").trigger("click");
    });

    $(".chosen-container-single:eq(1)").attr("data-toggle","0");
    $(".chosen-container-single:eq(2)").attr("data-toggle","1");
    $(window).on('load resize', function(){
        var windowVendor = $(window).width();
        var windowMaxDesktopWidth = 991;
        if(windowVendor <= windowMaxDesktopWidth){
            $(".chosen-container-single").bind("click");
            $(".chosen-container-single").click(function() {
                if($(this).data("toggle") === 0) {
                    $(".followers-circle").toggle();
                }
            });
        }
        if(windowVendor > windowMaxDesktopWidth){
            $(".followers-circle").css("display", "inline");
            $(".chosen-container-single").unbind("click");
        }
    });

})(jQuery);

function proceedPayment(obj)
{
    window.location.replace("/payment/review");
}
