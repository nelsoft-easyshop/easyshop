
<!-- Load Css -->
<link rel="stylesheet" href="/assets/css/chosen.min.css" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/main-style.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/bootstrap.css' rel="stylesheet" media='screen'/>

<section>
    <div class="pos-rel" id="display-banner-view">
        <div class="vendor-main-bg">
            <img src="<?=$bannerImage?>" alt="Banner Image">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <img src="<?=$avatarImage?>" alt="Profile Photo">
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
            <img src="<?=$bannerImage?>" alt="Banner Image">
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
                                <div><a id="avatar_remove" href="javascript:void(0)">Remove Photo</a></div>
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
                        <img src="<?=$avatarImage?>" alt="Profile Photo">
                    </div>
                    <h4><?=$storeNameDisplay?></h4>
                </li>
                <li>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-promo.jpg"></a>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-info.jpg"></a>
                    <a href=""><img src="<?=base_url()?>assets/images/img-vendor-icon-contact.jpg"></a>
                </li>
                <li> 
                    <form class="search-form">
                        <select class="ui-form-control search-type">
                            <option value="1">On Seller's Page</option>
                            <option value="2">Main Page</option> 
                        </select>
                        <input type="text" name="q_str" class="ui-form-control">
                        <input type="submit"  value="" class="submitSearch span_bg">
                    </form>
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

<!-- Load Js Files -->
<script src="/assets/js/src/vendor/chosen.jquery.min.js" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type="text/javascript">
    var jsonCity = <?php echo json_encode($cityLookup);?>;
</script>
<script src='/assets/js/src/vendor_header.js' type="text/javascript"></script>

 
