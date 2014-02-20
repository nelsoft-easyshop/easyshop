<link href="<?= base_url() ?>assets/css/jquery.bxslider.css" rel="stylesheet" />


<div class="clear"></div>

<section>
    <div class="wrapper">
        <p class="announcement">[<?PHP echo $data['header']; ?>]<?PHP echo $data['text']; ?></p>
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
                <li><img style="width:520px;height:270px" src="<?= base_url().$rows ?>" /></li>
                <?PHP endforeach; ?>
            </ul>

            <h2><?=$data['productSlide_title'] ?></h2>
            <ul class="mid_slide2">
                <?PHP foreach ($data['productSlide_id'] as $rows): ?>
                  <li><a href ="<?=base_url()."item/".$rows['id_product']."/".es_url_clean($rows['product']); ?>.html" ><img style="height:90px; width: 90px;" src="<?=  base_url().$rows['path'].'categoryview/'.$rows['file']; ?>" /><br /><?=html_escape($rows['product']); ?></a></li> 
                <?PHP endforeach; ?>
            </ul>


            <div class="clear"></div>
        </div>

        <!-- Start Right -->

        <div class="right_panel">
            <div class="right_panel_box">
                <div class="sign_in_register">
                    <div>
                        <a href="<?=base_url()?>login" class="orange_btn"><img src="<?= base_url() ?>assets/images/icon_login.png"> Sign In</a>
                    </div>
                    <div>
                        <a href="<?=base_url()?>register" class="orange_btn">Register</a>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="guides_panel">
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><img src="<?= base_url() ?>assets/images/img_shopping_guide.png"><br />Shopping Guide</a>
                    </div>
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><img src="<?= base_url() ?>assets/images/img_q_and_a.png"><br />Q &amp; A</a>
                    </div>
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><img src="<?= base_url() ?>assets/images/img_shipping.png"><br />Shipping</a>
                    </div>
                    <div>
                        <a href="<?= base_url() ?>home/under_construction"><img src="<?= base_url() ?>assets/images/img_secure_payment.png"><br />Secure Payment</a>
                    </div>
                </div>
            </div>
            <div class="secure_payment">
                <p>Global secure payment by:</p>
                <div>
                    <img src="<?= base_url() ?>assets/images/img_mastercard.png">
                    <img src="<?= base_url() ?>assets/images/img_visa.png">
                    <img src="<?= base_url() ?>assets/images/img_jcb.png">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="clear"></div>

