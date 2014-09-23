
<link rel="stylesheet" href="/assets/css/chosen.min.css" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>

<section>
    <div class="pos-rel" id="display-banner-view">
        <div class="vendor-main-bg">
            <img src="<?=$bannerImage?>">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <img src="<?=$avatarImage?>">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="storeName"><?=$storeNameDisplay?></h4>
                    <p><strong>Contact No. :</strong><?php echo strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "N/A" ?></p>
                    <p>
                        <span class="glyphicon glyphicon-map-marker"></span>
                        <?php if($hasAddress):?>
                            <span id="placeStock" class="cl-1"><strong><?php echo $arrVendorDetails['cityname'] . ", " . $arrVendorDetails['stateregionname']?></strong></span>
                        <?php else:?>
                            <span class="cl-1"><strong>Location not set</strong></span>
                        <?php endif;?>
                    </p>
                    <?php if($isEditable): ?>
                    <div class="vendor-profile-btn">
                        <a href="javascript:void(0)" id="edit-profile-btn" class="btn btn-default-3">
                            <img src="<?=base_url()?>assets/images/img-vendor-icon-edit.jpg"> Edit Profile
                        </a>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
    <div class="pos-rel" style="display:none;" id="edit-banner-view">
        <div class="vendor-main-bg">
            <div class="edit-cover-photo">
                <a href="javascript:void(0)" id="banner_edit">
                    <img src="<?=base_url()?>assets/images/img-default-cover-photo.png" alt="Change Cover Photo"><br />
                    <h4><strong>Change Cover Photo</strong></h4>
                </a>
            </div>
            <img src="<?=$bannerImage?>" alt="sample cover photo">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <div id="hidden-form">
                                <?php echo form_open_multipart('memberpage/upload_img', 'id="form_image"');?>
                                    <input type="file" data-type="avatar" style="visibility:hidden; height:0px; width:0px; position:absolute;" id="imgupload" accept="image/*" name="userfile"/> 
                                    <input type='hidden' name='x' value='0' id='image_x'>
                                    <input type='hidden' name='y' value='0' id='image_y'>
                                    <input type='hidden' name='w' value='0' id='image_w'>
                                    <input type='hidden' name='h' value='0' id='image_h'>
                                    <input type='hidden' name='vendor' value='1' id='vendor-hidden'>
                                <?php echo form_close();?>
                                <div id="div_user_image_prev">
                                    <span> Crop your Photo! </span>
                                    <img src="" id="user_image_prev">
                                    <button>OK</button>
                                </div>
                            </div>

                            <div class="edit-profile-photo">
                                <div>
                                    <img src="<?=base_url()?>assets/images/img-default-cover-photo.png" alt="Edit Profile Photo">
                                    <span>Change Profile Photo</span>
                                </div>
                            </div>
                            <div class="edit-profile-photo-menu">
                                <div><a id="avatar_edit" href="javascript:void(0)">Upload Photo</a></div>
                                <div><a href="">Remove Photo</a></div>
                            </div>
                            <img id="imageCropPreview" src="<?=$avatarImage?>" alt="Profile Photo">
                        </div>
                    </div>
                </div>
                <div class="pd-lr-20">
                    <input type="text" id="storeNameTxt" class="form-control mrgn-bttm-8 seller-name" value="<?=$storeNameDisplay; ?>" placeholder="Seller Name">
                    <input type="text" id="mobileNumberTxt" class="form-control mrgn-bttm-8" placeholder="Contact No." value="<?=strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "" ?>">
                    <div class="mrgn-bttm-8 edit-vendor-location">

                        <!-- State/Region Dropdown -->
                        <select name="c_stateregion" class="address_dropdown stateregionselect">
                            <option value="0">--- Select State/Region ---</option> 
                            <?php foreach($stateRegionLookup as $srkey=>$stateregion):?>
                                <option class="echo" value="<?php echo $srkey?>" <?php echo $arrVendorDetails['stateregion'] == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                            <?php endforeach;?>
                        </select>

                        <!-- City Dropdown -->
                        <select name="c_city" class="address_dropdown cityselect">
                            <option value="0">--- Select City ---</option> 
                            <?php foreach($cityLookup as $parentkey=>$arr):?>
                                <?php foreach($arr as $lockey=>$city):?>
                                    <option class="echo" value="<?php echo $lockey?>" data-parent="<?php echo $parentkey?>" <?php echo $arrVendorDetails['city'] == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                <?php endforeach;?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <?php if($isEditable): ?>
                    <div class="vendor-profile-btn edit-profile-btn">
                        <a href="javascript:void(0)" id="banner-cancel-changes" class="btn btn-default-1">Cancel</a>
                        <a href="javascript:void(0)" id="banner-save-changes"class="btn btn-default-3">Save Changes</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="sticky-nav-bg">
    <div class="vendor-sticky-nav">
        <div class="main-container">
            <ul class="vendor-nav">
                <?php
                    $url_id = $this->uri->segment(2, 0);
                ?>
                <li>
                    <a href="" class="<?php if($url_id=="0"){ echo "vendor-nav-active"; }else{ echo " ";}?>">
                        <img src="<?=base_url()?>assets/images/<?php if($url_id=="0"){ echo "img-sticky-nav-home-active"; }else{ echo "img-sticky-nav-home";}?>.jpg" alt="Store">
                    </a>
                </li>
                <li>
                    <a href="">Promo Page</a>
                </li>
                <li >
                    <a href="" class="<?php if($url_id=="about"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Seller Information</a>
                </li>
                <li>
                    <a href="" class="<?php if($url_id=="contact"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Contact</a>
                </li>
            </ul>
            <ul class="sticky-nav">
                <li>
                    <div class="vendor-profile-img-con">
                        <img src="<?=base_url()?>assets/images/img-default-vendor-profile-photo.jpg" alt="Profile Photo">
                    </div>
                    <h4>Air 21</h4>
                </li>
                <li>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-promo.jpg"></a>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-info.jpg"></a>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-contact.jpg"></a>
                </li>
                <li>
                    <select class="ui-form-control">
                        <option>On Seller's Page</option>
                        <option>Main Page</option>
                        <option>Other Page</option>
                    </select>
                    <input type="text" class="ui-form-control">
                    <input type="submit" value="" class="span_bg">
                </li>
                <li class="pos-rel">
                    <div class="header-cart-container">
                        <span class="header-cart-items-con sticky-cart">
                            <span class="header-cart-item">2 item(s)</span> in your cart
                        </span>
                        <span class="header-cart-icon-con span_bg cart-icon"></span>
                    </div>
                    <div class="sticky-header-cart-item-list">
                        <p>Recently add item(s)</p>
                        <div class="mrgn-bttm-15">
                            <div class="header-cart-item-img">
                                <a href="">
                                    <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                </a>
                            </div>
                            <div class="header-cart-item-con">
                                <a href=""><span>Doraemon - blue</span></a>
                                <span>x 1</span>
                                <span class="header-cart-item-price">&#8369; 450.00</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="mrgn-bttm-15">
                            <div class="header-cart-item-img">
                                <a href="">
                                    <span><img src="<?=base_url()?>assets/images/img_doraemon.png" alt="Doraemon"></span>
                                </a>
                            </div>
                            <div class="header-cart-item-con">
                                <a href=""><span>Doraemon - blue</span></a>
                                <span>x 1</span>
                                <span class="header-cart-item-price">&#8369; 450.00</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="header-cart-lower-content">
                            <div class="header-cart-shipping-total">
                                <p>Shipping: <span>&#8369; 50.00</span></p>
                                <p>Total: <span>&#8369; 100,500.00</span></p>
                            </div>
                            <div class="header-cart-buttons">
                                <a href="" class="header-cart-lnk-cart">go to cart</a>
                                <a href="" class="header-cart-lnk-checkout">checkout</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section>


<script src="/assets/js/src/vendor/chosen.jquery.min.js" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type="text/javascript">
    var jsonCity = <?php echo $jsonCity;?>;
</script>
<script type="text/javascript">


(function($){

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
        $('.category-products').hide();
        $(divId).show();
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
</script>

