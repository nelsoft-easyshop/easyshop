<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />

<!-- Load all css -->
<link type="text/css" href='/assets/css/main-style.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/bootstrap.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/font-awesome/css/font-awesome.min.css' rel="stylesheet" media='screen'/>
<link rel="stylesheet" href="/assets/css/chosen.min.css" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>

<!-- Load body -->
<header class="new-header-con">
    <div class="main-container">
        <div>
            <a href="<?=base_url()?>">
                <img src="/assets/images/img_logo.png" alt="Easyshop.ph Logo">
            </a>
        </div>
        <div class="search-container">
            <select class="ui-form-control">
                <option>On Seller's Page</option>
                <option>Main Page</option>
                <option>Other Page</option>
            </select>
            <input type="text" class="ui-form-control">
            <input type="submit" value="" class="span_bg">
        </div>
        <div class="pos-rel mrgn-rght-8">
            <div class="header-cart-container">
                <span class="header-cart-items-con">
                    <span class="header-cart-item">2 item(s)</span> in your cart
                </span>
                <span class="header-cart-icon-con span_bg cart-icon"></span>
            </div>
            <div class="header-cart-item-list">
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
        </div>
        <div>
            <!-- <div class="vendor-login-con">
                <img src="<?=base_url()?>assets/images/img-default-icon-user.jpg"> 
                <a href=""><strong>login</strong></a>  or 
                <a href=""><strong>Create and account</strong></a>
            </div> -->
            <div class="vendor-login-con">
                <img src="<?=base_url()?>assets/images/img-default-icon-user.jpg"> 
                <a href=""><span class="vendor-login-name"><strong>Seller2DaMax</strong></span></a>
                <div class="new-user-nav-dropdown">
                    <span class="user-nav-dropdown">Account Settings</span>
                </div>
                <ul class="nav-dropdown">
                    <li>
                        <a href="/me">Dashboard</a>
                    </li>
                    <li>
                        <a href="/me?me=pending">On-going Transactions</a>
                    </li>
                    <li class="nav-dropdown-border">
                        <a href="/me?me=settings">Settings</a>
                    </li>
                    <li class="nav-dropdown-border">
                        <a class="prevent" href="/login/logout">Logout</a>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</header>

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

<section class="sticky-nav-bg" id="bm">
    <div class="vendor-sticky-nav">
        <div class="main-container">
            <ul class="vendor-nav">
                <li>
                    <a href="" class="vendor-nav-active">
                        <img src="<?=base_url()?>assets/images/img-sticky-nav-home-active.jpg" alt="Store">
                    </a>
                </li>
                <li>
                    <a href="">Promo Page</a>
                </li>
                <li>
                    <a href="">Seller Information</a>
                </li>
                <li>
                    <a href="">Contact</a>
                </li>
            </ul>
            <ul class="sticky-nav">
                <li>
                    <div class="vendor-profile-img-con">
                        <img src="<?=$avatarImage; ?>" alt="Profile Photo">
                    </div>
                    <h4 class="storeName"><?=$storeNameDisplay?></h4>
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

<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
    <div class="row row-products">
        <div class="col-xs-3 no-padding col-left-wing">
            <div class="left-wing">
                <div class="panel-group panel-category border-0" id="category">
                    <div class="panel panel-default  border-0 no-padding">
                        <div class="panel-heading border-0 panel-category-heading" id="cat-header">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" class="a-category" data-parent="#category" href="#category-list">
                                    CATEGORIES <b class="cat fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>
                        <div id="category-list" class="panel-collapse collapse in">
                            <div class="panel-body border-0 no-padding">
                                <ul class="list-unstyled list-category">
                                    <?php foreach( $defaultCatProd as $catId=>$arrCat ):?>
                                        <a href="javascript: void(0)" data-link="#def-<?php echo $catId?>" class="color-default tab_categories"><li><?php echo $arrCat['name']?></li></a>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                 $("#cat-header").on('click','.a-category',function() {
                                            
                    var attr = $("b.cat").attr("class");

                    if(attr == "cat fa fa-minus-square-o pull-right")
                    {
                        $('b.cat').removeClass("cat fa fa-minus-square-o pull-right").addClass("cat fa fa-plus-square-o pull-right");
                        
                    }
                    else if(attr == "cat fa fa-plus-square-o pull-right"){
                        $('b.cat').removeClass("cat fa fa-plus-square-o pull-right").addClass("cat fa fa-minus-square-o pull-right");
                        
                    }
                });
                </script>
                <div class="panel-group panel-category border-0" id="filter">
                    <div class="panel panel-default  border-0 no-padding" id="filter-header">
                        <div class="panel-heading border-0 panel-category-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" class="a-filter" data-parent="#filter" href="#filter-list">
                                    FILTER PRODUCTS <b class="fil fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>
                        <div id="filter-list" class="panel-collapse collapse in">
                            <div class="panel-body border-0 no-padding">
                                <ul class="list-unstyled list-filter">
                                    <li>
                                        <p class="p-filter-name">By Condition</p>
                                        <select class="select-filter">
                                            <option>New</option>
                                            <option>Used</option>
                                            <option>New</option>
                                        </select>
                                    </li>
                                    <li>
                                        <p class="p-filter-name">By Condition</p>
                                        from <input type="text" class="input-filter-price"/> to <input type="text" class="input-filter-price"/>
                                    </li>
                                    <li>
                                        <center>
                                            <input type="submit" class="btn-filter" value="filter"/>
                                        </center>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <script>
                     $("#filter-header").on('click','.a-filter',function() {
                                                
                        var attr = $("b.fil").attr("class");

                        if(attr == "fil fa fa-minus-square-o pull-right")
                        {
                            $('b.fil').removeClass("fil fa fa-minus-square-o pull-right").addClass("fil fa fa-plus-square-o pull-right");
                        }
                        else if(attr == "fil fa fa-plus-square-o pull-right"){
                            $('b.fil').removeClass("fil fa fa-plus-square-o pull-right").addClass("fil fa fa-minus-square-o pull-right");
                        
                        }
                    });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-xs-9 col-products">
            <div class="div-products">
                <div class="div-product-view-option">
                    <table class="p-view color-default pull-left">
                        <tr>
                            <td class="td-view p-view color-default">VIEW STYLE:</td>
                            <td class="td-view" style="padding-top: 3px;"><span class="gv fa fa-th-large fa-2x icon-view icon-grid active-view"></span> <span class="lv fa fa-th-list fa-2x icon-view icon-list"></span></td>
                        </tr>
                    </table>
                </div>

                <div class="clear"></div>
                
                <input type="hidden" id="vid" value="<?php echo $arrVendorDetails['id_member']?>">
                <input type="hidden" id="vname" value="<?php echo $arrVendorDetails['username']?>">
                <input type="hidden" id="queryString" value='<?=json_encode($_GET); ?>' />

               <?=$viewProductCategory;?>

