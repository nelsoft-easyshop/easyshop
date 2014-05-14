<link href="<?= base_url() ?>assets/css/jquery.bxslider.css" rel="stylesheet" />

<div class="clear"></div>

<section>
    <div class="wrapper">
        <p class="announcement" style="color:#f28300"><strong><!--[<?PHP #echo $data['header']; ?>]--><?PHP echo $data['text']; ?></strong></p>
    </div>

</section>

<div class="clear"></div>
<section>
    <div class="wrapper">
        <div class="left_navigation">
			<div class="nav_title">Categories</div>
            <?php echo $category_navigation; ?> 
		</div>

        <div class="middle_content">
            <ul class="mid_slide1">
                <?PHP foreach ($data['mainSlide'] as $rows): ?>
                <li><img style="width:520px;height:270px" src="<?= base_url().$rows.'?ver=1.0' ?>" /></li>
                <?PHP endforeach; ?>
            </ul>

            <h2><?=$data['productSlide_title'] ?></h2>
            <ul class="mid_slide2">
                <?PHP foreach ($data['productSlide_id'] as $rows): ?>
                  <li>
                    <a href ="<?=base_url()."item/".$rows['slug']; ?>" >
                        <span class="mid_bottom_img_con">
                            <span class="mid_bottom_img">
                                <img src="<?=  base_url().$rows['path'].'categoryview/'.$rows['file']; ?>" />
                            </span>
                        </span>
                        <br />
                           <?=html_escape($rows['product']); ?> 
                    </a>
                </li> 
                <?PHP endforeach; ?>
            </ul>


            <div class="clear"></div>
        </div>

        <!-- Start Right -->

        <div class="right_panel">
            
            <div class="right_panel_box">
                <div class="sign_in_register">
                    <div>
                        <a href="<?=base_url()?>login" class="orange_btn"> Sign In</a>
                    </div>
                    <div>
                       <!-- <a href="<?=base_url()?>register" class="orange_btn"> Register</a> -->
                       <a href="<?=base_url()?>#register" class="orange_btn"> Register</a>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="guides_panel">
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><span class="span_bg shopping_guide"></span>Shopping Guide</a>
                    </div>
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><span class="q_and_a"></span>Q &amp; A</a>
                    </div>
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><span class="shipping"></span>Shipping</a>
                    </div>
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><span class="secure_payment"></span>Secure Payment</a>
                    </div>
                </div>
            </div>
            <br/>
            <div class="global_secure_payment">
                <p><strong>Payment Methods</strong></p>
                <div>
                    <span class="mastercard"></span>
                    <span class="visa"></span>
                    <span class="paypal"></span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="clear"></div>

<section>
    <div class="wrapper">
        <div class="product_title">
            <div><h2><?=$data['category1_title'] ?></h2></div>
            <!-- <div><span><a href="">view more</a></span></div> -->
        </div>
        <div class="clear"></div>
        <div class="border fashion_products">
            <div>
                <img id="cat1_main_prod" src="<?=base_url().$data['category1_pid_main'][0]['path'].'small/'.$data['category1_pid_main'][0]['file']; ?>">
                <div id="cat_1_main_details">
                    <a href="<?= base_url()."item/".$data['category1_pid_main'][0]['slug']; ?>">
                        <h2><?=html_escape($data['category1_pid_main'][0]['product']);?></h2>
                    </a>
              
                    <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid_main'][0]['price'],2,'.',',');?></span> onwards</p>
                </div>
            </div>
                <div class="products border2">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][0]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][0]['path'].'categoryview/'.$data['category1_pid'][0]['file']; ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][0]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][0]['price'],2,'.',',');?> </span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][1]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][1]['path'].'categoryview/'.$data['category1_pid'][1]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][1]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][1]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
                <div class="products border2">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][2]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][2]['path'].'categoryview/'.$data['category1_pid'][2]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][2]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][2]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][3]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][3]['path'].'categoryview/'.$data['category1_pid'][3]['file']?>"></span>
                            <h2><?=html_escape($data['category1_pid'][3]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][3]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
                <div class="products">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][4]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][4]['path'].'categoryview/'.$data['category1_pid'][4]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][4]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][4]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][5]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][5]['path'].'categoryview/'.$data['category1_pid'][5]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][5]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][5]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
        </div>
    </div>
</section>

