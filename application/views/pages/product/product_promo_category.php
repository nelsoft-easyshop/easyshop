<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 


<div class="clear"></div>


<div class="wrapper" id="main_search_container">

    <!-- start of countdown sales -->
    <div class="countdown_category_banner">
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
        <div class='cd_timerlabel_container'><span class='cd_timerlabel'><?php echo (strtotime(date('M d,Y H:i:s')) < strtotime($startdate))?'STARTS IN':'TIME LEFT';?></span></div>
    </div>
    <div class="cd_announcement">
        Due to our users' popular demand, we are now opening our promo items for our regular payment methods 
        like <br /><strong>DragonPay</strong> and <strong>COD (Cash-on-Delivery)</strong>.
    </div>
    <div class="cd_product_container">
        <div class='product_list'>
            <?php foreach($items as $item): ?>
                <div class="cd_product">
                    <?php if($item['is_soldout']): ?>
                        <a href="javascript:void(0)" style='cursor: default;' class="cd_link_con"> 
                    <?php else: ?>
                        <a href="<?=base_url().'item/'.$item['slug'];?>" class="cd_link_con"> 
                    <?php endif; ?> 
                    <div class="product_buy_con">
                            <span><span class="orange_btn3 <?php echo $item['is_soldout']||(!$item['start_promo'])?'disabled':'enabled';?>">BUY NOW</span></span>
                    </div>
                    <?php if($item['is_soldout']): ?>
                        <div class="cd_soldout">
                            <p>SOLDOUT</p>
                        </div>
                    <?php endif; ?>
                    <div>
                        <span class="cd_slide_discount">
                            <span><?php echo ($item['start_promo'])?number_format($item['percentage'],0,'.',','):'2';?>%<br>OFF</span>
                        </span>
                    </div>
                    
                        <span class="cd_prod_img_con">
                            <img src="<?= base_url().$item['path'].'categoryview/'.$item['file']; ?>">
                        </span>
                    
                    <h3>
                       <?php echo  es_string_limit(html_escape($item['name']), 54, '...');?>
                    </h3>
                    <div class="price-cnt">
                        <?php if(($item['start_promo'])):  ?>
                            <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                            <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                        <?php else: ?>
                            <div class="price"><span style='font-size:11px; font-weight:bold;'>as low as </span>PHP <?php echo number_format($item['original_price']*(1-0.99),2,'.',',');?>*</div>
                            <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                            <div></div>
                       <?php endif;  ?>
   
                    </div>
                    <div class="cd_condition">
                        Condition: <?=strtoupper(html_escape($item['condition']));?>
                    </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>


<input type = 'hidden' id='timer_date' value='<?php echo (strtotime(date('M d,Y H:i:s')) < strtotime($startdate))?$startdate:$enddate; ?>'/>

<script src="<?=base_url()?>/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script>
    
    
    $(document).ready(function(){
        var base_url = config.base_url;
        var offset = 1;
        var request_ajax = true;
        var ajax_is_on = false;
        var objHeight = $(window).height() - 50;
        var last_scroll_top = 0;
    
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $(window).scroll(function(event) {
            var st = $(this).scrollTop();
            if(st > last_scroll_top){
                if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {
                    if (request_ajax === true && ajax_is_on === false) {
                        ajax_is_on = true; 
                        $.ajax({
                            url: base_url + 'deals_more',
                            data:{page_number:offset,csrfname : csrftoken},
                            type: 'post',
                            dataType: 'JSON',
                            onLoading:jQuery(".loading_products").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                            success: function(d) {
                                if(d == "0"){
                                    ajax_is_on = true;
                                }else{ 
                                    $($.parseHTML(d.trim())).appendTo($('.product_list'));
                                    ajax_is_on = false;
                                    offset += 1;   
                                }
                               jQuery(".loading_products").fadeOut();    
                            }
                        });
                    }
                }
            }
            last_scroll_top = st;
        });


        var timer_date = new Date($('#timer_date').val());
        $('.cd_timer_container').countdown({
            until : timer_date,
            serverSync: serverTime,
            layout: ' <div class="cd_timer_days"><span id="countdown_days">{dnn}</span> <span>DAYS</span> </div>'+
                    ' <div class="cd_timer_hours"><span id="countdown_hours">{hnn}</span> <span>HOURS</span> </div>'+
                    ' <div class="cd_timer_minutes"><span id="countdown_minutes">{mnn}</span> <span>MINUTES</span> </div>' +
                    ' <div class="cd_timer_seconds"><span id="countdown_second">{snn}</span> <span>SECONDS</span> </div>',
            onExpiry: reload,
        });
    })

</script>