<!--
                <div class="view row row-items grid" id="fuck">
                    <?php if($product_count > 0):?>
                        <?php foreach($products as $catID=>$p):?>
                            <?php foreach($p['products'] as $prod):?>
                                <div class="panel panel-default panel-list-item">
                                    <table width="100%">
                                        <tr>
                                            
                                            <td width="20%" class="td-list-image" style="background: url(<?=base_url()?><?php echo $prod['product_image_path']?>) center no-repeat; background-cover: cover;">
                                                <a href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                                <div class="span-space">
                                                    <span class="span-discount-pin">10% OFF</span>
                                                </div>
                                                </a>
                                            </td>
                                            
                                            <td width="55%" class="td-list-item-info">
                                                <p class="p-list-item-name">
                                                    
                                                        <?php 
                                                            $prod_name = html_escape($prod['name']);
                                                            if(strlen($prod_name)>35){
                                                        ?>
                                                            <a class="color-default" rel="tooltiplist" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>" data-toggle="tooltip" data-placement="bottom"  title="<?php echo html_escape($prod['name']);?>">
                                                                <?php echo substr_replace( $prod_name, "...", 35);?>
                                                            </a>
                                                        <?php  
                                                            }else{
                                                        ?>
                                                            <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                                                <?php echo $prod_name;?>
                                                            </a>
                                                        <?php
                                                            }
                                                        ?>
                                                    
                                                    <script>
                                                        $(document).ready(function(){
                                                            $('[rel=tooltiplist]').tooltip({
                                                                placement : 'top'
                                                            });
                                                        });                                                     
                                                    </script>
                                                </p>
                                                <p class="p-list-item-category">
                                                    Electronics and Gadgets
                                                </p>
                                                <div class="div-list-desc-container">
                                                    Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
                                                </div>
                                            </td>
                                            <td width="25%" class="td-list-price">
                                                <p class="p-list-price">
                                                    P <?php echo html_escape($prod['price'])?>
                                                </p>
                                                <div class="clear"></div>
                                                <p class="p-list-discount">
                                                    <s> P 1,200.00 </s>
                                                </p>
                                                <p class="p-discount">
                                                    <span><s> P 1200.00 </s></span>
                                                </p>
                                                
                                                <center>
                                                    <button class="btn btn-default-cart">
                                                        <span class="fa fa-shopping-cart"></span> ADD TO CART
                                                    </button>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
--><!--
                <center>
                    <ul class="pagination pagination-items">
                        <li class="disabled"><a href="#"><span>&laquo;</span></a></li>
                        <li class="active"><a href="#"><span>1</span></a></li>
                        <li><a href="#"><span>2</span></a></li>
                        <li><a href="#"><span>3</span></a></li>
                        <li><a href="#"><span>4</span></a></li>
                        <li><a href="#"><span>5</span></a></li>
                        <li><a href="#"><span>6</span></a></li>
                        <li><a href="#"><span>7</span></a></li>
                        <li><a href="#"><span>&raquo;</span></a></li>
                    </ul>
                </center>
    -->
            </div>
        </div>
        
    </div>
    </div>
    
</section>

<script type="text/javascript">
    var jsonCity = <?php echo $jsonCity;?>;
</script>

<script src="/assets/js/src/bootstrap.js" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script src="/assets/js/src/vendor/chosen.jquery.min.js" type="text/javascript"></script>
<script src='/assets/js/src/vendorpage_new.js' type="text/javascript"></script>

