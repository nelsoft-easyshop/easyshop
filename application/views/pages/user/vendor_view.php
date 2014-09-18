<link type="text/css" href='<?=base_url()?>assets/css/main-style.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='<?=base_url()?>assets/css/bootstrap.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='<?=base_url()?>assets/css/font-awesome/css/font-awesome.min.css' rel="stylesheet" media='screen'/>

<section>
    <div class="pos-rel">
        <div class="vendor-main-bg">
            <img src="<?=base_url()?>assets/images/sample-vendor-img.jpg">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <img src="<?=base_url()?><?php echo $imgAvatar;?>">
                        </div>
                    </div>
                </div>
                <div>
                    <h4><?php echo $storeNameDisplay;?></h4>
                    <p><strong>Contact No. :</strong><?php echo strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "N/A";?></p>
                    <p>
                        <span class="glyphicon glyphicon-map-marker"></span>
                        <?php if( $hasAddress ):?>
                            <span class="cl-1"><strong><?php echo $arrVendorDetails['cityname'] . ', ' . $arrVendorDetails['stateregionname']?></strong></span>    
                        <?php else:?>
                            <span class="cl-1"><strong>Location not set</strong></span>    
                        <?php endif;?>
                    </p>
                    <div class="vendor-profile-btn">
                        <a href="" class="btn btn-default-2">
                            <span class="glyphicon glyphicon-plus-sign"></span> Follow
                        </a>
                        <a href="" class="btn btn-default-1">
                            <span class="glyphicon glyphicon-envelope"></span> Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pos-rel" style="display:none;">
        <div class="vendor-main-bg">
            <div class="edit-cover-photo">
                <a href="">
                    <img src="<?=base_url()?>assets/images/img-default-cover-photo.png" alt="Change Cover Photo"><br />
                    <h4><strong>Change Cover Photo</strong></h4>
                </a>
            </div>
            <img src="<?=base_url()?>assets/images/sample-vendor-img.jpg" alt="sample cover photo">
        </div>
        <div class="main-container vendor-main pos-ab">
            <div class="vendor-profile-content">
                <div class="pd-lr-20">
                    <div class="vendor-profile-img">
                        <div class="vendor-profile-img-con">
                            <div class="edit-profile-photo">
                                <div>
                                    <img src="<?=base_url()?>assets/images/img-default-cover-photo.png" alt="Edit Profile Photo">
                                    <span>Change Profile Photo</span>
                                </div>
                            </div>
                            <div class="edit-profile-photo-menu">
                                <div>Upload Photo</div>
                                <div>Remove Photo</div>
                            </div>
                            <img src="<?=base_url()?>assets/images/mg-default-cover-photo.jpg" alt="Profile Photo">
                        </div>
                    </div>
                </div>
                <div class="pd-lr-20">
                    <input type="text" class="form-control mrgn-bttm-8 mrgn-top-10" placeholder="Seller Name">
                    <input type="text" class="form-control mrgn-bttm-8" placeholder="Contact No.">
                    <div class="mrgn-bttm-8 edit-vendor-location">
                        <input type="text" class="ui-form-control">
                        <input type="text" class="ui-form-control">
                    </div>
                    <div class="vendor-profile-btn edit-profile-btn">
                        <a href="" class="btn btn-default-1">Cancel</a>
                        <a href="" class="btn btn-default-3">Save Changes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
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
                    <select>
                        <option>On Seller's Page</option>
                        <option>Main Page</option>
                        <option>Other Page</option>
                    </select>
                    <input type="text">
                    <input type="submit" value="" class="span_bg">
                </li>
                <li>
                    <div>
                        <span class="cart-items">
                            2 item(s) in your cart
                        </span>
                        <span class="cart-icon-con glyphicon glyphicon-shopping-cart"></span>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</section>

<div class="clear"></div>

