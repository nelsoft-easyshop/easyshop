<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />
<link type="text/css" href='<?=base_url()?>assets/css/main-style.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='<?=base_url()?>assets/css/bootstrap.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='<?=base_url()?>assets/css/font-awesome/css/font-awesome.min.css' rel="stylesheet" media='screen'/>

<header class="new-header-con">
    <div class="main-container">
        <div>
            <a href="<?=base_url()?>">
                <img src="<?=base_url()?>assets/images/img_logo.png" alt="Easyshop.ph Logo">
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
        <div>
            <div>
                <span class="cart-items-con">
                    <span class="cart-item">2 item(s)</span> in your cart
                </span>
                <span class="cart-icon-con fa fa-shopping-cart"></span>
            </div>
        </div>
        <div>
            <div class="vendor-login-con">
                <img src="<?=base_url()?>assets/images/img-default-icon-user.jpg"> 
                <a href=""><strong>login</strong></a>  or 
                <a href=""><strong>Create and account</strong></a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</header>

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
                            <img src="<?=base_url()?>assets/images/img-default-vendor-profile-photo.jpg">
                        </div>
                    </div>
                </div>
                <div>
                    <h4><?php echo $storeNameDisplay?></h4>
                    <p><strong>Contact No. :</strong><?php echo strlen($arrVendorDetails['contactno']) > 0 ? $arrVendorDetails['contactno'] : "N/A" ?></p>
                    <p>
                        <span class="glyphicon glyphicon-map-marker"></span>
                        <?php if($hasAddress):?>
                            <span class="cl-1"><strong><?php echo $arrVendorDetails['cityname'] . ", " . $arrVendorDetails['stateregionname']?></strong></span>
                        <?php else:?>
                            <span class="cl-1"><strong>Location not set</strong></span>
                        <?php endif;?>
                    </p>
                    <div class="vendor-profile-btn">
                        <a href="" class="btn btn-default-3">
                            <img src="<?=base_url()?>assets/images/img-vendor-icon-edit.jpg"> Edit Profile
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
                                <div><a href="">Upload Photo</a></div>
                                <div><a href="">Remove Photo</a></div>
                            </div>
                            <img src="<?=base_url()?>assets/images/img-default-vendor-profile-photo.jpg" alt="Profile Photo">
                        </div>
                    </div>
                </div>
                <div class="pd-lr-20">
                    <input type="text" class="form-control mrgn-bttm-8 seller-name" placeholder="Seller Name">
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

<section class="sticky-nav-bg">
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
                    <select class="ui-form-control">
                        <option>On Seller's Page</option>
                        <option>Main Page</option>
                        <option>Other Page</option>
                    </select>
                    <input type="text" class="ui-form-control">
                    <input type="submit" value="" class="span_bg">
                </li>
                <li>
                    <div>
                        <span class="cart-items-con">
                            <span class="cart-item">2 item(s)</span> in your cart
                        </span>
                        <span class="cart-icon-con fa fa-shopping-cart"></span>
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
                </div>
                <?php $divCounter++; endforeach;?>

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
-->
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
            </div>
        </div>
        
    </div>
    </div>
</section>

<script type="text/javascript" src='<?=base_url()?>assets/js/src/vendorpage_new.js'></script>
<script src="<?= base_url() ?>assets/js/src/jquery-1.8.2.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/bootstrap.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

    //create a stick nav
    var menuOffset = $('.vendor-sticky-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
    $(document).bind('ready scroll', function() {
        var docScroll = $(document).scrollTop();
        if (docScroll >= 455) 
            {
                if (!$('.vendor-sticky-nav').hasClass('sticky-nav-fixed')) {
                    $('.vendor-sticky-nav').addClass('sticky-nav-fixed').css({
                        top: '-155px'
                    }).stop().animate({
                        top: 0
                    }, 500);
                    
                }

                $('.vendor-content-wrapper').addClass('fixed-vendor-content');

            } 
        else 
            {
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

    
})(jQuery);



	 $(document.body).on('click','.icon-grid',function() {
			var view = $("div.view").attr("class");
		
			if(view == "view row row-items list")
			{
				$('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
				$('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-xs-3 thumb");
				$('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
				$('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
			}
			
			
		});
	$(document).on('click','.icon-list',function() {	
			
			var view = $("div.view").attr("class");
			if(view == "view row row-items grid")
			{
				
				$('div.view').removeClass("view row row-items grid").addClass("view row row-items list");
				$('div.col-lg-3').removeClass("col-xs-3").addClass("col-md-12 thumb");
				$('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view").addClass("gv fa fa-th-large fa-2x icon-view icon-grid");
				$('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list").addClass("lv fa fa-th-list fa-2x icon-view icon-list active-view");
			};
	});
	
	
	
	var $window = $(window),
       $stickyLeft = $('#the-sticky-div'),
       leftTop = $stickyLeft.offset().top;

	   $window.scroll(function() {
			$stickyLeft.toggleClass('sticky', $window.scrollTop() > leftTop);
		});
	
</script>
