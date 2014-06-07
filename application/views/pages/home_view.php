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

        <div class="middle_content2">
            
            <div class="countdown_container">
                <div class="countdown_top_content">
                    <div class="countdown_top_left_content">
                       <div class="cd_timer_container">
                        <div class="cd_timer_days">
                            <span id='countdown_days'>00</span>
                            <span>DAYS</span>
                        </div>
                        <div class="cd_timer_hours">
                            <span id='countdown_hours'>00</span>
                            <span>HOURS</span>
                        </div>
                        <div class="cd_timer_minutes">
                            <span id='countdown_minutes'>00</span>
                            <span>MINUTES</span>
                        </div>
                        <div class="cd_timer_seconds">
                            <span id="countdown_second">00</span>
                            <span>SECONDS</span> 
                        </div>
                       </div>
                    </div>
                    <div class="countdown_top_right_content">
                        <div class="cd_left_con">
                            <div class="cd_top_content <?php echo ($data['cd_product'][0]['is_soldout'])?'cd_main_slide_soldout':''?>">
                                <span class="cd_discount_tag ">
                                    <span>
                                        <?php echo ($data['cd_product'][0]['start_promo'])?($data['cd_product'][0]['percentage']):($data['cd_product'][0]['percentage']);  ?>%
                                        <br />OFF
                                    </span>
                                </span>

                            </div>
                            <div>
                                <p class="txt_buy">Buy an</p>
                                <p class="cd_prod_name"><a href="<?=base_url()?>item/<?=$data['cd_product'][0]['slug']?>"><?php echo html_escape($data['cd_product'][0]['product']);?></a></p>
                                <p class="cd_prod_base_price">Php <?php echo number_format($data['cd_product'][0]['original_price'],2,'.',',');?></p>
                                <?php if($data['cd_product'][0]['start_promo']):  ?>
                                    <p class="cd_prod_discount_price">Php <?php echo number_format($data['cd_product'][0]['price'],2,'.',',');?></p>
                                    <p class="cd_buy_btn"><a href="<?=base_url()?>item/<?=$data['cd_product'][0]['slug']?>  " class="orange_btn3">BUY NOW</a></p>
                                <?php else: ?>
                                    <p class="cd_prod_discount_price">Php <?php echo number_format($data['cd_product'][0]['original_price']*(1-0.99),2,'.',',');?>*</p>
                                    <p class='cd_prod_disclaimer'>* Lowest possible price</p>
                                    <p class="cd_buy_btn"><a href="" class="disable_btn">BUY NOW</a></p>
                                <?php endif;  ?>
                               
                            </div>
                        </div>                        
                        <div class="cd_right_con">
                        <img src="<?= base_url().$data['cd_product'][0]['path'].'categoryview/'.$data['cd_product'][0]['file']?>" alt="<?php echo html_escape($data['cd_product'][0]['product']);?>">
                        </div>     
                    </div>
                </div>
                <div class="clear"></div>
                <ul class="countdown_slides">
                        <?php $cnt = 0;
                        foreach($data['cd_product'] as $countdown_slide): 
                        $cnt++; ?>
                        <?php if($cnt === 1 ) continue; ?>
                        <li class='<?php echo ($countdown_slide['is_soldout'])?'cd_slide_soldout':''?>'>
                            <div>
                                <div>
                                    <span class="cd_slide_title"><a href="<?=base_url()?>item/<?=$countdown_slide['slug']?>"><?=html_escape($countdown_slide['product']);?></a></span>
                                    <span class="cd_slide_discount"><span> <?php echo ($countdown_slide['start_promo'])?($countdown_slide['percentage']):($countdown_slide['percentage']);  ?>%<br />OFF</span></span>
                                </div>
                                <div>
                                    <div class="cd_slide_bleft">
                                        <p class="cd_slide_base_price">Php <?php echo number_format($countdown_slide['original_price'],2,'.',','); ?></p>
                                        
                                        <?php if($data['cd_product'][1]['start_promo']):  ?>
                                            <p class="cd_slide_discount_price">Php <?php echo number_format($countdown_slide['price'],2,'.',','); ?></p>
                                            <p class="cd_slide_buy_btn"><a href="" class="orange_btn">BUY NOW</a></p>
                                        <?php else: ?>
                                            <p class="cd_slide_discount_price">Php <?php echo number_format($countdown_slide['original_price']*(1-0.99),2,'.',',');?>*</p>
                                            <p class='cd_prod_disclaimer'>* for as low as</p>
                                            <p class="cd_slide_buy_btn"><a href="<?=base_url()?>item/<?=$countdown_slide['slug']?>" class="disable_btn">BUY NOW</a></p>
                                        <?php endif;  ?>

                                    </div>
                                    <div class="cd_slide_rleft">
                                        <span>
                                            <img src="<?= base_url().$countdown_slide['path'].'categoryview/'.$countdown_slide['file']?>" alt="<?php echo html_escape($countdown_slide['product']);?>">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>


                <div class="clear"></div>
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
                <img id="cat1_main_prod" src="<?=base_url().$data['category1_pid_main']['path'].'small/'.$data['category1_pid_main']['file']; ?>">
                <div id="cat_1_main_details">
                    <a href="<?= base_url()."item/".$data['category1_pid_main']['slug']; ?>">
                        <h2><?=html_escape($data['category1_pid_main']['product']);?></h2>
                    </a>
              
                    <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid_main']['price'],2,'.',',');?></span> onwards</p>
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

<input type = 'hidden' id='timer_date' value='<?php echo (strtotime(date('M d,Y H:i:s')) < strtotime($data['cd_startdate']))?$data['cd_startdate']:$data['cd_enddate']; ?>'/>

<div class="clear"></div>

<script src="<?=base_url()?>assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/home.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="<?=base_url()?>/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>