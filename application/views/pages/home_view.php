<link href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link rel="canonical" href="<?php echo base_url();?>"/>

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
                    <area style='color:' shape="rect" coords="<?=$row['imagemap']['coordinate']?>" href="<?= $row['imagemap']['target']?>" alt="<?=$row['imagemap']['target']?>" target="_blank">
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
                        <span class="span_bg home_hot_item">HOT ITEM</span>

                        <a href="<?= base_url().'item/'.$row['slug'];?>">
                            <span class="mid_img_con">
                                <img src="<?= base_url().$row['path'].'categoryview/'.$row['file'];?>" alt="<?php echo html_escape($row['product_name']);?>">
                            </span>
                            <h2><?php echo html_escape($row['product_name']);?></h2>
                            <span class="mid_con_price">&#8369;<?php echo number_format($row['price'],2,'.',',');?> 
                                <small class="span_bg c_small_btn"></small>
                            </span>
                           
                        </a>
                    </div>
                
                <?php endfor; ?>
                
            </div>
           
        </div>
        
        <!-- Start Right -->

        <div class="right_panel">
            <div>
                <a href='/gadgetsgalore'><img src="/assets/images/gadgets-galore-thumbnail.jpg" alt="Gadgets Galore : Buy the latest gadgets at the lowest price"></a>
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
                <object type="application/x-shockwave-flash" data="<?= base_url() ?>assets/images/Set-A-Box.swf" width="198" height="179" WMODE="transparent">
                    <param name="movie" value="<?= base_url() ?>assets/images/Set-A-Box.swf" />
                    <param name="quality" value="high"/>
             </object>
          
            </div>
        </div>       
    </div>
</section>

<div class="clear before-feed"></div>

    <?php
    if(!$this->session->userdata('member_id')):
        foreach($sections as $section): ?>
            <section>
                <?php echo $section;?>
            </section>
    <?php 
        endforeach;
    endif; ?>
                
<div class="clear"></div>



<script src="<?=base_url()?>assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/home.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>

<script type="text/javascript">
$("body").bind("unloadingChild", function(){
    console.log("child unloaded");
});



$( document ).ready(function( $ ) {
    
    var ids;

    function loadFeed(postids)
    {
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var itmcount;
        $.ajax({ 
            type: "POST",
            url: config.base_url+'product/getFeed', 
            data: "ids=" + postids + "&"+csrfname+"=" + csrftoken,
            dataType : "json",
            success: function(data){
                request_ajax = true; 
                console.log('loading feed complete.');
                // $( data.html ).insertAfter( $('.before-feed') );
                itmcount = data.count;
                if(itmcount <= 0){
                    ajax_is_on = true;
                }else{
                    ajax_is_on = false;  
                    $('.home_feed_items').append(data.html);
                    ids = data.product_ids;
                }
            }
        });   
    }

    console.log('ready');
    <?php if($this->session->userdata('member_id')): ?>
        console.log('loading feed.');
        $('<section><div class="wrapper category_section"><div class=" home_cat_product_title red_cat"><a>FEED</a></div><div class="home_feed_items"></div></div></section><br/>').insertAfter( $('.before-feed') );
        loadFeed(0);

        // START OF INFINITE SCROLLING FUNCTION  
        var ajax_is_on = false; 
        var last_scroll_top = 0;
        var itmcnt;
     
        $(window).scroll(function(event) {
            var st = $(this).scrollTop();
            if(st > last_scroll_top){
                if ($(window).scrollTop() + 500 > $(document).height() - $(window).height()) {
                    if (request_ajax === true && ajax_is_on === false) {
                        request_ajax = false; 
                        itmcnt = loadFeed(ids);  
                    }
                }
            }
            last_scroll_top = st;
        });
        // END OF INFINITE SCROLLING FUNCTION
    <?php endif; ?>
});

</script>