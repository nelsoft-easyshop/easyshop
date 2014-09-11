<link type="text/css" href='<?=base_url()?>assets/css/main-style.css' rel="stylesheet" media='screen'/>
<link type="text/css" href='<?=base_url()?>assets/css/bootstrap.css' rel="stylesheet" media='screen'/>

<section>
    <div class="vendor-main-bg">
        <img src="<?=base_url()?>assets/images/sample-vendor-img.jpg">
    </div>
    <div class="main-container vendor-main pos-ab">
        <div class="vendor-profile-content">
            <div class="pd-lr-20">
                <div class="vendor-profile-img">
                    <div class="vendor-profile-img-con">
                        <img src="<?=base_url()?>assets/images/img_logo_air21.jpg">
                    </div>
                </div>
            </div>
            <div>
                <h4>Air 21</h4>
                <p><strong>Contact No. :</strong>09171234567</p>
                <p>
                    <a href="">
                        <span class="glyphicon glyphicon-map-marker"></span>
                        <span class="cl-1"><strong>Location not set</strong></span>
                    </a>
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

            <div class="vendor_products_wrapper user-tab">
                <?php if($product_count > 0):?>
                    <?php foreach($products as $catID=>$p):?>
                    <div class="vendor_txt_prod_header" class="<?php echo $p['slug']?>">
                        <div class="home_cat_product_title" style="background-color:#0078d5;">
                            <a target="_blank" href="<?php echo $p['cat_link']?>" <?php echo $p['cat_link']==="" ? 'onclick="return false"':""?> >
                                <img src="<?=base_url()?><?php echo $p['cat_img']?>">
                                <h2><?php echo $p['name']?></h2> 
                            </a>   
                        </div>
                    </div>
                    <div class="vendor_prod_items">
                        <?php foreach($p['products'] as $prod):?>
                            <div class="product vendor_product">
                                <a target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                    <span class="prod_img_wrapper">
                                        <span class="prod_img_container">
                                           <img src="<?=base_url()?><?php echo $prod['product_image_path']?>">
                                        </span>
                                    </span>
                                </a>    
                                <h3>
                                    <a target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                       <?php echo html_escape($prod['name'])?>
                                    </a>
                                </h3>
                                 <div class="price-cnt">
                                    <div class="price">
                                        Php <?php echo html_escape($prod['price'])?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <div class="txt_load_more_con v_loadmore">
                        <a target="_blank" href="<?php echo $p['loadmore_link']?>" class="grey_btn">LOAD MORE ITEMS</a>
                    </div>
                    <?php endforeach;?>
                <?php endif;?>
                </div>



        </div>
    </div>
    
</section>


