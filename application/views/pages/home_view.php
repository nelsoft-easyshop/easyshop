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
                <?PHP foreach ($data['mainSlide'] as $idx=>$row): ?>
                <li><img src="<?= base_url().$row['src'].'?ver='.ES_FILE_VERSION ?>"  usemap=" <?php echo isset($row['imagemap'])?'#'.$idx.'_image_map':''?>"/></li>
                <?PHP endforeach; ?>
            </ul>

            <?PHP foreach ($data['mainSlide'] as $idx=>$row): ?>
           
                <map name='<?php echo $idx?>_image_map'>
                    <!-- COORDS: left, top, right, bottom -->
                    <area style='color:' shape="rect" coords="<?=$row['imagemap']['coordinate']?>" href="<?= base_url().$row['imagemap']['target']?>" alt="<?=$row['imagemap']['target']?>">
                </map>
                
            <?PHP endforeach; ?>
            
            <!--
            <h2><?=$data['productSlide_title'] ?></h2>
            <ul class="mid_slide2">
                <?PHP foreach ($data['productSlide'] as $rows): ?>
                  <li>
                    <a href ="<?=base_url()."item/".$rows['slug']; ?>" >
                        <span class="mid_bottom_img_con">
                            <span class="mid_bottom_img">
                                <img src="<?=  base_url().$rows['path'].'categoryview/'.$rows['file']; ?>" />
                            </span>
                        <span>
                    </a>
                    </li>
                 <?php endforeach; ?>
             </ul>
             -->
                                       
            <div class="middle_content_items">

                <?PHP for($i = 0; $i < 3; $i++): ?>
                    <?PHP $row = $data['productSlide'][$i]; ?>
                    <div>
                        <a href="<?= base_url().'item/'.$row['slug'];?>"><h2><?php echo html_escape($row['product_name']);?></h2>
                            <span class="mid_con_price">&#8369;<?php echo number_format($row['price'],2,'.',',');?></span>
                            <span class="mid_img_con">
                                <img src="<?= base_url().$row['path'].'categoryview/'.$row['file'];?>" alt="<?php echo html_escape($row['product_name']);?>">
                            </span>
                        </a>
                    </div>
                
                <?php endfor; ?>
                
            </div>
           
        </div>
        
        <!-- Start Right -->

        <div class="right_panel">
            <div>
                <a href='<?=base_url()?>deals'><img src="<?= base_url() ?>assets/images/img_easy_treat.jpg" alt="Easy Treat: Best price offer for the day"></a>
            </div>
            
            <div class="easy_treat_item">
                <a href="<?= base_url().'item/'.$data['productSideBanner']['slug'];?>">
                    <h2><?php echo es_string_limit(html_escape($data['productSideBanner']['product_name']), 48); ?></h2>
                    <span>
                        <img src="<?= base_url().$data['productSideBanner']['path'].'categoryview/'.$data['productSideBanner']['file']; ?>" alt="<?php echo html_escape($data['productSideBanner']['product_name']); ?>">
                    </span>
                </a>
            </div>
            
            <div>
                <object type="application/x-shockwave-flash" data="<?= base_url() ?>assets/images/companion-site.swf" width="198" height="179" WMODE="transparent">
                    <param name="movie" value="<?= base_url() ?>assets/images/companion-site.swf" />
                    <param name="quality" value="high"/>
             </object>
             <!-- <iframe src="<?= base_url() ?>assets/images/Set-A-Box.swf" width="198" height="179"></iframe> -->
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
                        <h2><?=html_escape($data['category1_pid_main']['product_name']);?></h2>
                    </a>
              
                    <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid_main']['price'],2,'.',',');?></span> onwards</p>
                </div>
            </div>
                <div class="products border2">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][0]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][0]['path'].'categoryview/'.$data['category1_pid'][0]['file']; ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][0]['product_name']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][0]['price'],2,'.',',');?> </span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][1]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][1]['path'].'categoryview/'.$data['category1_pid'][1]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][1]['product_name']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][1]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
                <div class="products border2">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][2]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][2]['path'].'categoryview/'.$data['category1_pid'][2]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][2]['product_name']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][2]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][3]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][3]['path'].'categoryview/'.$data['category1_pid'][3]['file']?>"></span>
                            <h2><?=html_escape($data['category1_pid'][3]['product_name']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][3]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                </div>
                <div class="products">
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][4]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][4]['path'].'categoryview/'.$data['category1_pid'][4]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][4]['product_name']);?></h2>
                        </a>
                        <p>Price: <span>&#8369;<?php echo number_format($data['category1_pid'][4]['price'],2,'.',',');?></span> onwards</p>
                    </div>
                    <div>
                        <a href="<?= base_url()."item/".$data['category1_pid'][5]['slug']; ?>">
                            <span class="home_product_img_container"><img src="<?= base_url().$data['category1_pid'][5]['path'].'categoryview/'.$data['category1_pid'][5]['file'] ?>"></span>
                            <h2><?=html_escape($data['category1_pid'][5]['product_name']);?></h2>
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
                        <h2><?=html_escape($data['category2_pid'][1]['product_name']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][1]['price'],2,'.',',');?></span> onwards</p>
                </div>
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][2]['slug']; ?>">
                        <span class="home_product_img_container"><img src="<?= base_url().$data['category2_pid'][2]['path'].'categoryview/'.$data['category2_pid'][2]['file']; ?>"></span>
                        <h2><?=html_escape($data['category2_pid'][2]['product_name']);?></h2>
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
                                <h2><?=html_escape($data['category2_pid_mainslide'][0]['product_name']);?></h2>
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
                                <h2><?=html_escape($data['category2_pid_mainslide'][1]['product_name']);?></h2>
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
                                <h2><?=html_escape($data['category2_pid_mainslide'][2]['product_name']);?></h2>
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
                        <h2><?=html_escape($data['category2_pid'][3]['product_name']);?></h2>
                    </a>
                    <p>Price: <span>&#8369;<?php echo number_format($data['category2_pid'][3]['price'],2,'.',',');?></span> onwards</p>
                </div>
                <div>
                    <a href="<?= base_url()."item/".$data['category2_pid'][0]['slug']; ?>">
                        <span class="home_product_img_container"><img src="<?= base_url().$data['category2_pid'][0]['path'].'categoryview/'.$data['category2_pid'][0]['file'];  ?>"></span>
                        <h2><?=html_escape($data['category2_pid'][0]['product_name']);?></h2>
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
<script type="text/javascript">
$("body").bind("unloadingChild", function(){
    console.log("child unloaded");
});
</script>