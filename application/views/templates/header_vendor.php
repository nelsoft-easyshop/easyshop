
<!-- Load Css -->
<link rel="stylesheet" href="/assets/css/chosen.min.css" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>  

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
                    <h4 class="storeName"><?=html_escape($storeNameDisplay)?></h4>
                    <p><strong>Contact No. :</strong><span id="contactContainer"><?php echo strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "N/A" ?></span></p>
                    <p>
                        <img src="/assets/images/img-icon-marker.png" alt="marker">
                        <?php if($hasAddress):?>
                            <span id="placeStock" class="cl-1"><strong><?php echo $arrVendorDetails['cityname'] . ", " . $arrVendorDetails['stateregionname']?></strong></span>
                        <?php else:?>
                            <span class="cl-1"><strong>Location not set</strong></span>
                        <?php endif;?>
                    </p>
                    <?php if($isEditable): ?>
                    <div class="vendor-profile-btn">
                        <a href="javascript:void(0)" id="edit-profile-btn" class="btn btn-default-3">
                            <img src="/assets/images/img-vendor-icon-edit.png" alt="Edit Profile"> Edit Profile
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="vendor-profile-btn">
                        <span class="subscription_btn btn btn-default-2" style="display: <?php echo $subscriptionStatus === 'followed' ? '' : 'none'  ?>">
                            <span class="glyphicon glyphicon-minus-sign"></span>Unfollow
                        </span>
                        <span id="follow_btn" class="subscription_btn btn btn-default-2" style="display: <?php echo $subscriptionStatus === 'unfollowed' ? '' : 'none'  ?>">
                            <span class="glyphicon glyphicon-plus-sign"></span>Follow
                        </span>                       

                        <a class="btn btn-default-1" href="/<?=$arrVendorDetails['userslug']; ?>/contact">
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
                    <img src="/assets/images/img-default-cover-photo.png" alt="Change Cover Photo"><br />
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
                                <?php echo form_open_multipart('/memberpage/upload_img', 'id="form_image"');?>
                                    <input type="file" data-type="avatar" style="visibility:hidden; height:0px; width:0px; position:absolute;" id="imgupload" accept="image/*" name="userfile"/> 
                                    <input type='hidden' name='x' value='0' id='image_x'>
                                    <input type='hidden' name='y' value='0' id='image_y'>
                                    <input type='hidden' name='w' value='0' id='image_w'>
                                    <input type='hidden' name='h' value='0' id='image_h'>
                                    <input type='hidden' name='vendor' value='1' id='vendor-hidden'>
                                    <input type="hidden" name="url" value="<?=$vendorLink?>">
                                <?php echo form_close();?>
                                <div id="div_user_image_prev">
                                    <h1>Position and scale your photo</h1>
                                    <div class="img-editor-container">
                                        <img src="" id="user_image_prev">
                                    </div>
                                    <span class="modalCloseImg simplemodal-close btn btn-default-1">Cancel</span>
                                    <button class="btn btn-default-3">Apply</button>
                                </div>
                            </div>

                            <div class="edit-profile-photo">
                                <div>
                                    <img src="/assets/images/img-default-cover-photo.png" alt="Edit Profile Photo">
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
                    <input type="text" id="storeNameTxt" maxlength="50" class="form-control mrgn-bttm-8 seller-name" value="<?=html_escape($storeNameDisplay); ?>" data-origval="<?=html_escape($storeNameDisplay); ?>" placeholder="Seller Name">
                    <input type="text" id="mobileNumberTxt" maxlength="11" class="form-control mrgn-bttm-8" placeholder="Contact No." value="<?=strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "" ?>" data-origval="<?=strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : '' ?>">
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
                <?php if($noItem): ?>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']?>" class="<?php if($url_id=="0"){ echo "vendor-nav-active"; }else{ echo " ";}?>">
                        <img src="/assets/images/vendor-icons/<?php if($url_id=="0"){ echo "active-home"; }else{ echo "default-home";}?>.png" alt="Store" width="40px" height="40px">
                    </a>
                </li>
                <!--
                <li>
                    <a href="/">Promo Page</a>
                </li>
                -->
                <?php endif; ?>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/about" class="<?php if($url_id === "about"){ echo "vendor-nav-active"; }else{ echo " ";}?>">About</a>
                </li>
                <li>
                    <span class="followers-circle">12</span>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/followers" class="<?php if($url_id === "followers"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Followers</a>
                </li>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/contact" class="<?php if($url_id === "contact"){ echo "vendor-nav-active"; }else{ echo " ";}?>">Contact</a>
                </li>
            </ul>
            <ul class="sticky-nav">
                <li>
                    <div class="vendor-profile-img-con">
                        <img src="<?=$avatarImage?>" alt="Profile Photo">
                    </div>
                    <h4><?=html_escape($storeNameDisplay);?></h4>
                </li>
                <li>
                    <a href="/<?=$arrVendorDetails['userslug']?>"><img src="/assets/images/img-vendor-icon-promo.png" alt="Promo"></a>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/about"><img src="/assets/images/img-vendor-icon-info.png" alt="Seller Information"></a>
                    <a href="/<?=$arrVendorDetails['userslug']; ?>/contact"><img src="/assets/images/img-vendor-icon-contact.png" alt="Contact"></a>
                </li>
                <li> 
                    <form class="search-form">
                        <select class="ui-form-control search-type">
                            <option value="1">On Seller's Page</option>
                            <option value="2">Main Page</option> 
                        </select>
                        <input type="text" name="q_str" value="<?=($this->input->get('q_str'))?trim($this->input->get('q_str')):""?>" class="ui-form-control">
                        <input type="submit"  value="" class="submitSearch span_bg">
                    </form>
                </li>
                <li class="pos-rel">
                    <div class="header-cart-container">
                        <a href="<?php echo base_url()."cart/"; ?>" class="header-cart-wrapper">
                            <span class="header-cart-items-con sticky-cart ui-form-control">
                                <span class="header-cart-item"><?=$cart_size?> item(s)</span> in your cart
                            </span>
                            <span class="header-cart-icon-con span_bg cart-icon"></span>
                        </a>
                        <div class="sticky-header-cart-item-list">
                            <?PHP if ((intval(sizeof($cart_items))) === 0 ) : ?>
                            <?PHP else : ?>
                                <p>Recently add item(s)</p>
                                <?PHP for($cnt = sizeof($cart_items) - 1; $cnt > -1 ;$cnt--) : ?>
                                    <?PHP if(sizeof($cart_items) - 1 === $cnt || sizeof($cart_items) - 1 === $cnt +1) : ?>
                                        <div class="mrgn-bttm-15">
                                            <div class="header-cart-item-img">
                                                <a href="/item/<?=$cart_items[$cnt]['slug']?>">
                                                    <span><img src="/<?=$cart_items[$cnt]['imagePath']; ?>thumbnail/<?=$cart_items[$cnt]['imageFile']; ?>" alt="<?=$cart_items[$cnt]['name']?>"></span>
                                                </a>
                                            </div>
                                            <div class="header-cart-item-con">
                                                <a href="/item/<?=$cart_items[$cnt]['slug']?>"><span><?=$cart_items[$cnt]['name']?></span></a>
                                                <span>x <?=$cart_items[$cnt]['qty']?></span>
                                                <span class="header-cart-item-price">&#8369; <?=$cart_items[$cnt]['price']?></span>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    <?PHP endif; ?>
                                <?PHP endfor; ?>
                                <div class="header-cart-lower-content">
                                    <div class="header-cart-shipping-total">
                                        <p>Items(s) in cart: <span><?=$cart_size?></span></p>
                                        <p>Total: <span>&#8369; <?=$total?></span></p>
                                    </div>
                                    <div class="header-cart-buttons">
                                        <a href="/cart" class="header-cart-lnk-cart">go to cart</a>
                                        <a href="javascript:void(0)" onclick="proceedPayment(this)" class="header-cart-lnk-checkout">checkout</a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            <?PHP endif;?>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section> 
<input type="hidden" id="vendor-slug" name="name" value="<?php echo $arrVendorDetails['userslug']?>">
<!-- Load Js Files -->
<script type="text/javascript" src="/assets/js/src/vendor/jquery.easing.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.scrollUp.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/chosen.jquery.min.js"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<script type="text/javascript" src='/assets/js/src/vendor_header.js?ver=<?php echo ES_FILE_VERSION?>'></script>

 
