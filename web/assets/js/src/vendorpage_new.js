
var memconf = {
    ajaxStat : null,
    csrftoken: $("meta[name='csrf-token']").attr('content'),
    csrfname: $("meta[name='csrf-name']").attr('content'),
    vid: $('#vid').val(),
    vname: $('#vname').val(),
    order: 1,
    orderBy: 1,
    condition: "",
    lprice: "",
    uprice: "",
    countfiltered: 0
};

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

(function ($) {

    $('.sort_select').on('change',function(){
        memconf.orderBy = $(this).val();
        var group = $(this).data('group');
        $('#def-'+group+' > .product-paging').remove();
        $('#paginationDiv-'+group+' > center > ul > .pagination-indiv:first').trigger('click');
    }); 

    $(document.body).on('click','.icon-grid',function() {
        var view = $("div.view").attr("class");
        if(view == "view row row-items list"){
            $('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-lg-3 col-md-4 col-xs-6 thumb");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
        }
    });

    $(document).on('click','.icon-list',function() {   
        var view = $("div.view").attr("class");
        if(view == "view row row-items grid"){
            $('div.view').removeClass("view row row-items grid").addClass("view row row-items list");
            $('div.col-lg-3').removeClass("col-lg-3 col-md-4 col-xs-6 thumb").addClass("col-md-12 thumb");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view").addClass("gv fa fa-th-large fa-2x icon-view icon-grid");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list").addClass("lv fa fa-th-list fa-2x icon-view icon-list active-view");
        };
    });

    $('.pagination-maxleft').on('click', function(){
        $(this).siblings('.pagination-indiv:first').trigger('click');
    });
    $('.pagination-maxright').on('click', function(){
        $(this).siblings('.pagination-indiv:last').trigger('click');
    });

    $('.pagination-indiv').on('click', function(){
        var page = $(this).data('page');
        var group = $(this).data('group');

        var pageDiv = $('#def-'+group+' > .product-paging[data-page="'+page+'"]');
        var catDiv = $(this).closest('div.category-products');

        $(this).siblings('.pagination-indiv').removeClass('active');
        $(this).addClass('active');

        if(pageDiv.length === 1){
            $('#def-'+group+' > .product-paging').hide();
            pageDiv.show();
        }
        else{
            ItemListAjax(catDiv,page);
        }
    });

    $("#cat-header").on('click','.a-category',function() {
        var attr = $("b.cat").attr("class");
        if(attr == "cat fa fa-minus-square-o pull-right"){
            $('b.cat').removeClass("cat fa fa-minus-square-o pull-right").addClass("cat fa fa-plus-square-o pull-right");
            
        }
        else if(attr == "cat fa fa-plus-square-o pull-right"){
            $('b.cat').removeClass("cat fa fa-plus-square-o pull-right").addClass("cat fa fa-minus-square-o pull-right");
            
        }
    });

    $("#filter-header").on('click','.a-filter',function() {
        var attr = $("b.fil").attr("class");
        if(attr == "fil fa fa-minus-square-o pull-right"){
            $('b.fil').removeClass("fil fa fa-minus-square-o pull-right").addClass("fil fa fa-plus-square-o pull-right");
        }
        else if(attr == "fil fa fa-plus-square-o pull-right"){
            $('b.fil').removeClass("fil fa fa-plus-square-o pull-right").addClass("fil fa fa-minus-square-o pull-right");
        }
    });

    $(document).on('change',".price-field",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        }
        else {
            tempval = Math.abs(v);
            this.value = tempval.toFixed(2);
        }
    });

})(jQuery);

(function ($){

    $('#filter-btn').on('click', function(){
        var activeCategoryProductsDiv = $('.category-products.active');

        var condition = $('#filter-condition').val();
        var lprice = $.trim($('#filter-lprice').val());
        lprice = lprice.replace(new RegExp(",", "g"), '');
        lprice = parseFloat(lprice).toFixed(2);
        var uprice = $.trim($('#filter-uprice').val());
        uprice = uprice.replace(new RegExp(",", "g"), '');
        uprice = parseFloat(uprice).toFixed(2);

        memconf.condition = condition;
        memconf.lprice = !isNaN(lprice) ? lprice : "";
        memconf.uprice = !isNaN(uprice) ? uprice : "";
        memconf.countfiltered = memconf.uprice !== "" || memconf.lprice !== "" || memconf.condition !== "" ? 1 : 0;

        activeCategoryProductsDiv.find('.product-paging').remove();
        activeCategoryProductsDiv.find('li.pagination-indiv[data-page="1"]').trigger('click');
    });

})(jQuery);