<section>
    <div class="wrapper fashion_products">
        <div class="product_title">
            <div><h2><?=$data['category1_title'] ?></h2></div>
            <!-- <div><span><a href="">view more</a></span></div> -->
        </div>
        <div class="clear"></div>
        <div class="border">
            <div>
                <img id="cat1_main_prod" src="<?=base_url().$data['category1_pid_main'][0]['path'].'small/'.$data['category1_pid_main'][0]['file']; ?>">
                <div id="cat_1_main_details">
                    <a href="<?= base_url()."item/".$data['category1_pid_main'][0]['id_product']."/".es_url_clean($data['category1_pid_main'][0]['product']); ?>.html">
                        <h2><?=html_escape($data['category1_pid_main'][0]['product']);?></h2>
                    </a>
              
                    <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid_main'][0]['price'],2,'.',',');?></span> onwards</p>
                </div>
            </div>
                <div class="products border2">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][0]['id_product']."/".es_url_clean($data['category1_pid'][0]['product']); ?>.html">
                            <img src="<?= base_url().$data['category1_pid'][0]['path'].'categoryview/'.$data['category1_pid'][0]['file']; ?>">
                            <h2><?=html_escape($data['category1_pid'][0]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][0]['price'],2,'.',',');?> </span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][1]['id_product']."/".es_url_clean($data['category1_pid'][1]['product']); ?>.html">
                            <img src="<?= base_url().$data['category1_pid'][1]['path'].'categoryview/'.$data['category1_pid'][1]['file'] ?>">
                            <h2><?=html_escape($data['category1_pid'][1]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][1]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
                <div class="products border2">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][2]['id_product']."/".es_url_clean($data['category1_pid'][2]['product']); ?>.html">
                            <img src="<?= base_url().$data['category1_pid'][2]['path'].'categoryview/'.$data['category1_pid'][2]['file'] ?>">
                            <h2><?=html_escape($data['category1_pid'][2]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][2]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][3]['id_product']."/".es_url_clean($data['category1_pid'][3]['product']); ?>.html">
                            <img src="<?= base_url().$data['category1_pid'][3]['path'].'categoryview/'.$data['category1_pid'][3]['file']?>">
                            <h2><?=html_escape($data['category1_pid'][3]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][3]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
                <div class="products">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][4]['id_product']."/".es_url_clean($data['category1_pid'][4]['product']); ?>.html">
                            <img src="<?= base_url().$data['category1_pid'][4]['path'].'categoryview/'.$data['category1_pid'][4]['file'] ?>">
                            <h2><?=html_escape($data['category1_pid'][4]['product']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][4]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][5]['id_product']."/".es_url_clean($data['category1_pid'][5]['product']); ?>.html">
                            <img src="<?= base_url().$data['category1_pid'][5]['path'].'categoryview/'.$data['category1_pid'][5]['file'] ?>">
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
                    <a href="<?= base_url()."item/".$data['category2_pid'][1]['id_product']."/".es_url_clean($data['category2_pid'][1]['product']); ?>.html">
                        <img src="<?= base_url().$data['category2_pid'][1]['path'].'categoryview/'.$data['category2_pid'][1]['file']; ?>">
                        <h2><?=html_escape($data['category2_pid'][1]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][1]['price'],2,'.',',');?></span> onwards</p>
                </div>
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][2]['id_product']."/".es_url_clean($data['category2_pid'][2]['product']); ?>.html">
                        <img src="<?= base_url().$data['category2_pid'][2]['path'].'categoryview/'.$data['category2_pid'][2]['file']; ?>">
                        <h2><?=html_escape($data['category2_pid'][2]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][2]['price'],2,'.',',');?></span> onwards</p>
                </div>
            </div>           
            <div class="product_slide">
                <ul class="slider3">
                    <li><a href="<?=base_url()."item/".$data['category2_pid_mainslide'][0]['id_product']."/".es_url_clean($data['category2_pid_mainslide'][0]['product']); ?>.html"><img class="cat2_slide_prod" src="<?=base_url().$data['category2_pid_mainslide'][0]['product_image_path'] ?>" /></a></li>
                    <li><a href="<?=base_url()."item/".$data['category2_pid_mainslide'][1]['id_product']."/".es_url_clean($data['category2_pid_mainslide'][1]['product']); ?>.html"><img class="cat2_slide_prod" src="<?=base_url().$data['category2_pid_mainslide'][1]['product_image_path'] ?>" /></a></li>
                    <li><a href="<?=base_url()."item/".$data['category2_pid_mainslide'][2]['id_product']."/".es_url_clean($data['category2_pid_mainslide'][2]['product']); ?>.html"><img class="cat2_slide_prod" src="<?=base_url().$data['category2_pid_mainslide'][2]['product_image_path'] ?>" /></a></li>
                </ul>
            </div>
            <div class="electronics_product_sides products border3">
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][3]['id_product']."/".es_url_clean($data['category2_pid'][3]['product']); ?>.html">
                        <img src="<?= base_url().$data['category2_pid'][3]['path'].'categoryview/'.$data['category2_pid'][3]['file'];  ?>">
                        <h2><?=html_escape($data['category2_pid'][3]['product']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][3]['price'],2,'.',',');?></span> onwards</p>
                </div>
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][0]['id_product']."/".es_url_clean($data['category2_pid'][0]['product']); ?>.html">
                        <img src="<?= base_url().$data['category2_pid'][0]['path'].'categoryview/'.$data['category2_pid'][0]['file'];  ?>">
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
<script src="<?= base_url() ?>assets/JavaScript/categorynavigation.js" type="text/javascript"></script>
<script src="<?= base_url().$page_javascript ?>" type="text/javascript"></script>