<section>
    <div class="wrapper electronics_products">
        <div class="product_title">
            <div><h2><?=$data['category2_title'] ?></h2></div>
            <!-- <div><span><a href="">view more</a></span></div> -->
        </div>
        <div class="clear"></div>
        <div class="border">
            <div class="electronics_product_sides products border2">
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][1]['slug']; ?>">
                        <span class="home_product_img_container"><img src="<?= base_url().$data['category2_pid'][1]['path'].'categoryview/'.$data['category2_pid'][1]['file']; ?>"></span>
                        <h2><?=html_escape($data['category2_pid'][1]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][1]['price'],2,'.',',');?></span> onwards</p>
                </div>
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][2]['slug']; ?>">
                        <span class="home_product_img_container"><img src="<?= base_url().$data['category2_pid'][2]['path'].'categoryview/'.$data['category2_pid'][2]['file']; ?>"></span>
                        <h2><?=html_escape($data['category2_pid'][2]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][2]['price'],2,'.',',');?></span> onwards</p>
                </div>
            </div>           
            <div class="product_slide">
                <ul class="slider3">
                    <li>
                        <a href="<?=base_url()."item/".$data['category2_pid_mainslide'][0]['slug']; ?>">
                            <span class="elec_slide_img_con">
                            <img class="cat2_slide_prod" src="<?=base_url().$data['category2_pid_mainslide'][0]['path'].'/'.$data['category2_pid_mainslide'][0]['file']; ?>" />
                            </span>
                           
                        </a> 
                        <span class="electronics_slider_price_con">
                            <a href="<?=base_url()."item/".$data['category2_pid_mainslide'][0]['slug']; ?>">
                                <h2><?=html_escape($data['category2_pid_mainslide'][0]['product']);?></h2>
                            </a>
                            <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid_mainslide'][0]['price'],2,'.',',');?></span> onwards</p>
                        </span>
                    </li>
                    
                    <li>
                        <a href="<?=base_url()."item/".$data['category2_pid_mainslide'][1]['slug']; ?>">
                            <span class="elec_slide_img_con">
                            <img class="cat2_slide_prod" src="<?=base_url().$data['category2_pid_mainslide'][1]['path'].'/'.$data['category2_pid_mainslide'][1]['file']; ?>" />
                            </span>
                            
                        </a>
                        <span class="electronics_slider_price_con">
                            <a href="<?=base_url()."item/".$data['category2_pid_mainslide'][1]['slug']; ?>">
                                <h2><?=html_escape($data['category2_pid_mainslide'][1]['product']);?></h2>
                            </a>
                            <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid_mainslide'][1]['price'],2,'.',',');?></span> onwards</p>
                            </span>
                    </li>
                    
                    <li>
                        <a href="<?=base_url()."item/".$data['category2_pid_mainslide'][2]['slug']; ?>">
                            <span class="elec_slide_img_con">
                            <img class="cat2_slide_prod" src="<?=base_url().$data['category2_pid_mainslide'][2]['path'].'/'.$data['category2_pid_mainslide'][2]['file'] ?>" />
                            </span>
                            
                        </a>
                        <span class="electronics_slider_price_con">
                            <a href="<?=base_url()."item/".$data['category2_pid_mainslide'][2]['slug']; ?>">
                                <h2><?=html_escape($data['category2_pid_mainslide'][2]['product']);?></h2>
                            </a>
                            <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid_mainslide'][2]['price'],2,'.',',');?></span> onwards</p>
                            </span>
                    </li>
                </ul>
            </div>
            <div class="electronics_product_sides products border3">
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][3]['slug']; ?>">
                        <span class="home_product_img_container"><img src="<?= base_url().$data['category2_pid'][3]['path'].'categoryview/'.$data['category2_pid'][3]['file'];  ?>"></span>
                        <h2><?=html_escape($data['category2_pid'][3]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][3]['price'],2,'.',',');?></span> onwards</p>
                </div>
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][0]['slug']; ?>">
                        <span class="home_product_img_container"><img src="<?= base_url().$data['category2_pid'][0]['path'].'categoryview/'.$data['category2_pid'][0]['file'];  ?>"></span>
                        <h2><?=html_escape($data['category2_pid'][0]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][0]['price'],2,'.',',');?></span> onwards</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="clear"></div>

<script src="<?= base_url() ?>assets/JavaScript/js/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/JavaScript/categorynavigation.js?ver=1.0" type="text/javascript"></script>
<script src="<?= base_url().$page_javascript.'?ver=1.0'; ?>" type="text/javascript"></script>

