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
    var menuOffset = $('.vendor-sticky-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
    $(document).bind('ready scroll', function() {
        var docScroll = $(document).scrollTop();
        if (docScroll >= 455){
            if (!$('.vendor-sticky-nav').hasClass('sticky-nav-fixed')) {
                $('.vendor-sticky-nav').addClass('sticky-nav-fixed').css({
                    top: '-155px'
                }).stop().animate({
                    top: 0
                }, 500);
            }
            $('.vendor-content-wrapper').addClass('fixed-vendor-content');
        }
        else{
            $('.vendor-sticky-nav').removeClass('sticky-nav-fixed').removeAttr('style');
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
        var action =  (searchType == 1) ? "" : "/search/search.html";
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
            url: config.base_url+'memberpage/'+'updateVendorDetails',
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
                    $(".storeName").html(storName);
                    $("#placeStock > strong").html(citySelected+', '+stateRegionSelected);
                    $("#contactContainer").html((mobileNumber == "") ? "N/A" : mobileNumber);

                    // Update custom attr origval for "Cancel" functionality
                    $("#storeNameTxt").attr('data-origval', data.new_data.store_name);
                    $("#mobileNumberTxt").attr('data-origval', data.new_data.mobile);
                    $(".stateregionselect").attr('data-origval', data.new_data.state_region_id);
                    $(".cityselect").attr('data-origval',data.new_data.city_id);
                }
                else{
                    // Display error
                    alert(data.error);
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
                url: config.base_url+'memberpage/'+'removeUserImage', 
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


    $("#imgupload").on("change", function(){
        var oldIE;
        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0; 
        formAction = (imageUploadType == "avatar") ? "upload_img" : "banner_upload";

        if ($('html').is('.ie6, .ie7, .ie8, .ie9')){
            oldIE = true;
        }

        if (oldIE || isSafari){
            document.getElementById('form_image').action = '/memberpage/'+action;
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
                    document.getElementById('form_image').action = '/memberpage/'+formAction;
                    $('#form_image').submit();
                    $.modal.close();
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
            $.post(config.base_url+"memberpage/vendorSubscription", $(form).serializeArray(), function(data){
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
            $.removeCookie('es_vendor_subscribe');
            $.cookie('es_vendor_subscribe', vendorLink, {path: '/'});
            window.location.href = config.base_url + 'login';
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
            $.removeCookie('es_vendor_subscribe');
        }
    });

})(jQuery);