<section class="bg-product-section color-default">
    <div class="container">
    <div class="row row-products">
        <div class="col-md-3 border-1">
            <?php foreach( $defaultCatProd as $catId=>$arrCat ):?>
                <div data-link="#def-<?php echo $catId?>" class="tab_categories"><?php echo $arrCat['name']?></div>
            <?php endforeach;?>
        </div>
        <div class="col-md-9 col-products">
            <div class="div-products">
                <div class="div-product-view-option">
                    <table class="p-view color-default pull-left">
                        <tr>
                            <td class="td-view p-view color-default">VIEW STYLE:</td>
                            <td class="td-view" style="padding-top: 3px;"><span class="fa fa-th-large fa-2x icon-view icon-grid"></span> <span class="fa fa-th-list fa-2x icon-view icon-list"></span></td>
                        </tr>
                    </table>
                    
                    <select class="form-select-default color-default pull-right">
                        <option>Default Sorting</option>
                        <option>Best Match</option>
                        <option>Hot</option>
                    </select>
                </div>
                <div class="clear"></div>
                

                <?php 
                    $divCounter = 0;
                    foreach($defaultCatProd as $catId => $arrCat):
                ?>
                <div class="view row row-items grid div_product" id="def-<?php echo $catId?>" style="display:<?php echo $divCounter>0 ? 'none' : ''?>">
                    <?php if($arrCat['non_categorized_count'] === 0): ?>
                        <span>No items available for this category.</span>
                    <?php else:?>
                        <?php foreach($arrCat['products'] as $objProduct):?>
                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                <div class="panel-item">
                                    <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $objProduct->getSlug()?>">
                                        <div class="div-item">
                                            <span class="span-img-wrapper" style="background: url(<?=base_url()?><?php echo $arrCat['product_images'][$objProduct->getIdProduct()]?>) center no-repeat; background-cover: cover;">
                                                <center>
                                                    <div class="span-img-container">
                                                        
                                                    </div>
                                                </center>
                                            </span>
                                        </div>
                                    </a>
                                    <div class="div-item-info">
                                        <p class="p-item-name">
                                            <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $objProduct->getSlug()?>">
                                                <?php 
                                                    $prod_name = html_escape($objProduct->getName());
                                                    if(strlen($prod_name)>17){
                                                        
                                                        echo substr_replace( $prod_name, "...", 17);
                                                    
                                                    }else{
                                                        echo $prod_name;
                                                    }
                                                ?>
                                            </a>
                                        </p>
                                        <p class="p-category">
                                            Clothes and Accessories
                                        </p>
                                        <div class="div-amount">
                                            <p class="p-price">
                                                <span><s>  </s></span> P <?php echo html_escape($objProduct->getPrice())?>
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
                    <?php endif;?>



<!--
                    <?php if($product_count > 0):?>
                        <?php foreach($products as $catID=>$p):?>
                            <?php foreach($p['products'] as $prod):?>
                                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                    <div class="panel-item">
                                        <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                            <div class="div-item">
                                                <span class="span-img-wrapper" style="background: url(<?=base_url()?><?php echo $prod['product_image_path']?>) center no-repeat; background-cover: cover;">
                                                    <center>
                                                        <div class="span-img-container">
                                                            
                                                        </div>
                                                    </center>
                                                </span>
                                            </div>
                                        </a>
                                        <div class="div-item-info">
                                            <p class="p-item-name">
                                                <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                                    <?php 
                                                        $prod_name = html_escape($prod['name']);
                                                        if(strlen($prod_name)>17){
                                                            
                                                            echo substr_replace( $prod_name, "...", 17);
                                                        
                                                        }else{
                                                            echo $prod_name;
                                                        }
                                                    ?>
                                                </a>
                                            </p>
                                            <p class="p-category">
                                                Clothes and Accessories
                                            </p>
                                            <div class="div-amount">
                                                <p class="p-price">
                                                    <span><s>  </s></span> P <?php echo html_escape($prod['price'])?>
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
-->


                </div>
                <?php $divCounter++; endforeach;?>















<!--
                <div class="view row row-items grid" id="fuck">
                    <?php if($product_count > 0):?>
                        <?php foreach($products as $catID=>$p):?>
                            <?php foreach($p['products'] as $prod):?>
                                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                    <div class="panel-item">
                                        <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                            <div class="div-item">
                                                <span class="span-img-wrapper" style="background: url(<?=base_url()?><?php echo $prod['product_image_path']?>) center no-repeat; background-cover: cover;">
                                                    <center>
                                                        <div class="span-img-container">
                                                            
                                                        </div>
                                                    </center>
                                                </span>
                                            </div>
                                        </a>
                                        <div class="div-item-info">
                                            <p class="p-item-name">
                                                <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                                    <?php 
                                                        $prod_name = html_escape($prod['name']);
                                                        if(strlen($prod_name)>17){
                                                            
                                                            echo substr_replace( $prod_name, "...", 17);
                                                        
                                                        }else{
                                                            echo $prod_name;
                                                        }
                                                    ?>
                                                </a>
                                            </p>
                                            <p class="p-category">
                                                Clothes and Accessories
                                            </p>
                                            <div class="div-amount">
                                                <p class="p-price">
                                                    <span><s>  </s></span> P <?php echo html_escape($prod['price'])?>
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
-->

            </div>
        </div>
        
    </div>
    </div>
</section>

<script type="text/javascript" src='<?=base_url()?>assets/js/src/vendorpage_new.js'></script>