var validateRedTextBox = function(idclass)
{
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
    $(idclass).focus();
} 

var validateWhiteTextBox = function(idclass)
{
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "box-shadow": "0px 0px 2px 2px #FFFFFF"});
}

var removeParam = function(key, sourceURL)
{
    var rtn = sourceURL.split("?")[0],
    param,
    params_arr = [],
    queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
        return rtn;
    }
    return sourceURL;
}

function ItemListAjax(CatDiv,page)
{
    var catId = CatDiv.attr("data-catId");
    var catType = CatDiv.attr("data-catType");
    var loadingDiv = CatDiv.find('div.loading_div');
    var productPage = CatDiv.find('.product-paging');
    var currentQueryString = $("#queryString").val();

    memconf.ajaxStat = jQuery.ajax({
        type: "GET",
        url: config.base_url+'memberpage/'+'vendorLoadProducts',
        data: "vid="+memconf.vid+"&vn="+memconf.vname+"&cid="+catId+"&ct="+catType+
            "&p="+page+"&ob="+memconf.orderBy+"&o="+memconf.order+"&qs="+currentQueryString+"&con="+memconf.condition+"&lp="+memconf.lprice+"&up="+memconf.uprice+
            "&count="+memconf.countfiltered+"&"+memconf.csrfname+"="+memconf.csrftoken,
        beforeSend: function(){
            loadingDiv.show();
            productPage.hide();

            if(memconf.ajaxStat != null){
                memconf.ajaxStat.abort();
            }
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

            if(productPage.length > 0){
                CatDiv.find('.product-paging:last').after(obj.htmlData);    
            }
            else{
                CatDiv.find('.loading_div').after(obj.htmlData);
            }

            if(obj.isCount){
                CatDiv.find('.pagination-indiv:gt('+(obj.pageCount-1)+')').hide();
            }
            else{
                CatDiv.find('.pagination-indiv').show();
            }
            
        } 
    });
}


/********* DESIGNER ************/

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

    $(document.body).on('click','.icon-grid',function() {
        var view = $("div.view").attr("class");
    
        if(view == "view row row-items list")
        {
            $('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-lg-3 col-md-4 col-xs-6 thumb");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
        }
    });

    $(document).on('click','.icon-list',function() {   
        var view = $("div.view").attr("class");
        if(view == "view row row-items grid")
        {
            $('div.view').removeClass("view row row-items grid").addClass("view row row-items list");
            $('div.col-lg-3').removeClass("col-lg-3 col-md-4 col-xs-6 thumb").addClass("col-md-12 thumb");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view").addClass("gv fa fa-th-large fa-2x icon-view icon-grid");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list").addClass("lv fa fa-th-list fa-2x icon-view icon-list active-view");
        };
    });

    $('.tab_categories').on('click', function(){
        var divId = $(this).attr('data-link');
        $('.category-products').removeClass('active').hide();
        $(divId).addClass('active').show();
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


    // I Start Here....
    // -- Ryan Vasquez.

    $(document).on('click','#edit-profile-btn',function() {
        $('#display-banner-view').hide();
        $('#edit-banner-view').show();
    });

    $(document).on('click','#banner-cancel-changes',function() {
        $('#display-banner-view').show();
        $('#edit-banner-view').hide();
    });

   
    $(document).on('click','#banner-save-changes',function() {
        
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
            url: config.base_url+'memberpage/'+'vendorDetails',
            data: "vendor_details=1&store_name="+storName+
                    "&mobile="+mobileNumber+
                    "&stateregion="+stateRegion+
                    "&city="+city+
                    "&"+memconf.csrfname+"="+memconf.csrftoken,
            success: function(data){
                if(data.result){
                    // change all related display
                    $('#display-banner-view').show();
                    $('#edit-banner-view').hide();
                    $(".storeName").html(storName);
                    $("#placeStock > strong").html(citySelected+', '+stateRegionSelected);
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
            document.getElementById('form_image').action = 'memberpage/'+action;
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
                    document.getElementById('form_image').action = 'memberpage/'+formAction;
                    $('#form_image').submit();
                    $.modal.close();
                });

                if(imageUploadType  == "avatar"){
                    jcrop_api = $.Jcrop($('#user_image_prev'),{
                        aspectRatio: width/height,
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
                        aspectRatio: 980/270,
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
