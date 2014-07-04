<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 


<div class="clear"></div>


<div class="wrapper" id="main_search_container">

    <?php echo $deals_banner; ?>
    
    <div class="cd_product_container" id="promo1">
        <div class='product_list'>
            <?php foreach($items as $item): ?>
                <div class="cd_product">
                
                    <?php if($item['is_soldout']): ?>
                        <a href="javascript:void(0)" style='cursor: default;' class="cd_link_con"> 
                    <?php else: ?>
                        <a href="<?=base_url().'item/'.$item['slug'];?>" class="cd_link_con"> 
                    <?php endif; ?> 
                    <?php if(intval($item['is_promote']) === 1): ?> 
                  
                        <div class="product_buy_con">
                                <span><span class="orange_btn3 <?php echo $item['is_soldout']||(!$item['start_promo'])?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>
                      
                    <?php else: ?>
                         <div class="product_buy_con">
                                <span><span class="orange_btn3 <?php echo $item['is_soldout']?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>
                    <?php endif; ?>
                    
                      <?php if($item['is_soldout']): ?>
                            <div class="cd_soldout">
                                <p>SOLDOUT</p>
                            </div>
                     <?php endif; ?>
                    
                    
                    <div>
                        
                    <span class="cd_slide_discount">
                        <?php if(intval($item['is_promote']) === 1): ?> 
                            <span><?php echo ($item['start_promo'])?number_format($item['percentage'],0,'.',','):'2';?>%<br>OFF</span>
                        <?php else: ?>
                            <span><?php echo number_format($item['percentage'],0,'.',',');?>%<br>OFF</span>
                        <?php endif; ?>
                    </span>
                        
                    </div>
                    
                        <span class="cd_prod_img_con">
                            <img src="<?= base_url().$item['path'].'categoryview/'.$item['file']; ?>">
                        </span>
                    
                    <h3>
                       <?php echo  es_string_limit(html_escape($item['name']), 54, '...');?>
                    </h3>
                    <div class="price-cnt">
                        <?php if(intval($item['is_promote']) === 1): ?> 
                            <?php if(($item['start_promo'])):  ?>
                                <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                                <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                            <?php else: ?>
                                <div class="price"><span style='font-size:11px; font-weight:bold;'>as low as </span>PHP <?php echo number_format($item['original_price']*(1-0.99),2,'.',',');?>*</div>
                                <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                                <div></div>
                           <?php endif;  ?>
                        <?php else: ?>
                            <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                            <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                        <?php endif; ?>
   
                    </div>
                    <div class="cd_condition">
                        Condition: <?=strtoupper(html_escape($item['condition']));?>
                    </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="promo2">
        easy treat Promo
    </div>

</div>

<script src="<?=base_url()?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
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


    })

</script>