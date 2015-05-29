<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/chosen.min.css" type="text/css" media="screen"/>
    <link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/> 
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.vendor-banner.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<?=$snippetMarkUp; ?>
<?php include('vendor-custom-theme.php'); ?>
<div class="loader-upload" style="display: none;">
    <img src="<?php echo getAssetsDomain()?>assets/images/loading/preloader-whiteBG.gif"/>
    <span>Uploading photo</span>
</div>
<section>
    <div class="pos-rel" id="display-banner-view">
        <div class="vendor-main-bg" style="background: url(<?php echo getAssetsDomain().'.'.$bannerImage?>) center no-repeat; background-size: cover;">
            
        </div>
        <div class="container main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <div class="vendor-profile-photo-wrapper">
                                <img src="<?php echo getAssetsDomain().'.'.$avatarImage?>" class="avatar-image" alt="Profile Photo">
                            </div>
                        </div>
                    </div>
                </div>
                <div> 
                    <h4 class="storeName"><?=html_escape($arrVendorDetails['store_name'])?></h4>
                    <p class="contact-number-text"><strong>Contact No. :</strong><span id="contactContainer"><?php echo html_escape(strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "N/A"); ?></span></p>
                    <p class="location-text">
                        <img src="<?php echo getAssetsDomain()?>assets/images/img-icon-marker.png" alt="marker">
                        <?php if($hasAddress):?>
                            <span id="placeStock" class="cl-1"><strong><?php echo $arrVendorDetails['cityname'] . ", " . $arrVendorDetails['stateregionname']?></strong></span>
                        <?php else:?>
                            <span class="cl-1"><strong>Location not set</strong></span>
                        <?php endif;?>
                    </p>
                    <?php if($isEditable): ?>
                    <div class="vendor-profile-btn" style="margin-top: 10px;">
                        <a href="javascript:void(0)" id="edit-profile-btn" class="btn btn-default-3 btn-profile-edit-mobile">
                            <img src="<?php echo getAssetsDomain()?>assets/images/img-vendor-icon-edit.png" alt="Edit Profile"> Edit Profile
                        </a>
                        <span class="btn btn-default-3 btn-profile-edit-mobile" id="modal-edit-trigger">
                            <img src="<?php echo getAssetsDomain()?>assets/images/img-vendor-icon-edit.png" alt="Edit Profile"> Edit Profile
                        </span>
                        <a href="#" class="btn btn-default-2 btn-profile-edit-mobile btn-change-cover-photo-mobile">Change Cover Photo</a>
                    </div>
                    <?php else: ?>
                    <div class="vendor-profile-btn">
                        <span class="subscription_btn btn btn-default-2" style="display: <?php echo $subscriptionStatus === 'followed' ? '' : 'none'  ?>">
                            <span class="glyphicon glyphicon-minus-sign"></span>Unfollow
                        </span>
                        <span id="follow_btn" class="subscription_btn btn btn-default-2" style="display: <?php echo $subscriptionStatus === 'unfollowed' ? '' : 'none'  ?>">
                            <span class="glyphicon glyphicon-plus-sign"></span>Follow
                        </span>                       

                        <a class="btn btn-default-1 btn-message-profile" href="/<?=$arrVendorDetails['userslug']; ?>/contact">
                            <span class="icon-message-btn"></span>
                            Message
                        </a>

                         <?php echo form_open('');?>
                            <input type="hidden" id="subscribe_status" value="<?php echo $subscriptionStatus?>">
                            <input type="hidden" id="vendor_name" name="name" value="<?php echo $arrVendorDetails['username']?>">
                            <input type="hidden" id="is_loggedin" value="<?php echo $isLoggedIn ? 1 : 0 ?>">
                            <input type="hidden" name="vendorlink" value="<?php echo $arrVendorDetails['userslug']?>">
                        <?php echo form_close();?>
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
                    <img src="<?php echo getAssetsDomain()?>assets/images/img-default-cover-photo.png" alt="Change Cover Photo"><br />
                    <h4><strong>Change Cover Photo</strong></h4>
                </a>
            </div>
            <div class="vendor-main-bg" style="background: url(<?=getAssetsDomain().'.'.$bannerImage?>) center no-repeat; background-size: cover;">  
            </div> 
        </div>
        <div class="container main-container vendor-main pos-ab edit-profile-mobile">
            <div class="vendor-profile-content" id="edit-profile-info-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <div id="hidden-form">
                                <?php echo form_open_multipart('/store/upload_img', 'id="form_image"');?>
                                    <input type="file" data-type="avatar" style="visibility:hidden; height:0px; width:0px; position:absolute;" id="imgupload" accept="image/*" name="userfile"/> 
                                    <input type='hidden' name='x' value='0' id='image_x'>
                                    <input type='hidden' name='y' value='0' id='image_y'>
                                    <input type='hidden' name='w' value='0' id='image_w'>
                                    <input type='hidden' name='h' value='0' id='image_h'>
                                    <input type='hidden' name='vendor' value='1' id='vendor-hidden'>
                                    <input type="hidden" name="url" value="<?=$vendorLink?>">
                                    <input type="hidden" id="isAjax" name="isAjax" value="true"/>
                                <?php echo form_close();?>
                                <div id="div_user_image_prev">
                                    <div class="avatar-modal-loading">
                                        <img src="<?php getAssetsDomain() ?>assets/images/loading/preloader-whiteBG.gif"/>
                                    </div>
                                    
                                    <div class="avatar-modal-content">
                                        <h1>Position and scale your photo</h1>
                                        <div class="img-editor-container">
                                            <img src="" id="user_image_prev">
                                        </div>
                                        <span class="modalCloseImg simplemodal-close btn btn-default-1">Cancel</span>
                                        <button class="btn btn-default-3">Apply</button>
                                    </div>
                                </div>
                            </div>

                            <div class="edit-profile-photo">
                                <div>
                                    <img src="<?php getAssetsDomain() ?>assets/images/img-default-cover-photo.png" class="img-icon-edit-photo" alt="Edit Profile Photo">
                                    <span>Change Profile Photo</span>
                                </div>
                            </div>
                            <div class="edit-profile-photo-menu">
                                <div><a id="avatar_edit" href="javascript:void(0)">Upload Photo</a></div>
                                <div><a id="avatar_remove" href="javascript:void(0)">Remove Photo</a></div>
                            </div>
                            <img id="imageCropPreview" class="avatar-image" src="<?php echo getAssetsDomain().'.'.$avatarImage?>" alt="Profile Photo">
                        </div>
                    </div>
                </div>
                <div class="pd-lr-20 edit-profile-container">
                    <input type="text" id="storeNameTxt" maxlength="60" class="form-control mrgn-bttm-8 seller-name" value="<?=html_escape($arrVendorDetails['store_name']); ?>" data-origval="<?=html_escape($arrVendorDetails['store_name']); ?>" placeholder="Store Name">
                    <input type="text" id="mobileNumberTxt" maxlength="11" class="form-control mrgn-bttm-8" placeholder="Contact No." value="<?= html_escape(strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : ""); ?>" data-origval="<?= html_escape(strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : ''); ?>">
                    <div class="mrgn-bttm-8 edit-vendor-location">

                        <input type="hidden" id="json_city" value='<?php echo json_encode($cityLookup, JSON_HEX_APOS)?>'>

                        <!-- State/Region Dropdown -->
                        <select name="c_stateregion" class="address_dropdown stateregionselect" data-origval="<?php echo $arrVendorDetails['stateregion']?>">
                            <option value="0">--- Select State/Region ---</option> 
                            <?php foreach($stateRegionLookup as $srkey=>$stateregion):?>
                                <option class="echo" value="<?php echo $srkey?>" <?php echo $arrVendorDetails['stateregion'] == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                            <?php endforeach;?>
                        </select>

                        <!-- City Dropdown -->
                        <select name="c_city" class="address_dropdown cityselect" data-origval="<?php echo $arrVendorDetails['city']?>">
                            <option value="0">--- Select City ---</option> 
                            <?php foreach($cityLookup as $parentkey=>$arr):?>
                                <?php foreach($arr as $lockey=>$city):?>
                                    <option class="echo" value="<?php echo $lockey?>" data-parent="<?php echo $parentkey?>" <?php echo $arrVendorDetails['city'] == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                <?php endforeach;?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <?php if($isEditable): ?>
                    <div class="vendor-profile-btn edit-profile-btn edit-banner-profile">
                        <a href="javascript:void(0)" id="banner-cancel-changes" class="btn btn-default-1 btn-edit-profile-info-banner btn-cancel-me-wide">Cancel</a>
                        <a href="javascript:void(0)" id="banner-save-changes" class="btn btn-default-3 btn-edit-profile-info-banner clickable">Save Changes</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="sticky-nav-bg">
    <div class="vendor-menu-nav">
        <div class="container main-container">
            <ul class="vendor-nav">
                <?php
                    $url_id = $this->uri->segment(2, 0);
                ?>
                <?php if(!$hasNoItems): ?>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']?>" class="<?php if($url_id=="0"){ echo "vendor-nav-active"; }else{ echo " ";}?>">
                        <img src="<?php getAssetsDomain() ?>/assets/images/home-icons/<?php if($url_id=="0"){ echo "active-home-".html_escape($colorHexadecimal); }else{ echo "default-home";}?>.png" alt="Store" width="40px" height="40px">
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/about" class="<?php if($url_id === "about"){ echo "vendor-nav-active"; }else{ echo " ";}?>">About</a>
                </li>
                <li class="pos-rel">
                    <span class="followers-circle"><?=$followerCount; ?></span>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/followers" class="<?php if($url_id === "followers"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Followers</a>
                </li>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/contact" class="<?php if($url_id === "contact"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Contact</a>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section> 


<input type="hidden" id="vendor-slug" name="name" value="<?php echo $arrVendorDetails['userslug']?>">
<input type="hidden" id="max-upload-size" name="name" value="<?=EasyShop\Upload\AssetsUploader::MAX_ALLOWABLE_SIZE_KB; ?>">
<input type="hidden" id="max-upload-height" name="name" value="<?=EasyShop\Upload\AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX; ?>">
<input type="hidden" id="max-upload-width" name="name" value="<?=EasyShop\Upload\AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX; ?>">

<!-- Load Js Files -->
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.easing.min.js"></script>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.scrollUp.min.js"></script>
    <script type="text/javascript" src="/assets/js/src/vendor/chosen.jquery.min.js"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.Jcrop.min.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
    <script type='text/javascript' src="/assets/tinymce/plugins/jbimages/js/jquery.form.js"></script>
    <script type="text/javascript" src='/assets/js/src/vendor_header.js?ver=<?php echo ES_FILE_VERSION?>'></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.vendor_banner.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

